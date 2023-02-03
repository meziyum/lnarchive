<?php
/**
 * Archive Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Container -->
    <div class="row main-row"> <!-- Main Row -->

        <?php
        printf( //Get the Title
            '<h1 class="page-title">%1$s</h1>', //Page Title Div
            wp_kses_post( get_the_archive_title()), //Get the Title
        );
        ?>

        <div id="archive-wrap"> <!-- Archive Div -->
        </div>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>