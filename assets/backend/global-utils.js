/**
 * Global Utilities JS
 */
(function ($) {
    "use strict";

    window.adminMang = {
        /**
         * AJAX Wrapper
         */
        ajax: function (action, data, successCallback, errorCallback) {
            const formData = new FormData();
            formData.append("action", "admin_mang_" + action);
            formData.append("nonce", admin_mang_obj.nonce);

            for (const key in data) {
                formData.append(key, data[key]);
            }

            $.ajax({
                url: admin_mang_obj.ajax_url,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (successCallback) successCallback(response);
                },
                error: function (xhr, status, error) {
                    if (errorCallback) errorCallback(error);
                },
            });
        }
    };
})(jQuery);
