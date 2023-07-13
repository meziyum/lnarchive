<?php
/**
 * Notification Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class notification {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
    }
}
?>