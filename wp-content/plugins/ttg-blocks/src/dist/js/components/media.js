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
/*!***************************************!*\
  !*** ./assets/js/components/media.js ***!
  \***************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _base__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./base */ "./assets/js/components/base.js");
function _typeof(obj) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) { return typeof obj; } : function (obj) { return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }, _typeof(obj); }
var _excluded = ["autoPlay", "onStateChange"];
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return exports; }; var exports = {}, Op = Object.prototype, hasOwn = Op.hasOwnProperty, defineProperty = Object.defineProperty || function (obj, key, desc) { obj[key] = desc.value; }, $Symbol = "function" == typeof Symbol ? Symbol : {}, iteratorSymbol = $Symbol.iterator || "@@iterator", asyncIteratorSymbol = $Symbol.asyncIterator || "@@asyncIterator", toStringTagSymbol = $Symbol.toStringTag || "@@toStringTag"; function define(obj, key, value) { return Object.defineProperty(obj, key, { value: value, enumerable: !0, configurable: !0, writable: !0 }), obj[key]; } try { define({}, ""); } catch (err) { define = function define(obj, key, value) { return obj[key] = value; }; } function wrap(innerFn, outerFn, self, tryLocsList) { var protoGenerator = outerFn && outerFn.prototype instanceof Generator ? outerFn : Generator, generator = Object.create(protoGenerator.prototype), context = new Context(tryLocsList || []); return defineProperty(generator, "_invoke", { value: makeInvokeMethod(innerFn, self, context) }), generator; } function tryCatch(fn, obj, arg) { try { return { type: "normal", arg: fn.call(obj, arg) }; } catch (err) { return { type: "throw", arg: err }; } } exports.wrap = wrap; var ContinueSentinel = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var IteratorPrototype = {}; define(IteratorPrototype, iteratorSymbol, function () { return this; }); var getProto = Object.getPrototypeOf, NativeIteratorPrototype = getProto && getProto(getProto(values([]))); NativeIteratorPrototype && NativeIteratorPrototype !== Op && hasOwn.call(NativeIteratorPrototype, iteratorSymbol) && (IteratorPrototype = NativeIteratorPrototype); var Gp = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(IteratorPrototype); function defineIteratorMethods(prototype) { ["next", "throw", "return"].forEach(function (method) { define(prototype, method, function (arg) { return this._invoke(method, arg); }); }); } function AsyncIterator(generator, PromiseImpl) { function invoke(method, arg, resolve, reject) { var record = tryCatch(generator[method], generator, arg); if ("throw" !== record.type) { var result = record.arg, value = result.value; return value && "object" == _typeof(value) && hasOwn.call(value, "__await") ? PromiseImpl.resolve(value.__await).then(function (value) { invoke("next", value, resolve, reject); }, function (err) { invoke("throw", err, resolve, reject); }) : PromiseImpl.resolve(value).then(function (unwrapped) { result.value = unwrapped, resolve(result); }, function (error) { return invoke("throw", error, resolve, reject); }); } reject(record.arg); } var previousPromise; defineProperty(this, "_invoke", { value: function value(method, arg) { function callInvokeWithMethodAndArg() { return new PromiseImpl(function (resolve, reject) { invoke(method, arg, resolve, reject); }); } return previousPromise = previousPromise ? previousPromise.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(innerFn, self, context) { var state = "suspendedStart"; return function (method, arg) { if ("executing" === state) throw new Error("Generator is already running"); if ("completed" === state) { if ("throw" === method) throw arg; return doneResult(); } for (context.method = method, context.arg = arg;;) { var delegate = context.delegate; if (delegate) { var delegateResult = maybeInvokeDelegate(delegate, context); if (delegateResult) { if (delegateResult === ContinueSentinel) continue; return delegateResult; } } if ("next" === context.method) context.sent = context._sent = context.arg;else if ("throw" === context.method) { if ("suspendedStart" === state) throw state = "completed", context.arg; context.dispatchException(context.arg); } else "return" === context.method && context.abrupt("return", context.arg); state = "executing"; var record = tryCatch(innerFn, self, context); if ("normal" === record.type) { if (state = context.done ? "completed" : "suspendedYield", record.arg === ContinueSentinel) continue; return { value: record.arg, done: context.done }; } "throw" === record.type && (state = "completed", context.method = "throw", context.arg = record.arg); } }; } function maybeInvokeDelegate(delegate, context) { var method = delegate.iterator[context.method]; if (undefined === method) { if (context.delegate = null, "throw" === context.method) { if (delegate.iterator["return"] && (context.method = "return", context.arg = undefined, maybeInvokeDelegate(delegate, context), "throw" === context.method)) return ContinueSentinel; context.method = "throw", context.arg = new TypeError("The iterator does not provide a 'throw' method"); } return ContinueSentinel; } var record = tryCatch(method, delegate.iterator, context.arg); if ("throw" === record.type) return context.method = "throw", context.arg = record.arg, context.delegate = null, ContinueSentinel; var info = record.arg; return info ? info.done ? (context[delegate.resultName] = info.value, context.next = delegate.nextLoc, "return" !== context.method && (context.method = "next", context.arg = undefined), context.delegate = null, ContinueSentinel) : info : (context.method = "throw", context.arg = new TypeError("iterator result is not an object"), context.delegate = null, ContinueSentinel); } function pushTryEntry(locs) { var entry = { tryLoc: locs[0] }; 1 in locs && (entry.catchLoc = locs[1]), 2 in locs && (entry.finallyLoc = locs[2], entry.afterLoc = locs[3]), this.tryEntries.push(entry); } function resetTryEntry(entry) { var record = entry.completion || {}; record.type = "normal", delete record.arg, entry.completion = record; } function Context(tryLocsList) { this.tryEntries = [{ tryLoc: "root" }], tryLocsList.forEach(pushTryEntry, this), this.reset(!0); } function values(iterable) { if (iterable) { var iteratorMethod = iterable[iteratorSymbol]; if (iteratorMethod) return iteratorMethod.call(iterable); if ("function" == typeof iterable.next) return iterable; if (!isNaN(iterable.length)) { var i = -1, next = function next() { for (; ++i < iterable.length;) { if (hasOwn.call(iterable, i)) return next.value = iterable[i], next.done = !1, next; } return next.value = undefined, next.done = !0, next; }; return next.next = next; } } return { next: doneResult }; } function doneResult() { return { value: undefined, done: !0 }; } return GeneratorFunction.prototype = GeneratorFunctionPrototype, defineProperty(Gp, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), defineProperty(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, toStringTagSymbol, "GeneratorFunction"), exports.isGeneratorFunction = function (genFun) { var ctor = "function" == typeof genFun && genFun.constructor; return !!ctor && (ctor === GeneratorFunction || "GeneratorFunction" === (ctor.displayName || ctor.name)); }, exports.mark = function (genFun) { return Object.setPrototypeOf ? Object.setPrototypeOf(genFun, GeneratorFunctionPrototype) : (genFun.__proto__ = GeneratorFunctionPrototype, define(genFun, toStringTagSymbol, "GeneratorFunction")), genFun.prototype = Object.create(Gp), genFun; }, exports.awrap = function (arg) { return { __await: arg }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, asyncIteratorSymbol, function () { return this; }), exports.AsyncIterator = AsyncIterator, exports.async = function (innerFn, outerFn, self, tryLocsList, PromiseImpl) { void 0 === PromiseImpl && (PromiseImpl = Promise); var iter = new AsyncIterator(wrap(innerFn, outerFn, self, tryLocsList), PromiseImpl); return exports.isGeneratorFunction(outerFn) ? iter : iter.next().then(function (result) { return result.done ? result.value : iter.next(); }); }, defineIteratorMethods(Gp), define(Gp, toStringTagSymbol, "Generator"), define(Gp, iteratorSymbol, function () { return this; }), define(Gp, "toString", function () { return "[object Generator]"; }), exports.keys = function (val) { var object = Object(val), keys = []; for (var key in object) { keys.push(key); } return keys.reverse(), function next() { for (; keys.length;) { var key = keys.pop(); if (key in object) return next.value = key, next.done = !1, next; } return next.done = !0, next; }; }, exports.values = values, Context.prototype = { constructor: Context, reset: function reset(skipTempReset) { if (this.prev = 0, this.next = 0, this.sent = this._sent = undefined, this.done = !1, this.delegate = null, this.method = "next", this.arg = undefined, this.tryEntries.forEach(resetTryEntry), !skipTempReset) for (var name in this) { "t" === name.charAt(0) && hasOwn.call(this, name) && !isNaN(+name.slice(1)) && (this[name] = undefined); } }, stop: function stop() { this.done = !0; var rootRecord = this.tryEntries[0].completion; if ("throw" === rootRecord.type) throw rootRecord.arg; return this.rval; }, dispatchException: function dispatchException(exception) { if (this.done) throw exception; var context = this; function handle(loc, caught) { return record.type = "throw", record.arg = exception, context.next = loc, caught && (context.method = "next", context.arg = undefined), !!caught; } for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i], record = entry.completion; if ("root" === entry.tryLoc) return handle("end"); if (entry.tryLoc <= this.prev) { var hasCatch = hasOwn.call(entry, "catchLoc"), hasFinally = hasOwn.call(entry, "finallyLoc"); if (hasCatch && hasFinally) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } else if (hasCatch) { if (this.prev < entry.catchLoc) return handle(entry.catchLoc, !0); } else { if (!hasFinally) throw new Error("try statement without catch or finally"); if (this.prev < entry.finallyLoc) return handle(entry.finallyLoc); } } } }, abrupt: function abrupt(type, arg) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc <= this.prev && hasOwn.call(entry, "finallyLoc") && this.prev < entry.finallyLoc) { var finallyEntry = entry; break; } } finallyEntry && ("break" === type || "continue" === type) && finallyEntry.tryLoc <= arg && arg <= finallyEntry.finallyLoc && (finallyEntry = null); var record = finallyEntry ? finallyEntry.completion : {}; return record.type = type, record.arg = arg, finallyEntry ? (this.method = "next", this.next = finallyEntry.finallyLoc, ContinueSentinel) : this.complete(record); }, complete: function complete(record, afterLoc) { if ("throw" === record.type) throw record.arg; return "break" === record.type || "continue" === record.type ? this.next = record.arg : "return" === record.type ? (this.rval = this.arg = record.arg, this.method = "return", this.next = "end") : "normal" === record.type && afterLoc && (this.next = afterLoc), ContinueSentinel; }, finish: function finish(finallyLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.finallyLoc === finallyLoc) return this.complete(entry.completion, entry.afterLoc), resetTryEntry(entry), ContinueSentinel; } }, "catch": function _catch(tryLoc) { for (var i = this.tryEntries.length - 1; i >= 0; --i) { var entry = this.tryEntries[i]; if (entry.tryLoc === tryLoc) { var record = entry.completion; if ("throw" === record.type) { var thrown = record.arg; resetTryEntry(entry); } return thrown; } } throw new Error("illegal catch attempt"); }, delegateYield: function delegateYield(iterable, resultName, nextLoc) { return this.delegate = { iterator: values(iterable), resultName: resultName, nextLoc: nextLoc }, "next" === this.method && (this.arg = undefined), ContinueSentinel; } }, exports; }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }
function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _objectWithoutProperties(source, excluded) { if (source == null) return {}; var target = _objectWithoutPropertiesLoose(source, excluded); var key, i; if (Object.getOwnPropertySymbols) { var sourceSymbolKeys = Object.getOwnPropertySymbols(source); for (i = 0; i < sourceSymbolKeys.length; i++) { key = sourceSymbolKeys[i]; if (excluded.indexOf(key) >= 0) continue; if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue; target[key] = source[key]; } } return target; }
function _objectWithoutPropertiesLoose(source, excluded) { if (source == null) return {}; var target = {}; var sourceKeys = Object.keys(source); var key, i; for (i = 0; i < sourceKeys.length; i++) { key = sourceKeys[i]; if (excluded.indexOf(key) >= 0) continue; target[key] = source[key]; } return target; }

(function ($) {
  var STATE = {
    ENDED: 0,
    PLAYING: 1,
    UNSTARTED: -1,
    PAUSE: 2
  };
  function vimeo(elementId, vimeoId) {
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    var _options$autoPlay = options.autoPlay,
      autoPlay = _options$autoPlay === void 0 ? false : _options$autoPlay,
      onStateChange = options.onStateChange,
      otherOptions = _objectWithoutProperties(options, _excluded);
    var autoPlayOptions = autoPlay ? {
      autoplay: true,
      muted: true,
      loop: true,
      background: 1
    } : {};
    var promise = new Promise(function (resolve, reject) {
      if (typeof Vimeo === "undefined") {
        reject(null);
      }
      var player = new Vimeo.Player(elementId, _objectSpread(_objectSpread(_objectSpread({}, otherOptions), autoPlayOptions), {}, {
        id: vimeoId
      }));
      if (onStateChange) {
        player.on("ended", function () {
          return onStateChange(STATE.ENDED);
        });
        player.on("playing", function () {
          return onStateChange(STATE.PLAYING);
        });
        player.on("pause", function () {
          return onStateChange(STATE.PAUSE);
        });
      }
      player.on("loaded", function () {
        resolve({
          play: function play() {
            return player.play();
          },
          pause: function pause() {
            return player.pause();
          },
          paused: function paused() {
            return player.getPaused();
          }
        });
      });
    });
    return promise;
  }
  function youtube(elmentID, youtubeID) {
    var options = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : {};
    var _options$autoPlay2 = options.autoPlay,
      autoPlay = _options$autoPlay2 === void 0 ? false : _options$autoPlay2,
      _onStateChange = options.onStateChange;
    var autoPlayOptions = autoPlay ? {
      autoplay: 1,
      mute: 1,
      loop: 1
    } : {};
    var promise = new Promise(function (resolve, reject) {
      window.YT.ready(function () {
        try {
          var layer = new YT.Player(elmentID, {
            videoId: youtubeID,
            playerVars: _objectSpread({
              controls: 0,
              // Show pause/play buttons in player
              showinfo: 1,
              // Hide the video title
              modestbranding: 1,
              // Hide the Youtube Logo
              fs: 0,
              // Hide the full screen button
              cc_load_policy: 0,
              // Hide closed captions
              iv_load_policy: 3,
              // Hide the Video Annotations
              autohide: 1
            }, autoPlayOptions),
            events: {
              onReady: function onReady(event) {
                resolve({
                  play: function play() {
                    return event.target.playVideo();
                  },
                  pause: function pause() {
                    return event.target.pauseVideo();
                  },
                  paused: function paused() {
                    var state = event.target.getPlayerState();
                    return state === 2 || state === 0 || state === 5;
                  }
                });
              },
              onStateChange: function onStateChange(_ref) {
                var data = _ref.data;
                switch (data) {
                  case STATE.ENDED:
                    {
                      _onStateChange(STATE.ENDED);
                      break;
                    }
                  case STATE.PLAYING:
                    {
                      _onStateChange(STATE.PLAYING);
                      break;
                    }
                  case STATE.PAUSE:
                    {
                      _onStateChange(STATE.PAUSE);
                      break;
                    }
                }
              }
            }
          });
        } catch (error) {
          reject(null);
        }
      });
    });
    return promise;
  }
  function video(id) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var onStateChange = options.onStateChange,
      autoPlay = options.autoPlay;
    var promise = new Promise(function (resolve, reject) {
      var myVideo = document.getElementById(id);
      if (!myVideo) {
        reject(null);
      }
      var source = myVideo.querySelector("source");
      var src = source.getAttribute("data-src");
      source.setAttribute("src", src);
      myVideo.load();
      if (autoPlay) {
        myVideo.video = true;
        myVideo.muted = true;
        myVideo.loop = true;
        myVideo.load();
        myVideo.play();
      }
      if (onStateChange) {
        myVideo.addEventListener("ended", function () {
          onStateChange(STATE.ENDED);
        });
        myVideo.addEventListener("playing", function () {
          onStateChange(STATE.PLAYING);
        });
      }
      resolve({
        play: function play() {
          return myVideo.play();
        },
        pause: function pause() {
          return myVideo.pause();
        },
        paused: function paused() {
          return myVideo.paused;
        }
      });
    });
    return promise;
  }
  function loadVideos() {
    $(".ttg-media").each(function (index, el) {
      var $that = $(this);
      var $media = $(el).find(".ttg-media__video");
      var $center = $that.find(".ttg-media__center");
      var $playButton = $(el).find(".ttg-media__play");
      var type = $media.attr("data-type");
      var autoPlay = $media.attr("data-autoplay");
      var elmentID = $media.attr("id");
      var videoID = $media.attr("data-id");
      var onStateChange = function onStateChange(s) {
        if (!autoPlay) {
          if (s === STATE.ENDED) $(el).removeClass("is-playing");
          if (s === STATE.PAUSE) $(el).removeClass("is-playing");
        }
      };
      function init(_x) {
        return _init.apply(this, arguments);
      }
      function _init() {
        _init = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee2(player) {
          var play, _play;
          return _regeneratorRuntime().wrap(function _callee2$(_context2) {
            while (1) {
              switch (_context2.prev = _context2.next) {
                case 0:
                  _play = function _play3() {
                    _play = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee() {
                      var isPaused;
                      return _regeneratorRuntime().wrap(function _callee$(_context) {
                        while (1) {
                          switch (_context.prev = _context.next) {
                            case 0:
                              isPaused = false;
                              if (!(typeof player.paused().then !== "undefined")) {
                                _context.next = 7;
                                break;
                              }
                              _context.next = 4;
                              return player.paused();
                            case 4:
                              isPaused = _context.sent;
                              _context.next = 8;
                              break;
                            case 7:
                              isPaused = player.paused();
                            case 8:
                              if (player) {
                                if (isPaused) {
                                  player.play();
                                  $(el).addClass("is-playing");
                                } else {
                                  player.pause();
                                  $(el).removeClass("is-playing");
                                }
                              }
                            case 9:
                            case "end":
                              return _context.stop();
                          }
                        }
                      }, _callee);
                    }));
                    return _play.apply(this, arguments);
                  };
                  play = function _play2() {
                    return _play.apply(this, arguments);
                  };
                  $that.removeClass("loading");
                  $that.addClass("loaded");
                  $playButton.removeClass("disabled");
                  $playButton.on("click", function () {
                    play();
                  });
                  $that.on("playVideo", function () {
                    player.play();
                    $(el).addClass("is-playing");
                  });
                  $that.on("pauseVideo", function () {
                    player.pause();
                    $(el).removeClass("is-playing");
                  });
                case 8:
                case "end":
                  return _context2.stop();
              }
            }
          }, _callee2);
        }));
        return _init.apply(this, arguments);
      }
      function loadVideo() {
        if (!$that.hasClass("loaded")) {
          if (autoPlay) {
            $(el).addClass("is-playing");
          }
          $that.addClass("loading");
          switch (type) {
            case "youtube":
              {
                (0,_base__WEBPACK_IMPORTED_MODULE_0__.addScript)("iframe-api-js", "https://www.youtube.com/iframe_api", function () {
                  youtube(elmentID, videoID, {
                    autoPlay: autoPlay,
                    onStateChange: onStateChange
                  }).then(init);
                });
                break;
              }
            case "vimeo":
              {
                (0,_base__WEBPACK_IMPORTED_MODULE_0__.addScript)("vimeo-api-js", "https://player.vimeo.com/api/player.js", function () {
                  vimeo(elmentID, videoID, {
                    autoPlay: autoPlay,
                    onStateChange: onStateChange
                  }).then(init);
                });
                break;
              }
            default:
              {
                var _elmentID = $media.find("video").attr("id");
                video(_elmentID).then(init);
              }
          }
        }
      }
      function loadVideoOnDemand() {
        var isTrigger = (0,_base__WEBPACK_IMPORTED_MODULE_0__.isInViewport)($center[0]);
        if (isTrigger) {
          loadVideo();
        }
      }
      $that.on("loadVideo", function () {
        loadVideo();
      });
      loadVideoOnDemand();
      jQuery(window).on("scroll", function () {
        loadVideoOnDemand();
      });
    });
  }
  $(window).on("load", function () {
    loadVideos();
  });
})(jQuery);
})();

/******/ })()
;
//# sourceMappingURL=media.js.map