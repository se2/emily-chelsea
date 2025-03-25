/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************!*\
  !*** ./assets/js/components/header.js ***!
  \****************************************/
(function ($) {
  var lastKnownScrollPosition = 0;
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
    var child = $(el).find(">ul");
    var $expand = $("<span class=\"expand\"></span>");
    if (child.length) {
      var $a = $(el).find(">a");
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
      var deltaY = window.scrollY - lastKnownScrollPosition;
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
    var checked = $(this).prop("checked");
    console.log("checked", checked);
    if (checked) {
      $(".header-search__form input").focus();
    }
  });
  (function ($) {
    $(document).on('facetwp-loaded', function () {
      if (FWP.loaded) {
        // Run only after the initial page load
        $('html, body').animate({
          scrollTop: $('.woocommerce-products-header').offset().top // Scroll to the top of the element with class "facetp-template"
        }, 500);
      }
    });
  })(jQuery);
})(jQuery);
/******/ })()
;
//# sourceMappingURL=header.js.map