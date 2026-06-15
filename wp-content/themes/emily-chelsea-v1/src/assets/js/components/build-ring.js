(function ($) {
	function refreshModal(onSuccess) {
		jQuery.ajax({
			url: window.location.href,
			success: function (res) {
				var body = $(res).find(".build-ring-modal__body").html();
				$(document).find(".build-ring-modal__body").html(body);
				if (onSuccess) onSuccess();
			},
		});
	}

	function buildAnotherRing(onSuccess) {
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php?action=build_another_ring",
			success: function (res) {
				if (onSuccess) onSuccess();
			},
		});
	}

	$(".single_add_to_cart_button").off("click");

	$(".choose-this-setting").on("click", function (e) {
		e.preventDefault();
		const data = $(this).closest("form").serialize();
		const params = new URLSearchParams(data);
		const redirect = $(this).attr("href");

		params.delete("add-to-cart");

		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=add_ring&" + params.toString(),
			success: function () {
				if (redirect) {
					if (redirect.indexOf("step=3") >= 0) {
						buildAnotherRing(() => {
							window.location.href = redirect;
						});
						return;
					}

					window.location.href = redirect;
					return;
				}
				$(".build-ring-modal").removeClass("d-none");
			},
		});
	});

	$(".choose-this-stone").on("click", function (e) {
		e.preventDefault();
		const stoneId = $(this).val();

		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=add_stone&stone_id=" + stoneId,
			success: function () {
				refreshModal(() => {
					$(".build-ring-modal").removeClass("d-none");
				});
			},
		});
	});

	$(".add-stone-to-setting").on("click", function (e) {
		e.preventDefault();
		const redirect = $(this).attr("href");
		const stoneId = $(this).val();

		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=add_stone_to_ring&stone_id=" +
				stoneId,
			success: function () {
				window.location.href = redirect;
			},
		});
	});

	$(".build-ring-modal__close").on("click", function () {
		$(".build-ring-modal").addClass("d-none");
	});

	$(".change-ring").on("click", function (e) {
		e.preventDefault();

		const redirect = $(this).attr("href");
		$productId = $(this).attr("data-ring-id");
		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=change_ring&ring_id=" +
				$productId,
			success: function () {
				window.location.href = redirect;
			},
		});
	});

	$(".change-stone").on("click", function (e) {
		e.preventDefault();

		const redirect = $(this).attr("href");
		$productId = $(this).attr("data-ring-id");
		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=change_stone&ring_id=" +
				$productId,
			success: function () {
				window.location.href = redirect;
			},
		});
	});

	$(document).on("click", ".update-stone", function (e) {
		e.preventDefault();
		const redirect = $(this).attr("href");
		const stoneId = $(this).attr("data-stone-id");
		jQuery.ajax({
			url:
				"/wp-admin/admin-ajax.php?action=add_stone_to_ring&stone_id=" +
				stoneId,
			success: function () {
				if (redirect.indexOf("step=3") >= 0) {
					buildAnotherRing(() => {
						window.location.href = redirect;
					});
					return;
				}
				window.location.href = redirect;
			},
		});
	});

	$(".confirm-rings-btn").on("click", function (e) {
		e.preventDefault();
		const redirect = $(this).attr("href");
		jQuery.ajax({
			url: "/wp-admin/admin-ajax.php?action=confirm_rings",
			success: function () {
				window.location.href = redirect;
			},
		});
	});

	$(".confirm-rings__add-another-ring").on("click", function (e) {
		e.preventDefault();
		const redirect = $(this).attr("href");
		buildAnotherRing(() => {
			window.location.href = redirect;
		});
	});
})(jQuery);
