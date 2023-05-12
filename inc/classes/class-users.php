<?php
/**
 * Users Main Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class users{

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'rest_api_init', [$this, 'custom_endpoints']);
    }

    private function custom_endpoints(){
        register_rest_route( 'lnarchive/v1', 'current_user/(?P<object_id>\d+)', array(
            'methods' => 'GET',
            'callback' => [ $this, 'current_user_data'],
            'permission_callback' => function(){
                return is_user_logged_in();
            },
        ));
    }
    
    private function current_user_data( $request ){
        global $wpdb;
        $table_name = $wpdb->prefix . 'user_ratings';
        $object_id = $request['object_id'];
        $user_id = get_current_user_id();
        
        $return = array (
            'user_id' => $user_id,
            'user_rating' => $wpdb->get_var("SELECT rating FROM $table_name WHERE object_id=".$object_id." AND user_id=".$user_id),
        );
        return $return;
    }
}
?>