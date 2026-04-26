/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***************************************!*\
  !*** ./assets/js/pages/build-ring.js ***!
  \***************************************/
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _regeneratorRuntime() { "use strict"; /*! regenerator-runtime -- Copyright (c) 2014-present, Facebook, Inc. -- license (MIT): https://github.com/facebook/regenerator/blob/main/LICENSE */ _regeneratorRuntime = function _regeneratorRuntime() { return e; }; var t, e = {}, r = Object.prototype, n = r.hasOwnProperty, o = Object.defineProperty || function (t, e, r) { t[e] = r.value; }, i = "function" == typeof Symbol ? Symbol : {}, a = i.iterator || "@@iterator", c = i.asyncIterator || "@@asyncIterator", u = i.toStringTag || "@@toStringTag"; function define(t, e, r) { return Object.defineProperty(t, e, { value: r, enumerable: !0, configurable: !0, writable: !0 }), t[e]; } try { define({}, ""); } catch (t) { define = function define(t, e, r) { return t[e] = r; }; } function wrap(t, e, r, n) { var i = e && e.prototype instanceof Generator ? e : Generator, a = Object.create(i.prototype), c = new Context(n || []); return o(a, "_invoke", { value: makeInvokeMethod(t, r, c) }), a; } function tryCatch(t, e, r) { try { return { type: "normal", arg: t.call(e, r) }; } catch (t) { return { type: "throw", arg: t }; } } e.wrap = wrap; var h = "suspendedStart", l = "suspendedYield", f = "executing", s = "completed", y = {}; function Generator() {} function GeneratorFunction() {} function GeneratorFunctionPrototype() {} var p = {}; define(p, a, function () { return this; }); var d = Object.getPrototypeOf, v = d && d(d(values([]))); v && v !== r && n.call(v, a) && (p = v); var g = GeneratorFunctionPrototype.prototype = Generator.prototype = Object.create(p); function defineIteratorMethods(t) { ["next", "throw", "return"].forEach(function (e) { define(t, e, function (t) { return this._invoke(e, t); }); }); } function AsyncIterator(t, e) { function invoke(r, o, i, a) { var c = tryCatch(t[r], t, o); if ("throw" !== c.type) { var u = c.arg, h = u.value; return h && "object" == _typeof(h) && n.call(h, "__await") ? e.resolve(h.__await).then(function (t) { invoke("next", t, i, a); }, function (t) { invoke("throw", t, i, a); }) : e.resolve(h).then(function (t) { u.value = t, i(u); }, function (t) { return invoke("throw", t, i, a); }); } a(c.arg); } var r; o(this, "_invoke", { value: function value(t, n) { function callInvokeWithMethodAndArg() { return new e(function (e, r) { invoke(t, n, e, r); }); } return r = r ? r.then(callInvokeWithMethodAndArg, callInvokeWithMethodAndArg) : callInvokeWithMethodAndArg(); } }); } function makeInvokeMethod(e, r, n) { var o = h; return function (i, a) { if (o === f) throw Error("Generator is already running"); if (o === s) { if ("throw" === i) throw a; return { value: t, done: !0 }; } for (n.method = i, n.arg = a;;) { var c = n.delegate; if (c) { var u = maybeInvokeDelegate(c, n); if (u) { if (u === y) continue; return u; } } if ("next" === n.method) n.sent = n._sent = n.arg;else if ("throw" === n.method) { if (o === h) throw o = s, n.arg; n.dispatchException(n.arg); } else "return" === n.method && n.abrupt("return", n.arg); o = f; var p = tryCatch(e, r, n); if ("normal" === p.type) { if (o = n.done ? s : l, p.arg === y) continue; return { value: p.arg, done: n.done }; } "throw" === p.type && (o = s, n.method = "throw", n.arg = p.arg); } }; } function maybeInvokeDelegate(e, r) { var n = r.method, o = e.iterator[n]; if (o === t) return r.delegate = null, "throw" === n && e.iterator["return"] && (r.method = "return", r.arg = t, maybeInvokeDelegate(e, r), "throw" === r.method) || "return" !== n && (r.method = "throw", r.arg = new TypeError("The iterator does not provide a '" + n + "' method")), y; var i = tryCatch(o, e.iterator, r.arg); if ("throw" === i.type) return r.method = "throw", r.arg = i.arg, r.delegate = null, y; var a = i.arg; return a ? a.done ? (r[e.resultName] = a.value, r.next = e.nextLoc, "return" !== r.method && (r.method = "next", r.arg = t), r.delegate = null, y) : a : (r.method = "throw", r.arg = new TypeError("iterator result is not an object"), r.delegate = null, y); } function pushTryEntry(t) { var e = { tryLoc: t[0] }; 1 in t && (e.catchLoc = t[1]), 2 in t && (e.finallyLoc = t[2], e.afterLoc = t[3]), this.tryEntries.push(e); } function resetTryEntry(t) { var e = t.completion || {}; e.type = "normal", delete e.arg, t.completion = e; } function Context(t) { this.tryEntries = [{ tryLoc: "root" }], t.forEach(pushTryEntry, this), this.reset(!0); } function values(e) { if (e || "" === e) { var r = e[a]; if (r) return r.call(e); if ("function" == typeof e.next) return e; if (!isNaN(e.length)) { var o = -1, i = function next() { for (; ++o < e.length;) if (n.call(e, o)) return next.value = e[o], next.done = !1, next; return next.value = t, next.done = !0, next; }; return i.next = i; } } throw new TypeError(_typeof(e) + " is not iterable"); } return GeneratorFunction.prototype = GeneratorFunctionPrototype, o(g, "constructor", { value: GeneratorFunctionPrototype, configurable: !0 }), o(GeneratorFunctionPrototype, "constructor", { value: GeneratorFunction, configurable: !0 }), GeneratorFunction.displayName = define(GeneratorFunctionPrototype, u, "GeneratorFunction"), e.isGeneratorFunction = function (t) { var e = "function" == typeof t && t.constructor; return !!e && (e === GeneratorFunction || "GeneratorFunction" === (e.displayName || e.name)); }, e.mark = function (t) { return Object.setPrototypeOf ? Object.setPrototypeOf(t, GeneratorFunctionPrototype) : (t.__proto__ = GeneratorFunctionPrototype, define(t, u, "GeneratorFunction")), t.prototype = Object.create(g), t; }, e.awrap = function (t) { return { __await: t }; }, defineIteratorMethods(AsyncIterator.prototype), define(AsyncIterator.prototype, c, function () { return this; }), e.AsyncIterator = AsyncIterator, e.async = function (t, r, n, o, i) { void 0 === i && (i = Promise); var a = new AsyncIterator(wrap(t, r, n, o), i); return e.isGeneratorFunction(r) ? a : a.next().then(function (t) { return t.done ? t.value : a.next(); }); }, defineIteratorMethods(g), define(g, u, "Generator"), define(g, a, function () { return this; }), define(g, "toString", function () { return "[object Generator]"; }), e.keys = function (t) { var e = Object(t), r = []; for (var n in e) r.push(n); return r.reverse(), function next() { for (; r.length;) { var t = r.pop(); if (t in e) return next.value = t, next.done = !1, next; } return next.done = !0, next; }; }, e.values = values, Context.prototype = { constructor: Context, reset: function reset(e) { if (this.prev = 0, this.next = 0, this.sent = this._sent = t, this.done = !1, this.delegate = null, this.method = "next", this.arg = t, this.tryEntries.forEach(resetTryEntry), !e) for (var r in this) "t" === r.charAt(0) && n.call(this, r) && !isNaN(+r.slice(1)) && (this[r] = t); }, stop: function stop() { this.done = !0; var t = this.tryEntries[0].completion; if ("throw" === t.type) throw t.arg; return this.rval; }, dispatchException: function dispatchException(e) { if (this.done) throw e; var r = this; function handle(n, o) { return a.type = "throw", a.arg = e, r.next = n, o && (r.method = "next", r.arg = t), !!o; } for (var o = this.tryEntries.length - 1; o >= 0; --o) { var i = this.tryEntries[o], a = i.completion; if ("root" === i.tryLoc) return handle("end"); if (i.tryLoc <= this.prev) { var c = n.call(i, "catchLoc"), u = n.call(i, "finallyLoc"); if (c && u) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } else if (c) { if (this.prev < i.catchLoc) return handle(i.catchLoc, !0); } else { if (!u) throw Error("try statement without catch or finally"); if (this.prev < i.finallyLoc) return handle(i.finallyLoc); } } } }, abrupt: function abrupt(t, e) { for (var r = this.tryEntries.length - 1; r >= 0; --r) { var o = this.tryEntries[r]; if (o.tryLoc <= this.prev && n.call(o, "finallyLoc") && this.prev < o.finallyLoc) { var i = o; break; } } i && ("break" === t || "continue" === t) && i.tryLoc <= e && e <= i.finallyLoc && (i = null); var a = i ? i.completion : {}; return a.type = t, a.arg = e, i ? (this.method = "next", this.next = i.finallyLoc, y) : this.complete(a); }, complete: function complete(t, e) { if ("throw" === t.type) throw t.arg; return "break" === t.type || "continue" === t.type ? this.next = t.arg : "return" === t.type ? (this.rval = this.arg = t.arg, this.method = "return", this.next = "end") : "normal" === t.type && e && (this.next = e), y; }, finish: function finish(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.finallyLoc === t) return this.complete(r.completion, r.afterLoc), resetTryEntry(r), y; } }, "catch": function _catch(t) { for (var e = this.tryEntries.length - 1; e >= 0; --e) { var r = this.tryEntries[e]; if (r.tryLoc === t) { var n = r.completion; if ("throw" === n.type) { var o = n.arg; resetTryEntry(r); } return o; } } throw Error("illegal catch attempt"); }, delegateYield: function delegateYield(e, r, n) { return this.delegate = { iterator: values(e), resultName: r, nextLoc: n }, "next" === this.method && (this.arg = t), y; } }, e; }
function _createForOfIteratorHelper(o, allowArrayLike) { var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"]; if (!it) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = it.call(o); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }
function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }
function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i]; return arr2; }
function ownKeys(e, r) { var t = Object.keys(e); if (Object.getOwnPropertySymbols) { var o = Object.getOwnPropertySymbols(e); r && (o = o.filter(function (r) { return Object.getOwnPropertyDescriptor(e, r).enumerable; })), t.push.apply(t, o); } return t; }
function _objectSpread(e) { for (var r = 1; r < arguments.length; r++) { var t = null != arguments[r] ? arguments[r] : {}; r % 2 ? ownKeys(Object(t), !0).forEach(function (r) { _defineProperty(e, r, t[r]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(t)) : ownKeys(Object(t)).forEach(function (r) { Object.defineProperty(e, r, Object.getOwnPropertyDescriptor(t, r)); }); } return e; }
function _defineProperty(obj, key, value) { key = _toPropertyKey(key); if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }
function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }
(function ($) {
  var PRODUCT_TYPES = Object.freeze({
    RING: "rings",
    STONE: "center-stones"
  });
  var MODE = Object.freeze({
    START_WITH_SETTING: "START_WITH_SETTING",
    START_WITH_STONE: "START_WITH_STONE"
  });
  var ajaxUrl = "/wp-admin/admin-ajax.php";
  function init() {
    jQuery(document).on("click", ".build-ring-confirm .remove-design", function (e) {
      e.preventDefault();
      var uuid = $(this).attr("data-uuid");
      removeDesign(uuid, function () {
        refresh();
      });
    });
    jQuery(document).on("click", ".build-ring-mini-collection .remove-design", function (e) {
      e.preventDefault();
      var parent = $(this).closest(".build-ring-mini-collection");
      $(parent).addClass("loading");
      var uuid = $(this).attr("data-uuid");
      removeDesign(uuid, function () {
        getInitData();
        $(parent).removeClass("loading");
        refreshCart();
      });
    });
    getInitData(function (data) {
      var collections = data.collections || {};
      jQuery(".build-ring-confirm").each(function (index, value) {
        var uuid = $(this).attr("data-uuid");
        var form = $(this).find(".variations_form");
        var collection = collections[uuid] || null;
        if (!collection) {
          return;
        }
        var attrs = collection.attrs;
        if (attrs) {
          $.each(attrs, function (key, value) {
            $(form).find("[name='".concat(key, "']")).val(value);
          });
          var variationId = collection.variation_id || 0;
          $(form).find(".variation_id").val(variationId);
        }
      });
      $(document).find(".build-ring-confirm").each(function (index, value) {
        var uuid = $(this).attr("data-uuid");
        var form = $(this).find(".variations_form");
        $(form).find("[name^=attribute_pa]").each(function (index, value) {
          var selectId = $(this).attr("id");
          var id = "custom-select-".concat(selectId, "_").concat(uuid);
          customSelect(value, id);
        });
      });
      jQuery(document).find("[name='attribute_pa_metal-type']").on("change", function (el) {
        var container = $(this).closest(".build-ring-confirm");
        var uuid = container.attr("data-uuid");
        var form = $(this).closest(".variations_form");
        var val = $(this).find("option:selected").attr("value");
        var productId = $(form).data("product_id");
        var size = $(form).find("[name='attribute_pa_size']");
        var selectedSize = $(size).find("option:selected").attr("value");
        if ($(size).length) {
          jQuery.ajax({
            method: "POST",
            url: ajaxUrl,
            data: {
              action: "get_products_by_attr",
              parent_product_id: productId,
              meta_type: val
            },
            dataType: "json",
            success: function success(res) {
              var options = res.options;
              $(document).find("custom-select-pa_size_" + uuid).remove();
              $(size).html(options);
              $(size).val(selectedSize);
              customSelect($(size), "custom-select-pa_size_" + uuid);
            },
            error: function error(err) {
              console.error("Error fetching products:", err);
            }
          });
        }
      });
    });
    getSubtotal({
      success: function success(res) {
        $(".subtotal").html(res.data);
      }
    });
  }
  function alertMessage() {
    var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var _params$message = params.message,
      message = _params$message === void 0 ? "" : _params$message,
      _params$title = params.title,
      title = _params$title === void 0 ? "" : _params$title,
      _params$type = params.type,
      type = _params$type === void 0 ? "success" : _params$type;
    var $alert = null;
    $alert = $("\n\t\t\t<div class=\"build-ring-alert ".concat(type, "\">\n\t\t\t\t<div class=\"build-ring-alert__inner\">\n\t\t\t\t\t<button class=\"build-ring-alert__close\">x</button>\n\t\t\t\t\t<div class=\"build-ring-alert__content\">\n\t\t\t\t\t\t<h2 class=\"build-ring-alert__title\">").concat(title, "</h2>\n\t\t\t\t\t\t<div class=\"build-ring-alert__message\">").concat(message, "</div>\n\t\t\t\t\t\t<button class=\"build-ring-alert__confirm\">OK</button>\n\t\t\t\t\t</div>\n\t\t\t\t</div>\n\t\t\t</div>\n\t\t\t")).appendTo("body");
    var closeBtn = $alert.find(".build-ring-alert__close, .build-ring-alert__confirm");
    closeBtn.on("click", function () {
      $alert.fadeOut(300, function () {
        $(this).remove();
      });
    });
  }
  function processLoading() {
    return {
      start: function start() {
        jQuery(".page-build-ring").addClass("loading");
      },
      end: function end() {
        jQuery(".page-build-ring").removeClass("loading");
      }
    };
  }
  function setStep(step) {
    var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
    jQuery.ajax({
      url: ajaxUrl + "?action=set_step&step=" + step,
      success: function success(res) {
        if (res.isSuccess) {
          callback();
        }
      },
      dataType: "json"
    });
  }
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
  function parseQSToObject(queryString) {
    var urlParams = new URLSearchParams(queryString);
    var params = {};
    urlParams.forEach(function (value, key) {
      params[key] = value;
    });
    return params;
  }
  var refreshTimer = null;
  var cartRefreshTimer = null;
  function refreshCart() {
    if (cartRefreshTimer) {
      clearTimeout(cartRefreshTimer);
    }
    cartRefreshTimer = setTimeout(function () {
      jQuery.ajax({
        url: "/build-ring?step=" + new Date().getTime(),
        success: function success(res) {
          var cartCount = jQuery(res).find(".header-cart__count").html();
          jQuery(document).find(".header-cart__count").html(cartCount);
        }
      });
    }, 100);
  }
  function refresh() {
    var onSuccess = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : function () {};
    var loading = processLoading();
    loading.start();
    refreshTimer = setTimeout(function () {
      jQuery.ajax({
        url: "/build-ring?step=" + new Date().getTime(),
        success: function success(res) {
          var oldFilter = jQuery(document).find(".page-build-ring");
          var cartCount = jQuery(res).find(".header-cart__count").html();
          var filter = jQuery(res).find(".page-build-ring");
          oldFilter.replaceWith(filter);
          jQuery(document).find(".header-cart__count").html(cartCount);
          onSuccess();
          jQuery(document).find(".variations_form").each(function () {
            jQuery(this).wc_variation_form();
          });
          init();
          if (jQuery(".products-filter").length) {
            refreshFWP();
          }
          loading.end();
        }
      });
    }, 200);
  }
  var timer = null;
  function refreshFWP() {
    if (FWP) {
      FWP.reset();
      FWP.refresh();
    }
  }
  function redirect() {
    window.location.href = "/build-ring?step=" + new Date().getTime();
  }
  function toggleMode() {
    var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var _params$success = params.success,
      success = _params$success === void 0 ? function () {} : _params$success,
      _params$error = params.error,
      error = _params$error === void 0 ? function () {} : _params$error;
    jQuery.ajax({
      url: ajaxUrl + "?action=toggle_mode",
      success: success,
      dataType: "json",
      error: error
    });
  }
  function setMode() {
    var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var _params$success2 = params.success,
      success = _params$success2 === void 0 ? function () {} : _params$success2,
      _params$error2 = params.error,
      error = _params$error2 === void 0 ? function () {} : _params$error2,
      _params$mode = params.mode,
      mode = _params$mode === void 0 ? MODE.START_WITH_SETTING : _params$mode;
    jQuery.ajax({
      url: ajaxUrl + "?action=set_mode&mode=" + mode,
      success: success,
      dataType: "json",
      error: error
    });
  }
  function parseData(data) {
    console.log("data", data.trayItems);
    $("#build-ring-tray").html(data.trayItems);
    $("#build-ring-tray-extra-wrapper").html(data.trayExtra);
    $(".build-ring-mini-collection-wrapper").replaceWith(data.miniCollection);
    initStickyTray();
  }
  function refreshCollectionRing(uuid) {
    jQuery.ajax({
      url: ajaxUrl + "?action=refresh_collection_ring&uuid=" + uuid,
      success: function success(res) {
        if (res.isSuccess) {
          var item = jQuery(document).find('.build-ring-confirm[data-uuid="' + uuid + '"]');
          jQuery(item).find(".build-ring-collection__item__ring").html(res.data);
        }
      },
      dataType: "json"
    });
  }
  function getInitData() {
    var callback = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : function () {};
    jQuery.ajax({
      url: ajaxUrl + "?action=get_init_data",
      success: function success(data) {
        callback(data);
        parseData(data);
      },
      dataType: "json"
    });
  }
  function getSubtotal() {
    var params = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
    var _params$success3 = params.success,
      success = _params$success3 === void 0 ? function () {} : _params$success3,
      _params$error3 = params.error,
      error = _params$error3 === void 0 ? function () {} : _params$error3,
      _params$product = params.product,
      product = _params$product === void 0 ? "" : _params$product,
      _params$stone = params.stone,
      stone = _params$stone === void 0 ? "" : _params$stone;
    jQuery.ajax({
      url: ajaxUrl + "?action=get_subtotal",
      success: success,
      dataType: "json",
      error: error
    });
  }
  function productIsValid(_x) {
    return _productIsValid.apply(this, arguments);
  }
  function _productIsValid() {
    _productIsValid = _asyncToGenerator( /*#__PURE__*/_regeneratorRuntime().mark(function _callee(productId) {
      return _regeneratorRuntime().wrap(function _callee$(_context) {
        while (1) switch (_context.prev = _context.next) {
          case 0:
            return _context.abrupt("return", new Promise(function (resolve, reject) {
              jQuery.ajax({
                url: ajaxUrl + "?action=product_is_valid&product_id=" + productId,
                success: function success(res) {
                  if (res.isSuccess) {
                    resolve(res.data);
                  } else {
                    reject(res.data);
                  }
                },
                dataType: "json",
                error: function error(err) {
                  reject(err);
                }
              });
            }));
          case 1:
          case "end":
            return _context.stop();
        }
      }, _callee);
    }));
    return _productIsValid.apply(this, arguments);
  }
  function getErrorMessage(button) {
    if ($(button).hasClass("disabled")) {
      if ($(button).hasClass("wc-variation-is-unavailable")) {
        console.log("button22", $(button).hasClass("disabled"), button);
        return wc_add_to_cart_variation_params.i18n_unavailable_text;
      } else if ($(button).hasClass("wc-variation-selection-needed")) {
        return wc_add_to_cart_variation_params.i18n_make_a_selection_text;
      }
    }
    return "";
  }
  function isValidCollections(_ref) {
    var _ref$onSuccess = _ref.onSuccess,
      onSuccess = _ref$onSuccess === void 0 ? function () {} : _ref$onSuccess,
      error = _ref.error;
    jQuery.ajax({
      url: ajaxUrl + "?action=is_valid_collections",
      success: onSuccess,
      dataType: "json",
      error: error
    });
  }
  function selectProduct(productId, type) {
    var callback = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : function () {};
    var extraParams = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : {};
    var ajaxUrl = typeof jsData !== "undefined" && jsData.ajaxUrl ? jsData.ajaxUrl : "/wp-admin/admin-ajax.php";
    $.ajax({
      url: ajaxUrl,
      type: "POST",
      data: _objectSpread({
        action: "select_product",
        product_id: productId,
        type: type
      }, extraParams),
      dataType: "json",
      success: function success(response) {
        callback(response);
      },
      error: function error(err) {
        console.error("Error selecting product", err);
      }
    });
  }
  function updateProductAttributes(formData) {
    var callback = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : function () {};
    jQuery.ajax({
      url: ajaxUrl + "?action=update_product_attrs&" + formData,
      success: function success(data) {
        callback(data);
      },
      dataType: "json"
    });
  }
  $(document).on("click", "#build-ring-toggle-mode", function () {
    toggleMode({
      success: function success() {
        refresh();
      }
    });
  });
  $(".header-cart").on("click", function (e) {
    e.preventDefault();
    var href = $(this).attr("href");
    isValidCollections({
      onSuccess: function onSuccess(res) {
        var isValid = res.isValid;
        var message = res.message;
        if (isValid) {
          window.location.href = href;
          return;
        }
        alertMessage({
          message: message,
          title: "Error",
          type: "error"
        });
      }
    });
  });
  $(document).on("click", "#build-ring-mode--stone", function () {
    setMode({
      mode: MODE.START_WITH_STONE,
      success: function success() {
        refresh();
      }
    });
  });
  $(document).on("click", "#build-ring-mode--setting", function () {
    setMode({
      mode: MODE.START_WITH_SETTING,
      success: function success() {
        refresh();
      }
    });
  });
  function initDragDrop() {
    if (!$.fn.draggable || !$.fn.droppable) {
      console.warn("jQuery UI Draggable/Droppable not loaded");
      return;
    }

    // Add drag handle zone (center 50% of image)
    function addDragHandle($products) {
      // Add keyframes animation if not already added
      if (!document.getElementById("drag-handle-animation-style")) {
        var style = document.createElement("style");
        style.id = "drag-handle-animation-style";
        style.textContent = "\n\t\t\t\t\t@keyframes slide-horizontal {\n\t\t\t\t\t\t0% { transform: translateX(0px); }\n\t\t\t\t\t\t100% { transform: translateX(0px); }\n\t\t\t\t\t}\n\t\t\t\t\t@keyframes glow {\n\t\t\t\t\t\t0%, 100% { filter: drop-shadow(0 0 2px rgba(255,255,255,0.5)); }\n\t\t\t\t\t\t50% { filter: drop-shadow(0 0 8px rgba(255,255,255,0.9)); }\n\t\t\t\t\t}\n\t\t\t\t\t.drag-handle-zone svg {\n\t\t\t\t\t\tanimation: slide-horizontal 1s ease-in-out infinite alternate, glow 2s ease-in-out infinite;\n\t\t\t\t\t}\n\t\t\t\t";
        document.head.appendChild(style);
      }
      $products.each(function () {
        var $product = $(this);
        var $img = $product.find("img").first();

        // Remove existing handle if any
        $product.find(".drag-handle-zone").remove();
        if ($img.length) {
          // Create drag handle zone (square: 50% width x 50% width, centered)
          var $handle = $("<div>").addClass("drag-handle-zone");

          // Make image container relative
          $img.closest(".product-image, .product").css("position", "relative");
          $img.parent().css("position", "relative");

          // Insert handle after image
          $img.parent().append($handle);

          // Show handle on hover
          $img.parent().on("mouseenter", function () {
            $(this).find(".drag-handle-zone").css("opacity", 1);
          });
          $img.parent().on("mouseleave", function () {
            $(this).find(".drag-handle-zone").css("opacity", 0);
          });
        }
      });
    }
    var draggableConfig = {
      revert: "invalid",
      helper: function helper() {
        var $original = $(this);
        var $clone = $original.clone();
        $clone.css({
          width: $original.find("img").first().outerWidth(),
          height: $original.find("img").first().outerHeight() - 2
        });
        return $clone;
      },
      cursor: "move",
      zIndex: 9999,
      appendTo: "#wrapper__inner",
      handle: ".drag-handle-zone"
    };
    if ($(document).find(".center-stones-list").length) {
      var $stoneProducts = $(document).find(".center-stones-list .product");
      addDragHandle($stoneProducts);
      $stoneProducts.draggable(draggableConfig);
      $(document).find("#build-ring-tray").droppable({
        accept: ".center-stones-list .product",
        hoverClass: "ui-state-hover",
        drop: function drop(event, ui) {
          handleDrop(ui.draggable, PRODUCT_TYPES.STONE, $(this));
        },
        tolerance: "pointer"
      });
    }
    if ($(document).find(".rings-list").length) {
      var $ringProducts = $(document).find(".rings-list .product");
      addDragHandle($ringProducts);
      $ringProducts.draggable(draggableConfig);
      $(document).find("#build-ring-tray").droppable({
        accept: ".rings-list .product",
        hoverClass: "ui-state-hover",
        drop: function drop(event, ui) {
          handleDrop(ui.draggable, PRODUCT_TYPES.RING, $(this));
        },
        tolerance: "pointer"
      });
    }
  }
  function handleDrop($item, type, $target) {
    var productId = 0;
    // Try to find post-ID class
    var classes = $item.attr("class").split(/\s+/);
    var _iterator = _createForOfIteratorHelper(classes),
      _step;
    try {
      for (_iterator.s(); !(_step = _iterator.n()).done;) {
        var cls = _step.value;
        if (cls.match(/^post-\d+$/)) {
          productId = cls.replace("post-", "");
          break;
        }
      }
    } catch (err) {
      _iterator.e(err);
    } finally {
      _iterator.f();
    }
    if (!productId) {
      // Try finding add to cart button which usually has data-product_id
      productId = $item.find(".add_to_cart_button").data("product_id");
    }
    if (!productId) {
      console.error("Product ID not found for dragged item");
      return;
    }
    selectProduct(productId, type, function (res) {
      var message = (res === null || res === void 0 ? void 0 : res.message) || "";
      // Visual feedback
      if (res.isSuccess) {
        var $img = $item.find("img").clone();
        var $product = $("<div class='build-ring-tray__product'></div>").data("product-id", productId).append($img);
        var tray = type == PRODUCT_TYPES.RING ? "#build-ring-tray__ring" : "#build-ring-tray__stone";
        $target.find(tray).find(".build-ring-tray__empty").remove();
        $target.find(tray).append($product);
        refresh(function () {
          parseData(res.data);
        });
      } else {
        alertMessage({
          message: message,
          title: "Error",
          type: "error"
        });
      }
    });
  }
  $(document).on("facetwp-loaded", function () {
    initDragDrop();
  });
  $(".variations_form").on("submit", function (e) {
    e.preventDefault();
  });
  jQuery(".product-detail-build-ring .variations_form").on("submit", function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    var formObj = parseQSToObject(formData);
    var productType = formObj.product_type;

    // remove add_to_cart name from formData
    formData = formData.replace(/add-to-cart=\d+/, "");
    delete formObj["add-to-cart"];
    selectProduct(formObj.product_id, productType, function (res) {
      if (res.isSuccess) {
        updateProductAttributes(formData, function (res) {
          if (res.isSuccess) {
            redirect();
          }
        });
      } else {
        alertMessage({
          message: res.message,
          title: "Error",
          type: "error"
        });
      }
    }, _objectSpread(_objectSpread({}, formObj), {}, {
      stone_option: formObj.stone_option
    }));
  });
  jQuery(".product-detail-build-ring .cart:not(.variations_form)").on("submit", function (e) {
    e.preventDefault();
    var formData = $(this).serialize();
    var formObj = parseQSToObject(formData);
    var productType = formObj.product_type;
    var value = $(this).find(".single_add_to_cart_button").val();
    selectProduct(value, productType, function (res) {
      if (res.isSuccess) {
        redirect();
      } else {
        alertMessage({
          message: message,
          title: "Error",
          type: "error"
        });
      }
    });
  });
  jQuery(document).on("click", "#start-new-design", function (e) {
    e.preventDefault();
    setStep(1, function () {
      refresh();
    });
  });
  jQuery(document).on("click", "#continue-to-checkout", function (e) {
    e.preventDefault();
    var collections = jQuery(document).find(".build-ring-confirm");
    var isValid = true;
    jQuery.each(collections, function () {
      var form = jQuery(this).find(".variations_form");
      var variationId = parseInt(jQuery(form).find(".variation_id").val());
      var error = jQuery(form).find(".product-error-message").html();
      if (!variationId || error) {
        isValid = false;
      }
    });
    if (!isValid) {
      alertMessage({
        message: "Please select the Metal Type and Ring Size for your setting before continuing.",
        title: "Did you forget?",
        type: "error"
      });
      return;
    }
    window.location.href = "/checkout/";
  });
  jQuery(document).on("change", "#build-ring-tray-extra [name='stone_option']", function (e) {
    e.preventDefault();
    var value = $(this).val();
    console.log("value", value);
    if (value !== "custom_stone" && value !== "") {
      selectProduct(value, PRODUCT_TYPES.STONE, function (res) {
        if (res.isSuccess) {
          refresh();
        }
      });
    }
  });
  jQuery(document).on("click", ".set-step", function (e) {
    e.preventDefault();
    var step = $(this).attr("data-step");
    setStep(step, function () {
      refresh();
    });
  });
  jQuery(document).on("change", ".build-ring-confirm .variations_form .variation_id", function (el) {
    var container = $(this).closest(".build-ring-confirm");
    var uuid = $(container).attr("data-uuid");
    var form = $(this).closest(".variations_form");
    var subtotalEl = $(document).find(".subtotal");
    var productId = parseInt($(this).val());
    var formData = $(form).serialize();
    var formObj = parseQSToObject(formData);
    var errorEl = $(container).find(".product-error-message");
    delete formObj["add-to-cart"];
    selectProduct(productId, PRODUCT_TYPES.RING, function (res) {
      var message = res.message || "";
      if (message) {
        errorEl.html(message);
      } else {
        errorEl.html("");
      }
      getSubtotal({
        success: function success(res) {
          if (res.isSuccess) {
            subtotalEl.html(res.data);
          }
        }
      });
      refreshCollectionRing(uuid);
      refreshCart();
    }, _objectSpread({}, formObj));
  });
  jQuery(document).on("click", ".build-ring-tray__item-remove", function (e) {
    e.preventDefault();
    var $item = $(this).closest(".build-ring-tray__item");
    $item.addClass("loading");
    var id = $(this).data("id");
    removeTrayItem(id, function (res) {
      if (res.isSuccess) {
        getInitData();
      } else {
        alertMessage({
          message: res.message || "",
          title: "Error",
          type: "error"
        });
      }
      $item.removeClass("loading");
      refreshCart();
    });
  });

  // add js sticky for .build-ring-tray
  function initStickyTray() {
    // disable sticky on mobile
    if ($(window).width() < 768) {
      return;
    }
    var $tray = $(".build-ring-tray-wrapper");
    if ($tray.length && $.fn.stick_in_parent) {
      $tray.stick_in_parent({
        parent: "#build-ring-tray-slots",
        offset_top: 20
      });
    }
  }
  init();
})(jQuery);
/******/ })()
;
//# sourceMappingURL=build-ring.js.map