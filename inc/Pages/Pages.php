<?php  

/*
*
* @package Yariko
*
*/

namespace Inc\Pages;

class Pages{

	public function register(){

			$pages = array(
					new MenuPage([
							'title' => 'SAP B1 WP',
							'menu_slug' => 'main-menu',
							'callback' => array($this,'admin_index'),
							'icon_url' => 'dashicons-image-rotate-right',
							'position' => 110
					]),
					new MenuPage([
							'parent_slug' => 'main-menu',
							'title' => 'Dashboard',
							'menu_slug' => 'main-menu',
							'callback' => array($this,'admin_index'),
					]),
					new MenuPage([
							'parent_slug' => 'main-menu',
							'title' => 'Price List Group',
							'menu_slug' => 'price-list-menu',
							'callback' => array($this,'group_index'),
					]),
					new MenuPage([
							'parent_slug' => 'main-menu',
							'title' => 'Products',
							'menu_slug' => 'products-menu',
							'callback' => array($this,'products'),
					])
			);

			foreach ($pages as $menu) {
				$menu->addMenuElement();
			}

	}

	function admin_index(){
		require_once PLUGIN_PATH . 'templates/dashboard.php';
	}

	function group_index(){
		require_once PLUGIN_PATH . 'templates/price-list.php';
	}

	function products(){
		require_once PLUGIN_PATH . 'templates/products.php';
	}

}