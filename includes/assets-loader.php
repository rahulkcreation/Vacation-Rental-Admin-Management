<?php
/**
 * Assets Loader for Admin Management Plugin
 * Centralizes all CSS and JS loading.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main function to load assets based on hook
 * Handles common global assets and screen-specific ones.
 */
function admin_mang_load_assets($hook) {
    // Only load on our plugin screens
    $allowed_hooks = array(
        'toplevel_page_admin-management', 
        'admin-management_page_admin-mang-database'
    );

    if (!in_array($hook, $allowed_hooks)) {
        return;
    }

    // 1. Load Design Tokens (Main Global CSS)
    wp_enqueue_style(
        'admin-mang-design-tokens',
        ADMIN_MANG_URL . 'assets/global.css',
        array(),
        filemtime(ADMIN_MANG_PATH . 'assets/global.css')
    );

    // 2. Load Global Utilities (JS)
    wp_enqueue_script(
        'admin-mang-global-utils',
        ADMIN_MANG_URL . 'assets/global-utils.js',
        array('jquery'),
        filemtime(ADMIN_MANG_PATH . 'assets/global-utils.js'),
        true
    );

    // Localize for AJAX
    wp_localize_script('admin-mang-global-utils', 'admin_mang_obj', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('admin_mang_nonce')
    ));

    // 3. Load Toaster Assets
    wp_enqueue_style(
        'admin-mang-toaster-style',
        ADMIN_MANG_URL . 'assets/toaster.css',
        array('admin-mang-design-tokens'),
        filemtime(ADMIN_MANG_PATH . 'assets/toaster.css')
    );

    wp_enqueue_script(
        'admin-mang-toaster-script',
        ADMIN_MANG_URL . 'assets/toaster.js',
        array('jquery', 'admin-mang-global-utils'),
        filemtime(ADMIN_MANG_PATH . 'assets/toaster.js'),
        true
    );

    // 4. Load Screen Specific Assets
    if ($hook === 'toplevel_page_admin-management') {
        // Page Management Screen
        wp_enqueue_style(
            'admin-mang-page-management-style',
            ADMIN_MANG_URL . 'assets/admin-management.css',
            array('admin-mang-design-tokens', 'admin-mang-toaster-style'),
            filemtime(ADMIN_MANG_PATH . 'assets/admin-management.css')
        );

        wp_enqueue_script(
            'admin-mang-page-management-script',
            ADMIN_MANG_URL . 'assets/admin-management.js',
            array('jquery', 'admin-mang-global-utils', 'admin-mang-toaster-script'),
            filemtime(ADMIN_MANG_PATH . 'assets/admin-management.js'),
            true
        );
    } elseif ($hook === 'admin-management_page_admin-mang-database') {
        // Database Management Screen
        wp_enqueue_style(
            'admin-mang-database-style',
            ADMIN_MANG_URL . 'assets/database.css',
            array('admin-mang-design-tokens', 'admin-mang-toaster-style'),
            filemtime(ADMIN_MANG_PATH . 'assets/database.css')
        );

        wp_enqueue_script(
            'admin-mang-database-script',
            ADMIN_MANG_URL . 'assets/database.js',
            array('jquery', 'admin-mang-global-utils', 'admin-mang-toaster-script'),
            filemtime(ADMIN_MANG_PATH . 'assets/database.js'),
            true
        );
    }
}
