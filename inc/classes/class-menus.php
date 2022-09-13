<?php
/**
 * Theme Menus
 * 
 * @package LNarchive
 * 
 */
namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class menus{ //Assests Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }

    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action('init', [ $this, 'register_menus']);
    }

    //Register Menus
    public function register_menus() {
        register_nav_menus( //Locations List
            array(
                'fusfan_primary'    => ( 'Primary Menu'),
            )
        );
    }

    //Get Menu Id from location
    public function get_menu_id( $location ) {       
        //Get all the locations
        $locations = get_nav_menu_locations();
        //Get object id by location
        $menu_id = $locations[$location];
        //Return the menu id if not empty
        return ! empty( $menu_id) ? $menu_id : '';
    }

    //Get Sub Menu Items
    public function get_child_menu_items( $menu_array, $parent_id) { // $menu_array = array with all menu items $parent_id = id of the menu_item for which to find the submenus

        $child_menus =[]; //Define empty array

        if( ! empty( $menu_array) && is_array( $menu_array)) { //Check if the given item is array and is not empty
                foreach( $menu_array as $menu) { //Run loop for each item of the array
                    if( intval( $menu->menu_item_parent) === $parent_id) { //if the parent of the child is $parent_id
                        array_push( $child_menus, $menu); //Add the submenu menu item to the array
                    }
                }
        }

        return $child_menus;
    }
}
?>