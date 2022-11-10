<?php
/**
 * Reviews Template
 * 
 * @package LNarchive
 */

if ( post_password_required() ) //If post is password protected return
	return;
?>

<section id="reviews" class="reviews-section"> <!-- Reviews Section -->
    <?php
        comment_form( array(
                'title_reply'   => 'Leave a review', //Review Msg
                'comment_field' => '<textarea id="comment" name="comment" cols="45" rows="8" maxlength="65525"></textarea>', //Textarea HTML
                'logged_in_as'  => '', //Logged in as Messages
                'label_submit'  => 'Submit', //Button Label
                'submit_field'  =>  '<p class="form-submit d-flex justify-content-end">%1$s %2$s</p>', //HTML for the markdown surrounding the submit button
                'format'    => 'html5', //Format for the Review
            )
        );//Display the Review form 
    ?>
    <ul class="review-list reviewss"> <!-- Reviews List -->
        <?php
            wp_list_comments( array(
                'type' => 'comment', //Ignore Pingbacks and Trackbacks
                'style'      => 'ol', //Style for the listing
                'short_ping' => true, //Output Short pings
                'callback' => 'novel_reviews', //Callback function to display the comment entry
                'avatar_size'   => 64, //Default Avatar Size
            ) );
        ?>
    </ul><!-- .comment-list -->
    
</section><!-- #comments -->