(function ($) {
	let lastKnownScrollPosition = 0;

	function header() {
		var wrapperInner = $("#wrapper__inner").offset();
		var windowTop = $(window).scrollTop();
		var top = wrapperInner.top - 54;

		if (windowTop >= top) {
			$("#toggle-nav-btn").addClass("change-color");
			$("#main-navigation").addClass("change-color");
		} else {
			$("#toggle-nav-btn").removeClass("change-color");
			$("#main-navigation").removeClass("change-color");
		}
	}
	$(window).on("scroll", function () {
		header();
	});
	header();

	$(".main-navigation__middle > ul li").each(function (index, el) {
		const child = $(el).find(">ul");
		const $expand = $(`<span class="expand"></span>`);

		if (child.length) {
			const $a = $(el).find(">a");
			$a.append($expand);
			$a.on("click", function (e) {
				if (!$(this).hasClass("open-menu")) {
					e.stopPropagation();
					e.preventDefault();
				}
				$expand.toggleClass("active");
				$(child).toggleClass("open");
				$(this).toggleClass("open-menu");
			});

			$expand.on("click", function (e) {
				e.stopPropagation();
				e.preventDefault();
				$a.toggleClass("open-menu");
				$expand.toggleClass("active");
				$(child).toggleClass("open");
			});
		}
	});

	$(window).on("scroll", function () {
		window.requestAnimationFrame(function () {
			const deltaY = window.scrollY - lastKnownScrollPosition;
			lastKnownScrollPosition = window.scrollY;

			if (window.scrollY === 0) {
				$("body").addClass("stop-scroll");
				$("body").removeClass("scroll-down");
				$("body").removeClass("scroll-up");
			} else {
				if (deltaY > 0) {
					$("body").addClass("scroll-down");
					$("body").removeClass("scroll-up");
					$("body").removeClass("stop-scroll");
				} else if (deltaY < 0) {
					$("body").removeClass("scroll-down");
					$("body").removeClass("stop-scroll");
					$("body").addClass("scroll-up");
				}
			}
		});
	});

	$(".header-search__form__toggle").on("change", function () {
		const checked = $(this).prop("checked");
		console.log("checked", checked);
		if (checked) {
			$(".header-search__form input").focus();
		}
	});
})(jQuery);
