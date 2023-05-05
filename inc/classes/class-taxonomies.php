<?php
/**
 * Novel Post Type Taxonomies
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class taxonomies {

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [ $this, 'register_novel_volume_taxonomies']);
        add_action('save_post',[ $this, 'save_post_function']);
    }

    public function register_novel_volume_taxonomies() {

        register_taxonomy('publisher', ['novel'], array(
            'labels' => array(
                'name' => 'Publisher',
                'singular_name' => 'Publisher',
                'search_items' =>  'Search Publisher',
                'all_items' => 'All Publishers',
                'parent_item' => 'Parent Publisher',
                'parent_item_colon' => 'Parent Publisher: ',
                'name_field_description' => 'Name of the Publisher/Publishing Label',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'parent_field_description' => 'Assign a publisher if its a publishing label.',
                'desc_field_description' => 'A short informational description of the publisher/publishing label',
                'edit_item' => 'Edit Publisher',
                'view_item' => 'View Publisher',
                'update_item' => 'Update Publisher',
                'add_new_item' => 'Add New Publisher',
                'new_item_name' => 'New Publisher Name',
                'not_found' => 'No publishers found',
                'no_terms' => 'No publishers',
                'filter_by_item' => 'FIlter by Publisher',
                'most_used' => 'Most Used Publisher',
                'back_to_items' => 'Back to Publishers',
                'item_link' => 'Publisher Link',
                'item_link_description' => 'A link to the publisher',
                'menu_name' => 'Publisher',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'publisher',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'],
            'description' => 'The entity responsible for the distribution of the light novel and its associated labels',
            'show_admin_column' => true,
            'description' => 'A company or label publishing the novels',
            'update_count_callback' => '',
            'query_var' => 'publisher',
            'hierarchical' => true,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'Default term for when no publisher is assigned.'
            ),
            'rewrite' => array(
                'slug' => 'publisher',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));
        
        register_taxonomy('writer', ['novel'], array(
            'labels' => array(
                'name' => 'Author',
                'singular_name' => 'Author',
                'search_items' =>  'Search Author',
                'popular_items' => 'Popular Authors',
                'all_items' => 'All Authors',
                'name_field_description' => 'Name of the Author of the novel',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the Author',
                'edit_item' => 'Edit Author',
                'view_item' => 'View Auhtor',
                'update_item' => 'Update Author',
                'add_new_item' => 'Add New Author',
                'new_item_name' => 'New Author Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove author',
                'choose_from_most_used' => '',
                'not_found' => 'No author found',
                'no_terms' => 'No authors',
                'most_used' => '',
                'back_to_items' => 'Back to Authors',
                'item_link' => 'Author Link',
                'item_link_description' => 'A link to the author',
                'menu_name' => 'Author',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'writer',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => null,
            'description' => 'The author of the light novel in its source language.',
            'show_admin_column' => true,
            'description' => 'An author is the creator or originator of any written work',
            'update_count_callback' => '',
            'query_var' => 'writer',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'Unknown',
                'description' => 'Default term for when no author is assigned.'
            ),
            'rewrite' => array(
                'slug' => 'writer',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));

        register_taxonomy('illustrator', ['novel'], array(
            'labels' => array(
                'name' => 'Illustrator',
                'singular_name' => 'Illustrator',
                'search_items' =>  'Search Illustrator',
                'popular_items' => 'Popular Illustrators',
                'all_items' => 'All Illustrators',
                'name_field_description' => 'Name of the Illustrator of the novel',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the Illustrator',
                'edit_item' => 'Edit Illustrator',
                'view_item' => 'View Illustrator',
                'update_item' => 'Update Illustrator',
                'add_new_item' => 'Add New Illustrator',
                'new_item_name' => 'New Illustrator Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove illustrator',
                'choose_from_most_used' => '',
                'not_found' => 'No illustrator found',
                'no_terms' => 'No illustrators',
                'most_used' => '',
                'back_to_items' => 'Back to Illustrators',
                'item_link' => 'Illustrator Link',
                'item_link_description' => 'A link to the illustrator',
                'menu_name' => 'Illustrator',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'illustrator',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'],
            'description' => 'The artist responsible for the illustrations of the light novel.',
            'show_admin_column' => true,
            'description' => 'An illustrator is an artist who specializes in enhancing writing or elucidating concepts by providing a visual representation that corresponds to the content of the associated text or idea.',
            'update_count_callback' => '',
            'query_var' => 'illustrator',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'Default term for when no illustrator is assigned'
            ),
            'rewrite' => array(
                'slug' => 'illustrator',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));        

        register_taxonomy('language', ['novel'], array(
            'labels' => array(
                'name' => 'Language',
                'singular_name' => 'Language',
                'search_items' =>  'Search Language',
                'popular_items' => 'Popular Languages',
                'all_items' => 'All Languages',
                'name_field_description' => 'Name of the language of the novel',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the language',
                'edit_item' => 'Edit Language',
                'view_item' => 'View Language',
                'update_item' => 'Update Language',
                'add_new_item' => 'Add New Language',
                'new_item_name' => 'New Language Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove language',
                'choose_from_most_used' => '',
                'not_found' => 'No language found',
                'no_terms' => 'No languages',
                'most_used' => '',
                'back_to_items' => 'Back to Languages',
                'item_link' => 'Language Link',
                'item_link_description' => 'A link to the language',
                'menu_name' => 'Language',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'language',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'],
            'show_admin_column' => true,
            'description' => 'The source language of the novel from which its translated from.',
            'update_count_callback' => '',
            'query_var' => 'language',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'The language of the novel is not known.'
            ),
            'rewrite' => array(
                'slug' => 'language',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));        

        register_taxonomy('novel_status', ['novel'], array(
            'labels' => array(
                'name' => 'Status',
                'singular_name' => 'Status',
                'search_items' =>  'Search Statsu type',
                'popular_items' => 'Popular Status types',
                'all_items' => 'All Status types',
                'name_field_description' => 'Name of the Status type',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the status',
                'edit_item' => 'Edit Status',
                'view_item' => 'View Status',
                'update_item' => 'Update Status',
                'add_new_item' => 'Add New Status',
                'new_item_name' => 'New Status Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove status type',
                'choose_from_most_used' => '',
                'not_found' => 'No status types found',
                'no_terms' => 'No status types',
                'most_used' => '',
                'back_to_items' => 'Back to Status',
                'item_link' => 'Status Link',
                'item_link_description' => 'A link to the status',
                'menu_name' => 'Status',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'novel_status',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'],
            'show_admin_column' => true,
            'description' => 'The current publishing status of the series.',
            'update_count_callback' => '',
            'query_var' => 'status',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'The current status of the novel is not known.'
            ),
            'rewrite' => array(
                'slug' => 'status',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));

        register_taxonomy('format', ['volume'], array(
            'labels' => array(
                'name' => 'Format',
                'singular_name' => 'Format',
                'search_items' =>  'Search Formats',
                'popular_items' => 'Popular Formats',
                'all_items' => 'All Formats',
                'name_field_description' => 'Name of the Format',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the format',
                'edit_item' => 'Edit Format',
                'view_item' => 'View Format',
                'update_item' => 'Update Format',
                'add_new_item' => 'Add New Format',
                'new_item_name' => 'New Format Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove format',
                'choose_from_most_used' => '',
                'not_found' => 'No format found',
                'no_terms' => 'No formats',
                'most_used' => '',
                'back_to_items' => 'Back to Formats',
                'item_link' => 'Format Link',
                'item_link_description' => 'A link to the format',
                'menu_name' => 'Format',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'format',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => false,
            'description' => 'The formats in which the novels are published in',
            'show_admin_column' => true,
            'update_count_callback' => '',
            'query_var' => 'format',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'None',
                'slug' => 'none',
                'description' => 'Default term when no formats are assigned.'
            ),
            'rewrite' => array(
                'slug' => 'format',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));        

        register_taxonomy('translator', ['volume'], array(
            'labels' => array(
                'name' => 'Translator',
                'singular_name' => 'Translator',
                'search_items' =>  'Search Translator',
                'popular_items' => 'Popular Translators',
                'all_items' => 'All Translators',
                'name_field_description' => 'Name of the Translator of the novel',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the Translator',
                'edit_item' => 'Edit Translator',
                'view_item' => 'View Translator',
                'update_item' => 'Update Translator',
                'add_new_item' => 'Add New Translator',
                'new_item_name' => 'New Translator Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove translator',
                'choose_from_most_used' => '',
                'not_found' => 'No translator found',
                'no_terms' => 'No translators',
                'most_used' => '',
                'back_to_items' => 'Back to Translators',
                'item_link' => 'Translator Link',
                'item_link_description' => 'A link to the translator',
                'menu_name' => 'Translator',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'translator',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => null,
            'description' => '',
            'show_admin_column' => true,
            'description' => 'A person responsible for translating the light novel from its source language',
            'update_count_callback' => '',
            'query_var' => 'translator',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'Default term for when no translator is assigned.'
            ),
            'rewrite' => array(
                'slug' => 'translator',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));
        
        register_taxonomy('narrator', ['volume'], array(
            'labels' => array(
                'name' => 'Narrator',
                'singular_name' => 'Narrator',
                'search_items' =>  'Search Narrators',
                'popular_items' => 'Popular Narrators',
                'all_items' => 'All Narrators',
                'name_field_description' => 'Name of the Narrator of the audiobook',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'desc_field_description' => 'Information about the Narrator',
                'edit_item' => 'Edit Narrator',
                'view_item' => 'View Narrator',
                'update_item' => 'Update Narrator',
                'add_new_item' => 'Add New Narrator',
                'new_item_name' => 'New Narrator Name',
                'separate_items_with_commas' => '',
                'add_or_remove_items' => 'Add or remove narrator',
                'choose_from_most_used' => '',
                'not_found' => 'No narrator found',
                'no_terms' => 'No narrators',
                'most_used' => '',
                'back_to_items' => 'Back to Narrators',
                'item_link' => 'Narrators Link',
                'item_link_description' => 'A link to the narrator',
                'menu_name' => 'Narrator',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'narrator',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => [$this, 'taxonomies_datalist_display'],
            'description' => '',
            'show_admin_column' => true,
            'description' => 'A person responsible for translating the light novel from its source language',
            'update_count_callback' => '',
            'query_var' => 'narrator',
            'hierarchical' => false,
            'default_term' => array(
                'name' => 'Unknown',
                'slug' => 'unknown',
                'description' => 'Default term for when no narrator is assigned.'
            ),
            'rewrite' => array(
                'slug' => 'narrator',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));        

        register_taxonomy('genre', ['novel'], array(
            'labels' => array(
                'name' => 'Genre',
                'singular_name' => 'Genre',
                'search_items' => 'Search Genre',
                'all_items' => 'All Genres',
                'parent_item' => 'Parent Genre',
                'parent_item_colon' => 'Parent Genre: ',
                'name_field_description' => 'Name of the Genre',
                'slug_field_description' => 'The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.',
                'parent_field_description' => 'Assign a parent genre if its a sub-genre.',
                'desc_field_description' => 'A short informational description of the genre',
                'edit_item' => 'Edit Genre',
                'view_item' => 'View Genre',
                'update_item' => 'Update Genre',
                'add_new_item' => 'Add New Genre',
                'new_item_name' => 'New Genre Name',
                'not_found' => 'No genres found',
                'no_terms' => 'No genres',
                'filter_by_item' => 'FIlter by Genre',
                'most_used' => 'Most Used Genre',
                'back_to_items' => 'Back to Genres',
                'item_link' => 'Genre Link',
                'item_link_description' => 'A link to a genre',
                'menu_name' => 'Genre',
            ),
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => 'genre',
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'meta_box_cb' => null,
            'show_admin_column' => true,
            'description' => 'A category of literary composition characterized by a particular style, form, or content',
            'update_count_callback' => '',
            'query_var' => 'genre',
            'hierarchical' => true,
            'default_term' => array(
                'name' => 'None',
                'slug' => 'none',
                'description' => 'Default term for when no genre is assigned.'
            ),
            'rewrite' => array(
                'slug' => 'genre',
                'with_front' => false,
                'hierarchical' => false,
            ),
            'capabilities' => array(
                'manage_terms' => 'manage_categories',
                'edit_terms' => 'manage_categories',
                'delete_terms' => 'manage_categories',
                'assign_terms' => 'manage_categories',
            ),
            'sort' => false,
            '_builtin' => false
        ));        

        unregister_taxonomy_for_object_type('post_tag', 'volume');
    }

    public function save_post_function($post_id) {
        $default_tag = "None";
        $tags = get_the_tags();
    
        if(empty($tags))
            wp_set_post_tags( $post_id, $default_tag, true );
        else if( count($tags)>1){
            foreach ($tags as $tag) {
                if ($tag->name == $default_tag) {
                    wp_remove_object_terms($post_id, $default_tag, 'post_tag');
                }
            }
        }
    
        $args = array(
            'public'   => true,
            '_builtin' => false
        );
        $taxonomies = get_taxonomies( $args, 'objects');
    
        foreach( $taxonomies as $tax ) {
            $tax_name = $tax->name;
            $terms = get_the_terms( $post_id, $tax_name);
    
            if( !empty($terms) && count($terms)>1){
                foreach( $terms as $term) {
                    $term_name = $term->name;

                    if( $term_name == $tax->default_term['name']){
                        wp_remove_object_terms($post_id, $term_name, $tax_name);
                    }
                }
            }
        }
    }

    function taxonomies_datalist_display( $post, $box ) {

        $defaults = array();

        if( !isset($box['args']) || !is_array($box['args'])){
            $args=array();
        }
        else{
            $args = $box['args'];
        }

        extract(wp_parse_args($args, $defaults), EXTR_SKIP);
        $tax = get_taxonomy($taxonomy);
        ?>
            <div id="taxonomy-<?php echo $taxonomy;?>" class="selectdiv">
                <?php
                    if (current_user_can($tax->cap->edit_terms)):
                            ?>
                                <input list="tax_list_<?php echo $taxonomy;?>" 
                                name="<?php echo "tax_input[$taxonomy][]";?>" 
                                id="<?php echo "tax_input[$taxonomy][]";?>" 
                                class="widefat" 
                                autocomplete="on"
                                multiple
                                <?php
                                    $value = get_the_terms( $post, $taxonomy );

                                    if( !empty( $value )){
                                        echo 'value="'.$value[0]->name.'"';
                                    }
                                ?>
                                >
                                <datalist name="tax_list_<?php echo $taxonomy;?>" id="tax_list_<?php echo $taxonomy;?>">
                                    <?php
                                        $terms = get_terms($taxonomy, array('hide_empty' => false));

                                        foreach( $terms as $term){
                                            ?>
                                                <option value="<?php echo $term->name ;?>">
                                            <?php
                                        }
                                    ?>
                                </datalist>
                            <?php
                    endif;
                ?>
            </div>
        <?php
    }
}
?>