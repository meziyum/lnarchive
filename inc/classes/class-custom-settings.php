<?php
/**
 * Custom Settings Template
 */

namespace lnarchive\inc; //Namespace
use lnarchive\inc\traits\Singleton; //Singleton Directory using namespace

class custom_settings{ //Custom Settings Class

    use Singleton; //Using Sinlgeton

    protected function __construct(){ //Constructor

        //Load Class
         $this->set_hooks(); //Loading the hooks
    }

    protected function set_hooks() { //Hooks function
        
         /**
          * Actions
          */

        //Adding functions to the hooks
        add_action( 'admin_menu', [ $this, 'add_setting_pages'] );
        add_action( 'admin_init', [$this, 'seo_settings_func'] );
    }

    function add_setting_pages() { //Add Setting Menus and Submenus

        //Subpages
        add_submenu_page( //SEO submenu in Settings page
            'options-general.php', //Main page name
            'SEO', //Page Title
            'SEO', //Menu Title
            'manage_options', //Permission
            'seo-settings', //Slug
            [$this, 'seo_settings_callback'], //Callback
        );

    }

    function seo_settings_callback(){ //SEO subpage function display

        if ( ! current_user_can( 'manage_options' ) ) { //If current user has necessary capabilites
            return;
        }

        if ( isset( $_GET['settings-updated'] ) ) { //SEO Settings saved msg
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' ); //Display the msg
        }
        ?>
            <div class="wrap"> <!-- Main Div-->
                <h1><?php echo get_admin_page_title() ?></h1> <!-- Page Title -->
                <form method="post" action="options.php"> <!--Settings Form -->
                    <?php
                        settings_fields( 'seo_settings_grp' ); //Define the Options Grp Name
                        do_settings_sections( 'seo-settings' ); //Page Slug
                        submit_button(); //Submit Button
                    ?>
                </form>
            </div>
        <?php
    }
  
    function seo_settings_func(){ //SEO Settings function

        $page_slug = 'seo-settings'; //Page Slug
        $option_group = 'seo_settings_grp'; //Options Grp Name

        add_settings_section( // General Section
            'seo_general', //ID
            'General Settings', //Title
            '', // Callback to display the section (optional)
            $page_slug, //Page Slug
        );

        register_setting( $option_group, 'seo-title-length'); //Register SEO Title Setting
        register_setting( $option_group, 'seo-desc-length'); //Register SEO Desc Setting
        register_setting( $option_group, 'seo-taxonomies'); //Register SEO Taxonomies

        add_settings_field( //Add SEO Title Settings
            'seo-title-length', //ID
            'SEO Title Length', //Title
            [$this, 'seo_title_settings_callback'], //Callback function to display
            $page_slug, //Page Slug
            'seo_general' //ID of the section the setting is in
        );

        add_settings_field( //Add SEO Desc Settings
            'seo-desc-length', //ID
            'SEO Title Description', //Title
            [$this, 'seo_desc_settings_callback'], //Callback function to display
            $page_slug, //Page Slug
            'seo_general', //ID of the section the setting is in
        );

        add_settings_field( //Setting for taxonomies which will have the SEO meta and desc
            'seo-taxonomies', //ID
            'Taxonomies', //Title
            [$this, 'seo_taxonomies'], //Callback function to display
            $page_slug, //Page Slug
            'seo_general', //ID of the section the setting is in
        );
    }

    function seo_taxonomies(){

        $args = array(
            'public'   => true,
            '_builtin' => false    
        ); 

        $taxonomies = get_taxonomies( $args );

        foreach( $taxonomies as $tax ) {
            ?>
                
                <input type="checkbox" id="seo_taxonomies[<?php echo $tax?>]" name="seo_taxonomies[<?php echo $tax?>]" value="1" <?php echo checked(1, get_option('seo-taxonomies['.$tax.']'), true); ?>>
                <label for="seo_taxonomies[<?php echo $tax?>]"><?php echo get_taxonomy($tax)->labels->name; ?></label><br>
                
            <?php
        }
    }

    function seo_title_settings_callback() { //Function to display SEO Title Length Setting
        ?>
            <input type="number" id="seo-title-length" name="seo-title-length" value="<?php echo get_option('seo-title-length');?>">
        <?php
    }

    function seo_desc_settings_callback() { //Function to display SEO Desc Length Setting
        ?>
            <input type="number" id="seo-desc-length" name="seo-desc-length" value="<?php echo get_option('seo-desc-length');?>">
        <?php 
    }
}
?>