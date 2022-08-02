<?php
/**
 * Theme Functions
 * 
 * @package fusfan
 */

if ( ! defined( 'FUSFAN_DIR_PATH' ) ) {
	define( 'FUSFAN_DIR_PATH', untrailingslashit( get_template_directory() ) );
}

if ( ! defined( 'FUSFAN_DIR_URI' ) ) {
	define( 'FUSFAN_DIR_URI', untrailingslashit( get_template_directory_uri() ) );
}

if ( ! defined( 'FUSFAN_BUILD_URI' ) ) {
	define( 'FUSFAN_BUILD_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build' );
}

if ( ! defined( 'FUSFAN_BUILD_JS_URI' ) ) {
	define( 'FUSFAN_BUILD_JS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/js' );
}

if ( ! defined( 'FUSFAN_BUILD_JS_DIR_PATH' ) ) {
	define( 'FUSFAN_BUILD_JS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/build/js' );
}

if ( ! defined( 'FUSFAN_BUILD_IMG_URI' ) ) {
	define( 'FUSFAN_BUILD_IMG_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/src/img' );
}

if ( ! defined( 'FUSFAN_BUILD_CSS_URI' ) ) {
	define( 'FUSFAN_BUILD_CSS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/css' );
}

if ( ! defined( 'FUSFAN_BUILD_CSS_DIR_PATH' ) ) {
	define( 'FUSFAN_BUILD_CSS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/build/css' );
}

if ( ! defined( 'FUSFAN_BUILD_LIB_URI' ) ) {
	define( 'FUSFAN_BUILD_LIB_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/library' );
}

require_once FUSFAN_DIR_PATH . '/inc/helpers/autoloader.php';
require_once FUSFAN_DIR_PATH . '/inc/helpers/template-tags.php';

 //Calling the Main theme class
use fusfan\inc\fusfan_theme;
function fusfan_get_theme_instance() {
    fusfan_theme::get_instance();
}
fusfan_get_theme_instance();
?>