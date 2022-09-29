<?php
/**
 * Theme Functions
 * 
 * @package LNarchive
 */

if ( ! defined( 'LNARCHIVE_DIR_PATH' ) ) {
	define( 'LNARCHIVE_DIR_PATH', untrailingslashit( get_template_directory() ) );
}

if ( ! defined( 'LNARCHIVE_DIR_URI' ) ) {
	define( 'LNARCHIVE_DIR_URI', untrailingslashit( get_template_directory_uri() ) );
}

if ( ! defined( 'LNARCHIVE_BUILD_URI' ) ) {
	define( 'LNARCHIVE_BUILD_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build' );
}

if ( ! defined( 'LNARCHIVE_BUILD_JS_URI' ) ) {
	define( 'LNARCHIVE_BUILD_JS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/js' );
}

if ( ! defined( 'LNARCHIVE_BUILD_JS_DIR_PATH' ) ) {
	define( 'LNARCHIVE_BUILD_JS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/build/js' );
}

if ( ! defined( 'LNARCHIVE_BUILD_IMG_URI' ) ) {
	define( 'LNARCHIVE_BUILD_IMG_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/src/img' );
}

if ( ! defined( 'LNARCHIVE_BUILD_CSS_URI' ) ) {
	define( 'LNARCHIVE_BUILD_CSS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/css' );
}

if ( ! defined( 'LNARCHIVE_BUILD_CSS_DIR_PATH' ) ) {
	define( 'LNARCHIVE_BUILD_CSS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/build/css' );
}

if ( ! defined( 'LNARCHIVE_BUILD_LIB_URI' ) ) {
	define( 'LNARCHIVE_BUILD_LIB_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/build/library' );
}

//Get the Helper Functions
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/autoloader.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/post-type-taxonomies.php';

 //Calling the Main theme class
use lnarchive\inc\lnarchive_theme;
function lnarchive_get_theme_instance() {
    lnarchive_theme::get_instance();
}
lnarchive_get_theme_instance();
?>