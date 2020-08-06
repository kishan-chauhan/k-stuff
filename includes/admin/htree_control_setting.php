<?php
/*
	Control slider setting
*/
ob_start();
function htree_control_setting(){
		if($_GET['action'] =='add-control' || $_GET['action'] =='edit'){
			htree_addcontrol();
		}else{
			
			?>
			<div class="wrap">
				<h1>Internet Data Control | <a href="admin.php?page=internet-data-setting&action=add-control">Add Control</a></h1>	
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
					$data_obj = new Htree_Control_list();
					$record = $data_obj->prepare_items();
					$data_obj->display(); ?>
				</form>					
			</div>
			<?php
		}
}


//Data listing
class Htree_Control_list extends WP_List_Table {

		/** Class constructor */
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Control', 'sp' ), //singular name of the listed records
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
		$table_name  = Htree_Customer_Tbl;		
		$sql = "SELECT * FROM $table_name  ";

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}else{
			$sql .= ' ORDER BY id DESC' ;
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
		Htree_Customer_Tbl,
		[ 'id' => $id ],
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

	  $sql = "SELECT COUNT(*) FROM ".Htree_Customer_Tbl."";

	  return $wpdb->get_var( $sql );
	}
	
	/** Text displayed when no record data is available */
	public function no_items() {
	  _e( 'No record available.', 'sp' );
	}

	// edit delete link
	function column_icons_id($item) {
		global $wpdb;
	  $actions = array(
				'edit'      => sprintf('<a href="?page=%s&action=%s&edit-control=%s">Edit</a>',$_REQUEST['page'],'edit',$item['id']),
				'delete'    => sprintf('<a href="?page=%s&action=%s&controldelete=%s">Delete</a>',$_REQUEST['page'],'delete',$item['id']),
			);
		$image_src = wp_get_attachment_url( $item['icons_id'] );
		$attachment = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE guid='".$image_src."' ");				
		$image_id = $attachment->ID;
		$image_src = wp_get_attachment_image_src($image_id, 'thumbnail');
		$image_src = $image_src[0];
		$image = '<img src="'.$image_src.'" width="50">';		

	  return sprintf('%1$s %2$s',$image, $this->row_actions($actions) );
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
		case 'icons_id':			
			return esc_html( $item[ $column_name ] );
		case 'control_type':
			return esc_html( $item[ $column_name ] );
		case 'header_text':
			return esc_html( $item[ $column_name ] );
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
		'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['id']
	  );
	}
	
	/**
	 * Associative array of columns
	 * @return array
	 */
	function get_columns() {
	  $columns = [
		'cb'      => '<input type="checkbox" />',
		'icons_id' => __( 'Control Icon', 'sp' ),
		'control_type' => __( 'Control Name', 'sp' ),
		'header_text' => __( 'Header Text', 'sp' ),		
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
			'control_type' => array( 'control_type', true ),			
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
		   $deleteId = $_GET['controldelete'];
		   $deleterecord 	= $wpdb->delete(Htree_Customer_Tbl,array("id" => $deleteId)); 
		   wp_redirect("admin.php?page=internet-data-setting" );
		   exit;
		}
		else {
		  self::sp_delete_record( absint( $_GET['id'] ) );
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