(function ($) {
	$(document).on("click", ".quantity__plus", function (e) {
		e.preventDefault();
		const parent = $(this).parents(".quantity");
		const $input = $(parent).find(".input-text");
		const currentValue = parseInt($input.val(), 10);
		$input.val(currentValue + 1);
		$input.trigger("change");
	});

	$(document).on("click", ".quantity__minus", function (e) {
		e.preventDefault();
		const parent = $(this).parents(".quantity");
		const $input = $(parent).find(".input-text");
		const currentValue = parseInt($input.val(), 10);
		$input.val(currentValue >= 1 ? currentValue - 1 : 0);
		$input.trigger("change");
	});
})(jQuery);
