<?php
/**
 *  Blog List Template
 * 
 * @package lnpedia
 */
get_header(); //Get the Header
?>

<link rel="stylesheet"
  href="https://fonts.googleapis.com/css?family=Cantarell">

<main id="main" class="container blog-content" role="main">
    <div class="row main-row">
        <div class="col-lg-9 col-md-9 col-sm-12">
        <?php
        if(have_posts()) { //If there is post
        ?>
            <div class="blog-background">  
                <header class="p-3 border-bottom border-primary">
                    <h1 class="page-title"> 
                        <?php single_post_title();//Display the Title?>
                    </h1>
                </header>   
                <?php
                //Loop through the Posts
                    while( have_posts(  )) : the_post();
                        //Article Division?>              
                        <article id="post-<?php the_ID();?>" <?php post_class('mb-3');?>>
                            <div class="border-bottom pb-3 border-primary row m-3 blog-entry">
                                <div class="col-lg-3 col-md-5 col-sm-12">
                                    <?php get_template_part('template-parts/components/blog/entry-blog-header'); //Get the Header?>
                                </div>
                                <div class="col-lg-9 col-md-7 col-sm-12">
                                    <?php
                                    get_template_part('template-parts/components/blog/entry-blog-meta'); //Get the Meta Data
                                    the_custom_excerpt(180); //Get the excerpt
                                    ?>
                                </div>
                                <?php get_template_part('template-parts/components/blog/entry-footer'); //Get the Footer?>
                            </div>         
                        </article>
                        <?php
                    endwhile; //End While Loop
                    ?>
                <div class=" m-3 blog-pagination blog-background">
                    <?php
                        custom_pagination(); //Display the Custom Pagination
                    ?>
                </div>
            </div>
            <?php
        }
        //If there is not post
        else {
            get_template_part( 'template-parts/content-none' );
        }
        ?>
        </div>
        <div class="blog-background blog-sidebar col-lg-3 col-md-3 col-sm-12">
            <?php get_sidebar('sidebar-main'); //Show the Sidebar?>
        </div>
    </div>
</main>

<?php get_footer(); //Get the Footer ?>

