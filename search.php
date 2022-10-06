<?php
/**
 * Search Result Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header
?>

<main id="main" class="main-content" role="main"> <!-- Main Content Container -->
    <div class="row main-row"> <!-- Main Row -->
        <div class="search-result-wrap col-lg-9"> <!-- Blog Content Div -->
            <?php
                
                $s=get_search_query(); //Get the Searc Query
                
                $nargs = array( //Novel Query Args
                    's' =>$s,
                    'post_type' => 'novel',
                );
                
                $nquery = new WP_Query( $nargs ); //New query for Novel Listing

                if($nquery->have_posts()) { //If there are novels
                    ?><h2>Novels</h2> <!-- Novel Heading --> <?php
                    novel_list($nquery, 'novel-search'); //Novel List      
                }

                wp_reset_postdata(); //Reset the $POST data

                $pargs = array( //Post Query Args
                    's' =>$s,
                    'post_type' => 'post',
                );
                
                $pquery = new WP_Query( $pargs ); //New query for Post Listing
 
                if($pquery->have_posts()) { //If there are posts
                    ?><h2>Posts</h2> <!-- Post Heading --> <?php
                    post_list( $pquery, 'post-search-result'); //Post List
                }

                wp_reset_postdata(); //Reset the $POST data

            ?>
        </div>
        <aside class="blog-sidebar col d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>