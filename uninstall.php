<?php 
/*
* Trigger this file  on Pluging Uninstall
*
* @package yariko
*/
require "inc/Base/WRPL_Signature.php";



if( ! defined('WP_UNINSTALL_PLUGIN') ){
	die;
}


global $wpdb;
$table_name1 = $wpdb->prefix . 'wr_price_lists';
$table_name2 = $wpdb->prefix . 'wr_price_lists_price';
$table_name3 = $wpdb->prefix . 'wr_rules';
$wpdb->query('DELETE FROM ' .$wpdb->prefix . 'options WHERE option_name LIKE "wrpl%" AND  option_name NOT LIKE "wrpl_role%"');
$wpdb->query( "DROP TABLE IF EXISTS $table_name1" );
$wpdb->query( "DROP TABLE IF EXISTS $table_name2" );
$wpdb->query( "DROP TABLE IF EXISTS $table_name3" );

$signature = new WRPL_Signature();
if($signature->is_valid()){
    $result = $signature->remove_license($signature->get_license());
}