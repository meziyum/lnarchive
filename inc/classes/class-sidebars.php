<?php
/**
 * Sidebar Class
 * 
 * @package LNarchive
 * 
 */

namespace lnarchive\inc; //Namespace Definition
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class sidebars{ //Assests Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor function

        //Load Class
         $this->set_hooks(); //Setting the hook below
    }

    protected function set_hooks() {
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'widgets_init', [ $this, 'ln_register_sidebars']);
    }

    public function ln_register_sidebars() { //Function to register all sidebars
        
        register_sidebar( [ //Register Main Sidebar
                'name' => 'Main Sidebar',
                'id' => 'sidebar-main',
                'description' => 'Main Sidebar',
                'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class=""widget-title>',
                'after_title' => '</h3>',
            ]
        );

        register_sidebar( [ //Register Post Sidebar
                'name' => 'Post Sidebar',
                'id' => 'sidebar-post',
                'description' => 'Post Sidebar',
                'before_widget' => '<div id="%1$s" class="widget widget-sidebar %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h3 class=""widget-title>',
                'after_title' => '</h3>',
            ]
        );

        register_sidebar( [ //Register Novel Sidebar
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