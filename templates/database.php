<?php
/**
 * Database Management Template
 */

if (!defined('ABSPATH')) {
    exit;
}

$exists = admin_mang_table_exists();
$row_count = admin_mang_get_row_count();
$last_updated = admin_mang_get_last_updated();
$data = admin_mang_get_all_entries();
?>

<div class="admin-mang-container" id="admin-mang-database-page">
    <header class="admin-mang-header">
        <div class="admin-mang-header-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
            </svg>
        </div>
        <div class="admin-mang-header-content">
            <h1 class="admin-mang-header-title">Database Management</h1>
            <p class="admin-mang-header-subtitle">Check and manage the <code>wp_admin_management</code> table status.</p>
        </div>
    </header>

    <div class="admin-mang-grid">
        <!-- Status Card -->
        <div class="admin-mang-card">
            <div class="admin-mang-card-header">
                <h2 class="admin-mang-card-title">Table Status Overview</h2>
                <div id="admin-mang-table-badge" class="admin-mang-status <?php echo $exists ? 'admin-mang-status-exists' : 'admin-mang-status-missing'; ?>">
                    <svg class="admin-mang-status-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <?php if ($exists) : ?>
                            <polyline points="20 6 9 17 4 12"></polyline>
                        <?php else : ?>
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        <?php endif; ?>
                    </svg>
                    <span id="admin-mang-status-text"><?php echo $exists ? 'EXISTS' : 'NOT EXISTS'; ?></span>
                </div>
            </div>
            <div class="admin-mang-card-body">
                <div class="admin-mang-status-info">
                    <div class="admin-mang-info-item">
                        <span class="admin-mang-info-label">Total Rows:</span>
                        <span id="admin-mang-info-count" class="admin-mang-info-value"><?php echo $row_count; ?> entries</span>
                    </div>
                    <div class="admin-mang-info-item">
                        <span class="admin-mang-info-label">Last Updated:</span>
                        <span id="admin-mang-info-date" class="admin-mang-info-value"><?php echo $last_updated; ?></span>
                    </div>
                </div>

                <div class="admin-mang-actions">
                    <button type="button" id="admin-mang-btn-update" class="admin-mang-btn admin-mang-btn-primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 2v6h-6"></path>
                            <path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path>
                            <path d="M3 22v-6h6"></path>
                            <path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path>
                        </svg>
                        Create / Update Table
                    </button>
                    <button type="button" id="admin-mang-btn-refresh" class="admin-mang-btn admin-mang-btn-secondary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                        Refresh status
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="admin-mang-card admin-mang-data-card">
            <div class="admin-mang-card-header">
                <h2 class="admin-mang-card-title">Database Records</h2>
            </div>
            <div class="admin-mang-card-body admin-mang-no-padding">
                <div class="admin-mang-table-wrapper">
                    <table class="admin-mang-table" id="admin-mang-data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Page ID</th>
                                <th>Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="admin-mang-table-body">
                            <?php if (empty($data)) : ?>
                                <tr class="admin-mang-empty-row">
                                    <td colspan="5">No database records found.</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($data as $row) : ?>
                                    <tr>
                                        <td><?php echo esc_html($row['id']); ?></td>
                                        <td><strong><?php echo esc_html($row['name']); ?></strong></td>
                                        <td><code><?php echo $row['page_id'] ? esc_html($row['page_id']) : '-'; ?></code></td>
                                        <td><?php echo $row['value'] ? esc_html($row['value']) : '-'; ?></td>
                                        <td>
                                            <span class="admin-mang-badge-row">Synced</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
