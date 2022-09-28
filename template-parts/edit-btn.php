<?php
/**
 * 
 * Edit Button Template
 * 
 * @package LNarchive
 */

$post_type = get_post_type(); //Get the post type

if( current_user_can('edit_posts')){ //Check if the user has capability to edit the post
    ?>
        <button onclick="location.href='<?php echo esc_url(get_edit_post_link());?>'" type="button" class="edit-button float-end"> <!-- Edit Button -->
            <a class= "entry-footer-link"> <!-- The Edit Button Text -->
                Edit <?php echo ucwords(esc_html($post_type));?>
            </a>
        </button>
    <?php
}
?>