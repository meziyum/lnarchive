<?php
/**
 * Security Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class security{

    use Singleton;

    protected function __construct() {
         $this->set_hooks();
    }

    protected function set_hooks() {
        add_filter('the_generator', [$this, 'security_remove_version']);
        add_filter( 'login_errors', [$this, 'no_wordpress_errors'] );
        add_filter('xmlrpc_enabled', [ $this, '__return_false']);
    }

    private function security_remove_version() {
        return '';
    }

    private function no_wordpress_errors() {
        return 'Your username or password is incorrect';
    }
}
?>