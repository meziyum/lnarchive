<?php
/**
 * Posts and Custom Posts Filter Admin
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class post_filter {

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action('restrict_manage_posts', [$this, 'add_taxonomy_filters']);
        add_action('restrict_manage_posts',[ $this, 'add_series_filter_to_posts_admin' ]);
        add_action('restrict_manage_posts',[ $this, 'add_manager_filter_to_posts_admin' ]);

        add_action('pre_get_posts',[ $this, 'add_taxonomy_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_metadata_filter_to_posts_query' ]);
        add_action('pre_get_posts',[ $this, 'add_manager_filter_to_posts_query' ]);
    }

    function add_taxonomy_filters($post_type) {
        $taxs = array();

        if ($post_type == 'novel' ) {
            $taxs = array('publisher', 'genre', 'post_tag', 'writer', 'illustrator', 'novel_status', 'language');
        }
        else if ($post_type == 'volume' ) {
            $taxs = array('format', 'translator', 'narrator');
        }

        foreach ($taxs as $tax ) {
            $this->filter_search_dropdown($tax);
        }
    }

    function filter_search_dropdown($taxonomy) {

        $terms = get_terms( $taxonomy, array(
            'hide_empty' => true,
        ) );

        ?>
            <input  list="<?php echo esc_attr($taxonomy);?>_filter_list" 
                    name="<?php echo esc_attr($taxonomy);?>_filter" 
                    id="<?php echo esc_attr($taxonomy);?>_filter" 
                    autocomplete="on"
                    <?php
                        if (!empty($_GET[esc_attr($taxonomy).'_filter'])) {
                            echo 'value="'.$_GET[esc_attr($taxonomy).'_filter'].'"'; //Assign a value
                        } else {
                            echo 'placeholder="All '.esc_attr(get_taxonomy_labels(get_taxonomy($taxonomy))->name).'" ';
                        }
                    ?>
            >

            <datalist name="<?php echo esc_attr($taxonomy);?>_filter_list" id="<?php echo esc_attr($taxonomy);?>_filter_list">
                <option value="All <?php echo esc_attr(get_taxonomy_labels(get_taxonomy($taxonomy))->name);?>">
                <?php
                    foreach ($terms as $term) {
                        ?>
                            <option value="<?php echo esc_attr($term->name) ;?>">
                            </option>
                        <?php
                    }
                ?>
            </datalist>
        <?php
    }

    function add_taxonomy_filter_to_posts_query($query) {

        global $post_type, $pagenow;

        if ( $pagenow == 'edit.php') {
            if ($post_type == 'novel') {
                $taxs = array('publisher', 'genre', 'post_tag', 'writer', 'illustrator', 'novel_status', 'language');
                $this->filter_by_taxonomy( $taxs, $query );
            }
            else if ($post_type == 'volume' ) {
                $taxs = array('format', 'translator', 'narrator');
                $this->filter_by_taxonomy( $taxs, $query );
            }
        }
    }

    function filter_by_taxonomy(array $taxs, $query) {

        $filters = array();

        foreach ( $taxs as $tax ) {
            if (isset($_GET[$tax.'_filter']) && term_exists( $_GET[$tax.'_filter'], $tax )) {
                array_push(
                    $filters,
                    array(
                        'taxonomy' => $tax,
                        'field' => 'name',
                        'terms' => sanitize_text_field($_GET[$tax.'_filter']),
                    ),
                );
            }

            if(!empty($filters)) {
                $query->query_vars['tax_query'] = array(
                    'relation' => 'AND',
                        $filters,
                );
            }
        }
    }

    function add_series_filter_to_posts_admin($post_type) {

        if ($post_type == 'volume') {

            $series_args = array(
                'numberposts' => -1, 
                'post_type' => 'novel',
            );

            $series = get_posts( $series_args );
            ?>
            <input list="series_filter" 
            name="series_choice" 
            id="series_choice" 
            autocomplete="on" 
            <?php
                if (!empty($_GET['series_choice'])) {
                    echo 'value="'.esc_attr($_GET['series_choice']).'"';
                } else {
                    echo 'placeholder="All Series"';
                }        
            ?>
            >
            <datalist name="series_filter" id="series_filter">
                <option value="All Series">
                <?php
                    foreach ( $series as $novel) {
                        ?>
                            <option value="<?php echo esc_attr($novel->post_title);?>">
                        <?php
                    }
                ?>
            </datalist>
            <?php
        }        
    }

    function add_metadata_filter_to_posts_query($query) {

        global $post_type, $pagenow, $wpdb;

        if ($pagenow == 'edit.php') {
            if ($post_type == 'volume') {

                $novel_id = null;

                if (isset($_GET['series_choice'])) {
                    $series_choice = sanitize_text_field($_GET['series_choice']);
        
                    $novel_id = $wpdb->get_var(
                        $wpdb->prepare(
                            "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type = 'novel'",
                            $series_choice
                        )
                    );
                }
        
                if ($novel_id) {
                    $query->query_vars['meta_query'] = array(
                        array(
                            'key' => 'series_value',
                            'value' => $novel_id,
                        ),
                    );
                } else {
                    $query->query_vars['meta_query'] = array();
                }
            }
        }
    }

    function add_manager_filter_to_posts_admin() {

        $user_args = array(
            'show_option_all'   => 'All Managers',
            'show_option_none'  => '',
            'option_none_value'       => -1,
            'hide_if_only_one_author' => '',
            'orderby'           => 'display_name',
            'order'             => 'ASC',
            'include'           => '',
            'exclude'           => '',
            'multi'             => 0,
            'show'              => 'display_name',
            'echo'              => 1,
            'name'              => 'manager_admin_filter',
            'class'             => '',
            'id'                => '',
            'blog_id'                 => get_current_blog_id(),
            'role'                    => array(),
            'role__in'                => array(),
            'role__not_in'            => array(),
            'capability'               => ['publish_posts'],
            'capability__in'          => array(),
            'capability__not_in'      => array(),
            'selected'                => 0,
            'include_selected'        => false
        );

        if (isset($_GET['manager_admin_filter'])) {
            $user_args['selected'] = (int)sanitize_text_field($_GET['manager_admin_filter']);
        }

        wp_dropdown_users($user_args);
    }

    function add_manager_filter_to_posts_query($query) {

        global $pagenow;

        if ($pagenow == 'edit.php') {
            if (isset($_GET['manager_admin_filter'])) {
                $author_id = (int)sanitize_text_field($_GET['manager_admin_filter']);
                if ($author_id != 0) {
                    $query->query_vars['author'] = $author_id;
                }
            }
        }
    }
}
?>
