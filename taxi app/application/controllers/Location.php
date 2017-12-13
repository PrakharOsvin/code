<?php

class Location extends CI_Controller {

    function __construct() {
      parent::__construct();
      $this->load->model('Admin_model', '', TRUE);
    }

    public function track($jobcode) {
      $jobdecode =  explode("--mycheckall",base64_decode($jobcode));
      $job_id = $jobdecode[0];            
      $data['driver_detail'] = $this->Admin_model->driver_detail($job_id);
      $data['title'] = "Track Location";
      // print_r($data);die;
      $this->load->view("track_location",$data);
    }

    public function marker()
    {
      $id = $this->input->get('driver_id');
      $driver_detail = $this->Admin_model->selectWhere('tbl_users','latitude,longitude',array('id'=>$id));
      // print_r($driver_detail);
      echo(json_encode($driver_detail));
    }
}

?>