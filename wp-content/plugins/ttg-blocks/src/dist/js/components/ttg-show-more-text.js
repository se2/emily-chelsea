/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!****************************************************!*\
  !*** ./assets/js/components/ttg-show-more-text.js ***!
  \****************************************************/
(function ($) {
  $(document).on("click", ".ttg-show-more-text .ttg-show-more-text__title", function () {
    var $parent = $(this).parents(".ttg-show-more-text");
    if ($parent.hasClass("open")) {
      $parent.removeClass("open");
    } else {
      $parent.addClass("open");
    }
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=ttg-show-more-text.js.map