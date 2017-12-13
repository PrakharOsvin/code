<?php

class Blog extends CI_Controller {

    function __construct() {
        parent::__construct();
        // error_reporting(E_ALL); ini_set('display_errors', 1);
        $this->load->model('Admin_model', '', TRUE);
    }

    public function notifications()
    {
        $id = $this->input->get('id');
        $data['notification'] = $this->Admin_model->selectWhere("tbl_notifications","*",array('id'=>$id));
        $this->load->view('notifications',$data);
    }

}

?>