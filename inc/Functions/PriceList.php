<?php  

/*
*
* @package Yariko
*
*/

namespace Inc\Functions;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class PriceList{

	public function register(){

		// Simple, grouped and external products
		//add_filter('woocommerce_product_get_price', array( $this, 'custom_price' ), 99, 2 );
		//add_filter('woocommerce_product_get_regular_price', array( $this, 'custom_price' ), 99, 2 );
		// Variable
		//add_filter('woocommerce_product_variation_get_regular_price', array( $this, 'custom_variation_price' ), 99, 2 );
		//add_filter('woocommerce_product_variation_get_price', array( $this, 'custom_variation_price' ), 99, 2 );
	}






}

