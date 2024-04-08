import { simpleParallax } from "./base";

(function ($) {
	jQuery(".simple-parallax").each(function (index, el) {
		const config = JSON.parse($(el).attr("data-config"));
		const $that = $(el);
		console.log("config", config);
		simpleParallax(Number(config?.intensity) || 5, $that);
	});
})(jQuery);
