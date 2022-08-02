<?php
/**
 * Meta boxes Class
 * 
 * @package lnpedia
 * 
 */
namespace fusfan\inc; //Namespace Definition
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class meta_boxes{ //Assests Class
    use Singleton; //Using Sinlgeton
    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }
    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box']);
        add_action('save_post', [$this, 'save_post_meta_data']);
    }
    public function add_custom_meta_box( $post) {
        $screens = [ 'post'];
        foreach ( $screens as $screen ) {
        add_meta_box(
            'hide-page-title',                 // Unique ID
            'Hide Page Title',      // Box title
            [ $this, 'custom_meta_box_html'],  // Content callback, must be of type callable
            $screen,                            // Post type
            'side'
            );
        }
    }
    public function custom_meta_box_html( $post ) {
        $value = get_post_meta( $post->ID, '_hide_page_title', true );

        /**
         * 
         * Use Nonce for verification
         * 
         */
        wp_nonce_field( plugin_basename( __FILE__ ), 'hide_title_meta_box_nonce');

        ?>
        <label for="hide-title-field">Hide the page title</label>
        <select name="hide_title_field" id="hide-title-field" class="postbox">
            <option value="">Select</option>
            <option value="yes" <?php selected( $value, 'yes' ); ?>>Yes</option>
            <option value="no" <?php selected( $value, 'no' ); ?>>No</option>
        </select>
        <?php
    }
    public function save_post_meta_data( $post_id) {
        /**
         * 
         * When the post is saved or updated we get $_POST available
         * Check if the current user is authorized 
         * 
         */
         if( ! current_user_can( 'edit_post', $post_id)) {
            return;
         }

        /**
        * Check if the nonce value we received is the same as we created
        */
        if( ! isset( $_POST['hide_title_meta_box_nonce']) || ! wp_verify_nonce( $_POST['hide_title_meta_box_nonce'], plugin_basename(__FILE__) )) {
            return;
        }
        if ( array_key_exists( 'hide_title_field', $_POST ) ) {
            update_post_meta(
                $post_id,
                '_hide_page_title',
                $_POST['hide_title_field']
            );
        }
    }
}
?>