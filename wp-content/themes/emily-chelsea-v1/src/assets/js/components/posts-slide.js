(function ($) {
	const defaultConfig = {
		slidesToShow: 2,
		autoplay: true,
		responsive: [
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
				},
			},
		],
	};
	$(".ttg-posts-slide").each(function (index, el) {
		const config = $(el).attr("data-config")
			? JSON.parse($(el).attr("data-config"))
			: defaultConfig;
		$(el).slick(config);
	});
})(jQuery);
