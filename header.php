<?php
/**
 * Header Template
 * 
 * @package LNarchive
 */
?>

<!doctype html>
<html lang="en">

<head>
    <meta name="googlebot" content="notranslate">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head();?>
    <?php

        $post_type = get_post_type( get_queried_object_id());
        $object_id = get_the_ID(get_queried_object_id());

        if(is_home() && !is_front_page()) {
            ?>
                <title><?php esc_html(single_post_title());?></title>
                <meta name="description" content="The blog page of the LNarchive with all the articles">
            <?php
        } else if (is_front_page()) {
            ?>
                <title>LNarchive</title>
                <meta name="description" content="The blog page of the LNarchive with all the articles">
            <?php
        }
        else if(is_archive()){
            ?>
            <title><?php echo sanitize_text_field(get_the_archive_title());?></title>
            <meta name="description" content="<?php echo sanitize_text_field(get_the_archive_description());?>">
            <?php
        } else {
            ?>
            <title><?php echo esc_html(get_post_meta( $object_id, 'seo_meta_title_val', true ));?></title>
            <meta name="description" content="<?php echo esc_html(get_post_meta( $object_id, 'seo_meta_desc_val', true ));?>">
            <?php
        }
    ?>
</head>

<body <?php body_class()?>>
<?php wp_body_open();?>

<div id="main_body" class="site">
    <header id="masthead" class="site-header" role="banner">
        <?php get_template_part('/template-parts/header/nav');?>
    </header>

    <div id="content" class="site-content container">