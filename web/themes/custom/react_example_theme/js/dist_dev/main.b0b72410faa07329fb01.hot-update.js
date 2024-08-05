"use strict";
self["webpackHotUpdatereact_example_theme"]("main",{

/***/ "./js/src/components/DrupalProjectStats.jsx":
/*!**************************************************!*\
  !*** ./js/src/components/DrupalProjectStats.jsx ***!
  \**************************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "./node_modules/react/index.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! prop-types */ "./node_modules/prop-types/index.js");
/* harmony import */ var prop_types__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(prop_types__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _config__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../config */ "./js/src/config.js");
/* provided dependency */ var __react_refresh_utils__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js");
/* provided dependency */ var __react_refresh_error_overlay__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js");
__webpack_require__.$Refresh$.runtime = __webpack_require__(/*! ./node_modules/react-refresh/runtime.js */ "./node_modules/react-refresh/runtime.js");

var _s = __webpack_require__.$Refresh$.signature();
function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }



var DrupalProjectStats = function DrupalProjectStats() {
  _s();
  var _useState = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('drupal'),
    _useState2 = _slicedToArray(_useState, 2),
    project = _useState2[0],
    setProject = _useState2[1];
  var _useState3 = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)(null),
    _useState4 = _slicedToArray(_useState3, 2),
    usage = _useState4[0],
    setUsage = _useState4[1];
  var projects = [{
    key: 'drupal',
    value: 'Drupal'
  }, {
    key: 'marquee',
    value: 'Marquee'
  }, {
    key: 'token',
    value: 'Token'
  }, {
    key: 'pathauto',
    value: 'Pathauto'
  }];
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(function () {
    setUsage(false);
    var data = fetch("https://www.drupal.org/api-d7/node.json?field_project_machine_name=".concat(project)).then(function (response) {
      return response.json();
    }).then(function (result) {
      if (result.list[0].project_usage) {
        setUsage(result.list[0].project_usage);
      }
    })["catch"](function (error) {
      return console.log("error", error);
    });
  }, [project]);
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", null, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", null, "Choose a project:", /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("br", null), projects.map(function (elem) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("button", {
      onClick: function onClick() {
        return setProject(elem.key);
      }
    }, elem.value);
  })), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("hr", null), /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("div", {
    className: "project--name"
  }, "Usage stats for ", /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("strong", null, project), " by version:", usage ? /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("ul", null, Object.keys(usage).map(function (key) {
    return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement(StatsItem, {
      count: usage[key],
      version: key,
      key: key
    });
  })) : /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("p", null, "fetching data ...")));
};

// Provide type checking for props. Think of this as documentation for what
// props a component accepts.
// https://reactjs.org/docs/typechecking-with-proptypes.html
_s(DrupalProjectStats, "fJnwNmU3LSfL0ZOj/s7dhVmtLTo=");
_c = DrupalProjectStats;
DrupalProjectStats.propTypes = {
  projectName: (prop_types__WEBPACK_IMPORTED_MODULE_2___default().string).required
};

// Set a default value for any required props.
DrupalProjectStats.defaultProps = {
  projectName: 'drupal'
};

/**
 * Another component: this one displays usage statistics for a specific version
 * of Drupal. It's not exported, so it can only be used in this file's scope.
 * Breaking things up like this can help make your code easier to maintain.
 */
var StatsItem = function StatsItem(_ref) {
  var count = _ref.count,
    version = _ref.version;
  return /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("li", null, /*#__PURE__*/react__WEBPACK_IMPORTED_MODULE_0___default().createElement("strong", null, version), ": ", count);
};
_c2 = StatsItem;
StatsItem.propTypes = {
  count: (prop_types__WEBPACK_IMPORTED_MODULE_2___default().number),
  version: (prop_types__WEBPACK_IMPORTED_MODULE_2___default().string)
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (DrupalProjectStats);
var _c, _c2;
__webpack_require__.$Refresh$.register(_c, "DrupalProjectStats");
__webpack_require__.$Refresh$.register(_c2, "StatsItem");

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

/***/ }),

/***/ "./js/src/config.js":
/*!**************************!*\
  !*** ./js/src/config.js ***!
  \**************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   API_CREATE_ORDER: () => (/* binding */ API_CREATE_ORDER),
/* harmony export */   API_KEY: () => (/* binding */ API_KEY),
/* harmony export */   API_PRODUCT: () => (/* binding */ API_PRODUCT),
/* harmony export */   API_PRODUCTS: () => (/* binding */ API_PRODUCTS),
/* harmony export */   API_SEND_CONTACTS_FORM: () => (/* binding */ API_SEND_CONTACTS_FORM),
/* harmony export */   API_URL: () => (/* binding */ API_URL),
/* harmony export */   API_USER: () => (/* binding */ API_USER),
/* harmony export */   API_USER_REGISTER: () => (/* binding */ API_USER_REGISTER),
/* harmony export */   APP_TEST: () => (/* binding */ APP_TEST),
/* harmony export */   CLIENT_ID: () => (/* binding */ CLIENT_ID),
/* harmony export */   CLIENT_SECRET: () => (/* binding */ CLIENT_SECRET),
/* harmony export */   GRANT_TYPE: () => (/* binding */ GRANT_TYPE),
/* harmony export */   OAUTH_TOKEN_URL: () => (/* binding */ OAUTH_TOKEN_URL),
/* harmony export */   PASSWORD: () => (/* binding */ PASSWORD),
/* harmony export */   SCOPE: () => (/* binding */ SCOPE),
/* harmony export */   SHOP_URL: () => (/* binding */ SHOP_URL),
/* harmony export */   USERNAME: () => (/* binding */ USERNAME)
/* harmony export */ });
/* provided dependency */ var __react_refresh_utils__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/lib/runtime/RefreshUtils.js");
/* provided dependency */ var __react_refresh_error_overlay__ = __webpack_require__(/*! ./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js */ "./node_modules/@pmmmwh/react-refresh-webpack-plugin/overlay/index.js");
__webpack_require__.$Refresh$.runtime = __webpack_require__(/*! ./node_modules/react-refresh/runtime.js */ "./node_modules/react-refresh/runtime.js");

//import process from 'process'

//require('dotenv').config()

//require('dotenv').config({ path: require('find-config')('.env') })

//console.log(require("dotenv").config())

// const process = require('process');
//  import dotenv from "dotenv"
// dotenv.config();
// // import * as path from 'path';
// //dotenv.config({ path: path.join(__dirname, '..', '.env') });
//
// //////dotenv.config({ path: __dirname });
//
// console.log(__dirname);
// console.log(__filename);

//dotenv.config({ path: './env' });
//console.log(path.join(__dirname, '..', '.env'));

// const path = require('path')
// require('dotenv').config({path: path.relative(process.cwd(), path.join(__dirname,'.env'))});

//console.log(process);

var API_KEY = "0244e259-8f89bbb6-20b7242e-1fce12a7";
var API_URL = "https://fortniteapi.io/v2/shop?lang=en";
var GRANT_TYPE = "password";
var CLIENT_ID = "dticOO-Q1_MQvJ3HGXDlknY0KwQVi7n_K72ormxj3aA";
var CLIENT_SECRET = "app";
var SCOPE = "oauth";
var USERNAME = "oauth123";
var PASSWORD = "oauth123";
var SHOP_URL = "https://shop-site.ddev.site";
var OAUTH_TOKEN_URL = "/oauth/token";
var API_PRODUCTS = "/api/v2/products";
var API_PRODUCT = "/api/v3/products/";
var API_USER = "/api/v3/users/";
var API_CREATE_ORDER = "/api/v2/shop-order-resource";
var API_USER_REGISTER = "/user/register";
var API_SEND_CONTACTS_FORM = "/entity/contact_message";
var APP_TEST = "test_env_var";
var projects = [{
  key: 'drupal',
  value: 'Drupal'
}, {
  key: 'marquee',
  value: 'Marquee'
}, {
  key: 'token',
  value: 'Token'
}, {
  key: 'pathauto',
  value: 'Pathauto'
}];


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
/******/ 	__webpack_require__.h = () => ("92e5221382880baf9fad")
/******/ })();
/******/ 
/******/ }
);
//# sourceMappingURL=main.b0b72410faa07329fb01.hot-update.js.map