<?php
/**
 * Post Types Meta Fields
 * 
 * @package LNarchive
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class post_type_meta_fields { //Post Type Meta Fields

    use Singleton; //Using Sinlgeton

    private $meta_title_length; //Define the private Meta-title max length
    private $meta_desc_length; //Define the private Meta-Desc Max length

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks

         $this->meta_title_length=60; //Assign the values to the max meta-title length
         $this->meta_desc_length=155; //Assign the values to the max meta-desc length
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'add_meta_boxes', [ $this, 'add_seo_meta_desc'] );
        add_action( 'add_meta_boxes', [ $this, 'novel_metaboxes_add'] );
        add_action( 'save_post', [ $this, 'save_seo_meta_title'] );
        add_action( 'save_post', [ $this, 'save_seo_meta_desc'] );
        add_action( 'save_post', [$this,'save_published_date'] );
    }

    function novel_metaboxes_add() { //Function to add metaboxes to the Novel postype
        
        //Published Date
        add_meta_box(
            'published_date', //The ID
            'Published Date', //The Heading
            [ $this, 'published_date_metabox_callback'], //The visual callback
            'volume', //Post types
            'side', //Location
            'default', //Priority
            null, //Args
        );
    }

    function published_date_metabox_callback( $post ) { //Function to display the Published Date Selection

         // Nonce Register
         wp_nonce_field( 'published_date_nonce_action', 'published_date_nonce' );

         $published_date = get_post_meta( $post->ID, 'published_date_value', true ); //Get the published date value
         
         ?>
         <div class="published-date"> <!-- Published Date Div -->
                    <label for="published_date">Published Date</label>
                    <input name="published_date" type="date" value="<?php echo $published_date;?>">
         </div>
         <?php
    }

    function save_published_date( $post_id) { //Function to save the published date value
        
        // Nonce Verification
        if ( ! isset( $_POST['published_date_nonce'] ) || ! wp_verify_nonce( $_POST['published_date_nonce'], 'published_date_nonce_action')) {
            return;
        }

        // If the post type is in autosave then the values dont need to be updated
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        //If the user doesnt have edit_post capability
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        if ( !empty( $_POST['published_date'] ) ) { //If the time is set
            update_post_meta(
                $post_id, //The post id
                'published_date_value', //Key
                $_POST['published_date'] //Value of the Meta
             );
        }
        else{ //Default Date
            update_post_meta(
                $post_id, //The post id
                'published_date_value', //Key
                get_the_date(), //Pubished post date is set as the novel publish date by default
             );
        }
    }

    function add_seo_meta_desc() { //Function to add metaboxes to post types
        
        $screens = get_post_types(); //Get all the post types

        foreach ( $screens as $screen ) { //Loop through all post types

            //Add SEO Meta TitleMetabox
            add_meta_box(
                'seo_meta_title', //The ID
                'SEO Title', //The Heading
                [ $this, 'seo_meta_title_callback'], //The visual callback
                $screen, //Post types
                'side', //Location
                'default', //Priority
                null, //Args
            );

            //Add SEO Meta Desc Metabox
            add_meta_box(
                'seo_meta_desc', //The ID
                'SEO Description', //The Heading
                [ $this, 'seo_meta_desc_callback'], //The visual callback
                $screen, //Post t ypes
                'side', //Location
                'default', //Priority
                null, //Args
            );
        }
    }

    function seo_meta_title_callback( $post ) { //Function to display the meta-ttiel input field

        // Nonce Register
        wp_nonce_field( 'seo_meta_title_nonce_action', 'seo_meta_title_nonce' );

        $value = get_post_meta( $post->ID, 'seo_meta_title_val', true ); //Get the meta-title value
        
        //Meta Title Input
        echo '<div class="seo_meta-title">
          <label for="seo_meta_title">Meta Title</label>
          <input type="text" name="seo_meta_title" id="seo_meta_title" maxlength="'.$this->meta_title_length.'" value="'.$value.'"/>
          <p>The meta title for SEO purposes. Max Characters('.$this->meta_title_length.')</p>
        </div>';
    }

    function seo_meta_desc_callback( $post ) { //Function to display the meta_desc input field

        // Nonce Register
        wp_nonce_field( 'seo_meta_desc_nonce_action', 'seo_meta_desc_nonce' );
    
        $value = get_post_meta( $post->ID, 'seo_meta_desc_val', true ); //Get the meta-desc value

        //Meta Desc Input
        echo '<div class="seo_meta_desc">
          <textarea name="seo_meta_desc" id="seo_meta_desc" rows="4" cols="30" maxlength="'.$this->meta_desc_length.'">'.$value.'</textarea>
          <p>The description for SEO purposes. Max Characters('.$this->meta_desc_length.')</p>
        </div>';
    }

    function save_seo_meta_title( $post_id ) { //Function to save the SEO_Meta_Title for the post types

        $post_type = get_post_type(); //Get the Post Type

        // Nonce Verification
        if ( ! isset( $_POST['seo_meta_title_nonce'] ) || ! wp_verify_nonce( $_POST['seo_meta_title_nonce'], 'seo_meta_title_nonce_action')) {
            return;
        }

        // If the post type is in autosave then the values dont need to be updated
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verify User Permissions
        if ( isset( $post_type ) && 'page' == $post_type ) { //Check if its a page
            if ( ! current_user_can( 'edit_page', $post_id ) ) { //If the user doesnt have edit_page capability
                return;
            }
        }
        else { //For all other post types
            if ( ! current_user_can( 'edit_post', $post_id ) ) { //If the user doesnt have edit_post capability
                return;
            }
        }

        // Updating the value
        if ( empty( $_POST['seo_meta_title'] ) ) { //If the meta_title is not set then default value
            $title = get_the_title()." "; //Get the title
            $title = substr($title, 0, $this->meta_title_length); //Truncate the title to the max char
            $result = substr($title, 0, strrpos($title, ' ')); //Tuncate it again at last space so no half words are displayed
            update_post_meta( $post_id, 'seo_meta_title_val', $result."..."); //Update the page seo title value to the title with a concatinated ... to understand that its not the full title
        }     
        else { //IF the form field is set
            $value = sanitize_text_field( $_POST['seo_meta_title'] ); //Get the value from the form field
            update_post_meta( $post_id, 'seo_meta_title_val', $value ); //Update the value
        }
    }

    function save_seo_meta_desc( $post_id ) { //Function to save the SEO Meta Desc for post types

        $post_type = get_post_type(); //Get the Post Type

        // Nonce Verification
        if ( ! isset( $_POST['seo_meta_desc_nonce'] ) || ! wp_verify_nonce( $_POST['seo_meta_desc_nonce'], 'seo_meta_desc_nonce_action')) {
            return;
        }

        // If the post type is in autosave then the values dont need to be updated
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Verify User Permissions
        if ( isset( $post_type ) && 'page' == $post_type ) { //Check if its a page
            if ( ! current_user_can( 'edit_page', $post_id ) ) { //If the user doesnt have edit_page capability
                return;
            }
        }
        else { //For all other post types
            if ( ! current_user_can( 'edit_post', $post_id ) ) { //If the user doesnt have edit_post capability
                return;
            }
        }

        // Updating the value
        if ( empty( $_POST['seo_meta_desc'] ) ) { //If the meta_desc is not set

            //Assign Default Value
            if( $post_type == 'page' || !has_excerpt()) { //Default value for the page and if there are no excerpts
                update_post_meta( $post_id, 'seo_meta_desc_val', wp_trim_excerpt("",get_queried_object())); //Update the page seo desc value to a custom excerpt generated from the content
            }
            else { //For all other post types
                $excerpt=get_the_excerpt(); //Get the excerpt
                $excerpt = substr($excerpt, 0, $this->meta_desc_length); //Truncate the excerpt to the max char
                $result = substr($excerpt, 0, strrpos($excerpt, ' ')); //Tuncate it again at last space so no half words are displayed
                update_post_meta( $post_id, 'seo_meta_desc_val', $result);//Update the default values
            }
        }
        else {
            $value = sanitize_text_field( $_POST['seo_meta_desc'] ); //Get the value from the form field
            update_post_meta( $post_id, 'seo_meta_desc_val', $value ); //Update the value
        }
    }
}
?>