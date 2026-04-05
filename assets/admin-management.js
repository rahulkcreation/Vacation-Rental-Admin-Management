/**
 * Page Management Frontend Logic
 */
(function ($) {
  "use strict";

  $(document).ready(function () {
    const $form = $("#admin-mang-pages-form");
    const $submitBtn = $("#admin-mang-submit-pages");

    /**
     * Form Submission
     */
    $form.on("submit", function (e) {
      e.preventDefault();

      $submitBtn.prop("disabled", true).css("opacity", 0.7);
      const entries = [];

      // Collect data from each row
      $(".admin-mang-form-row").each(function () {
        const $row = $(this);
        const name = $row.data("name");
        const type = $row.data("type");
        const page_id = $row.find('[name="page_id"]').val();
        const value = $row.find('[name="value"]').val();

        entries.push({
          name: name,
          page_id: page_id,
          value: value,
        });
      });

      // AJAX Save
      window.adminMang.ajax(
        "save_page_entries",
        { entries: entries },
        function (response) {
          $submitBtn.prop("disabled", false).css("opacity", 1);

          if (response.success) {
            window.adminMang.showToaster(response.data.message, "success");

            // Update dot indicators to synced state
            $(".admin-mang-dot")
              .removeClass("admin-mang-dot-new")
              .addClass("admin-mang-dot-synced");
          } else {
            window.adminMang.showToaster(
              response.data.message || "Error saving changes.",
              "error",
            );
          }
        },
        function () {
          $submitBtn.prop("disabled", false).css("opacity", 1);
          window.adminMang.showToaster("Network error occurred.", "error");
        },
      );
    });

    /**
     * Track changes and update dots
     */
    $(document).on(
      "change input",
      ".admin-mang-select, .admin-mang-input",
      function () {
        const $row = $(this).closest(".admin-mang-form-row");
        $row
          .find(".admin-mang-dot")
          .removeClass("admin-mang-dot-synced")
          .addClass("admin-mang-dot-new");

        // Optional: Highlight changed row
        $row.css("border-color", "var(--admin-mang-warning)");
      },
    );
  });
})(jQuery);
