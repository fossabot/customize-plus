import $ from 'jquery';
import _ from 'underscore';
import { api, wpApi } from '../core/globals';
import ControlBaseChoices from './base-choices';
// import ControlBase from './base';
import Validate from '../core/validate';
import Sanitize from '../core/sanitize';

/**
 * Control Select class
 *
 * @class wp.customize.controlConstructor.kkcp_select
 * @alias api.controls.Select
 * @constructor
 * @extends api.controls.Base
 * @augments api.controls.BaseChoices
 * @augments wp.customize.Control
 * @augments wp.customize.Class
 */
let Control = ControlBaseChoices.extend({
  /**
   * @override
   */
  validate: function (value) {
    return Validate.oneOrMoreChoices([], value, this.setting, this);
  },
  /**
   * @override
   */
  sanitize: function (value) {
    return Sanitize.oneOrMoreChoices(value);
  },
  /**
   * Destroy `selectize` instance if any.
   *
   * @override
   */
  onDeflate: function () {
    if (this.__select && this.__select.selectize) {
      this.__select.selectize.destroy();
    }
  },
  /**
   * We do a comparison with two equals `==` because sometimes we want to
   * compare `500` to `'500'` (like in the font-weight dropdown) and return
   * true from that. // @@todo the before comment... \\
   *
   * @override
   */
  syncUI: function (value) {
    if (!_.isEqual(value, this._getValueFromUI())) {
      this._updateUI(value);
    }
  },
  /**
   * @override
   */
  ready: function () {
    const selectizeOpts = this.params.selectize || false;
    const setting = this.setting;

    this.__select = this._container.getElementsByTagName('select')[0];
    this.__options = this._container.getElementsByTagName('option');

    // use selectize
    if (selectizeOpts) {
      $(this.__select).selectize(_.extend({
        maxItems: this.params.max,
        onChange: (value) => {
          setting.set(value);
        }
      }, selectizeOpts));
    // or use normal DOM API
    } else {
      this.__select.onchange = function () {
        setting.set(this.value);
      };
    }

    // sync selected state on options on ready
    this._updateUI(this.setting());
  },
  /**
   * Get value from UI
   *
   * @return {?array}
   */
  _getValueFromUI () {
    if (!this.__select) {
      return null;
    }
    const selectize = this.__select.selectize;
    if (selectize) {
      return selectize.getValue();
    }
    return this.__select.value;
  },
  /**
   * Update UI syncing options values
   *
   * Pass `true` as second argument to perform a `silent` update, that does
   * not trigger the `onChange` event of `selectize`.
   *
   * @param {string|array} value
   */
  _updateUI: function (value) {
    // use selectize
    if (this.params.selectize) {
      this.__select.selectize.setValue(value, true);
    }
    // or use normal DOM API
    else {
      for (let i = this.__options.length; i--;) {
        let option = this.__options[i];
        option.selected = (value == option.value);
      }
    }
  }
});

export default wpApi.controlConstructor['kkcp_select'] = api.controls.Select = Control;
