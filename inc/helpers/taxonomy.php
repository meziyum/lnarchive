<?php
/**
* 
* Taxonomy Helper Functions
* 
* @package LNarchive
*/
    function get_terms_except_default($tax) {
        $tax_name = $tax->name;
        $default_term = $tax->default_term ? $tax->default_term['name'] : 'None';
            
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

    function taxonomy_button_list($post_type, $tax_terms, $tax_name) {
        if(!empty($tax_terms)) {
            foreach( $tax_terms as $term) {
                $term_name = $term->name;
                ?>
                    <a class="<?php echo esc_attr($tax_name);?>-button anchor-button" href='<?php 
                        if($post_type =="novel") {
                            echo esc_attr(get_post_type_archive_link('novel')).'?'.$tax_name.'_filter'.'='.$term_name;
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