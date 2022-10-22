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
    <ul class="comment-list comments"> <!-- Comments List -->
        <?php
            wp_list_comments( array(
                'type' => 'comment', //Ignore Pingbacks and Trackbacks
                'style'      => 'ol', //Style for the listing
                'short_ping' => true, //Output Short pings
                'callback' => 'nested_comments', //Callback function to display the comment entry
            ) );
        ?>
    </ul><!-- .comment-list -->
    <?php
        comment_form();//Display the Comment form 
    ?>
</section><!-- #comments -->