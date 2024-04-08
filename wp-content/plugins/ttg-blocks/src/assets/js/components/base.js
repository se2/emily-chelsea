export function isInViewport(element) {
	const rect = element.getBoundingClientRect();
	return (
		rect.top >= 0 &&
		rect.left >= 0 &&
		rect.bottom <=
			(window.innerHeight || document.documentElement.clientHeight) &&
		rect.right <=
			(window.innerWidth || document.documentElement.clientWidth)
	);
}

export function addScript(id, src, callback = () => {}) {
	const hasScript = document.getElementById(id);

	if (!!hasScript && callback) {
		callback();
		return;
	}

	const s = document.createElement("script");
	s.id = id;
	s.setAttribute("src", src);
	s.onload = callback;
	document.head.appendChild(s);
}

export function simpleParallax(intensity = 1, element) {
	jQuery(element).data("offset-top", jQuery(element).offset().top);
	var currentScroll = jQuery(window).scrollTop();
	var imgPos = 0;
	var scrollAnchorTop =
		"<span id='scroll-anchor-top' style='position:fixed;top:0;left:0'></span>";
	var scrollAnchorBottom =
		"<span id='scroll-anchor-bottom' style='position:fixed;bottom:0;left:0'></span>";
	var existAnchorTop = jQuery(document).find("#scroll-anchor-top").length;
	var existAnchorBottom = jQuery(document).find(
		"#scroll-anchor-bottom",
	).length;

	if (!existAnchorTop) {
		jQuery("body").prepend(scrollAnchorTop);
	}

	if (!existAnchorBottom) {
		jQuery("body").append(scrollAnchorBottom);
	}

	jQuery(window).scroll(function () {
		var offset =
			jQuery(element).parents(".prallax-image-container").offset() || 0;
		var h = jQuery(element).outerHeight();

		var offsetTop = offset.top || 0;
		var offsetBottom = offsetTop + h;
		var offsetAnchorBottom = jQuery("#scroll-anchor-bottom").offset();
		var offsetAnchorTop = jQuery("#scroll-anchor-top").offset();
		var next = jQuery(window).scrollTop();

		if (next < currentScroll) {
			if (offsetAnchorBottom.top < offsetBottom) {
				imgPos = (offsetAnchorBottom.top - offsetBottom) / intensity;
			} else if (
				offsetAnchorBottom.top > offsetBottom &&
				offsetAnchorTop.top > offsetTop
			) {
				imgPos = (offsetAnchorTop.top - offsetBottom + h) / intensity;
			}
		} else {
			if (offsetAnchorTop.top > offsetTop) {
				imgPos = (offsetAnchorTop.top - offsetTop) / intensity;
			} else if (
				offsetAnchorTop.top < offsetTop &&
				offsetAnchorBottom.top - h < offsetTop
			) {
				imgPos = (offsetAnchorBottom.top - offsetTop - h) / intensity;
			}
		}

		jQuery(element).attr(
			"style",
			`transform:translate3d(0, ${imgPos}px, 0)`,
		);
		currentScroll = next;
	});
	jQuery(window).on("resize", function () {
		jQuery(element).data("offset-top", jQuery(element).offset().top);
		var scrollTop = jQuery(window).scrollTop();
		var offsetTop = jQuery(element).data("offset-top") || 0;

		var imgPos = (scrollTop - offsetTop) / intensity + "px";
		jQuery(element).attr(
			"style",
			`transform:translate3d(0, ${imgPos}px, 0)`,
		);
	});
}
