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
                'title_reply'   => 'Leave a Comment', //Comment Msg
                'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525"></textarea>', //Textarea HTML
                'logged_in_as'  => '', //Logged in as Messages
                'cancel_reply_before'   => '<small class="float-end">', //HTML before cancel reply anchor
                'cancel_reply_after'    => '</small>', //HTML after cancel reply anchor
                'cancel_reply_link' => 'Cancel Reply', //Cancel Reply to anchor text
                'title_reply_to'    => 'Leave a Reply to %s', //Leave a reply to HTML
                'label_submit'  => 'Submit', //Button Label
                'submit_field'  =>  '<p class="form-submit d-flex justify-content-end">%1$s %2$s</p>', //HTML for the markdown surrounding the submit button
                'format'    => 'html5', //Format for the Comment
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
                'avatar_size'   => 64, //Default Avatar Size
            ) );
        ?>
    </ul><!-- .comment-list -->
    
</section><!-- #comments -->