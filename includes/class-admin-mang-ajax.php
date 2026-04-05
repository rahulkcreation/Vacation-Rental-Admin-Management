<?php
/**
 * AJAX Handler for Admin Management
 */

if (!defined('ABSPATH')) {
    exit;
}

class Admin_Mang_AJAX {

    public function __construct() {
        add_action('wp_ajax_admin_mang_update_tables', array($this, 'admin_mang_update_tables'));
        add_action('wp_ajax_admin_mang_refresh_status', array($this, 'admin_mang_refresh_status'));
        add_action('wp_ajax_admin_mang_save_page_entries', array($this, 'admin_mang_save_page_entries'));
    }

    /**
     * Nonce verification and capability check
     */
    private function verify_request() {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'admin_mang_nonce')) {
            wp_send_json_error(array('message' => 'Invalid security token.'));
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => 'Unauthorized access.'));
        }
    }

    /**
     * Create/Update Database Tables
     */
    public function admin_mang_update_tables() {
        $this->verify_request();

        try {
            admin_mang_create_tables();
            wp_send_json_success(array('message' => 'Database tables created/updated successfully.'));
        } catch (Exception $e) {
            wp_send_json_error(array('message' => 'Error updating tables: ' . $e->getMessage()));
        }
    }

    /**
     * Refresh Status Logic
     */
    public function admin_mang_refresh_status() {
        $this->verify_request();

        $status = array(
            'exists' => admin_mang_table_exists(),
            'rowCount' => admin_mang_get_row_count(),
            'lastUpdated' => admin_mang_get_last_updated(),
            'data' => admin_mang_get_all_entries()
        );

        wp_send_json_success($status);
    }

    /**
     * Save Page Management Entries
     */
    public function admin_mang_save_page_entries() {
        $this->verify_request();

        global $wpdb;
        $table_name = $wpdb->prefix . 'admin_management';

        $entries = isset($_POST['entries']) ? $_POST['entries'] : array();

        if (empty($entries)) {
            wp_send_json_error(array('message' => 'No data provided to save.'));
        }

        foreach ($entries as $entry) {
            $name = sanitize_text_field($entry['name']);
            $page_id = !empty($entry['page_id']) ? sanitize_text_field($entry['page_id']) : null;
            $value = !empty($entry['value']) ? sanitize_textarea_field($entry['value']) : null;

            // Check if entry exists by name
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM $table_name WHERE name = %s",
                $name
            ));

            if ($existing) {
                // Update
                $wpdb->update(
                    $table_name,
                    array(
                        'page_id' => $page_id,
                        'value' => $value,
                        'updated_at' => current_time('mysql')
                    ),
                    array('id' => $existing),
                    array('%s', '%s', '%s'),
                    array('%d')
                );
            } else {
                // Insert
                $wpdb->insert(
                    $table_name,
                    array(
                        'name' => $name,
                        'page_id' => $page_id,
                        'value' => $value,
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    ),
                    array('%s', '%s', '%s', '%s', '%s')
                );
            }
        }

        wp_send_json_success(array('message' => 'Changes saved successfully to database.'));
    }
}
