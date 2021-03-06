<?php defined( 'ABSPATH' ) or die;

/**
 * Customize custom classes for panels, sections, control and settings.
 *
 * All custom classes are collected in this file by an automated task that
 * inlines the `require` php statements.
 *
 * @package    Customize_Plus
 * @subpackage Customize
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */

/**
 * @global $wp_customize {WP_Customize_Manager} WordPress Customizer instance
 */
global $wp_customize;


/**
 * Customize Plus Setting base class
 *
 * {@inheritDoc}
 *
 * @since 1.0.0
 * @override
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Setting_Base extends WP_Customize_Setting {

	/**
	 * Setting type just for JavaScript, to instantiate the right constructor
	 *
	 * @since 1.0.0
	 * @see  $this->json
	 * @var string
	 */
	protected $js_type = 'kkcp_base';

	/**
	 * {@inheritDoc}. Change default to `postMessage` for Customize Plus settings.
	 *
	 * @since 1.0.0
	 */
	public $transport = 'postMessage';

	/**
	 * Sanitize the setting's default factory value for use in JavaScript.
	 *
	 * @see  PHP WP_Customize_Setting->js_value
	 * @since 1.0.0
	 *
	 * @return mixed The requested escaped value.
	 */
	public function js_value_default() {
		if ( is_string( $this->default ) )
			return html_entity_decode( $this->default, ENT_QUOTES, 'UTF-8');

		return $this->default;
	}

	/**
	 * {@inheritDoc}. Change type in order to use our custom JavaScript
	 * constructor without changing the `type` property, which should remain
	 * either `theme_mod` or `option` as defined in the customize tree, default to
	 * `theme_mod`. Settings are initialized in `customize-controls.js#7836`.
	 * Finally add the factory value of the setting (its default as defined by the
	 * theme developer).
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function json() {
		return array(
			'value'     => $this->js_value(),
			'transport' => $this->transport,
			'dirty'     => $this->dirty,
			'type'      => $this->js_type, // $this->type,
			'default'  => $this->js_value_default(),
		);
	}

	// interesting methods to maybe override:
	// public function sanitize( $value ) {}
	// public function validate( $value ) {}
	// public function js_value() {}
}


/**
 * Customize Plus Setting Font Family class
 *
 * {@inheritDoc}
 *
 * @since 1.0.0
 * @override
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Setting_Font_Family extends KKcp_Customize_Setting_Base {

	/**
	 * We do not override this because we do not need a special JavaScript
	 * constructor for this setting.
	 *
	 * @since 1.0.0
	 * @inheritDoc
	 */
	// protected $js_type = 'kkcp_font_family';

	/**
	 * {@inheritDoc}. Always normalize the font value
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function js_value() {
		$value = parent::js_value();

		return KKcp_Helper::normalize_font_families( $value );
	}

	/**
	 * {@inheritDoc}. Always normalize the font value
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function js_value_default() {
		$value = parent::js_value_default();

		return KKcp_Helper::normalize_font_families( $value );
	}

}


/**
 * Customize Plus Control base class
 *
 * {@inheritDoc}
 *
 * @since 1.0.0
 * @override
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Base extends WP_Customize_Control {

	/**
	 * Optional value
	 *
	 * Whether this control setting value is optional, in other words, when its
	 * setting is allowed to be empty.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $optional = false;

	/**
	 * Loose (allows human error to be previewed)
	 *
	 * Whether this control allows the user to set and preview an invalid value.
	 * The value will not be saved anyway. In fact when this option (applyable
	 * on all controls) is set to `true` the user will both see the notification
	 * error sent from JavaScript in the frontend AND the one from the backend.
	 * Since notifications are the same they will not be duplicated.
	 * When this option is set to `false` the user will not be able to preview
	 * an invalid value in the customizer preview, and the last valid value will
	 * always be the one both previewed and sent to the server.
	 *
	 * @see JS kkcp.controls.Base->_validate
	 * @see JS kkcp.controls.Base->sanitize
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $loose = false;

	/**
	 * Enable live validation of control default setting's value
	 *
	 * For readibility this property is 'positively' put. But in the JSON `params`
	 * it is reversed in its negative form, so that the `control.params` object
	 * get another property only in the less frequent case and prints a slightly
	 * smaller JSON. In `to_json` this becomes `noLiveValidation`.
	 *
	 * @see JS kkcp.controls.Base->initialize
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $live_validation = true;

	/**
	 * Enable live sanitization of control default setting's value
	 *
	 * For readibility this property is 'positively' put. But in the JSON `params`
	 * it is reversed in its negative form, so that the `control.params` object
	 * get another property only in the less frequent case and prints a slightly
	 * smaller JSON. In `to_json` this becomes `noLiveSanitization`.
	 *
	 * @see JS kkcp.controls.Base->initialize
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $live_sanitization = true;

	/**
	 * Info
	 *
	 * The control info data, optional. It displays the given content in a popover.
	 *
	 * @premium A Customize Plus Premium feature.
	 * @since 1.0.0
	 * @var array
	 */
	public $info;

	/**
	 * Advanced
	 *
	 * Whether this control is advanced or normal, users and developers will be
	 * able to hide or show the advanced controls.
	 *
	 * @premium A Customize Plus Premium feature.
	 * @since 1.0.0
	 * @var bool
	 */
	public $advanced = false;

	/**
	 * Whether this control is searchable by the Search tool.
	 *
	 * For readibility this property is 'positively' put. But in the JSON `params`
	 * it is reversed in its negative form, so that the `control.params` object
	 * get another property only in the less frequent case and prints a slightly
	 * smaller JSON. In `to_json` this becomes `unsearchable`.
	 *
	 * @premium A Customize Plus Premium feature.
	 * @since 1.0.0
	 * @var bool
	 */
	public $searchable = true;

	/**
	 * Allowed input attributes
	 *
	 * Whitelist the input attrbutes that can be set on this type of control.
	 * Subclasses can override this with a custom array whose sanitize methods
	 * must be class methods of `KKcp_SanitizeJS` or global functions.
	 *
	 * For a list of valid HTML attributes
	 * @see  https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input
	 *
	 * @abstract
	 * @since 1.0.0
	 * @var array
	 */
	protected $allowed_input_attrs = array();

	/**
	 * {@inhertDoc}. Filter only the valid `input_attrs` values
	 *
	 * @since 1.0.0
	 */
	public function __construct( $manager, $id, $args = array() ) {

		if ( isset( $args['input_attrs'] ) && ! empty( $args['input_attrs'] ) && ! empty( $this->allowed_input_attrs ) ) {
			$args['input_attrs'] = KKcp_SanitizeJS::options( $args['input_attrs'], $this->allowed_input_attrs );
		}

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * {inheritDoc}
	 *
	 * Change parent method adding more default data shared by all the controls
	 * (add them only if needed to save bytes on the huge `_wpCustomizeSettings`
	 * JSON on load). In the end call an abstract method to add stuff here from
	 * subclasses.
	 *
	 * @since 1.0.0
	 * @ovrride
	 */
	public function to_json() {
		parent::to_json();

		if ( $this->optional ) {
			$this->json['optional'] = true;
		}
		if ( $this->loose ) {
			$this->json['loose'] = true;
		}
		if ( ! $this->live_validation ) {
			$this->json['noLiveValidation'] = true;
		}
		if ( ! $this->live_sanitization ) {
			$this->json['noLiveSanitization'] = true;
		}

		// input attrs is widely used in all Customize Plus controls
		if ( ! empty( $this->input_attrs ) ) {
			$this->json['attrs'] = $this->input_attrs;
		}

		// remove description if not specified
		if ( ! $this->description ) {
			unset( $this->json['description'] );
		}

		// remove content, we rely completely on js, and declare the control
		// container in the js control base class
		unset( $this->json['content'] );

		// @premium A Customize Plus Premium features.
		if ( class_exists( 'KKcpp' ) ) {
			// add info data if any
			if ( $this->info ) {
				$this->json['info'] = $this->info;
			}

			// add advanced data if specified
			if ( $this->advanced ) {
				$this->json['advanced'] = $this->advanced;
			}

			// add unsearchable flag if specified
			if ( ! $this->searchable ) {
				$this->json['unsearchable'] = true;
			}
		}

		// call a function to add data to `control.params`
		$this->add_to_json();
	}

	/**
	 * Add parameters passed to the JavaScript via JSON. This free us to override
	 * the `to_json` method calling everytime the parent method.
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function add_to_json() {}

	/**
	 * Never render any content for controls from PHP
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function render() {}

	/**
	 * Never render any inner content for controls from PHP
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function render_content() {}

	/**
	 * Templates are entirely managed in JavaScript Control classes
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function content_template() {}

	/**
	 * Sanitization callback
	 *
	 * All control's specific sanitizations pass from this function which
	 * always check if the value satisfy the `optional` attribute and then
	 * delegates additional and specific sanitization to the class that
	 * inherits from this, which need to override the static method `sanitize`.
	 * The control instance is always passed to that method in addition to the
	 * value and the setting instance.
	 *
	 * @since 1.0.0
	 * @param string               $value   The value to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string The sanitized value.
	 */
	public static function sanitize_callback( $value, $setting ) {
		$control = $setting->manager->get_control( $setting->id );

		return $control::sanitize( $value, $setting, $control );
	}

	/**
	 * Sanitize
	 *
	 * Class specific sanitization, method to override in subclasses.
	 *
	 * @since 1.0.0
	 * @see  JS `kkcp.controls.Base.sanitize`
	 *
	 * @abstract
	 * @param string               $value   The value to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @param WP_Customize_Control $control Control instance.
	 * @return string The sanitized value.
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return esc_html( $value );
		// return wp_kses_post( $value );
	}

	/**
	 * Validate callback
	 *
	 * All control's specific validation pass from this function which
	 * always check if the value satisfy the `optional` attribute and then
	 * delegates additional and specific validation to the class that
	 * inherits from this, which needs to override the static method `validate`.
	 * The control instance is always passed to that method in addition to the
	 * value and the setting instance.
	 *
	 * @see http://bit.ly/2kzgHlm
	 *
	 * @since 1.0.0
	 * @see  JS `kkcp.controls.Base._validate`
	 *
	 * @param WP_Error 						 $validity
	 * @param mixed 							 $value    The value to validate.
	 * @param WP_Customize_Setting $setting  Setting instance.
	 * @return mixed
	 */
	public static function validate_callback( $validity, $value, $setting ) {
		$control = $setting->manager->get_control( $setting->id );

		// immediately check a required value validity
		$validity = KKcp_Validate::required( $validity, $value, $setting, $control );

		// if a required value is not supplied only perform one validation routine
		if ( ! empty( $validity->get_error_messages() ) ) {
			return $validity;
		}

		// otherwise apply the specific control/setting validation
		return $control::validate( $validity, $value, $setting, $control );
	}

	/**
	 * Validate
	 *
	 * Class specific validation, method to override in subclasses.
	 *
	 * @since 1.0.0
	 * @see  JS `kkcp.controls.Base.validate`
	 *
	 * @abstract
	 * @param WP_Error 						 $validity
	 * @param mixed 							 $value    The value to validate.
	 * @param WP_Customize_Setting $setting  Setting instance.
	 * @param WP_Customize_Control $control  Control instance.
	 * @return mixed
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return $validity;
	}

	/**
	 * Get localized strings for current controls.
	 *
	 * Allows control classes to add localized strings accessible on our main
	 * `js` object `kkcp.l10n`.
	 *
	 * @since  1.0.0
	 * @abstract
	 * @return array
	 */
	public function get_l10n() {
		return array();
	}

	/**
	 * Get localize string for current control
	 *
	 * Allows control classes to get a localized string by its key value. This is
	 * useful during validation to define the validation messages only once both
	 * for JavaScript and PHP validation.
	 *
	 * @see  JS `kkcp.controls.Base.l10n`
	 * @since  1.0.0
	 * @return string
	 */
	public function l10n( $key ) {
		$strings = $this->get_l10n();
		if ( isset( $strings[ $key ] ) ) {
			return $strings[ $key ];
		}
		if ( class_exists( 'KKcp_Customize' ) ) {
			$strings = KKcp_Customize::get_js_l10n();
			if ( isset( $strings[ $key ] ) ) {
				return $strings[ $key ];
			}
		}
		return '';
	}

	/**
	 * Get js constants for current controls.
	 *
	 * Allows control classes to add its specific constants variables on our
	 * main `js` object `kkcp.l10n`.
	 *
	 * @since  1.0.0
	 * @abstract
	 * @return array
	 */
	public function get_constants() {
		return array();
	}

	/**
	 * Add error
	 *
	 * Shortcut to manage the $validity object during validation
	 *
	 * @see  JS kkcp.controls.Base._addError
	 * @since  1.0.0
	 * @param WP_Error					$validity
	 * @param string						$msg_id
	 * @param mixed|array|null 	$msg_arguments
	 * @return WP_Error
	 */
	public function add_error( $validity, $msg_id, $msg_arguments ) {
		$msg = $this->l10n( $msg_id );

		// if there is an array of message arguments
		if ( is_array( $msg_arguments ) ) {
			$validity->add( $msg_id, vsprintf( $msg, $msg_arguments ) );
		}
		// if there is just one message argument
		else if ( ! empty( $msg_arguments ) ) {
			$validity->add( $msg_id, sprintf( $msg, $msg_arguments ) );
		}
		// if it is a simple string message leave it as it is
		else {
			$validity->add( $msg_id, $msg );
		}
		return $validity;
	}
}

/**
 * Base Choices Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
abstract class KKcp_Customize_Control_Base_Choices extends KKcp_Customize_Control_Base {

	/**
	 * Option to force a maximum choices selection boundary
	 *
	 * @since 1.0.0
	 * @abstract
	 * @var ?int
	 */
	public $max = 1;

	/**
	 * Option to force a minimum choices selection boundary
	 *
	 * @since 1.0.0
	 * @abstract
	 * @var ?int This should be `null` or a value higher or lower than 1. To set
	 *           a control as optional use the `$optional` class property instead
	 *           of setting `$min` to `1`.
	 */
	public $min = null;

	/**
	 * Subclasses needs to override this with a custom default array.
	 *
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 * @override
	 * @abstract
	 * @var array
	 */
	public $choices = array();

	/**
	 * This is then populated in the construct based on the choices array and
	 * later used for validation.
	 *
	 * @since 1.0.0
	 * @abstract
	 * @var null|array
	 */
	public $valid_choices = null;

	/**
	 * Get valid choices values from choices array. Subclasses can use this
	 * in their constructor. Let' not override the constructor here too but
	 * force subclasses to override it in case they need it.
	 *
	 * @since 1.0.0
	 * @abstract
	 * @param array              $choices
	 * @return array
	 */
	protected function get_valid_choices ( $choices ) {
		if ( is_array( $choices ) ) {
			$choices_values = array();

			foreach ( $choices as $choice_key => $choice_value ) {
				array_push( $choices_values, $choice_key );
			}
			return $choices_values;
		}
		return $choices;
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'vNotArray' => esc_html__( 'It must be a list of values', 'kkcp' ),
			'vNotAChoice' => esc_html__( '**%s** is not an allowed choice', 'kkcp' ),
			'vNotExactLengthArray' => esc_html__( 'It must contain exactly **%s** values', 'kkcp' ),
			'vNotMinLengthArray' => esc_html__( 'It must contain minimum **%s** values', 'kkcp' ),
			'vNotMaxLengthArray' => esc_html__( 'It must contain maximum **%s** values', 'kkcp' ),
		);
	}

	/**
	 * JavaScript parameters nedeed by choice based controls.
	 *
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function add_to_json() {
		$this->json['id'] = KKcp_SanitizeJS::string( true, $this->id );
		$this->json['max'] = KKcp_SanitizeJS::int_or_null( false, $this->max );
		$this->json['min'] = KKcp_SanitizeJS::int_or_null( false, $this->min );
		$this->json['choices'] = $this->choices;
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::one_or_more_choices( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::one_or_more_choices( $validity, $value, $setting, $control );
	}
}

/**
 * Base Radio Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
abstract class KKcp_Customize_Control_Base_Radio extends KKcp_Customize_Control_Base_Choices {

	/**
	 * {@inhertDoc}. Populate the `valid_choices` property.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->valid_choices = $this->get_valid_choices( $this->choices );
	}

	/**
	 * Override the base choices, we don't need `min`, `max` for radio based
	 * controls, but we extends it anyway to reuse its js template.
	 *
	 * {@inheritDoc}
	 *
	 * @since 1.0.0
	 * @override
	 */
	protected function add_to_json() {
		$this->json['id'] = KKcp_SanitizeJS::string( true, $this->id );
		$this->json['choices'] = $this->choices;
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::single_choice( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::single_choice( $validity, $value, $setting, $control );
	}
}

/**
 * Base Set Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
abstract class KKcp_Customize_Control_Base_Set extends KKcp_Customize_Control_Base_Choices {

	/**
	 * Subclasses needs to override this with a custom array
	 *
	 * @since 1.0.0
	 * @abstract
	 * @var array
	 */
	protected $supported_sets = array();

	/**
	 * Subclasses needs to override this with a custom string
	 *
	 * @since 1.0.0
	 * @abstract
	 * @var string
	 */
	protected $set_js_var = 'mySet';

	/**
	 * {@inheritDoc}. Override it here in order to manually populate the
	 * `valid_choices` property from the 'globally' defined sets filtered with
	 * the given `choices` param.
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$filtered_sets = $this->get_filtered_sets( $this->choices );
		$this->valid_choices = $this->get_valid_choices( $filtered_sets );
	}

	/**
	 * Get set by the given name, subclasses must override this with their data
	 *
	 * e.g.:
	 * ```
	 * if ( $name === 'standard' ) {
	 *     return array();
	 * }
	 * ```
	 *
	 * @abstract
	 * @since  1.0.0
	 * @return array
	 */
	protected function get_set ( $name ) {
		return [];
	}

	/**
	 * Get flatten set values
	 *
	 * @since  1.0.0
	 * @param  array $set
	 * @return array
	 */
	public static function get_flatten_set_values ( $set ) {
		$values = array();

		foreach ( $set as $set_group_values ) {
			if ( isset( $set_group_values['values'] ) && is_array( $set_group_values['values'] ) ) {
				foreach ( $set_group_values['values'] as $value ) {
					array_push( $values, $value );
				}
			} else {
				wp_die( 'cp_api', sprintf( esc_html__( 'Customize Plus | API error: set %s must follow the `set` strucure with values in `values` array.', 'kkcp' ), $set ) );
			}
		}

		return $values;
	}

	/**
	 * Get all filtered sets
	 *
	 * @since 1.0.0
	 * @param array   $choices
	 * @return array
	 */
	private function get_filtered_sets ( $choices ) {
		$filtered_sets = [];

		foreach ( $this->supported_sets as $set_name ) {
			$filtered_sets[ $set_name ] = $this->get_filtered_set( $set_name, $choices );
		}

		return $filtered_sets;
	}

	/**
	 * Get one filtered set
	 *
	 * @since 1.0.0
	 * @param string  $name
	 * @param array   $filter
	 * @return array
	 */
	private function get_filtered_set ( $name, $filter ) {
		$set = $this->get_set( $name );
		$filtered_set = [];

		// choices filter is a single set name
		if ( is_string( $filter ) && $name === $filter ) {
			$filtered_set = $set;
		}
		// choices filter is an array of set names
		else if ( is_array( $filter ) && in_array( $name, $filter ) ) {
			$filtered_set = $set;
		}
		// choices filter is a more complex filter that filters per set
		else if ( is_array( $filter ) ) {
			foreach ( $filter as $filter_group_key => $filter_groups ) {
				# code...
				// whitelist based on a filter string
				if ( is_string( $filter_groups ) ) {
					// whitelist simply a group by its name
					if ( isset( $set[ $filter_groups ] ) ) {
						$filtered_set[ $filter_groups ] = $set[ $filter_groups ];
					} else {
						// whitelist with a quickChoices filter, which filter by values
						// on all the set groups regardless of the set group names.
						$quick_choices = explode( ',', $filter_groups );
						$filtered_set = array_intersect( self::get_flatten_set_values( $set ), $quick_choices );
						// we can break here, indeed, this is a quick filter...
						break;
					}
				}
				// whitelist specific values per each group of the set
				else if ( KKcp_Helper::is_assoc( $filter_groups ) ) {
					foreach ( $filter_groups as $filter_group_key => $filter_group_values ) {
						$filtered_set[ $filter_group_key ] = array_intersect($set[ $filter_group_key ]['values'], $filter_group_values );
					}
				}
				// whitelist multiple groups of a set
				else if ( is_array( $filter_groups ) ) {
					foreach ( $filter_groups as $filter_group_key ) {
						$filtered_set[ $filter_group_key ] = $set[ $filter_group_key ];
					}
				}
			}
		}
		// choices filter is not present, just use all the set
		else {
			$filtered_set = $set;
		}

		return $filtered_set;
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function get_valid_choices ( $filtered_sets ) {
		$valid_choices = array();

		foreach ( $filtered_sets as $set_values ) {

			// set can be a multidimensional array divided by groups
			if ( KKcp_Helper::is_assoc( $set_values ) ) {
				foreach ( $set_values as $group_values ) {
					if ( isset( $group_values['values'] ) && is_array( $group_values['values'] ) ) {
						$valid_choices = array_merge( $valid_choices, $group_values['values'] );
					}
				}
			}
			// set can be a flat array ... (e.g. when is filtered by a quickChoices)
			else if ( is_array( $set_values ) ) {
				$valid_choices = array_merge( $valid_choices, $set_values );
			}
		}

		return $valid_choices;
	}

	/**
	 * {@inheritDoc}. Set set arrays as constants to use in JavaScript
	 *
	 * @since  1.0.0
	 * @override
	 */
	public function get_constants() {
		$sets = array();

		foreach ( $this->supported_sets as $set ) {
			$sets[ $set ] = $this->get_set( $set );
		}

		return array(
			$this->set_js_var => $sets
		);
	}

	/**
	 * @override
	 * @since 1.0.0
	 */
	protected function add_to_json() {
		parent::add_to_json();

		$this->json['setVar'] = KKcp_SanitizeJS::string( true, $this->set_js_var );
		$this->json['supportedSets'] = KKcp_SanitizeJS::array( true, $this->supported_sets );
	}
}


/**
 * Buttonset Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Buttonset extends KKcp_Customize_Control_Base_Radio {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_buttonset';
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Buttonset' );

/**
 * Checkbox Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Checkbox extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_checkbox';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'label' => array( 'sanitizer' => 'string' ),
	);

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'vCheckbox' => esc_html__( 'It must be either checked or unchecked', 'kkcp' ),
		);
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::checkbox( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::checkbox( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Checkbox' );

/**
 * Color Control custom class
 *
 * The color control uses Spectrum as a Javascript Plugin which offers more
 * features comparing to Iris, the default one used by WordPress.
 * We abstract the Spectrum options that developers are allowed to define
 * setting them as class properties which are then put in the JSON params of the
 * control object, ready to be used in the JavaScript implementation and in the
 * backend side validation and sanitization.
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Color extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_color';

	/**
	 * Allow alpha channel modification (rgba colors)
	 *
	 * - When `true` RGBA colors will be allowed and a slider for the alpha
	 *   channel will be displayed
	 * - When `false` RGBA colors will be not allowed (if given the alpha channel
	 *   value will be stripped out on save). The color picker will not display
	 *   any control for the alpha channel.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $alpha = true;

	/**
	 * Allow transparent colors
	 *
	 * - When `true` the css color value `transparent` is allowed.
	 * - When `false` the css color value `transparent` is not allowed to be
	 *   selected.
	 *
	 * @since 1.0.0
	 * @var bool
	 */
	public $transparent = true;

	/**
	 * Show palette only in color control
	 *
	 * - When `true` the picker will be shown
	 * - When `false` the picker will not be shown. In this case a palette array
	 *   must be defined otherwise the color controll will be useless.
	 * - When `'hidden'` the picker will be toggled, the user will need to click
	 *   a 'Show color picker' toggle button. Its use is therefore allowed but
	 *   discouraged
	 *
	 * In regards to spectrum this is an abstraction of the following options:
	 * {@link(https://bgrins.github.io/spectrum/#options-selectionPalette, docs)},
	 * {@link(https://bgrins.github.io/spectrum/#options-showPaletteOnly, docs)}
	 *
	 * @since 1.0.0
	 * @var bool|string
	 */
	public $picker = true;

	/**
	 * Palette
	 *
	 * - When `false` no palette selection is shown
	 * - When is an `array`, it needs to be an array of arrays where each array
	 *   is a row of color choices in the UI. If the `picker` property is set to
	 *   `false` only the colors defined in the palette are allowed to be picked
	 *   and will pass validation. If `picker` is set to `true` the palette will
	 *   not constrain the user choice turning the palette into a sort of design
	 *   suggestion.
	 *
	 * In regards to spectrum this is an abstraction of the following options:
	 * {@link(https://bgrins.github.io/spectrum/#options-selectionPalette, docs)},
	 * {@link(https://bgrins.github.io/spectrum/#options-showPaletteOnly, docs)}
	 *
	 * @since 1.0.0
	 * @var bool|array
	 */
	public $palette = false;

	/**
	 * Color format supported
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $color_formats_supported = array(
		'hex',
		'rgb',
		'rgba',
		'keyword',
		// 'hsl',
		// 'hsla',
	);

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_constants() {
		return array(
			'colorsKeywords' => KKcp_Data::COLORS_KEYWORDS,
			'colorFormatsSupported' => self::$color_formats_supported
		);
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'cancelText' => esc_html__( 'Cancel', 'kkcp' ),
			'chooseText' => esc_html__( 'Choose', 'kkcp' ),
			'clearText' => esc_html__( 'Clear selection', 'kkcp' ),
			'selectColor' => esc_html__( 'Select color', 'kkcp' ),
			'noColorSelectedText' => esc_html__( 'No color selected', 'kkcp' ),
			'togglePaletteMoreText' => esc_html__( 'Show color picker', 'kkcp' ),
			'togglePaletteLessText' => esc_html__( 'Hide color picker', 'kkcp' ),
			'vColorWrongType' => esc_html__( 'Colors must be strings', 'kkcp' ),
			'vNotInPalette' => esc_html__( 'Color not in the allowed palette', 'kkcp' ),
			'vNoTransparent' => esc_html__( 'Transparent is not allowed', 'kkcp' ),
			'vNoRGBA' => esc_html__( 'RGBA color is not allowed', 'kkcp' ),
			'vColorInvalid' => esc_html__( 'Not a valid color', 'kkcp' ),
		);
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		if ( $this->alpha ) {
			$this->json['alpha'] = true;
		}
		if ( $this->transparent ) {
			$this->json['transparent'] = true;
		}
		if ( $this->picker ) {
			$this->json['picker'] = $this->picker;
		}
		if ( $this->palette ) {
			$this->json['palette'] = KKcp_SanitizeJS::array( false, $this->palette );
		}

		$this->json['mode'] = 'custom';
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::color( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::color( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Color' );

/**
 * Content Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Content extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_content';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $advanced = false;

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $searchable = false;

	/**
	 * Markdown
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $markdown = '';

	/**
	 * Alert
	 *
	 * - When false no particular style will be applied
	 * - When a string, it can be one of 'info|success|warning|danger|light',
	 *   according style will be applied.
	 *
	 * @see  self::$allowed_alerts
	 * @since 1.0.0
	 * @var string
	 */
	public $alert = false;

	/**
	 * Allowed alerts values
	 *
	 * @since 1.0.0
	 * @var array
	 */
	private static $allowed_alerts = array(
		'info', 'success', 'warning', 'danger', 'light'
	);

	/**
	 * {@inheritDoc}. Override it here in order to check the given alert value.
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function __construct( $manager, $id, $args = array() ) {
		if ( isset( $args['alerts'] ) && is_string( $args['alerts'] ) ) {
			$args['alerts'] = KKcp_SanitizeJS::enum( false, $args['alerts'], self::$allowed_alerts );
		}

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		if ( $this->markdown ) {
			$this->json['markdown'] = $this->markdown;
		}
		if ( $this->alert ) {
			$this->json['alert'] = $this->alert;
		}
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Content' );

/**
 * Font Family Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Font_Family extends KKcp_Customize_Control_Base_Set {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_font_family';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $max = 10;

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $choices = array( 'standard' );

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $supported_sets = array(
		'standard',
	);

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $set_js_var = 'fontFamiliesSets';

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	protected function get_set ( $name ) {
		if ( $name === 'standard' ) {
			return KKcp_Data::get_font_families_standard();
		}

		return [];
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function get_valid_choices ( $filtered_sets ) {
		$valid_choices = parent::get_valid_choices( $filtered_sets );

		foreach ( $valid_choices as $valid_choice ) {
			array_push( $valid_choices, KKcp_Helper::normalize_font_family( $valid_choice ) );
		}

		return $valid_choices;
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::font_family( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::font_family( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Font_Family' );

/**
 * Icon Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Icon extends KKcp_Customize_Control_Base_Set {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_icon';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $choices = array( 'dashicons' );

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $supported_sets = array(
		'dashicons'
	);

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $set_js_var = 'iconSets';

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	protected function get_set ( $name ) {
		if ( $name === 'dashicons' ) {
			return KKcp_Data::get_dashicons();
		}

		return [];
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'iconSearchPlaceholder' => esc_html__( 'Search icon by name...', 'kkcp' ),
		);
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Icon' );

/**
 * Multicheck Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Multicheck extends KKcp_Customize_Control_Base_Choices {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_multicheck';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $max = null;

	/**
	 * Sortable
	 *
	 * @since 1.0.0
	 * @var boolean
	 */
	public $sortable = false;

	/**
	 * {@inheritDoc}. Set `max` dynamically to the number of choices if the
	 * developer has not already set it explicitly and populate valida choices.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $manager, $id, $args = array() ) {

		if ( isset( $args['choices'] ) && is_array( $args['choices'] ) && ! isset( $args['max'] ) ) {
			$args['max'] = count( $args['choices'] );
		}

		parent::__construct( $manager, $id, $args );

		$this->valid_choices = $this->get_valid_choices( $this->choices );

	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function enqueue() {
		if ( $this->sortable ) {
			wp_enqueue_script( 'jquery-ui-sortable' );
		}
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		parent::add_to_json();

		$this->json['sortable'] = KKcp_SanitizeJS::bool( false, $this->sortable );
		$this->json['choicesOrdered'] = $this->value();
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::multiple_choices( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::multiple_choices( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Multicheck' );

/**
 * Number Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Number extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_number';

	/**
	 * @since 1.0.0
	 * @ineritDoc
	 */
	protected $allowed_input_attrs = array(
		'tooltip' => array( 'sanitizer' => 'string' ),
		'float' => array( 'sanitizer' => 'bool' ),
		'min' => array( 'sanitizer' => 'number' ),
		'max' => array( 'sanitizer' => 'number' ),
		'step' => array( 'sanitizer' => 'number' ),
		// 'pattern' => array( 'sanitizer' => 'string' ),
	);

	/**
	 * @since  1.0.0
	 * @inerhitDoc
	 */
	public function get_l10n() {
		return array(
			'vNotAnumber' => esc_html__( 'It must be a number', 'kkcp' ),
			'vNoFloat' => esc_html__( 'It must be an integer, not a float', 'kkcp' ),
			'vNotAnInteger' => esc_html__( 'It must be an integer number', 'kkcp' ),
			'vNumberStep' => esc_html__( 'It must be a multiple of **%s**', 'kkcp' ),
			'vNumberLow' => esc_html__( 'It must be higher than **%s**', 'kkcp' ),
			'vNumberHigh' => esc_html__( 'It must be lower than **%s**', 'kkcp' ),
		);
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::number( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::number( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Number' );

/**
 * Radio Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Radio extends KKcp_Customize_Control_Base_Radio {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_radio';
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Radio' );

/**
 * Radio Image Control custom class
 *
 * The images name needs to be named like following: '{setting-id}-{choice-value}.png'
 * and need to be in the $IMG_ADMIN path.
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Radio_Image extends KKcp_Customize_Control_Base_Radio {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_radio_image';
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Radio_Image' );

/**
 * Select Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Select extends KKcp_Customize_Control_Base_Choices {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_select';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'native' => array( 'sanitizer' => 'bool' ),
		'hide_selected' => array( 'sanitizer' => 'bool' ),
		'sort' => array( 'sanitizer' => 'bool' ),
		'persist' => array( 'sanitizer' => 'bool' ),
		'removable' => array( 'sanitizer' => 'bool' ),
		'draggable' => array( 'sanitizer' => 'bool' ),
		'restore_on_backspace' => array( 'sanitizer' => 'bool' ),
	);

	/**
	 * {@inhertDoc}. Populate the `valid_choices` property.
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		$this->valid_choices = $this->get_valid_choices( $this->choices );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Select' );

/**
 * Select Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Font_Weight extends KKcp_Customize_Control_Select {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_font_weight';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $choices = array(
		'normal' => array(
			'label' => 'Normal',
			'tooltip' => 'Defines a normal text. This is default',
		),
		'bold' => array(
			'label' => 'Bold',
			'tooltip' => 'Defines thick characters',
		),
		'bolder' => array(
			'label' => 'Bolder',
			'tooltip' => 'Defines thicker characters',
		),
		'lighter' => array(
			'label' => 'Lighter',
			'tooltip' => 'Defines lighter characters',
		),
		'100' => '100',
		'200' => '200',
		'300' => '300',
		'400' => '400 (Same as normal)',
		'500' => '500',
		'600' => '600',
		'700' => '700 (Same as bold)',
		'800' => '800',
		'900' => '900',
		'initial' => array(
			'label' => 'Initial',
			'tooltip' => 'Sets this property to its default value',
		),
		'inherit' => array(
			'label' => 'Inherit',
			'tooltip' => 'Inherits this property from its parent element',
		),
	);
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Font_Weight' );

/**
 * Slider Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Slider extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_slider';

	/**
	 * @since 1.0.0
	 * @ineritDoc
	 */
	protected $allowed_input_attrs = array(
		'float' => array( 'sanitizer' => 'bool' ),
		'min' => array( 'sanitizer' => 'number' ),
		'max' => array( 'sanitizer' => 'number' ),
		'step' => array( 'sanitizer' => 'number' ),
	);

	/**
	 * Units
	 *
	 * @since  1.0.0
	 * @var array
	 */
	public $units = array( 'px' );

	/**
	 * Allowed units
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected static $allowed_units = KKcp_Data::CSS_UNITS;

	/**
	 * {@inheritDoc}. Override it here in order to implicitly allow float numbers
	 * if input_attrs['step'] is a float number. Check also that the given units
	 * are supported and always transform is value in an array.
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function __construct( $manager, $id, $args = array() ) {
		if ( isset( $args['input_attrs'] ) && isset( $args['input_attrs']['step'] ) && is_float( $args['input_attrs']['step'] ) ) {
			$args['input_attrs']['float'] = true;
		}

		if ( isset( $args['units'] ) && ! empty( $args['units'] ) ) {
			$args['units'] = KKcp_SanitizeJS::item_in_array( true, $args['units'], self::$allowed_units );
			if ( is_string( $args['units'] ) ) {
				$args['units'] = array( $args['units'] );
			}
		}

		parent::__construct( $manager, $id, $args );
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'vSliderMissingUnit' => esc_html__( 'A CSS unit must be specified', 'kkcp' ),
			'vSliderUnitNotAllowed' => esc_html__( 'CSS unit **%s** is not allowed here', 'kkcp' ),
			'vSliderNoUnit' => esc_html__( 'It does not accept a CSS unit', 'kkcp' ),
		);
	}

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-slider' );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		if ( ! empty( $this->units ) ) {
			$this->json['units'] = $this->units;
		}
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::slider( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::slider( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Slider' );

/**
 * Sortable Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Sortable extends KKcp_Customize_Control_Base_Choices {

	/**
	 * @override
	 * @since 1.0.0
	 */
	public $type = 'kkcp_sortable';

	/**
	 * @override
	 * @since  1.0.0
	 */
	public function enqueue() {
		wp_enqueue_script( 'jquery-ui-sortable' );
	}

	/**
	 * {@inheritDoc}. Always dynamically set `max` equal to the number of choices.
	 *
	 * @since 1.0.0
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( is_array( $this->choices ) ) {
			$this->max = count( $this->choices );
		}
		$this->valid_choices = $this->get_valid_choices( $this->choices );
	}

	/**
	 * @override
	 * @since 1.0.0
	 */
	protected function add_to_json() {
		$this->json['choicesOrdered'] = $this->value();
		$this->json['choices'] = $this->choices;
	}

	/**
	 * @override
	 * @since 1.0.0
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::sortable( $value, $setting, $control );
	}

	/**
	 * @override
	 * @since 1.0.0
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::sortable( $validity, $value, $setting, $control );
	}
}

/**
 * Register on WordPress Customize global object
 */
$wp_customize->register_control_type( 'KKcp_Customize_Control_Sortable' );

/**
 * Tags Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Tags extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_tags';

	/**
	 * Option to allow a maximum selection boundary.
	 *
	 * There is no maximum by default for this control.
	 *
	 * @since 1.0.0
	 * @var ?int
	 */
	public $max = null;

	/**
	 * Option to allow a minimum selection boundary
	 *
	 * @since 1.0.0
	 * @var ?int To set a control as optional use the `$optional` class property
	 *      		 instead of setting `$min` to `1`.
	 */
	public $min = null;

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'persist' => array( 'sanitizer' => 'bool' ),
		'removable' => array( 'sanitizer' => 'bool' ),
		'draggable' => array( 'sanitizer' => 'bool' ),
		'restore_on_backspace' => array( 'sanitizer' => 'bool' ),
	);

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'vTagsType' => esc_html__( 'Tags must be a string', 'kkcp' ),
			'vTagsMin' => esc_html__( 'Minimum **%s** tags required', 'kkcp' ),
			'vTagsMax' => esc_html__( 'Maximum **%s** tags allowed', 'kkcp' ),
		);
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		$this->json['max'] = KKcp_SanitizeJS::int_or_null( false, $this->max );
		$this->json['min'] = KKcp_SanitizeJS::int_or_null( false, $this->min );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
 	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::tags( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
 	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::tags( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Tags' );

/**
 * Text Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Text extends KKcp_Customize_Control_Base {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_text';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'tooltip' => array( 'sanitizer' => 'string' ),
		'type' => array( 'sanitizer' => 'enum', 'values' => array( 'text', 'tel', 'url', 'email', ) ),
		'autocomplete' => array( 'sanitizer' => 'string' ),
		'maxlength' => array( 'sanitizer' => 'int' ),
		'minlength' => array( 'sanitizer' => 'int' ),
		'pattern' => array( 'sanitizer' => 'string' ),
		'placeholder' => array( 'sanitizer' => 'string' ),
		'spellcheck' => array( 'sanitizer' => 'bool' ),
	);


	/**
	 * HTML (allows html in the setting value)
	 *
	 * Note: When this property is truthy sanitization is done as with all the
	 * other controls while validation is a bit 'loose', so the input of the user
	 * might slightly differs from the actual value stored in the database.
	 * Setting this property to `true` can be dangerous instead, only do it if you
	 * know its implications.
	 *
	 * - When `false` (default) no `html` is allowed at all.
	 * - When `'escape'` `html` tags will be escaped.
	 * - When `'dangerous'` all html will be allowed (dangerous).
	 * - In all other cases value will be treated as the `$context` argument passed
	 * to `wp_kses_allowed_html` which will then be passed to `wp_kses`
	 * @see https://codex.wordpress.org/Function_Reference/wp_kses_allowed_html
	 * @see https://codex.wordpress.org/Function_Reference/wp_kses
	 * e.g.
	 * ```
	 * $html => array(
	 *   'kses' => array(
	 *     'b' => array(),
	 *     'e' => array(),
	 *   ),
	 * ),
	 * // or
	 * $html => 'post',
	 * ```
	 *
	 * @since 1.0.0
	 * @var bool|string|array
	 */
	public $html = false;

	/**
	 * @since  1.0.0
	 * @inheritDoc
	 */
	public function get_l10n() {
		return array(
			'vTextType' => esc_html__( 'It must be a string', 'kkcp' ),
			'vInvalidUrl' => esc_html__( 'Invalid URL', 'kkcp' ),
			'vInvalidEmail' => esc_html__( 'Invalid email', 'kkcp' ),
			'vTextTooLong' => esc_html__( 'It must be shorter than **%s** chars', 'kkcp' ),
			'vTextTooShort' => esc_html__( 'It must be longer than **%s** chars', 'kkcp' ),
			'vTextPatternMismatch' => esc_html__( 'It must follow this pattern **%s**', 'kkcp' ),
			'vTextHtml' => esc_html__( 'HTML is not allowed. It will be stripped out on save', 'kkcp' ),
			'vTextInvalidHtml' => esc_html__( 'This text contains some unallowed HTML. It will be stripped out on save', 'kkcp' ),
			'vTextHtmlTags' => esc_html__( 'The following HTML tags are not allowed: **%s**. They will be stripped out on save', 'kkcp' ),
			'vTextEscaped' => esc_html__( 'HTML code will be escaped on save', 'kkcp' ),
			'vTextDangerousHtml' => esc_html__( 'HTML code is dangerously allowed here', 'kkcp' ),
		);
	}

	/**
	 * @since 1.0.0
	 * @ovrride
	 */
	protected function add_to_json() {
		parent::add_to_json();

		if ( $this->html ) {
			$this->json['html'] = $this->html;
		}
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		return KKcp_Sanitize::text( $value, $setting, $control );
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function validate( $validity, $value, $setting, $control ) {
		return KKcp_Validate::text( $validity, $value, $setting, $control );
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Text' );

/**
 * Password Control custom class
 *
 * @todo  The default setting of this control get hashed with `wp_hash_password`
 * before getting saved to the database. This does not happen in the frontend
 * preview for obvious reasons.
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Password extends KKcp_Customize_Control_Text {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_password';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'autocomplete' => array( 'sanitizer' => 'string' ),
		'maxlength' => array( 'sanitizer' => 'int' ),
		'minlength' => array( 'sanitizer' => 'int' ),
		'pattern' => array( 'sanitizer' => 'string' ),
		'placeholder' => array( 'sanitizer' => 'string' ),
		'visibility' => array( 'sanitizer' => 'bool' ),
	);

	/**
	 * @since  1.0.0
	 * @inerhitDoc
	 */
	public function get_l10n() {
		return array(
			'passwordShow' => esc_html__( 'Show password', 'kkcp' ),
			'passwordHide' => esc_html__( 'Hide password', 'kkcp' ),
		);
	}

	/**
	 * Simple sanitization that hashes the password.
	 *
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected static function sanitize( $value, $setting, $control ) {
		if ( is_string( $value ) ) {
			return wp_hash_password( $value );
		}
		return null;
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Password' );

/**
 * Textarea Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Textarea extends KKcp_Customize_Control_Text {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_textarea';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'tooltip' => array( 'sanitizer' => 'string' ),
		'rows' => array( 'sanitizer' => 'int' ),
		'autocomplete' => array( 'sanitizer' => 'string' ),
		'maxlength' => array( 'sanitizer' => 'int' ),
		'minlength' => array( 'sanitizer' => 'int' ),
		'placeholder' => array( 'sanitizer' => 'string' ),
		'spellcheck' => array( 'sanitizer' => 'bool' ),
	);

	/**
	 * Enable TinyMCE textarea (default = `false`)
	 *
	 * @since 1.0.0
	 * @var boolean|array
	 */
	public $wp_editor = false;

	/**
	 * Allowed WP editor options
	 *
	 * Sanitize methods must be class methods of `KKcp_SanitizeJS` or global
	 * functions
	 *
	 * The commented options are not allowed to be changed and some of theme
	 * are always overriden in js to the indicated default value.
	 *
	 * @see the following docs:
	 * - https://codex.wordpress.org/Function_Reference/wp_editor
	 * - https://codex.wordpress.org/Quicktags_API#Default_Quicktags
	 * - https://codex.wordpress.org/TinyMCE
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public static $allowed_wp_editor_options = array(
		'wpautop' => array( 'sanitizer' => 'bool' ),
		// Default: false (instead of WP core's true)

		'mediaButtons' => array( 'sanitizer' => 'bool' ),
		// Default: false (instead of WP core's true)

		// 'textareaName' => 'string',
		// Default: $editor_id

		'textareaRows' => array( 'sanitizer' => 'int' ),
		// Default: 5 (instead of WP core's get_option('default_post_edit_rows', 10))

		// 'tabindex' => array( 'sanitizer' => 'int' ),
		// Default: None

		// 'editorCss' => array( 'sanitizer' => 'string' ),
		// Default: None

		'editorClass' => array( 'sanitizer' => 'string' ),
		// Default: Empty string

		'editorHeight' => array( 'sanitizer' => 'int' ),
		// Default: None

		// 'teeny' => array( 'sanitizer' => 'bool' ),
		// Default: true (instead of WP core's false)

		// 'dfw' => array( 'sanitizer' => 'bool' ),
		// Default: false

		'tinymce' => array( 'sanitizer' => 'bool_object', 'permissive_object' => true ),
		// Default: true (we don't sanitize each option here)

		'quicktags' => array( 'sanitizer' => 'bool_object', 'values' => array(
			'buttons' => array( 'sanitizer' => 'string' )
		) ),
		// Default: true

		'dragDropUpload' => array( 'sanitizer' => 'bool' ),
		// Default: false
	);

	/**
	 * {@inheritDoc}. Override it here in order to force the unset of `wp_editor`
	 * in case the user has no the right capability. Then, if the wp_editor is in
	 * use force a possible `$html => false` property to context `'post'` (this
	 * can stil be tweaked on a per control base).
	 *
	 * @since 1.0.0
	 * @override
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );

		if ( ! user_can_richedit() ) {
			$this->wp_editor = false;
		}

		if ( $this->wp_editor && ! $this->html ) {
			$this->html = 'post';
		}
	}

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected function add_to_json() {
		parent::add_to_json();

		if ( $this->wp_editor ) {
			if ( is_array( $this->wp_editor ) ) {
				$this->json['wp_editor'] = KKcp_SanitizeJS::options( $this->wp_editor, self::$allowed_wp_editor_options );
			} else {
				$this->json['wp_editor'] = KKcp_SanitizeJS::bool( false, $this->wp_editor );
			}
			wp_enqueue_editor();
		}
	}
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Textarea' );

/**
 * Toggle Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: 1.1.1
 * @link       https://knitkode.com/products/customize-plus
 */
class KKcp_Customize_Control_Toggle extends KKcp_Customize_Control_Checkbox {

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	public $type = 'kkcp_toggle';

	/**
	 * @since 1.0.0
	 * @inheritDoc
	 */
	protected $allowed_input_attrs = array(
		'label_false' => array( 'sanitizer' => 'string' ),
		'label_true' => array( 'sanitizer' => 'string' ),
	);
}

// Register on WordPress Customize global object
$wp_customize->register_control_type( 'KKcp_Customize_Control_Toggle' );