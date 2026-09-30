(function ($) {
	// Plain sticky sidebar: toggle a class instead of sticky-kit's continuous
	// top recalculation, which was what made the Cardknox card iframes
	// flicker on scroll. Only the class changes on scroll; position/top/width
	// are only written when the state (top/stuck/bottom) actually changes.
	var $wrapper = $("#order-review-wrapper");
	var $parent = $wrapper.parent();
	var STICK_OFFSET = 20; // gap from top of viewport while stuck
	var MIN_WIDTH = 1100; // matches $grid-breakpoints.md, where columns sit side by side
	var ticking = false;

	function resetPosition() {
		$wrapper
			.removeClass("is-sticky is-bottom")
			.css({ position: "", top: "", bottom: "", width: "" });
	}

	function updateSticky() {
		ticking = false;

		if ($(window).width() < MIN_WIDTH) {
			resetPosition();
			return;
		}

		var parentRect = $parent[0].getBoundingClientRect();
		var wrapperHeight = $wrapper.outerHeight();

		if (parentRect.top > STICK_OFFSET) {
			resetPosition();
		} else if (parentRect.bottom < wrapperHeight + STICK_OFFSET) {
			$wrapper
				.removeClass("is-sticky")
				.addClass("is-bottom")
				.css({ position: "absolute", top: "", bottom: 0, width: "" });
		} else {
			$wrapper.removeClass("is-bottom").addClass("is-sticky").css({
				position: "fixed",
				top: STICK_OFFSET,
				bottom: "",
				width: $parent.width(),
			});
		}
	}

	function onScroll() {
		if (!ticking) {
			ticking = true;
			requestAnimationFrame(updateSticky);
		}
	}

	if ($wrapper.length && $parent.length) {
		$parent.css("position", "relative");
		$(window).on("scroll resize", onScroll);
		$(document.body).on("updated_checkout", updateSticky);
		updateSticky();
	}

	$(document).on("click", ".build-ring-collection__change", function (e) {
		e.preventDefault();
		var uuid = $(this).data("uuid");
		var mode = $(this).data("mode");
		var $btn = $(this);
		$btn.prop("disabled", true);
		$.ajax({
			url:
				jsData.ajaxUrl +
				"?action=change_item&uuid=" +
				uuid +
				"&mode=" +
				mode +
				"&force=1",
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
