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
        add_action( 'admin_init', [$this, 'novel_settings_func'] );
        add_action( 'admin_init', [$this, 'seo_settings_func'] );
        add_action( 'admin_init', [$this, 'social_settings_func'] );
        add_action( 'admin_init', [$this, 'tax_settings_func'] );
    }

    function add_setting_pages() {

        add_submenu_page(
            'options-general.php',
            'Novel',
            'Novel',
            'manage_options',
            'novel-settings',
            [$this, 'novel_settings_callback'],
        );

        add_submenu_page(
            'options-general.php',
            'Taxonomies',
            'Taxonomies',
            'manage_options',
            'tax-settings',
            [$this, 'tax_settings_callback'],
        );

        add_submenu_page(
            'options-general.php',
            'SEO',
            'SEO',
            'manage_options',
            'seo-settings',
            [$this, 'seo_settings_callback'],
        );

        add_submenu_page(
            'options-general.php',
            'Social',
            'Social',
            'manage_options',
            'social-settings',
            [$this, 'social_settings_callback'],
        );
    }

    function tax_settings_callback() {
        if (!current_user_can( 'manage_options' )) {
            return;
        }

        if (isset( $_GET['settings-updated'] )) {
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
        }
        ?>
            <div class="wrap">
                <h1><?php echo get_admin_page_title() ?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields('tax-settings-grp');
                        do_settings_sections('tax-settings');
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }

    function novel_settings_callback() {
        if (!current_user_can( 'manage_options' )) {
            return;
        }

        if (isset( $_GET['settings-updated'] )) {
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
        }
        ?>
            <div class="wrap">
                <h1><?php echo get_admin_page_title() ?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields('novel-settings-grp');
                        do_settings_sections('novel-settings');
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }

    function tax_settings_func() {
        $page_slug = 'tax-settings';
        $option_group = 'tax-settings-grp';

        add_settings_section(
            'taxonomy_weightage',
            'Taxonomies Weightage',
            '',
            $page_slug,
        );

        $taxonomies = get_taxonomies(array('_builtin' => false,), 'names');
        array_push($taxonomies, 'post_tag', 'category');

        foreach($taxonomies as $tax) {
            register_setting($option_group, 'tax-weightage-'.$tax);
            add_settings_field(
                'tax-weightage-'.$tax,
                get_taxonomy_labels(get_taxonomy($tax))->name,
                [$this, 'checkbox_display'],
                $page_slug,
                'taxonomy_weightage',
                array('tax-weightage-'.$tax),
            );
        }
    }

    function novel_settings_func() {
        $page_slug = 'novel-settings';
        $option_group = 'novel-settings-grp';

        add_settings_section(
            'taxonomy_display',
            'Novel Info Table Taxonomies',
            '',
            $page_slug,
        );

        $taxs = get_object_taxonomies('novel');

        foreach($taxs as $tax) {
            register_setting($option_group, 'novel-display-'.$tax);
            add_settings_field(
                'novel-display-'.$tax,
                get_taxonomy_labels(get_taxonomy($tax))->name,
                [$this, 'checkbox_display'],
                $page_slug,
                'taxonomy_display',
                array('novel-display-'.$tax),
            );
        }
    }

    function social_settings_callback() {
        if (!current_user_can( 'manage_options' )) {
            return;
        }

        if (isset( $_GET['settings-updated'] )) {
            add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
        }
        ?>
            <div class="wrap">
                <h1><?php echo get_admin_page_title() ?></h1>
                <form method="post" action="options.php">
                    <?php
                        settings_fields( 'social_settings_grp' );
                        do_settings_sections( 'social-settings' );
                        submit_button();
                    ?>
                </form>
            </div>
        <?php
    }

    function social_Settings_func() {
        $page_slug = 'social-settings';
        $option_group = 'social_settings_grp';

        add_settings_section(
            'social_display',
            'Display',
            '',
            $page_slug,
        );

        $socials = array(
            'discord',
            'reddit',
            'twitter',
            'instagram',
        );

        foreach($socials as $social) {
            register_setting($option_group, $social.'-display');
            add_settings_field(
                $social.'-display',
                ucfirst($social).' Link',
                [$this, 'checkbox_display'],
                $page_slug,
                'social_display',
                array($social.'-display'),
            );
        }

        add_settings_section(
            'social_links',
            'Social Links',
            '',
            $page_slug,
        );

        register_setting($option_group, 'discord-link');
        register_setting($option_group, 'twitter-link');
        register_setting($option_group, 'reddit-link');
        register_setting($option_group, 'instagram-link');

        add_settings_field(
            'discord-link',
            'Discord Link',
            [$this, 'social_link_callback'],
            $page_slug,
            'social_links',
            array('discord-link'),
        );
        add_settings_field(
            'twitter-link',
            'Twitter Link',
            [$this, 'social_link_callback'],
            $page_slug,
            'social_links',
            array('twitter-link'),
        );
        add_settings_field(
            'reddit-link',
            'Reddit Link',
            [$this, 'social_link_callback'],
            $page_slug,
            'social_links',
            array('reddit-link'),
        );
        add_settings_field(
            'instagram-link',
            'Instagram Link',
            [$this, 'social_link_callback'],
            $page_slug,
            'social_links',
            array('instagram-link'),
        );
    }

    function social_link_callback($args) {
        $link_type = $args[0];
        ?>
            <input type="url" id="<?php echo $link_type;?>" name="<?php echo $link_type;?>" value="<?php echo esc_html(get_option($link_type));?>">
        <?php
    }

    function seo_settings_callback() {
        if (!current_user_can( 'manage_options' )) {
            return;
        }

        if (isset( $_GET['settings-updated'] )) {
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

        register_setting($option_group, 'seo-title-length');
        register_setting($option_group, 'seo-desc-length');

        add_settings_field(
            'seo-title-length',
            'SEO Title Length',
            [$this, 'number_input_display'],
            $page_slug,
            'seo_general',
            array('seo-title-length'),
        );

        add_settings_field(
            'seo-desc-length',
            'SEO Title Description',
            [$this, 'number_input_display'],
            $page_slug,
            'seo_general',
            array('seo-desc-length'),
        );
    }

    function number_input_display($args) {
        $type = $args[0];
        ?>
            <input type="number" id="<?php echo $type;?>" name="<?php echo $type;?>" value="<?php echo esc_html(get_option($type));?>">
        <?php
    }

    function checkbox_display($args) {
        $display_type = $args[0];
        ?>
            <input type="checkbox" id="<?php echo $display_type;?>" name="<?php echo $display_type;?>" value="1" <?php echo checked(1, esc_html(get_option($display_type)), true); ?>>
        <?php
    }
}
?>