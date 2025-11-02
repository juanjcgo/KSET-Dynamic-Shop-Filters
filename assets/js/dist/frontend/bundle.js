/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/frontend/index.js":
/*!*******************************!*\
  !*** ./src/frontend/index.js ***!
  \*******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ \"react\");\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);\n/* harmony import */ var react_dom_client__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! react-dom/client */ \"react-dom/client\");\n/* harmony import */ var react_dom_client__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(react_dom_client__WEBPACK_IMPORTED_MODULE_1__);\n/* harmony import */ var _selectors_FilterShopProducts__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./selectors/FilterShopProducts */ \"./src/frontend/selectors/FilterShopProducts.jsx\");\n/* harmony import */ var _selectors_FilterSearchResults__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./selectors/FilterSearchResults */ \"./src/frontend/selectors/FilterSearchResults.jsx\");\n\n\n\n\n\n// Use WordPress React if available\nvar _ref = wp.element || (react__WEBPACK_IMPORTED_MODULE_0___default()),\n  createElement = _ref.createElement,\n  render = _ref.render;\ndocument.addEventListener('DOMContentLoaded', function () {\n  var appShopFilters = document.getElementById('kset-shop-filters');\n  var appShopResults = document.getElementById('kset-search-results');\n  if (appShopFilters) {\n    // Try WordPress way first, fallback to React 18+\n    if (wp.element && wp.element.render) {\n      wp.element.render(createElement(_selectors_FilterShopProducts__WEBPACK_IMPORTED_MODULE_2__[\"default\"]), appShopFilters);\n    } else {\n      var appRoot = (0,react_dom_client__WEBPACK_IMPORTED_MODULE_1__.createRoot)(appShopFilters);\n      appRoot.render(wp.element.createElement(_selectors_FilterShopProducts__WEBPACK_IMPORTED_MODULE_2__[\"default\"], null));\n    }\n  }\n  if (appShopResults) {\n    // Try WordPress way first, fallback to React 18+\n    if (wp.element && wp.element.render) {\n      wp.element.render(createElement(_selectors_FilterSearchResults__WEBPACK_IMPORTED_MODULE_3__[\"default\"]), appShopResults);\n    } else {\n      var _appRoot = (0,react_dom_client__WEBPACK_IMPORTED_MODULE_1__.createRoot)(appShopResults);\n      _appRoot.render(wp.element.createElement(_selectors_FilterSearchResults__WEBPACK_IMPORTED_MODULE_3__[\"default\"], null));\n    }\n  }\n});\n\n//# sourceURL=webpack://kset-dynamic-shop-filters/./src/frontend/index.js?\n}");

/***/ }),

/***/ "./src/frontend/selectors/FilterSearchResults.jsx":
/*!********************************************************!*\
  !*** ./src/frontend/selectors/FilterSearchResults.jsx ***!
  \********************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ \"react\");\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);\n\nvar FilterSearchResults = function FilterSearchResults() {\n  return wp.element.createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, wp.element.createElement(\"h1\", null, \"FilterSearchResults Component\"));\n};\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FilterSearchResults);\n\n//# sourceURL=webpack://kset-dynamic-shop-filters/./src/frontend/selectors/FilterSearchResults.jsx?\n}");

/***/ }),

/***/ "./src/frontend/selectors/FilterShopProducts.jsx":
/*!*******************************************************!*\
  !*** ./src/frontend/selectors/FilterShopProducts.jsx ***!
  \*******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

eval("{__webpack_require__.r(__webpack_exports__);\n/* harmony export */ __webpack_require__.d(__webpack_exports__, {\n/* harmony export */   \"default\": () => (__WEBPACK_DEFAULT_EXPORT__)\n/* harmony export */ });\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ \"react\");\n/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);\n\nvar FilterShopProducts = function FilterShopProducts() {\n  return wp.element.createElement((react__WEBPACK_IMPORTED_MODULE_0___default().Fragment), null, wp.element.createElement(\"h1\", null, \"FilterShopProducts Component\"));\n};\n/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (FilterShopProducts);\n\n//# sourceURL=webpack://kset-dynamic-shop-filters/./src/frontend/selectors/FilterShopProducts.jsx?\n}");

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = React;

/***/ }),

/***/ "react-dom/client":
/*!***************************!*\
  !*** external "ReactDOM" ***!
  \***************************/
/***/ ((module) => {

module.exports = ReactDOM;

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
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
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
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	var __webpack_exports__ = __webpack_require__("./src/frontend/index.js");
/******/ 	
/******/ })()
;