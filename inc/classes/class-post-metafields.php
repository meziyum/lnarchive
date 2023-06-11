<?php
/**
 * Post Types Meta Fields
 * 
 * @package LNarchive
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;
use WP_Error;

class post_metafields {

    use Singleton;

    private $meta_title_length;
    private $meta_desc_length;

    protected function __construct() {
         $this->set_hooks();
         $this->meta_title_length=get_option('seo-title-length');
         $this->meta_desc_length=get_option('seo-desc-length');
    }

    protected function set_hooks() {
        add_action( 'add_meta_boxes', [ $this, 'add_seo_meta'] );
        add_action( 'add_meta_boxes', [ $this, 'novel_metaboxes_add'] );
        add_action( 'save_post', [ $this, 'save_seo_meta_title'] );
        add_action( 'save_post', [ $this, 'save_seo_meta_desc'] );
        add_action( 'save_post', [$this,'save_published_date'] );
        add_action( 'save_post', [$this,'save_series'] );
        add_action( 'save_post', [$this,'save_alternate_names'] );
        add_action( 'save_post', [$this,'save_isbn_meta'] );
    }

    function novel_metaboxes_add() {
        
        add_meta_box(
            'alternate_names',
            'Alternate Names',
            [ $this, 'alternate_names_metabox_callback'],
            'novel',
            'side',
            'default',
            null,
        );
           
        add_meta_box(
            'published_date',
            'Publication Date',
            [ $this, 'published_date_metabox_callback'],
            'volume',
            'side',
            'default',
            null,
        );

        add_meta_box(
            'series',
            'Series',
            [ $this, 'series_metabox_callback'],
            ['volume', 'post'],
            'side',
            'default',
            null,
        );

        add_meta_box(
            'isbn_meta',
            'ISBN-13',
            [ $this, 'isbn_meta_callback'],
            ['volume'],
            'side',
            'default',
            null,
        );
    }

    function isbn_meta_callback( $post ) {

        wp_nonce_field( 'isbn_nonce_action', 'isbn_nonce');

        ?>
        <div class="isbn-div">
            <?php

                $formats = get_terms('format', array(
                    'hide_empty' => false,
                ));
                ?>
                <table> <!-- Table -->
                    <?php
                    foreach( $formats as $format) {

                        $format_name = $format->name;

                        if( $format_name == "None") {
                            continue;
                        }
                        ?>
                            <tr class="form-field isbn-<?php echo $format_name;?>">
                                <th>
                                    <label for="isbn-<?php echo $format_name;?>"><?php echo $format_name;?></label>
                                </th>
                                <td>
                                    <input name="isbn-<?php echo $format_name;?>" id="isbn-<?php echo $format_name;?>" value="<?php echo get_post_meta( $post->ID, 'isbn_'.$format_name.'_value', true );?>" maxlength="14"/>
                                </td>
                            </tr>
                        <?php
                    }
                    ?>
                </table>
        </div>
        <?php
    }

    function save_isbn_meta( $post_id) {

        if ( ! isset( $_POST['isbn_nonce'] ) || ! wp_verify_nonce( $_POST['isbn_nonce'], 'isbn_nonce_action'))
            return;
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( ! current_user_can( 'edit_post', $post_id ) )
            return;

        $formats = get_terms('format', array(
            'hide_empty' => false,
        ));

        foreach( $formats as $format) {

            $format_name = $format->name;

            if ($format_name == "None" || empty($_POST['isbn-'.$format_name]))
                continue;

            update_post_meta(
                $post_id,
                'isbn_'.$format_name.'_value',
                sanitize_text_field($_POST['isbn-'.$format_name])
            );

        }
    }

    function alternate_names_metabox_callback ( $post ) {

        wp_nonce_field( 'alternate_names_nonce_action', 'alternate_names_nonce');

        $alternate_names = get_post_meta( $post->ID, 'alternate_names_value', true );
        ?>
        <div class="alternate-names-div">
          <textarea name="alternate_names_meta" id="alternate_names_meta" rows="4" cols="35"><?php echo esc_html($alternate_names)?></textarea>
          <p>Alternate names for the Novel. Separate multiple values by comma. Leave Empty if there are none.</p>
        </div>
        <?php
    }

    function save_alternate_names( $post_id ) {
        
        if (!isset($_POST['alternate_names_nonce']) || ! wp_verify_nonce($_POST['alternate_names_nonce'], 'alternate_names_nonce_action'))
            return;
        
        if (defined('DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
            return;

        if (!current_user_can('edit_post', $post_id ))
            return;

        if(empty($_POST['alternate_names_meta']))
            return;

        update_post_meta(
            $post_id,
            'alternate_names_value',
            sanitize_text_field($_POST['alternate_names_meta'])
        );
    }

    function published_date_metabox_callback( $post ) {

        wp_nonce_field( 'published_date_nonce_action', 'published_date_nonce' );

        $formats = get_terms('format', array(
            'hide_empty' => false,
        ));

        ?>
        <table>
            <?php
                foreach( $formats as $format ) {

                    $format_name = $format->name;
                    $published_date = get_post_meta( $post->ID, 'published_date_value_'.$format_name, true );

                    if( $format_name == "None") {
                        continue;
                    }
                    ?>
                        <tr class="published-date-<?php echo $format_name;?>">
                            <th>
                                <label for="published_date_<?php echo $format_name;?>"><?php echo $format_name;?></label>
                            </th>
                            <td>
                                <input name="published_date_<?php echo $format_name;?>" type="date" value="<?php echo esc_html($published_date);?>">
                            </td>
                        </tr>
                    <?php
                }
            ?>
        </table>
        <?php
    }

    function save_published_date( $post_id) {
        
        if (!isset( $_POST['published_date_nonce']) || !wp_verify_nonce( $_POST['published_date_nonce'], 'published_date_nonce_action'))
            return;

        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
            return;

        if (!current_user_can( 'edit_post', $post_id ))
            return;

        $formats = get_terms('format', array(
            'hide_empty' => false,
        ));

        foreach ($formats as $format) {
            
            $format_name = $format->name;

            if ($format_name == "None")
                continue;

            $meta_key = 'published_date_value_'.$format_name;

            if (!empty($_POST['published_date_'.$format_name])) {
                update_post_meta(
                    $post_id,
                    $meta_key,
                    sanitize_text_field($_POST['published_date_'.$format_name]),
                );
                error_log('yes');
            } else {
                error_log('no');
                if(get_post_meta($post_id, $meta_key, true)) {
                    delete_post_meta($post_id, $meta_key);
                    error_log('Delete');
                }
            }
        }
    }

    function series_metabox_callback( $post ) {

        wp_nonce_field( 'series_nonce_action', 'series_nonce' );

        $args = array(
            'post_type' => 'novel',
            'posts_per_page' => -1,
        );
        $series = get_posts($args);
        $series_value = get_post_meta($post->ID, 'series_value', true);
        ?>
            <input list="series_list" class="widefat" name="series_meta" id="series_meta" autocomplete="on" value="<?php echo get_the_title($series_value);?>">
            <datalist id="series_list">
                <?php 
                    foreach($series as $novel) {
                        ?>
                            <option value="<?php echo $novel->post_title;?>">
                        <?php
                    }
                ?>
            </datalist>
        <?php
    }

    function save_series( $post_id) {

        if (!isset( $_POST['series_nonce'] ) || ! wp_verify_nonce( $_POST['series_nonce'], 'series_nonce_action'))
            return;
        
        if (defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE)
            return;

        if (!current_user_can( 'edit_post', $post_id ))
            return;

        if (empty($_POST['series_meta']))
            return;

        global $wpdb;
        $series_id = $wpdb->get_var(
            "SELECT ID FROM ".$wpdb->posts." WHERE post_title = '".$_POST['series_meta']."' AND post_type = 'novel'",
        );

        if(!$series_id) {
            return new WP_Error( 'error_code', 'Error message');
        }

        update_post_meta(
            $post_id,
            'series_value',
            $series_id
        );
    }

    function add_seo_meta() {
        
        $screens = get_post_types();

        foreach ( $screens as $screen ) {

            add_meta_box(
                'seo_meta_title',
                'SEO Title',
                [ $this, 'seo_meta_title_callback'],
                $screen,
                'side',
                'default',
                null,
            );

            add_meta_box(
                'seo_meta_desc',
                'SEO Description',
                [ $this, 'seo_meta_desc_callback'],
                $screen,
                'side',
                'default',
                null,
            );
        }
    }

    function seo_meta_title_callback( $post ) {

        wp_nonce_field( 'seo_meta_title_nonce_action', 'seo_meta_title_nonce' );

        $value = get_post_meta( $post->ID, 'seo_meta_title_val', true );
        
        echo '<div class="seo_meta-title">
          <label for="seo_meta_title">Meta Title</label>
          <input type="text" name="seo_meta_title" id="seo_meta_title" maxlength="'.$this->meta_title_length.'" value="'.esc_html($value).'"/>
          <p>The meta title for SEO purposes. Max Characters('.$this->meta_title_length.')</p>
        </div>';
    }

    function seo_meta_desc_callback( $post ) {

        wp_nonce_field( 'seo_meta_desc_nonce_action', 'seo_meta_desc_nonce' );
    
        $value = get_post_meta( $post->ID, 'seo_meta_desc_val', true );

        echo '<div class="seo_meta_desc">
          <textarea name="seo_meta_desc" id="seo_meta_desc" rows="4" cols="35" maxlength="'.$this->meta_desc_length.'">'.esc_html($value).'</textarea>
          <p>The description for SEO purposes. Max Characters('.$this->meta_desc_length.')</p>
        </div>';
    }

    function save_seo_meta_title( $post_id ) {

        $post_type = get_post_type();

        if ( ! isset( $_POST['seo_meta_title_nonce'] ) || ! wp_verify_nonce( $_POST['seo_meta_title_nonce'], 'seo_meta_title_nonce_action'))
            return;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( isset( $post_type ) && 'page' == $post_type ) {
            if ( ! current_user_can( 'edit_page', $post_id ) )
                return;
        }
        else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return;
        }

        if ( empty( $_POST['seo_meta_title'] ) ) {
            $title = sanitize_text_field(get_the_title())." ";
            $meta_length = $this->meta_title_length;

            if(strlen( $title ) > $meta_length) {
                $title = substr($title, 0, $meta_length);
                $title = substr($title, 0, strrpos($title, ' '));
                $title = $title."...";
            }
            
            update_post_meta( $post_id, 'seo_meta_title_val', $title );
        }     
        else {
            $value = sanitize_text_field( $_POST['seo_meta_title'] );
            update_post_meta( $post_id, 'seo_meta_title_val', $value );
        }
    }

    function save_seo_meta_desc( $post_id ) {

        $post_type = get_post_type();

        if ( !isset( $_POST['seo_meta_desc_nonce'] ) || ! wp_verify_nonce( $_POST['seo_meta_desc_nonce'], 'seo_meta_desc_nonce_action'))
            return;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        if ( isset( $post_type ) && 'page' == $post_type ) {
            if ( ! current_user_can( 'edit_page', $post_id ) )
                return;
        }
        else {
            if ( ! current_user_can( 'edit_post', $post_id ) )
                return;
        }

        if ( empty( $_POST['seo_meta_desc'] ) ) {
            if( $post_type == 'page' || !has_excerpt()) {
                update_post_meta( $post_id, 'seo_meta_desc_val', sanitize_text_field(wp_trim_excerpt("",get_queried_object())));
            }
            else {
                $excerpt=sanitize_text_field(get_the_excerpt());
                $excerpt = substr($excerpt, 0, $this->meta_desc_length);
                $result = substr($excerpt, 0, strrpos($excerpt, ' '));
                update_post_meta( $post_id, 'seo_meta_desc_val', $result);
            }
        }
        else {
            $value = sanitize_text_field( $_POST['seo_meta_desc'] );
            update_post_meta( $post_id, 'seo_meta_desc_val', $value );
        }
    }
}
?>