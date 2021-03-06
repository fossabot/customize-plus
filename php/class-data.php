<?php defined( 'ABSPATH' ) or die;

if ( ! class_exists( 'KKcp_Data' ) ):

	/**
	 * Data
	 *
	 * An helper class containing just static data to re-use accross PHP and JS.
	 *
	 * @package    Customize_Plus
	 * @subpackage Customize
	 * @author     KnitKode <dev@knitkode.com> (https://knitkode.com)
	 * @copyright  2018 KnitKode
	 * @license    GPLv3
	 * @version    Release: 1.1.1
	 * @link       https://knitkode.com/products/customize-plus
	 */
	class KKcp_Data {

		/**
		 * Browser's native css units
		 *
		 * @since  1.0.0
		 * @var array
		 */
		const CSS_UNITS = array(
			'em',
			'ex',
			'%',
			'px',
			'cm',
			'mm',
			'in',
			'pt',
			'pc',
			'ch',
			'rem',
			'vh',
			'vw',
			'vmin',
			'vmax',
		);

		/**
		 * Browser's native css color keywords
		 *
		 * @since  1.0.0
		 * @var array
		 */
		const COLORS_KEYWORDS = array(
			'aliceblue',
			'antiquewhite',
			'aqua',
			'aquamarine',
			'azure',
			'beige',
			'bisque',
			'black',
			'blanchedalmond',
			'blue',
			'blueviolet',
			'brown',
			'burlywood',
			'cadetblue',
			'chartreuse',
			'chocolate',
			'coral',
			'cornflowerblue',
			'cornsilk',
			'crimson',
			'cyan',
			'darkblue',
			'darkcyan',
			'darkgoldenrod',
			'darkgray',
			'darkgreen',
			'darkgrey',
			'darkkhaki',
			'darkmagenta',
			'darkolivegreen',
			'darkorange',
			'darkorchid',
			'darkred',
			'darksalmon',
			'darkseagreen',
			'darkslateblue',
			'darkslategray',
			'darkslategrey',
			'darkturquoise',
			'darkviolet',
			'deeppink',
			'deepskyblue',
			'dimgray',
			'dimgrey',
			'dodgerblue',
			'firebrick',
			'floralwhite',
			'forestgreen',
			'fuchsia',
			'gainsboro',
			'ghostwhite',
			'gold',
			'goldenrod',
			'gray',
			'green',
			'greenyellow',
			'grey',
			'honeydew',
			'hotpink',
			'indianred',
			'indigo',
			'ivory',
			'khaki',
			'lavender',
			'lavenderblush',
			'lawngreen',
			'lemonchiffon',
			'lightblue',
			'lightcoral',
			'lightcyan',
			'lightgoldenrodyellow',
			'lightgray',
			'lightgreen',
			'lightgrey',
			'lightpink',
			'lightsalmon',
			'lightseagreen',
			'lightskyblue',
			'lightslategray',
			'lightslategrey',
			'lightsteelblue',
			'lightyellow',
			'lime',
			'limegreen',
			'linen',
			'magenta',
			'maroon',
			'mediumaquamarine',
			'mediumblue',
			'mediumorchid',
			'mediumpurple',
			'mediumseagreen',
			'mediumslateblue',
			'mediumspringgreen',
			'mediumturquoise',
			'mediumvioletred',
			'midnightblue',
			'mintcream',
			'mistyrose',
			'moccasin',
			'navajowhite',
			'navy',
			'oldlace',
			'olive',
			'olivedrab',
			'orange',
			'orangered',
			'orchid',
			'palegoldenrod',
			'palegreen',
			'paleturquoise',
			'palevioletred',
			'papayawhip',
			'peachpuff',
			'peru',
			'pink',
			'plum',
			'powderblue',
			'purple',
			'red',
			'rosybrown',
			'royalblue',
			'saddlebrown',
			'salmon',
			'sandybrown',
			'seagreen',
			'seashell',
			'sienna',
			'silver',
			'skyblue',
			'slateblue',
			'slategray',
			'slategrey',
			'snow',
			'springgreen',
			'steelblue',
			'tan',
			'teal',
			'thistle',
			'tomato',
			'transparent',
			'turquoise',
			'violet',
			'wheat',
			'white',
			'whitesmoke',
			'yellow',
			'yellowgreen',
		);

		/**
		 * Browser's native css font families divided in groups
		 *
		 * @see http://www.w3schools.com/cssref/css_websafe_fonts.asp
		 * @since  1.0.0
		 * @var array
		 */
		public static function get_font_families_standard () {
			return array(
				'serif' => array(
					'label' => 'Serif Fonts',
					'values' => array(
						'Georgia',
						'"Palatino Linotype"',
						'"Book Antiqua"',
						'Palatino',
						'"Times New Roman"',
						'Times',
						'serif',
					),
				),
				'sans-serif' => array(
					'label' => 'Sans-Serif Fonts',
					'values' => array(
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
					),
				),
				'monospace' => array(
					'label' => 'Monospace Fonts',
					'values' => array(
						'"Courier New"',
						'Courier',
						'"Lucida Console"',
						'Monaco',
						'monospace',
						'Menlo',
						'Consolas',
					),
				),
			);
		}

		/**
		 * Get an array of all available dashicons.
		 *
		 * @see https://github.com/knitkode/dashicons/blob/master/groups.json
		 * @since  1.0.0
		 * @static
		 * @access public
		 * @return array
		 */
		public static function get_dashicons() {
			return array(
				'admin_menu' => array(
					'label' => esc_html__( 'Admin Menu', 'kkcp' ),
					'values' => array(
						'menu',
						'admin-site',
						'dashboard',
						'admin-post',
						'admin-media',
						'admin-links',
						'admin-page',
						'admin-comments',
						'admin-appearance',
						'admin-plugins',
						'admin-users',
						'admin-tools',
						'admin-settings',
						'admin-network',
						'admin-home',
						'admin-generic',
						'admin-collapse',
						'filter',
						'admin-customizer',
						'admin-multisite',
					)
				),
				'welcome_screen' => array(
					'label' => esc_html__( 'Welcome Screen', 'kkcp' ),
					'values' => array(
						'welcome-write-blog',
						'welcome-add-page',
						'welcome-view-site',
						'welcome-widgets-menus',
						'welcome-comments',
						'welcome-learn-more',
					),
				),
				'post_formats' => array(
					'label' => esc_html__( 'Post Formats', 'kkcp' ),
					'values' => array(
						'format-aside',
						'format-image',
						'format-gallery',
						'format-video',
						'format-status',
						'format-quote',
						'format-chat',
						'format-audio',
						'camera',
						'images-alt',
						'images-alt2',
						'video-alt',
						'video-alt2',
						'video-alt3',
					)
				),
				'media' => array(
					'label' => esc_html__( 'Media', 'kkcp' ),
					'values' => array(
						'media-archive',
						'media-audio',
						'media-code',
						'media-default',
						'media-document',
						'media-interactive',
						'media-spreadsheet',
						'media-text',
						'media-video',
						'playlist-audio',
						'playlist-video',
						'controls-play',
						'controls-pause',
						'controls-forward',
						'controls-skipforward',
						'controls-back',
						'controls-skipback',
						'controls-repeat',
						'controls-volumeon',
						'controls-volumeoff',
					)
				),
				'image_editing' => array(
					'label' => esc_html__( 'Image Editing', 'kkcp' ),
					'values' => array(
						'image-crop',
						'image-rotate',
						'image-rotate-left',
						'image-rotate-right',
						'image-flip-vertical',
						'image-flip-horizontal',
						'image-filter',
						'undo',
						'redo',
					)
				),
				'tinymce' => array(
					'label' => esc_html__( 'Tinymce', 'kkcp' ),
					'values' => array(
						'editor-bold',
						'editor-italic',
						'editor-ul',
						'editor-ol',
						'editor-quote',
						'editor-alignleft',
						'editor-aligncenter',
						'editor-alignright',
						'editor-insertmore',
						'editor-spellcheck',
						'editor-expand',
						'editor-contract',
						'editor-kitchensink',
						'editor-underline',
						'editor-justify',
						'editor-textcolor',
						'editor-paste-word',
						'editor-paste-text',
						'editor-removeformatting',
						'editor-video',
						'editor-customchar',
						'editor-outdent',
						'editor-indent',
						'editor-help',
						'editor-strikethrough',
						'editor-unlink',
						'editor-rtl',
						'editor-break',
						'editor-code',
						'editor-paragraph',
						'editor-table',
					)
				),
				'posts' => array(
					'label' => esc_html__( 'Posts', 'kkcp' ),
					'values' => array(
						'align-left',
						'align-right',
						'align-center',
						'align-none',
						'lock',
						'unlock',
						'calendar',
						'calendar-alt',
						'visibility',
						'hidden',
						'post-status',
						'edit',
						'trash',
						'sticky',
					)
				),
				'sorting' => array(
					'label' => esc_html__( 'Sorting', 'kkcp' ),
					'values' => array(
						'external',
						'arrow-up',
						'arrow-down',
						'arrow-right',
						'arrow-left',
						'arrow-up-alt',
						'arrow-down-alt',
						'arrow-right-alt',
						'arrow-left-alt',
						'arrow-up-alt2',
						'arrow-down-alt2',
						'arrow-right-alt2',
						'arrow-left-alt2',
						'sort',
						'leftright',
						'randomize',
						'list-view',
						'exerpt-view',
						'grid-view',
					)
				),
				'social' => array(
					'label' => esc_html__( 'Social', 'kkcp' ),
					'values' => array(
						'share',
						'share-alt',
						'share-alt2',
						'twitter',
						'rss',
						'email',
						'email-alt',
						'facebook',
						'facebook-alt',
						'googleplus',
						'networking',
					)
				),
				'wordpress_org' => array(
					'label' => esc_html__( 'Wordpress Org', 'kkcp' ),
					'values' => array(
						'hammer',
						'art',
						'migrate',
						'performance',
						'universal-access',
						'universal-access-alt',
						'tickets',
						'nametag',
						'clipboard',
						'heart',
						'megaphone',
						'schedule',
					)
				),
				'products' => array(
					'label' => esc_html__( 'Products', 'kkcp' ),
					'values' => array(
						'wordpress',
						'wordpress-alt',
						'pressthis',
						'update',
						'screenoptions',
						'info',
						'cart',
						'feedback',
						'cloud',
						'translation',
					)
				),
				'taxonomies' => array(
					'label' => esc_html__( 'Taxonomies', 'kkcp' ),
					'values' => array(
						'tag',
						'category',
					)
				),
				'widgets' => array(
					'label' => esc_html__( 'Widgets', 'kkcp' ),
					'values' => array(
						'archive',
						'tagcloud',
						'text',
					)
				),
				'notifications' => array(
					'label' => esc_html__( 'Notifications', 'kkcp' ),
					'values' => array(
						'yes',
						'no',
						'no-alt',
						'plus',
						'plus-alt',
						'minus',
						'dismiss',
						'marker',
						'star-filled',
						'star-half',
						'star-empty',
						'flag',
						'warning',
					)
				),
				'misc' => array(
					'label' => esc_html__( 'Misc', 'kkcp' ),
					'values' => array(
						'location',
						'location-alt',
						'vault',
						'shield',
						'shield-alt',
						'sos',
						'search',
						'slides',
						'analytics',
						'chart-pie',
						'chart-bar',
						'chart-line',
						'chart-area',
						'groups',
						'businessman',
						'id',
						'id-alt',
						'products',
						'awards',
						'forms',
						'testimonial',
						'portfolio',
						'book',
						'book-alt',
						'download',
						'upload',
						'backup',
						'clock',
						'lightbulb',
						'microphone',
						'desktop',
						'tablet',
						'smartphone',
						'phone',
						'index-card',
						'carrot',
						'building',
						'store',
						'album',
						'palmtree',
						'tickets-alt',
						'money',
						'smiley',
						'thumbs-up',
						'thumbs-down',
						'layout',
					)
				)
			);
		}
	}

endif;
