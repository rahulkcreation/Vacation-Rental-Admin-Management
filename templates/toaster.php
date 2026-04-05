<?php

/**
 * Global Toaster Component
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div id="admin-mang-toaster" class="admin-mang-toaster admin-mang-hidden">
    <div class="admin-mang-toaster-icon">
        <svg class="admin-mang-toast-success-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12"></polyline>
        </svg>
        <svg class="admin-mang-toast-error-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="15" y1="9" x2="9" y2="15"></line>
            <line x1="9" y1="9" x2="15" y2="15"></line>
        </svg>
        <svg class="admin-mang-toast-warning-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
            <line x1="12" y1="9" x2="12" y2="13"></line>
            <line x1="12" y1="17" x2="12.01" y2="17"></line>
        </svg>
    </div>
    <div class="admin-mang-toaster-content">
        <div id="admin-mang-toaster-message" class="admin-mang-toaster-message">
            Operation completed successfully!
        </div>
    </div>
    <button class="admin-mang-toaster-close" aria-label="Close Toast">&times;</button>
</div>