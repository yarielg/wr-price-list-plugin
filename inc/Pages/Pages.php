<?php

/*
*
* @package Yariko
*
*/

namespace Wrpl\Inc\Pages;

class Pages{

    public function register(){



        add_action('admin_menu', function(){
            add_menu_page('WR Price Manager', 'WR Price Manager', 'manage_options', 'wrpl-products-menu', array($this,'wrpl-products-menu') , WRPL_PLUGIN_URL. 'assets/img/price-tag.png',110);
        });

        add_action('admin_menu',function(){
            $page_product =  add_submenu_page( 'wrpl-main-menu', __('Products','wr_price_list'), __('Products','wr_price_list'),'manage_options', 'wrpl-products-menu', array($this,'products'));
            add_action( 'load-' . $page_product, function(){
                add_action( 'admin_enqueue_scripts',function (){

                    wp_enqueue_style('wrtable_css', WRPL_PLUGIN_URL . '/assets/css/admin/wrtable.min.css'  );
                    wp_enqueue_style('tree-view_css', WRPL_PLUGIN_URL . '/assets/css/admin/bootstrap-treeview.min.css'  );
                    wp_enqueue_style('main_admin_styles',  WRPL_PLUGIN_URL . '/assets/css/admin/main.css' );



                    wp_enqueue_script( 'wrtable_js', WRPL_PLUGIN_URL . '/assets/js/admin/wrtable.min.js');
                    wp_enqueue_script( 'tree-view_js', WRPL_PLUGIN_URL . '/assets/js/admin/bootstrap-treeview.min.js');

                    wp_enqueue_script('main_js', WRPL_PLUGIN_URL . '/assets/js/admin/main.js',array ('jquery'), '1.0', true );
                    wp_enqueue_script( 'main_js');
                    wp_localize_script( 'main_js', 'parameters',['ajax_url'=> admin_url('admin-ajax.php')]);

                    wp_enqueue_script('jquery-ui-draggable');
                    wp_enqueue_script('jquery-ui-droppable');
                    wp_enqueue_script('by_categories_js', WRPL_PLUGIN_URL . '/assets/js/admin/category.js',array('jquery-ui-sortable'), null, true );
                    wp_enqueue_script( 'by_categories_js');
                    wp_localize_script( 'by_categories_js', 'parameters',['ajax_url'=> admin_url('admin-ajax.php'),'source' => 'category']);
                });
            });
        });

    }

    //Assigning the template to each page
    function admin_index(){
        require_once WRPL_PLUGIN_PATH . 'templates/dashboard.php';
    }

    function group_index(){
        require_once WRPL_PLUGIN_PATH . 'templates/price-list.php';
    }

    function products(){
        require_once WRPL_PLUGIN_PATH . 'templates/products.php';
    }


}
?>