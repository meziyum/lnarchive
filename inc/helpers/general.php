<?php
/**
* 
* General Helper Functions
* 
* @package LNarchive
*/

    function get_contributor_roles(){
        return array(
            'Novice Contributor' => 0,
            'Active Contributor' => 100,
            'Dedicated Contributor' => 500,
            'Veteran Contributor' => 1500,
            'Elite Contributor' => 3000,
            'Master Contributor' => 6000,
            'Grandmaster Contributor' => 12000,
            'Legendary Contributor' => 25000,
            'Supreme Contributor' => 50000,
            'God Contributor' => 100000
        );
    }
?>