<?php

/*
*
* @package Yariko
*
*/

namespace Wrpl\Inc\Base;

class Enqueue{

    public function register(){

        add_action( 'admin_enqueue_scripts', array( $this , 'enqueue_admin' ) ); //action to include script to the backend, in order to include in the frontend is just wp_enqueue_scripts instead admin_enqueue_scripts
       // add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend'));

    }

    function enqueue_admin(){
        //enqueue all our scripts admin
        wp_enqueue_style( 'bootstrap_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );


        //wp_enqueue_script( 'jquery_js', 'https://code.jquery.com/jquery-3.2.1.slim.min.js');
        wp_enqueue_script( 'bootstrap_js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js');

        wp_enqueue_script( 'popper_js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js');
        wp_register_script('mediaelement', plugins_url('wp-mediaelement.min.js', __FILE__), array('jquery'), '4.8.2', true);
        wp_enqueue_script('mediaelement');
    }

}