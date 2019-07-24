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
		//add_filter('woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );
		add_filter('woocommerce_product_get_regular_price', array( $this, 'custom_regular_price' ), 99, 2 );
		add_filter('woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );

        add_filter( 'woocommerce_product_get_sale_price', array( $this, 'custom_sale_price' ), 10, 2 );
        add_filter( 'woocommerce_product_variation_get_sale_price', array( $this, 'custom_sale_price' ), 10, 2 );
		// Variable
		add_filter('woocommerce_product_variation_get_regular_price', array( $this, 'custom_regular_price' ), 99, 2 );
		add_filter('woocommerce_product_variation_get_price', array( $this, 'custom_price' ), 99, 2 );

        add_filter( 'woocommerce_format_sale_price', array( $this, 'custom_price_template') , 20, 3 );

        add_filter( 'woocommerce_variable_sale_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );
        add_filter( 'woocommerce_variable_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );

	}

    function custom_price($price,$product){
        $price_list = $this->price_list_controller->wrpl_get_user_price_list();
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
        if(!empty($p) && $p>0){
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

        $min_max = $this->product_controller->getMinMaxPriceVariation($product_parent_id,$price_list);
        return wc_price($min_max['min']) . " - " .  wc_price($min_max['max']);

    }

}

