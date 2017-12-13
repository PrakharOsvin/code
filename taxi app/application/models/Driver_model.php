<?php

class Driver_model extends CI_Model {

	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->db->query("SET time_zone='+3:30'");
    }

    // Sign up

    function add_driver($data) {
        if ($this->db->insert('tbl_users', $data)) {
        return true;
        } else {
        return false;
        }
    }  
}

?>