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
        add_action( 'rest_api_init', [$this, 'register_routes']);
    }

    public function register_rest_fields() {
        register_rest_field( 'post', 'categoryList', array(
            'get_callback' => [$this, 'get_category_list'],
        ));
    }

    public function register_routes() {
        register_rest_route( 'lnarchive/v1', 'post_filters', array(
            'methods' => 'GET',
            'callback' => [ $this, 'get_post_filters'],
            'permission_callback' => function(){
                return true;
            },
        ));
    }

    public function get_post_filters() {

        $filter_taxonomies = array('category');
        $response = array();

        foreach( $filter_taxonomies as $tax){

            $terms = get_terms( $tax, array(
                'hide_empty' => true,
            ));
            
            $terms_list=array();
            foreach( $terms as $term){
                array_push($terms_list, array(
                    'term_id' => $term->term_id,
                    'term_name' => $term->name,  
                ));
            }

            $taxObj = get_taxonomy($tax);

            array_push($response, array(
                'taxQueryName' => $taxObj->rest_base,
                'taxLabel' => $taxObj->label,
                'list' => $terms_list,
            ));
        }

        return $response;
    }

    public function get_category_list() {
        $categories = get_the_category();
        
        $response = array_map(function($category) {
            return $category->name;
        }, $categories);
        
        $response = array_filter($response, function($category) {
            return $category !== "None";
        });
        
        return array_values($response);
    }
}
?>