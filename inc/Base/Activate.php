<?php 	

/*
*
* @package yariko		
*
*/
namespace Inc\Base;

class Activate{

	public static function activate(){

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();


        $table_name1 = $wpdb->prefix . 'wr_price_lists';
        $table_name2 = $wpdb->prefix . 'wr_price_lists_price';
        $table_name3 = $wpdb->prefix . 'posts';


        $sql1 = "CREATE TABLE $table_name1 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          description text NOT NULL,
          PRIMARY KEY  (id)
        ) ENGINE=InnoDB $charset_collate;";

        $sql2 = "CREATE TABLE $table_name2 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          id_price_list INT NOT NULL,
          id_product INT NOT NULL,
          PRIMARY KEY  (id)
        ) ENGINE=InnoDB $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );
	}	

		

}
