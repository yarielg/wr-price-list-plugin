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

        add_filter( 'woocommerce_variable_sale_price_html', array($this,'iconic_variable_price_format'), 10, 2 );
        add_filter( 'woocommerce_variable_price_html', array($this,'iconic_variable_price_format'), 10, 2 );
    }

    function getProducts(){
        global $wpdb;

        $products = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts  LEFT JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type IN (%s,%s) AND post_status NOT IN (%s) AND meta_key = %s", 'product','product_variation','auto-draft','_regular_price')
        );

        $products = stdToArray($products);
        $product_with_its_variations = array();
        foreach ($products as $product){
           if(! $this->isProductHasVariations($product['ID'])){ //not including products with variations
                $image_values = wp_get_attachment_image_src( get_post_thumbnail_id($product['ID']), 'single-post-thumbnail' );
                $product['image'] = $image_values[0];
                $product['price'] = $product['meta_value'];
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
        $post_id = $_POST['id'];
        $price = $_POST['price'];

        update_post_meta($post_id, '_regular_price', $price);
       // update_post_meta($post_id, '_sale_price', '');
        update_post_meta($post_id, '_price', $price);


        echo json_encode(array('msg' => 'Price updated'));
        wp_die();
    }

    function getMinMaxPriceVariation($id){
        global $wpdb;

        $variations = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM $wpdb->prefix" . "posts INNER JOIN $wpdb->prefix" . "postmeta ON ID=post_id WHERE post_type = %s AND post_parent = $id AND meta_key = %s", 'product_variation','_regular_price')
        );

        $variations = stdToArray($variations);
        $max = 0;
        $min = PHP_FLOAT_MAX;
        foreach($variations as $variation){
            if($variation['meta_value'] > $max){
                $max = $variation['meta_value'];
            }
            if($variation['meta_value'] < $min){
                $min = $variation['meta_value'];
            }
        }
        return array('min' => $min, 'max' => $max);
    }

    function iconic_variable_price_format( $price, $product ) {

      //  $prefix = sprintf('%s: ', __('From', 'iconic'));

       // $min_price_regular = 7;//$product->get_variation_regular_price( 'min', true );
       // $min_price_sale    = 5;//$product->get_variation_sale_price( 'min', true );
       // $max_price = 4;//$product->get_variation_price( 'max', true );
       // $min_price = 4;//$product->get_variation_price( 'min', true );

        $product_parent_id = $product->get_id();

        /*$price = ( $min_price_sale == $min_price_regular ) ?
            wc_price( $min_price_regular ) :
            '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';*/

        $min_max = $this->getMinMaxPriceVariation($product_parent_id);
        return wc_price($min_max['min']) . " - " .  wc_price($min_max['max']);

    }
}