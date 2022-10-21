<?php
/**
 * Comment Template
 * 
 * @package LNarchive
 */

if ( post_password_required() )
	return;
?>

<section id="comments" class="comments-section">
    <?php
        $args = array(
            'status' => 'approve',
            'parent'    => 0,
            'post_id' => get_the_ID(),
        );
        $comments_query = new WP_Comment_Query( $args );
        $comments = $comments_query->comments;
        $comments = get_comments( $args );
        if ( $comments ) {
            foreach ( $comments as $comment ) {
                $comment_id = $comment->comment_ID;
                ?>
                    <div class="row">
                        <div class="col-lg-2 col-md-2">
                            <?php

                            ?>
                        </div>

                        <div>
                            <?php
                                echo $comment->comment_content;
                            ?>
                        </div>

                        <?php echo comment_reply_link( array(), $comment_id);?>
                    </div>
                <?php
                
            }
        }
    comment_form();//Display the Comment form 
    ?>
</section><!-- #comments -->