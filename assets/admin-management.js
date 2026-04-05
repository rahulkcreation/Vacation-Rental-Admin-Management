/**
 * Page Management Frontend Logic
 */
(function ($) {
    "use strict";

    $(document).ready(function () {
        const $form = $("#admin-mang-pages-form");
        const $submitBtn = $("#admin-mang-submit-pages");

        /**
         * Tab Switching Logic
         */
        $(document).on('click', '.am-pm-tab-btn', function() {
            const target = $(this).data('tab');
            
            // Update buttons
            $('.am-pm-tab-btn').removeClass('active');
            $(this).addClass('active');
            
            // Update content areas
            $('.am-pm-tab-content').removeClass('active');
            $(`#am-pm-tab-${target}`).addClass('active');
        });

        /**
         * Form Submission
         */
        $form.on("submit", function (e) {
            e.preventDefault();

            $submitBtn.prop("disabled", true).addClass('loading');
            const entries = [];

            // Collect data from each row (Mapped Pages)
            $(".am-pm-form-row").each(function () {
                const $row = $(this);
                const name = $row.data("name");
                const page_id = $row.find('[name="page_id"]').val();
                const value = $row.find('[name="value"]').val();

                if (name) {
                    entries.push({
                        name: name,
                        page_id: page_id || '',
                        value: value || '',
                    });
                }
            });

            // AJAX Save
            window.adminMang.ajax(
                "save_page_entries",
                { entries: entries },
                function (response) {
                    $submitBtn.prop("disabled", false).removeClass('loading');

                    if (response.success) {
                        window.adminMang.showToaster(response.data.message, "success");

                        // Update dot indicators to synced state
                        $(".am-pm-dot")
                            .removeClass("status-new")
                            .addClass("status-synced");
                    } else {
                        window.adminMang.showToaster(
                            response.data.message || "Error saving changes.",
                            "error",
                        );
                    }
                },
                function () {
                    $submitBtn.prop("disabled", false).removeClass('loading');
                    window.adminMang.showToaster("Network error occurred.", "error");
                },
            );
        });

        /**
         * Track changes and update dots
         */
        $(document).on(
            "change input",
            ".am-pm-select, .am-pm-input",
            function () {
                const $row = $(this).closest(".am-pm-form-row");
                $row
                    .find(".am-pm-dot")
                    .removeClass("status-synced")
                    .addClass("status-new");
            }
        );
    });
})(jQuery);
