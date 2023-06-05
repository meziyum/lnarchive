<?php
/**
 * Roles Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class roles {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
        add_action( 'init', [$this, 'init_roles']);
        add_action( 'after_setup_theme', [$this, 'set_default_role']);
    }

    function init_roles() {
        $contributor_capabilities = array(
            'read' => true,
            'edit_posts' => true,
            'delete_posts' => false,
            'upload_files' => true,
            'publish_posts' => false,
            'edit_published_posts' => false,
            'delete_published_posts' => false,
            'delete_private_posts' => false,
            'edit_private_posts' => false,
            'read_private_posts' => false,
            'delete_others_posts' => false,
            'edit_others_posts' => false,
            'edit_comment' => true,
            'moderate_comments' => false,
            'manage_categories' => false,
            'assign_categories' => true,
        );
        $contributor_roles = array_keys(get_contributor_roles());

        foreach($contributor_roles as $role) {
            add_role($role, $role, $contributor_capabilities);
        }

        remove_role('contributor');
    }

    function set_default_role() {
        update_option('default_role', array_keys(get_contributor_roles())[0]);
    }
}
?>