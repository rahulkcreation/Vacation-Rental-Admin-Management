<?php
/**
 * Page Management Template
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Pre-defined Entries Configuration
 */
$admin_mang_default_entries = array(
    array('id' => 1, 'name' => 'My Profile', 'type' => 'page'),
    array('id' => 2, 'name' => 'User Booking', 'type' => 'page'),
    array('id' => 3, 'name' => 'Add New Listing', 'type' => 'page'),
    array('id' => 4, 'name' => 'Host Listings', 'type' => 'page'),
    array('id' => 5, 'name' => 'Listing Archive', 'type' => 'page'),
    array('id' => 6, 'name' => 'Listing Single View', 'type' => 'page'),
    array('id' => 7, 'name' => 'Logout', 'type' => 'value'),
);

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
    $saved_entries[$entry['name']] = array(
        'page_id' => $entry['page_id'],
        'value'   => $entry['value']
    );
}
?>

<div class="admin-mang-container" id="admin-mang-page-management">
    <header class="admin-mang-header">
        <div class="admin-mang-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2L2 7l10 5 10-5-10-5z"></path>
                <path d="M2 17l10 5 10-5"></path>
                <path d="M2 12l10 5 10-5"></path>
            </svg>
        </div>
        <div class="admin-mang-header-content">
            <h1 class="admin-mang-header-title">Page Management</h1>
            <p class="admin-mang-header-subtitle">Map the core features to specific pages or URL values for redirection.</p>
        </div>
    </header>

    <div class="admin-mang-card admin-mang-form-card">
        <div class="admin-mang-card-header">
            <h2 class="admin-mang-card-title">Setup Mappings</h2>
            <div class="admin-mang-card-meta">
                <span class="admin-mang-badge-info">Active Configuration</span>
            </div>
        </div>

        <div class="admin-mang-card-body">
            <form id="admin-mang-pages-form" class="admin-mang-pages-form">
                <div class="admin-mang-form-grid">
                    <?php foreach ($admin_mang_default_entries as $entry) : 
                        $name = $entry['name'];
                        $saved_page_id = isset($saved_entries[$name]) ? $saved_entries[$name]['page_id'] : '';
                        $saved_value = isset($saved_entries[$name]) ? $saved_entries[$name]['value'] : '';
                    ?>
                        <div class="admin-mang-form-row" data-name="<?php echo esc_attr($name); ?>" data-type="<?php echo esc_attr($entry['type']); ?>">
                            <div class="admin-mang-field-label">
                                <label><?php echo esc_html($name); ?></label>
                                <span class="admin-mang-field-hint">Fixed Label</span>
                            </div>

                            <div class="admin-mang-field-input-wrapper">
                                <?php if ($entry['type'] === 'page') : ?>
                                    <select name="page_id" class="admin-mang-select">
                                        <option value="">-- Select Page --</option>
                                        <?php foreach ($all_pages as $page) : ?>
                                            <option value="<?php echo esc_attr($page->ID); ?>" <?php selected($saved_page_id, $page->ID); ?>>
                                                <?php echo esc_html($page->post_title); ?> (ID: <?php echo $page->ID; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="value" value="">
                                <?php else : ?>
                                    <input type="text" 
                                           name="value" 
                                           class="admin-mang-input" 
                                           placeholder="e.g. /wp-login.php?action=logout" 
                                           value="<?php echo esc_attr($saved_value); ?>">
                                    <input type="hidden" name="page_id" value="">
                                <?php endif; ?>
                            </div>

                            <div class="admin-mang-status-indicator">
                                <?php if (isset($saved_entries[$name])) : ?>
                                    <span class="admin-mang-dot admin-mang-dot-synced" title="Synced with Database"></span>
                                <?php else : ?>
                                    <span class="admin-mang-dot admin-mang-dot-new" title="Not saved yet"></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="admin-mang-form-footer">
                    <div class="admin-mang-footer-info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="16" x2="12" y2="12"></line>
                            <line x1="12" y1="8" x2="12.01" y2="8"></line>
                        </svg>
                        <span>All mappings are global and will affect site-wide redirections.</span>
                    </div>
                    <button type="submit" id="admin-mang-submit-pages" class="admin-mang-btn admin-mang-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Save All Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
