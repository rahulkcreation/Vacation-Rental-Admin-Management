/**
 * Toaster Component JS
 * Dependent on global-utils.js
 */
(function ($) {
    "use strict";

    if (!window.adminMang) {
        window.adminMang = {};
    }

    /**
     * Show Toaster
     */
    window.adminMang.showToaster = function (message, type = "success") {
        const $toaster = $("#admin-mang-toaster");
        const $message = $("#admin-mang-toaster-message");

        if (!$toaster.length) return;

        $message.text(message);
        $toaster.removeClass("admin-mang-success admin-mang-error admin-mang-warning");
        $toaster.addClass("admin-mang-" + type);

        $toaster.fadeIn(300).css("display", "flex");

        if (window.adminMangToasterTimeout) {
            clearTimeout(window.adminMangToasterTimeout);
        }

        window.adminMangToasterTimeout = setTimeout(function () {
            $toaster.fadeOut(300);
        }, 5000);
    };

    /**
     * Close Toaster Action
     */
    $(document).on("click", ".admin-mang-toaster-close", function () {
        $("#admin-mang-toaster").fadeOut(300);
    });

})(jQuery);
