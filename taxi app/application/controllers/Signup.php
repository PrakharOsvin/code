<?php
class Signup extends CI_Controller {
 
  function __construct(){
     parent::__construct();
     $this->load->helper('form');
      $this->load->helper('url');
      $this->load->database();
     $this->load->model('Driver_model');    
  }
 
  function index()
  {

    $this->load->library('form_validation');

    $this->form_validation->set_rules('name','Full Name','required|min_length[1]|max_length[125]');
    $this->form_validation->set_rules('email', 'Email', 'required|min_length[1]|max_length[255]|valid_email');
    $this->form_validation->set_rules('phone', 'Phone Number','required|min_length[10]|max_length[12]');
    $this->form_validation->set_rules('password', 'Password', 'trim|required');

    
   
    if($this->form_validation->run() == FALSE){
      $this->load->view('template/header');
      $this->load->view('signup_view');
    }else{
      $config['upload_path'] = 'public/images/profile_pic';
      $config['allowed_types'] = 'gif|jpg|png';
      $config['max_size'] = '5000';
      $config['max_width']  = '5024';
      $config['max_height']  = '5068';

      $this->load->library('upload', $config);

      if (!$this->upload->do_upload('profile_pic'))
      {
        $error = array('error' => $this->upload->display_errors());
        $imagename = "default.jpg";
        
      }
      else
      {
        $datai = $this->upload->data();
        $imagename = $datai['full_path'];
      }

      $md5_pass = md5($this->input->post('password'));
      $user_type = 2;
      $data = array('name' => $this->input->post('name'),
       'email' => $this->input->post('email'), 
       'password' => $md5_pass, 
       'user_type' => $user_type, 
       'phone' => $this->input->post('phone'), 
       'profile_pic' => $imagename);

      $result = $this->Driver_model->add_driver($data);
      echo $result;
      // $this->load->view('template/header', $error);
      // $this->load->view('signup_view');
    } 
  }
 
}
?>