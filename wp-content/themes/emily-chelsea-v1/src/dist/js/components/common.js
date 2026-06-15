/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./assets/js/components/common.js ***!
  \****************************************/
(function ($) {
  $(document).on("facetwp-loaded", function () {
    var facets_in_use = "" != FWP.buildQueryString();

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
    $('html, body').animate({
      scrollTop: $('.facetwp-template').offset().top - 120 // Scroll to the top of the element with class "facetp-template"
    }, 500);
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
    var id = $(this).attr("href");
    if (id === "#" || !id) return true;
    var $target = $(document).find(id);
    var headerH = $("#main-header").outerHeight();
    if ($target.length) {
      $([document.documentElement, document.body]).animate({
        scrollTop: $target.offset().top - headerH
      }, 500);
    }
  });
  $(document.body).on("updated_cart_totals", function () {
    $.ajax({
      url: jsData.ajaxUrl,
      data: {
        action: "get_cart_counter"
      },
      dataType: "json",
      success: function success(res) {
        $(".header-cart__count").html(res.counter);
      }
    });
  });
  $(document).on("click", function (e) {
    var $currentTarget = $(e.target);
    var isFilter = $currentTarget.hasClass("products-filter__item") || $currentTarget.parents(".products-filter__item");

    // If element is opened and click target is outside it, hide it
    if (isFilter) {
      var filter = "";
      if ($currentTarget.hasClass("products-filter__item")) {
        filter = $currentTarget;
      } else {
        filter = $currentTarget.parents(".products-filter__item");
      }
      $(".products-filter__item").not(filter).find(".products-filter__item__toggle").each(function () {
        $(this).prop("checked", false);
      });
    } else {
      $(".products-filter__item").find(".products-filter__item__toggle").each(function () {
        $(this).prop("checked", false);
      });
    }
  });
  function customSelect(select) {
    var id = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : "";
    var options = $(select).find("option");
    var selectedOption = $(select).find("option:selected");
    var listItems = "";
    $.each(options, function (index, el) {
      var text = $(el).html();
      var value = $(el).val();
      var selectedValue = selectedOption.val();
      var isActive = selectedValue === value;
      var extraText = $(el).attr("data-extra-text") || "";
      listItems += "<li class=\"".concat(isActive ? "active" : "", "\" data-value=\"").concat(value, "\">").concat(text).concat(extraText, "</li>");
    });
    listItems = $("\n\t\t<div class=\"custom-select\" id=\"".concat(id, "\">\n\t\t<div class=\"custom-select__inner\">\n\t\t<ul>").concat(listItems, "</ul>\n\t\t</div>\n\t\t</div>"));
    $(listItems).insertAfter(select);
    var wrapper = $(select).next(".custom-select");
    var wrapperInner = $(wrapper).find(".custom-select__inner");
    var ul = wrapper.find("ul");
    $(select).on("change", function () {
      var value = $(this).val();
      ul.find("li").removeClass("active");
      ul.find("li[data-value=\"".concat(value, "\"]")).addClass("active");
    });
    ul.find("li").on("click", function () {
      var value = $(this).attr("data-value");
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
    var target = $(e.target);
    if ($(target).hasClass("custom-select") || $(target).parents(".custom-select").length) {
      if ($(target).hasClass("custom-select")) {
        $(document).find(".custom-select").not(target).removeClass("active");
        return;
      }
      if ($(target).parents(".custom-select").length) {
        var dropdown = $(target).parents(".custom-select");
        $(document).find(".custom-select").not(dropdown).removeClass("active");
        console.log("parents");
      }
    } else {
      console.log("target outside");
      $(document).find(".custom-select").removeClass("active");
    }
  });
  $(".toggle-modal").on("click", function (e) {
    e.preventDefault();
    var target = $(this).attr("data-target");
    if (target) {
      var el = $(document).find(target);
      $(el).toggleClass("open");
    }
  });
  $(".modal__close").on("click", function () {
    var parent = $(this).parents(".modal");
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
    var footerHeight = $("#main-footer").outerHeight();
    $("#main-navigation-line").css("bottom", footerHeight);
    console.log("footerHeight", footerHeight);
  }
  try {
    var _calculateScrollbarWidth = function _calculateScrollbarWidth() {
      var scrollBarWidth = window.innerWidth - document.documentElement.clientWidth + "px";
      document.documentElement.style.setProperty("--scrollbar-width", scrollBarWidth);
      window.localStorage.setItem("scrollBarWidth", scrollBarWidth);
    };
    var scrollBarWidth = window.localStorage.getItem("scrollBarWidth");
    if (scrollBarWidth) {
      document.documentElement.style.setProperty("--scrollbar-width", window.innerWidth - document.documentElement.clientWidth + "px");
    }
    _calculateScrollbarWidth();
    calcMainLineBottom();
    var resizeInterval = 0;
    $(window).on("resize", function () {
      clearInterval(resizeInterval);
      resizeInterval = setTimeout(function () {
        calcMainLineBottom();
      }, 500);
    });
  } catch (error) {
    console.log("error", error);
  }
})(jQuery);
/******/ })()
;
//# sourceMappingURL=common.js.map