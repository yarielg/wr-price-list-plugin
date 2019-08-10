<?php

/*
*
* @package yariko		
*
*/
namespace Wrpl\Inc\Base;

class Activate{

    public static function activate(){

        update_option('wrpl-assign-method',1);
        update_option('wrpl-hide_price',0);

        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();


        $table_name1 = $wpdb->prefix . 'wr_price_lists';
        $table_name2 = $wpdb->prefix . 'wr_price_lists_price';

        $sql1 = "CREATE TABLE $table_name1 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          description varchar(100) NOT NULL,
          id_parent varchar(11) NOT NULL,
          factor varchar(11) NOT NULL,
          PRIMARY KEY  (id)
        ) $charset_collate;";

        $sql2 = "CREATE TABLE $table_name2 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          id_price_list INT NOT NULL,
          id_product INT NOT NULL,
          price varchar(11) NOT NULL,
          sale_price varchar(11) NOT NULL,
          PRIMARY KEY  (id)
        )  $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );
    }
}
