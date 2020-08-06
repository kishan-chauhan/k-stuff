<?php
/**
 * Plugin Name: My Data
 * Plugin URI:  https://kishanchauhanstuff.wordpress.com
 * Description: calculation.
 * Author: Kishanchauhan
 * Author URI:  https://kishanchauhanstuff.wordpress.com
 * Version: 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'INTERNET_DATA_CHECKER', '1.0.0' );
define( 'DATA_CHECKER_PLUGIN_NAME', 'Internet Data Checker' );
define( 'DATA_CHECKER_URL', plugin_dir_url( __FILE__ ) );
define( 'DATA_CHECKER_ASSETS', plugin_dir_url( __FILE__ ) . 'assets/' );
define( 'DATA_CHECKER_DIR', plugin_dir_path( __FILE__ ) );
define( 'DATA_CHECKER_INC', plugin_dir_path( __FILE__ ) . 'includes/' );
//define( 'SUPER_PLUGIN_DIR', dirname( __FILE__ ) );
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
// table create
function htree_datatbl() {
	global $wpdb;
	$table_name1 = $wpdb->prefix ."heaven_Customer_preference_tbl";
	$table_name2 = $wpdb->prefix ."heaven_Package_tbl";
	
	$sql_1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
					  `id` int(11) NOT NULL auto_increment,
					  `icons_id` varchar(255) NOT NULL,
					  `header_text` varchar(255) NOT NULL,
					  `control_type` varchar(255) NOT NULL,
					  `control_label` varchar(255) NOT NULL,
					  `control_min_value` varchar(255) NOT NULL,
					  `control_max_value` varchar(255) NOT NULL,
					  `minimum_mb_percontrol` varchar(255) NOT NULL,
					  `control_name_color` varchar(50) NOT NULL,
					  `control_custom_css` varchar(50) NOT NULL,
					  `control_bg_color` varchar(50) NOT NULL,
					  `control_text_color` varchar(50) NOT NULL,
					  `custom_html` text NULL,
					  `custom_css` text NULL,
					  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
					  PRIMARY KEY  (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
					
	$sql_2 = "CREATE TABLE IF NOT EXISTS $table_name2 (
						`package_id` int(11) NOT NULL auto_increment,
						`package_name` varchar(255) NOT NULL,
						`package_description` text NOT NULL,
						`package_long_description` text NOT NULL,
						`package_price` varchar(255) NOT NULL,
						`package_data`  varchar(255) NOT NULL,
						`package_formula` varchar(255) NOT NULL,
						`package_addon_formula` varchar(255) NOT NULL,
						`datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						 PRIMARY KEY  (`package_id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";
	
	require_once(ABSPATH . "wp-admin/includes/upgrade.php");
	dbDelta($sql_1);
	dbDelta($sql_2);				
}
register_activation_hook(__FILE__,'htree_datatbl');

//define table name
define('Htree_Customer_Tbl',$wpdb->prefix.'heaven_Customer_preference_tbl');
define('Htree_Package_Tbl',$wpdb->prefix.'heaven_Package_tbl');

//
function demo_data_insert(){
	$customHtml_1 = "Please include all devices such as Desktop Computers, Laptops, Tablets, Ipads and Smartphones.";
	$insert_control_1 = $wpdb->insert(Htree_Customer_Tbl, array(
		'icons_id' => '',
		'header_text' => 'How many devices are connected to the internet?',
		'control_type' => 'Device',
		'control_label' => 'Devices',
		'control_min_value' => '0',
		'control_max_value' => '10',
		'control_name_color' => '',
		'control_custom_css' => '',
		'control_bg_color' => '',
		'control_text_color' => '',
		'custom_html' => $customHtml_1,
		'custom_css' => ''		
	));
	
	$customHtml_2 = "Movie - 2GB, Windows Update-250MB";
	$insert_control_2 = $wpdb->insert(Htree_Customer_Tbl, array(
		'icons_id' => '',
		'header_text' => 'Software, Window Updates, Office, Antivirus etc. per month? ',
		'control_type' => 'Downloads',
		'control_label' => 'GB',
		'control_min_value' => '0',
		'control_max_value' => '250',
		'control_name_color' => '',
		'control_custom_css' => '',
		'control_bg_color' => '',
		'control_text_color' => '',
		'custom_html' => $customHtml_2,
		'custom_css' => ''		
	));
	
	
	
	$insert_package_1 = $wpdb->insert(Htree_Package_Tbl, array(
		'package_name' => 'GoLite',
		'package_description' => '3MB - 30GB DATA',
		'package_long_description' => 'Downloads',
		'package_price' => '30',
		'package_data' => '30',
		'package_formula' => '',
		'package_addon_formula' => ''
	));
	
	$insert_package_2 = $wpdb->insert(Htree_Package_Tbl, array(
		'package_name' => 'Go3',
		'package_description' => '3MB - 80GB DATA',
		'package_long_description' => 'Downloads',
		'package_price' => '40',
		'package_data' => '80',
		'package_formula' => '',
		'package_addon_formula' => ''
	));
}

/* Plugin setting url function */
function booking_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="admin.php?page=internet-data-setting">' . __( 'Settings' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'booking_plugin_add_settings_link' );


//menu creating
function htree_internet_create_menu(){
	add_menu_page(	
				'Internet Data Settings',
				'Internet Data Settings',
				'manage_options',
				'internet-data-setting',
				'htree_control_setting',
			plugins_url('/assets/images/icon.png',__FILE__)
		);
		add_submenu_page(
			'internet-data-setting',
			'Manage Package',
			'Manage Package',
			'manage_options',
			'package-settings',
			'htree_internet_package'
		);
		
		
}
add_action('admin_menu','htree_internet_create_menu');

//load admin js & css
function load_admin_js_and_css(){
	wp_register_style('style.css', plugin_dir_url(__FILE__) . 'assets/css/style.css');
	wp_enqueue_style('style.css');
	
	wp_register_script( 'htree_internet-checker',plugins_url( '/assets/js/internet-checker.js', __FILE__ ),array( 'jquery'),'1',true);	
	wp_enqueue_script('htree_internet-checker');
	
	wp_register_script( 'htree_vanilla-picker',plugins_url( '/assets/js/vanilla-picker.js', __FILE__ ),array( 'jquery'),'1',true);
	wp_enqueue_script('htree_vanilla-picker');
}
//add_action( 'wp_enqueue_scripts', 'load_scripts'  );
add_action( 'admin_enqueue_scripts', 'load_admin_js_and_css');
// media popup open
function load_wp_media_files() {
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'load_wp_media_files' );
// includes files
require_once plugin_dir_path( __FILE__ ) . 'includes/admin/internet-package-list.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin/htree_addpackage.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin/htree_control_setting.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/admin/htree_addcontrol.php';

require_once plugin_dir_path( __FILE__ ) . 'includes/user/htree_datachecker.php';

