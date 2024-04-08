/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/js/components/base.js":
/*!**************************************!*\
  !*** ./assets/js/components/base.js ***!
  \**************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "addScript": () => (/* binding */ addScript),
/* harmony export */   "isInViewport": () => (/* binding */ isInViewport),
/* harmony export */   "simpleParallax": () => (/* binding */ simpleParallax)
/* harmony export */ });
function isInViewport(element) {
  var rect = element.getBoundingClientRect();
  return rect.top >= 0 && rect.left >= 0 && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && rect.right <= (window.innerWidth || document.documentElement.clientWidth);
}
function addScript(id, src) {
  var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : function () {};
  var hasScript = document.getElementById(id);
  if (!!hasScript && callback) {
    callback();
    return;
  }
  var s = document.createElement("script");
  s.id = id;
  s.setAttribute("src", src);
  s.onload = callback;
  document.head.appendChild(s);
}
function simpleParallax() {
  var intensity = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : 1;
  var element = arguments.length > 1 ? arguments[1] : undefined;
  jQuery(element).data("offset-top", jQuery(element).offset().top);
  var currentScroll = jQuery(window).scrollTop();
  var imgPos = 0;
  var scrollAnchorTop = "<span id='scroll-anchor-top' style='position:fixed;top:0;left:0'></span>";
  var scrollAnchorBottom = "<span id='scroll-anchor-bottom' style='position:fixed;bottom:0;left:0'></span>";
  var existAnchorTop = jQuery(document).find("#scroll-anchor-top").length;
  var existAnchorBottom = jQuery(document).find("#scroll-anchor-bottom").length;
  if (!existAnchorTop) {
    jQuery("body").prepend(scrollAnchorTop);
  }
  if (!existAnchorBottom) {
    jQuery("body").append(scrollAnchorBottom);
  }
  jQuery(window).scroll(function () {
    var offset = jQuery(element).parents(".prallax-image-container").offset() || 0;
    var h = jQuery(element).outerHeight();
    var offsetTop = offset.top || 0;
    var offsetBottom = offsetTop + h;
    var offsetAnchorBottom = jQuery("#scroll-anchor-bottom").offset();
    var offsetAnchorTop = jQuery("#scroll-anchor-top").offset();
    var next = jQuery(window).scrollTop();
    if (next < currentScroll) {
      if (offsetAnchorBottom.top < offsetBottom) {
        imgPos = (offsetAnchorBottom.top - offsetBottom) / intensity;
      } else if (offsetAnchorBottom.top > offsetBottom && offsetAnchorTop.top > offsetTop) {
        imgPos = (offsetAnchorTop.top - offsetBottom + h) / intensity;
      }
    } else {
      if (offsetAnchorTop.top > offsetTop) {
        imgPos = (offsetAnchorTop.top - offsetTop) / intensity;
      } else if (offsetAnchorTop.top < offsetTop && offsetAnchorBottom.top - h < offsetTop) {
        imgPos = (offsetAnchorBottom.top - offsetTop - h) / intensity;
      }
    }
    jQuery(element).attr("style", "transform:translate3d(0, ".concat(imgPos, "px, 0)"));
    currentScroll = next;
  });
  jQuery(window).on("resize", function () {
    jQuery(element).data("offset-top", jQuery(element).offset().top);
    var scrollTop = jQuery(window).scrollTop();
    var offsetTop = jQuery(element).data("offset-top") || 0;
    var imgPos = (scrollTop - offsetTop) / intensity + "px";
    jQuery(element).attr("style", "transform:translate3d(0, ".concat(imgPos, "px, 0)"));
  });
}

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!************************************!*\
  !*** ./assets/js/components/bg.js ***!
  \************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _base__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./base */ "./assets/js/components/base.js");

(function ($) {
  jQuery(".simple-parallax").each(function (index, el) {
    var config = JSON.parse($(el).attr("data-config"));
    var $that = $(el);
    console.log("config", config);
    (0,_base__WEBPACK_IMPORTED_MODULE_0__.simpleParallax)(Number(config === null || config === void 0 ? void 0 : config.intensity) || 5, $that);
  });
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=bg.js.map