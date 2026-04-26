/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./assets/js/pages/cart.js ***!
  \*********************************/
(function ($) {
  var ajaxUrl = "/wp-admin/admin-ajax.php";
  function removeDesign(uuid) {
    var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
    jQuery.ajax({
      url: ajaxUrl + "?action=remove_design&uuid=" + uuid,
      success: function success(res) {
        if (res.isSuccess) {
          callback();
        }
      },
      dataType: "json"
    });
  }
  function removeTrayItem(id) {
    var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
    jQuery.ajax({
      url: ajaxUrl + "?action=remove_tray_item&id=" + id.toString(),
      success: function success(res) {
        callback(res);
      },
      dataType: "json"
    });
  }
  $(".remove-tray-item-from-cart").off("click");
  $(document).on("click", ".remove-tray-item-from-cart", function (e) {
    e.preventDefault();
    var id = $(this).data("uuid");
    removeTrayItem(id, function () {
      location.reload();
    });
  });
  $(".remove-design-from-cart").off("click");
  $(document).on("click", ".remove-design-from-cart", function (e) {
    e.preventDefault();
    var uuid = $(this).data("uuid");
    removeDesign(uuid, function () {
      location.reload();
    });
  });
})(jQuery);
/******/ })()
;
//# sourceMappingURL=cart.js.map