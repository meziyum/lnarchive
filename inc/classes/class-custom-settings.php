<?php
/**
 * Custom Settings Template
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class custom_settings {

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'admin_menu', [ $this, 'add_setting_pages'] );
        add_action( 'admin_init', [$this, 'seo_settings_func'] );
    }

    function add_setting_pages() {

        add_submenu_page(
            'options-general.php',
            'SEO',
            'SEO',
            'manage_options',
            'seo-settings',
            [$this, 'seo_settings_callback'],
        );

    }

    function seo_settings_callback() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
        }
        ?>
            <div class="wrap">
                <h1><?php echo get_admin_page_title() ?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'seo_settings_grp' );
                        do_settings_sections( 'seo-settings' );
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }
  
    function seo_settings_func() {

        $page_slug = 'seo-settings';
        $option_group = 'seo_settings_grp';

        add_settings_section(
            'seo_general',
            'General Settings',
            '',
            $page_slug,
        );

        register_setting( $option_group, 'seo-title-length');
        register_setting( $option_group, 'seo-desc-length');
        register_setting( $option_group, 'seo-taxonomies');

        add_settings_field(
            'seo-title-length',
            'SEO Title Length',
            [$this, 'seo_title_settings_callback'],
            $page_slug,
            'seo_general'
        );

        add_settings_field(
            'seo-desc-length',
            'SEO Title Description',
            [$this, 'seo_desc_settings_callback'],
            $page_slug,
            'seo_general',
        );

        add_settings_field(
            'seo-taxonomies',
            'Taxonomies',
            [$this, 'seo_taxonomies'],
            $page_slug,
            'seo_general',
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

    function seo_title_settings_callback() {
        ?>
            <input type="number" id="seo-title-length" name="seo-title-length" value="<?php echo get_option('seo-title-length');?>">
        <?php
    }

    function seo_desc_settings_callback() {
        ?>
            <input type="number" id="seo-desc-length" name="seo-desc-length" value="<?php echo get_option('seo-desc-length');?>">
        <?php 
    }
}
?>