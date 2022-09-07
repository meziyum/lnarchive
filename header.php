<?php
/**
 * Header Template
 * 
 * @package LNarchive
 */
?>

<!doctype html> <!-- Doc Type HTML -->
<html lang="en"> <!-- Lang -->

<head> <!-- Header -->
    <meta charset="utf-8"> <!-- Character Encoding of the HTML Doc -->
    <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Viewport Bootstrap -->
    <?php wp_head();?> <!-- Calling the wp_head function -->
    <?php
        if(is_home() && !is_front_page()) {
            ?>
                <meta name="description" content="The blog page of the LNarchive with all the articles">
            <?php
        }
    ?>
</head>

<body <?php body_class()?>> <!-- Calling Body Class -->
<?php wp_body_open();?> <!-- Body Open -->

<div id="main_body" class="site"> <!-- Main Body Div -->
    <header id="masthead" class="site-header" role="banner"> <!-- Primary Navbar(Header) Div -->
        <?php get_template_part('/template-parts/header/nav');//Get the Header?>
    </header>

    <div id="content" class="site-content container"> <!-- Main Content Div -->