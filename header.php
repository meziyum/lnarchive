<?php
/**
 * Header Template
 * 
 * @package lnpedia
 */
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"> <!-- Character Encoding of the HTML Doc -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Viewport Bootstrap -->
    <?php wp_head();?> <!-- Calling the wp_head function -->
</head>

<body <?php body_class()?>>
<?php 
    if ( function_exists( 'wp_body_open' ) ) { //Backward Compatibility
        wp_body_open();
    } else {
        do_action( 'wp_body_open' );
    }
?>
<div id="main_body" class="site">
    <header id="masthead" class="site-header" role="banner">
        <?php get_template_part('/template-parts/header/nav');?>
    </header>

    <div id="content" class="site-content">