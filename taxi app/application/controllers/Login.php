<?php
class Login extends CI_Controller {
 
function __construct(){
   parent::__construct();
   $this->load->model('Admin_model','',TRUE);
   $config['protocol'] = "smtp";
    $config['smtp_host'] = "mail.masirapp.com";
    $config['smtp_port'] = "25";
    $config['smtp_user'] = "masir@masirapp.com"; 
    $config['smtp_pass'] = "Q0ZqxM@u(8Gq";
    $config['charset'] = "utf-8";
    $config['mailtype'] = "html";
    $config['newline'] = "\r\n";
    $config['crlf'] = '\r\n';
    $this->load->library('email', $config);
   $session_data = $this->session->userdata('logged_in');

  if($session_data['id']!=""){
	 redirect('Dashboard');
	}
	
}
 
function index()
{
//echo date_default_timezone_get();
   //This method will have the credentials validation
   $this->load->library('form_validation');
   $this->form_validation->set_rules('email', 'Email', 'trim|required');
   $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');
 
  if($this->form_validation->run() == FALSE){
    //Field validation failed.  User redirected to login page
    $this->load->view('template/header');
	  $this->load->view('login_view');
	  // $this->load->view('templates/footer');
  }else{
    redirect('Dashboard');
  } 
}
 
function check_database($password){
   //Field validation succeeded.  Validate against database
	$email = $this->input->post('email');
	$password = md5($this->input->post('password'));
   //query the database
	// print_r($email);print_r($password);die;
   $result = $this->Admin_model->login($email, $password);
   if($result){
    // print_r($result);die;
    // $permissions = $this->Admin_model->permissions($result->department_id);
     $sess_array = array();
       $sess_array = array(
         'id' => $result->id,
         'email' => $result->email,
         'name' => $result->name,
         'user_type' => $result->user_type,
         'department_id' => $result->department_id,
         // 'permissions' => $permissions,
       );
       $this->session->set_userdata('logged_in', $sess_array);
     return TRUE;
   }
   else
   {
     $this->form_validation->set_message('check_database', 'Invalid Email or Password');
     return false;
     }
  }

  public function forgot_password()
  {
    $this->load->library('form_validation');
    $this->form_validation->set_rules('email', 'Email', 'valid_email|trim|required|callback_check_email');
    if($this->form_validation->run() == FALSE){
      $this->load->view('template/header');
      $this->load->view('forgot_password');
    }else{
      $email = $this->input->post('email');
      $id = $this->Admin_model->forgotpassword($email);
      // print_r($id);die;
      $body = "<!DOCTYPE html>
      <head>
        <meta content=text/html; charset=utf-8 http-equiv=Content-Type /> 
        <title>Recover Password</title>
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
      </head>
      <body>    
        <table width=60% border=0 bgcolor=#FFCC66 style=margin:0 auto; float:none;font-family: 'Open Sans', sans-serif; padding:0 0 10px 0;>
          <tr>
            <th width=20px></th>
            <th style=padding-top:30px;padding-bottom:30px;> <img src=".base_url()."public/images/logo.png></th>
            <th width=20px></th>
          </tr>
          <tr>
            <td width=20px></td>
            <td bgcolor=#fff style=border-radius:10px;padding:20px;>      
              <table width=100%;>
                <tr>
                  <th style=font-size:20px; font-weight:bolder; text-align:right;padding-bottom:10px;border-bottom:solid 1px #ddd;> Hello " . $id['username'] . "</th>
                </tr>
                <tr>
                  <td style=font-size:16px;>
                    <p> You have request a password retrieval for your user account at MASIR.To complete the process, click the link below.</p>
                    <p><a target='_blank' href=" . base_url('api/User/newpassword/' . $id['b_id']) . ">Change Password</a></p>
                  </td>                    
                </tr>
                <tr>
                  <td style=text-align:center; padding:20px;>
                    <h2 style=margin-top:50px; font-size:29px;>Best Regards,</h2>
                    <h3 style=margin:0; font-weight:100;>Customer Support</h3>
                    <h3 style=margin:0; font-weight:100;><img src=".base_url()."public/images/logo.png></h3>
                  </td>
                </tr>
              </table>
            </td>
            <td width=20px></td>
          </tr>
          <tr>
            <td width=20px></td>
            <td style=text-align:center; color:#fff; padding:10px;> Copyright © MASIR All Rights Reserved</td>
            <td width=20px></td>
          </tr>
        </table>
      </body>";

      $from = 'MASIR';

      $this->email->set_newline("\r\n");
      $this->email->from('masir@masirapp.com',$from);
      $this->email->to($email);
      $this->email->subject('Password Retrival');
      $this->email->message($body);
      $this->email->send();
      //echo $this->email->print_debugger(); die;
      $this->session->set_flashdata('message', "Email Sent Successfully!!!");
      $this->load->view('template/header');
      $this->load->view('login_view');
    }
  }

  public function check_email()
  {
    $dbResult = $this->Admin_model->selectWhere('tbl_users','id',array('email'=>$_POST['email']));
    if (!empty($dbResult)) {
      return TRUE;
    } else {
      $this->form_validation->set_message('check_email', 'Email id doesn\'t exist');
     return false;
    }
  }
}
?>