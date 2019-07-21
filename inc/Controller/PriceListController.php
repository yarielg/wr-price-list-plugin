<?php


namespace Inc\Controller;


class PriceListController
{
    public function register(){

        //get all price lists
        add_action( 'wp_ajax_nopriv_get_price_lists', array($this,'getPriceList' ));
        add_action( 'wp_ajax_get_price_lists', array($this,'getPriceList' ));


    }

    function getPriceList(){
        global $wpdb;

        $plists = $wpdb->get_results("SELECT * FROM $wpdb->prefix" . "wr_price_lists" );

        $plists = stdToArray($plists);
        echo json_encode($plists);
        wp_die();
    }

}