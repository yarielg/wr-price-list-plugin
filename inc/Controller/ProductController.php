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
            $image_values = wp_get_attachment_image_src( get_post_thumbnail_id($product['ID']), 'single-post-thumbnail' );
            $product['image'] = $image_values[0];
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

    function getVariations(){
        global $wpdb;
        $id = $_POST['id'];
        $posts_table = $wpdb->prefix . 'posts';
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " .$posts_table. " INNER JOIN ".$postmeta_table." ON " .$posts_table. ".ID = ".$postmeta_table.".post_id WHERE ".$postmeta_table.".meta_key = '_thumbnail_id'  AND post_parent = '$id'");

        $variations = stdToArray($variations);
        $final_variations = array();
        foreach ($variations as $variation){
            $variation['image'] = wp_get_attachment_image_url( $variation['meta_value'], 'post-thumbnail' );
            array_push($final_variations,$variation);
        }


        echo json_encode($final_variations);
        wp_die();
    }


}