<?php
/**
* 
* Taxonomy Helper Functions
* 
* @package LNarchive
*/
    function get_terms_except_default($tax) {
        $tax_name = $tax->name;
        $default_term = get_tax_default_term($tax);
            
        $terms = get_terms($tax_name, array(
            'hide_empty' => true,
        ));
        
        $terms_list=array();
        foreach($terms as $term) {
            $term_name = $term->name;

            if ($term_name != $default_term)  {
                array_push($terms_list, array(
                    'term_id' => $term->term_id,
                    'term_name' => $term_name,
                ));
            }
        }
        
        return $terms_list;
    }

    function get_tax_default_term($tax) {
        if (!is_object($tax)) {
            $tax = get_taxonomy($tax);
        }

        return $tax->default_term ? $tax->default_term['name'] : 'None';
    }

    function get_public_taxonomies() {
        $taxonomies = get_taxonomies(array('_builtin' => false,), 'names');
        array_push($taxonomies, 'post_tag', 'category');
        return $taxonomies;
    }

    function taxonomy_button_list($post_type, $tax_terms, $tax_name) {
        if(!empty($tax_terms)) {
            foreach( $tax_terms as $term) {
                $term_name = $term->name;
                ?>
                    <a class="<?php echo esc_attr($tax_name);?>-button anchor-button" href='<?php 
                        if($post_type =="novel") {
                            echo esc_attr(get_post_type_archive_link('novel')).'?'.$tax_name.'_filter'.'='.$term->term_id;
                        } else {
                            echo esc_attr(get_term_link($term));
                        } 
                        ?>'>
                        <?php echo esc_html($term_name)?>
                    </a>
                <?php
            }
        }
    }
?>