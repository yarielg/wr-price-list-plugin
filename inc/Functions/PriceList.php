<?php

/*
*
* @package Yariko
*
*/

namespace Wrpl\Inc\Functions;
use Wrpl\Inc\Controller\PriceListController;
use Wrpl\Inc\Controller\ProductController;

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

        //add_filter( 'woocommerce_format_sale_price', array( $this, 'custom_price_template') , 20, 3 );

        add_filter( 'woocommerce_variable_sale_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );
        add_filter( 'woocommerce_variable_price_html', array($this,'wrpl_variation_price_range'), 10, 2 );

        add_filter( 'woocommerce_get_price_html', array($this,'wrpl_woocommerce_price_html'), 100, 2 );

        //sale tag
       // add_filter('woocommerce_sale_flash',array($this,'woocommerce_custom_sale_text'), 10, 3);

        //hide price not login user
        if(get_option('wrpl-hide_price')  == 1 ){
            add_action( 'init', array($this,'hide_price_not_login_user') );

        }

        //sold out
        //Trying to create a tag manager
        //add_filter( 'woocommerce_single_product_image_thumbnail_html', array($this,'sv_add_text_above_wc_product_image'),10,2 );
        //add_action( 'woocommerce_before_shop_loop_item_title', array($this,'bbloomer_new_badge_shop_page'), 2 );
        //add_action( 'woocommerce_before_shop_item_title', array($this,'bbloomer_new_badge_shop_page'), 2 );

    }

   /* function sv_add_text_above_wc_product_image( $img_html ) {

        echo '<h4 class="pepe_pepe" style="text-align: center;">Some sample text</h4>';

        return $img_html;
    }*/

    /*function bbloomer_new_badge_shop_page() {
        global $product;
            echo '<span class="itsnew">' . $product->get_id() . '</span>';
    }*/

    function hide_price_not_login_user() {
        if ( ! is_user_logged_in() ) {
            remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');
            remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
            add_action( 'woocommerce_single_product_summary', array($this,'wrpl_print_login_to_see'), 31 );
            add_action( 'woocommerce_after_shop_loop_item', array($this,'wrpl_print_login_to_see'), 11 );
            add_filter( 'woocommerce_is_sold_individually','custom_remove_all_quantity_fields', 10, 2 );

            //add this inline css style to assets/css/front/main.css when we want hide add to cart button and other stuff
            add_action( 'wp_enqueue_scripts', function(){
                wp_add_inline_style( 'wr_frontend_main', ".single_add_to_cart_button, form.cart .quantity,.product_type_simple.add_to_cart_button{display: none !important;width:0;height:0; visibility: hidden;}" );
            });
        }
    }

    function wrpl_print_login_to_see() {
        echo stripslashes( get_option('wrpl-custom_msg_no_login_user'));
    }

    function custom_price($price,$product){
        $price_list = get_option('wrpl-assign-method') == 1 ? $this->price_list_controller->wrpl_get_user_price_list() : $this->product_controller->wrpl_get_price_list_by_category($product->get_id());
        $rp = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        $sp = $this->product_controller->getSalesPrice($product->get_id(),$price_list);
        return $sp == 0 ? $rp : $sp;


    }

    function custom_regular_price($price,$product){
        $price_list = get_option('wrpl-assign-method') == 1 ? $this->price_list_controller->wrpl_get_user_price_list() : $this->product_controller->wrpl_get_price_list_by_category($product->get_id());
        $p = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        return $p;
    }

    function custom_sale_price($price,$product){
        $price_list = get_option('wrpl-assign-method') == 1 ? $this->price_list_controller->wrpl_get_user_price_list() : $this->product_controller->wrpl_get_price_list_by_category($product->get_id());
        $sp = $this->product_controller->getSalesPrice($product->get_id(),$price_list);
        $rp = $this->product_controller->getRegularPrice($product->get_id(),$price_list);
        if($sp>0){
            return $sp;

        }
        return $rp;
    }

    function wrpl_variation_price_range( $price, $product ) {

        if (  is_user_logged_in() && get_option('wrpl-hide_price')  != 1) {

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

    }

    function wrpl_woocommerce_price_html( $price, $product ){

        if ( ! is_user_logged_in() && get_option('wrpl-hide_price')  == 1 ) {
            return '';
        }else{
            $price_list = get_option('wrpl-assign-method') == 1 ? $this->price_list_controller->wrpl_get_user_price_list() : $this->product_controller->wrpl_get_price_list_by_category($product->get_id());
            $min_max = $this->product_controller->getMinMaxPriceVariation($product->get_id(),$price_list,'_regular_price');
            $min_max_sale = $this->product_controller->getMinMaxPriceVariation($product->get_id(),$price_list,'_sale_price');
            //$pl_objec =$this->price_list_controller->wrpl_get_price_list_by_id($price_list);

            if(!$product->has_child()){
                if($this->custom_sale_price($price,$product) != $this->custom_regular_price($price,$product)){
                    switch(get_option('wrpl-format-price-method')){
                        case 1:
                            $html_price = '<del>' . wc_price($this->custom_regular_price($price,$product)) . '</del>  ' . wc_price($this->custom_sale_price($price,$product)) ;
                            break;
                        case 2:
                            $html_price =  wc_price($this->custom_sale_price($price,$product)) . ' <del>' . wc_price($this->custom_regular_price($price,$product)) . '</del>' ;
                            break;
                        case 3:
                            $html_price =  wc_price($this->custom_sale_price($price,$product)) ;
                            break;
                        default:
                            $html_price =  wc_price($this->custom_regular_price($price,$product));
                            break;
                    }
                }else{
                    $html_price =  wc_price($this->custom_regular_price($price,$product));
                }
                return $html_price;
            }else{

                if(  $min_max_sale['max'] > 0 && $min_max_sale['min']<$min_max['min']){
                    return __('Starting at ','wr_price_list') . wc_price($min_max_sale['min']);
                }else{

                    if(  $min_max_sale['max'] > 0 && $min_max_sale['min']<$min_max['min']){
                        return __('Starting at ','wr_price_list') . wc_price($min_max_sale['min']);
                    }else{
                        return __('Starting at ','wr_price_list') . wc_price($min_max['min']);
                    }

                }

            }
        }
    }

    //
    /*function woocommerce_custom_sale_text($text, $post, $_product)
    {
        return '<span class="onsale">SALE!</span>';
    }*/


}

