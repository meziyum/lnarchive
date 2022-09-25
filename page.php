<?php
/**
 * Sinlge Page Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header function
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row">
        <div class="page-wrap content-wrap col"> <!-- Page Content Div -->
            <?php
            if( have_posts(  ) ) { //If there are pages
                while( have_posts(  )) : the_post(); //Loop through the pages
                    
                    //Title
                    printf(
                        '<h1 class="page-title">%1$s</h1>', //HTML
                        wp_kses_post( get_the_title()), //Get the Title
                    );

                    the_content(); //Display the Content

                    wp_link_pages( //Display the subpages of the page
                        [
                        'before' => '<div class="page-links d-flex justify-content-center">', //Before Subpage
                        'after' =>  '</div>', //After Subpage
                        'link_before' =>'<button class="post-page-no">', //Before Subpage No
                        'link_after' => '</button>', //After Subpage No
                        ]
                    );
                    
                    if( current_user_can('edit_posts')){ //Check if the user has capability to edit the post
                        ?>
                            <button onclick="location.href='<?php echo esc_url(get_edit_post_link());?>'" type="button" class="edit-button float-end"> <!-- Edit Button -->
                                <a class= "entry-footer-link text-white"> <!-- The Edit Button Text -->
                                    Edit <?php echo ucwords($post_type);?>
                                </a>
                            </button>
                        <?php
                    }
                endwhile;
            }
            ?>
        </div>
        <aside class="sidebar-wrap col-lg-3 d-none d-lg-block"> <!-- Sidebar Col -->
            <?php get_sidebar('sidebar-main'); //Get the Sidebar?>
        </aside>
    </div>
    
</main> <!-- End of Main -->

<?php get_footer();?> <!-- Get the Footer -->