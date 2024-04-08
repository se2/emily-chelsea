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

/***/ }),

/***/ "./assets/scss/components/separator.scss":
/*!***********************************************!*\
  !*** ./assets/scss/components/separator.scss ***!
  \***********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/styling-box.scss":
/*!*************************************************!*\
  !*** ./assets/scss/components/styling-box.scss ***!
  \*************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-background.scss":
/*!****************************************************!*\
  !*** ./assets/scss/components/ttg-background.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-column-editor.scss":
/*!*******************************************************!*\
  !*** ./assets/scss/components/ttg-column-editor.scss ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-column.scss":
/*!************************************************!*\
  !*** ./assets/scss/components/ttg-column.scss ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-row-editor.scss":
/*!****************************************************!*\
  !*** ./assets/scss/components/ttg-row-editor.scss ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-row.scss":
/*!*********************************************!*\
  !*** ./assets/scss/components/ttg-row.scss ***!
  \*********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/ttg-show-more-text.scss":
/*!********************************************************!*\
  !*** ./assets/scss/components/ttg-show-more-text.scss ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/base.scss":
/*!*******************************!*\
  !*** ./assets/scss/base.scss ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/block-editor.scss":
/*!**************************************************!*\
  !*** ./assets/scss/components/block-editor.scss ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/button.scss":
/*!********************************************!*\
  !*** ./assets/scss/components/button.scss ***!
  \********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/image.scss":
/*!*******************************************!*\
  !*** ./assets/scss/components/image.scss ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./assets/scss/components/media.scss":
/*!*******************************************!*\
  !*** ./assets/scss/components/media.scss ***!
  \*******************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


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
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
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
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/dist/js/components/base": 0,
/******/ 			"dist/css/components/media": 0,
/******/ 			"dist/css/components/image": 0,
/******/ 			"dist/css/components/button": 0,
/******/ 			"dist/css/components/block-editor": 0,
/******/ 			"dist/css/base.min": 0,
/******/ 			"dist/css/components/ttg-show-more-text": 0,
/******/ 			"dist/css/components/ttg-row": 0,
/******/ 			"dist/css/components/ttg-row-editor": 0,
/******/ 			"dist/css/components/ttg-column": 0,
/******/ 			"dist/css/components/ttg-column-editor": 0,
/******/ 			"dist/css/components/ttg-background": 0,
/******/ 			"dist/css/components/styling-box": 0,
/******/ 			"dist/css/components/separator": 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunksrc"] = self["webpackChunksrc"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/js/components/base.js")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/base.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/block-editor.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/button.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/image.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/media.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/separator.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/styling-box.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-background.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-column-editor.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-column.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-row-editor.scss")))
/******/ 	__webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-row.scss")))
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, ["dist/css/components/media","dist/css/components/image","dist/css/components/button","dist/css/components/block-editor","dist/css/base.min","dist/css/components/ttg-show-more-text","dist/css/components/ttg-row","dist/css/components/ttg-row-editor","dist/css/components/ttg-column","dist/css/components/ttg-column-editor","dist/css/components/ttg-background","dist/css/components/styling-box","dist/css/components/separator"], () => (__webpack_require__("./assets/scss/components/ttg-show-more-text.scss")))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ })()
;
//# sourceMappingURL=base.js.map