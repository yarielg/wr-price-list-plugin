<?php
/*
*
* @package yariko


Plugin Name:  WR Price List for Woocommerce
Plugin URI:   http://webreadynow.com/plugins/wr_price_list/
Description:  Create price lists to sell your product with different price depending on user role, user id, or specific products. You also can create base price list.
Version:      1.0
Author:       Yariko
Author URI:   http://webreadynow.com/author/yariko
License:      GPLv2 or later
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  wporg
Domain Path:  /languages
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

defined('ABSPATH') or die('You do not have access, sally human!!!'); //for security

if( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php') ){
    require_once  dirname( __FILE__ ) . '/vendor/autoload.php';
}

define('WRPL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define('WRPL_PLUGIN_URL' , plugin_dir_url(  __FILE__  ) );
define('WRPL_ADMIN_URL' , get_admin_url() );
define('WRPL_PLUGIN_DIR_BASENAME' , dirname(plugin_basename(__FILE__)) );


//include the helpers
include 'inc/Util/helper.php';

if( class_exists( 'Wrpl\\Inc\\Init' ) ){
    register_activation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Activate','activate') );
    //register_deactivation_hook( __FILE__ , array('Wrpl\\Inc\\Base\\Deactivate','deactivate') );
    Wrpl\Inc\Init::register_services();

}



