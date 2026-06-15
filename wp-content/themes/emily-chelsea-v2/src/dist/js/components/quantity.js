/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************************!*\
  !*** ./assets/js/components/quantity.js ***!
  \******************************************/
(function ($) {
  $(document).on("click", ".quantity__plus", function (e) {
    e.preventDefault();
    var parent = $(this).parents(".quantity");
    var $input = $(parent).find(".input-text");
    var currentValue = parseInt($input.val(), 10);
    var readOnly = $input.props("readonly");
    if (readOnly) return;
    $input.val(currentValue + 1);
    $input.trigger("change");
  });
  $(document).on("click", ".quantity__minus", function (e) {
    e.preventDefault();
    var parent = $(this).parents(".quantity");
    var $input = $(parent).find(".input-text");
    var min = $input.attr("min");
    var currentValue = parseInt($input.val(), 10);
    var readOnly = $input.props("readonly");
    if (readOnly) return;
    $input.val(currentValue > min ? currentValue - 1 : min);
    $input.trigger("change");
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=quantity.js.map