/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************************!*\
  !*** ./assets/js/components/posts-slide.js ***!
  \*********************************************/
(function ($) {
  var defaultConfig = {
    slidesToShow: 2,
    autoplay: true,
    responsive: [{
      breakpoint: 480,
      settings: {
        slidesToShow: 1
      }
    }]
  };
  $(".ttg-posts-slide").each(function (index, el) {
    var config = $(el).attr("data-config") ? JSON.parse($(el).attr("data-config")) : defaultConfig;
    $(el).slick(config);
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=posts-slide.js.map