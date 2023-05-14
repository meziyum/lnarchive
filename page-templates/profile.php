<?php
/**
 * Template Name: Profile
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main">
    <div id="<?php echo esc_attr(get_the_ID());?>" class="main-row">
        <div class="profile-wrap">
            <?php
                if( have_posts(  ) ) {
                    while( have_posts(  )) : the_post();
                        printf(
                            '<h1 id="page-title">%1$s</h1>',
                            'Upcoming Releases',
                        );
                    endwhile;
                }
            ?>
            <div id="profile-section"></div>
        </div>
    </div>
</main>

<?php get_footer();?>