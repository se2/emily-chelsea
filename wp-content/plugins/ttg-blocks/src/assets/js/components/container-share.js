(function ($) {
	$(".container-share__social__inner").each(function (index, el) {
		$(el).stick_in_parent({
			offset_top: $("#new-header-placeholder").height() + 20,
		});
	});
})(jQuery);
