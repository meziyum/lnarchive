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
            <a class="edit-button anchor-button" href="<?php echo esc_url(get_edit_post_link());?>">
                Edit <?php echo ucwords(esc_html(get_post_type()));?>
            </a> 
        <?php
    }    
?>