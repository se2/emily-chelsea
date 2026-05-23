(function ($) {
	$("#order-review-wrapper").stick_in_parent({
		recalc_every: false,
	});

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
})(jQuery);
