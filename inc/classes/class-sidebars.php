<?php
/**
 * Sidebar Class
 * 
 * @package LNarchive
 * 
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class sidebars{

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'widgets_init', [ $this, 'ln_register_sidebars']);
    }

    private function ln_register_sidebars() {
        
        register_sidebar( [
                'name' => 'Main Sidebar',
                'id' => 'sidebar-main',
                'description' => 'Main Sidebar',
                'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class=""widget-title>',
                'after_title' => '</h3>',
            ]
        );

        register_sidebar( [
                'name' => 'Post Sidebar',
                'id' => 'sidebar-post',
                'description' => 'Post Sidebar',
                'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class=""widget-title>',
                'after_title' => '</h3>',
            ]
        );

        register_sidebar( [
                'name' => 'Novel Sidebar',
                'id' => 'sidebar-novel',
                'description' => 'Novel Sidebar',
                'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class=""widget-title>',
                'after_title' => '</h3>',
            ]
        );
    }
}
?>