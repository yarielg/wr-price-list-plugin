<?php

namespace Inc\Controller;

class ProductController{



    public function register(){

        //get variation by product id
        add_action( 'wp_ajax_nopriv_get_variations', array($this,'getVariations' ));
        add_action( 'wp_ajax_get_variations', array($this,'getVariations' ));

        //get all products
        add_action( 'wp_ajax_nopriv_get_products', array($this,'getProducts' ));
        add_action( 'wp_ajax_get_products', array($this,'getProducts' ));


    }

    function getProducts(){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type IN (%s) AND post_status NOT IN (%s)", 'product','auto-draft')
        );

        $products = stdToArray($products);
        $product_with_its_variations = array();
        foreach ($products as $product){
            $product['variations'] = $this->getVariationByProductId($product['ID']);
            array_push($product_with_its_variations,$product);
        }

        echo json_encode($product_with_its_variations);
        wp_die();
    }

    function getVariationByProductId($id){
        global $wpdb;

        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type = %s AND post_parent = $id", 'product_variation')
        );
        $variations = stdToArray($variations);
        return count($variations) > 0 ? $variations : [] ;
    }

    function getVariations($id){
        global $wpdb;
        $id = $_POST['id'];
        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type = %s AND post_parent = $id", 'product_variation')
        );

        echo json_encode($variations);
        wp_die();
    }

}