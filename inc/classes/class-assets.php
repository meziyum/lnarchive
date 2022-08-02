<?php
/**
 * Enqueuing all assests
 * 
 * @package lnpedia
 * 
 */
namespace fusfan\inc; //Namespace Definition
use fusfan\inc\traits\Singleton; //Singleton Directory using namespace

class assets{ //Assests Class
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
        add_action('wp_enqueue_scripts', [ $this, 'register_styles']);
        add_action('wp_enqueue_scripts', [ $this, 'register_scripts']);
    }
    public function register_styles() { //Styles
      //Register Styles
      wp_register_style( 'fusfan_stylesheet', FUSFAN_DIR_URI . '/style.css', [], filemtime(FUSFAN_DIR_PATH . '/style.css'), 'all'); //Main Stylesheet
      wp_register_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css', [], '5.2.0' , 'all');
      wp_register_style( 'fonts_css', get_template_directory_uri() . '/assets/src/library/fonts/fonts.css ', [], false , 'all'); //Get the Font
      //Enqueue Styles
      wp_enqueue_style('fusfan_stylesheet');
      wp_enqueue_style('bootstrap_css');
      wp_enqueue_style('fonts_css');
    }
    public function register_scripts() { //Scripts    
         //Registering Scripts
         wp_register_script( 'fusfan_main_script', FUSFAN_BUILD_JS_URI . '/main.js', [], filemtime(FUSFAN_BUILD_JS_DIR_PATH . '/main.js'), true );
         wp_register_script( 'popper_js', 'https://unpkg.com/@popperjs/core@2', [], '2', true );
         wp_register_script( 'bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js', ['popper_js'], '5.2.0-beta1', true );
         //Enqueing Scripts
         wp_enqueue_script('fusfan_main_script');
         wp_enqueue_script('popper_js');
         wp_enqueue_script('bootstrap_js');
    }
}
?>