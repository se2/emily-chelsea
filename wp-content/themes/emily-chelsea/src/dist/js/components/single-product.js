/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************************************!*\
  !*** ./assets/js/components/single-product.js ***!
  \************************************************/
(function ($) {
  var galleryCount = $(".woocommerce-product-gallery__thumbs .woocommerce-product-gallery__image").length;
  $("#woocommerce-product-gallery__image-main img").load(function () {
    $("#woocommerce-product-gallery__image-main").removeClass("loading");
  });
  $("#woocommerce-product-gallery__image-main a").on("click", function (e) {
    e.preventDefault();
  });
  function updateImage(url) {
    $("#woocommerce-product-gallery__image-main").addClass("loading");
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image img").attr("srcset", "");
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image a").attr("href", url);
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image img").attr("src", url);
  }
  function hideImage() {
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image").addClass("hide");
  }
  function showImage() {
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__image").removeClass("hide");
  }
  function hideVideos() {
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__video").addClass("hide");
    $("#woocommerce-product-gallery__image-main .woocommerce-product-gallery__video .ttg-media").each(function (index, el) {
      $(el).trigger("pauseVideo");
    });
  }
  function showVideo(videoId) {
    var $videoWrapper = $("#" + videoId);
    var $video = $videoWrapper.find(".ttg-media");
    $videoWrapper.removeClass("hide");
    $video.trigger("loadVideo");
    $video.trigger("playVideo");
  }
  $(".woocommerce-product-gallery__thumbs").slick({
    slidesToShow: 6,
    arrows: false
  });

  // $(".woocommerce-product-gallery__thumbs").on(
  // 	"beforeChange",
  // 	function (event, slick, currentSlide, nextSlide) {
  // 		const url = images[nextSlide];
  // 		updateImage(url);
  // 	},
  // );

  function loadVideos() {
    $("#woocommerce-product-gallery__image-main .ttg-media").each(function () {
      $(this).trigger("loadVideo");
      $(this).trigger("pauseVideo");
    });
  }
  $(".woocommerce-product-gallery__thumbs .woocommerce-product-gallery__image").on("click", function (e) {
    e.preventDefault();
    var dataIndex = $(this).attr("data-slick-index");
    var url = $(this).attr("data-image-url");
    var type = $(this).attr("data-type");
    if (type === "image") {
      showImage();
      updateImage(url);
      hideVideos();
    } else {
      var target = $(this).attr("data-target");
      hideImage();
      hideVideos();
      showVideo(target);
    }
    if (galleryCount >= 7) {
      $(".woocommerce-product-gallery__thumbs").slick("slickGoTo", dataIndex);
    }
  });
  $(".product-gallery__items").isotope({
    // options
    itemSelector: ".product-gallery__item"
  });
  jQuery("#pa_metal-type").on("change", function (el) {
    var val = $(this).find("option:selected").attr("value");
    var productId = $(".variations_form").data("product_id");
    var selctedSize = $("#pa_size").find("option:selected").attr("value");
    if (productId == 12633) {
      jQuery.ajax({
        method: "POST",
        url: jsData.ajaxUrl,
        data: {
          action: "get_products_by_attr",
          parent_product_id: productId,
          meta_type: val
        },
        dataType: "json",
        success: function success(res) {
          var options = res.options;
          $("#custom-select-pa_size").remove();
          $("#pa_size").html(options);
          $("#pa_size").val(selctedSize);
          customSelect($("#pa_size"), "custom-select-pa_size");
        }
      });
    }
  });
  setTimeout(function () {
    $("[name^=attribute_pa]").each(function (index, value) {
      var id = "custom-select-".concat($(value).attr("id"));
      customSelect(value, id);
    });
    if ($("#pa_size").length > 0) {
      $("#pa_metal-type").trigger("change");
    }
  }, 500);
})(jQuery);
/******/ })()
;
//# sourceMappingURL=single-product.js.map