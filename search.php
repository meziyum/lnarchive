<?php
/**
 * Search Result Template
 * 
 * @package LNarchive
 */
get_header(); //Get the Header

$result_found = false; //Results Found Flag
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
                    $result_found = true; //Set the flag to true
                    ?><h2 class="page-title">Novels</h2> <!-- Novel Heading --> <?php
                    novel_list($nquery, 'novel-search'); //Novel List      
                }

                wp_reset_postdata(); //Reset the $POST data

                $pargs = array( //Post Query Args
                    's' =>$s,
                    'post_type' => 'post',
                );
                
                $pquery = new WP_Query( $pargs ); //New query for Post Listing
 
                if($pquery->have_posts()) { //If there are posts
                    $result_found = true; //Set the flag to true 
                    ?><h2 class="page-title">Posts</h2> <!-- Post Heading --> <?php
                    post_list( $pquery, 'post-search-result'); //Post List
                }

                wp_reset_postdata(); //Reset the $POST data

                if( $result_found == false) { //If the flag is false that is no results found
                    echo "No Results Found";
                }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block"> <!-- Sidebar Div -->
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </aside>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>