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

        foreach ($roles as $role){
            $price_list = $_POST[wrpl_valid_name($role['name'])];
            $role = wrpl_valid_name($role['name']);
            update_option('wrpl-' . $role,$price_list);
        }
    }

    function wrpl_get_user_price_list() //relation between price list and role are ave in options table of wp (wrpl-nameofpricelist,price_list_value)
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $roles = ( array )$user->roles;
            return get_option('wrpl-'.$roles[0]) ? get_option('wrpl-'.$roles[0]) : 'default';
        }else{
            return 'default';
        }

    }

    function wrpl_exist_price_list_name($name){
        global $wpdb;
        $name = strtolower($name);
        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists WHERE LOWER(description) = '$name'");
        if(count($plists)>0){
            return true;
        }else{
            return false;

        }
    }

    function wrpl_add_price_list($plist){
        global  $wpdb;
        $plist = preg_replace('/\s+/', ' ',$plist); //removing extra spacing
        if(!$this->wrpl_exist_price_list_name($plist)){
            $wpdb->query("INSERT INTO $wpdb->prefix" . "wr_price_lists (description) VALUES ('$plist')");
            return true;
        }else{
            return false;
        }
    }

    function wrpl_remove_price_list($id){
        global $wpdb;
        $result = $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_price_lists WHERE id = '$id'");
        $result = $wpdb->get_results("UPDATE $wpdb->prefix" . "options SET option_value = 'default' WHERE option_value = '$id'");
        $result = $wpdb->get_results("DELETE FROM $wpdb->prefix" . "wr_price_lists_price WHERE id_price_list = '$id'");
        return $result;
    }
}