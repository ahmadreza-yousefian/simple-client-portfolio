<?php
/*
Plugin Name: Simple Client Portfolio
Plugin URI:  https://github.com/your-github-username/wp-simple-client-portfolio
Description: Adds a "Clients" CPT and provides Elementor widgets to display them.
Version:     2.0.0
Author:      Ahmadreza
Author URI:  https://github.com/your-github-username
License:     GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: simple-client-portfolio
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SCP_PLUGIN_DIR', plugin_dir_path(__FILE__));

/**
 * =================================================================
 * 1. REGISTER CUSTOM POST TYPE & TAXONOMY
 * =================================================================
 */
function scp_register_post_type_and_taxonomy() {
    // --- Register Custom Post Type: "Clients" ---
    $client_labels = array(
        'name'               => 'Clients',
        'singular_name'      => 'Client',
        'menu_name'          => 'Clients',
        'name_admin_bar'     => 'Client',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Client',
        'new_item'           => 'New Client',
        'edit_item'          => 'Edit Client',
        'view_item'          => 'View Client',
        'all_items'          => 'All Clients',
        'search_items'       => 'Search Clients',
        'not_found'          => 'No clients found.',
        'not_found_in_trash' => 'No clients found in Trash.',
    );
    $client_args = array( 'labels' => $client_labels, 'public' => true, 'show_in_menu' => true, 'menu_position' => 20, 'menu_icon' => 'dashicons-groups', 'supports' => array('title', 'thumbnail'), 'rewrite' => array('slug' => 'clients'), 'has_archive' => true, );
    register_post_type('scp_client', $client_args);

    // --- Register Custom Taxonomy: "Industry" ---
    $industry_labels = array(
        'name'              => 'Industries',
        'singular_name'     => 'Industry',
        'search_items'      => 'Search Industries',
        'all_items'         => 'All Industries',
        'parent_item'       => 'Parent Industry',
        'parent_item_colon' => 'Parent Industry:',
        'edit_item'         => 'Edit Industry',
        'update_item'       => 'Update Industry',
        'add_new_item'      => 'Add New Industry',
        'new_item_name'     => 'New Industry Name',
        'menu_name'         => 'Industries',
    );
    $industry_args = array( 'hierarchical' => true, 'labels' => $industry_labels, 'show_ui' => true, 'show_admin_column' => true, 'query_var' => true, 'rewrite' => array('slug' => 'industry'), );
    register_taxonomy('scp_industry', 'scp_client', $industry_args);
}
add_action('init', 'scp_register_post_type_and_taxonomy');

/**
 * =================================================================
 * 2. ADD CUSTOM META BOX FOR WEBSITE URL
 * =================================================================
 */
function scp_website_url_meta_box_html($post) {
    $website_url = get_post_meta($post->ID, '_scp_website_url', true);
    wp_nonce_field('scp_save_website_url', 'scp_website_url_nonce');
    echo '<label for="scp_website_url_field">Client Website URL:</label>';
    echo '<input type="url" id="scp_website_url_field" name="scp_website_url_field" value="' . esc_attr($website_url) . '" class="widefat" placeholder="https://example.com">';
}
function scp_add_website_url_meta_box() {
    add_meta_box('scp_website_url_box', 'Website Information', 'scp_website_url_meta_box_html', 'scp_client', 'side');
}
add_action('add_meta_boxes', 'scp_add_website_url_meta_box');

function scp_save_website_url_meta_data($post_id) {
    if (!isset($_POST['scp_website_url_nonce']) || !wp_verify_nonce($_POST['scp_website_url_nonce'], 'scp_save_website_url')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (isset($_POST['scp_website_url_field'])) {
        update_post_meta($post_id, '_scp_website_url', esc_url_raw($_POST['scp_website_url_field']));
    }
}
add_action('save_post_scp_client', 'scp_save_website_url_meta_data');

/**
 * =================================================================
 * 3. CUSTOMIZE ADMIN COLUMNS
 * =================================================================
 */
function scp_add_logo_admin_column($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'title') {
            $new_columns['scp_logo'] = 'Logo';
        }
    }
    return $new_columns;
}
add_filter('manage_scp_client_posts_columns', 'scp_add_logo_admin_column');

function scp_display_logo_admin_column($column, $post_id) {
    if ($column === 'scp_logo') {
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, array(80, 80));
        } else {
            echo '—';
        }
    }
}
add_action('manage_scp_client_posts_custom_column', 'scp_display_logo_admin_column', 10, 2);

/**
 * =================================================================
 * 4. ELEMENTOR INTEGRATION
 * =================================================================
 */
function scp_init_elementor_integration() {
    if (did_action('elementor/loaded')) {
        require_once SCP_PLUGIN_DIR . 'includes/elementor-integration.php';
    }
}
add_action('plugins_loaded', 'scp_init_elementor_integration');
