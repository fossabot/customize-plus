<?php // @partial
/**
 * Password Control custom class
 *
 * @since  1.0.0
 *
 * @package    Customize_Plus
 * @subpackage Customize\Controls
 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
 * @copyright  2018 KnitKode
 * @license    GPLv3
 * @version    Release: pkgVersion
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
	protected $input_attrs_allowed = array(
		'autocomplete' => array( 'sanitizer' => 'string' ),
		'disabled' => array( 'sanitizer' => 'bool' ),
		'maxlength' => array( 'sanitizer' => 'number' ),
		'minlength' => array( 'sanitizer' => 'number' ),
		'pattern' => array( 'sanitizer' => 'string' ),
		'placeholder' => array( 'sanitizer' => 'string' ),
	);
}

/**
 * Register on WordPress Customize global object
 */
$wp_customize->register_control_type( 'KKcp_Customize_Control_Password' );