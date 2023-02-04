<?php
/**
 * 
 * Edit Button Template
 * 
 * @package LNarchive
 */
?>

<?php
    if( current_user_can('edit_posts')){
        ?>
            <button onclick="location.href='<?php echo esc_url(get_edit_post_link());?>'" type="button" class="edit-button float-end">
                <a class= "entry-footer-link">
                    Edit <?php echo ucwords(esc_html(get_post_type()));?>
                </a>
            </button>
        <?php
    }    
?>