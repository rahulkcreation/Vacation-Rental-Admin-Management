/**
 * Global Utilities JS
 * Modern AJAX Wrapper for Admin Management Plugin
 */
(function ($) {
    "use strict";

    window.adminMang = {
        /**
         * AJAX Wrapper
         * Uses standard jQuery object-based serialization for better nested data support.
         */
        ajax: function (action, data, successCallback, errorCallback) {
            // Prepare common parameters + custom data
            const postData = $.extend({
                action: "admin_mang_" + action,
                nonce: admin_mang_obj.nonce
            }, data);

            $.ajax({
                url: admin_mang_obj.ajax_url,
                type: "POST",
                data: postData,
                // Default processData and contentType are fine for WP AJAX
                success: function (response) {
                    if (successCallback) successCallback(response);
                },
                error: function (xhr, status, error) {
                    console.error("Admin Mang AJAX Error:", error);
                    if (errorCallback) errorCallback(error);
                },
            });
        },

        /**
         * Show Toaster Wrapper
         */
        showToaster: function(message, type) {
            if (typeof window.adminMangToaster === 'function') {
                window.adminMangToaster(message, type);
            }
        }
    };
})(jQuery);
