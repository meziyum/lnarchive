<?php
/**
 * Similar Novels Class
 */

namespace lnarchive\inc;
use lnarchive\inc\traits\Singleton;

class similar_novels {
    use Singleton;

    protected function __construct(){
        $this->set_hooks();
    }

    protected function set_hooks() {
    }
}
?>