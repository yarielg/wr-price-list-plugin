<?php


namespace Inc\Controller;


class PriceListController
{
    public function register(){

        //get all price lists
        //add_action( 'wp_ajax_nopriv_get_price_lists', array($this,'getPriceList' ));
        //add_action( 'wp_ajax_get_price_lists', array($this,'getPriceList' ));


    }

    function wrpl_get_price_lists(){
        global $wpdb;
        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists" );

        $plists = stdToArray($plists);

        return $plists;
    }

    function wrpl_save_price_list_role($roles){
        global $wpdb;
        $data = array();
        foreach ($roles as $role){
            $price_list = $_POST[wrpl_valid_name($role['name'])];
            $role = wrpl_valid_name($role['name']);
            update_option('wrpl-' . $role,$price_list);
        }
    }

    function wrpl_get_user_price_list()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $roles = ( array )$user->roles;
            return get_option('wrpl-'.$roles[0]);
        }else{
            return 'default';
        }

    }
}