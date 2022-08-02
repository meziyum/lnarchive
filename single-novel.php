<?php

/**
 * Main Template File
 * 
 * @package lnpedia
 */
get_header();
?>

<main id="main" class="site-content container bg-white" role="main">
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-12">
            <?php
            if( have_posts(  ) ) { //If there are posts
                ?>
                    <div class="novel-wrap">
                        <?php
                        while( have_posts(  )) : the_post();
                            ?>
                                <div class="row">
                                    <div class="col-lg-4 col-md-6 cold-sm-12">
                                    
                                    </div>
                                    <div class="col-lg-8 col-md-6 cold-sm-12">

                                    </div>
                                </div>
                            <?php
                        endwhile;
                        ?>
                    </div>
                <?php
            }
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-12">
            <?php 
                get_sidebar('sidebar-main'); //Get the Sidebar
            ?>
         </div>
    </div>
</main>

<?php get_footer();?>