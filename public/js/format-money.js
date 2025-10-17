"use strict";

/*!
 * format-money-js v1.6.3
 * (c) 2020-2023 Yurii Derevych
 * URL: https://github.com/dejurin/format-money-js
 * Sponsored:
 * https://cr.today/
 * https://currencyrate.today/
 * Released under the BSD-2-Clause License.
 */
function _typeof(o) { "@babel/helpers - typeof"; return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (o) { return typeof o; } : function (o) { return o && "function" == typeof Symbol && o.constructor === Symbol && o !== Symbol.prototype ? "symbol" : typeof o; }, _typeof(o); }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
function _defineProperties(e, r) { for (var t = 0; t < r.length; t++) { var o = r[t]; o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(e, _toPropertyKey(o.key), o); } }
function _createClass(e, r, t) { return r && _defineProperties(e.prototype, r), t && _defineProperties(e, t), Object.defineProperty(e, "prototype", { writable: !1 }), e; }
function _toPropertyKey(t) { var i = _toPrimitive(t, "string"); return "symbol" == _typeof(i) ? i : i + ""; }
function _toPrimitive(t, r) { if ("object" != _typeof(t) || !t) return t; var e = t[Symbol.toPrimitive]; if (void 0 !== e) { var i = e.call(t, r || "default"); if ("object" != _typeof(i)) return i; throw new TypeError("@@toPrimitive must return a primitive value."); } return ("string" === r ? String : Number)(t); }
function _classCallCheck(a, n) { if (!(a instanceof n)) throw new TypeError("Cannot call a class as a function"); }
// Make it available globally for browser use
window.FormatMoney = FormatMoney;
var FormatMoney = /*#__PURE__*/_createClass(function FormatMoney(options) {
  var _this = this;
  _classCallCheck(this, FormatMoney);
  this.options = options;
  this.version = '1.6.3';
  this.defaults = {
    grouping: true,
    separator: ',',
    decimalPoint: '.',
    decimals: 0,
    symbol: '',
    append: false,
    leadZeros: true
  };
  // Format
  this.from = function (value) {
    var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
    var parse = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
    // Merge custom options
    var customOptions = Object.assign(Object.assign({}, _this.options), options);
    // If value not number return undefined
    if (typeof value !== 'number') return undefined;
    // If value is NaN
    if (Number.isNaN(value)) return undefined;
    // Set a sign for negative number
    var negativeSign = value < 0 ? '-' : '';
    var result;
    var left;
    var body;
    var prefix = '';
    var suffix = '';
    result = Math.abs(value).toFixed(customOptions.decimals);
    if (parseFloat(result) === 0 || result === '0') {
      negativeSign = '';
    }
    if (!customOptions.leadZeros) {
      var resultFloat = parseFloat(result);
      result = resultFloat.toString();
    }
    var resultArr = result.split('.');
    var _resultArr = _slicedToArray(resultArr, 1);
    left = _resultArr[0];
    var right = resultArr.length > 1 ? customOptions.decimalPoint + resultArr[1] : '';
    if (customOptions.grouping) {
      body = '';
      for (var i = 0, len = left.length; i < len; i += 1) {
        if (i !== 0 && i % 3 === 0) {
          body = customOptions.separator + body;
        }
        body = left[len - i - 1] + body;
      }
      left = body;
    }
    if (customOptions.append) {
      suffix = customOptions.symbol;
    } else {
      prefix = customOptions.symbol;
    }
    if (parse) {
      return {
        source: value,
        negative: value < 0,
        fullAmount: left + right,
        amount: left,
        decimals: right,
        symbol: customOptions.symbol
      };
    }
    return negativeSign + prefix + left + right + suffix;
  };
  // Unformat
  this.un = function (value, options) {
    // Merge custom options
    var customOptions = Object.assign(Object.assign({}, _this.options), options);
    if (typeof value === 'number') return value;
    if (typeof value !== 'string') return undefined;
    // Build regex to strip out everything except digits, decimal point and minus sign:
    var regex = new RegExp("[^0-9-".concat(customOptions.decimalPoint, "]"), 'g');
    var unFormatted = parseFloat(value.replace(/\((?=\d+)(.*)\)/, '-$1') // replace bracketed values with negatives
    .replace(regex, '') // strip out any cruft
    .replace("".concat(customOptions.decimalPoint), '.'));
    return !Number.isNaN(unFormatted) ? unFormatted : 0;
  };
  // Merge options
  this.options = Object.assign(Object.assign({}, this.defaults), options);
});
// Make it available globally for browser use
window.FormatMoney = FormatMoney;
