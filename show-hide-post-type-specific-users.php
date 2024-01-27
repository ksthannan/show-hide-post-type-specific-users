<?php
/*
Plugin Name: Show Hide Posts Specific Users
Description: It allows you to show or hide post types for specific users
Version: 1.0.0
Author: SHP
Author URI: #
License: GPLv2
Text Domain: shpost

This allows me to show or hide specific post types to specific users. It is a property website with a listing of custom post types (properties). I would like to have something like “Visible for” for the single property entries in the dashboard. For example, if “Frank” is logged in, he should only see those items which are checked for the user “Frank”
 */


if (!defined('ABSPATH')) {
    exit;
}

// Constant values 
define('SHPOST_VERSION', '1.0.0');
define('SHPOST_FILE', __FILE__);
define('SHPOST_PATH', __DIR__);
define('SHPOST_URL', plugins_url('', SHPOST_FILE));
define('SHPOST_ASSETS', SHPOST_URL . '/assets');

register_activation_hook(__FILE__, 'shpost_activation');
function shpost_activation(){

}

register_deactivation_hook(__FILE__, 'shpost_deactivation');
function shpost_deactivation(){

}

add_action('plugins_loaded', 'shpost_init_plugin');
function shpost_init_plugin(){
	load_plugin_textdomain('shpost', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}


// Admin enqueue 
add_action('admin_enqueue_scripts', 'shpost_admin_scripts');
function shpost_admin_scripts()
{

    wp_enqueue_style('shpost-select2-style', SHPOST_ASSETS . '/css/select2.min.css', array(), SHPOST_VERSION, 'all');

    wp_enqueue_style('shpost-admin-style', SHPOST_ASSETS . '/css/admin_style.css', array(), SHPOST_VERSION, 'all');

    wp_enqueue_script('shpost-select2-script', SHPOST_ASSETS . '/js/select2.full.min.js', array('jquery' ), SHPOST_VERSION, true);

    wp_enqueue_script('shpost-admin-script', SHPOST_ASSETS . '/js/admin_script.js', array('jquery' ), SHPOST_VERSION, true);

    wp_localize_script( 'shpost-admin-script', 'shpost_admin_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ), 
        'plugin_assets' => SHPOST_ASSETS, 
	));
}

// Frontend enqueue 
add_action('wp_enqueue_scripts', 'shpost_frontend_scripts');
function shpost_frontend_scripts()
{
    wp_enqueue_style('shpost-style', SHPOST_ASSETS . '/css/style.css', array(), SHPOST_VERSION, 'all');

    wp_enqueue_script('shpost-script', SHPOST_ASSETS . '/js/script.js', array('jquery'), SHPOST_VERSION, true);

    wp_localize_script( 'shpost-script', 'shpost_ajax_object', array( 
        'ajax_url' => admin_url( 'admin-ajax.php' ), 
	));
}

/**
 * Add settings menu in the plugin action link 
 */
function shpost_plugin_add_settings_link( $links ) {
    $settings_link = '<a href="' . admin_url( 'options-general.php?page=show-hide-post-type-specific-users' ) . '">' . __( 'Settings', 'shpost' ) . '</a>';
    array_push( $links, $settings_link );
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'shpost_plugin_add_settings_link' );

// require files 
require_once('inc/admin/admin.php');
require_once('inc/admin/admin-functions.php');
require_once('inc/frontend/frontend.php');
require_once('inc/frontend/frontend-functions.php');


