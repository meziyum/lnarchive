<?php
/**
 * Category Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="archive-wrap col-lg-9"> <!-- Archive Div -->
        <?php
        printf( //Get the Title
            '<h1 class="page-title">%1$s</h1>', //Page Title Div
            wp_kses_post( get_the_archive_title()), //Get the Title
        );
        if(have_posts()) { //If there is post
            //Loop through the Posts
            ?>
            <div class="row novel-list"> <!-- Archive List Div -->
            <?php
                while( have_posts()) : the_post(); //While there are posts
                    get_template_part('template-parts/post/post-list'); //Get the Post List
                endwhile; //End While Loop
            ?>
            </div>
            <?php
        }
        ?>
        </div>
        <div class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>