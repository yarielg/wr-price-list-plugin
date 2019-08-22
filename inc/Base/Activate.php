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
        update_option('wrpl-default_list',1);

        define('YOUR_LICENSE_SERVER_URL','https://webreadynow.com');

        $prox_anio = mktime(0, 0, 0, date("m"), date("d"), date("Y") + 1);

        $api_params = array(
            'slm_action' => 'slm_create_new',
            'secret_key' => '5d5df9b0ad55e8.43571874',
            'first_name' => 'elvis',
            'last_name' => 'presley',
            'email' => 'elvis@king.com',
            'company_name' => 'WEB READY',
            'txn_id' => 'ABC0987654321',
            'max_allowed_domains' => '2',
            'date_created' =>date('Y-m-d'),
            'date_expiry' =>date('Y-m-d', $prox_anio),
);

// Send query to the license manager server
$response = wp_remote_get(add_query_arg($api_params,YOUR_LICENSE_SERVER_URL ), array('timeout' => 20, 'sslverify' => false));

// Check for error in the response
if (is_wp_error($response)){
    echo "Unexpected Error! The query returned with an error.";
    }

// License data.
$license_data = json_decode(wp_remote_retrieve_body($response));


        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();


        $table_name1 = $wpdb->prefix . 'wr_price_lists';
        $table_name2 = $wpdb->prefix . 'wr_price_lists_price';
        $table_name3 = $wpdb->prefix . 'wr_rules';

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

        $sql3 = "CREATE TABLE $table_name3 (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          id_price_list varchar(11) NOT NULL,
          id_category INT NOT NULL,
          priority INT NOT NULL DEFAULT 0,
          rule_type varchar(11) NOT NULL,
          PRIMARY KEY  (id)
        )  $charset_collate;";

        $sql4 = "INSERT INTO $table_name1 (description,id_parent,factor)
SELECT * FROM (SELECT 'Default Woocommerce', '0','') AS tmp
WHERE NOT EXISTS (
    SELECT description FROM $table_name1 WHERE description = 'Default Woocommerce'
) LIMIT 1;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql1 );
        dbDelta( $sql2 );
        dbDelta( $sql3 );
        dbDelta( $sql4 );
    }
}
