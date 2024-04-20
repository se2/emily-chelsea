(function ($) {
	$(document).on("facetwp-loaded", function () {
		const facets_in_use = "" != FWP.buildQueryString();

		// see https://api.jquery.com/toggle/
		// TRUE to show, FALSE to hide
		console.log("facets_in_use", facets_in_use);
		$(".facetwp-reset").toggle(facets_in_use);

		if (facets_in_use) {
			$(".product-filter-user-selection").addClass("active");
		} else {
			$(".product-filter-user-selection").removeClass("active");
		}
	});

	$(document).on("facetwp-loaded", function () {
		console.log("FWP.settings", FWP);
		if (!FWP.settings) return;

		$.each(FWP.settings.num_choices, function (key, val) {
			// assuming each facet is wrapped within a "facet-wrap" container element
			// this may need to change depending on your setup, for example:
			// change ".facet-wrap" to ".widget" if using WP text widgets
			var $facet = $(".facetwp-facet-" + key);
			var $wrap = $facet.closest(".products-filter__item");

			console.log("val", key, val);
			if (key === "available_in_fairmined_gold") {
				console.log("val", val);
				if (!val) {
					$wrap.find(".products-filter__item__label").show();
				} else {
					$wrap.find(".products-filter__item__label").hide();
				}
			}
		});
	});

	$("a[href^=#]").on("click", function (e) {
		e.preventDefault();
		const id = $(this).attr("href");
		if (id === "#" || !id) return true;

		const $target = $(document).find(id);
		const headerH = $("#main-header").outerHeight();
		if ($target.length) {
			$([document.documentElement, document.body]).animate(
				{
					scrollTop: $target.offset().top - headerH,
				},
				500,
			);
		}
	});

	$(document.body).on("updated_cart_totals", function () {
		$.ajax({
			url: jsData.ajaxUrl,
			data: {
				action: "get_cart_counter",
			},
			dataType: "json",
			success: function (res) {
				$(".header-cart__count").html(res.counter);
			},
		});
	});

	$(document).on("click", function (e) {
		const $currentTarget = $(e.target);
		const isFilter =
			$currentTarget.hasClass("products-filter__item") ||
			$currentTarget.parents(".products-filter__item");

		// If element is opened and click target is outside it, hide it
		if (isFilter) {
			let filter = "";
			if ($currentTarget.hasClass("products-filter__item")) {
				filter = $currentTarget;
			} else {
				filter = $currentTarget.parents(".products-filter__item");
			}
			$(".products-filter__item")
				.not(filter)
				.find(".products-filter__item__toggle")
				.each(function () {
					$(this).prop("checked", false);
				});
		} else {
			$(".products-filter__item")
				.find(".products-filter__item__toggle")
				.each(function () {
					$(this).prop("checked", false);
				});
		}
	});

	function customSelect(select, id = "") {
		const options = $(select).find("option");
		const selectedOption = $(select).find("option:selected");
		let listItems = "";

		$.each(options, function (index, el) {
			const text = $(el).html();
			const value = $(el).val();
			const selectedValue = selectedOption.val();
			const isActive = selectedValue === value;
			const extraText = $(el).attr("data-extra-text") || "";
			listItems += `<li class="${
				isActive ? "active" : ""
			}" data-value="${value}">${text}${extraText}</li>`;
		});
		listItems = $(`
		<div class="custom-select" id="${id}">
		<div class="custom-select__inner">
		<ul>${listItems}</ul>
		</div>
		</div>`);
		$(listItems).insertAfter(select);

		const wrapper = $(select).next(".custom-select");
		const wrapperInner = $(wrapper).find(".custom-select__inner");
		const ul = wrapper.find("ul");
		$(select).on("change", function () {
			const value = $(this).val();
			ul.find("li").removeClass("active");
			ul.find(`li[data-value="${value}"]`).addClass("active");
		});
		ul.find("li").on("click", function () {
			const value = $(this).attr("data-value");
			ul.find("li").removeClass("active");
			$(this).addClass("active");
			$(select).val(value).trigger("change");
		});

		wrapper.on("click", function () {
			wrapper.toggleClass("active");
		});
	}

	window.customSelect = customSelect;

	$(document).on("click", function (e) {
		const target = $(e.target);

		if (
			$(target).hasClass("custom-select") ||
			$(target).parents(".custom-select").length
		) {
			if ($(target).hasClass("custom-select")) {
				$(document)
					.find(".custom-select")
					.not(target)
					.removeClass("active");
				return;
			}
			if ($(target).parents(".custom-select").length) {
				const dropdown = $(target).parents(".custom-select");
				$(document)
					.find(".custom-select")
					.not(dropdown)
					.removeClass("active");
				console.log("parents");
			}
		} else {
			console.log("target outside");
			$(document).find(".custom-select").removeClass("active");
		}
	});

	$(".toggle-modal").on("click", function (e) {
		e.preventDefault();
		const target = $(this).attr("data-target");
		if (target) {
			const el = $(document).find(target);
			$(el).toggleClass("open");
		}
	});
	$(".modal__close").on("click", function () {
		const parent = $(this).parents(".modal");
		$(parent).removeClass("open");
	});

	// jQuery(($) => {
	// 	$.fn.wc_variations_image_update = () => {
	// 		//Do nothing
	// 	};
	// });
})(jQuery);

(function ($) {
	function calcMainLineBottom() {
		const footerHeight = $("#main-footer").outerHeight();
		$("#main-navigation-line").css("bottom", footerHeight);
		console.log("footerHeight", footerHeight);
	}

	try {
		function _calculateScrollbarWidth() {
			const scrollBarWidth =
				window.innerWidth - document.documentElement.clientWidth + "px";
			document.documentElement.style.setProperty(
				"--scrollbar-width",
				scrollBarWidth,
			);
			window.localStorage.setItem("scrollBarWidth", scrollBarWidth);
		}

		const scrollBarWidth = window.localStorage.getItem("scrollBarWidth");
		if (scrollBarWidth) {
			document.documentElement.style.setProperty(
				"--scrollbar-width",
				window.innerWidth - document.documentElement.clientWidth + "px",
			);
		}

		_calculateScrollbarWidth();
		calcMainLineBottom();

		var resizeInterval = 0;

		$(window).on("resize", () => {
			clearInterval(resizeInterval);
			resizeInterval = setTimeout(() => {
				calcMainLineBottom();
			}, 500);
		});
	} catch (error) {
		console.log("error", error);
	}
})(jQuery);
