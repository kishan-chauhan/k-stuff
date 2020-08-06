<?php
/*
Setting package
*/
ob_start();
function htree_internet_package(){

		if($_GET['action'] =='add-package' || $_GET['action'] =='edit'){
			htree_addpackage();
		}else{
			?>
			<div class="wrap">
				<h1>Package Settings | <a href="admin.php?page=package-settings&action=add-package">Add Package</a></h1>	
				<?php
				 if($_GET['msg'] == "successadd"){
					echo "<span class='success'>Successfully control added</span>";
				 }
				 if($_GET['msg'] == "successupdate"){
				 	echo "<span class='success'>Successfully control updated</span>";
 				 }
				 ?>
				<form method="post">
					<?php
					$data_obj = new Htree_Package_list();
					$record = $data_obj->prepare_items();
					$data_obj->display(); ?>
				</form>					
			</div>
			<?php
		}
}


//Package listing
class Htree_Package_list extends WP_List_Table {

		/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Package', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Control', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?

		] );
	}
	
	/**
	 * Retrieve snippets data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_records_all( $per_page = 10, $page_number = 1 ) {
		global $wpdb;
		$table_name  = Htree_Package_Tbl;		
		$sql = "SELECT * FROM $table_name  ";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}else{
			$sql .= ' ORDER BY package_id DESC' ;
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		return $result;
	}
	
	/**
	 * Delete a record.
	 *
	 * @param int $id
	 */
	public static function sp_delete_record( $id ) {
	  global $wpdb;

	  $wpdb->delete(
		Htree_Package_Tbl,
		[ 'package_id' => $id ],
		[ '%d' ]
	  );
	}
	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
	  global $wpdb;

	  $sql = "SELECT COUNT(*) FROM ".Htree_Package_Tbl."";

	  return $wpdb->get_var( $sql );
	}
	
	/** Text displayed when no record data is available */
	public function no_items() {
	  _e( 'No record available.', 'sp' );
	}

	// edit delete link
	function column_package_name($item) {
	  $actions = array(
				'edit'      => sprintf('<a href="?page=%s&action=%s&edit-package=%s">Edit</a>',$_REQUEST['page'],'edit',$item['package_id']),
				'delete'    => sprintf('<a href="?page=%s&action=%s&packagedelete=%s">Delete</a>',$_REQUEST['page'],'delete',$item['package_id']),
			);

	  return sprintf('%1$s %2$s', $item['package_name'], $this->row_actions($actions) );
	}

	/**
	 * Render a column when no column specific method exists.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {		
	  switch ( $column_name ) {
		case 'package_name':
			return esc_html( $item[ $column_name ] );		
		case 'package_price':
			return esc_html( $item[ $column_name ] );	
		case 'package_data':
			return esc_html( $item[ $column_name] );
		case 'datetime':
		  return date('Y-m-d H:i',strtotime($item[ $column_name ]));		
		default:
		  return print_r( $item, true ); //Show the whole array for troubleshooting purposes
	  }
	}
	
	/**
	 * Render the bulk edit checkbox
	 * @param array $item
	 * @return string
	 */
	function column_cb( $item ) {
	  return sprintf(
		'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['package_id']
	  );
	}
	
	/**
	 * Associative array of columns
	 * @return array
	 */
	function get_columns() {
	  $columns = [
		'cb'      => '<input type="checkbox" />',
		'package_name' => __( 'Package Name', 'sp' ),
		'package_price' => __( 'Package Price', 'sp' ),		
		'package_data' => __( 'Package Data', 'sp' ),
		'datetime'    => __( 'Date', 'sp' )		
	  ];

	  return $columns;
	}
	
	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {

		return array(
			'package_name' => array( 'package_name', true ),			
		);
	}
	
	/**
	 * Returns an associative array containing the bulk action	 *
	 * @return array
	 */
	public function get_bulk_actions() {
	  $actions = [
		'bulk-delete' => 'Delete'
	  ];
	  return $actions;
	}
	
	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
	  $columns = $this->get_columns();
 	  $hidden = array();
	  $sortable = $this->get_sortable_columns();	
	  //$this->_column_headers = $this->get_column_info();
	  $this->_column_headers = array( $columns, $hidden, $sortable );

	  /** Process bulk action */
	  $this->process_bulk_action();

	  $per_page     = $this->get_items_per_page( 'control_per_page', 20 );
	  $current_page = $this->get_pagenum();
	  $total_items  = self::record_count();
	  
	  $this->set_pagination_args( [
		'total_items' => $total_items, //WE have to calculate the total number of items
		'per_page'    => $per_page //WE have to determine how many items to show on a page
	  ] );


	  $this->items = self::get_records_all( $per_page, $current_page );
	}
	
	
	/*
		Bulk action
	*/
	public function process_bulk_action() {		
		global $wpdb;
	  //Detect when a bulk action is being triggered...
	  if ( 'delete' === $this->current_action() ) {

		// In our file that handles the request, verify the nonce.
		$nonce = esc_attr( $_REQUEST['_wpnonce'] );

		if ( ! wp_verify_nonce( $nonce, 'sp_delete' ) ) {
		  //die( 'Go get a life script kiddies' );
		   $deleteId = $_GET['packagedelete'];
		   $deleterecord 	= $wpdb->delete(Htree_Package_Tbl,array("package_id" => $deleteId)); 
		   wp_redirect("admin.php?page=package-settings" );
		   exit;
		}
		else {
		  self::sp_delete_record( absint( $_GET['package_id'] ) );
		  wp_redirect( esc_url( add_query_arg() ) );
		  exit;
		}
	  }

	  // If the delete bulk action is triggered
	  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' ) || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' ) ) {

		$delete_ids = esc_sql( $_POST['bulk-delete'] );

		// loop over the array of record IDs and delete them
		foreach ( $delete_ids as $id ) {
		  self::sp_delete_record( $id );
		}

		wp_redirect( esc_url( add_query_arg() ) );
		exit;
	  }
	}

}
?>