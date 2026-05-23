(function ($) {
	const ajaxUrl = "/wp-admin/admin-ajax.php";

	function removeDesign(uuid, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=remove_design&uuid=" + uuid,
			success: (res) => {
				if (res.isSuccess) {
					callback();
				}
			},
			dataType: "json",
		});
	}

	function removeTrayItem(id, callback = () => {}) {
		jQuery.ajax({
			url: ajaxUrl + "?action=remove_tray_item&id=" + id.toString(),
			success: (res) => {
				callback(res);
			},
			dataType: "json",
		});
	}

	$(document).on("click", ".build-ring-collection__change", function (e) {
		e.preventDefault();
		var uuid = $(this).data("uuid");
		var mode = $(this).data("mode");
		var $btn = $(this);
		$btn.prop("disabled", true);
		$.ajax({
			url: jsData.ajaxUrl + "?action=change_item&uuid=" + uuid + "&mode=" + mode + "&force=1",
			success: function (res) {
				if (res.isSuccess && jsData.buildRingUrl) {
					window.location.href = jsData.buildRingUrl;
				} else {
					$btn.prop("disabled", false);
				}
			},
			error: function () {
				$btn.prop("disabled", false);
			},
			dataType: "json",
		});
	});

	$(".remove-tray-item-from-cart").off("click");
	$(document).on("click", ".remove-tray-item-from-cart", function (e) {
		e.preventDefault();
		var id = $(this).data("uuid");
		removeTrayItem(id, () => {
			location.reload();
		});
	});

	$(".remove-design-from-cart").off("click");
	$(document).on("click", ".remove-design-from-cart", function (e) {
		e.preventDefault();
		var uuid = $(this).data("uuid");
		removeDesign(uuid, () => {
			location.reload();
		});
	});
})(jQuery);
