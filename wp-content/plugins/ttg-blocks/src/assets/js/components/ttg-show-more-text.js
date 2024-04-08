(function ($) {
	$(document).on(
		"click",
		".ttg-show-more-text .ttg-show-more-text__title",
		function () {
			const $parent = $(this).parents(".ttg-show-more-text");
			if ($parent.hasClass("open")) {
				$parent.removeClass("open");
			} else {
				$parent.addClass("open");
			}
		},
	);
})(jQuery);
