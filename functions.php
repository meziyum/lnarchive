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
	define( 'LNARCHIVE_BUILD_URI', untrailingslashit( get_template_directory_uri() ) . '/assets' );
}

if ( ! defined( 'LNARCHIVE_BUILD_JS_URI' ) ) {
	define( 'LNARCHIVE_BUILD_JS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/js' );
}

if ( ! defined( 'LNARCHIVE_BUILD_JS_DIR_PATH' ) ) {
	define( 'LNARCHIVE_BUILD_JS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/js' );
}

if ( ! defined( 'LNARCHIVE_BUILD_IMG_URI' ) ) {
	define( 'LNARCHIVE_BUILD_IMG_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/src/img' );
}

if ( ! defined( 'LNARCHIVE_BUILD_CSS_URI' ) ) {
	define( 'LNARCHIVE_BUILD_CSS_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/css' );
}

if ( ! defined( 'LNARCHIVE_BUILD_CSS_DIR_PATH' ) ) {
	define( 'LNARCHIVE_BUILD_CSS_DIR_PATH', untrailingslashit( get_template_directory() ) . '/assets/css' );
}

if ( ! defined( 'LNARCHIVE_BUILD_LIB_URI' ) ) {
	define( 'LNARCHIVE_BUILD_LIB_URI', untrailingslashit( get_template_directory_uri() ) . '/assets/library' );
}

require_once LNARCHIVE_DIR_PATH . '/inc/helpers/autoloader.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/general.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/post.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/user.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/novel.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/volume.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/taxonomy.php';
require_once LNARCHIVE_DIR_PATH . '/inc/helpers/sanitize.php';

use lnarchive\inc\lnarchive_theme;
function lnarchive_get_theme_instance() {
    lnarchive_theme::get_instance();
}
lnarchive_get_theme_instance();
?>