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
        if ( have_comments() ) {
            ?>
		    <h2 class="comments-title">
			    <?php
				    printf( _nx( 'One thought on "%2$s"', '%1$s thoughts on "%2$s"', get_comments_number(), 'comments title', 'twentythirteen' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			    ?>
		    </h2>

            <ol class="comment-list">
                <?php
                    wp_list_comments( array(
                        'style'       => 'ol',
                        'short_ping'  => true,
                        'avatar_size' => 74,
                    ) );
                ?>
            </ol><!-- .comment-list -->
		    <?php
                // Are there comments to navigate through?
                if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {
                    ?>
                        <nav class="navigation comment-navigation" role="navigation">
                            <h1 class="screen-reader-text section-heading"><?php _e( 'Comment navigation', 'twentythirteen' ); ?></h1>
                            <div class="nav-previous"><?php previous_comments_link( __( '&larr; Older Comments', 'twentythirteen' ) ); ?></div>
                            <div class="nav-next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'twentythirteen' ) ); ?></div>
                        </nav><!-- .comment-navigation -->
                    <?php 
                }

                if ( ! comments_open() && get_comments_number() ) {
                    ?>
                        <p class="no-comments"><?php _e( 'Comments are closed.' , 'twentythirteen' ); ?></p>
                    <?php 
                }
        }
    comment_form();//Display the Comment form 
    ?>
</section><!-- #comments -->