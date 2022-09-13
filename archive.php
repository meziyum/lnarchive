<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header();
?>

<main id="main" class="main-content" role="main"> <!-- Main Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="col-lg-9 content">
        <?php
        printf( //Get the Title
            '<h1 class="page-title text-dark">%1$s</h1>', //Page Title Div
            wp_kses_post( get_the_archive_title()), //Get the Title
        );
        get_template_part('template-parts/novel/novel-list');
        ?>
        </div>

        <div class="archive-sidebar col-lg-3 d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>