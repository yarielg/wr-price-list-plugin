<?php
/*
*
* @package yariko


Plugin Name:  WR Price List for Woocommerce
Plugin URI:   https://www.webreadynow.com/en/wr-price-list-manager-woocommerce
Description:  Create a Sales Price List(s) based on existing Price List(s). Changes to the primary price list will affect child price list. Configure child list to be a discount price list or raise cost based on a percentage of the parent list.
Version:      1.0.2
Author:       Web Ready Now
Author URI:   https://webreadynow.com/
Tested up to: 5.3.2
Text Domain:  wr_price_list
Domain Path:  /languages
*/

defined('ABSPATH') or die('You do not have access, sally human!!!');

define ( 'WRPL_PLUGIN_VERSION', '1.0.2');

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}

define('WRPL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('WRPL_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('WRPL_ADMIN_URL' , get_admin_url() );
define('WRPL_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );


//include the helpers
include 'inc/Util/helper.php';

if ( class_exists( 'woocommerce' ) ){
    if( class_exists( 'Wrpl\\Inc\\Init' ) ){
        register_activation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Activate','activate') );
        register_deactivation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Deactivate','deactivate') );
        Wrpl\Inc\Init::register_services();
    }
}else{

    add_action('admin_notices', function(){
        ?>
            <div class="notice notice-error is-dismissible">
                <p>WR Price List Manager required WooCommerce, please activate it to use <b>WR Price List Manager</b> Plugin</p>
            </div>
        <?php
    });
}

