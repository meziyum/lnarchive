<?php
/**
 * Similar Novels Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
use WP_Query;

class similar_novels {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('save_post_novel', [$this, 'update_similar_for_all_terms'], 10, 3);
    }

    function update_similar_for_all_terms($post_id, $post, $update) {
        $this->update_similar_novels($post_id);
        $taxonomies = get_public_taxonomies();
        $query = $this->generate_query_for_novel_terms($post_id, $taxonomies);

        $args = array(
            'post_type' => 'novel',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => $query,
        );
        $nquery = new WP_Query($args);
        $novels = $nquery->posts;
        error_log(print_r($args,true));
        error_log(print_r($novels,true));

        foreach($novels as $novel_id) {
            $this->update_similar_novels($novel_id);
        }
        wp_reset_query();
    }

    function update_similar_novels($novel_id) {
        error_log('Update Similar Novels for '.$novel_id);
        $taxonomies = get_public_taxonomies();

        $novel_terms = array();
        foreach ($taxonomies as $tax) {
            $novel_terms[$tax] = wp_get_post_terms($novel_id, $tax, array('fields' => 'ids'));
        }

        $args = array(
            'post_type' => 'novel',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'post__not_in' => array($novel_id),
        );
        $nquery = new WP_Query($args);
        $novels = $nquery->posts;
        $simlar_novels = array();

        foreach ($novels as $target_novel_id) {
            $simlarity=0;

            foreach ($taxonomies as $tax) {
                if (!get_option('tax-weightage-'.$tax)) {
                    continue;
                }
                
                $target_terms = wp_get_post_terms($target_novel_id, $tax, array('fields' => 'ids'));

                foreach($target_terms as $target_term_id) {
                    if (in_array($target_term_id, $novel_terms[$tax])) {
                        $simlarity += (int)get_term_meta($target_term_id, 'weightage', true);
                    }
                }
            }
            array_push($simlar_novels, array( 'id' => $target_novel_id, 'similarity' => $simlarity));
        }
        wp_reset_query();

        usort($simlar_novels, [$this, 'compare_weightage']);
        $top6 = array_slice($simlar_novels, 0, 6);
        $similar_novel_ids = array_map([$this, 'get_id_from_similar_novel'], $top6);
        $current_similar_novels = get_post_meta($novel_id, 'similar_novels', true);
        
        $result = update_post_meta($novel_id, 'similar_novels', $similar_novel_ids);
        /*error_log(print_r($similar_novel_ids, true));
        error_log(print_r($result, true));
        error_log(print_r($current_similar_novels, true));*/
    }

    function generate_query_for_novel_terms($novel_id, $taxonomies){
        $query=array();
        $query['relation'] = 'OR';
        foreach ($taxonomies as $tax) {
            if (!get_option('tax-weightage-'.$tax)) {
                continue;
            }

            $terms = wp_get_post_terms($novel_id, $tax);
            $query_terms =array();
            foreach($terms as $term) {
                $term_name = $term->name;
                $default_term = get_tax_default_term($tax);

                if ($term_name == $default_term) {
                    continue;
                }
                array_push(
                    $query_terms,
                    $term_name
                );
            }

            if (count($query_terms) === 0) {
                continue;
            }

            array_push(
                $query,
                array(
                    'taxonomy' => $tax,
                    'field' => 'name',
                    'terms' => $query_terms
                ),
            );
        }
        return $query;
    }

    function compare_weightage($first, $second) {
        if ($first['similarity'] === $second['similarity']) {
            return 0;
        }
        return ($first['similarity'] < $second['similarity']) ? 1 : -1;
    }

    function get_id_from_similar_novel($simlar_novel){
        return $simlar_novel['id'];
    }
}
?>