<?php
/**
 * Database Schema functions for Admin Management
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Default Schema Configuration
 * Used for UI display and initial DB population.
 */
function admin_mang_get_default_schema() {
    return array(
        array('name' => 'My Profile',           'type' => 'page'),
        array('name' => 'User Booking',        'type' => 'page'),
        array('name' => 'Add New Listing',     'type' => 'page'),
        array('name' => 'Host Listings',       'type' => 'page'),
        array('name' => 'Listing Archive',     'type' => 'page'),
        array('name' => 'Listing Single View', 'type' => 'page'),
        array('name' => 'Logout',              'type' => 'value'),
    );
}

/**
 * Create custom table for admin management
 */
function admin_mang_create_tables() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(255) NOT NULL,
        page_id VARCHAR(255) DEFAULT NULL,
        value TEXT DEFAULT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY unique_name (name)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);

    // Initial population of default entries if missing
    $defaults = admin_mang_get_default_schema();
    foreach ($defaults as $entry) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE name = %s",
            $entry['name']
        ));

        if (!$exists) {
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $entry['name'],
                    'page_id' => null,
                    'value' => null,
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql')
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );
        }
    }
}

/**
 * Check if table exists
 */
function admin_mang_table_exists() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    return $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
}

/**
 * Get table row count
 */
function admin_mang_get_row_count() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return 0;
    return $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
}

/**
 * Get last update timestamp
 */
function admin_mang_get_last_updated() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return 'N/A';
    $last_updated = $wpdb->get_var("SELECT MAX(updated_at) FROM $table_name");
    return $last_updated ? $last_updated : 'N/A';
}

/**
 * Get all entries
 */
function admin_mang_get_all_entries() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'admin_management';
    if (!admin_mang_table_exists()) return array();
    return $wpdb->get_results("SELECT * FROM $table_name ORDER BY id ASC", ARRAY_A);
}
