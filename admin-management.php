<?php
/**
 * Plugin Name: Admin Management
 * Plugin URI: https://arttechfuzion.com
 * Description: A comprehensive admin management system for managing pages, settings, and database operations.
 * Version: 1.1.0
 * Author: Art-Tech Fuzion
 * Text Domain: admin-management
 * 
 * @package AdminManagement
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define constants
 */
define('ADMIN_MANG_VERSION', '1.1.0');
define('ADMIN_MANG_PATH', plugin_dir_path(__FILE__));
define('ADMIN_MANG_URL', plugin_dir_url(__FILE__));

/**
 * Include files
 */
require_once ADMIN_MANG_PATH . 'includes/db-schema.php';
require_once ADMIN_MANG_PATH . 'includes/assets-loader.php'; // New Loader
require_once ADMIN_MANG_PATH . 'includes/class-admin-mang-ajax.php';

/**
 * Main Plugin Class
 */
class Admin_Mang_Plugin {

    public function __construct() {
        add_action('admin_menu', array($this, 'admin_mang_register_menus'));
        add_action('admin_enqueue_scripts', array($this, 'admin_mang_enqueue_assets'));
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'admin_mang_add_settings_link'));

        // Initialize AJAX handlers
        new Admin_Mang_AJAX();
    }

    /**
     * Add Settings link on plugins page
     */
    public function admin_mang_add_settings_link($links) {
        $settings_link = '<a href="admin.php?page=admin-management">Settings</a>';
        array_push($links, $settings_link);
        return $links;
    }

    /**
     * Register Menus
     */
    public function admin_mang_register_menus() {
        add_menu_page(
            'Admin Management',
            'Admin Management',
            'manage_options',
            'admin-management',
            array($this, 'admin_mang_page_management_screen'),
            'dashicons-admin-generic',
            30
        );

        add_submenu_page(
            'admin-management',
            'Page Management',
            'Page Management',
            'manage_options',
            'admin-management',
            array($this, 'admin_mang_page_management_screen')
        );

        add_submenu_page(
            'admin-management',
            'Database',
            'Database',
            'manage_options',
            'admin-mang-database',
            array($this, 'admin_mang_database_screen')
        );
    }

    /**
     * Enqueue Assets
     * Delegating all logic to the centralized loader.
     */
    public function admin_mang_enqueue_assets($hook) {
        admin_mang_load_assets($hook);
    }

    /**
     * Page Management Screen
     */
    public function admin_mang_page_management_screen() {
        include ADMIN_MANG_PATH . 'templates/admin-management.php';
        include ADMIN_MANG_PATH . 'templates/toaster.php';
    }

    /**
     * Database Management Screen
     */
    public function admin_mang_database_screen() {
        include ADMIN_MANG_PATH . 'templates/database.php';
        include ADMIN_MANG_PATH . 'templates/toaster.php';
    }
}

/**
 * Initialize Plugin
 */
new Admin_Mang_Plugin();
