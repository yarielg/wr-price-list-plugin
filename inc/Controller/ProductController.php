<?php

namespace Inc\Controller;

class ProductController{



    public function register(){

        //get variation by product id
      //  add_action( 'wp_ajax_nopriv_get_variations', array($this,'getVariations' ));
       // add_action( 'wp_ajax_get_variations', array($this,'getVariations' ));

        //get all products
        add_action( 'wp_ajax_nopriv_get_products', array($this,'getProducts' ));
        add_action( 'wp_ajax_get_products', array($this,'getProducts' ));

        //edit price
        add_action( 'wp_ajax_nopriv_edit_price', array($this,'editPrice' ));
        add_action( 'wp_ajax_edit_price', array($this,'editPrice' ));


    }

    function getProducts(){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_type IN (%s,%s) AND post_status NOT IN (%s)", 'product','product_variation','auto-draft')
        );

        $products = stdToArray($products);
        $product_with_its_variations = array();
        foreach ($products as $product){
           if(! $this->isProductHasVariations($product['ID'])){ //not including products with variations
                $image_values = wp_get_attachment_image_src( get_post_thumbnail_id($product['ID']), 'single-post-thumbnail' );
                $product['image'] = $image_values[0];
                $product['price'] = 15;
                array_push($product_with_its_variations,$product);
            }
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
        return count($variations) > 0 ? $variations : array() ;
    }

    function  isProductHasVariations($id){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts WHERE post_parent = %d AND post_type = %s", $id,'product_variation')
        );

        if(count(stdToArray($products))>0){
            return true;
        }
        return false;
    }
    function getImageVariationByIdVariation($id_variation){
        global $wpdb;
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " . $postmeta_table . " WHERE meta_key = '_thumbnail_id'  AND post_id = '$id_variation'");

        $variations = stdToArray($variations);
        $image_url = -1;
        if(count($variations)>0){
            $image_url = wp_get_attachment_image_url( $variations[0]['meta_value'], 'post-thumbnail' );
        }

        return $image_url ;
    }

    /*function getVariations(){
        global $wpdb;
        $id = $_POST['id'];
        $posts_table = $wpdb->prefix . 'posts';
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " . $posts_table. " WHERE  post_parent = '$id'");

        $variations = stdToArray($variations);
        $final_variations = array();
        foreach ($variations as $variation){
            $variation['image'] = $this->getImageVariationByIdVariation($variation['post_id']) ?: null;
            $variation['price'] = 15;
            array_push($final_variations,$variation);
        }


        echo json_encode($final_variations);
        wp_die();
    }*/

    function editPrice(){
        global $wpdb;
        $post_id = $_POST['id'];
        $price = $_POST['price'];


        $posts_table = $wpdb->prefix . 'posts';
        $postmeta_table =  $wpdb->prefix . 'postmeta';
        $variations = $wpdb->get_results("SELECT * FROM " . $posts_table. " WHERE  post_parent = '$id'");

        echo json_encode(560);
        wp_die();
    }
}