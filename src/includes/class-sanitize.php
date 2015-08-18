<?php defined( 'ABSPATH' ) or die;

/**
 * Sanitize functions
 *
 * @package    Customize_Plus
 * @subpackage Customize
 * @author     PlusWP <dev@pluswp.com> (http://pluswp.com)
 * @copyright  2015 PlusWP (kunderi kuus)
 * @license    GPL-2.0+
 * @version    Release: pkgVersion
 * @link       http://pluswp.com/customize-plus
 */
class PWPcp_Sanitize {

	/**
	 * Is an associative array or not
	 * @link(http://stackoverflow.com/a/14669600/1938970, source)
	 * @since  0.0.1
	 * @param  array   $array The array to test
	 * @return boolean        Whether is associative or not
	 */
	public static function is_assoc(array $array) {
		// Keys of the array
		$keys = array_keys($array);

		// If the array keys of the keys match the keys, then the array must
		// not be associative (e.g. the keys array looked like {0:0, 1:1...}).
		return array_keys($keys) !== $keys;
	}

	/**
	 * Sanitize CSS
	 *
	 * @link(https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php#L27, source)
	 * @param string $input CSS to sanitize.
	 * @return string Sanitized CSS.
	 */
	public static function css( $input ) {
		return wp_strip_all_tags( $input );
	}

	/**
	 * Sanitize image
	 *
	 * @link(https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php#L141, source)
	 * @param string               $image   Image filename.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @return string The image filename if the extension is allowed; otherwise, the setting default.
	 */
	public static function image( $image, $setting ) {
		// Array of valid image file types.
		// The array includes image mime types that are included in wp_get_mime_types()
		$mimes = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
				'bmp'          => 'image/bmp',
				'tif|tiff'     => 'image/tiff',
				'ico'          => 'image/x-icon'
		);
		// Return an array with file extension and mime_type.
		$file = wp_check_filetype( $image, $mimes );
		// If $image has a valid mime_type, return it; otherwise, return the default.
		return ( $file['ext'] ? $image : $setting->default );
	}

	/**
	 * HTML sanitization callback example.
	 *
	 * @link(https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php#L120, source)
	 * @param string $html HTML to sanitize.
	 * @return string Sanitized HTML.
	 */
	public static function html( $html ) {
		return wp_filter_post_kses( $html );
	}

	/**
	 * No-HTML sanitization callback example.
	 *
	 * @link(https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php#L179, source)
	 * @since  0.0.1
	 * @param string $nohtml The no-HTML content to sanitize.
	 * @return string Sanitized no-HTML content.
	 */
	public static function nohtml( $nohtml ) {
		return wp_filter_nohtml_kses( $nohtml );
	}

	/**
	 * Is setting value (`control.setting()`) empty?
	 * Used to check if required control's settings have instead an empty value
	 *
	 * @since  0.0.1
	 * @see php class method `PWPcp_Sanitize::is_setting_value_empty()`
	 * @param  string  $value A setting value
	 * @return boolean 				Whether the setting value has to be considered
	 *                        empty, or not set.
	 */
	public static function is_setting_value_empty( $value ) {
		// first try to compare it to an empty string
		if ( $value === '' ) {
			return true;
		} else {
			// if it's a jsonized value try to parse it and
			$value_parsed = json_decode( $value );
			if ( $value_parsed ) {
				// see if we have an empty array or an empty object
				if ( is_array( $value_parsed ) && empty( $value_parsed ) ) {
					return true;
				}
			}
			return false;
		}
	}

	/**
	 * Sanitize font families.
	 *
	 * Be sure that each font family is wrapped in quote, good for consistency
	 *
	 * @since  0.0.1
	 * @param  string $input
	 * @return string
	 */
	public static function font_families( $input ) { // @@todo to finish, check that the inputs are valid font family names \\
		$font_families_sanitized = array();

		// treat a string
		if ( is_string( $input ) ) {
			foreach ( explode( ',', $input ) as $font_family ) {
				// remove eventual quotes
				$unquoted_font_family = str_replace( "'", '', str_replace( '"', '', $font_family ) );
				array_push( $font_families_sanitized, "'" . trim( $unquoted_font_family ) . "'" );
			}
			return implode( ',', $font_families_sanitized );
		}
		// and an array
		else if ( is_array( $input ) ) {
			foreach ( $input as $font_family ) {
				// remove eventual quotes
				$unquoted_font_family = str_replace( "'", '', str_replace( '"', '', $font_family ) );
				array_push( $font_families_sanitized, "'" . trim( $unquoted_font_family ) . "'" );
			}
			return $font_families_sanitized;
		}
	}

	/**
	 * Extract unit (like `px`, `em`, `%`, etc.) from control->units property
	 *
	 * @since  0.0.1
	 * @param  string               $input   The control's setting value
	 * @param  WP_Customize_Control $control Control instance.
	 * @return string 				               The first valid unit found.
	 */
	public static function extract_size_unit( $input, $control ) {
		if ( is_array( $control->units ) ) {
			foreach ( $control->units as $unit ) {
				if ( false != strpos( $input, $unit ) ) {
					return $unit;
				}
			}
			return isset( $control->units[0] ) ? $control->units[0] : '';
		}
		return '';
	}

	/**
	 * Extract number from input, returns 0 otherwise
	 *
	 * @since  0.0.1
	 * @param  string 							$input   The value from where to extract
	 * @param  WP_Customize_Control $control Control instance.
	 * @return int|float|boolean The extracted number or false if the input does not
	 *                           contain any digit.
	 */
	public static function extract_number( $input, $control ) {
		if ( is_int( $input ) || ( is_float( $input ) && $control->allowFloat ) ) {
			return $input;
		}
		if ( $control->allowFloat ) {
			$number_extracted = filter_var( $input, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
		} else {
			$number_extracted = filter_var( $input, FILTER_SANITIZE_NUMBER_INT );
		}
		if ( $number_extracted || 0 == $number_extracted ) {
			return $number_extracted;
		}
		return false;
	}

	/**
	 * Sanitize / validate a number against an array of attributes.
	 *
	 * @since  0.0.1
	 * @param  int|float 						$number  The number to sanitize
	 * @param  WP_Customize_Control $control Control instance.
	 * @return int|float      			The saniitized / valid number
	 */
	public static function number( $number, $control ) {
		$attrs = $control->input_attrs;

		// if it's a float but it is not allowed to be it round it
		if ( is_float( $number ) && ! $control->allowFloat ) {
			$number = round( $number );
		}
		if ( $attrs ) {
			// if doesn't respect the step given round it to the closest
			// then do the min and max checks
			if ( isset( $attrs['step'] ) && $number % $attrs['step'] != 0 ) {
				$number = round( $number / $attrs['step'] ) * $attrs['step'];
			}
			// if it's lower than the minimum return the minimum
			if ( isset( $attrs['min'] ) && $number < $attrs['min'] ) {
				return $attrs['min'];
			}
			// if it's higher than the maxmimum return the maximum
			if ( isset( $attrs['max'] ) && $number > $attrs['max'] ) {
				return $attrs['max'];
			}
		}
		return $number;
	}

	/**
	 * Sanitize/validate hex color
	 * check for a hex color string like '#c1c2b4' or '#c00' or '#CCc000' or 'CCC'
	 *
	 * @since  0.0.1
	 * @param  string $input  The input value to sanitize
	 * @return string|boolean The sanitized input or `false` in case the input
	 *                        value is not valid.
	 */
	public static function color_hex( $input ) {
		$input = trim( $input );

		if ( preg_match( '/^#([A-Fa-f0-9]{3}){1,2}$/', $input ) ) {
			return $input;
		}
		// check for a hex color string without hash 'c1c2b4'
		else if ( preg_match( '/^([A-Fa-f0-9]{3}){1,2}$/', $input ) ) {
			// hex color is valid, add hash
			return '#' . $input;
		} else {
			return false;
		}
	}

	/**
	 * Sanitize / validate RGBA color
	 *
	 * @since  0.0.1
	 * @param  string $input  The input value to sanitize
	 * @return string|boolean The sanitized input or `false` in case the input
	 *                        value is not valid.
	 */
	public static function color_rgba( $input ) {
		$input = trim( $input );
		if ( preg_match( '/^rgba\(\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])\s*,\s*(0|[1-9]\d?|1\d\d?|2[0-4]\d|25[0-5])\s*,\s*(0?\.[0-9]*[1-9][0-9]*|[01])\s*\)$/', $input ) ) {
			$input = str_replace( ' ', '', $input );
			sscanf( $input, 'rgba(%d,%d,%d,%f)', $red, $green, $blue, $alpha );
			return "rgba($red,$green,$blue,$alpha)";
		} else {
			return false;
		}
	}

	/**
	 * Sanitize string compared to the choices array (i.e. for radio based control)
	 *
	 * @since 0.0.1
	 * @param string               $input   The value to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @param WP_Customize_Control $control Control instance.
	 * @return string The sanitized value.
	 */
	public static function string_in_choices( $input, $setting, $control ) {
		if ( isset( $control->choices[ $input ] ) ) {
			return $input;
		} else {
			return $setting->default;
		}
	}

	/**
	 * Sanitize array compared to the choices array (i.e. for radio based control)
	 *
	 * @since 0.0.1
	 * @param string               $input   The value to sanitize.
	 * @param WP_Customize_Setting $setting Setting instance.
	 * @param WP_Customize_Control $control Control instance.
	 * @return string The sanitized value.
	 */
	public static function array_in_choices( $input, $setting, $control ) {
		$input_decoded = json_decode( $input );

		if ( is_array( $input_decoded ) ) {
			$input_sanitized = array();

			foreach ( $input_decoded as $key ) {
				if ( isset( $control->choices[ $key ] ) ) {
					array_push( $input_sanitized, $key );
				}
			}
			return json_encode( $input_sanitized );
		} else {
			return $setting->default;
		}
	}
}