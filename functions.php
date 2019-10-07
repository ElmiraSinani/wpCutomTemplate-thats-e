<?php
    //ini_set('error_reporting', E_ALL);
    require_once(get_template_directory() . '/includes/libs/helpers.php');
    require_once (get_template_directory() .'/includes/metaboxes/meta-page-settings.php');
    require_once(get_template_directory() . '/templates/lis_top_menu.php');
    
    require_once("templates/inc/portfolio-post-types.php");
    
    
    function load_styles_and_scripts() {
        
    //load styles       
    wp_enqueue_style('jquery-fancybox', get_template_directory_uri() . '/assets/fancybox/source/jquery.fancybox.css');
  
    //load scripts
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery-min', get_template_directory_uri() . '/js/jquery-1.8.3.min.js', array(), '', true);
    }
   
    
    wp_enqueue_script('jquery-isotope', get_template_directory_uri() . '/js/jquery.isotope.js', array(), '', true);
    wp_enqueue_script('jquery-fancybox-pack', get_template_directory_uri() . '/assets/fancybox/source/jquery.fancybox.pack.js', array(), '', true);

    //custom scropts
    wp_enqueue_script('common-scripts', get_template_directory_uri() . '/js/common-scripts.js', array(), '', true);
        
    }

    add_action('wp_enqueue_scripts', 'load_styles_and_scripts');
   

    // Clean up the <head>
    function removeHeadLinks() {
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
   // Page Settings
    if (function_exists('add_theme_support')) {
        add_theme_support('post-thumbnails');           
    }
    
    //Register Menu
    add_action('init', 'lis_menus');

    function lis_menus() {
        register_nav_menus(array(
            'primary-nav' => __('Header Navigation', 'pt_admin_framework'),
            //'secondary-nav' => __('Header Secondary Navigation', 'pt_admin_framework'),
            'footer-nav' => __('Footer Navigation', 'pt_admin_framework')
        ));
    }
	

?>