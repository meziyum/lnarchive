<?php
/**
 * Reading List Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class reading_list {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
    }
}
?>