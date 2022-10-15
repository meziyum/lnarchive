/******/ (function() { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/sass/admin.scss":
/*!*****************************!*\
  !*** ./src/sass/admin.scss ***!
  \*****************************/
/***/ (function(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

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
/************************************************************************/
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	!function() {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = function(exports) {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	}();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
!function() {
/*!*************************!*\
  !*** ./src/js/admin.js ***!
  \*************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _sass_admin_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../sass/admin.scss */ "./src/sass/admin.scss");
// admin.js
//import './directory';
//Import Styles

jQuery(document).ready(function () {
  //If the document is ready. Prevent execution of the js before the document is ready
  if (jQuery("#titlediv").length) {
    //If Post Title exists
    if (jQuery("input[name=post_title]").val() == '') {
      //Default state of the button
      document.querySelector('#publish').disabled = true; //Disable the Publish Button
    }

    jQuery("input[name=post_title]").keyup(function () {
      //Post Title Key Input action
      if (jQuery("input[name=post_title]").val() == '') {
        document.querySelector('#publish').disabled = true; //Disable the Publish Button
      } else {
        document.querySelector('#publish').disabled = false; //Enable the Publish Button
      }
    });
  }

  if (jQuery("#series").length) {
    //If Series Selector exists
    if (jQuery('#series_meta').find(":selected").val() === "none") {
      //Default state of the button
      document.querySelector('#publish').disabled = true; //Disable the Publish Button
    }

    jQuery('#series').keyup(function () {
      //Series key input action
      if (jQuery('#series_meta').find(":selected").val() === "none") {
        //If the selected value is none
        document.querySelector('#publish').disabled = true; //Disable the Publish button
      } else {
        //Else
        document.querySelector('#publish').disabled = false; //Enable the Publish Button
      }
    });
  }
});
}();
/******/ })()
;
//# sourceMappingURL=admin.js.map