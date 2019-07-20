<?php  

/*
*
* @package Yariko
*
*/

namespace Inc\Pages;

class Pages{

	public function register(){



        add_action('admin_menu', function(){
            add_menu_page('WR Price List', 'WR Price List', 'manage_options', 'wrpl-main-menu', array($this,'admin_index'),'dashicons-image-rotate-right',110);
        });

        /*add_action('admin_menu', function(){
            add_submenu_page( 'wrpl-main-menu', 'Dashboard', 'Dashboard','manage_options', 'wrpl-main-menu', array($this,'admin_index'));
        });*/

        add_action('admin_menu',function(){
            $page_product =  add_submenu_page( 'wrpl-main-menu', 'Products', 'Products','manage_options', 'wrpl-products-menu', array($this,'products'));
            add_action( 'load-' . $page_product, array($this,'wrpl_products_page_load_scripts') );
        });

	}

	//Assigning the template to each page
	function admin_index(){
		require_once PLUGIN_PATH . 'templates/dashboard.php';
	}

	function group_index(){
		require_once PLUGIN_PATH . 'templates/price-list.php';
	}

	function products(){
		require_once PLUGIN_PATH . 'templates/products.php';
	}

    function wrpl_products_page_load_scripts(){
        // Unfortunately we can't just enqueue our scripts here - it's too early. So register against the proper action hook to do it
        add_action( 'admin_enqueue_scripts',function (){
            wp_enqueue_style('datatable_css', PLUGIN_URL . '/assets/css/admin/datatables.min.css'  );
            wp_enqueue_style('editor_datatable_css', PLUGIN_URL . '/assets/css/admin/editor.dataTables.min.css'  );
            wp_enqueue_style('main_admin_styles',  PLUGIN_URL . '/assets/css/admin/main.css' );

            wp_enqueue_script( 'datatable_js', PLUGIN_URL . '/assets/js/admin/datatables.min.js');
            wp_enqueue_script( 'datatable_js', PLUGIN_URL . '/assets/js/admin/dataTables.editor.min.js');

            wp_enqueue_script('main_js', PLUGIN_URL . '/assets/js/admin/main.js',array ('jquery'), '1.0', true );
            wp_enqueue_script( 'main_js');
            wp_localize_script( 'main_js', 'parameters',['ajax_url'=> admin_url('admin-ajax.php')]);
        });
    }


}