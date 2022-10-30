<?php
/**
 * Comment Template
 * 
 * @package LNarchive
 */

if ( post_password_required() ) //If post is password protected return
	return;
?>

<section id="comments" class="comments-section"> <!-- Comments Section -->
    <?php
        comment_form( array(
                'title_reply'   => 'Leave a Comment',
                'label_submit'  => 'Submit',
                'submit_field'  =>  '<p class="form-submit d-flex justify-content-end">%1$s %2$s</p>',
                'format'    => 'html5',
            )
        );//Display the Comment form 
    ?>
    <ul class="comment-list comments"> <!-- Comments List -->
        <?php
            wp_list_comments( array(
                'type' => 'comment', //Ignore Pingbacks and Trackbacks
                'style'      => 'ol', //Style for the listing
                'short_ping' => true, //Output Short pings
                'callback' => 'nested_comments', //Callback function to display the comment entry
                'avatar_size'   => 64,
            ) );
        ?>
    </ul><!-- .comment-list -->
    
</section><!-- #comments -->