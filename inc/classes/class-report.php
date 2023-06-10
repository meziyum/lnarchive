<?php
/**
 * Report Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class report {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
    }
}
?>