(function ($) {
	const galleryCount = $(
		".woocommerce-product-gallery__thumbs .woocommerce-product-gallery__image",
	).length;

	$("#woocommerce-product-gallery__image-main img").load(function () {
		$("#woocommerce-product-gallery__image-main").removeClass("loading");
	});
	$("#woocommerce-product-gallery__image-main a").on("click", function (e) {
		e.preventDefault();
	});

	function updateImage(url) {
		$("#woocommerce-product-gallery__image-main").addClass("loading");
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image img",
		).attr("srcset", "");
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image a",
		).attr("href", url);
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image img",
		).attr("src", url);
	}

	function hideImage() {
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image",
		).addClass("hide");
	}

	function showImage() {
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image",
		).removeClass("hide");
	}

	function hideVideos() {
		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__video",
		).addClass("hide");

		$(
			"#woocommerce-product-gallery__image-main .woocommerce-product-gallery__video .ttg-media",
		).each(function (index, el) {
			$(el).trigger("pauseVideo");
		});
	}

	function showVideo(videoId) {
		const $videoWrapper = $("#" + videoId);
		const $video = $videoWrapper.find(".ttg-media");

		$videoWrapper.removeClass("hide");
		$video.trigger("loadVideo");
		$video.trigger("playVideo");
	}

	$(".woocommerce-product-gallery__thumbs").slick({
		slidesToShow: 6,
		arrows: false,
	});

	// $(".woocommerce-product-gallery__thumbs").on(
	// 	"beforeChange",
	// 	function (event, slick, currentSlide, nextSlide) {
	// 		const url = images[nextSlide];
	// 		updateImage(url);
	// 	},
	// );

	function loadVideos() {
		$("#woocommerce-product-gallery__image-main .ttg-media").each(
			function () {
				$(this).trigger("loadVideo");
				$(this).trigger("pauseVideo");
			},
		);
	}

	$(
		".woocommerce-product-gallery__thumbs .woocommerce-product-gallery__image",
	).on("click", function (e) {
		e.preventDefault();
		const dataIndex = $(this).attr("data-slick-index");
		const url = $(this).attr("data-image-url");
		const type = $(this).attr("data-type");
		if (type === "image") {
			showImage();
			updateImage(url);
			hideVideos();
		} else {
			const target = $(this).attr("data-target");
			hideImage();
			hideVideos();
			showVideo(target);
		}

		if (galleryCount >= 7) {
			$(".woocommerce-product-gallery__thumbs").slick(
				"slickGoTo",
				dataIndex,
			);
		}
	});

	$(".product-gallery__items").isotope({
		// options
		itemSelector: ".product-gallery__item",
	});
})(jQuery);
