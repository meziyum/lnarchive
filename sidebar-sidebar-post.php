<?php
/**
 * The Post Sidebar Template
 * 
 * @package LNarchive
 */
?>

<aside id="secondary" role="complementary"> <!-- Aside Div for the Sidebar -->
    <?php 
        dynamic_sidebar('sidebar-post'); //Get the Post Sidebar
    ?>
</aside>