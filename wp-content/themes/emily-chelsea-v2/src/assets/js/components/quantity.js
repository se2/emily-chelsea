(function ($) {
	$(document).on("click", ".quantity__plus", function (e) {
		e.preventDefault();
		const parent = $(this).parents(".quantity");
		const $input = $(parent).find(".input-text");
		const currentValue = parseInt($input.val(), 10);
		const readOnly = $input.props("readonly");

		if (readOnly) return;

		$input.val(currentValue + 1);
		$input.trigger("change");
	});

	$(document).on("click", ".quantity__minus", function (e) {
		e.preventDefault();
		const parent = $(this).parents(".quantity");
		const $input = $(parent).find(".input-text");

		const min = $input.attr("min");
		const currentValue = parseInt($input.val(), 10);

		const readOnly = $input.props("readonly");

		if (readOnly) return;

		$input.val(currentValue > min ? currentValue - 1 : min);
		$input.trigger("change");
	});
})(jQuery);
