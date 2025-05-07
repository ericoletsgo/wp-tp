<?php
/**
 * Portfolio functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Portfolio
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function portfolio_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Portfolio, use a find and replace
		* to change 'portfolio' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'portfolio', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'portfolio' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'portfolio_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'portfolio_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function portfolio_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'portfolio_content_width', 640 );
}
add_action( 'after_setup_theme', 'portfolio_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function portfolio_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'portfolio' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'portfolio' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'portfolio_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function portfolio_scripts() {
	wp_enqueue_style( 'portfolio-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'portfolio-style', 'rtl', 'replace' );

	wp_enqueue_script( 'portfolio-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	wp_enqueue_style( 'swiper-css', get_template_directory_uri() . '/assets/swiper/swiper-bundle.min.css' );
	wp_enqueue_script('jquery');
	wp_enqueue_script('swiper-js', get_template_directory_uri() . '/assets/swiper/swiper-bundle.min.js', array('jquery'), false, true);
	wp_enqueue_script('portfolio-custom', get_template_directory_uri() . '/js/custom-scripts.js', array('jquery', 'swiper-js'), _S_VERSION, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'portfolio_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function ericportfoliotheme_customize_register($wp_customize) {
	$wp_customize->add_section('ep_hero_section', array(
		'title'    => __('Homepage: Hero Section', 'ericportfoliotheme'),
		'priority' => 110,
	));

	$wp_customize->add_setting('ep_hero_heading', array(
		'default' => '[Your Name]',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('ep_hero_heading', array(
		'label' => __('Hero Heading', 'ericportfoliotheme'),
		'section' => 'ep_hero_section',
		'type' => 'text',
	));

	$wp_customize->add_setting('ep_hero_subheading', array(
		'default' => '[Your 2-line Subheading/Description]',
		'sanitize_callback' => 'wp_kses_post'
	));
	$wp_customize->add_control('ep_hero_subheading', array(
		'label' => __('Hero Subheading', 'ericportfoliotheme'),
		'section' => 'ep_hero_section',
		'type' => 'textarea',
	));

	$wp_customize->add_setting('ep_hero_cv_button_text', array(
		'default' => 'View My CV',
		'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('ep_hero_cv_button_text', array(
		'label' => __('CV Button Text', 'ericportfoliotheme'),
		'section' => 'ep_hero_section',
		'type' => 'text',
	));

	// CV Button URL
	$wp_customize->add_setting('ep_hero_cv_button_url', array(
		'default' => '#',
		'sanitize_callback' => 'esc_url_raw'
	));
	$wp_customize->add_control('ep_hero_cv_button_url', array(
		'label' => __('CV Button URL', 'ericportfoliotheme'),
		'section' => 'ep_hero_section',
		'type' => 'url',
	));
}
add_action('customize_register', 'ericportfoliotheme_customize_register');

