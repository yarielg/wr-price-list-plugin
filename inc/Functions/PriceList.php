<?php

/*
*
* @package Yariko
*
*/

namespace Inc\Functions;
use Inc\Controller\PriceListController;
use Inc\Controller\ProductController;

class PriceList{

    public $product_controller;
    public $price_list_controller;

    function __construct()
    {
        $this->product_controller =  new ProductController();
        $this->price_list_controller =  new PriceListController();
    }

    public function register(){

        // Simple, grouped and external products
        add_filter('woocommerce_product_get_regular_price', array( $this, 'custom_regular_price' ), 99, 2 );
        add_filter('woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );

        add_filter( 'woocommerce_product_get_sale_price', array( $this, 'custom_sale_price' ), 10, 2 );
        add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'custom_sale_price' ), 10, 2 );
        // Variable
        add_filter('woocommerce_product_variation_get_regular_price', array( $this, 'custom_regular_price' ), 99, 2 );
        add_filter('woocommerce_product_variation_get_price', array( $this, 'custom_price' ), 99, 2 );

        // add_filter( 'woocommerce_format_sale_price', array( $this, 'custom_price_template') , 20, 3 );

        add_filter( 'woocommerce_variable_sale_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );
        add_filter( 'woocommerce_variable_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );

        add_filter( 'woocommerce_get_price_html', array($this,'wrpl_woocommerce_price_html'), 100, 2 );

        $this->wp_cache_flush();
    }

    function wp_cache_flush() {
        global $wp_object_cache;

        return $wp_object_cache->flush();
    }

    function custom_price($price,$product){
        $price_list = $this->price_list_controller->wrpl_get_user_price_list(); //get price list of the logging user
        $rp = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        $sp = $this->product_controller->getSalesPrice($product->get_id(),$price_list);
        return $sp == 0 ? $rp : $sp;


    }

    function custom_regular_price($price,$product){
        $price_list = $this->price_list_controller->wrpl_get_user_price_list();
        $p = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        return $p;
    }

    function custom_sale_price($price,$product){
        $price_list = $this->price_list_controller->wrpl_get_user_price_list();
        $sp = $this->product_controller->getSalesPrice($product->get_id(),$price_list);
        $rp = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        if($sp>0){
            return $sp;

        }
        return $rp;
    }

    function wrpl_variation_price_range( $price, $product ) {

        //  $prefix = sprintf('%s: ', __('From', 'webready'));

        // $min_price_regular = 7;//$product->get_variation_regular_price( 'min', true );
        // $min_price_sale    = 5;//$product->get_variation_sale_price( 'min', true );
        // $max_price = 4;//$product->get_variation_price( 'max', true );
        // $min_price = 4;//$product->get_variation_price( 'min', true );
        $price_list = $this->price_list_controller->wrpl_get_user_price_list();
        $product_parent_id = $product->get_id();
        /*$price = ( $min_price_sale == $min_price_regular ) ?
            wc_price( $min_price_regular ) :
            '<del>' . wc_price( $min_price_regular ) . '</del>' . '<ins>' . wc_price( $min_price_sale ) . '</ins>';*/

        $min_max = $this->product_controller->getMinMaxPriceVariation($product_parent_id,$price_list,'_regular_price');
        if($min_max['min'] != $min_max['max']){

            return wc_price($min_max['min']) . " - " .  wc_price($min_max['max']);

        }

    }

    function wrpl_woocommerce_price_html( $price, $product ){
        $price_list = $this->price_list_controller->wrpl_get_user_price_list();
        $min_max = $this->product_controller->getMinMaxPriceVariation($product->get_id(),$price_list,'_regular_price');
        $min_max_sale = $this->product_controller->getMinMaxPriceVariation($product->get_id(),$price_list,'_sale_price');
        if(!$product->has_child()){
            if($this->custom_sale_price($price,$product) != $this->custom_regular_price($price,$product)){
                $html_price = '<del>' . wc_price($this->custom_regular_price($price,$product)) . '</del>  ' . wc_price($this->custom_sale_price($price,$product)) ;
            }else{
                $html_price =  wc_price($this->custom_regular_price($price,$product));
            }
            return $html_price;
        }else{

            if(  $min_max_sale['min']>0 && $min_max_sale['min']<$min_max['min']){
                return 'Start in: ' . wc_price($min_max_sale['min']);
            }else{
                return 'Start in: ' . wc_price($min_max['min']);
            }

        }
    }



}

