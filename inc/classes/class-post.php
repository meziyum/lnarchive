<?php
/**
 * Post Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class post {

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'rest_api_init', [$this, 'register_rest_fields']);
    }

    public function register_rest_fields(){
        register_rest_field( 'post', 'categoryList', array(
            'get_callback' => [$this, 'get_category_list'],
        ));
    }

    public function get_category_list() {

        $categories = get_the_category();
        $response = array();

        foreach( $categories as $category){
            if( $category->name == "None")
                continue;
            array_push($response, $category->name);
        }
        return $response;
    }
}
?>