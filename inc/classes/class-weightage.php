<?php
/**
 * Taxonomy Weightage Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
use WP_Query;

class weightage {

    use Singleton;

    protected function __construct(){
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('init', [$this, 'register_weightage']);
        add_action('set_object_terms', [$this, 'update_weightage_on_term_assign'], 10, 4);

        $taxonomies = get_taxonomies(array('_builtin' => false,), 'names');
        array_push($taxonomies, 'post_tag', 'category');

        foreach ($taxonomies as $taxonomy) {
            add_action( 'edited_'.$taxonomy, [$this, 'update_weightage'], 10, 1);
        }
    }

    private function register_weightage() {
        $taxonomies = get_taxonomies(array('_builtin' => false,), 'names');
        array_push($taxonomies, 'post_tag', 'category');
      
        foreach ($taxonomies as $taxonomy) {
          register_meta( 'term', 'weightage', array(
            'object_subtype' => $taxonomy,
            'type' => 'number',
            'single' => true,
            'show_in_rest' => true,
          ) );
        }
    }

    private function update_weightage_on_term_assign($object_id, $terms, $tt_ids, $taxonomy) {
        if(get_option('tax-weightage-'.$taxonomy) =='1') {
            foreach($terms as $term) {
                $this->update_weightage($term);
            }
        }
    }

    public function update_weightage($term_id) {
        $term = get_term($term_id);

        $query_args = array(
            'post_type' => 'any',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => $term->taxonomy,
                    'field' => 'term_id',
                    'terms' => $term_id,
                )
            )
        );
        $query = new WP_Query($query_args);
        $count = $query->found_posts;
        $value = 100000/$count;

        if(get_term_meta($term_id, 'weightage') != $value) {
            update_term_meta($term_id, 'weightage', $value);
        }
    }
}
?>