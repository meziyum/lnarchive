<?php
/**
 * Search Result Template
 * 
 * @package LNarchive
 */
get_header();

$result_found = false;
?>

<main id="main" class="main-content" role="main">
    <div class="row main-row">
        <div id="search-results" class="search-result-wrap col-lg-9">
            <?php
                
                $s=get_search_query();
                
                $nargs = array(
                    's' =>$s,
                    'post_type' => 'novel',
                );
                
                $nquery = new WP_Query( $nargs );

                if($nquery->have_posts()){
                    $result_found = true;
                    ?><h2 class="page-title">Novels</h2><?php
                    novel_list($nquery, array( 'name' => 'novel-search' ));    
                }

                wp_reset_query();

                $pargs = array(
                    's' =>$s,
                    'post_type' => 'post',
                );
                $pquery = new WP_Query( $pargs );
 
                if($pquery->have_posts()) {
                    $result_found = true; 
                    ?><h2 class="page-title">Posts</h2><?php
                    post_list( $pquery, 'post-search-result');
                }

                wp_reset_query();

                if( $result_found == false) {
                    echo "No Results Found";
                }
            ?>
        </div>
        <aside class="sidebar-wrap col d-none d-lg-block">
            <?php get_sidebar('sidebar-main');?>
        </aside>
    </div>
</main>

<?php get_footer();?>