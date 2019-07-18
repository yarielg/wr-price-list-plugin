<?php 
/*
* Trigger this file  on Pluging Uninstall
*
* @package yariko
*/

if( ! defined('WP_UNINSTALL_PLUGIN') ){
	die;
}

global $wpdb;
$table_name1 = $wpdb->prefix . 'wr_price_lists';
$table_name2 = $wpdb->prefix . 'wr_price_lists_price';
$wpdb->query( "DROP TABLE IF EXISTS $table_name1" );
$wpdb->query( "DROP TABLE IF EXISTS $table_name2" );
