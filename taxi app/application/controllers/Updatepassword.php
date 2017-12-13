<?php
class Updatepassword extends CI_Controller{
	function __construct() {
   parent::__construct();
   $this->load->model('Admin_model','',TRUE);
   $session_data = $this->session->userdata('logged_in');
    if(!$session_data){
      redirect('Login');
    }
    $this->load->view('template/header');
    $this->load->view('header');     
    $this->load->view('left-sidebar');     

     $this->load->library('form_validation');
      $this->load->library('session');

	}

 
 	/*Speciality*/
 	public function Updatepassword(){ 		
 		$this->load->view('Updatepassword');    
 	    $this->load->view('footer');
  }


  public function Changepassword(){     

 
//   $this->form_validation->set_rules('email', 'Email', 'trim|required');
  
   $this->form_validation->set_rules('oldpassword', 'Password', 'trim|required|callback_check_database');
    $this->form_validation->set_rules('newpassword', 'Password', 'trim|required');
    $this->form_validation->set_rules('cnewpassword', 'Password Confirmation', 'trim|required|matches[newpassword]');


// $this->form_validation->set_rules('username', 'Username', 'required');
//     $this->form_validation->set_rules('password', 'Password', 'required');
//     $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
//     $this->form_validation->set_rules('email', 'Email', 'required');

    if ($this->form_validation->run() == FALSE)
    {
      $this->load->view('Updatepassword');
          $this->load->view('footer');
    }
    else
    {
         $message = [
                     'id' => $this->input->post('id'),
                     'password' => $this->input->post('newpassword')
                   ];
               $this->Admin_model->Changepassword($message); 

              //$this->load->view('formsuccess');
    }
}


   function check_database(){
   //Field validation succeeded.  Validate against database
   $password = $this->input->post('oldpassword');
   //print_r($password);die;
   $id = $this->input->post('id');
    // print_r($id);die;
   $result = $this->Admin_model->checkpassword($id, $password);
 
   if($result){
     
     return true;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid Old Password Entered');
     return false;
     }
  }

}
?>