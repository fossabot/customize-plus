/* exported: Preprocessor */

/**
 * Preprocessor
 *
 */
var Preprocessor = (function () {

  var constants = K6['constants'];

  /**
   * Just quickly build an object to lookup for properties
   * by name
   * @param  {Array} array
   * @return {Object}       An object with the array values as keys and empty values
   */
  function _getLookupObjFromArray (array) {
    var obj = {};
    for (var i = array.length - 1; i >= 0; i--) {
      obj[ array[i] ] = true;
    }
    return obj;
  }

  // @public API
  return {
    // size related
    varsSize: constants['VARS_NAMES_SIZE'],
    varsSizeLookup: _getLookupObjFromArray(constants['VARS_NAMES_SIZE']),
    functionsSize: constants['PREPROCESSOR_MATH_FUNCTIONS'],
    // color related
    varsColor: constants['VARS_NAMES_COLOR'],
    varsColorLookup: _getLookupObjFromArray(constants['VARS_NAMES_COLOR']),
    functionsColor: constants['PREPROCESSOR_COLOR_SIMPLE_FUNCTIONS']
  };
})();