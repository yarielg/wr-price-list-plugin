<?php  

/*
*
* @package Yariko
*
*/

namespace Inc\Pages;

class MenuPage{

		public $porperties = array();

		public function __construct($porperties){

				$this->porperties = [
						'title' => $porperties['title'] ?? 'title',
						'capability' => $porperties['capability'] ?? 'manage_options',
						'menu_slug' => $porperties['menu_slug'],
						'callback' => $porperties['callback'],
						'icon_url' => $porperties['icon_url'] ?? null,
						'position' => $porperties['position'],
						'parent_slug' => $porperties['parent_slug'] ?? $porperties['menu_slug']
				];			
				
		}

		public function addMenuElement(){
			add_action('admin_menu', function(){
				if($this->porperties['parent_slug'] == $this->porperties['menu_slug'] && isset($this->porperties['icon_url'])){
					add_menu_page($this->porperties['title'],$this->porperties['title'],$this->porperties['capability'],$this->porperties['menu_slug'],$this->porperties['callback'],$this->porperties['icon_url'],$this->porperties['position']);
				}else{
					add_submenu_page($this->porperties['parent_slug'],$this->porperties['title'],$this->porperties['title'],$this->porperties['capability'],$this->porperties['menu_slug'],$this->porperties['callback']);
				}
			});
		}

}