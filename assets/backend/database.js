/**
 * Database Management Frontend Logic
 */
(function ($) {
  "use strict";

  $(document).ready(function () {
    const $btnUpdate = $("#admin-mang-btn-update");
    const $btnRefresh = $("#admin-mang-btn-refresh");
    const $statusBadge = $("#admin-mang-table-badge");
    const $statusText = $("#admin-mang-status-text");
    const $infoCount = $("#admin-mang-info-count");
    const $infoDate = $("#admin-mang-info-date");
    const $tableBody = $("#admin-mang-table-body");

    /**
     * Update Table Action
     */
    $btnUpdate.on("click", function () {
      $btnUpdate.prop("disabled", true).css("opacity", 0.7);

      window.adminMang.ajax(
        "update_tables",
        {},
        function (response) {
          $btnUpdate.prop("disabled", false).css("opacity", 1);

          if (response.success) {
            window.adminMang.showToaster(response.data.message, "success");
            refreshStatus();
          } else {
            window.adminMang.showToaster(
              response.data.message || "Error updating tables.",
              "error",
            );
          }
        },
        function () {
          $btnUpdate.prop("disabled", false).css("opacity", 1);
          window.adminMang.showToaster("Network error occurred.", "error");
        },
      );
    });

    /**
     * Refresh Status Action
     */
    $btnRefresh.on("click", function () {
      refreshStatus(true);
    });

    /**
     * Refresh Status Logic
     */
    function refreshStatus(showToast = false) {
      $btnRefresh.find("svg").addClass("admin-mang-spin");

      window.adminMang.ajax("refresh_status", {}, function (response) {
        $btnRefresh.find("svg").removeClass("admin-mang-spin");

        if (response.success) {
          const data = response.data;

          // Update Badge
          $statusBadge.removeClass(
            "admin-mang-status-exists admin-mang-status-missing",
          );
          if (data.exists) {
            $statusBadge.addClass("admin-mang-status-exists");
            $statusText.text("EXISTS");
            $statusBadge
              .find("svg")
              .html('<polyline points="20 6 9 17 4 12"></polyline>');
          } else {
            $statusBadge.addClass("admin-mang-status-missing");
            $statusText.text("NOT EXISTS");
            $statusBadge
              .find("svg")
              .html(
                '<circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line>',
              );
          }

          // Update Info
          $infoCount.text(data.rowCount + " entries");
          $infoDate.text(data.lastUpdated);

          // Update Table
          updateTableData(data.data);

          if (showToast) {
            window.adminMang.showToaster(
              "Status refreshed successfully.",
              "success",
            );
          }
        }
      });
    }

    /**
     * Update Data Table
     */
    function updateTableData(records) {
      if (!records || records.length === 0) {
        $tableBody.html(
          '<tr class="admin-mang-empty-row"><td colspan="5">No database records found.</td></tr>',
        );
        return;
      }

      let html = "";
      records.forEach(function (row) {
        html += `
                    <tr>
                        <td>${row.id}</td>
                        <td><strong>${row.name}</strong></td>
                        <td><code>${row.page_id ? row.page_id : "-"}</code></td>
                        <td>${row.value ? row.value : "-"}</td>
                        <td><span class="admin-mang-badge-row">Synced</span></td>
                    </tr>
                `;
      });
      $tableBody.html(html);
    }

    // CSS for spinner in JS context
    const style = document.createElement("style");
    style.innerHTML = `
            .admin-mang-spin {
                animation: admin-mang-spin 1s linear infinite;
            }
        `;
    document.head.appendChild(style);
  });
})(jQuery);
