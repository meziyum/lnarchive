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
        </div>
    </div>
</main>

<?php get_footer();?>