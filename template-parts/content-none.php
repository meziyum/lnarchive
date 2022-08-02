<?php
/**
 * 
 * Posts cannot be found display message
 * 
 * @package lnpedia
 * 
 */
?>

<section class="no-result not-found">
    <header class="page-header">
        <h1 class="page_title">
            <?php echo 'Nothing Found';?>
        </h1>
    </header>

    <div class="page-content">
        <?php
            if( is_home(  ) && current_user_can( 'publish_posts')) {
                ?>
                    <p><?php
                        printf(
                                wp_kses(
                                    'Ready to Publish your first post? <a href="%s">Get Started here</a> ',
                                    [
                                        'a' => [
                                            'href' =>   []
                                        ]
                                    ]
                                    
                                ),
                                admin_url('post-new.php')
                        )
                        ?>
                    </p>
                <?php
            }
            elseif ( is_search()) {
                ?>
                    <p>
                        <?php 'Sorry but nothing matched your search item. Please try again.'?>
                    </p>
                <?php
                get_search_form();
            }
            else {
                ?>
                    <p>
                        <?php 'It seems that we cann find what you are looking for. Perhaps search can help.'?>
                    </p>
                <?php
            }
        ?>
    </div>
</section>