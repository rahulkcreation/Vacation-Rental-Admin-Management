<?php
/**
 * Page Management Template
 * Modern UI with Tabbed Navigation
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Configuration from Database Schema
 */
$admin_mang_default_entries = admin_mang_get_default_schema();

/**
 * Get all WordPress pages
 */
$all_pages = get_pages();

/**
 * Get saved entries from database
 */
$saved_raw = admin_mang_get_all_entries();
$saved_entries = array();
foreach ($saved_raw as $entry) {
    if (isset($entry['name'])) {
        $saved_entries[$entry['name']] = array(
            'page_id' => $entry['page_id'],
            'value'   => $entry['value']
        );
    }
}
?>

<div class="am-pm-container" id="admin-mang-page-management">
    <!-- Main Heading -->
    <header class="am-pm-header">
        <h1 class="am-pm-title">Dashboard Settings</h1>
    </header>

    <!-- Card Wrapper -->
    <div class="am-pm-dashboard-card">
        <!-- Tabs Navigation -->
        <nav class="am-pm-tabs">
            <button type="button" class="am-pm-tab-btn active" data-tab="mappings">Page Mappings</button>
            <button type="button" class="am-pm-tab-btn" data-tab="general">General</button>
        </nav>

        <form id="admin-mang-pages-form" class="am-pm-form">
            <!-- Tab 1: Mappings -->
            <div id="am-pm-tab-mappings" class="am-pm-tab-content active">
                <div class="am-pm-form-grid">
                    <?php foreach ($admin_mang_default_entries as $entry) : 
                        if ($entry['type'] !== 'page') continue;
                        $name = $entry['name'];
                        $label = isset($entry['label']) ? $entry['label'] : $name;
                        $saved_page_id = isset($saved_entries[$name]) ? $saved_entries[$name]['page_id'] : '';
                    ?>
                        <div class="am-pm-form-row" data-name="<?php echo esc_attr($name); ?>" data-type="page">
                            <label><?php echo esc_html($label); ?></label>
                            
                            <div class="am-pm-field-wrapper">
                                <select name="page_id" class="am-pm-select">
                                    <option value="">-- Select Page --</option>
                                    <?php foreach ($all_pages as $page) : ?>
                                        <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($saved_page_id, $page->ID); ?>>
                                            <?php echo esc_html($page->post_title); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="value" value="">
                            </div>

                            <div class="am-pm-status">
                                <span class="am-pm-dot <?php echo isset($saved_entries[$name]) ? 'status-synced' : 'status-new'; ?>" title="Database Status"></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tab 2: General -->
            <div id="am-pm-tab-general" class="am-pm-tab-content">
                <div class="am-pm-form-grid">
                    <?php foreach ($admin_mang_default_entries as $entry) : 
                        if ($entry['type'] !== 'value') continue;
                        $name = $entry['name'];
                        $label = isset($entry['label']) ? $entry['label'] : $name;
                        $saved_value = isset($saved_entries[$name]) ? $saved_entries[$name]['value'] : '';
                    ?>
                        <?php 
                            $slug = strtolower(str_replace(' ', '-', $name));
                            $placeholder = ($name === 'Logout') ? 'e.g. /wp-login.php?action=logout' : 'e.g. /wp-login.php';
                        ?>
                        <div class="am-pm-form-row" data-name="<?php echo esc_attr($name); ?>" data-type="value">
                            <label for="am-pm-input-<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></label>
                            
                            <div class="am-pm-field-wrapper">
                                <input type="text" 
                                       id="am-pm-input-<?php echo esc_attr($slug); ?>"
                                       name="value" 
                                       class="am-pm-input" 
                                       placeholder="<?php echo esc_attr($placeholder); ?>" 
                                       value="<?php echo esc_attr($saved_value); ?>">
                                <input type="hidden" name="page_id" value="">
                            </div>

                            <div class="am-pm-status">
                                <span class="am-pm-dot <?php echo isset($saved_entries[$name]) ? 'status-synced' : 'status-new'; ?>" title="Database Status"></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Action Area -->
            <div class="am-pm-footer">
                <button type="submit" id="admin-mang-submit-pages" class="am-pm-save-btn">
                    <span class="btn-text">Save Changes</span>
                    <span class="btn-loader"></span>
                </button>
            </div>
        </form>
    </div>
</div>
