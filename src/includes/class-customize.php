<?php defined( 'ABSPATH' ) or die;

if ( ! class_exists( 'PWPcp_Customize' ) && class_exists( 'PWPcp_Singleton' ) ):

	/**
	 * Contains methods for customizing the theme customization screen.
	 *
	 * @package    Customize_Plus
	 * @subpackage Customize
	 * @author     PlusWP <dev@pluswp.com> (http://pluswp.com)
	 * @copyright  2015 PlusWP (kunderi kuus)
	 * @license    GPL-2.0+
	 * @version    Release: pkgVersion
	 * @link       http://pluswp.com/customize-plus
	 */

	class PWPcp_Customize extends PWPcp_Singleton {

		/**
		 * WordPress customize custom types for panels,
		 * sections controls and settings.
		 * Each type must be declared with its shortname and name
		 * of its php class.
		 *
		 * @var array
		 */
		public static $custom_types = array(
			'panels' => array(),
			'sections' => array(),
			'controls' => array(
				'color' => 'WP_Customize_Color_Control',
				'image' => 'WP_Customize_Image_Control',
				'upload' => 'WP_Customize_Upload_Control',
				'pwpcp_buttonset' => 'PWPcp_Customize_Control_Buttonset',
				'pwpcp_color' => 'PWPcp_Customize_Control_Color',
				'pwpcp_font_family' => 'PWPcp_Customize_Control_Font_Family',
				'pwpcp_multicheck' => 'PWPcp_Customize_Control_Multicheck',
				'pwpcp_number' => 'PWPcp_Customize_Control_Number',
				'pwpcp_radio' => 'PWPcp_Customize_Control_Radio',
				'pwpcp_radio_image' => 'PWPcp_Customize_Control_Radio_Image',
				'pwpcp_select' => 'PWPcp_Customize_Control_Select',
				'pwpcp_slider' => 'PWPcp_Customize_Control_Slider',
				'pwpcp_text' => 'PWPcp_Customize_Control_Text',
				'pwpcp_toggle' => 'PWPcp_Customize_Control_Toggle',
			),
			'settings' => array(),
		);


		/**
		 * CSS shared for all the icons
		 *
		 * @var string
		 */
		private static $css_icons_shared = 'position:relative;top:4px;left:-2px;line-height:0;opacity:.5;font-size:20px;font-weight:normal;font-family:"dashicons";';

		/**
		 * CSS for icons displayment
		 *
		 * @var string
		 */
		private static $css_icons = '';

		/**
		 * Font families
		 *
		 * @see http://www.w3schools.com/cssref/css_websafe_fonts.asp
		 * @var array
		 */
		public static $font_families = array(
		  // Serif Fonts
		  'Georgia',
		  '"Palatino Linotype"',
		  '"Book Antiqua"',
		  'Palatino',
		  '"Times New Roman"',
		  'Times',
		  'serif',
		  // Sans-Serif Fonts
		  'Arial',
		  'Helvetica',
		  '"Helvetica Neue"',
		  '"Arial Black"',
		  'Gadget',
		  '"Comic Sans MS"',
		  'cursive',
		  'Impact',
		  'Charcoal',
		  '"Lucida Sans Unicode"',
		  '"Lucida Grande"',
		  'Tahoma',
		  'Geneva',
		  '"Trebuchet MS"',
		  'Verdana',
		  'sans-serif',
		  // Monospace Font
		  '"Courier New"',
		  'Courier',
		  '"Lucida Console"',
		  'Monaco',
		  'monospace',
		  'Menlo',
		  'Consolas',

		  // Google font
		  '"Lato"',
		);

		/**
		 * Constructor
		 *
		 * @since  0.0.1
		 */
		protected function __construct() {
			add_action( 'customize_register', array( __CLASS__, 'register_custom_classes' ) );
			add_action( 'customize_controls_print_styles', array( __CLASS__, 'enqueue_css_admin' ) );
			add_action( 'customize_controls_print_styles', array( __CLASS__, 'enqueue_js_shim' ) );
			add_action( 'customize_controls_print_footer_scripts' , array( __CLASS__, 'enqueue_js_admin' ) );
			add_action( 'customize_controls_print_footer_scripts', array( __CLASS__, 'get_view_loader' ) );
			add_action( 'customize_preview_init' , array( __CLASS__, 'enqueue_js_preview' ) );
		}

		/**
		 * Outputs the custom css file
		 * in the admin page of the customize
		 *
		 * @since  0.0.1
		 */
		public static function enqueue_css_admin() {
			do_action( 'PWPcp/customize/enqueue_css_admin_pre', 'PWPcp-customize' );

			wp_enqueue_style( 'PWPcp-customize', plugins_url( 'assets/customize.min.css', PWPcp_PLUGIN_FILE ), array( 'dashicons' ), PWPcp_PLUGIN_VERSION );
			wp_add_inline_style( 'PWPcp-customize', self::$css_icons );

			do_action( 'PWPcp/customize/enqueue_css_admin_post', 'PWPcp-customize' );
		}

		/**
		 * Outputs the javascript needed
		 * in the admin page of the customize (not the iframe in it).
		 * Register and add data and localized strings to the customize.js
		 *
		 * @since  0.0.1
		 */
		public static function enqueue_js_admin() {
			do_action( 'PWPcp/customize/enqueue_js_admin_pre', 'PWPcp-customize' );

			wp_register_script( 'PWPcp-customize', plugins_url( 'assets/customize.min.js', PWPcp_PLUGIN_FILE ), array( 'json2', 'underscore', 'jquery', 'jquery-ui-slider' ), PWPcp_PLUGIN_VERSION, false );
			wp_localize_script( 'PWPcp-customize', 'PWPcp', array(
					'components' => apply_filters( 'PWPcp/customize/js_components', array() ),
					'constants' => self::get_js_constants(),
					'l10n' => self::get_js_l10n(),
				) );
			wp_enqueue_script( 'PWPcp-customize' );

			do_action( 'PWPcp/customize/enqueue_js_admin_post', 'PWPcp-customize' );
		}

		/**
		 * Get javascript constants
		 *
		 * @since  0.0.1
		 * @return array The required plus the additional constants, added through hook.
		 */
		public static function get_js_constants() {
			$required = array(
				'THEME_URL' => get_stylesheet_directory_uri(),
				'IMAGES_BASE_URL' => PWPcp_Theme::$images_base_url,
				'DOCS_BASE_URL' => PWPcp_Theme::$docs_base_url,
				'FONT_FAMILIES' => pwpcp_sanitize_font_families( self::$font_families ),
			);
			$additional = (array) apply_filters( 'PWPcp/customize/js_constants', array() );
			return array_merge( $required, $additional );
		}

		/**
		 * Get javascript localized strings
		 *
		 * @since  0.0.1
		 * @return array The required plus the additional localized strings, added through hook.
		 */
		public static function get_js_l10n() {
			$required = array(
				'back' => __( 'Back', 'pkgTextdomain' ),
				'tools' => __( 'Tools', 'pkgTextdomain' ),
				'introTitle' => __( 'Customize Plus', 'pkgTextdomain' ),
				'introText' => __( 'Welcome to Customize Plus', 'pkgTextdomain' ),
				'customColor' => __( 'Custom', 'pkgTextdomain' ),
				'togglePaletteMoreText' => __( 'Show color picker', 'pkgTextdomain' ),
				'togglePaletteLessText' => __( 'Hide color picker', 'pkgTextdomain' ),
			);
			$additional = (array) apply_filters( 'PWPcp/customize/js_l10n', array() );
			return array_merge( $required, $additional );
		}

		/**
		 * // @@todo @@trial .......................................................................
		 * doesn't work for now and not  even sure if it will be needed \\
		 * @param array $localized_strings [description]
		 */
		public static function add_js_l10n( $localized_strings = array() ) {
			global $wp_scripts;
			$data = '';
			foreach ( $localized_strings as $key => $value ) {
				$data .= sprintf( 'window.PWPcp.l10n[%s] = %s;', json_encode( $key ), json_encode( $value ) );
			}
			wp_localize_script( 'PWPcp-customize', 'dasadta', $data );
			$wp_scripts->add_data( 'PWPcp-customize', 'dasadta', $data );
		}

		/**
		 * This outputs the javascript needed in the iframe
		 * and manage the dequeuing / enqueuing of the stylesheets
		 *
		 * @since  0.0.1
		 */
		public static function enqueue_js_preview() {

			do_action( 'PWPcp/customize/preview_init' );

			wp_enqueue_script( 'PWPcp-customize-preview', plugins_url( 'assets/customize-preview.min.js', PWPcp_PLUGIN_FILE ), array( 'jquery', 'customize-preview' ), PWPcp_PLUGIN_VERSION, true );
		}

		/**
		 * Get view for fullscreen loader (used later on also by other components
		 * like the 'importer')
		 *
		 * @since  0.0.1
		 */
		public static function get_view_loader() { // @@wptight-layout \\
			?>
			//= include '../views/customize-loader.php'
			<?php
		}

		/**
		 * Enqueue ECMA script 5 shim for old browsers
		 *
		 * @since  0.0.1
		 */
		public static function enqueue_js_shim() {
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
			wp_enqueue_script( 'es5-shim', plugins_url( "assets/es5-shim{$min}.js", PWPcp_PLUGIN_FILE ), array(), PWPcp_PLUGIN_VERSION );
			wp_script_add_data( 'es5-shim', 'conditional', 'if lt IE 9' );
		}

		/**
		 * Custom settings, controls, sections, and panels, load classes and
		 * register through the WordPress API.
		 *
		 * @since  0.0.1
		 */
		public static function register_custom_classes() {
			require_once( PWPcp_PLUGIN_DIR . 'includes/customize-classes.php' );

			do_action( 'PWPcp/customize/register_custom_classes', __CLASS__ );

			self::register_tree();
		}

		/**
		 * Register custom settings/controls/sections/panels types
		 *
		 * @since  0.0.1
		 * @param  array(<$type, $class_name>) $types The custom types to add
		 */
		public static function register_custom_types( $types ) {
			foreach ( $types as $type => $new_custom_types ) {
				if ( self::$custom_types[ $type ] ) {
					self::$custom_types[ $type ] = array_merge( self::$custom_types[ $type ], $new_custom_types );
				}
			}
		}

		/**
		 * Register customize tree.
		 * On the root level we can have either panels or section so we check
		 * the `subject` of the arrays at the first level of depth of the tree.
		 *
		 * @since  0.0.1
		 */
		private static function register_tree() {
			$priority = 0;

			foreach ( PWPcp_Theme::$customize_tree as $component ) {
				$priority++;

				if ( 'panel' === $component['subject'] ) {
					self::add_panel_from_tree( $component, $priority );
				}
				else if ( 'section' === $component['subject'] ) {
					self::add_section_from_tree( null, $component, $priority );
				}
			}
		}

		/**
		 * Add panel declared in the Customizer tree.
		 *
		 * @since  0.0.1
		 * @param Array $panel    The panel array as defined by the theme developers.
		 * @param Int   $priority This incremental number is used by WordPress to calculate
		 *                        the order at which the panel are inserted in the UI.
		 * @global $wp_customize {WP_Customize_Manager} WordPress Customizer instance
		 */
		private static function add_panel_from_tree( $panel, $priority ) {
			global $wp_customize;

			// dynamically get panel_id with opitons_prefix
			$panel_id = PWPcp_Theme::$options_prefix . '-' . $panel['id'];

			// augment panel args array
			$panel_args = array();
			$panel_args['title'] = $panel['title'];
			if ( isset( $panel['description'] ) ) {
				$panel_args['description'] = $panel['description'];
			}
			$panel_args['priority'] = $priority;
			// $panel_args['capability'] = 'edit_theme_options'; // @@tocheck \\
			// $panel_args['theme_supports'] = ''; // @@tocheck \\

			// add panel icon if specified
			if ( isset( $panel['icon'] ) ) {
				self::add_css_panel_dashicon( $panel_id, $panel['icon'] );
			}

			// Add panel to WordPress
			$wp_customize->add_panel( $panel_id, $panel_args );

			// Add panel's sections
			if ( isset( $panel['sections'] ) ) {
				// set priority to 0
				$priority_depth1 = 0;
				// Loop through 'sections' array in each panel and add sections
				foreach ( $panel['sections'] as $section ) {
					// increment priority
					$priority_depth1++;
					self::add_section_from_tree( $panel_id, $section, $priority_depth1 );
				}
			}
		}

		/**
		 * Add section declared in the Customizer tree.
		 *
		 * @since  0.0.1
		 * @param String $panel_id The id of the parent panel when the section is nested
		 *                         inside a panel.
		 * @param Array  $section  The section array as defined by the theme developers.
		 * @param Int    $priority This incremental number is used by WordPress to calculate
		 *                         the order at which the section are inserted in the UI.
		 * @global $wp_customize {WP_Customize_Manager} WordPress Customizer instance
		 */
		private static function add_section_from_tree( $panel_id, $section, $priority ) {
			global $wp_customize;

			// create section args array
			$section_args = array();
			$section_args['title'] = $section['title'];
			if ( isset( $section['description'] ) ) {
				$section_args['description'] = $section['description'];
			}
			$section_args['priority'] = $priority;
			// $section_args['capability'] = 'edit_theme_options'; // @@tocheck \\

			if ( $panel_id ) {
				$section_args['panel'] = $panel_id;
			}

			// add section dashicon if specified
			if ( isset( $section_args['dashicon'] ) ) {
				self::add_css_section_dashicon( $section['id'], $section_args['dashicon'] );
			}

			// Add section to WordPress
			$wp_customize->add_section( $section['id'], $section_args );

			// Add section fields
			if ( isset( $section['fields'] ) ) {
				// Loop through 'fields' array in each section and add settings and controls
				foreach ( $section['fields'] as $field_id => $field_args ) {
					self::tree_add_field( $section['id'], $field_id, $field_args );
				}
			}
		}

		/**
		 * Add field (setting + control) declared in the Customizer tree.
		 *
		 * @since  0.0.1
		 * @param String $section_id The id of the parent section (required).
		 * @param Array  $field_id   The section id as defined by the theme developers.
		 * @param Array  $field_args The section array as defined by the theme developers.
		 * @global $wp_customize {WP_Customize_Manager} WordPress Customizer instance
		 */
		private static function tree_add_field( $section_id, $field_id, $field_args ) {
			global $wp_customize;

			$control_args = $field_args['control'];
			$setting_args = isset( $field_args['setting'] ) ? $field_args['setting'] : null;

			if ( $setting_args ) {

				// Check if 'option' or 'theme_mod' is used to store option
				// If nothing is set, $wp_customize->add_setting method will default use 'theme_mod'
				// If 'option' is used as setting type its value will be stored in an entry in
				// {prefix}_options table.
				if ( isset( $setting_args['type'] ) && 'option' == $setting_args['type'] ) {
					$field_id = PWPcp_Theme::$options_prefix . '[' . $field_id . ']'; // @@tobecareful this is tight to customize-component-import.js \\
				}

				// add default callback function, if none is defined
				if ( ! isset( $setting_args['sanitize_callback'] ) ) {
					$setting_args['sanitize_callback'] = 'pwpcp_sanitize_callback';
				}
				// Add setting to WordPress
				$wp_customize->add_setting( $field_id, $setting_args );

			}
			// if no settings args are passed then use the Dummy Setting Class
			else {
				// Add dummy setting to WordPress
				$wp_customize->add_setting( new PWPcp_Customize_Setting_Dummy( $wp_customize, $field_id ) );
			}

			// augment control args array with section id
			$control_args['section'] = $section_id;

			// Add control to WordPress
			$control_type = $control_args['type'];

			if ( isset( PWPcp_Customize::$custom_types['controls'][ $control_type ] ) ) {
				$control_type_class = PWPcp_Customize::$custom_types['controls'][ $control_type ];

				// if the class exist use it
				if ( class_exists( $control_type_class ) ) {
					$wp_customize->add_control( new $control_type_class( $wp_customize, $field_id, $control_args ) );
				}
				// if the desired class doesn't exist just use the plain WordPress API
				else {
					$wp_customize->add_control( $field_id, $control_args );
					// @@todo error (wrong api implementation, missing class) \\
				}
			}
			// if the desired control type is not specified just use the plain WordPress API
			else {
				$wp_customize->add_control( $field_id, $control_args );
				// @@todo error (wrong api implementation, missing control type) \\
			}
		}

		/**
		 * Add the needed css to display a dashicon for the given panel
		 *
		 * @since  0.0.1
		 * @param string $panel_id      The panel which will show the specified dashicon.
		 * @param int    $dashicon_code The dashicon code number, the `\f` is automatically
		 *                              added.
		 */
		private static function add_css_panel_dashicon( $panel_id = '', $dashicon_code ) {
			if ( ! absint( $dashicon_code ) ) {
				return;
			}
			self::$css_icons .= "#accordion-panel-$panel_id > h3:before,#accordion-panel-$panel_id .panel-title:before{content:'\\f$dashicon_code';" . self::$css_icons_shared . '}';
		}

		/**
		 * Add the needed css to display a dashicon for the given section
		 * // @@temp disabled for now \\
		 *
		 * @since  0.0.1
		 * @param string $section_id    The section which will show the specified dashicon.
		 * @param int    $dashicon_code The dashicon code number, the `\f` is automatically
		 *                              added.
		 */
		private static function add_css_section_dashicon( $section_id = '', $dashicon_code ) {
			return;
			if ( ! absint( $dashicon_code ) ) {
				return;
			}
			self::$css_icons .= "#accordion-section-$section_id > h3:before{content:'\\f$dashicon_code';}" . self::$css_icons_shared . '}';
		}
	}

	// Instantiate
	PWPcp_Customize::get_instance();

endif;