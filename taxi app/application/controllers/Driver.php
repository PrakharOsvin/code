<?php

class Driver extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Admin_model', '', TRUE);
        $this->load->library('upload');
        // $methodList = get_class_methods($this);
    // print_r($methodList);die;
   /* foreach ($methodList as $key => $value) {
      if ($value!="__construct"&&$value!="get_instance"&&$value!="index"&&$value!="add_permission"&&$value!="add_manager"&&$value!="add_driver"&&$value!="add_holidays"&&$value!="add_vehicle"&&$value!="Add_promo") {
        $this->Admin_model->add_permission(array('permission_name'=>$value));
      }
    }*/
    // die;
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

        $this->load->library('user_agent');
        $session_data = $this->session->userdata('logged_in');
        if (!$session_data) {
            redirect('Login');
        }
        // $this->router->fetch_class(); // class = controller
        $methodName = $this->router->fetch_method();
        $id = $this->Admin_model->methodId($methodName);
        if ($id!="") {
          $check_permission = $this->Admin_model->check_permission($session_data['department_id'],$id->permission_id);
          if ($check_permission=="") {
            redirect('Dashboard/error');
          }
        }
    }

    public function error()
    {
        $this->load->view('errors/403');
    }

    public function add_driver() {
   //------------------------ random password generator  
      // if (!empty($_POST)) {
      //   # code...
      // print_r($_FILES);die;
      // }
        $chars_min = 6;
        $chars_max = 10;         
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz1234567890QWERTYUIOPLKJHGFDSAZXCVBNM@#$';
        $password = "";
        for($i=0; $i<$length; $i++) {
            $current_letter = $selection ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
            $password .=  $current_letter;
        }                

      // return $password;
        // echo $password;
       //------------------------------------------

        $this->load->library('form_validation');

        $this->form_validation->set_rules('fname','First Name','required|min_length[1]|max_length[125]');
        $this->form_validation->set_rules('lname','Last Name','min_length[1]|max_length[125]');
        $this->form_validation->set_rules('gender','Gender','required');
        // $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('phone', 'Phone Number','required|min_length[4]|max_length[20]|is_unique[tbl_users.phone]');
        $this->form_validation->set_rules('address','Address','required|min_length[1]|max_length[225]');
        $this->form_validation->set_rules('state_identifier','State Identifier','required|min_length[2]|max_length[2]');
        // $this->form_validation->set_rules('region','state / province / region','required|min_length[1]|max_length[30]');
        // $this->form_validation->set_rules('postal_code','zip or postal code','required|min_length[4]|max_length[6]');

        // $this->form_validation->set_rules('vehicle_number','vehicle_number','required');
        $this->form_validation->set_rules('vehicle_type','vehicle type','required');
        $this->form_validation->set_rules('cell_provider','Cell Provider','required');

        $this->form_validation->set_rules('license_number','license number','required|min_length[4]|max_length[20]|is_unique[tbl_driver_documents.driver_license_number]');
        $this->form_validation->set_rules('license_exp_date','license exp date','required');
        $this->form_validation->set_rules('insurance_number','insurance number','required|min_length[4]|max_length[20]');
        $this->form_validation->set_rules('insurance_exp_date','insurance exp date','required');
        $this->form_validation->set_rules('registration_number','registration number','required');
        $this->form_validation->set_rules('registration_exp_date','registration exp date','required');
        $this->form_validation->set_rules('smoker','smoker','required');
        $this->form_validation->set_rules('bg_chk','background check received','required');
        $this->form_validation->set_rules('vehicle_model','vehicle model','required');
        // Check Referral
        $this->form_validation->set_rules('referral_code', 'Referral Code', 'callback_checkReferral');
        // vehicle info
        $this->data['vehicle'] = $this->Admin_model->get_vehicle_list();
        $this->data['model_list'] = $this->Admin_model->select("tbl_vehicle_model","*");
        $this->data['commissionLevels'] = $this->Admin_model->select("tbl_commissionLevels","*");
        
        // print_r($this->data);die;
        if($this->form_validation->run() == FALSE){
          // $this->load->view('template/header');
          //   $this->load->view('header');
          //   $this->load->view('left-sidebar');
          //   $this->load->view('add_driver', $this->data);
          //   $this->load->view('footer');
        }else{
/*echo "<pre>";
print_r($_FILES);
echo "</pre>";
die;*/
          $upload_path = "public/profilePic/";
          $image = "profile_pic";
          $profile_pic = $this->do_upload($upload_path, $image);

          $upload_path = "document/";
          $image = "document";
          $document = $this->do_upload($upload_path, $image);

          $upload_path = "vehicle_pic/";
          $image = "vehicle_pic";
          $vehicle_pic = $this->do_upload($upload_path, $image);

          $upload_path = "license_pic/";
          $image = "license_pic";
          $license_pic = $this->do_upload($upload_path, $image);

          $upload_path = "insurance_pic/";
          $image = "insurance_pic";
          $insurance_pic = $this->do_upload($upload_path, $image);

          $upload_path = "registration_pic/";
          $image = "registration_pic";
          $registration_pic = $this->do_upload($upload_path, $image);

          $upload_path = "registration_plate_pic/";
          $image = "registration_plate_pic";
          $registration_plate_pic = $this->do_upload($upload_path, $image);

           /* echo "$profile_pic"."<br/>";
            echo "$license_pic"."<br/>";
            echo "$registration_pic"."<br/>";
            echo "$document"."<br/>";
            echo "$insurance_pic"."<br/>";
            echo "$registration_plate_pic"."<br/>";
            die();*/
          $md5_pass = md5($password);
          
          $result = $this->Admin_model->add_driver($md5_pass, $profile_pic, $license_pic, $registration_pic, $insurance_pic, $vehicle_pic, $document, $registration_plate_pic);  //------------ Add details to database
            if (!empty($_POST['commission'])) {
              $date_modified = date("Y-m-d H:i:s");
              $commissionData = array(
                'driver_id'=>$result,
                'commission'=>$_POST['commission'],
                'commission_from'=>$_POST['commission_from'],
                'commission_to'=>$_POST['commission_to'],
                'date_modified'=> $date_modified
              );
              $this->Admin_model->insert('tbl_commission',$commissionData);
            }
            
            if (!empty($_POST['from'])) {
                $permit_data = array(
                  'driver_id'=>$result,
                  'from'=>$_POST['from'],
                  'to'=>$_POST['to'],
                  'addedOn'=>date("Y-m-d H:i:s"),
                );
                $this->Admin_model->insert('tbl_permit',$permit_data);
            }
//-------------------------------------- email verification 
          // print_r($result);
          // die();
          if($result == 0){
  $this->session->set_flashdata('message', "Sorry !! This user is already registered Once");
          // echo $this->session->flashdata('message');
          // die();
         /* $this->load->view('template/header');
            $this->load->view('header');
            $this->load->view('left-sidebar');
            $this->load->view('add_driver');
            $this->load->view('footer');

*/
           //redirect('Driver/add_driver');

          } else{
            // print_r($result);die();   
          $id1 = $result;
           
              $static_key = "afvsdsdjkldfoiuy4uiskahkhsajbjksasdasdgf43gdsddsf";
              $ids = $id1."_".$static_key;
              $b_id = base64_encode($ids);
             $url = base_url('verification')."/?id=".$b_id;

          $email = $this->input->post('email');
          if ($email!="") {
            $body = "<!DOCTYPE html>
              <head>
                <meta content=text/html; charset=utf-8 http-equiv=Content-Type /> 
                <title>Account Verification</title>
                <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
              </head>
              <body>    
                <table width=60% border=0 bgcolor=#FFCC66 style=margin:0 auto; float:none;font-family: 'Open Sans', sans-serif; padding:0 0 10px 0;>
                  <tr>
                    <th width=20px></th>
                    <th style=padding-top:30px;padding-bottom:30px;> <img src=".base_url()."public/images/avatar1_small.jpg width=150></th>
                    <th width=20px></th>
                  </tr>
                  <tr>
                    <td width=20px></td>
                    <td bgcolor=#fff style=border-radius:10px;padding:20px;>      
                      <table width=100%;>
                        <tr>
                          <th style=font-size:20px; font-weight:bolder; text-align:right;padding-bottom:10px;border-bottom:solid 1px #ddd;> Hello </th>
                        </tr>
                        <td style=font-size:16px;>
                            <p> Your login Credentials are given below:</p>
                            <p>Email Id : ". $email ." </p>
                            <p>Password : ". $password ."</p>
                        <tr>
                          <td style=font-size:16px;>
                            <p> You have request to signup your account for MASIR.To complete the process, click the link below.</p>
                            <p><a href='" . $url . "' target='_blank'>".$url."</a></p>
                          </td>                    
                        </tr>
                        <tr>
                          <td style=text-align:center; padding:20px;>
                            <h2 style=margin-top:50px; font-size:29px;>Best Regards,</h2>
                            <h3 style=margin:0; font-weight:100;>Customer Support</h3>
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
          }
        /*otp*/
        $otp_data['phone']=$this->input->post('phone');
        $otp_data['message']='your Password is: '.$password.' \r\n Please keep it confidential.';
         $otpResult = $this->otp($otp_data);
        /**/
          // echo $result;
          // print_r($this->data);die();

          $_POST = array();
          $this->session->set_flashdata('message', "Details added Successfully!!!, Password:$password");
          // echo $this->session->flashdata('message');
          // die();
         // redirect('Driver/add_driver');
            // $this->load->view('template/header');
            // $this->load->view('header');
            // $this->load->view('left-sidebar');
            // $this->load->view('add_driver');
            // $this->load->view('footer');
        }
      }
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('add_driver',$this->data);
        $this->load->view('footer');
            
    }

    public function checkReferral()
    {
      if (!empty($this->input->post('referral_code'))) {
      	$refData = array('promo_code'=>$this->input->post('referral_code'));
          $ref = $this->Admin_model->checkReferral($refData);
        // print_r($ref);die;
        if (empty($ref)) {
        	$this->form_validation->set_message('checkReferral', 'Invalid Referral Code.');
        	// $this->session->set_flashdata('checkReferral', "Invalid Referral Code");
        	return false;
        }else{
        	return TRUE;
        }
      }else{
        return TRUE;
      }
    }

    public function do_upload($upload_path, $image)
    {
        $baseurl = base_url();
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '1000000';
        $config['max_width']  = '20000';
        $config['max_height']  = '20000';

        $this->upload->initialize($config);
        if (!$this->upload->do_upload($image))
        {
            $error = array('error' => $this->upload->display_errors());
            // print_r($error);die;
            return $imagename = "";
        }
        else
        {
            $datail = $this->upload->data();
            return $imagename = $baseurl.$upload_path.$datail['file_name'];
        }
    }

    public function otp($data=NULL)
    {
     // error_reporting(0);  
     $sms_username = 'ar.naderi';
     $sms_password = 'sh8938411';
     $from_number = array(20008580);
     $to_number = array($data['phone']);

    $date=date("d/m/Y H:i"); //Date example
    list($day, $month, $year, $hour, $minute) = split('[/ :]', $date); 

    //The variables should be arranged according to your date format and so the separators
    $timestamp = mktime($hour, $minute, 0, $month, $day, $year);

     $sendDate = array($timestamp); 
     $message = array($data['message']);

          libxml_disable_entity_loader(false);
          $client = new SoapClient("http://parsasms.com/webservice/v2.asmx?WSDL");
       
          $params = array(
            'username'  => $sms_username,
            'password'  => $sms_password,
            'senderNumbers' => $from_number,
            'recipientNumbers'=> $to_number,
            'sendDate'=> $sendDate,
            'messageBodies' => $message
          );
           
          $results = $client->SendSMS($params);
          return $results;
          // print_r($results);die; 
    }
}

?>