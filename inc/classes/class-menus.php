<?php
/**
 * Theme Menus
 * 
 * @package LNarchive
 * 
 */
namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class menus {
    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('init', [ $this, 'register_menus']);
    }

    public function register_menus() {
        register_nav_menus(
            array(
                'fusfan_primary'    => ( 'Primary Menu'),
                'footer_primary'    => ( 'Footer Primary Menu'),
                'footer_secondary'  => ( 'Footer Secondary Menu'),
                'footer_tertiary'   => ( 'Footer Tertiary Menu'),
            )
        );
    }

    public function get_menu_id( $location ) {
        $locations = get_nav_menu_locations();
        $menu_id = $locations[$location];
        return ! empty( $menu_id) ? $menu_id : '';
    }

    public function get_child_menu_items( $menu_array, $parent_id) {

        $child_menus =[];

        if( ! empty( $menu_array) && is_array( $menu_array)) {
                foreach( $menu_array as $menu) {
                    if( intval( $menu->menu_item_parent) === $parent_id) {
                        array_push( $child_menus, $menu);
                    }
                }
        }

        return $child_menus;
    }
}
?>