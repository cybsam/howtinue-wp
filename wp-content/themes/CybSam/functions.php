<?php

// add_theme_support('title-tag');

function load_stylesheets(){


    wp_register_style('style', get_template_directory_uri().'/style.css', array(), false, 'all');
    wp_enqueue_style('style');
}


function load_theme_js(){
    wp_register_script('jquery2', get_template_directory_uri().'/js/jquery/jquery-2.2.4.min.js','',1,'true');
    wp_enqueue_script('jquery2');
    wp_register_script('popper', get_template_directory_uri().'/js/bootstrap/popper.min.js','',1,'true');
    wp_enqueue_script('popper');
    wp_register_script('bootstrap4', get_template_directory_uri().'/js/bootstrap/bootstrap.min.js','',1,'true');
    wp_enqueue_script('bootstrap4');
    wp_register_script('cus_plu', get_template_directory_uri().'/js/plugins/plugins.js','',1,'true');
    wp_enqueue_script('cus_plu');
    wp_register_script('activejs', get_template_directory_uri().'/js/active.js','',1,'true');
    wp_enqueue_script('activejs');
    wp_register_script('script', get_template_directory_uri().'script.js','',1,'true');
    wp_enqueue_script('script');
}


add_action('wp_enqueue_scripts','load_stylesheets' );
add_action('wp_enqueue_scripts','load_theme_js' );




add_theme_support('menus');

register_nav_menus( 
    array(
        
        'main-menu' => __('Main Menu', 'theme'),
        'footer-menu' => __('Footer Menu', 'theme'),
    )
    );

