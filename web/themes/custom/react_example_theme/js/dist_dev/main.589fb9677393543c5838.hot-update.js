"use strict";
self["webpackHotUpdatereact_example_theme"]("main",{

/***/ "./js/src/components/NodeListOnly.jsx":
/*!********************************************!*\
  !*** ./js/src/components/NodeListOnly.jsx ***!
  \********************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../config */ "./js/src/config.js");
/* harmony import */ var base_64__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! base-64 */ "./node_modules/base-64/base64.js");
/* harmony import */ var base_64__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(base_64__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _NodeItem__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./NodeItem */ "./js/src/components/NodeItem.jsx");
/* provided dependency */ var __react_refresh_utils__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js");
/* provided dependency */ var __react_refresh_error_overlay__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js");
__webpack_require__.$Refresh$.runtime = __webpack_require__(/*! ./node_modules/react-refresh/runtime.js */ "./node_modules/react-refresh/runtime.js");

var _s = __webpack_require__.$Refresh$.signature();
function _extends() { return _extends = Object.assign ? Object.assign.bind() : function (n) { for (var e = 1; e < arguments.length; e++) { var t = arguments[e]; for (var r in t) ({}).hasOwnProperty.call(t, r) && (n[r] = t[r]); } return n; }, _extends.apply(null, arguments); }
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }




function NodeListOnly() {
  _s();
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)([]),
    _useState2 = _slicedToArray(_useState, 2),
    content = _useState2[0],
    setContent = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState4 = _slicedToArray(_useState3, 2),
    filter = _useState4[0],
    setFilter = _useState4[1];
  var _useState5 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('page'),
    _useState6 = _slicedToArray(_useState5, 2),
    type = _useState6[0],
    setFType = _useState6[1];
  var site_url = 'https://shop-site.ddev.site/';
  var api_url = 'jsonapi/node/';
  var node_types = [{
    id: 'page',
    'label': 'Page'
  }, {
    id: 'article',
    'label': 'Article'
  }];
  var typeSelect = function typeSelect(e) {
    var type = e.target.value;
  };
  var searchOnChange = function searchOnChange(e) {
    var word = e.target.value;
    setFilter(word.toLowerCase());
  };
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
    fetch(site_url + api_url + '/' + type, {
      method: 'GET',
      headers: new Headers({
        'Authorization': 'Basic ' + (0,base_64__WEBPACK_IMPORTED_MODULE_2__.encode)(_config__WEBPACK_IMPORTED_MODULE_1__.USERNAME + ":" + _config__WEBPACK_IMPORTED_MODULE_1__.PASSWORD),
        'Content-Type': 'application/json'
      })
      // headers: {
      //   'Authorization': '7XApls2RNEO6xmqVpbehZzbJGLOhtIkwAlHm5nU8ay25rK8XrVmAD1cybGQHr0xA',
      // },
    }).then(function (response) {
      return response.json();
    }).then(function (data) {
      return setContent(data.data);
    })["catch"](function (err) {
      return console.log('There was an error accessing the API', err);
    });
  }, []);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, content.length ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "content"
  }, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "node-type"
  }, node_types.map(function (node_type) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("label", {
      "for": node_type.id
    }, node_type.label, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("input", {
      id: node_type.id,
      name: "types",
      type: "radio",
      value: node_type.id,
      onClick: typeSelect
    }));
  })), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("input", {
    type: "texfield",
    name: "search",
    onChange: searchOnChange
  }), content.filter(function (item) {
    if (!filter) {
      return item;
    }
    if (filter && (item.attributes.title.toLowerCase().includes(filter) || item.attributes.body.value.toLowerCase().includes(filter))) {
      return item;
    }
  }).map(function (item) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(_NodeItem__WEBPACK_IMPORTED_MODULE_3__["default"], _extends({
      key: item.id
    }, item.attributes));
  })) : '');
}
_s(NodeListOnly, "75hpSfhUNA0uv9SRjuWMIluchM4=");
_c = NodeListOnly;
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (NodeListOnly);
var _c;
__webpack_require__.$Refresh$.register(_c, "NodeListOnly");

const $ReactRefreshModuleId$ = __webpack_require__.$Refresh$.moduleId;
const $ReactRefreshCurrentExports$ = __react_refresh_utils__.getModuleExports(
	$ReactRefreshModuleId$
);

function $ReactRefreshModuleRuntime$(exports) {
	if (true) {
		let errorOverlay;
		if (typeof __react_refresh_error_overlay__ !== 'undefined') {
			errorOverlay = __react_refresh_error_overlay__;
		}
		let testMode;
		if (typeof __react_refresh_test__ !== 'undefined') {
			testMode = __react_refresh_test__;
		}
		return __react_refresh_utils__.executeRuntime(
			exports,
			$ReactRefreshModuleId$,
			module.hot,
			errorOverlay,
			testMode
		);
	}
}

if (typeof Promise !== 'undefined' && $ReactRefreshCurrentExports$ instanceof Promise) {
	$ReactRefreshCurrentExports$.then($ReactRefreshModuleRuntime$);
} else {
	$ReactRefreshModuleRuntime$($ReactRefreshCurrentExports$);
}

/***/ })

},
/******/ function(__webpack_require__) { // webpackRuntimeModules
/******/ /* webpack/runtime/getFullHash */
/******/ (() => {
/******/ 	__webpack_require__.h = () => ("1ad83ce15204576bc763")
/******/ })();
/******/ 
/******/ }
);
//# sourceMappingURL=main.589fb9677393543c5838.hot-update.js.map