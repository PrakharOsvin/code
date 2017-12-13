<?php
// error_reporting(E_ALL);
// ini_set('display_errors',1);
defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

  /**
 * API for app
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis, Rohit Dhiman
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class UserNew extends REST_Controller {

  function __construct() {
      // Construct the parent class
      parent::__construct();
      // error_reporting(E_ALL); ini_set('display_errors', 1);
      // Configure limits on our controller methods
      // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
      //  $this->methods['user_get']['limit'] = 500; // 500 requests per hour per user/key
      // $this->methods['user_post']['limit'] = 100; // 100 requests per hour per user/key
      // $this->methods['user_delete']['limit'] = 50; // 50 requests per hour per user/key
      $this->load->model('User_model_new','User_model');
      $this->load->model('Admin_model');
      // $this->load->helper('date');
      $this->load->helper(array('date','form', 'url','googledirections_helper'));
/*
     $config['protocol'] = "smtp";
      $config['smtp_host'] = "91.232.66.24";
      $config['smtp_port'] = 25;
      $config['smtp_user'] = "billing@aber.ir"; 
      $config['smtp_pass'] = "Bhmd#25736";
      $config['charset'] = "utf-8";
      $config['mailtype'] = "html";
      $config['newline'] = "\r\n";
      $config['crlf'] = '\r\n';
      // $config['smtp_crypto'] = 'tls';
      $config['smtp_timeout'] = 5;
      $config['wordwrap'] = TRUE;
      $this->load->library('email', $config);*/

      $this->load->library('form_validation');
      // $this->load->library('../controllers/Push_notification.php');
       $this->load->database(); 
      /* $this->load->helper('url'); */
  }

  public function login_post()
  {
    // $this->User_model->autoDeactivate();
    $device_id = $this->post('device_id');
    $phoneNumber = $this->post('phoneNumber');
    $unique_device_id = $this->input->post('unique_device_id');
    $user_type = $this->input->post('user_type');

    /*To check froud lock*/
    $status = $this->User_model->selectdata('tbl_users','status',array('status'=>0,'phone'=>$phoneNumber));

    $isExist = $this->User_model->selectdata('tbl_users', 'id', array('phone'=>$phoneNumber));
    if (empty($isExist)) {
      $isExist = 0;
    } else {
      $isExist = 1;
    }
    if ($user_type==2&&$isExist==0) {
      $response = array(
        "controller" => "user",
        "action" => "login",
        "Errorcode" => "420",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Please contact your admin to register",
      );
      
      $this->set_response($response, REST_Controller::HTTP_OK);
      return false;
    } 
    $otp = $this->generateRandomString();
    $otp_data['phone']=$phoneNumber;
    
    if ($isExist==0) {
      // $otp_data['message']= "به خانواده آبر خوش آمدید. کد تایید شما:$otp";
       $otp_data['message']= "به خانواده آبر خوش آمدید. کد فعالسازی:$otp";
    } else {
      // $otp_data['message']= "از ورود مجدد شما به آبر خرسندیم. کد تایید شما:$otp";
      // $otp_data['message']= "از ورود مجدد شما به آبر خرسندیم. کد فعالسازی:$otp";
      $otp_data['message']= "از ورود مجدد شما به آبر خرسندیم.
 کد فعالسازی:$otp";
    }

    if (!empty($status)&&$user_type==2) {
      $response = array(
        "controller" => "user",
        "action" => "login",
        "Errorcode" => "400",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Ask your Admin to activate your account.",
      );
      
      $this->set_response($response, REST_Controller::HTTP_OK);
      return false;
    } 
    /*check inputs*/
    elseif (empty($phoneNumber)) {
      $response = array(
        "controller" => "user",
        "action" => "Login",
        "Errorcode" => "200",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Please Provide your Mobile Number"
      );
      $this->set_response($response, REST_Controller::HTTP_OK);  
      return false;
    }elseif ($user_type!=""&&$isExist==1) {
      $driverCheck = $this->User_model->selectdata('tbl_users','id',array('phone'=>$phoneNumber,'user_type'=>$user_type));
      // print_r($driverCheck);die;
      if (empty($driverCheck)&&$user_type==0) {
        $response = array(
          "controller" => "user",
          "action" => "Login",
          "Errorcode" => "202",
          "ResponseCode" => false,
          "MessageWhatHappen" => "There seems to be a driver with same phone number, use driver app to login."
        );
        $this->set_response($response, REST_Controller::HTTP_OK);  
        return false;
      }elseif (empty($driverCheck)&&$user_type==2) {
        $response = array(
          "controller" => "user",
          "action" => "Login",
          "Errorcode" => "201",
          "ResponseCode" => false,
          "MessageWhatHappen" => "There seems to be a passenger with same phone number, use passenger app to login."
        );
        $this->set_response($response, REST_Controller::HTTP_OK);  
        return false;
      }
    }

    // if ($device_id==1) {
    //   $otp_data['message']=' ‫کاربر گرامی به مسیر خوش آمدید.لطفا کد Masir://data/'.$otp.' را در نرم افزار مسیر، وارد نمایید';
    // } else {
    // }
    // $otp_data['message']= "$otp:از ورود مجدد شما به آبر خرسندیم. کد تایید شما";
    // print_r($otp_data);die;
    // $otpResult = $this->aberSendSms($otp_data);

    // print_r($otpResult);die;
    if ($otpResult==500) {
      $response = array(
        "controller" => "user",
        "action" => "aberSendSms",
        "Errorcode" => "500",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Sorry... our service is down",
        'isExist' => $isExist
      );
      $this->set_response($response, REST_Controller::HTTP_OK);
      return false;
    }
      $otpError = 0;
      // $SendSMSResult = $otpResult->SendSMSResult;
      $isSend = $this->User_model->selectdata('tbl_otp','id',array('phoneNumber'=>$phoneNumber));
      // print_r($isSend);die();
      if (empty($isSend)) {
        $this->User_model->insertdata('tbl_otp',array('phoneNumber'=>$phoneNumber,'otp'=>$otp,'response_code'=>$otpError , 'added_on'=>date("Y-m-d H:i:s")));
      }else{
        $this->User_model->updatedata('tbl_otp',array('phoneNumber'=>$phoneNumber),array('otp'=>$otp,'response_code'=>$otpError , 'added_on'=>date("Y-m-d H:i:s"), 'status'=>0));
      }
      
      // print_r($phoneNumber);
      // print_r($this->db->last_query());
      // die;
      $response = array(
        "controller" => "user",
        "action" => "Login",
        "Errorcode" => "200",
        "ResponseCode" => true,
        "MessageWhatHappen" => "OTP Sent Successfully, Please verify your Mobile Number",
        'isExist' => $isExist
      );
    // print_r($otpResult);die;
    $this->set_response($response, REST_Controller::HTTP_OK);  
  }

  function generateRandomString($length = 4) {
      $characters = '0123456789';
      $charactersLength = strlen($characters);
      $randomString = '';
      for ($i = 0; $i < $length; $i++) {
          $randomString .= $characters[rand(0, $charactersLength - 1)];
      }
      return $randomString;
  }

  public function verifyOtp_post()
  {
    $otp = $this->input->post('otp');
    $phoneNumber = $this->input->post('phoneNumber');
    $date = date("Y-m-d H:i:s");

    $unique_device_id = $this->input->post('unique_device_id');
    $token_id = $this->input->post('token_id');
    $device_id = $this->input->post('device_id');
    // print_r($_POST);die;

    /*To check OTP*/
    $db_result = $this->User_model->selectdata('tbl_otp','*',array('phoneNumber'=>$phoneNumber,'otp'=>$otp));
    if (empty($db_result)) {
      $response = array(
        'controller'=>'user',
        'action'=>'verifyOtp',
        'Errorcode'=>'200',
        'ResponseCode'=>false,
        'MessageWhatHappen'=>'OTP doesn\'t match'
      );
    }
    else{
      $this->User_model->updatedata('tbl_otp',array('phoneNumber'=>$phoneNumber),array('status'=>1));

      $this->User_model->updatedata('tbl_users',array('phone'=>$phoneNumber),array('unique_device_id'=>$unique_device_id));

      $user_info['user_info'] = $this->User_model->selectdata('tbl_users','*',array('phone'=>$phoneNumber));
// print_r($user_info);die;
      /*To check If User exist*/
      $issuetype = array('issue_type' => 12,);
      $issue_list = $this->User_model->issue_list($issuetype);

      if (!empty($user_info['user_info'])) {
        $user_id = $user_info['user_info'][0]->id;
        $user_type = $user_info['user_info'][0]->user_type;
        /*Login*/
        $loginData = array('user_id'=>$user_id,'device_id'=>$device_id,'token_id'=>$token_id,'unique_device_id'=>$unique_device_id,'status'=>1,'user_type'=>$user_type,'date_created'=>date("Y-m-d H:i:s"));
        // $this->User_model->insertdata('tbl_login',$loginData);
        $userInfo = $this->User_model->login($loginData);

        $coupon = $this->User_model->selectdata('tbl_promocode', '*', array(
          'promocode_beneficiary_id' => $user_id ,
          'status' => '2'
        ));

        $response = array(
          "controller" => "user",
          "action" => "verifyOtp",
          "Errorcode" => "200",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Welcome",
          'coupon' => $coupon,
          'UserData' => $userInfo,
          'issue_list'=>$issue_list
        );
      }else{
        $response = array(
          'controller'=>'user',
          'action'=>'verifyOtp',
          'Errorcode'=>'200',
          'ResponseCode'=>true,
          'MessageWhatHappen'=>'Phone number verified',
          'UserData'=>$user_info,
          'issue_list'=>$issue_list
        );
      }
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  function signup_post(){
    
    $phone = $this->input->post('phoneNumber');
    $first_name = $this->input->post('first_name');
    $last_name = $this->input->post('last_name');
    // $email = $this->input->post('email');
    // $gender = $this->input->post('gender');
    // $dob = $this->input->post('dob');
    $unique_device_id = $this->input->post('unique_device_id');
    $token_id = $this->input->post('token_id');
    $device_id = $this->input->post('device_id');
    $user_type = 0;
    /*is exist*/
    $isExist = $this->User_model->selectdata('tbl_users', 'id', array('phone'=>$phone));
    /*Is verified*/
    $is_verified = $this->User_model->selectdata('tbl_otp', 'status', array(
          'phoneNumber' => $phone ,
          'otp_type' => '0',
          'status' => 1
        ));
    if (!empty($isExist)) {
      $response = array(
        "controller" => "user",
        "action" => "signup",
        "Errorcode" => "200",
        "ResponseCode" => false,
        "MessageWhatHappen" => "User already exist please login to continue.",
      );
    } elseif (empty($is_verified)) {
      $response = array(
        "controller" => "user",
        "action" => "signup",
        "Errorcode" => "200",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Not Verified.",
      );
    } else {
      /*Signup*/
      $signupData = array('phone'=>$phone,'first_name'=>$first_name,'last_name'=>$last_name,'device_id'=>$device_id,'unique_device_id'=>$unique_device_id,'phone_verified'=>1,'date_created'=>date("Y-m-d H:i:s"));
      // $this->User_model->insertdata('tbl_users',$signupData);
      $insert_id = $this->User_model->signup($signupData);

      /*Login*/
      $user_id = $insert_id;
      $loginData = array('user_id'=>$user_id,'user_type'=>$user_type,'device_id'=>$device_id,'token_id'=>$token_id,'unique_device_id'=>$unique_device_id,'status'=>1,'date_created'=>date("Y-m-d H:i:s"));
      // $this->User_model->insertdata('tbl_login',$loginData);
      $userInfo = $this->User_model->login($loginData);

      // $userInfo = $this->User_model->selectdata('tbl_users','*',array('id'=>$user_id));

      $response = array(
        "controller" => "user",
        "action" => "signup",
        "Errorcode" => "200",
        "ResponseCode" => true,
        "MessageWhatHappen" => "Welcome",
        'UserData' => $userInfo
      );
    }

    $this->set_response($response, REST_Controller::HTTP_OK);  
  }

  function forgotpassword_post() {  //old

      $email = $this->post('email');
      $user_type = $this->post('user_type');
      $id = $this->User_model->forgotpassword($email,$user_type);
      // print_r($email);die;
      if ($id == "Email does not exist in our database.") {
          $result = array(
              "controller" => "user",
              "action" => "forgotpassword",
              "ResponseCode" => false,
              "MessageWhatHappen" => $id
          );
      } else {
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
      $result = array(
        "controller" => "user",
        "action" => "forgotpassword",
        "ResponseCode" => true,
        "MessageWhatHappen" => "Mail Sent Successfully"
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function forgotpassword_get()
  {
    $phone = $this->input->get('phone');
    if (empty($phone)) {
      $result = array(
        "controller" => "user",
        "action" => "forgotpassword",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Please enter phone number"
      );
    } else {
      $db_result=$this->User_model->selectdata('tbl_users','id,phone',array('phone'=>$phone, 'user_type' =>'0'));
      if (empty($db_result)) {
        $result = array(
          "controller" => "user",
          "action" => "forgotpassword",
          "ResponseCode" => false,
          "MessageWhatHappen" => "No record found"
        );
      } else {
        $phone = $db_result[0]->phone;
        $id1 = $db_result[0]->id;
        $otp = rand(0000,9999);
        $otp_data['phone']=$phone;
        $otp_data['message']="کاربر گرامی گذر واژه موقت  شما $otp میباشد.
لطفا پس از ورود نسبت به انتخاب گذر واژه جدید اقدام نمایید.";
        $otpResult = $this->aberSendSms($otp_data);       
        if ($otpResult==500) {
          $response = array(
            "controller" => "user",
            "action" => "aberSendSms",
            "Errorcode" => "200",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Sorry... our service is down",
            'isExist' => $isExist
          );
          $this->set_response($response, REST_Controller::HTTP_OK);
          return false;
        }
        $otpError = 0;
        $SendSMSResult = $otpResult->SendSMSResult;

        $this->User_model->insertdata('tbl_otp',array('user_id'=>$id1,'otp'=>$otp,'response_code'=>$otpError , 'added_on'=>date("Y-m-d H:i:s"),'otp_type'=>1));

        $result = array(
          "controller" => "user",
          "action" => "forgotpassword",
          "ResponseCode" => true,
          'otp'=>$otp,
          'user_id'=>$id1,
          "MessageWhatHappen" => $SendSMSResult
        );
      }
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function verifyFpOtp_get()
  {
    $data = $_GET;
    $otp = $this->input->get('otp');
    $user_id = $this->input->get('user_id');
    // $id = $this->User_model->loginData($user_id);
    // print_r($_GET);die;
    $db_result = $this->User_model->selectdata('tbl_otp','otp',array('user_id'=>$user_id,'otp'=>$otp,'otp_type'=>1));
    if (count($db_result)>0) {
      // print_r($data);die;
      $id = $this->User_model->loginData($data);
      $result = array(
        'controller'=>'user',
        'action'=>'verifyOtp_get',
        'Errorcode'=>'200',
        'ResponseCode'=>true,
        "MessageWhatHappen" => "Logged in Successfully",
        "GetData" => $id
        );
    } else {
      $result = array(
        'controller'=>'user',
        'action'=>'verifyOtp_get',
        'Errorcode'=>'200',
        'ResponseCode'=>false,
        'MessageWhatHappen'=>'OTP doesn\'t match'
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }


  function driver_login_post() {
    // print_r($this->post());die;
    $inputs = array('password'=>md5($this->post('password')),
      'token_id' => $this->post('token_id'),
      'device_id' => $this->post('device_id'),
      'unique_device_id'=>$this->post('unique_device_id'),
    );
    $email_phone = $this->post('email_phone');
    if ($inputs['password']==""||$inputs['token_id']==""||$inputs['device_id']==""||$inputs['unique_device_id']==""||$email_phone=="") {
      $result = array(
              "controller" => "user",
             "action" => "driver_login",
              "ResponseCode" => false,
              "MessageWhatHappen" =>"Please Enter The Required Credentials"
             );
    }else{
      if (filter_var($email_phone, FILTER_VALIDATE_EMAIL)) {
        // echo "email format";
        $inputs['email'] = $email_phone;

        $db_result = $this->User_model->driver_login($inputs);
      }elseif (ctype_digit($email_phone)) {
        // echo "phone";
        $inputs['phone'] = $email_phone;
        $db_result = $this->User_model->driver_login($inputs);
      }else{
        $result = array(
                "controller" => "user",
                "action" => "driver_login",
                "ResponseCode" => false,
                "MessageWhatHappen" => "Please enter valid email id or phone"
            );
        header('content-type:application/json');
        echo(json_encode($result));
        die;
      }
      // print_r($db_result);die;
      if ($db_result==2) {
        $result = array(
                  "controller" => "user",
                  "action" => "driver_login",
                  "ResponseCode" => false,
                  "MessageWhatHappen" =>'Incorrect email or password'
             );
      }elseif ($db_result==404) {
        $result = array(
              "controller" => "user",
              "action" => "driver_login",
              "ResponseCode" => false,
              "MessageWhatHappen" => "This email or phone is not registered yet"
          );
      }elseif ($db_result=="f1") {
        $result = array(
              "controller" => "user",
              "action" => "driver_login",
              "ResponseCode" => false,
              "MessageWhatHappen" => "Ask your Admin to activate your account"
          );
      }elseif ($db_result==409) {
        $result = array(
                  "controller" => "user",
                  "action" => "driver_login",
                  "ResponseCode" => false,
                  "MessageWhatHappen" =>"Seems like you haven't verified your email/phone yet"
             );
      }elseif ($db_result['user_info']>0) {

        $result = array(
                  "controller" => "user",
                  "action" => "driver_login",
                  "ResponseCode" => true,
                  "MessageWhatHappen" => "Logged in Successfully",
                  "GetData" => $db_result
              );
      } else {
        $result = array(
              "controller" => "user",
              "action" => "signup",
              "ResponseCode" => false,
              "MessageWhatHappen" => "Something Went wrong"
          );
      }
      
    } 
    // print_r($inputs);
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  function newpassword_get($reset_token) {
    if ($this->get('id')!="") {
      $data['id'] = $this->get('id');
      $data['db_result'] = array(1,2,3,43,4,);
      $this->load->view('template/header');
      $this->load->view('newpasswordM', $data);
    } else {
      $userid=base64_decode($reset_token);
      $useridArr = explode("_", $userid);
      $userid = $useridArr[1];
      $data['id'] = $userid;
      $data['db_result'] = $this->User_model->selectdata("tbl_password_recovery","id",array('user_id'=>$data['id'],'reset_token'=>$reset_token,'token_status'=>0));
      $this->load->view('template/header');
      $this->load->view('newpassword', $data);
    }
      // print_r($db_result);die;
      // echo "<pre>";print_r($data);die;
      
  }

  function updateNewpassword_post() {
//    echo "hfhsd";
      $uid = $this->input->post('id');
      $static_key = "afvsdsdjkldfoiuy4uiskahkhsajbjksasdasdgf43gdsddsf";
      $id = $uid . "_" . $static_key;
      $id = base64_encode($id);
//    echo $uid; die;

      $message = [
          'id' => $this->input->post('id'),
          'password' => $this->input->post('password'),
          'base64id' => $id
      ];

      $this->load->library('form_validation');
      $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[passconf]|md5');
      $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|md5');

      if ($this->form_validation->run() == FALSE) {

          $this->session->set_flashdata('msg', '<span style="color:red">Please Enter Same Password</span>');
          redirect("api/user/newpassword?id=" . $message['id']);
      } else {

          $this->User_model->updateNewpassword($message);
          
          // print_r($this->db->last_query());die;
      }
      //    $this->User_model->updateNewpassword($message);
      /*  $this->set_response($id, REST_Controller::HTTP_OK); */
  }

  function changepassword_post() {
      $message = [
          'user_id' => $this->post('user_id'),
          'oldpassword' => $this->post('oldpassword'),
          'newpassword' => $this->post('newpassword')
      ];
      
      $id = $this->User_model->changepassword($message);
      if ($id == "updated") {
          $result = array(
              "controller" => "user",
              "action" => "changepassword",
              "ResponseCode" => true,
              "MessageWhatHappen" => "Password Changed Successfully"
          );
      } elseif ($id == "not updated") {
          $result = array(
              "controller" => "user",
              "action" => "changepassword",
              "ResponseCode" => false,
              "MessageWhatHappen" => "Incorrect Old Password"
          );
      }
      $this->set_response($result, REST_Controller::HTTP_OK);
  }


  function updateLatLong_get() {
    // error_reporting(E_ALL);
    //   ini_set('display_errors',1);
    //print_r(date("Y-m-d G:i:a")); die;
      $message = [
          'user_id' => $this->get('user_id'),
          'latitude' => $this->get('latitude'),
          'longitude' => $this->get('longitude'),
          'token_id' => $this->get('token_id'),
          'language' => $this->get('language'),
          'job_id' => $this->get('job_id'),
      ];
      $getUserLogin = $this->User_model->selectdata("tbl_login","token_id",array('status'=>'1','user_id'=>$message['user_id'],'token_id'=>$message['token_id']));

      if (count($getUserLogin)==0) {
        $getUserLogout = $this->User_model->selectdata("tbl_logout","*",array('user_id'=>$message['user_id']),'','id','desc',1);
        // print_r($getUserLogout[0]->logedOutBy);
        $result = array(
                "controller" => "user",
                "action" => "UpdateLatLong",
                "ResponseCode" => false,
                'job_id'=>$this->get('job_id'),
                "MessageWhatHappen" => $getUserLogout[0]->logedOutBy
            );
      }   
      else
      {
        $address = "";
        if ($message['job_id']!="") {
          $status = $this->User_model->job_status2($message['job_id']);
        } else {
          // $status = $this->User_model->job_status("",$message['user_id']);
          $status = "";
        }
        $id = $this->User_model->updateLatLong($message);

          $pessenger_id = $status->user_id;
          if (!empty($pessenger_id)) {
            $pessenger_info = $this->User_model->selectdata('tbl_users','first_name, last_name, latitude,longitude,(SELECT device_id FROM `tbl_login` where user_id='.$pessenger_id.' order by id desc limit 1) as device_id',array('id'=>$pessenger_id));
          } else {
            $pessenger_info = [];
          }
          

        //--------------- check whether in wrong place or not---------
        $restricted = false;
        $today = date("Y-m-d");
        $time = date('Gi');
        $select_date = $this->db->query("SELECT * from tbl_holidays WHERE holiday = '$today' ")->result();
        // print_r(date('l'));die;
        $permited =$this->User_model->permited($message['user_id']);

        // $rzone_time = $this->db->query("SELECT * from tbl_rzone_time")->result();
        // print_r($rzone_time);die;
        
        if (count($select_date)==0 && date('l')!="Friday" && empty($permited)) {
          $latlng = array('pickup_lat'=>$message['latitude'],'pickup_long'=>$message['longitude']);
          if (date('l')=="Sunday" && ($time>=0545 && $time<=1700))
          { // B
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('C',$message['user_id']);
          }
          elseif (date('l')=="Monday" && ($time>=0545 && $time<=1700))
          { // C
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('B',$message['user_id']);
          }
          elseif (date('l')=="Tuesday" && ($time>=0545 && $time<=1700))
          { // B
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('C',$message['user_id']);
          }
          elseif (date('l')=="Saturday" && ($time>=0545 && $time<=1700))
          { // C
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('B',$message['user_id']);
          }
          elseif (date('l')=="Wednesday" && ($time>=0545 && $time<=1700))
          { // C
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('B',$message['user_id']);
          }
          elseif (date('l')=="Thursday" && ($time>=0545 && $time<=1300))
          { // B
            $checkPosition = $this->User_model->checkPosition($latlng);
            $driverPermit =$this->User_model->driverPermit('C',$message['user_id']);
          }else{
            $checkPosition = array();
            $checkPosition[rz] = 0;
            $checkPosition[oe] = 0;
            $driverPermit = array();
          }

          if ($checkPosition[rz]==1) {
          $this->User_model->updatedata('tbl_users',array('id'=>$message['user_id']),array('is_restricted'=>1));
          $restricted = true;
        } elseif($checkPosition[oe]==1 && !empty($driverPermit)) {
          $this->User_model->updatedata('tbl_users',array('id'=>$message['user_id']),array('is_restricted'=>1));
          $restricted = true;
        }else{
          $this->User_model->updatedata('tbl_users',array('id'=>$message['user_id']),array('is_restricted'=>0));
        }
          // print_r($checkPosition[rz]);die;
        }else{
          $this->User_model->updatedata('tbl_users',array('id'=>$message['user_id']),array('is_restricted'=>0));
        }
        //----------------------End----------------
        $earning = $this->User_model->select_by_query("SELECT sum(amount) as daily_earning FROM `tbl_payment` where user_id=".$message['user_id']." and date(date_created)=date(now())");
         // print_r($earning[0]->daily_earning);die;
        $daily_earning = $earning[0]->daily_earning;
        $daily_earning = (is_null($daily_earning)) ? 0 : $daily_earning;
         //print_r($push_info);die;
        $not =$this->User_model->notification($message['user_id'],2);
        /*=====================*/
          $wb = $this->User_model->selectdata("tbl_users","wallet_balance",array('id' => $message['user_id']));
          $wallet_balance = $wb[0]->wallet_balance;
        /*======================*/
        if ($id == 1 && $status != "") {
            $push_info = $this->User_model->push_info($message['user_id']);  
            $result = array(
                "controller" => "user",
                "action" => "UpdateLatLong",
                "ResponseCode" => true,
                "MessageWhatHappen" => "LatLong Changed Successfully",
                "status_code" => $status,
                'ride_details'=>$push_info,
                'pessenger_info'=>$pessenger_info,
                'address' => $address,
                'restricted'=>$restricted,
                'daily_earning'=>$daily_earning,
                'wallet_balance'=>$wallet_balance,
                'job_id'=>$this->get('job_id'),
                'unread_count' => $not->unread
            );
        }elseif ($id == 1 && $status == "") {
                 $push_info = $this->User_model->push_info($message['user_id']);           
                 $result = array(
                      "controller" => "user",
                      "action" => "UpdateLatLong",
                      "ResponseCode" => true,
                      "MessageWhatHappen" => "LatLong Changed Successfully",
                      'ride_details'=>$push_info,
                      'pessenger_info'=>$pessenger_info,
                      'address' => $address,
                      'restricted'=>$restricted,
                      'daily_earning'=>$daily_earning,
                      'wallet_balance'=>$wallet_balance,
                      'job_id'=>$this->get('job_id'),
                      'unread_count' => $not->unread
                  );
        } elseif ($id == 0) {
            $result = array(
                "controller" => "user",
                "action" => "UpdateLatLong",
                "ResponseCode" => false,
                'job_id'=>$this->get('job_id'),
                "MessageWhatHappen" => "Error in updating LatLong"
            );
        }
      }
      $this->set_response($result, REST_Controller::HTTP_OK);
  }
  
  function getLatLong_get() {
      $message = [
          'user_id' => $this->get('user_id')
      ];
      // print_r($message);die();
      $id = $this->User_model->getLatLong($message);
      if ($id !== 0) {
          $result = array(
              "controller" => "user",
              "action" => "getLatLong",
              "ResponseCode" => true,
              "MessageWhatHappen" => "Location is:",
              "data" => $id
          );
      } elseif ($id == 0) {
          $result = array(
              "controller" => "user",
              "action" => "getLatLong",
              "ResponseCode" => false,
              "MessageWhatHappen" => "Location not found"
          );
      }
      $this->set_response($result, REST_Controller::HTTP_OK);
  }

  function driversNearby_post() {
    $message = [

        'user_id' => $this->post('user_id'),
        'latitude' => $this->post('latitude'),
        'longitude' => $this->post('longitude')
    ];
    $token_id = $this->post('token_id');

    $getUserLogin = $this->User_model->selectdata("tbl_login","token_id",array('status'=>'1','user_id'=>$message['user_id']));
    // print_r($getUserLogin);die;
    $user_id = $this->post('user_id');
    $where = array('id' => $user_id);
    $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    $wallet_balance = $wb[0]->wallet_balance;
    $not =$this->User_model->notification($user_id,0);
    if ($getUserLogin[0]->token_id != $token_id) {
    $result = array(
            "controller" => "user",
            "action" => "DriversNearby",
            "ResponseCode" => false,
            "message" => "session expired",
            'wallet_balance'=>$wallet_balance,
            'unread_count' => $not->unread
        );
    }else{
      $vehicle_type = $this->User_model->get_vehicle_list();
      $id = $this->User_model->driversNearby($message);
   
    if(count($id)>0) {

      $result = array(
          "controller" => "user",
          "action" => "DriversNearby",
          "ResponseCode" => true,
          "MessageWhatHappen" => $id,
          "Vehicle Type" => $vehicle_type,
          'wallet_balance'=>$wallet_balance,
          'unread_count' => $not->unread
      );
    }else{
        $result = array(
            "controller" => "user",
            "action" => "DriversNearby",
            "ResponseCode" => false,
            "MessageWhatHappen" => "No drivers Found",
            "Vehicle Type" => $vehicle_type,
            'wallet_balance'=>$wallet_balance,
            'unread_count' => $not->unread
        );
    }
  }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  function jobs_post() {
    $message = [
        'userId' => $this->post('userId'),
        'pickup_location' => $this->post('pickup_location'),
        'drop_off_location' => $this->post('drop_off_location'),
        'pickup_lat' => $this->post('pickup_lat'),
        'pickup_long' => $this->post('pickup_long'),
        'dropoff_lat' => $this->post('dropoff_lat'),
        'dropoff_long' => $this->post('dropoff_long'),
        'job_datetime' => $this->post('job_datetime'),
        'message' => $this->post('message'),
        'type' => $this->post('type'),
        'job_id' => $this->post('job_id'),
        'is_seen' => $this->post('is_seen')
    ];

    $id = $this->User_model->jobs($message);
    if ($id != "") {
        $result = array(
            "controller" => "user",
            "action" => "addjob",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Job added Successfully",
            "GetData" => $id
        );
    } else {
        $result = array(
            "controller" => "user",
            "action" => "addFriend",
            "ResponseCode" => false,
            "MessageWhatHappen" => "unsuccessfull"
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }


  function logout_post() {
    $message = [
        'user_id' => $this->post('user_id'),
        'token_id' => $this->post('token_id'),
        'unique_device_id' => $this->post('unique_device_id')
    ];
    $db_result = $this->User_model->selectdata('tbl_jobs','id',array('driver_id'=>$message['user_id'],'is_active'=>1));
    // print_r($this->db->last_query());die;
    // print_r($db_result);die;
    if (count($db_result)>0) {
      $result = array(
            "controller" => "user",
            "action" => "logout",
            "ResponseCode" => false,
            "MessageWhatHappen" => "You can't Logout as ride's going on",
            "UserData" => $db_result
        );
    }else{
      $id = $this->User_model->logout($message);
      if ($id == "0") {
          $result = array(
              "controller" => "user",
              "action" => "logout",
              "ResponseCode" => false,
              "MessageWhatHappen" => $id
          );
      } elseif ($id == "1") {
        $logData = array('user_id' => $message['user_id'],'logedOutBy'=>'user','logedOutOn'=>date("Y-m-d H:i:s") );
        $this->User_model->insertdata('tbl_logout', $logData);
          $result = array(
              "controller" => "user",
              "action" => "logout",
              "ResponseCode" => true,
              "MessageWhatHappen" => "Logged Out Successfully"
          );
      }
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  
  public function updateprofilepic_post() {

    $config['upload_path'] = 'public/profileImages';
    $config['allowed_types'] = 'gif|jpg|png';
    $config['max_size'] = 3000;
    $config['max_width'] = 10240;
    $config['max_height'] = 7680;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('userfile')) {
        $error = array('error' => $this->upload->display_errors());

        /*   $result = array(
          "controller" => "user",
          "action" => "updateprofile",
          "ResponseCode" => false,
          "MessageWhatHappen" => $error
          ); */
        $imagename = "";
    } else {
        $data = $this->upload->data();

        /* $result = array(
          "controller" => "user",
          "action" => "updateprofilepic",
          "ResponseCode" => true,
          "MessageWhatHappen" => $data
          ); */
        $imagename = $data['file_name'];
    }
    $message = array(
        'userId' => $this->post('userId'),
        'fullname' => $this->post('fullname'),
        'phone' => $this->post('phone'),
        'imagename' => $imagename
    );
    $id = $this->User_model->updateprofilepic($message);

    if ($id == 1) {
        $result = array(
            "controller" => "user",
            "action" => "updateprofile",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Updated Successfully"
        );
    } else {
        $result = array(
            "controller" => "user",
            "action" => "updateprofile",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Try Again"
        );
    }

    $this->set_response($result, REST_Controller::HTTP_OK);
  }


  function viewprofile_post() {
    $userId = $this->post('userId');
    $id = $this->User_model->viewprofile($userId);
    //echo "<pre>";print_r($id);die;
    if ($id == "") {
        $result = array(
            "controller" => "user",
            "action" => "viewtrainerprofile",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Try Again"
        );
    } else {
        $result = array(
            "controller" => "user",
            "action" => "viewtrainerprofile",
            "ResponseCode" => true,
            "UserData" => $id['userdata']
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }


  function phone_verification_post(){ 
    $phone = $this->post('phone'); 
    $otp = $this->post('otp');
    $message = $this->User_model->phone_verification($phone);
    if ($message == "") {
            $result = array(
            "controller" => "user",
            "action" => "phone_verification",
            "ResponseCode" => false);
        } else{
            $result = array(
            "controller" => "user",
            "action" => "phone_verification",
            "ResponseCode" => true);
        }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  function get_location_post()
  {
    $location = $this->post('location');
    $locations = $this->User_model->get_location_list($location);
    // print_r($locations);
    // die();
    if (count($locations)>0) {
        $result = array(
            "controller" => "user",
            "action" => "get_location",
            "ResponseCode" => true,
            "locations" => $locations);
        } else{
            $result = array(
            "controller" => "user",
            "action" => "get_location",
            "ResponseCode" => false);
        }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  function book_ride_post()
  {

    $data = array(
    'user_id' => $this->post('user_id'),
    'pickup_location' => $this->post('pickup_location'),
    'dropoff_location' => $this->post('dropoff_location'),
    'pickup_lat' => $this->post('pickup_lat'),
    'pickup_long' => $this->post('pickup_long'),
    'dropoff_lat' => $this->post('dropoff_lat'),
    'dropoff_long' => $this->post('dropoff_long'),
    'way_points' => $this->post('way_points'),
    'smoker' => $this->post('smoker'),
    'gender' => $this->post('gender'),
    'spk_english' => $this->post('spk_english'),
    'job_datetime' => date("Y-m-d H:i:s"),
    'estimate' => $this->post('estimate'),
    'blue_mode' => $this->post('blue_mode'),
    'vehicle_id' => 4,
    );
    // print_r($data);
    // die();
    // $ip = $this->input->ip_address();
    // $this->db->query("INSERT INTO `testDataFlow`(`job_id`, `ip`, `passenger_id`, `driver_id`, `function`, `itration`, `data`, `addedOn`) VALUES (0, '$ip', ".$data['user_id'].",0,'book_ride',1,0,now())");
    
    /*------------apply coupon if applied-------------------*/
    if ($this->post('promo_code')!="") {
      $apply_cupon_data = array('user_id' => $this->post('user_id'),'job_id'=>0,'promo_code' => $this->post('promo_code') );
      
      $result = $this->User_model->apply_cupon($apply_cupon_data);

      if ($result == "exist") {
          $respo = array(
          "controller" => "user",
          "action" => "apply_cupon",
          "ResponseCode" => false,
          'Errorcode' => 410,
          "message" => "already applied",
        );
        $this->set_response($respo, REST_Controller::HTTP_OK); 
        return false;
      }elseif ($result=="not") {
        $respo = array(
          "controller" => "user",
          "action" => "apply_cupon",
          "ResponseCode" => false,
          'Errorcode' => 411,
          "message" => "This offer is for new users only",
        );
        $this->set_response($respo, REST_Controller::HTTP_OK); 
        return false;
      }
      elseif (!empty($result)) {
          $respo = array(
          "controller" => "user",
          "action" => "apply_cupon",
          "ResponseCode" => true,
          'coupon_type' => $result,
          "message" => "Applied Successfully",);
      }
      else{
          $respo = array(
          "controller" => "user",
          "action" => "apply_cupon",
          "ResponseCode" => false,
          'Errorcode' => 412,
          "message" => "Invalid Promo code",);
        $this->set_response($respo, REST_Controller::HTTP_OK); 
        return false;
      }
    }
    /*----------------End promo part-----------------------*/

    $where = array('id' => $data['user_id']);
    /*---------Update Payment Method------------*/
    $est = explode(',', $data['estimate']);
    $est_fare = $est[2];
    $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    if ($wb[0]->wallet_balance<$est_fare) {
      $this->User_model->updatedata('tbl_users',$where,array('payment_method'=>0));
    } else {
      $this->User_model->updatedata('tbl_users',$where,array('payment_method'=>2));
    }
    
    $user_details = $this->User_model->selectdata("tbl_users","*",$where);
    // print_r($user_details[0]->payment_method);die;
    $where_cupon = array('promocode_beneficiary_id'=>$data['user_id'],'status'=>'2');
    $selected_cupon = $this->User_model->selectdata("tbl_promocode","value",$where_cupon);
    // print_r($selected_cupon);die;

    /*if ($user_details[0]->payment_method==2 && count($selected_cupon)>0 && $selected_cupon[0]->value+$user_details[0]->wallet_balance < 6400) {
      $respo = array(
        "controller" => "user",
        "action" => "book_ride",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Right now, you can't book a ride due to insufficient balance.",
        "wallet_balance"=>$user_details[0]->wallet_balance);
        $this->set_response($respo, REST_Controller::HTTP_OK);
    } 
    else*/
    if ($user_details[0]->payment_method==2 && $user_details[0]->wallet_balance < 0) 
    {
      $respo = array(
        "controller" => "user",
        "action" => "book_ride",
        "ResponseCode" => false,
        'Errorcode' => 450,
        "MessageWhatHappen" => "Right now, you can't book a ride due to insufficient balance.",
        "wallet_balance"=>$user_details[0]->wallet_balance);
      $this->set_response($respo, REST_Controller::HTTP_OK);
    } 
    else 
    {
      $data['payment_method'] = $user_details[0]->payment_method;
      $job_id = $this->User_model->book_ride($data);  

      $data['job_id'] = $job_id;      
      $client_info = $this->User_model->client_info($data); 

      /*===================Find Driver====================*/  
      $result = $this->User_model->find($data);
      /*====================================================*/

      $pickup_lat = $this->input->post('pickup_lat');
      $pickup_long = $this->input->post('pickup_long');
      $dropoff_lat = $this->input->post('dropoff_lat');
      $dropoff_long = $this->input->post('dropoff_long');
      
      $data12 = googleDirections($_POST);
   
      // $info['routes'] = $data12->routes[0];
      if ($data12->status != "OK")
      {
        // if ($data12) {
        $result1 = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'info' => $info,
          "MessageWhatHappen" => $data12,
        );
        $this->set_response($result1, REST_Controller::HTTP_OK); 
        return false;
      }
      else
      {
        $vehicle_type = $data['vehicle_id'];
        $vehicle_info = $this->User_model->selectdata('tbl_vehicle', '*', array(
          'id' => $vehicle_type
        ));

        // print_r($vehicle_info);die();

        $minFare = $vehicle_info[0]->base_rate;
        $per_km = $vehicle_info[0]->per_km;
        $traffic_cahrges_permin = $vehicle_info[0]->traffic_charges;

        // print_r($minFare);die;

        $distance = $data12->routes[0]->legs[0]->distance->value;
        $info['base_fare'] = $minFare;
        $info['per_km'] = $per_km;
        $info['traffic_cahrges_permin'] = $traffic_cahrges_permin;
        $info['distance'] = $distance/1000;
        // print_r($distance);
        if ($distance < 1000)
        {

          // print_r($distance);die;

          $info['estimated_fare'] = $minFare;
        }
        elseif ($distance >= 1000 && $distance < 8000)
        {
          $distanceData = $distance / 1000;
          // print_r($distanceData);
          $distanceRate = ($distanceData - 1) * $per_km;
          $info['estimated_fare'] = round(($distanceRate + $minFare) / 500) * 500;

          // $info['estimated_fare'] = $minFare;
          // print_r($info);die;

        }
        else
        {
          $duration = $data12->routes[0]->legs[0]->duration->value;
          $duration_in_traffic = $data12->routes[0]->legs[0]->duration_in_traffic->value;
          $divisor_for_minutes = $duration % (60 * 60);
          $duration = floor($divisor_for_minutes / 60);
          $divisor_for_minutes = $duration_in_traffic % (60 * 60);
          $duration_in_traffic = floor($divisor_for_minutes / 60);
          if ($duration_in_traffic > $duration)
          {
            $extraTime = $duration_in_traffic - $duration;
          }
          else
          {
            $extraTime = 0;
          }

          $newDistance = round(($distance - 1000) / 1000);
          // echo "$per_km";die;

          $traffic_cahrges = $extraTime * $traffic_cahrges_permin;
          $fare = $minFare + ($newDistance * $per_km) + $traffic_cahrges;

          $info['duration'] = $duration;
          $info['timeInTraffic'] = $extraTime;
          // //////////////////////////////////////////////////////////////////
          $fare1 = $minFare + ($newDistance * $per_km);
          $info['decFare'] = $fare1;
          $info['normal_fare'] = round($fare1 / 500) * 500;

          $info['estimated_fare'] = round(($fare1 + $traffic_cahrgess) / 500) * 500;

        }
      }
      /*--------------End Cal------------------------*/
      
      if ($info['estimated_fare']>$selected_cupon[0]->value) {
        $payable_amount = $info['estimated_fare']-$selected_cupon[0]->value;
      } else {
        $payable_amount = 0;
      }
      

      $insertData = array('start_address' => $data12->routes[0]->legs[0]->start_address,
      'end_address' => $data12->routes[0]->legs[0]->end_address,      
      'user_id' => $this->input->post('user_id'),
      'addedOn' => date("Y-m-d H:i:s"),
      'trip_id' => $job_id,
      'estimated_price' =>$info['estimated_fare'],
      'reason'  => '1',
      ); 
      if ($result == "false") {
          
      // $this->db->where('user_id', $data['user_id']);
      $this->db->insert('tbl_priceQuoteLog', $insertData); 
      
     // $this->User_model->updatedata("tbl_priceQuoteLog",$data['user_id'],$insertData);
     // print_r($this->db->last_query());die;
          $respo = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'Errorcode' => 401,
          'info' => $info,
          "MessageWhatHappen" => "No Driver Found");
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }elseif ($result == "RZ0") {
        $this->db->insert('tbl_priceQuoteLog', $insertData); 
        $respo = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'Errorcode' => 402,
          'info' => $info,
          "MessageWhatHappen" => "No drivers are available for this pickup restricted zone");
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }elseif ($result == "RZ1") {
        $this->db->insert('tbl_priceQuoteLog', $insertData); 
        $respo = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'Errorcode' => 403,
          'info' => $info,
          "MessageWhatHappen" => "No drivers are available for this dropoff restricted zone");
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }elseif ($result == "POE") {
        $this->db->insert('tbl_priceQuoteLog', $insertData); 
        $respo = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'Errorcode' => 405,
          'info' => $info,
          "MessageWhatHappen" => "No drivers are available for this pickup oe zone");
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }elseif ($result == "DOE") {
         $this->db->insert('tbl_priceQuoteLog', $insertData); 
      
         $respo = array(
          "controller" => "user",
          "action" => "book_ride",
          "ResponseCode" => false,
          'Errorcode' => 406,
          'info' => $info,
          "MessageWhatHappen" => "No drivers are available for this dropoff oe zone");
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }
      else{
          // print_r($result);die;
         $this->db->insert('tbl_priceQuoteLog', $insertData); 
      
          $device_id = $result['driver']->device_id;
          $token_id = $result['driver']->token_id;
          $user_id = $result['driver']->id;      // driver id
          $message = "you have new ride";
          $action = "book_ride";
          $ride_details = $client_info;

            //==========to prevent driver from getting another push============
            $datetime = date("Y-m-d H:i:s");
            $where = array('id' => $user_id,);
            $set = array('push_time' => $datetime,
              'is_free' =>  '0',
              'pjob_id' => $job_id,);
            $this->User_model->updatedata("tbl_users",$where,$set);
  

          $respo = array(
              "controller" => "user",
              "action" => "book_ride",
              "ResponseCode" => true,
              'info' => $info,
              "user_id" => $user_id,
              "job_id" => $job_id,
              'estimated_price' =>$info['estimated_fare'],
              'payable_amount' =>$payable_amount,
              'discountAmt' => $selected_cupon[0]->value,
              );
          $this->set_response($respo, REST_Controller::HTTP_OK); 
      }
    }        
  }

  function respond_get($cronData=null) // 0->Accept or 1,2->decline
  {
    // $this->User_model->autoDeactivate();
    if (empty($cronData)) {
      $data = array('user_id' => $this->get('user_id'),
        'job_id' => $this->get('job_id'),
        'resp' => $this->get('response'));
    } else {
      $data = $cronData;
    }
    // print_r($data);die;

    if ($data['user_id']==""||$data['job_id']==""||$data['resp']=="") {
      $respo = array(
        "controller" => "user",
        "action" => "respond",
        "ResponseCode" => false,
        'Errorcode'=>401,
        "MessageWhatHappen" => "All fields are required");
      $this->set_response($respo, REST_Controller::HTTP_OK); 
      return false;
    }
/*===============================        To get Client INfo      =============================================*/
    $ride_details = $this->User_model->ride_details($data);
    $latitude = $ride_details['user_data']->latitude;
    $longitude = $ride_details['user_data']->longitude;
    // $key = "AIzaSyDnT9r_aKKC5_isYcgkWKHhedGAh-Mv3-U";
    // $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&language='.$_GET['language'].'';

    // $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.trim($pickup_lat).','.trim($pickup_long).'&destination='.trim($dropoff_lat).','.trim($dropoff_long).'&key='.$key.'';

    // $url = "http://phphosting.osvin.net/aberbackusa/admin/api/user/geo_coder?latitude=".trim($latitude)."&longitude=".trim($longitude)."&language=".$_GET['language']."";
    // $json = file_get_contents($url);
    // $geodata = json_decode($json);
    // $address = $geodata->results[0]->formatted_address;
    $address = "";
    // print_r($address);die;
/*===============================================*/

    $is_accept = $this->User_model->get_job($data);

    $issuetype = array('issue_type' => 12,);
    $issue_list = $this->User_model->issue_list($issuetype);

        // print_r($is_accept['status']);die;
    if ($is_accept['status']=='50') {                 // CHeck if client cancelled trip
        $respo = array(
            "controller" => "user",
            "action" => "respond",
            "ResponseCode" => true,
            'Errorcode'=>402,
            'status' => $is_accept['status'],
            "MessageWhatHappen" => "Ride cancelled by client",
            "ride_details" => $ride_details,
            "job_id" => $data['job_id'],
            'issue_list'=>$issue_list);
        $this->set_response($respo, REST_Controller::HTTP_OK); 
    }
    elseif ($is_accept['status']>0&&$data['resp'] != 0) {
      $respo = array(
          "controller" => "user",
          "action" => "respond",
          "ResponseCode" => false,
          'Errorcode'=>403,
          'status' => $is_accept['status'],
          "MessageWhatHappen" => "Action restricted.",
          "job_id" => $data['job_id'],
          'issue_list'=>$issue_list);
      $this->set_response($respo, REST_Controller::HTTP_OK); 
    }
    elseif ($data['resp'] == 0) {  //0 For Accept
      // print_r($data);die;
      $accept = $this->User_model->accept_ride($data);
      $driver_info = $this->User_model->driver_info($data);
      
      $cmData = array(
      'p_name'=>$ride_details['user_data']->first_name,
      'd_name'=>$driver_info[0]->first_name,
      'p_num'=>$ride_details['user_data']->phone,
      'd_num'=>$driver_info[0]->phone,
      'call_type'=>0,
      'TripNumber'=>$data['job_id']
      );
      // print_r($cmData);die;
      $this->callingMode_post($cmData);
      $user_login_data = $this->User_model->getUserLogin($ride_details['user_data']->id); 
      // print_r($user_login_data);die;
      if(count($user_login_data)>0){
        $message = "bookRideAccepted";
        $action = "درایور درخواست پذیرفته"; //Driver accepted the request
        foreach ($user_login_data as $value) {
          if($value->mb_device_id!=0){
            $param = array(
              'token_id'=>$value->mb_token_id,
              'message'=>$message,
              'action'=>$action,
              'job_id'=>$data['job_id']
            );
            $this->iosPush_post($param);
          }
        }
      }
      $respo = array(
          "controller" => "user",
          "action" => "respond",
          "ResponseCode" => true,
          'Errorcode'=>200,
          'status' => $is_accept['status'],
          "MessageWhatHappen" => "accepted",
          "ride_details" => $ride_details,
          'address' => $address,
          "job_id" => $data['job_id'],
          'issue_list'=>$issue_list
          );
      $this->set_response($respo, REST_Controller::HTTP_OK); 
    }elseif ($data['resp'] == 1 || $data['resp'] == 2){                                                     //1 For decline 2 for cron decline
        // $this->User_model->cancel_ride($data);
      // print_r($_GET);die;
        $this->User_model->next($data);     //free previous driver and enter responce in table
        $check = $this->User_model->get_job($data);
        $check['job_id'] = $check['id'];
        // print_r($check);
        // die;
        $job_id = $data['job_id'];

        $result = $this->User_model->find($check);
        // print_r($result);die();
        if ($result == "false" || $result == "RZ0" || $result == "RZ1" || $result == "POE" || $result == "DOE") {               //if no driver found
          
            $user_login_data = $this->User_model->getUserLogin($ride_details['user_data']->id); 
            // print_r($user_login_data);die();
            if(count($user_login_data)>0){
              $message = "no_driver";
              $action = "There are no drivers";
              foreach ($user_login_data as $value) {
                if($value->mb_device_id!=0){
                  $job_id = $data['job_id'];
                  $param = array(
                    'token_id'=>$value->mb_token_id,
                    'message'=>$message,
                    'action'=>$action,
                    'job_id'=>$job_id
                  );
                  $this->iosPush_post($param);
                }
              } 
              // print_r($push);die;
            }
            $respo = array(
            "controller" => "user",
            "action" => "respond",
            "ResponseCode" => true,
            "MessageWhatHappen" => "no driver found",
            'issue_list'=>$issue_list
            );
            $this->set_response($respo, REST_Controller::HTTP_OK); 
        }else{
            $user_id = $result['driver']->id;

            //to prevent driver from getting request
            $datetime = date("Y-m-d H:i:s");
            $where = array('id' => $user_id,);
            $set = array('push_time' => $datetime,
              'is_free' =>  '0',
              'pjob_id' => $job_id,);
            $this->User_model->updatedata("tbl_users",$where,$set);

            $respo = array(
                "controller" => "user",
                "action" => "respond",
                "ResponseCode" => true,
                'Errorcode'=>405,
                "MessageWhatHappen" => "declined",
                "user_id" => $user_id,
                "job_id" => $data['job_id'],
                'issue_list'=>$issue_list
            );
            $this->set_response($respo, REST_Controller::HTTP_OK); 
          } 
      }
  }

  public function ride_status_get()
  {
    $data = array('user_id' => $this->get('user_id'), 
        'job_id' => $this->get('job_id'),
        );
    // print_r($data);
    // die;
    $result = $this->User_model->ride_status($data);
    $user_id = $this->get('user_id');
    $where = array('id' => $user_id);
    $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    $wallet_balance = $wb[0]->wallet_balance;
    $not =$this->User_model->notification($user_id,0);

    // print_r($result);die;
    if ($result ==0) {
        $respo = array(
            "controller" => "user",
            "action" => "ride_status",
            "ResponseCode" => false,
            "MessageWhatHappen" => "No Driver Found",
            'wallet_balance'=>$wallet_balance,
            'unread_count' => $not->unread
        );
    }
    elseif ($result ==1) {
        $respo = array(
            "controller" => "user",
            "action" => "ride_status",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Searching for Drivers",
            'wallet_balance'=>$wallet_balance,
            'unread_count' => $not->unread
        );
    }
    else{
        $respo = array(
            "controller" => "user",
            "action" => "ride_status",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Driver details are:",
            "UserData" => $result,
            'wallet_balance'=>$wallet_balance,
            'unread_count' => $not->unread
        );
    }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function cancel_ride_get()
  {

    // $push = $this->User_model->ios(f143a3df8e326df30b0bf3904703805fdee3a99fab7e25767c6812d4fbaa6ef5, "kjhsdjfkhsd");
    // print_r($push);die;
    $data = array('client_id' => $this->get('client_id'), 
        'driver_id' => $this->get('driver_id'),
        'user_type' => $this->get('user_type'),
        'job_id' => $this->get('job_id'),
        'reason_id' => $this->get('reason_id'),
        'flag' => $this->get('flag'),
        'other_reason' => $this->get('other_reason'),
        );
      if (empty($data['job_id'])) {
        $selectJob = $this->db->query("SELECT id FROM tbl_jobs WHERE user_id=".$data['client_id']." ORDER BY id DESC LIMIT 1 ")->row();
        $data['job_id'] = $selectJob->id;
      }
      if ($data["flag"]=="2") {         //after accept
          if ($data['reason_id']==0) {
            if ($data['user_type'] == 0) {
                // print_r($data['user_type']);die;
                $message = "Client cancelled trip";
                $user_login_data = $this->User_model->getUserLogin($this->get('driver_id'));
            }
            elseif ($data['user_type'] == 2) {
                $message = "Driver cancelled trip";
                $user_login_data = $this->User_model->getUserLogin($this->get('client_id'));
            }
            // print_r($user_login_data);die;
            $action = "cancel_ride";
            $job_id = $data['job_id'];
            $datetime = date("Y-m-d H:i:s");
            foreach ($user_login_data as $value) {
                if($value->mb_device_id==0){
                    // $push = $this->User_model->android($value->mb_token_id, $message, $ride_details, $action,$job_id);
                }else{
                  $param = array(
                    'token_id'=>$value->mb_token_id,
                    'message'=>$message,
                    'action'=>$action,
                    'fare'=>$respo['fare'],
                    'datetime'=>$datetime,
                    'ride_details'=>$ride_details
                  );
                  $this->iosPush_post($param);
                }
            }
            // print_r($push);
            // die;
          }
      }
      if ($data['reason_id']>0) {
        $result = $this->User_model->updatedata('tbl_jobs', array('id'=>$data['job_id']), array('reason_id'=>$data['reason_id'],'other_reason'=>$data['other_reason']));
        $respo = array(
            "controller" => "user",
            "action" => "cancel_ride",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Reason submitted Successfully",
            "UserData" => true
        );
      } else {
        // $this->User_model->updatedata('tbl_promocode', array('job_id'=>$data['job_id']), array('status'=>0));
        $result = $this->User_model->cancel_ride($data);
        $respo = array(
            "controller" => "user",
            "action" => "cancel_ride",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Ride cancelled Successfully",
            "UserData" => true
        );
      }
    // }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }


/*  function start_ride_get()
  {
    $data = array('user_id' => $this->get('user_id'),
        'job_id' => $this->get('job_id'),
        'is_active' => $this->get('is_active'));
    $this->User_model->start_ride($data);
    $respo = array(
        "controller" => "user",
        "action" => "start_ride",
        "ResponseCode" => true,
        "message" => "job is active now",
        "job_id" => $data['job_id']);
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }

  function finish_ride_get()
  {
    $data = array('user_id' => $this->get('user_id'),
        'job_id' => $this->get('job_id'));
    $result = $this->User_model->finish_ride($data);
    $respo = array(
        "controller" => "user",
        "action" => "finish_ride",
        "ResponseCode" => true,
        "message" => "job is completed now",
        "job_id" => $data['job_id'],
        "driver_id" => $data['user_id'],
        "info" => $result);
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }*/

  public function start_finish_arrive_ride_get(){

    $flag = $this->get('flag');//0-start 1-arrive,2-finish
    $user_login_data = $this->User_model->getUserLogin($this->get('client_id'));
    // print_r($user_login_data);die;
    $data = array(
        'user_id' => $this->get('user_id'),
        'job_id' => $this->get('job_id'),
        'client_id' => $this->get('client_id'),
        );      
    $admin = $this->User_model->findAdmin();
    $data['admin_id'] = $admin->id;
    // print_r($admin->id);die;
    if($flag==0){
        
        $this->User_model->start_ride($data);

        $respo = array(
                "controller" => "user",
                "action" => "start_ride",
                "ResponseCode" => true,
                "message" => "job is active now",
                "job_id" => $data['job_id']
            );

        $message = "ride_started";
        $action = "Ride has been started";
        $ride_details = $this->User_model->check_status($data['client_id']);
        //send the notifications to the client
// print_r($ride_details);die;
        foreach ($user_login_data as $value) {
            if($value->mb_device_id==1){
                $param = array(
                  'token_id'=>$value->mb_token_id,
                  'message'=>$message,
                  'action'=>$action,
                  'job_id'=>$data['job_id'],
                );
                $this->iosPush_post($param);
            }
        }
        
    }elseif($flag==1){
   // print_r($_GET);die;
    // $this->User_model->set_arrived($this->get('job_id');
        if($this->User_model->set_arrived($this->get('job_id'))){

            $respo = array(
                "controller" => "user",
                "action" => "arrived",
                "ResponseCode" => true,
                "message" => "Driver status set to arrived",
            );

            $message = "driver_arrived";
            $action = "Driver has been arrived";
            $ride_details = $this->User_model->check_status($data['client_id']);
            // print_r($ride_details);die;
            foreach ($user_login_data as $value) {
                if($value->mb_device_id==1){
                  $sound = "horn.mp3";
                  $datetime = date("Y-m-d H:i:s");
                  $param = array(
                    'token_id'=>$value->mb_token_id,
                    'message'=>$message,
                    'action'=>$action,
                    'datetime'=>$datetime,
                    'job_id'=>$data['job_id'],
                    'sound'=>$sound
                  );
                  $this->iosPush_post($param);
                }
            }
        }
        else{
            $respo = array(
                "controller" => "user",
                "action" => "start_finish_arrive_ride",
                "ResponseCode" => true,
                "message" => "Something went wrong! Please try again"
            );
        }
        
    }elseif($flag==2){
        $data['fare_cal'] = $this->fare_cal($data);
        // print_r($data);die;
        $result = $this->User_model->finish_ride($data);
        // print_r($result);die;
        $date1 = $result->job_start_datetime;
        $date2 = $result->job_completed_datetime;

         $start_date = new DateTime($date1);
          $end_date = new DateTime($date2);
          $interval = $start_date->diff($end_date);
          $time_taken = $interval->y . " years, " . $interval->m." months, ".$interval->d." days, ". $interval->h . ":" . $interval->i.":".$interval->s;
          $minutes = ($interval->y * 365 * 24 * 60)+($interval->m * 30 * 24 * 60)+($interval->d * 24 * 60)+($interval->h * 60)+$interval->i;
          //-----------driver and admin share-----------
        $driver_share = $this->User_model->driverCommission($data['user_id']);
        // print_r($driver_share);die;
        $admin_share = 100-$driver_share;
        $wb = $this->User_model->selectdata("tbl_users","wallet_balance",array('id' => $data['user_id']));
          // print_r($wb);die;
        $wallet_balance = $wb[0]->wallet_balance;

        $respo = array(
            "controller" => "user",
            "action" => "finish_ride",
            "ResponseCode" => true,
            "message" => "job is completed now",
            "job_id" => $data['job_id'],
            "driver_id" => $data['user_id'],
            "distance" => $data['fare_cal']['distance'],
            "time_taken" => $time_taken,
            "minutes" => $minutes,
            "fare" => $data['fare_cal']['fare'],
            'waiting_time_cost'=>'',
            "payable_amount"=>"",
            "wallet_balance"=>$wallet_balance,
            'driver_share' => $driver_share,
            'admin_share'=>$admin_share,
            "payment_method"=>$result->payment_method,
            "info" => $result);
        /*------------------  Payment ---------------------------*/

        $where = array('job_id' => $data['job_id'],
          'status' => '1');
        $coupon_val = $this->User_model->selectdata("tbl_promocode","value,promocode,dsc_type",$where);
        if (count($coupon_val)>0) {
          $dsc_type = $coupon_val[0]->dsc_type;
          if ($dsc_type==1) {
            $coupon_value = $coupon_val[0]->value;
          } else {
            // print_r(20/100);
            $coupon_value = ($coupon_val[0]->value/100)*$data['fare_cal']['fare'];
          }
          // print_r($coupon_value);
        } else {
          $coupon_value = 0;
        }
        
        // $coupon_value = 0;

        // print_r($coupon_val);die;
        // $result->payment_method=1;
        $where = array('id' => $data['client_id']);
        $user_details = $this->User_model->selectdata("tbl_users","payment_method,wallet_balance",$where);
        $client_wallet_bal = $user_details[0]->wallet_balance;
        $amount_to_pay = $data['fare_cal']['fare']-$coupon_value;
        if ($result->payment_method==2&&$client_wallet_bal<$amount_to_pay) {
          $this->User_model->updatedata('tbl_jobs',array('id'=>$result->id),array('payment_method'=>0));
          $result->payment_method=0;
        }
        // print_r($result);die;
        if ($result->payment_method==2) { /*Payment method wallet*/

          // print_r($client_wallet_bal);die;
          // $respo['payment_method'] = $result->payment_method;

           /* having enough balance */
            $rideAmount = $amount_to_pay;
            // print_r($rideAmount);die;
            $this->User_model->sub_wallet('tbl_users',$data['client_id'],$rideAmount);

            $adminAmount = ($data['fare_cal']['fare']*$admin_share)/100;

            $this->User_model->add_wallet('tbl_managers',$data['admin_id'],$adminAmount);

            $admin_pay_type = "2"; 
            $client_pay_type = "1";
            $driver_pay_type = "2";

            $aRefID = 0;
            $cRefID = 0;
            $dRefID = 0;
            
            $driverAmount = ($data['fare_cal']['fare']*$driver_share)/100;
            $this->User_model->add_wallet('tbl_users',$data['user_id'],$driverAmount);
            $respo['waiting_time_cost'] = $data['fare_cal']['waiting_time_cost'];
            $respo['payable_amount'] = $amount_to_pay+$data['fare_cal']['waiting_time_cost'];
        }elseif ($result->payment_method==0){
          /*
          payment method cash

          */

          // $respo['payment_method'] = $result->payment_method;
          // print_r($data);die;
          $totalAmount = $data['fare_cal']['fare']+$data['fare_cal']['waiting_time_cost'];
          if ($coupon_value>0) {  
            $driverAmount = ($totalAmount*$driver_share)/100;
            $amount_to_pay = $totalAmount-$coupon_value;
            $respo['waiting_time_cost'] = $data['fare_cal']['waiting_time_cost'];
            $respo['payable_amount'] = $amount_to_pay;
            if ($totalAmount>$driverAmount) {

              $adminAmount = $totalAmount-$driverAmount;

              $this->User_model->sub_wallet('tbl_users',$data['user_id'],$adminAmount);
              $this->User_model->add_wallet('tbl_managers',$data['admin_id'],$adminAmount);

              $admin_pay_type = "1"; 
              $client_pay_type = "2";
              $driver_pay_type = "1";

              $aRefID = $data['user_id'];
              $cRefID = $data['user_id'];
              $dRefID = $data['client_id'];
            } else {
              $adminAmount = $driverAmount-$totalAmount;
              $this->User_model->sub_wallet('tbl_managers',$data['admin_id'],$adminAmount);
              $this->User_model->add_wallet('tbl_users',$data['user_id'],$adminAmount);

              $admin_pay_type = "2"; 
              $client_pay_type = "2";
              $driver_pay_type = "1";

              $aRefID = $data['user_id'];
              $cRefID = $data['user_id'];
              $dRefID = $data['client_id'];
            }
          } else {
            $admin_pay_type = "1"; 
            $client_pay_type = "2";
            $driver_pay_type = "1";

            $adminAmount = ($totalAmount*$admin_share)/100;
            $this->User_model->sub_wallet('tbl_users',$data['user_id'],$adminAmount);
            $this->User_model->add_wallet('tbl_managers',$data['admin_id'],$adminAmount);

            $driverAmount = ($totalAmount*$driver_share)/100;
            $respo['waiting_time_cost'] = $data['fare_cal']['waiting_time_cost'];
            $respo['payable_amount'] = $totalAmount;

            $aRefID = $data['user_id'];
            $cRefID = $data['user_id'];
            $dRefID = $data['client_id'];
          }
          $wb = $this->User_model->selectdata("tbl_users","wallet_balance",array('id' => $data['user_id']));
          // print_r($wb);die;
          $wallet_balance = $wb[0]->wallet_balance;
          $respo['wb_after_txn'] = $wallet_balance;
          $respo['adminAmount'] = $adminAmount;
          $respo['driverAmount'] = $driverAmount;
        }
        else{

          $response = array(
            "controller" => "user",
            "action" => "finish_ride",
            "ResponseCode" => false,
            "message" => "Please select Payment Method",
          );
          $message = "Please select Payment Method";
          $action = "finish_ride";
          $driver_info = $this->User_model->driver_info($data);
          foreach ($user_login_data as $value) {
            if($value->mb_device_id==1){
                $param = array(
                  'token_id'=>$value->mb_token_id,
                  'message'=>$message,
                  'action'=>$action,
                  'fare'=>$respo['fare'],
                  'job_id'=>$data['job_id'],
                );
                $this->iosPush_post($param);
            }
          }
          header('content-type:application/json');
          echo(json_encode($response));
          // $this->set_response($response, REST_Controller::HTTP_OK);
          exit;
        }
        /* To save payment transaction */

        $method = $result->payment_method;
        $datetime = date("Y-m-d H:i:s");

        $adminWallet = $this->User_model->selectdata("tbl_managers","wallet_balance",array('id'=>$data['admin_id']));
        // print_r($adminWallet[0]->wallet_balance);die;
        $adminWalletBal = $adminWallet[0]->wallet_balance;
        $driverWallet = $this->User_model->selectdata("tbl_users","wallet_balance",array('id'=>$data['user_id']));
        $driverWalletBal = $driverWallet[0]->wallet_balance;
        $clientWallet = $this->User_model->selectdata("tbl_users","wallet_balance",array('id'=>$data['client_id']));
        $clientWalletBal = $clientWallet[0]->wallet_balance;


        $client_transaction_data = array('job_id'=>$data['job_id'],'payment_type'=>$client_pay_type,'payment_method' => $method,'payment_status' => '100', 'payment_RefID' => $cRefID,'user_id'=>$data['client_id'],'amount'=>$respo['payable_amount'], 'wallet_balance'=>$clientWalletBal, 'date_created'=>$datetime);

        $driver_transaction_data = array('job_id'=>$data['job_id'],'payment_type'=>$driver_pay_type,'payment_method' => $method,'payment_status' => '100', 'payment_RefID' => $dRefID,'user_id'=>$data['user_id'],'amount'=>$driverAmount, 'wallet_balance'=>$driverAmount, 'date_created'=>$datetime);

        if ($adminAmount>0) {
          $admin_transaction_data = array('job_id'=>$data['job_id'],'payment_type'=>$admin_pay_type,'payment_method' => $method,'payment_status' => '100', 'payment_RefID' => $aRefID, 'user_id'=>$data['admin_id'],'amount'=>$adminAmount, 'wallet_balance'=>$adminWalletBal, 'date_created'=>$datetime);
        }

        $this->User_model->insertdata("tbl_payment",$client_transaction_data);
        $this->User_model->insertdata("tbl_payment",$driver_transaction_data);
        $this->User_model->insertdata("tbl_payment",$admin_transaction_data);
        /*------------------------------------------------------*/
        
        // print_r($respo);die;
        $message = "finish_ride";
        $action = "Ride has been finished";
        $driver_info = $this->User_model->driver_info($data);
        //send the notifications to the client
        // print_r($driver_info);die;
        $this->User_model->updatedata("tbl_jobs",array('id'=>$data['job_id']),array('payable_amount'=>$respo['payable_amount'],'status'=>4));
        /*------------Push notification------------------*/
        foreach ($user_login_data as $value) {
          if($value->mb_device_id==1){
              $param = array(
                'token_id'=>$value->mb_token_id,
                'message'=>$message,
                'action'=>$action,
                'fare'=>$respo['fare'],
                'job_id'=>$data['job_id'],
              );
              $this->iosPush_post($param);
          }
        }
    }
    // print_r($respo);die;
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }

  public function userDatabyDate_get()
{
		$data = array(

      'phone' =>$this->get('phone'),
			'start_date' =>$this->get('start_date'),
			'end_date' =>$this->get('end_date'),
      'limit' =>$this->get('limit')
			);	
		if($data['start_date']=='' || $data['end_date'] == ''){
			$respo = array(
              "controller" => "user",
              "action" => "data",
              "ResponseCode" => false,
              "MessageWhatHappen" =>"Please Enter The Required Credentials"
             );
		}
    elseif($data['phone'] !=''){
      $dataQuery1 = $this->User_model->userByPhone($data);
      if($dataQuery1){
         $respo = array(
            "controller" => "user",
            "action" => "Trip details",
            "ResponseCode" => true,
            "GetData" =>$dataQuery1,
            ); 
      }
      else{
        $respo = array(
              "controller" => "user",
              "action" => "data",
              "ResponseCode" => false,
              "MessageWhatHappen" =>"No trip found"
             );
                   }
    }
		else{
		$dataQuery = $this->User_model->userbyDate($data);
		// print_r($dataQuery);die();
		if($dataQuery){
			 $respo = array(
						"controller" => "user",
						"action" => "Trip details",
						"ResponseCode" => true,
						"GetData" =>$dataQuery,
						); 
		}
		else{
			$respo = array(
						"controller" => "user",
						"action" => "Trip details",
						"ResponseCode" => false,
						"MessageWhatHappen" =>"Error No Data Available"
						); 
								}
							}
							 $this->set_response($respo, REST_Controller::HTTP_OK); 
						}
  public function fare_cal($data)
  {
    $vehicle_info = $this->User_model->fare_cal($data);
    $job_id = array('job_id' => $data['job_id'],);
    $way_points = $this->User_model->get_points($job_id);
    // print_r($vehicle_info);die;
    // print_r($way_points);die;
    /*foreach ($way_points as $key => $value) {
      print_r($value);
    }die;*/

    $where = array('id' => $data['job_id'],);
    $waiting_time_array = $this->User_model->selectdata("tbl_jobs","estimate,destination_changed,waiting_time",$where);
    $waiting_time = $waiting_time_array[0]->waiting_time;
    // $to_time = strtotime($waiting_time);
    // $waiting_time = '21:30:30';
    $parsed = date_parse($waiting_time);
    // $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
    $minutes = $parsed['hour'] * 60 + $parsed['minute'] + $parsed['second']/60;
    $minutes = ceil($minutes);//charge on 1 second also
    // print_r($minutes);die;
    $est = explode(',', $waiting_time_array[0]->estimate);
    
    $distance = $est[0];

    $data['distance'] = (is_nan($distance)) ? '0' : "$distance" ;
    $fareSettings = $this->User_model->selectdata('tbl_appSettings','*');
    // print_r($fareSettings);die;
    $freeMinutes = $fareSettings[0]->value;
    $freeDistance = $fareSettings[1]->value;
    // echo "$freeDistance";die;
    $waiting_time_cost = 0;
    if ($waiting_time_array[0]->destination_changed!=1 && $minutes<=$freeMinutes) { 
      $data['fare'] = $est[2];
      // print_r($est);die;
    }else{   // if destination changed "waiting time"
      // $est = explode(',', $waiting_time_array[0]->estimate);
      $estimated_fare = $est[2];


      if ($minutes<=$freeMinutes) {
        $fare = $estimated_fare;
      }else{
        $newTime = $minutes-$freeMinutes;
        $newTime = ceil($newTime/10);//per ten minutes
        $waiting_time_cost = $vehicle_info->waiting_charge*$newTime;
        $fare = $estimated_fare;
      }

      // print_r($fare);die;
      $fare = (is_nan($fare)) ? '0' : "$fare" ;
      // print_r($fare); 
      // print_r($data);
      // die;
       $data['fare'] = round($fare/500) * 500;
    }
    $data['waiting_time_cost'] = $waiting_time_cost;
    return $data;
  }

  function rating_get()
  {
    $data = array('user_id' => $this->get('user_id'),
      'user_type' => $this->get('user_type'),
      'job_id' => $this->get('job_id'),
      'rating' => $this->get('rating'),
      'feedback' => $this->get('feedback'),
      'driver_issue' => $this->get('issue_id'),
      'issueTitle' => $this->get('issueTitle')
    );
    $db_result = $this->User_model->rate_ride($data);
    if ($db_result==1) {
      $respo = array(
        "controller" => "user",
        "action" => "rating",
        "ResponseCode" => true,
        "message" => "Rated",
        "job_id" => $data['job_id']);
    }else{
      $respo = array(
        "controller" => "user",
        "action" => "rating",
        "ResponseCode" => false,
        "message" => "Error",);
    }
    
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }

  function promo_code_get()
  {                   // for refrral code
    $data = array('user_id' => $this->get('user_id'),
    'promo_code' => $this->get('promo_code') );

    $result = $this->User_model->apply_promo($data);
    if ($result == "yo") {
        $respo = array(
        "controller" => "user",
        "action" => "promo_code",
        "ResponseCode" => true,
        "message" => "Applied Successfully",);
    }
    elseif ($result == "exist") {
        $respo = array(
        "controller" => "user",
        "action" => "promo_code",
        "ResponseCode" => false,
        "message" => "already applied",);
    }
    else{
        $respo = array(
        "controller" => "user",
        "action" => "promo_code",
        "ResponseCode" => false,
        "message" => "Invalid Promo code",);
    }
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }

  public function apply_cupon_get($data=null)
  {             // for cupon and other promo code and refrral (merged)
    if (empty($data)) {
      $data = array(
        'user_id' => $this->get('user_id'),
        //'promo_type'=>$this->get('promo_type'),
        'promo_code' => $this->get('promo_code'), 
        'job_id' => $this->get('job_id'),
        // 'start_date'=>$this->get('start_date'), 
        // 'end_date'=>$this->get('end_date'), 
        // 'promo_use'=>$this->get('promo_use'), 
        // 'amt_type'=>$this->get('amt_type'), 
        // 'disc_amt'=>$this->get('disc_amt'), 
        // 'disc_percent'=>$this->get('disc_percent'), 
        // 'code_usage' =>$this->get('code_usage'), 
      );
    }
    if ($data['job_id']=="") {
      $data['job_id']=0;
      $ongoing = $this->User_model->selectdata('tbl_jobs', 'id', array('user_id'=>$_GET['user_id'],'is_active'=>1));
      if (!empty($ongoing)) {
        $data['job_id']=$ongoing[0]->id;
      }
    }

    $result = $this->User_model->apply_cupon($data);

    if ($result == "exist") {
        $respo = array(
        "controller" => "user",
        "action" => "apply_cupon",
        "ResponseCode" => false,
        'Errorcode'=>401,
        "message" => "already applied",);
    }elseif ($result=="not") {
      $respo = array(
        "controller" => "user",
        "action" => "apply_cupon",
        "ResponseCode" => false,
        'Errorcode'=>402,
        "message" => "This offer is for new users only",
      );
    }
    elseif (!empty($result)) {
        $respo = array(
        "controller" => "user",
        "action" => "apply_cupon",
        "ResponseCode" => true,
        'coupon_type' => $result,
        "message" => "Applied Successfully",);
    }
    else{
        $respo = array(
        "controller" => "user",
        "action" => "apply_cupon",
        "ResponseCode" => false,
        'Errorcode'=>403,
        "message" => "Invalid Promo code",);
    }
    $this->set_response($respo, REST_Controller::HTTP_OK); 
  }

  public function cupon_list_get()
  {
    $user_id = $this->get('user_id');
    // $cupon_list = $this->User_model->listData($where);
    $promo_list = $this->User_model->promo_list($user_id);
    if (empty($promo_list)) {
      $respo = array(
          "controller" => "user",
          "action" => "cupon_list",
          "ResponseCode" => false,
          "MessageWhatHappen" =>'No Coupon Found',
        );
    }else{
      $respo = array(
        "controller" => "user",
        "action" => "cupon_list",
        "ResponseCode" => true,
        'Promo' => $promo_list
      );
    }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function select_cupon_get()
  {
    if ($this->get('job_id')!="") {
      $where = array('promocode_beneficiary_id' => $this->get('user_id'),'status' =>2);
      $isSelected = $this->User_model->selectdata('tbl_promocode','id',$where);
      // print_r($isSelected);die();
      if (!empty($isSelected)) {
        $respo = array(
          "controller" => "user",
          "action" => "select_cupon",
          "ResponseCode" => false,
          "MessageWhatHappen"=>"Already Selected");  
        $this->set_response($respo, REST_Controller::HTTP_OK);
        return false;
      }
    }

    // print_r($this->get());die;
    $data = array('promocode_beneficiary_id' => $this->get('user_id'), 'promocode' => $this->get('promo_code'));
    // print_r($data);die;
    $select_cupon = $this->User_model->select_cupon($data);

    if (count($select_cupon)>0) {
      $respo = array(
        "controller" => "user",
        "action" => "select_cupon",
        "ResponseCode" => true,
        "GetData" => $select_cupon,);  
    } else {
      $respo = array(
        "controller" => "user",
        "action" => "select_cupon",
        "ResponseCode" => false,
        "MessageWhatHappen"=>"Invalid promo code");  
    }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  function customer_support_post()
  {
    $data = array('user_id' => $this->post('user_id'),
    'name' => $this->post('name'),
    'email' => $this->post('email'),
    'query' => $this->post('query'));
    $result = $this->User_model->customer_support($data);
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
                  <p>User Id is:</p>
                  <p> ".$data['user_id']."</p>
                  <p>User Name is:</p>
                  <p> ".$data['name']."</p>
                  <p>User email Id is:</p>
                        <p> ".$data['email']."</p>
                        <p>User Query is:</p>
                      <p> ".$data['query']."</p>
                  <tr>                  
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
    /*$this->email->initialize(array(
                'protocol' => 'smtp',
                'smtp_host' => 'smtp.sendgrid.net',
                'smtp_user' => 'wefinance',
                'smtp_pass' => 'admin@1@1',
                'smtp_port' => '587',
                ));
    $this->email->from('wefinanceapp@gmail.com');
    $this->email->to('osvinandroid@gmail.com');
    $this->email->subject('User Query');
    $this->email->message($body);*/
    // $this->email->send();
    $from = 'MASIR';
    $email = 'masir@masirapp.com';
    // $headers = 'MIME-Version: 1.0' . "\r\n";
    // $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

    // // More headers
    // $headers .= 'From: '.$from. "\r\n";
    // $headers .= 'Reply-To: '.$from."\r\n";  
    // $headers .= 'Return-Path: '.$from."\r\n";
    // //$headers .= 'Bcc: '.$from."\r\n";
    // //$headers .= 'Cc: myboss@example.com' . "\r\n";
    // mail($email,'Password Retrival',$body,$headers);
    $this->email->set_newline("\r\n");
    $this->email->from('masir@masirapp.com',$from);
    $this->email->to($email);
    $this->email->subject('Customer Support');
    $this->email->message($body);
    $this->email->send();

    if ($result) {
        $respo = array(
        "controller" => "user",
        "action" => "customer_support",
        "ResponseCode" => true,
        "message" => "Query sent Successfully",);
        $this->set_response($respo, REST_Controller::HTTP_OK);
    }else{
        $respo = array(
        "controller" => "user",
        "action" => "customer_support",
        "ResponseCode" => false,
        "message" => "Some technical error",);
        $this->set_response($respo, REST_Controller::HTTP_OK); 
    }
  }
  
  function GetDrivingDistance_post()
  {
    $pickup_lat = $this->post('pickup_lat');
    $pickup_long = $this->post('pickup_long');
    $dropoff_lat = $this->post('dropoff_lat');
    $dropoff_long = $this->post('dropoff_long');
    $pickup_id = $this->post('pickup_id');
    $dropoff_id = $this->post('dropoff_id');
    $language = $this->post('language');
    if (!empty($pickup_id))
    {
      $origin = "place_id:$pickup_id";
    }
    else
    {
      $origin = "$pickup_lat,$pickup_long";
    }

    if (!empty($dropoff_id))
    {
      $destination = "place_id:$dropoff_id";
    }
    else
    {
      $destination = "$dropoff_lat,$dropoff_long";
      $userInfo = $this->User_model->selectdata('tbl_users', 'wallet_balance,vehicle_id', array(
        'id' => $this->post('user_id')
      ));

      $data = googleDirections($_POST);
      // print_r($data);die;
      $info['routes'] = $data->routes[0];
      if ($data->status != "OK")
      {
        // if ($data) {
        $result = array(
          "controller" => "user",
          "action" => "GetDrivingDistance",
          "ResponseCode" => false,
          "MessageWhatHappen" => $data,
        );
      }
      else
      {
        $vehicle_type = $userInfo[0]->vehicle_id;
        $vehicle_info = $this->User_model->selectdata('tbl_vehicle', '*', array(
          'id' => $vehicle_type
        ));

        // print_r($vehicle_info);die();

        $minFare = $vehicle_info[0]->base_rate;
        $per_km = $vehicle_info[0]->per_km;
        $traffic_cahrges_permin = $vehicle_info[0]->traffic_charges;

        // print_r($minFare);die;

        $distance = $data->routes[0]->legs[0]->distance->value;
        $info['base_fare'] = $minFare;
        $info['per_km'] = $per_km;
        $info['traffic_cahrges_permin'] = $traffic_cahrges_permin;
        $info['distance'] = $distance/1000;
        // print_r($distance);
        if ($distance < 1000)
        {

          // print_r($distance);die;

          $info['estimated_fare'] = $minFare;
        }
        elseif ($distance >= 1000 && $distance < 8000)
        {
          $distanceData = $distance / 1000;
          // print_r($distanceData);
          $distanceRate = ($distanceData - 1) * $per_km;
          $info['estimated_fare'] = round(($distanceRate + $minFare) / 500) * 500;

          // $info['estimated_fare'] = $minFare;
          // print_r($info);die;

        }
        else
        {
          $duration = $data->routes[0]->legs[0]->duration->value;
          $duration_in_traffic = $data->routes[0]->legs[0]->duration_in_traffic->value;
          $divisor_for_minutes = $duration % (60 * 60);
          $duration = floor($divisor_for_minutes / 60);
          $divisor_for_minutes = $duration_in_traffic % (60 * 60);
          $duration_in_traffic = floor($divisor_for_minutes / 60);
          if ($duration_in_traffic > $duration)
          {
            $extraTime = $duration_in_traffic - $duration;
          }
          else
          {
            $extraTime = 0;
          }

          $newDistance = round(($distance - 1000) / 1000);
          // echo "$per_km";die;

          $traffic_cahrges = $extraTime * $traffic_cahrges_permin;
          $fare = $minFare + ($newDistance * $per_km) + $traffic_cahrges;

          $info['duration'] = $duration;
          $info['timeInTraffic'] = $extraTime;
          // //////////////////////////////////////////////////////////////////
          $fare1 = $minFare + ($newDistance * $per_km);
          $info['decFare'] = $fare1;
          $info['normal_fare'] = round($fare1 / 500) * 500;

          /*$newData = $distance / 1000;
          $newDis = $newData - 8;
          $calc = $per_km * 8;
          $newCal = ($newDis * 600) + $calc;*/

          $info['estimated_fare'] = round(($fare1 + $traffic_cahrgess) / 500) * 500;

          // print_r($info['estimated_fare']);die;

        }

        $coupon = $this->User_model->selectdata('tbl_promocode', '*', array(
          'promocode_beneficiary_id' => $this->input->post('user_id') ,
          'status' => '2'
        ));

        $result = array(
          "controller" => "user",
          "action" => "GetDrivingDistance",
          "ResponseCode" => true,
          "GetData" => $info,
          // 'GetData2' => $data2,
          'coupon' => $coupon,
          'wallet_balance' => $userInfo[0]->wallet_balance,
          'notification' => $this->User_model->notification($this->post('user_id'),0)
        );

        // ----------Log-------------

        $insertData = array(
          'start_address' => $data->routes[0]->legs[0]->start_address,
          'end_address' => $data->routes[0]->legs[0]->end_address,
          'estimated_price' => $info['estimated_fare'],
          'user_id' => $this->input->post('user_id') ,
          'addedOn' => date("Y-m-d H:i:s") ,
          'reason' => '0',
          'distance' => $data->routes[0]->legs[0]->distance->value
        );
        $var = $this->post('user_id');
        $pricequote = $this->db->query("SELECT * FROM tbl_priceQuoteLog where user_id='" . $var . "'")->result();

        $this->User_model->insertdata('tbl_priceQuoteLog', $insertData);
      }

      // }
      // -------------End----------------

      $this->set_response($result, REST_Controller::HTTP_OK);

      // print_r($data);

    }
  }

   public function geo_coder_get()
  {
    $latitude = $this->get('latitude');
    $longitude = $this->get('longitude');
    $language = $this->get('language');
    
    $key = "AIzaSyDnT9r_aKKC5_isYcgkWKHhedGAh-Mv3-U";
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($latitude).','.trim($longitude).'&language='.$language.'';
    // print_r($url);die;
    // $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.trim($pickup_lat).','.trim($pickup_long).'&destination='.trim($dropoff_lat).','.trim($dropoff_long).'&key='.$key.'';

    $json = file_get_contents($url);
    $data = json_decode($json);
    $this->set_response($data, REST_Controller::HTTP_OK);
  }

  public function updateprofile_post() {

    $config['upload_path'] = 'public/profilePic';
    $config['allowed_types'] = 'gif|jpg|png|jpeg';
    $config['max_size'] = 5000;
    $config['max_width'] = 10240;
    $config['max_height'] = 7680;

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('profile_pic')) {
        $error = array('error' => $this->upload->display_errors());
        $imagename = "";
    } else {
        $data = $this->upload->data();
        $imagename = $data['file_name'];
    }

    $message = array(
        'user_id' => $this->post('user_id'),
        'first_name' => $this->post('first_name'),
        'last_name' => $this->post('last_name'),
        'gender' => $this->post('gender'),
        'imagename' => $imagename,
        'dob' => $this->post('dob'),
        'email' => $this->post('email'),
    );
    // print_r($message);
    // die();

    $id = $this->User_model->updateprofile($message);

    $info = $this->User_model->viewprofile($message['user_id']);

    /*$email = $message['email'];
    if ($email!="") {
      $id1 = $this->post('user_id');
      $static_key = "afvsdsdjkldfoiuy4uiskahkhsajbjksasdasdgf43gdsddsf";
      $ids = $id1 . "_" . $static_key;
      $b_id = base64_encode($ids);
      $url = base_url('verification/update_email') . "?id=" . $b_id. "&email=" . $email;

      $data = array('message' => "You have request to signup your account for MASIR.To complete the process, click the link below.",
      'url' => $url,
      'url_name' => "Click here to update your email",
      'from' => "MASIR",
      'email' => $email,
      'subject' => "MASIR: email verification", );
      $this->sendemail($data);

      $result = array(
            "controller" => "user",
            "action" => "updateprofile",
            "ResponseCode" => true,
            "MessageWhatHappen" => "verify you email to update email address",
            "GetData" => $info
        );
    }
    else*/
    if($id == 1) {
      $result = array(
          "controller" => "user",
          "action" => "updateprofile",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Updated Successfully",
          "GetData" => $info
      );
    }
    else {
        $result = array(
            "controller" => "user",
            "action" => "updateprofile",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Try Again"
        );
    }

    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function viewprofile_get() {
    $user_id = $this->get('user_id');
    $id = $this->User_model->viewprofile($user_id);
    //echo "<pre>";print_r($id);die;
    if ($id == "") {
        $result = array(
            "controller" => "user",
            "action" => "viewprofile",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Try Again"
        );
    } else {
        $result = array(
            "controller" => "user",
            "action" => "viewprofile",
            "ResponseCode" => true,
            "UserData" => $id['userdata']
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }
  
  public function check_status_get() {

    $data = $this->get('user_id');
    $getType = $this->User_model->selectdata('tbl_users','user_type', array('id'=>$data));
    // print_r($getType[0]->user_type);die;
    $user_type = $getType[0]->user_type;
        
    $result = $this->User_model->check_status($data);

    $user_id = $this->get('user_id');
    $where = array('id' => $user_id);
    $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    $wallet_balance = $wb[0]->wallet_balance;
    $not =$this->User_model->notification($user_id,$user_type);
    $coupon = $this->User_model->selectdata('tbl_promocode', '*', array(
          'promocode_beneficiary_id' => $this->input->get('user_id') ,
          'status' => '2'
        ));

    $issuetype = array('issue_type' => 12,);
    $issue_list = $this->User_model->issue_list($issuetype);

    if($result['is_verified']==0){
        $respo = array(
            "controller" => "user",
            "action" => "check_status",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Phone Number is not verified yet",
            "UserData" => $result,
            'wallet_balance'=>$wallet_balance,
            'coupon' => $coupon,
            'unread_count' => $not->unread,
            'issue_list'=>$issue_list
        );
    }
    elseif($result['is_verified']==1){

      $respo = array(
          "controller" => "user",
          "action" => "check_status",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Phone Number is verified!!!",
          "UserData" => $result,
          'wallet_balance'=>$wallet_balance,
          'coupon' => $coupon,
          'unread_count' => $not->unread,
          'issue_list'=>$issue_list
      );
    }

    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function updatePaymentMethod_post(){
    /*
    *Passenger Api for update payment method
    */
    $data =$this->post('trip_id');
    $user_id = $this->post('user_id');
    $payment_method = $this->post('payment_method');
    $fare_amount = $this->post('fare_amount');

    //Check wallet balance if method changed to wallet
    if ($payment_method==2) {
      $where = array('id' => $user_id);
      $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
      $wallet_balance = $wb[0]->wallet_balance;
      if ($fare_amount>$wallet_balance) {
        $respo = array(
          "controller" => "user",
          "action" => "updatePaymentMethod",
          'Errorcode'=>401,
          'inputs'=>$this->input->post(),
          'wallet_balance'=>$wallet_balance,
          "ResponseCode" => false,
          "MessageWhatHappen" => "Insufficient wallet balance.",
        );
        $this->set_response($respo, REST_Controller::HTTP_OK);
        return false;
      }
    }

    //Update payment method
    $insertData= array(
        'payment_method'=>$this->post('payment_method')
      );   
    $this->db->where('id', $data);
    $this->db->update('tbl_jobs', $insertData);

    $respo = array(
          "controller" => "user",
          "action" => "updatePaymentMethod",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Updated Payment Method",              
      );
    
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function updatePaymentMethodDriver_post()
  {
    /*
    *Driver hit it after finish ride API to change passenger payment method
    *Last step
    *
    */
    $data =$this->post('trip_id');
    $payment_method = $this->post('payment_method');
    $query = $this->db->query('select * from tbl_jobs where id='.$data)->result();
    $driver_id = $query[0]->driver_id;
    $user_id = $query[0]->user_id;
    $fare_amount = $query[0]->fare;

    //Check wallet balance if method changed to wallet
    /*if ($payment_method==2) {
      $where = array('id' => $user_id);
      $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
      $wallet_balance = $wb[0]->wallet_balance;
      if ($fare_amount>$wallet_balance) {
        $respo = array(
          "controller" => "user",
          "action" => "updatePaymentMethodDriver",
          'Errorcode'=>401,
          'inputs'=>$this->input->post(),
          'wallet_balance'=>$wallet_balance,
          "ResponseCode" => false,
          "MessageWhatHappen" => "Insufficient wallet balance.",              
        );
        $this->set_response($respo, REST_Controller::HTTP_OK);
        return false;
      }
    }*/
    $this->db->trans_start();
    //Update payment method and Complete the ride
    $insertData= array(
        'payment_method'=>$this->post('payment_method'),
        'is_completed_ride'=>'1'
      );   
    $this->db->where('id', $data);
    $this->db->update('tbl_jobs', $insertData);

    //Free the driver
    $mydata = array(
      'is_free'=>'1'
      );
    $this->db->where('id', $driver_id);
    $this->db->update('tbl_users',$mydata);

    $this->db->trans_complete(); 
    $respo = array(
          "controller" => "user",
          "action" => "updatePaymentMethod",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Updated Payment Method",              
      );
    
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }
    
  public function payment_method_post()
  {
    $updatedata= array(
        'payment_method'=>$this->post('payment_method')
      ); 
    $where = array('id'=>$this->post('job_id')); 
    $this->User_model->updatedata('tbl_jobs',$where,$updatedata);

    $respo = array(
          "controller" => "user",
          "action" => "payment_method",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Updated Payment Method",              
      );
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function set_status_get(){
    $data = array('user_id' => $this->get('user_id'), 'status' => $this->get('status'),);
    $db_result = $this->User_model->selectdata('tbl_jobs','id',array('driver_id'=>$data['user_id'],'is_active'=>1));
    // print_r($db_result);die;
    if (count($db_result)>0 && $data['status']==0) {
      $respo = array(
            "controller" => "user",
            "action" => "set_status",
            "ResponseCode" => false,
            "MessageWhatHappen" => "You can't get offline as ride's going on",
            "UserData" => $db_result
        );
    } else{
      $result = $this->User_model->set_status($data);
      // print_r($result);die;
      if($result['code']==0){
          $respo = array(
              "controller" => "user",
              "action" => "set_status",
              "ResponseCode" => true,
              "MessageWhatHappen" => "Offline",
              "UserData" => $result
          );
      }
      elseif($result['code']==1){
          $respo = array(
              "controller" => "user",
              "action" => "set_status",
              "ResponseCode" => true,
              "MessageWhatHappen" => "Online",
              "UserData" => $result
          );
      }
    }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function ride_history_get()
  {
    $data = array('id' => $this->get('user_id'),
        'user_type' => $this->get('user_type'),
        'limit' => $this->get('limit'),
        'offset' => $this->get('offset'),
        );
    $result = $this->User_model->ride_history($data);
    // print_r($result);die;
    $total_records = $this->User_model->totalRecords($data['id']);
    $issue_list = $this->User_model->issue_list();
    if(!empty($result)){
        $respo = array(
            "controller" => "user",
            "action" => "ride_history",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Ride History Found",
            "UserData" => $result,
            "issue list" => $issue_list,
            'total_records' => $total_records
        );
    }
    elseif($result==0){
        $respo = array(
            "controller" => "user",
            "action" => "ride_history",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Wrong inputs(user_type)",
        );
    }
    else{
        $respo = array(
            "controller" => "user",
            "action" => "ride_history",
            "ResponseCode" => false,
            "MessageWhatHappen" => "No record found",
        );
    }
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function restricted_zone_get()
  {
    $data = $this->User_model->restricted_zone();
    $respo = array(
        "controller" => "user",
        "action" => "restricted_zone",
        "ResponseCode" => true,
        "MessageWhatHappen" => "List of restricted zone",
        "data" => $data
    );
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

  public function update_vehicle_get()
  {
    $data = array('user_id' => $this->get('user_id'),
    'vehicle_id' => $this->get('vehicle_id'), );
    $result = $this->User_model->update_vehicle($data);
    $respo = array(
        "controller" => "user",
        "action" => "restricted_zone",
        "ResponseCode" => true,
        "MessageWhatHappen" => "Vehicle Type Updated",
    );
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }

/*--------------------------------------------------------------------*/
  public function insertlatlong_get() {
    $job_id = $this->input->get('job_id');
    $latitude = $this->input->get('latitude');
    $longitude = $this->input->get('longitude');
    $driver_id = $this->input->get('user_id');
    if(($job_id!="") && ($latitude!="") && ($longitude!="") && ($driver_id!="")){
        $insertdata = array("job_id"=>$job_id,"latitude"=>$latitude,"longitude"=>$longitude);
        $this->User_model->insertdata("tbl_job_distance",$insertdata);
        
        $updatedata = array('latitude'=>$latitude,'longitude'=>$longitude);
        $where = array('id'=>$driver_id);
        $this->User_model->updatedata("tbl_users",$where,$updatedata);
        
        $result = array(
            "controller" => "user",
            "action" => "insertlatlong",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Successfully Inserted"
        );
    }
    else{
        $result = array(
            "controller" => "user",
            "action" => "insertlatlong",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Please fill all fields"
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);       
  }

  public function updatewaitingtime_get() {
    $job_id = $this->input->get('job_id');
    $time = $this->input->get('time');
    $flag= $this->input->get("flag");
      if($flag== "0")
  {
    $data = $this->db->query(" UPDATE tbl_jobs SET is_waiting = 1, waitingStartedOn=NOW() WHERE  id = '".$job_id."'");
     $result = array(
                    "controller" => "user",
                    "action" => "updatewaitingtime",
                    "ResponseCode" => true,
                    "MessageWhatHappen" => "waiting stop",
                    'time' => $time,
                    "is_waiting" => $getdata[0]->is_waiting
                );
  }    
      if($flag== "1")
  {
    $data = $this->db->query(" UPDATE tbl_jobs SET is_waiting = 0 WHERE  id = '".$job_id."'");
     $result = array(
                    "controller" => "user",
                    "action" => "updatewaitingtime",
                    "ResponseCode" => true,
                    "MessageWhatHappen" => "waiting start",
                    'time' => $time,
                    "is_waiting" => $getdata[0]->is_waiting
                );
  } 
    if(($flag!="") && ($job_id!="")){
        if($flag=="0"){
            $where = "id = '".$job_id."'";
            $getdata = $this->User_model->selectdata("tbl_jobs","tbl_jobs.waiting_time,waitingStartedOn",$where);
            $result = array(
                    "controller" => "user",
                    "action" => "updatewaitingtime",
                    "ResponseCode" => true,
                    "MessageWhatHappen" => "get data",
                    'time' => $time,
                    "Waiting_time" => $getdata[0]->waiting_time,
                    'waitingStartedOn' => $getdata[0]->waitingStartedOn
                );

        }
        else{
            if($time!=""){
                $updatedata = array('waiting_time'=>$time);
                $where = array('id'=>$job_id);
                $this->User_model->updatedata("tbl_jobs",$where,$updatedata);            
                $result = array(
                    "controller" => "user",
                    "action" => "updatewaitingtime",
                    "ResponseCode" => true,
                    'time' => $time,
                    "MessageWhatHappen" => "Successfully Updated"
                );
            }
            else{
                $result = array(
                    "controller" => "user",
                    "action" => "updatewaitingtime",
                    "ResponseCode" => false,
                    "MessageWhatHappen" => "Please fill all fields"
                );
            }                
        }
    }
    else
    {
        $result = array(
            "controller" => "user",
            "action" => "updatewaitingtime",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Please fill all fields"
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);       
  }

  public function waitingtime_get()
  {
    $job_id = $this->input->get('job_id');
    $flag= $this->input->get("flag");
    // flag 1->start 0->stop
    $where = "id = '".$job_id."'";
    $getdata = $this->User_model->selectdata("tbl_jobs","tbl_jobs.waiting_time,waitingStartedOn",$where);
    $oldTime = $getdata[0]->waiting_time;
    // var_dump($oldTime);die;
    $date = date("Y-m-d H:i:s");
    if ($flag==1) {
      if ($oldTime=="00:00:00") {
        $time = $oldTime;
      }else{
        $date1 = $getdata[0]->waitingStartedOn;
        $date2 = $date;
        $start_date = new DateTime($date1);
        $end_date = new DateTime($date2);
        $dteDiff = $start_date->diff($end_date);
        $time2 = $dteDiff->format("%H:%I:%S"); 

        $secs = strtotime($time2)-strtotime("00:00:00");
        $time = date("H:i:s",strtotime($oldTime)+$secs);
        // print_r($time);die;
      }
    } else {
      $date1 = $getdata[0]->waitingStartedOn;
      $date2 = $date;
      $start_date = new DateTime($date1);
      $end_date = new DateTime($date2);
      $dteDiff = $start_date->diff($end_date);
      $time2 = $dteDiff->format("%H:%I:%S"); 

      $secs = strtotime($time2)-strtotime("00:00:00");
      $time = date("H:i:s",strtotime($oldTime)+$secs);
      // print_r($time);die;
      $date = $date1;
    }
    $updatedata = array('waiting_time'=>$time,'is_waiting'=>$flag,'waitingStartedOn'=>$date);
    $where = array('id'=>$job_id);
    $this->User_model->updatedata("tbl_jobs",$where,$updatedata);    
    
    $where = "id = '".$job_id."'";
    $getdata = $this->User_model->selectdata("tbl_jobs","is_waiting,waiting_time,waitingStartedOn",$where);
    $result = array(
        "controller" => "user",
        "action" => "waitingtime_get",
        "ResponseCode" => true,
        'time' => $getdata,
        "MessageWhatHappen" => "Successfully Updated"
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function insertrating_post(){
    $rating = $this->input->post('rating');
    if($rating!=""){
        $insertdata = array("job_id"=>$this->input->post('job_id'),
                            "user_id"=>$this->input->post('customer_id'),
                            "driver_id"=>$this->input->post('driver_id'),
                            "user_type"=>$this->input->post('user_type'),
                            "review"=>$this->input->post('review'),
                            "rating"=>$rating
                            );
        $this->User_model->insertdata("tbl_rating",$insertdata);
         $result = array(
                    "controller" => "user",
                    "action" => "insertrating",
                    "ResponseCode" => true,
                    "MessageWhatHappen" => "Rating sent Successfully",
                    );
    }
    else
    {
        $result = array(
            "controller" => "user",
            "action" => "insertrating",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Please Give some rating"
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function driver_rate_get($id){
    if($id!=""){
        $query = "SELECT tbl_rating.user_id,tbl_rating.review,tbl_rating.rating,tbl_rating.driver_id,tbl_users.profile_pic FROM `tbl_rating` left join tbl_users on tbl_users.id = tbl_rating.user_id where driver_id = '".$id."' order by rating desc";
        $data = $this->User_model->select_by_query($query);
        $result = array(
                        "controller" => "user",
                        "action" => "driver_rate",
                        "ResponseCode" => true,
                        "MessageWhatHappen" => "Rating About Driver",
                        "data" =>$data
                    );
    }
    else
    {
        $result = array(
            "controller" => "user",
            "action" => "driver_rate",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Driver Id is empty"
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }
  public function addressLang_get(){
    $dropoff_loc = $this->input->get('location');
    $lang = $this->input->get('lang');
    $address = urlencode($dropoff_loc);

     if($dropoff_loc !="" && $lang !=""){
    // print_r($address);die;
    $url = "http://maps.googleapis.com/maps/api/geocode/json?address=$address&language=$lang";
     $json = file_get_contents($url);
      $res = json_decode($json);
     $result = array(
            "controller" => "user",
            "action" => "get address and lang",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Successfull",  
            "GetData" => $res 
        );
   }
   else{
        $result = array(
            "controller" => "user",
            "action" => "incorrect data",
            "ResponseCode" => false,
            "MessageWhatHappen" => "failure",  
           
        );

   }
      $this->set_response($result, REST_Controller::HTTP_OK); 
   }
  

  function update_drop_off_get()
  {
    $job_id = $this->input->get('job_id');
    $dropoff_loc = $this->input->get('dropoff_loc');
    $pickup_lat = $this->input->get('pickup_lat');
    $pickup_long = $this->input->get('pickup_long');
    $dropoff_lat = $this->input->get('dropoff_lat');
    $dropoff_long = $this->input->get('dropoff_long');
    $language = $this->input->get('language');

    // $way_points = $this->input->get('way_points');
    // $placeid =  $this->input->get('placeid');

    if ($dropoff_long != "")
    {
      $res = googleDirections($_GET);
      if ($res->status != "OK")
      {
        $result = array(
          "controller" => "user",
          "action" => "update_drop_off_get",
          "ResponseCode" => false,
          "MessageWhatHappen" => $res,
        );
        $this->set_response($result, REST_Controller::HTTP_OK); 
        return false;
      }
      $way_points = base64_encode($res->routes[0]->overview_polyline->points);
      $dropoff_lat1 = $res->routes[0]->legs[0]->end_location->lat;
      $dropoff_long1 = $res->routes[0]->legs[0]->end_location->lng;
      $dropoff_location1 = $res->routes[0]->legs[0]->end_address;

      // die();

    }

    if (($dropoff_long != "") && ($dropoff_lat != "") && ($job_id != ""))
    {
      $DurationS = $res->routes[0]->legs[0]->duration->value;
      $distanceS = $res->routes[0]->legs[0]->distance->value;

      // print_r($DurationS);

      // $explodeData = explode(' ', $DurationS);
      $vals = round($DurationS/60);
      // $explodeData1 = explode(' ', $distanceS);
      $vals1 = round($distanceS/1000);

      // print_r($vals1);die();

      $vehicle_info = $this->User_model->selectdata('tbl_vehicle', '*', array(
        'vehicle_status' => 0
      ));

      // print_r($vehicle_info);die();

      $minFare = $vehicle_info[0]->minimum_fare;
      $per_km = $vehicle_info[0]->per_km;
      $traffic_cahrges_permin = $vehicle_info[0]->traffic_charges;
      $distance = $res->routes[0]->legs[0]->distance->value;

      // print_r($distance);die;
      $info['base_fare'] = $minFare;
      $info['per_km'] = $per_km;
      $info['traffic_cahrges_permin'] = $traffic_cahrges_permin;
      $info['distance'] = $distance/1000;
      if ($distance < 1000)
      {

        // print_r($distance);die;

        $info['estimated_fare'] = $minFare;
      }
      elseif ($distance >= 1000 && $distance < 8000)
      {
        $distanceData = $distance / 1000;
        // print_r($distanceData);
        $distanceRate = ($distanceData - 1) * $per_km;
        $info['estimated_fare'] = round(($distanceRate + $minFare) / 500) * 500;

        // $info['estimated_fare'] = $minFare;
        // print_r($info);die;

      }
      else
      {
        $duration = $res->routes[0]->legs[0]->duration->value;
        $duration_in_traffic = $res->routes[0]->legs[0]->duration_in_traffic->value;
        $divisor_for_minutes = $duration % (60 * 60);
        $duration = floor($divisor_for_minutes / 60);
        $divisor_for_minutes = $duration_in_traffic % (60 * 60);
        $duration_in_traffic = floor($divisor_for_minutes / 60);
        if ($duration_in_traffic > $duration)
        {
          $extraTime = $duration_in_traffic - $duration;
        }
        else
        {
          $extraTime = 0;
        }

        $newDistance = round(($distance - 1000) / 1000);
        // echo "$per_km";die;

        $traffic_cahrges = $extraTime * $traffic_cahrges_permin;
        $fare = $minFare + ($newDistance * $per_km) + $traffic_cahrges;

        $info['duration'] = $duration;
        $info['timeInTraffic'] = $extraTime;
        // //////////////////////////////////////////////////////////////////
        $fare1 = $minFare + ($newDistance * $per_km);
        $info['decFare'] = $fare1;
        $info['normal_fare'] = round($fare1 / 500) * 500;
        $info['estimated_fare'] = round(($fare1 + $traffic_cahrgess) / 500) * 500;

        // print_r($info['estimated_fare']);die;

      }

      $implodeData = array(
        $vals1,
        $vals,
        $info['estimated_fare']
      );
      $newEstimate = implode($implodeData, ',');

      // print_r($newEstimate);die();

      $updatedata = array(
        'dropoff_location' => $dropoff_location1,
        'dropoff_lat' => $dropoff_lat,
        'dropoff_long' => $dropoff_long,
        'estimate' => $newEstimate,
        'way_points' => $way_points,
        'destination_changed' => 1
      );
      $where = array(
        'id' => $job_id
      );
      $this->User_model->updatedata("tbl_jobs", $where, $updatedata);
      $query = $this->db->query("select id,dropoff_location,dropoff_lat,dropoff_long from tbl_jobs where id=" . $job_id)->result_array();

      // print_r($query);die;

      $query[0]['estimated_fare'] = $info['estimated_fare'];
      $query[0]['way_points'] = $res;
      $result = array(
        "controller" => "user",
        "action" => "update_drop_off",
        "ResponseCode" => true,
        "MessageWhatHappen" => "Successfully Updated",
        'info' => $info
      );
    }
    else
    {
      $result = array(
        "controller" => "user",
        "action" => "update_drop_off",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Please fill all fields"
      );
    }

    $this->set_response($result, REST_Controller::HTTP_OK);
  }
/*-------------------------------------------------------------------------*/
  public function verify_password_post()
  {
    $data = array('id' => $this->post('user_id'),
    'password' => md5($this->post('password')), );
    $respo = $this->User_model->verify_password($data);
    if ($respo == '1') {
      $result = array(
            "controller" => "user",
            "action" => "verify_password",
            "ResponseCode" => true,
            "MessageWhatHappen" => $respo
        );
    } else {
      $result = array(
            "controller" => "user",
            "action" => "verify_password",
            "ResponseCode" => false,
            "MessageWhatHappen" => $respo
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }
    function place_details_get(){ 
    $data = array(
      'placeid'=>$this->input->get('placeid'),  
      );
     if(empty($data['placeid'])) {

      $result = array(
              "controller" => "user",
              "action" => "Geocode",
              "ResponseCode" => false,
              "MessageWhatHappen" => 'Enter the Parameters'
          );
    }
    $key = "AIzaSyDnT9r_aKKC5_isYcgkWKHhedGAh-Mv3-U";
    $result1 = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$data['placeid'].'&key='.$key; 
    $json = file_get_contents($result1);
    $data = json_decode($json);    
        if($result1){
            $result =  $data;
          
        }
        else{
             $result = array(
              "controller" => "user",
              "action" => "Geocode",
              "ResponseCode" => false,
              "MessageWhatHappen" => 'No location found'
          );
        }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function update_address_get()
  {
    if ($this->get('flag') == '1')
    { //to update address
      $id = $this->get('user_id');
      $find_user = $this->User_model->selectdata("tbl_users", "id", array(
        'id' => $id
      ));
      $location = $this->get('location');
      $placeid = $this->get('placeid');
      $type = $this->get('type');
      if ($type == 0)
      { //Home location Updating
        $data['home_address_farsi'] = $location;
        $data['home_address'] = $location;
        if (empty($this->get('latlng')))
        {
          $url = "http://phphosting.osvin.net/aberbackusa/admin/api/User/place_details?placeid=$placeid";

          //  $json = file_get_contents($url);

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          curl_close($ch);

          // $json = file_get_contents($url);

          $res = json_decode($response);
          $lat = $res->result->geometry->location->lat;
          $lng = $res->result->geometry->location->lng;
          $data['home_latlng'] = "$lat,$lng";

          // Call place details API and get latlongs from there
        }
        else
        {
          $data['home_latlng'] = $this->get('latlng');
        }

        // Update to users table for home locations and addresses

        $respo = $this->User_model->update_address($data, $id);
      }
      elseif ($type == 1)
      { //Work location Updating
        $data['work_address'] = $location;
        $data['work_address_farsi'] = $location;
        if (empty($this->get('latlng')))
        {

          // Call place details API and get latlongs from there

          $url = "http://phphosting.osvin.net/aberbackusa/admin/api/User/place_details?placeid=$placeid";

          // $json = file_get_contents($url);

          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $response = curl_exec($ch);
          curl_close($ch);

          // $json = file_get_contents($url);

          $res = json_decode($response);
          $lat = $res->result->geometry->location->lat;
          $lng = $res->result->geometry->location->lng;
          $data['work_latlng'] = "$lat,$lng";
        }
        else
        {
          $data['work_latlng'] = $this->get('latlng');
        }

        // Update to users table for work locations and addresses

        $respo = $this->User_model->update_address($data, $id);
      }
      else
      {
        $respo = 0;
      }

      if ($respo != 0)
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => true,
          "MessageWhatHappen" => 'Updated Successfully',
          'GetData' => $respo
        );
      }
      elseif ($respo == '404')
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => false,
          "MessageWhatHappen" => "User not found"
        );
      }
      else
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => false,
          "MessageWhatHappen" => "No address selected"
        );
      }

      $this->set_response($result, REST_Controller::HTTP_OK);
    }
    elseif ($this->get('flag') == '03')
    { //to delete home address
      $id = $this->get('user_id');
      $data['home_address'] = "";
      $respo = $this->User_model->update_address($data, $id);
      if ($respo == '1')
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Home address deleted"
        );
      }
      else
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => false,
          "MessageWhatHappen" => "No address selected"
        );
      }

      $this->set_response($result, REST_Controller::HTTP_OK);
    }
    elseif ($this->get('flag') == '02')
    { //to delete work address
      $id = $this->get('user_id');
      $data['work_address'] = "";
      $respo = $this->User_model->update_address($data, $id);
      if ($respo == '1')
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => true,
          "MessageWhatHappen" => "Work address deleted"
        );
      }
      else
      {
        $result = array(
          "controller" => "user",
          "action" => "update_address",
          "ResponseCode" => false,
          "MessageWhatHappen" => "No address selected"
        );
      }

      $this->set_response($result, REST_Controller::HTTP_OK);
    }
    else
    {
      $result = array(
        "controller" => "user",
        "action" => "update_address",
        "ResponseCode" => false,
        "MessageWhatHappen" => "no flag set"
      );
      $this->set_response($result, REST_Controller::HTTP_OK);
    }
  }

  public function location_post()
  {
    $id = $this->input->post('id'); //update
    $locationData = array(
      'location_name'=>$this->input->post('location_name'),
      'other_name'=>$this->input->post('other_name'),
      'latitude'=>$this->input->post('latitude'),
      'longitude'=>$this->input->post('longitude'),
      'user_id'=>$this->input->post('user_id'),
      'location_type'=>$this->input->post('location_type'),
      'date_created'=>date("Y-m-d H:i:s")
    );
    // $locationList = $this->User_model->selectdata('tbl_user_location','count(*)',array('user_id'=>$locationData['user_id']));
    if (empty($id)) {
      if ($locationData['location_type']==2) {
        $countFav = $this->User_model->selectdata('tbl_user_location','count(*) as favCount',array('location_type'=>$locationData['location_type'],'user_id'=>$locationData['user_id']));
        $favCount = $countFav[0]->favCount;
        $favLimit = 4;
        if ($favCount>=$favLimit) {
          $response = array(
            'controller'=>'user',
            'action'=>'location',
            'ResponseCode'=>false,
            'locationList'=>'Limit reached'
          );
          $this->set_response($response, REST_Controller::HTTP_OK);
          return false;
        }else{
          // print_r($locationData);die;
          $this->User_model->insertdata('tbl_user_location',$locationData);

          $response = array(
            'controller'=>'user',
            'action'=>'location',
            'ResponseCode'=>true,
            'locationList'=>'Added'
          );
        }
      }else{
        // print_r($locationData);die;
        $this->User_model->insertdata('tbl_user_location',$locationData);

        $response = array(
          'controller'=>'user',
          'action'=>'location',
          'ResponseCode'=>true,
          'locationList'=>'Added'
        );
      }
    } else {
      $this->User_model->updatedata('tbl_user_location',array('id'=>$id),$locationData);
        $response = array(
        'controller'=>'user',
        'action'=>'location',
        'ResponseCode'=>true,
        'locationList'=>'Updated'
      );
    }
    
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function location_get()
  {
    $user_id = $this->input->get('user_id');
    $locationList = $this->User_model->selectdata('tbl_user_location','*',array('user_id'=>$user_id));
    $response = array(
      'controller'=>'user',
      'action'=>'location',
      'ResponseCode'=>true,
      'locationList'=>$locationList
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function location_delete($id)
  {
    $this->User_model->deleteWhere('tbl_user_location',array('id'=>$id));
    $response = array(
      'controller'=>'user',
      'action'=>'location',
      'ResponseCode'=>true,
      'locationId'=>$id
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function sendemail($data)
  {
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
                  <th style=font-size:20px; font-weight:bolder; text-align:right;padding-bottom:10px;border-bottom:solid 1px #ddd;> Hello ".$data['name']."</th>
                </tr>
                <tr>
                  <td style=font-size:16px;>
                    <p>".$data['message']."</p>
                    <p><a href='".$data['url']."''>".$data['url_name']."</a></p>
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
      // echo $body;
      // die();
          /*$this->email->set_newline("\r\n");
          $this->email->from($data['from']);
          $this->email->to($data['email']);
          $this->email->subject($data['subject']);
          $this->email->message($body);
          $this->email->send();*/

$from = $data['from'];
// $headers = 'MIME-Version: 1.0' . "\r\n";
// $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";

// // More headers
// $headers .= 'From: '.$from. "\r\n";
// $headers .= 'Reply-To: '.$from."\r\n";  
// $headers .= 'Return-Path: '.$from."\r\n";
// //$headers .= 'Bcc: '.$from."\r\n";
// //$headers .= 'Cc: myboss@example.com' . "\r\n";
// mail($data['email'],'Password Retrival',$body,$headers);

$this->email->set_newline("\r\n");
$this->email->from('masir@masirapp.com',$from);
$this->email->to($data['email']);
$this->email->subject('Customer Support');
$this->email->message($body);
$this->email->send();
  }

  public function last_ride_get()
  {
    $id = $this->get('user_id');
    $type = $this->get('user_type');
    $respo = $this->User_model->last_ride($id,$type);
    $issue_list = $this->User_model->issue_list();
    // print_r($respo['ride_info']);die;
    if ($respo['ride_info']!="") {
      // print_r($respo['ride_info']->id);die;
      // $latlng = $this->User_model->get_points($respo['ride_info']->id);
      $result =  array(
            "controller" => "user",
            "action" => "last_ride",
            "ResponseCode" => true,
            "MessageWhatHappen" => "last ride details",
            "last_ride" => $respo,
            "issue_list" => $issue_list
        );
    } else {
      $result =  array(
            "controller" => "user",
            "action" => "last_ride",
            "ResponseCode" => false,
            "MessageWhatHappen" => "last ride details not found",
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function submit_issue_post()
  {
    $data = array('id' => $this->post('job_id'),
    'user_type' => $this->post('user_type'),
    'issueTitle' => $this->post('issueTitle'),
    'comment' => $this->post('comment'), );
    $respo = $this->User_model->submit_issue($data);
    $result =  array(
            "controller" => "user",
            "action" => "submit_issue",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Submitted Successfully",
        );
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function issue_list_get()
  {
    $data = array('issue_type' => $this->get('issue_type'),);
    $issue_list = $this->User_model->issue_list($data);
    // print_r($issue_list);die;
    $result =  array(
            "controller" => "user",
            "action" => "submit_issue",
            "ResponseCode" => true,
            // "MessageWhatHappen" => "Submitted Successfully",
            "GetData" => $issue_list
        );
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function distance_get()
  {
    $data = array('job_id' => $this->get('job_id'), );
    $way_points = $this->User_model->get_points($data);
    // print_r($way_points);
    // print_r($way_points[1]->longitude);
    $l = count($way_points);
    // print_r($l);
    $unit = "K";
    for ($i=0; $i <= $l-2; $i++) { 
      $lat1 = $way_points[$i]->latitude;
      $lon1 = $way_points[$i]->longitude;
      $lat2 = $way_points[$i+1]->latitude;
      $lon2 = $way_points[$i+1]->longitude;
      // print_r($lat1);
      $result = $this->User_model->distance($lat1, $lon1, $lat2, $lon2, $unit);
      $a += $result;
      print_r($a); 
      echo "\n";
    }
  }

  public function job_details_get()
  {
    $job_id = $this->get('job_id');
    $job_details = $this->User_model->job_details($job_id);
    
    if ($job_details!=0) {
    $user_id = $job_details[0]->user_id;
    // print_r($job_details);die;
    $locationList = $this->User_model->selectdata('tbl_user_location','*',array('user_id'=>$user_id));
      $result =  array(
            "controller" => "user",
            "action" => "job_details",
            "ResponseCode" => true,
            "MessageWhatHappen" => "Job Details:",
            "job_details" => $job_details,
            "locationList" => $locationList,
        );
    } else {
      $result =  array(
            "controller" => "user",
            "action" => "job_details",
            "ResponseCode" => false,
            "MessageWhatHappen" => "Job id not found:",
        );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function send_message_post()
  {
    $datetime = date("Y-m-d H:i:s");

    $data = array(
      'job_id' => $this->post('job_id'), 
      'from_id' => $this->post('from_id'), 
      'to_id' => $this->post('to_id'), 
      'message' => $this->post('message'), 
      'datetime' => $datetime 
    );

    $from = $this->User_model->viewprofile($data['from_id']);
    $name = $from['userdata'][0]->name;
    $profile_pic = $from['userdata'][0]->profile_pic;
    if (empty($data['job_id'])) {
      $jobDetails = $this->User_model->selectdata('tbl_jobs','id',array('user_id'=>$data['from_id'],'driver_id'=>$data['driver_id']));
      $data['job_id'] = $jobDetails[0]->id;
    }else{
      $jobDetails = $this->User_model->selectdata('tbl_jobs','*',array('id'=>$data['job_id']));
    }
    $is_active=$jobDetails[0]->is_active;
    // print_r($is_active);die;
    $user_login_data = $this->User_model->getUserLogin($data['to_id']);
    // print_r($from);die;
    if ($is_active==0) {
      $result =  array(
              "controller" => "user",
              "action" => "send_message_post",
              "ResponseCode" => false,
              'Errorcode'=>401,
              "MessageWhatHappen" => "Trip is inactive.",
          );
    } elseif (count($user_login_data) == "") {
      $result =  array(
              "controller" => "user",
              "action" => "send_message_post",
              "ResponseCode" => false,
              'Errorcode'=>402,
              "MessageWhatHappen" => "User is offline",
          );
    } else {
      $this->User_model->insertdata("tbl_chat",$data);
      $decMessage = base64_decode($data['message']);
      foreach ($user_login_data as $value) {
        if($value->mb_device_id==0){
            $push = $this->User_model->android($value->mb_token_id, $data['message'],"","chating",$data['job_id'],$data['from_id'],$name,$profile_pic,$datetime);
        }else{
            $param = array(
              'token_id'=>$value->mb_token_id,
              'message'=>"driver_message",
              'action'=>$decMessage,
              'id'=>$data['from_id'],
              'sound'=>"chat.mp3",
            );
            $this->iosPush_post($param);
        }
      }
            // print_r($push);die;
      $result =  array(
              "controller" => "user",
              "action" => "send_message_post",
              "ResponseCode" => true,
              "MessageWhatHappen" => "message sent",
              "dateTime" => $datetime,
          );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function chat_history_get()
  {
    $data = array('from_id' => $this->get('from_id'),
    'to_id' => $this->get('to_id'),
    'job_id' => $this->get('job_id'),);
    
    $jobDetails = $this->User_model->selectdata('tbl_jobs','*',array('id'=>$data['job_id']));
    $is_active=$jobDetails[0]->is_active;
    if ($is_active==0) {
      $result =  array(
        "controller" => "user",
        "action" => "send_message_post",
        "ResponseCode" => false,
        'Errorcode'=>401,
        "MessageWhatHappen" => "Trip is inactive.",
      );
    } else{
      $chat_history = $this->User_model->chat_history($data);
      // print_r($chat_history);
      $result =  array(
        "controller" => "user",
        "action" => "chat_history_get",
        "ResponseCode" => true,
        "MessageWhatHappen" => "record found",
        "GetData" => $chat_history
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function wallet_get()
  {
    $user_id = $this->get('user_id');
    $where = "(`user_id` = $user_id AND `payment_method` != 0)";
    $detail = $this->User_model->selectdata("tbl_payment","*",$where,'','id','desc',10);
    // print_r($this->db->last_query());die;
    // echo(count($detail));
    // for ($i=0; $i <= count($detail); $i++) { 
    //   foreach ($detail[$i] as $key => $value) {
    //     echo "$key"." => "."$value"."<br>";
    //   }
    // }
    $where = array('id' => $user_id, );
    $wallet_balance = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    $walletBalance  = $wallet_balance[0]->wallet_balance;
      // $infos = round($walletBalance/500)*500;
      $infos = round($walletBalance);
      // print_r($infos);die();
    $result =  array(
      "controller" => "user",
      "action" => "wallet",
      "ResponseCode" => true,
      "GetData" => $detail,
      "wallet_balance" => $infos,
      );
    $this->set_response($result, REST_Controller::HTTP_OK); 
  }

  public function notifications_get()
  {
    $user_type = $this->get('user_type');
    $user_id = $this->get('user_id');

    $table_name = "tbl_jobs";
    $select = "id,date_created,job_start_datetime,accept_datetime,arrived_datetime";
    
    if ($this->get('user_type')==0) {
      $where = "user_id = '".$this->get('user_id')."' AND is_active = '1'";
    } else {
      $where = "driver_id = '".$this->get('user_id')."' AND is_active = '1'";
    }
          
    $order = "id";
    $by = "desc";
    $job = $this->User_model->selectdata($table_name,$select,$where,"",$order,$by);
    // print_r($this->db->last_query());die;

    $notifications = $this->User_model->notifications($user_id,$user_type);
    $unread_count = $this->User_model->notification($user_id,$user_type);
    if ($job[0]->job_start_datetime != "0000-00-00 00:00:00" || count($notifications) > 0) {
      $result =  array(
      "controller" => "user",
      "action" => "notifications",
      "ResponseCode" => true,
      "job_notification" => $job,
      "other_notifications" => $notifications,
      'unread_count' => $unread_count->unread,
      );
    } else {
      $result =  array(
      "controller" => "user",
      "action" => "notifications",
      "ResponseCode" => false,
      "MessageWhatHappen" => "Nothing Here But Me :( ",
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function notificationRead_post()
  {
    $data = array(
      'user_id' => $this->input->post('user_id'),
      'notification_id' => $this->input->post('notification_id'),
      'date_modified' => date("Y-m-d H:i:s"),
      'status' => 1,
    );
    $sel = $this->User_model->selectdata('tbl_notification_status', 'id', array('notification_id'=>$data['notification_id'],'user_id'=>$data['user_id']));
    if (empty($sel)) {
      $this->User_model->insertdata('tbl_notification_status', $data);
    }

    $result =  array(
      "controller" => "user",
      "action" => "notificationRead",
      "ResponseCode" => true,
      "notification_id" => $data['notification_id'],
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function payment_get()
  {
    // phpinfo();die;  
    $MerchantID = '7ee6cf54-27d0-11e6-9d48-005056a205be'; //Required
    $job_id = $this->get('job_id');
    $user_id = $this->get('user_id');
    $Amount = $this->get('amount'); //Amount will be based on Toman - Required
    $Description = 'توضیحات تراکنش تستی'; // Required
    $Email = 'osvinandroid@gmail.Com'; // Optional
    $Mobile = '+917832027983'; // Optional

    if ($user_id != "") {
      $CallbackURL = base_url().'api/user/verify?user_id='.$user_id.'&amount='.$Amount; // Required
    } else {
      $CallbackURL = base_url().'api/user/verify?job_id='.$job_id.'&amount='.$Amount; // Required
    }

    libxml_disable_entity_loader(false);
    $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);
    // echo "$CallbackURL";die;

    $result = $client->PaymentRequest(
      [
      'MerchantID' => $MerchantID,
      'Amount' => $Amount,
      'Description' => $Description,
      'Email' => $Email,
      'Mobile' => $Mobile,
      'CallbackURL' => $CallbackURL,
      ]
      );

    //Redirect to URL You can do it also by creating a form
    if ($result->Status == 100) {
      Header('Location: https://www.zarinpal.com/pg/StartPay/'.$result->Authority);
    } else {
      echo'ERR: '.$result->Status;
      $respo['status'] = $result->Status;
      // $this->load->view('transaction',$respo);
    }
  }

  public function verify_get()
  {
    $MerchantID = '7ee6cf54-27d0-11e6-9d48-005056a205be';
    $data['job_id'] = $_GET['job_id'];
    $data['user_id'] = $_GET['user_id'];
    $Amount = $_GET['amount'];
    $Authority = $_GET['Authority'];
    $datetime = date("Y-m-d H:i:s");

    if($data['user_id'] != ""){
        $selectdata = $this->User_model->selectdata("tbl_users","wallet_balance",array('id'=>$data['user_id']));
        $respo['wallet_balance'] = $selectdata[0]->wallet_balance;
    }

    // print_r($_GET['job_id']);die;
    if ($data['job_id'] != "") {
      $job_details = $this->User_model->get_job($data);
      // print_r($job_details);die;
      $Amount = $job_details['payable_amount']; //Amount will be based on Toman
      // $Amount = 100;
      
    }

    if ($_GET['Status'] == 'OK') {
      libxml_disable_entity_loader(false);
      $client = new SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

      $result = $client->PaymentVerification(
        [
        'MerchantID' => $MerchantID,
        'Authority' => $Authority,
        'Amount' => $Amount,
        ]
        );

      
      
      if ($result->Status == 100) {
        // echo 'Transation success. RefID:'.$result->RefID;

        // echo "<pre>"; print_r($result); echo "</pre>";
        $respo['message'] = "Transaction has been successfull.";
        $respo['RefID'] = $result->RefID;
        $respo['status'] = $result->Status;

        if ($data['job_id'] != "") {
          $where = array('id' => $data['job_id']);
          $update_data = array('payment_status' => $result->Status,
            'payment_RefID' => $result->RefID);
          $table_name = "tbl_jobs";
          $this->User_model->updatedata($table_name,$where,$update_data);
          
          $insert_data = array('payment_type'=>'1','payment_method' => '1','payment_status' => $result->Status, 'payment_RefID' => $result->RefID, 'job_id'=>$data['job_id'],'amount'=>$Amount, 'date_created'=>$datetime);
          
        } else {
          $this->User_model->add_wallet('tbl_users',$data['user_id'],$Amount);
          $insert_data = array('payment_type'=>'2','payment_method' => '1','payment_status' => $result->Status, 'payment_RefID' => $result->RefID, 'user_id'=>$data['user_id'],'amount'=>$Amount, 'date_created'=>$datetime);
        }

      } else {
        // echo 'Transaction failed. Status:'.$result->Status;
        // echo "<pre>"; print_r($result);echo "</pre>"; 

        $respo['message'] = "Transaction failed.";
        $respo['RefID'] = $result->RefID;
        $respo['status'] = $result->Status;

        if ($data['job_id'] != "") {
          $where = array('id' => $data['job_id']);
          $update_data = array('payment_status' => $result->Status,
            'payment_RefID' => $result->RefID);
          $table_name = "tbl_jobs";
          $this->User_model->updatedata($table_name,$where,$update_data);
          $insert_data = array('payment_type'=>'1','payment_method' => '1','payment_status' => $result->Status, 'payment_RefID' => $result->RefID, 'job_id'=>$data['job_id'],'amount'=>$Amount, 'date_created'=>$datetime);
        } else {
          $insert_data = array('payment_type'=>'2','payment_method' => '1','payment_status' => $result->Status, 'payment_RefID' => $result->RefID, 'user_id'=>$data['user_id'],'amount'=>$Amount, 'date_created'=>$datetime);
        }            
      }
    } else {

      // $result = array('status'=>23,);
      // echo 'Transaction canceled by user';

      $respo['message'] = "Transaction canceled by user.";
      $respo['status'] = 333;

      if ($data['job_id'] != "") {
        // print_r($data);die;
        $where = array('id' => $data['job_id']);
        $update_data = array('payment_status' => $respo['status'],);
        $table_name = "tbl_jobs";
        $this->User_model->updatedata($table_name,$where,$update_data);

        $insert_data = array('payment_type'=>'1','payment_method' => '1','payment_status' => $respo['status'], 'job_id'=>$data['job_id'],'amount'=>$Amount, 'date_created'=>$datetime);
      } else {
        $insert_data = array('payment_type'=>'2','payment_method' => '1','payment_status' => $respo['status'],'user_id'=>$data['user_id'],'amount'=>$Amount, 'date_created'=>$datetime);
      }
    }
    // print_r($respo);die;

    $this->User_model->insertdata("tbl_payment",$insert_data);
    // $this->load->view('transaction',$respo);
    $this->set_response($respo, REST_Controller::HTTP_OK);
  }
  public function push_api_get()
  {
    $id = $this->get('driver_id');
    $push_info = $this->User_model->push_info($id);
    if (count($push_info) > 0) {
      $result =  array(
      "controller" => "user",
      "action" => "push_api",
      "ResponseCode" => true,
      "message" => "You have new ride",
      "ride_details" => $push_info,
      );
    } else {
      $result =  array(
      "controller" => "user",
      "action" => "push_api",
      "ResponseCode" => false,
      "MessageWhatHappen" => "Nothing Here But Me :( ",
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function job_status_get()
  { /* 1-accepted, 2-arrived, 3- started, 4-completed, 50-client canceled, 52-driver cancelled, 6-closed, 7-Arriving */
    // $val = 6800;
    // echo round($val/500) * 500;die;
    $user_id = $this->get('client_id');
    $token_id = $this->get('token_id');

    $getUserLogin = $this->User_model->selectdata("tbl_login","token_id",array('status'=>'1','user_id'=>$user_id));
      // print_r($getUserLogin);die;
      if ($getUserLogin[0]->token_id != $token_id) {
        $result = array(
                "controller" => "user",
                "action" => "job_status",
                "ResponseCode" => false,
                "message" => "session expired"
            );
      }
      else
      {
        $find_job_id = $this->User_model->find_job_id($user_id);
        // print_r($find_job_id->id);die;
        $job_id = $find_job_id->id;
        $data =array('job_id'=>$job_id);
        $resultstatus = $this->User_model->ride_status($data);
        // print_r($resultstatus);die;
        if ($resultstatus[1]->gender==1) {
          $settings = $this->User_model->selectdata("tbl_settings","status",array('settingName'=>"Female Profile Pic Privacy"));
          // print_r($settings);die;
          if ($settings[0]->status==1) {
            $resultstatus[1]->profile_pic="";
          }
        }
        $status = $this->User_model->job_status($job_id);

        $latlng = array(
          'latitude'=>$this->input->get('latitude'),
          'longitude'=>$this->input->get('longitude'),
          );
        $this->User_model->updatedata('tbl_users',array('id'=>$user_id),$latlng);
       // print_r($resultstatus);die;

        if (count($status) > 0) {

          $where = array('id' => $user_id);
          $wb = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
          $wallet_balance = $wb[0]->wallet_balance;
          $not =$this->User_model->notification($user_id,0);

          $coupon = $this->User_model->selectdata('tbl_promocode', '*', array(
            'promocode_beneficiary_id' => $this->input->get('client_id') ,
            'status' => '2'
          ));

          $result =  array(
          "controller" => "user",
          "action" => "status",
          "ResponseCode" => true,
          'wallet_balance'=>$wallet_balance,
          "job_id" => $job_id,
          "status_code" => $status->status,
          "payment_status" => $status->payment_status,
          'coupon' => $coupon,
          'unread_count' => $not->unread
          );
          if($status->status ==52){
          $coupon = $this->User_model->selectdata('tbl_promocode','*',array('promocode_beneficiary_id'=>$user_id ,'status'=>'2') );
          $result['coupon'] = $coupon;
          }
         
          if($status->status!=0){
            $ride_details = $this->User_model->check_status($this->input->get('client_id'));
            $result['ride_detail'] = $ride_details;
          }
          // if ($status->status=="6") {
          //   # code...
          // }
          if ($status->status ==6){
              // $insertData = array(
              // 'reason'  => '1',
              // );    
              // $where = array('user_id'=>$user_id);
              // // $this->User_model->updatedata("tbl_priceQuoteLog",$where,$insertData);  
              $result['message'] = "No Driver Found";
          }
          if ($status->status ==0){
              $result['message'] ="Searching for Drivers";
          }
          if(!($status->status==0)){
            $result['message'] = "Driver details are:";
            $result["UserData"] = $resultstatus ; 
          }
           

        }
        else {
           $result =  array(
          "controller" => "user",
          "action" => "status",
          "ResponseCode" => false,
          "message" => "Nothing Here But Me :( ",
          );
          
        }
      }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function arriving_get()
  {
    // print_r("jhsdgf");
    $this->User_model->set_arriving();
  }

  public function update_payment_method_get()
  {
    $where = array('id' => $this->get('user_id'));
    $data = array('payment_method' => $this->get('payment_method'));
    $result = $this->User_model->updatedata("tbl_users",$where,$data);

    if ($result == 1) {
      $result =  array(
      "controller" => "user",
      "action" => "update_payment_method",
      "ResponseCode" => true,
      "message" => "Updated Successfully",
      );
    } else {
      $result =  array(
      "controller" => "user",
      "action" => "update_payment_method",
      "ResponseCode" => false,
      "MessageWhatHappen" => "Nothing Here But Me :( ",
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function payment_status_get()
  {
    if ($this->get('job_id')!="") {
      $data['job_id'] = $this->get('job_id');
      $details = $this->User_model->get_job($data);
    } else {
      $user_id = $this->get('user_id');
      $details = $this->User_model->payment($user_id);
    }
    
    
    $result =  array(
      "controller" => "user",
      "action" => "payment_status_get",
      "ResponseCode" => true,
      "message" => "details",
      "GetData" => $details
      );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function set_pricing_get()
  {
    $data = array();
    $data['pricing'] = $this->get('pricing');
    $vehicle_id=$this->get('id');
    $table_name = "tbl_driver_vehicle_info";
    $this->User_model->updatedata($table_name,array('id'=>$vehicle_id),$data);

    $details = $this->User_model->selectdata($table_name,"*",array('id'=>$vehicle_id));
    // print_r($details[0]->pricing);
    if ($details[0]->pricing == $data['pricing']) {
      $result =  array(
      "controller" => "user",
      "action" => "set_pricing",
      "ResponseCode" => true,
      "message" => "successfull",
      "GetData" => $details
      );
    } else {
      $result =  array(
      "controller" => "user",
      "action" => "set_pricing",
      "ResponseCode" => false,
      "message" => "unable to update",
      "GetData" => $details
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function location_history_get()
  {
    $user_id = array('user_id'=>$this->get('user_id'));
    $locations = $this->User_model->selectdata("tbl_jobs","pickup_location,dropoff_location,pickup_lat,pickup_long,dropoff_lat,dropoff_long",$user_id,NULL,'id','desc',5);
    $address = $this->User_model->selectdata('tbl_users','home_address,work_address,home_address_farsi,work_address_farsi,home_latlng,work_latlng',array('id'=>$this->get('user_id')));
    // print_r($locations);die;
    if (count($locations)>0) {
      $result =  array(
      "controller" => "user",
      "action" => "location_history_get",
      "ResponseCode" => true,
      "message" => "History of locations",
      "GetData" => $locations,
      'address'=>$address,
      );
    } else {
      $result =  array(
      "controller" => "user",
      "action" => "location_history_get",
      "ResponseCode" => false,
      "message" => "No History found",
      'address'=>$address,
      );
    }
  $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function call_get()
  {
    $data = $this->input->get();
    // $data['free_line'] = rand(1111111111,9999999999);
    $data['free_line'] = 02122262055;
    $data['addedOn'] = date("Y-m-d H:i:s");
    $db_result = $this->User_model->getWhere('relation_id','tbl_call_relation',array('job_id'=>$data['job_id']),'1');
    // print_r($data);die;
    if (count($db_result)<1) {
      $insert = $this->User_model->insertdata('tbl_call_relation',$data);
    }
    $relation = $this->User_model->getWhere('*','tbl_call_relation',array('job_id'=>$data['job_id']),'1');
    $db_result2 = $this->User_model->selectdata('tbl_callingMode','callingMode');
    // print_r($db_result2);
    // die;
//     $CI = &get_instance();
// //setting the second parameter to TRUE (Boolean) the function will return the database object.
// $this->db2 = $CI->load->database('otherdb', TRUE);
// $qry = $this->db2->query("SELECT * FROM contact");
// print_r($qry->result());die;
    $callingMode = $db_result2[0]->callingMode;
    $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>true,
      'message'=>'relation created',
      'data'=>$relation,
      'callingMode'=>$callingMode
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function callingMode_post($data=null)
  {
    // $data1 = $this->input->get();
    if (empty($data)) {
      $data = array(
        'p_name'=>$this->input->post('p_name'),
        'd_name'=>$this->input->post('d_name'),
        'p_num'=>$this->input->post('p_num'),
        'd_num'=>$this->input->post('d_num'),
        'call_type'=>$this->input->post('call_type'),
        'TripNumber'=>$this->input->post('TripNumber')
        );
    }

    $job = $this->User_model->selectdata('tbl_jobs', 'status', array('id'=>$data['TripNumber']));
    $data['Type'] = $job[0]->status;
    // print_r($data);die;
    $query = $this->db->query('select * from tbl_callingMode')->result();
    $data['CallTime'] = $query[0]->CallTime;
    $data['BeepTime'] = $query[0]->BeepTime;
     // print_r($data);die;
      //print_r($data['p_name']);die;
    if($query[0]->callingMode =='secure'){
    if(empty($data['p_name'])|| empty($data['d_name']) || empty($data['p_num']) || empty($data['d_num'])){

       $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>false,
      'message'=>'Enter all fields',      
    );

    }

    elseif($data['call_type'] == 0){
      // print_r($data);die();
      $result = $this->User_model->insertContact($data);
      if($result ==1){
         $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>true,
      'message'=>'Value Inserted', 
      'free_line'    => $query[0]->number
    );}
         else{
           $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>false,
      'message'=>'Error Inserting data',      
    );
         }
      }

    
    // $data['free_line'] = rand(1111111111,9999999999);
//     $data['free_line'] = 02122262055;
//     $data['addedOn'] = date("Y-m-d H:i:s");
//     $db_result = $this->User_model->getWhere('relation_id','tbl_call_relation',array('job_id'=>$data['job_id']),'1');
//     // print_r($data);die;
//     if (count($db_result)<1) {
//       $insert = $this->User_model->insertdata('tbl_call_relation',$data);
//     }
//     $relation = $this->User_model->getWhere('*','tbl_call_relation',array('job_id'=>$data['job_id']),'1');
//     $db_result2 = $this->User_model->selectdata('tbl_callingMode','callingMode');
//     // print_r($db_result2);
//     // die;
//     $CI = &get_instance();
// //setting the second parameter to TRUE (Boolean) the function will return the database object.
// $this->db2 = $CI->load->database('otherdb', TRUE);
// $qry = $this->db2->query("SELECT * FROM contact");
// // print_r($qry->result());die;
//     $callingMode = $db_result2[0]->callingMode;
   elseif($data['call_type'] == 1){
       // print_r($data);die();
      $result = $this->User_model->insertType($data);

      if($result ==1){
         $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>true,
      'message'=>'Inserted',
      'free_line'=> $query[0]->number
    );}
         else{
           $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>false,
      'message'=>'Error Inserting data',      
    );
         }
      }
    }
   
    else{
    $result = array(
      'controller'=>'User',
      'action'=>'call',
      'ResponseCode'=>false,
      'message'=>'Normal Mode',
     
    );
}
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function traffic_get()
  {
    $db_result = $this->User_model->selectdata('tbl_tehran','*');

    $result = array(
      'controller'=>'User',
      'action'=>'traffic',
      'ResponseCode'=>true,
      'message'=>'Traffic Details:',
      'data'=>$db_result,
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function traffic_update_post()
  {
    $id = $this->input->post('id');
    $traffic_level = $this->input->post('traffic_level');
    $this->User_model->updatedata('tbl_tehran',array('id'=>$id),array('traffic_level'=>$traffic_level));
    $result = array(
      'controller'=>'User',
      'action'=>'traffic_update',
      'ResponseCode'=>true,
      'message'=>'Traffic Updated:'
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function add_location_post()
  {
    $data = $_POST;
    $this->User_model->insertdata('tbl_tehran',$data);
    $result = array(
      'controller'=>'User',
      'action'=>'add_location',
      'ResponseCode'=>true,
      'message'=>'Location added:'
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function delete_location_get()
  {
    $where = $this->input->get();
    $this->User_model->deleteWhere('tbl_tehran',$where);
    $result = array(
      'controller'=>'User',
      'action'=>'delete_location',
      'ResponseCode'=>true,
      'message'=>'Location deleted:'
    );
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function updatePassengerLatlng_post()
  {
    $user_id = $this->input->post('user_id');
    $data = array(
      'latitude'=>$this->input->post('latitude'),
      'longitude'=>$this->input->post('longitude'),
      );
    $this->User_model->updatedata('tbl_users',array('id'=>$user_id),$data);
    $respo = array('controller'=>'User',
      'action'=>'updatePassengerLatlng',
      'ResponseCode'=>true,
      'message'=>'Updated');
    $this->set_response($respo, REST_Controller::HTTP_OK);
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

  public function sendSms_post($data=NULL)
  {
   // error_reporting(0);  
   $sms_username = 'ar.naderi';
    $sms_password = 'sh8938411';
   $from_number = array(20008580);
   $to_number = array($_POST['phone']);

  $date=date("d/m/Y H:i"); //Date example
  list($day, $month, $year, $hour, $minute) = split('[/ :]', $date); 

  //The variables should be arranged according to your date format and so the separators
  $timestamp = mktime($hour, $minute, 0, $month, $day, $year);

   $sendDate = array($timestamp); 
   $message = array($_POST['message']);

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
        // return $results;
        print_r($results);
  }

  public function GetMessageStatus_post()
  {
     error_reporting(0);  
     $sms_username = 'ar.naderi';
    $sms_password = 'sh8938411';
     $MessageIDs = array($_POST[MessageIDs]);
     // print_r($_POST[username]);die;
          
          $client = new SoapClient("http://parsasms.com/webservice/v2.asmx?WSDL");
       
          $params = array(
            'username'  => $sms_username,
            'password'  => $sms_password,
            'MessageIDs' => $MessageIDs
          );
           
          $results = $client->GetMessageStatus( $params );

          print_r($results);

  }

  public function GetCredit_post()
  {
    $sms_username = 'ar.naderi';
    $sms_password = 'sh8938411';


          
          $client = new SoapClient("http://parsasms.com/webservice/v2.asmx?WSDL");
       
          $params = array(
            'username'  => $sms_username,
            'password'  => $sms_password
          );
           
          $results = $client->GetCredit( $params );

        //  if ($results->SendSMSResult == 'Success' )
        //    echo $success = 'ارسال پیام ها با موفقیت انجام شد';
        //  else 
        //     echo $success = $results->SendSMSResult;
          echo "Your Credit Is : ".$results->GetCreditResult;
  }

  public function jiring_get()
  {
    $StanVal=$this->User_model->selectdata('tbl_stan','stan',array('id'=>1));
    $Stan=$StanVal[0]->stan+1;
    $this->User_model->updatedata('tbl_stan',array('id'=>1),array('stan'=>$Stan));
    $input_xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
      <TokenRequest>
      <AdditionalInfo>Masirapp-payment</AdditionalInfo>
      <Amount>$_GET[Amount]</Amount>
      <MerchantId>3027</MerchantId>
      <MerchantPin>PLMk8(gh$657j</MerchantPin>
      <ServiceId>1</ServiceId>
      <UserMSISDN>$_GET[UserMSISDN]</UserMSISDN>
      <Stan>$Stan</Stan>
      </TokenRequest>";

      // print_r($input_xml);
      // die;
    $curl = curl_init();
    // curl_setopt($curl_handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
    curl_setopt_array($curl, array(
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_PORT => "8585",
      CURLOPT_URL => "https://mipg-core.jiring.ir:8585/IPG/TOKEN/REQUEST",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 300,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $input_xml,
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml",
        "postman-token: 62f5cb90-7072-dfd6-07c7-b0921be008b9"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    //setting the curl parameters.
    /*$url = "https://mipg-core.jiring.ir:8585/IPG/TOKEN/REQUEST";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // Following line is compulsary to add as it is:
    curl_setopt($ch, CURLOPT_POSTFIELDS,
                "xmlRequest=" . $input_xml);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    $response = curl_exec($ch);
    $err = curl_error($curl);
    curl_close($ch);*/

    if ($err) {
      echo "Request cURL Error #:" . $err;
    } else {
    $reqResponse = json_decode(json_encode(simplexml_load_string($response)));
    // print_r($reqResponse->TokenRRN);die;

    /*----INQUIRY-----*/
    if (isset($reqResponse->TokenRRN) && $reqResponse->TokenRRN != "") {
      $TokenRRN = $reqResponse->TokenRRN;

      $input_xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
      <TokenRequest>
      <MerchantId>3027</MerchantId>
      <MerchantPin>PLMk8(gh$657j</MerchantPin>
      <TokenRRN>$TokenRRN</TokenRRN>
      </TokenRequest>";

      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_PORT => "8585",
        CURLOPT_URL => "https://mipg-core.jiring.ir:8585/IPG/TOKEN/INQUIRY",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $input_xml,
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: text/xml",
          "postman-token: 62f5cb90-7072-dfd6-07c7-b0921be008b9"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        $inqResponse = simplexml_load_string($response);
      }
      
      /*----------------*/
    }
    $result = array(
      'controller'=>"User",
      'action'=>'jiring',
      'reqResponse'=>$reqResponse,
      'inqResponse'=>$inqResponse
      );
    // $responce->controller="User";
    // $responce->action="jiring";
    $this->set_response($result, REST_Controller::HTTP_OK);
    }
  }

  public function inquiry_get()
  {
     $input_xml = "<?xml version='1.0' encoding='UTF-8' standalone='yes'?>
      <TokenRequest>
      <MerchantId>3027</MerchantId>
      <MerchantPin>PLMk8(gh$657j</MerchantPin>
      <TokenRRN>$_GET[TokenRRN]</TokenRRN>
      </TokenRequest>";

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_PORT => "8585",
      CURLOPT_URL => "https://mipg-core.jiring.ir:8585/IPG/TOKEN/INQUIRY",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $input_xml,
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml",
        "postman-token: 62f5cb90-7072-dfd6-07c7-b0921be008b9"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response = simplexml_load_string($response);
      $this->set_response($response, REST_Controller::HTTP_OK);
    }
  }

  public function country_get($data)
  {
    $key = "AIzaSyDnT9r_aKKC5_isYcgkWKHhedGAh-Mv3-U";
    $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=simla+india&key=$key&language=ES';

    $result = file_get_contents($url);
    print_r($result);
    $result = json_decode($json);
  }

  public function places_get()
  {
    $key = "AIzaSyDnT9r_aKKC5_isYcgkWKHhedGAh-Mv3-U";
    $input = urlencode($this->input->get('input'));
    $language = $this->input->get('language');
    $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?key=$key&input=$input&components=country:irn&language=$language";
    // print_r($url);die;
    $response = file_get_contents($url);
    // print_r($response);die;
    $response = json_decode($response);
    $result = array(
      'controller'=>"User",
      'action'=>'places',
      'GetData'=>$response,
      'ResponseCode'=>true
    );
    // $responce->controller="User";
    // $responce->action="jiring";
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function checkPosition_get()
  {
    $result = $this->User_model->checkPosition($_GET);
    print_r($result);
  }

  public function test_get()
  {
    // print_r(date('w'));
    // $isExist = $this->db->query("SELECT * FROM `tbl_zone_setting` where now() between start_date and end_date")->row();
    // $data['user_id'] = $_GET[id];
    // $driver_share = $this->User_model->driverCommission($data['user_id']);
    $insert = $this->db->query("INSERT into test set time = NOW()");
    // $admin_share = 100-$driver_share;

    print_r(date("Y-m-d H:i:s"));

  }

/*===================== Driver Panel API Start ==================*/

  public function add_driver_post()
  {
    // error_reporting(E_ALL); ini_set('display_errors', 1);
  //------------------------ random password generator  
    // print_r($_POST);die;
    if (empty($_POST['phone'])) {
      $response = array('controller'=>'user',
        'action'=>'add_driver',
        'ResponseCode'=>false,
        'MessageWhatHappen'=>'Please provide phone number'
      );
      $this->set_response($response, REST_Controller::HTTP_OK);
      return false;
    }else{
      $checkPhone = $this->User_model->selectdata('tbl_users', 'id', array('phone'=>$_POST['phone']));
      // print_r($this->db->last_query());die;
      if (!empty($checkPhone)) {
        $response = array('controller'=>'user',
          'action'=>'add_driver',
          'ResponseCode'=>false,
          'MessageWhatHappen'=>'Phone number already registered'
        );
        $this->set_response($response, REST_Controller::HTTP_OK);
        return false;
      }
    }
    /*------- Apply Referral-----*/
    if (!empty($this->input->post('referral_code')))
    {
      $refData = array(
        'promo_code' => $this->input->post('referral_code')
      );
      $cd = $this->User_model->check_referral($refData);
      if (empty($cd)) {
        $response = array('controller'=>'user',
          'action'=>'add_driver',
          'ResponseCode'=>false,
          'GetData'=>$cd,
          'MessageWhatHappen'=>'Invalid Referral Code.'
        );
        $this->set_response($response, REST_Controller::HTTP_OK);
        return false;
      }
    }
    /*-------------------*/
    $chars_min = 6;
    $chars_max = 10;
    $length = rand($chars_min, $chars_max);
    $selection = 'aeuoyibcdfghjklmnpqrstvwxz1234567890QWERTYUIOPLKJHGFDSAZXCVBNM@#$';
    $password = "";
    for($i=0; $i<$length; $i++) {
        $current_letter = $selection ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
        $password .=  $current_letter;
    }

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
    
    $result = $this->User_model->add_driver($md5_pass, $profile_pic, $license_pic, $registration_pic, $insurance_pic, $vehicle_pic, $document, $registration_plate_pic);
    //---------- apply referral
    if (!empty($cd)) {
      $adrData = array(
        'user_id'=>$result,
        'promo_code' => $this->input->post('referral_code')
      );
      $adr=$this->User_model->apply_driver_ref($adrData);
    }
    //------------ Add details to database
    $emergencyContact = array(
      'user_id' => $result,
      'first_name' => $this->input->post('first_name'),
      'last_name' => $this->input->post('last_name'),
      'cell' => $this->input->post('cell'),
      'home_phone' => $this->input->post('home_phone'),
      'date_created' => date("Y-m-d H:i:s"),
    );
    $this->User_model->insertdata('tbl_emergencyContact', $emergencyContact);
    //----------------Commission
    if (!empty($_POST['commission'])) {
      $date_modified = date("Y-m-d H:i:s");
      $commissionData = array(
        'driver_id'=>$result,
        'commission'=>$_POST['commission'],
        'commission_from'=>$_POST['commission_from'],
        'commission_to'=>$_POST['commission_to'],
        'forever'=>$_POST['forever'],
        'date_modified'=> $date_modified
      );
      $this->User_model->insertdata('tbl_commission',$commissionData);
      if (!empty($_POST['dflt_commission'])) {
        $date_modified = date("Y-m-d H:i:s");
        $date = new DateTime($_POST['commission_to']);
        $date->modify('+1 day');
        $fromDate = $date->format('Y-m-d');
        $commissionData = array(
          'driver_id'=>$result,
          'commission'=>$_POST['dflt_commission'],
          'commission_from'=>$fromDate,
          'forever'=>1,
          'date_modified'=> $date_modified
        );
        $this->User_model->insertdata('tbl_commission',$commissionData);
      }
    }
    /*otp*/
    $otp_data['phone']=$this->input->post('phone');
    $otp_data['message']='your Password is: '.$password.' \r\n Please keep it confidential.';
    $otpResult = $this->aberSendSms($otp_data);
    if ($otpResult==500) {
      $response = array(
        "controller" => "user",
        "action" => "aberSendSms",
        "Errorcode" => "200",
        "ResponseCode" => false,
        "MessageWhatHappen" => "Sorry... our service is down",
        'isExist' => $isExist
      );
      $this->set_response($response, REST_Controller::HTTP_OK);
      return false;
    }
    //-------------------------------------- email verification 

      // print_r($result);die();   
    $id1 = $result;
     
    $static_key = "afvsdsdjkldfoiuy4uiskahkhsajbjksasdasdgf43gdsddsf";
    $ids = $id1."_".$static_key;
    $b_id = base64_encode($ids);
    $url = base_url('verification')."/?id=".$b_id;

    $email = $this->input->post('email');
    
    $response = array('controller'=>'user',
      'action'=>'add_driver',
      'ResponseCode'=>true,
      'driver_id'=>$result,
      'MessageWhatHappen'=>'Driver Added Successfully'
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function upload_files_post()
  {
    // print_r($_FILES['document']);die;
    $user_id = $_POST['user_id'];
    if (isset($_FILES['profile_pic'])) {
      $upload_path = "public/profilePic/";
      $image = "profile_pic";
      $profile_pic = $this->do_upload($upload_path, $image);

      $where = array('id'=>$user_id);
      $table_name = 'tbl_users';
      $data = array('profile_pic'=>$profile_pic);
    }

    if (isset($_FILES['document'])) {
      $upload_path = "document/";
      $image = "document";
      $document = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('document'=>$document);
    }

    if (isset($_FILES['vehicle_pic'])) {
      $upload_path = "vehicle_pic/";
      $image = "vehicle_pic";
      $vehicle_pic = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('vehicle_image '=>$vehicle_pic);
    }

    if (isset($_FILES['license_pic'])) {
      $upload_path = "license_pic/";
      $image = "license_pic";
      $license_pic = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('driver_license_image'=>$license_pic);
    }

    if (isset($_FILES['insurance_pic'])) {
      $upload_path = "insurance_pic/";
      $image = "insurance_pic";
      $insurance_pic = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('vehicle_insurance_image '=>$insurance_pic);
    }

    if (isset($_FILES['registration_pic'])) {
      $upload_path = "registration_pic/";
      $image = "registration_pic";
      $registration_pic = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('vehicle_registration_image'=>$registration_pic);
    }

    if (isset($_FILES['registration_plate_pic'])) {
      $upload_path = "registration_plate_pic/";
      $image = "registration_plate_pic";
      $registration_plate_pic = $this->do_upload($upload_path, $image);

      $where = array('user_id'=>$user_id);
      $table_name = 'tbl_driver_documents';
      $data = array('vehicle_registration_plate_image'=>$registration_plate_pic);
    }
    $this->db->set($data);
    $this->db->where($where);
    $this->db->update($table_name);
    $response = array('controller'=>'user',
      'action'=>'upload_files_post',
      'ResponseCode'=>true,
      'MessageWhatHappen'=>'File Uploaded Successfully'
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function do_upload($upload_path, $image)
  {
    $this->load->library('upload');
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

  public function expirationDates_post()
  {
      /*Insurance-Expiration-Date
      */
    if ( !empty($this->input->post('driver_id')) ) {

      $id = $this->input->post('driver_id');

      if ( !empty($_POST['permit_start']) && !empty($_POST['permit_exp']) ) {

        $havePermit = $this->Admin_model->selectWhere('tbl_permit','id',array('driver_id'=>$id));

        $permit_data = array(
          'driver_id'=>$id,
          'from'=>$_POST['permit_start'],
          'to'=>$_POST['permit_exp'],
          'addedOn'=>date("Y-m-d H:i:s"),
        );
        if (!empty($havePermit)) {
        $this->Admin_model->updateWhere('tbl_permit',array('driver_id'=>$id),$permit_data);
        }else{
          $this->Admin_model->insert('tbl_permit',$permit_data);
        }
      }

      if (!empty($_POST['driver_license_exp_date'])) {
        $this->Admin_model->updateWhere('tbl_driver_documents',array('user_id'=>$id),array('driver_license_exp_date'=>$_POST['driver_license_exp_date']));
      }
      if (!empty($_POST['contract_exp_date'])) {
        $this->Admin_model->updateWhere('tbl_driver_documents',array('user_id'=>$id),array('contract_exp_date'=>$_POST['contract_exp_date']));
      }

      if (!empty($_POST['insurance_exp_date'])) {
        $this->Admin_model->updateWhere('tbl_driver_vehicle_info',array('user_id'=>$id),array('insurance_exp_date'=>$_POST['insurance_exp_date']));
      }
      $result = $this->Admin_model->driverInfo($id);
      $response = array('controller'=>'UserNew',
        'action'=>'expirationDates_post',
        'ResponseCode'=>true,
        'MessageWhatHappen'=>'Updated',
        'UserData'=>$_POST,
        'Result'=>$result);
    } else {
      $response = array('controller'=>'UserNew',
          'action'=>'expirationDates_post',
          'ResponseCode'=>false,
          'MessageWhatHappen'=>'driver_id is requiered',
          'UserData'=>$_POST);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function smogScenario_post()
  {
    $table_name = 'tbl_zone_setting';
    if ( !empty($_POST['scenario']) && !empty($_POST['start_date']) && !empty($_POST['end_date']) ) {
      $isExist = $this->Admin_model->get_where($table_name,array('id'=>1));
      $zoneData = array('scenario'=>$_POST['scenario'],'start_date'=>$_POST['start_date'],'end_date'=>$_POST['end_date'],'date_modified'=>date("Y-m-d H:i:s"));
      if (empty($isExist)) {
        $this->Admin_model->insert($table_name,$zoneData);
      }else{
        $this->Admin_model->update($table_name,1,$zoneData);
      }
      $isExist = $this->Admin_model->get_where($table_name,array('id'=>1));
      $response = array('controller'=>'UserNew',
        'action'=>'smogScenario_post',
        'ResponseCode'=>true,
        'MessageWhatHappen'=>'Updated',
        'UserData'=>$_POST,
        'Result'=>$isExist);
    }else{
      $response = array('controller'=>'UserNew',
        'action'=>'smogScenario_post',
        'ResponseCode'=>false,
        'MessageWhatHappen'=>'scenario, start_date, end_date requiered',
        'UserData'=>$_POST);
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }
/*===================== Driver Panel API End ==================*/

  public function add_money_post()
  {
    $job_id = 0;
    $payment_type = 2;
    $payment_method = 1;
    $user_id = $this->input->post('user_id');
    $payment_status = $this->input->post('payment_status');
    $payment_RefID = $this->input->post('payment_RefID');
    $amount = $this->input->post('amount');
    $date_created = date("Y-m-d H:i:s");

    if (empty($user_id)||empty($payment_status)||empty($payment_RefID)||empty($amount)) {
      $response = array(
        'controller' => "User",
        'action' => "add_money",
        'ResponseCode' => false,
        'MessageWhatHappen' => 'All fields are Required'
      );
    } else {
      if ($payment_status==100) {
        $this->User_model->add_wallet('tbl_users', $user_id, $amount);
      }
      /*wallet balance*/
      $where = array('id' => $user_id, );
      $wallet_balance = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
      $walletBalance  = $wallet_balance[0]->wallet_balance;
      // $infos = round($walletBalance/500)*500;
      $infos = round($walletBalance);
      /*-------------*/
      $insert_data = array(
        'job_id'=>$job_id,
        'payment_type'=>$payment_type,
        'payment_method' => $payment_method,
        'user_id' => $user_id,
        'payment_status' => $payment_status,
        'payment_RefID' => $payment_RefID,
        'amount'=>$amount,
        'wallet_balance'=>$infos,
        'date_created'=>$date_created
      );
      $this->User_model->insertdata("tbl_payment",$insert_data);

      $response = array(
        'controller' => "User",
        'action' => "add_money",
        'ResponseCode' => true,
        'wallet_balance' => $infos
      );
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function APwallet_post()
  {
    $hrespData = array();
    $hrespData['user_id'] = $this->post('user_id');
    $hrespData['job_id'] = $this->post('job_id');
    $hrespData['createdOn'] = date("Y-m-d H:i:s");
    $hresp1 = json_decode( $this->post('hresp'));
    $hresp = json_decode($hresp1->hresp);
    foreach ($hresp as $key => $value) {
      // echo "$key";
      $hrespData[$key] = $value;
    }
    // print_r($hrespData);
    // die;
    $this->User_model->insertdata('tbl_APwallet',$hrespData);
    $response = array(
      'controller' => "User",
      'action' => "APwalletAdd_post",
      'ResponseCode' => true,
      'UserData'=>$hrespData
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function issue_post()
  {
    $user_id = $this->input->post('user_id');
    $device_id = $this->input->post('device_id');
    $os = $this->input->post('os');
    $token_id = $this->input->post('token_id');
    $unique_device_id = $this->input->post('unique_device_id');
    $issue = $this->input->post('issue');

    // $recieverEmailId = "info@aber.ir";
    $recieverEmailId = "osvinandroid@gmail.com";
    $body = "<!DOCTYPE html>
      <head>
        <meta content=text/html; charset=utf-8 http-equiv=Content-Type /> 
        <title>Customer Issue</title>
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
                  <th style=font-size:20px; font-weight:bolder; text-align:right;padding-bottom:10px;border-bottom:solid 1px #ddd;> Hello Admin</th>
                </tr>
                <tr>
                  <td style=font-size:16px;>
                    <p> Client Issue is:.</p>
                    <p>".$issue."</p>
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

      // $this->email->set_newline("\r\n");
      // $this->email->from('billing@aber.ir');
      // $this->email->to($recieverEmailId);
      // $this->email->subject('Issue');
      // $this->email->message($body);
      // $this->email->send();
      /*$this->load->library('email');
       $this->email->initialize(array(
                    'protocol' => 'smtp',
                    'smtp_host' => 'smtp.sendgrid.net',
                    'smtp_user' => 'wefinance',
                    'smtp_pass' => 'admin@1@1',
                    'smtp_port' => '587',
                    'SMTPDebug' => 2,
                ));
      $this->email->set_newline("\r\n");

      $this->email->from('billing@aber.ir', 'Aber');

      $this->email->to($recieverEmailId);

      $this->email->subject('Issue');

      $this->email->message($body);

      // $this->email->send();
      if(!$this->email->send()){

      echo $this->email->print_debugger(); die;
      }*/
     /*$headers = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type:text/html;charset=UTF-8' . "\r\n";
      $from = "billing@aber.ir";
    //  $to = "osvinandroid@gmail.com";
      $subject = 'Issue';
      $message = $body;
      $headers .= 'From: '.$from. "\r\n"."X-Mailer: PHP/" . phpversion();
        mail($recieverEmailId,$subject,$message,$headers);*/
    $response = array(
      'controller' => "User",
      'action' => "issue",
      'ResponseCode' => true,
      'MessageWhatHappen' => 'Issue Submitted'
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function emergencyContact_post()
  {
    $input = array(
      'user_id' => $this->input->post('user_id'),
      'first_name' => $this->input->post('first_name'),
      'last_name' => $this->input->post('last_name'),
      'cell' => $this->input->post('cell'),
      'home_phone' => $this->input->post('home_phone'),
      'date_created' => date("Y-m-d H:i:s"),
    );
    if (empty($input['user_id'])||empty($input['cell'])) {
      $response = array(
        'controller' => "User",
        'action' => "emergencyContact_post",
        'ResponseCode' => false,
        'MessageWhatHappen' => 'Driver id and cell is required'
      );
    } else {
      $this->User_model->insertdata('tbl_emergencyContact', $input);
      $response = array(
        'controller' => "User",
        'action' => "emergencyContact_post",
        'ResponseCode' => true,
        'MessageWhatHappen' => 'Emergency Contact Added'
      );
    }
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function userDetail_get()
  {
    $inputs = array('user_type' => $this->input->get('user_type'),
    'phone' => $this->input->get('phone'),
    'first_name' => $this->input->get('first_name'),
    'last_name' => $this->input->get('last_name'),
    'start_date' => $this->input->get('start_date'),
    'end_date' => $this->input->get('end_date'),
    'limit' => $this->input->get('limit'),
    'offset' => ($this->input->get('offset')=="")?0:$this->input->get('offset')
    );
    // print_r($inputs);die;
    $result = $this->User_model->userDetail($inputs);
    $response = array(
      'controller' => "User",
      'action' => "userDetail",
      'ResponseCode' => true,
      'results' => $result
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function aberSendSms($data)
  {
    $message = $data['message'];
    $phone = $data['phone'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "3033",
      CURLOPT_URL => "http://192.168.70.90:3033/aber.asmx",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\n  <soap:Body>\n    <SendSMS xmlns=\"http://tempuri.org/\">\n      <username>naeb7Q7geirn</username>\n      <password>^_GhG_!_PpP_^</password>\n      <lineNumber>5000530303</lineNumber>\n      <message>$message</message>\n      <destinationNumber>$phone</destinationNumber>\n    </SendSMS>\n  </soap:Body>\n</soap:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml; charset=utf-8"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);

   curl_close($curl);
   // print_r($err);
   // die();
 // $info = curl_getinfo($response);
 // print($info);die('here');
    if ($err) {
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('192.168.70.90',0,'$err','curl',$phone,now())");
      $response2 = $this->aberSendSms2($data);
      if ($response2==500) {
        $response3 = $this->aberSendSmsSoap_get($data);
        if ($response3==500) {
          return 500;
        } else {
          return $response3;
        }
      } elseif (!empty($response2)) {
        return $response2;
      }else{
        return 500;
      }
    } else {
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('192.168.70.90',1,'$response','curl',$phone,now())");
      return $response;
    }
  }

  public function aberSendSms2($data)
  {
    $message = $data['message'];
    $phone = $data['phone'];
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://phphosting.osvin.net/aberbackusa/admin/api/user/aberSendSms?phone=$phone&message=$message",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "GET",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('173.248.157.82',0,'$err','curl',$phone,now())");
      return 500;
    } else {
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('173.248.157.82',1,'$response','curl',$phone,now())");
      return $response;
    }
  }

  public function aberSendSmsSoap_get($data=null)
  {
    if (!empty($data)) {
      $message = $data['message'];
      $phone = $data['phone'];
    }else{
      $message = $this->input->get('message');
      $phone = $this->input->get('phone');
    }
    libxml_disable_entity_loader(false);
    use_soap_error_handler(false);
    try {
      $client = new SoapClient('http://192.168.70.90:3033/aber.asmx?WSDL', array("trace" => 1, "exception" => true));
      $params = array();
      $params['username'] = "naeb7Q7geirn";
      $params['password'] = "^_GhG_!_PpP_^";
      $params['lineNumber'] = "5000530303";
      $params['message'] = $message;
      $params['destinationNumber'] = $phone;

      $response = $client->SendSMS($params);
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('192.168.70.90',1,'$response->SendSMSResult','soap',$phone,now())");
    } catch ( SoapFault $e ) { // Do NOT try and catch "Exception" here
      $err = addslashes($e->faultstring);
      $this->db->query("INSERT INTO `otpTest`(`server`, `result`, `response`, `method`, `phone`, `addedOn`) VALUES ('192.168.70.90',0,'$err','soap',$phone,now())");
      header('HTTP/1.0 200 OK');
      $response = 500;
    }
    // print_r($response);
    return $response;
  }

  public function cron_get()
  {
    // $this->db->query("INSERT INTO test set time=now()");
    $busy_foult = $this->User_model->busy_foult();
    if (count($busy_foult)>0) {
            foreach ($busy_foult as $keyvalue) {
              $data = array('user_id' => $keyvalue->id, 'job_id' => $keyvalue->pjob_id, 'resp' => 2);
              // print_r($keyvalue->pjob_id);die;
              $this->respond_get($data);
        }
    }
    // echo "<pre>"; 
    // print_r($busy_foult);
    // print_r($json);
    // print_r($response);
    // echo "</pre>";
    // redirect();
    // $this->User_model->autoDeactivate();
    $arriving = $this->User_model->set_arriving();

    $response = array(
      'controller' => "User",
      'action' => "cron",
      'ResponseCode' => true,
      'data'=>$busy_foult,
      'results' => "This is cron"
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function aberSendSms_get($data=null)
  {
    if (!empty($data)) {
      $message = $data['message'];
      $phone = $data['phone'];
    }else{
      $message = $this->input->get('message');
      $phone = $this->input->get('phone');
    }

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "3033",
      CURLOPT_URL => "http://192.168.70.90:3033/aber.asmx",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 20,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\n  <soap:Body>\n    <SendSMS xmlns=\"http://tempuri.org/\">\n      <username>naeb7Q7geirn</username>\n      <password>^_GhG_!_PpP_^</password>\n      <lineNumber>5000530303</lineNumber>\n      <message>$message</message>\n      <destinationNumber>$phone</destinationNumber>\n    </SendSMS>\n  </soap:Body>\n</soap:Envelope>",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/xml; charset=utf-8"
      ),
    ));
    
    $response = curl_exec($curl);
    $err = curl_error($curl);

   curl_close($curl);
   // print_r($err);
   // die();
 // $info = curl_getinfo($response);
 // print($info);die('here');
    if ($err) {
      $response2 = $this->aberSendSms2($data);
      if ($response2==500) {
        $response3 = $this->aberSendSmsSoap_get($data);
        if ($response3==500) {
          echo 500;
        } else {
          print_r($response3);
        }
      } elseif (!empty($response2)) {
        print_r($response2);
      }else{
        echo 500;
      }
    } else {
      print_r($response);
    }
  }

/*  public function aberSendSmsLocal_post()
  {
    $message = $_POST['message'];
    $phone = $_POST['phone'];
    $client = new SoapClient('http://192.168.70.90:3033/aber.asmx?WSDL', array("trace" => 1, "exception" => 0));

    $params = array();
    $params['username'] = "naeb7Q7geirn";
    $params['password'] = "^_GhG_!_PpP_^";
    $params['lineNumber'] = "5000530303";
    $params['message'] = $message;
    $params['destinationNumber'] = $phone;

    $response = $client->SendSMS($params);
    echo(json_encode($response));
  }

  public function aberSendSms_get()
  {
    $message = "hello";
    $phone = 12345;
    // libxml_disable_entity_loader(false);
    use_soap_error_handler(true);
    try {
      $client = new SoapClient('http://192.168.70.90:3033/aber.asmx?WSDL', array("trace" => 1, "exception" => true));
    } catch ( SoapFault $e ) { // Do NOT try and catch "Exception" here
      $faultcode = $e->faultcode;
      $this->db->query("INSERT INTO pushtest set addedOn=now(),server='aber',result=0,faultcode='$faultcode' ");
      echo "sorry";
    }
    $params = array();
    $params['username'] = "naeb7Q7geirn";
    $params['password'] = "^_GhG_!_PpP_^";
    $params['lineNumber'] = "5000530303";
    $params['message'] = $message;
    $params['destinationNumber'] = $phone;

    $response = $client->SendSMS($params);
      $this->db->query("INSERT INTO pushtest set addedOn=now(),server='aber',result=1 ");

    print_r($response);
  }

  public function aberSend_get()
  {
    $message = "hello";
    $phone = 12345;
    // libxml_disable_entity_loader(false);
    use_soap_error_handler(true);
    try {
      $client = new SoapClient('http://192.168.70.90:3033/aber.asmfdgx?WSDL', array("trace" => 1, "exception" => true));
    } catch ( SoapFault $e ) { // Do NOT try and catch "Exception" here
      // $this->db->query("INSERT INTO pushtest set addedOn=now(),server='aber',result=0 ");
    trigger_error("SOAP Fault: (faultcode: {$e->faultcode}, faultstring: {$e->faultstring})", E_USER_ERROR);
      print_r($e->faultcode);
    }
    $params = array();
    $params['username'] = "naeb7Q7geirn";
    $params['password'] = "^_GhG_!_PpP_^";
    $params['lineNumber'] = "5000530303";
    $params['message'] = $message;
    $params['destinationNumber'] = $phone;

    $response = $client->SendSMS($params);
      // $this->db->query("INSERT INTO pushtest set addedOn=now(),server='aber',result=1 ");

    print_r($response);
  }

  */

  public function driverWallet_get()
  {
    
  }

  public function pushTestios_post()
  {
    $token_id = $this->input->post('token_id');
    $alert = $this->input->post('alert');
    $message = "pushTest";
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_URL => "http://phphosting.osvin.net/aberbackusa/admin/api/user/iosPush",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"token_id\"\r\n\r\n$token_id\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n$message\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sound\"\r\n\r\ndefault\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"alert\"\r\n\r\n$alert\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
      ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      // echo "cURL Error #:" . $err;
      $result = array(
        'controller' => "pushTest",
        'action' => "test",
        'ResponseCode' => false,
        'results' => $err
      );
    } else {
      $result = array(
        'controller' => "pushTest",
        'action' => "test",
        'ResponseCode' => true,
        'results' => $response
      );
    }
      $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function sendSms_get()
  {
    error_reporting(E_ALL); ini_set('display_errors', 1);
    $phone = $_GET['phone'];
    $text = $_GET['text'];
    $this->load->helper('sms_helper');
    $id = _sms($phone,$text);
    print_r($id);
  }

  public function resetUserData_post()
  {
    if (!empty($_POST['user_id'])) {
      $ongoing = $this->User_model->selectdata('tbl_jobs', 'id', array('user_id'=>$_POST['user_id'],'is_active'=>1));
      if (empty($ongoing)) {
        // echo "string";
        $this->User_model->updatedata('tbl_promocode',array('promocode_beneficiary_id'=>$_POST['user_id'],'status'=>2), array('status'=>0));
        $result = array(
          'controller' => "User",
          'action' => "resetUserData_post",
          'ResponseCode' => true,
          'MessageWhatHappen' => 'promo unselected'
        );
      }else{
        $result = array(
          'controller' => "User",
          'action' => "resetUserData_post",
          'ResponseCode' => false,
          'MessageWhatHappen' => 'Ongoing Ride',
          'result' => $ongoing[0]->id
        );
      }
      // print_r($ongoing);die;
    }else{
      $result = array(
        'controller' => "User",
        'action' => "resetUserData_post",
        'ResponseCode' => false,
        'results' => 'user id required'
      );
    }
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function stack_get()
  {
    echo '<a href="http://stackexchange.com/users/8987020/rohit-dhiman"><img src="http://stackexchange.com/users/flair/8987020.png" width="208" height="58" alt="profile for Rohit Dhiman on Stack Exchange, a network of free, community-driven Q&amp;A sites" title="profile for Rohit Dhiman on Stack Exchange, a network of free, community-driven Q&amp;A sites" /></a>';
  }

  public function commissions_get()
  {
    $result = $this->User_model->selectdata('tbl_commissionLevels','*');
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function setDriverStatus_post()
  {
    $where = array('id' => $_POST['driver_id'],'user_type'=>2);
    $data = array('status'=>$_POST['status']);
    $this->User_model->updatedata('tbl_users', $where, $data);
    $response = array(
      'controller' => "User",
      'action' => "setDriverStatus_post",
      'ResponseCode' => true,
      'results' => 'status updated',
      'driver_id' => $_POST['driver_id']
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function feedback_post()
  {
    $data = array(
      'user_id' => $_POST['user_id'],
      'feedback' => $_POST['feedback'],
      'createdOn' => date("Y-m-d H:i:s"),
    );
    $this->User_model->insertdata('tbl_feedback', $data);
    $response = array(
      'controller' => "User",
      'action' => "feedback_post",
      'ResponseCode' => true,
      'results' => 'Feeddback submitted.',
    );
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

  public function otp_get()
  {
    $result = $this->User_model->selectdata('tbl_otp','*',array('phoneNumber'=>$_GET['phoneNumber']));
    $this->set_response($result, REST_Controller::HTTP_OK);
  }

  public function smsLog_get()
  {
    $result = $this->User_model->selectdata('otpTest','*','','','id','desc');
    echo "<pre>";
    print_r($result);
    echo "</pre>";
  }

  public function pmailer_get()
  {
    error_reporting(E_ALL);
      ini_set('display_errors',1);
    $this->load->library('email');

    $mail = new PHPMailer();
    $mail->IsSMTP();          
   /* $mail->Host = 'smtp.sendgrid.net';                 
    $mail->Port = 587;   
    $mail->Username = 'wefinance'; 
    $mail->Password = 'admin@1@1'; */              
    $mail->Host = '91.232.66.24';                 
    // Specify main and backup server
    $mail->Port = 25;                                    
    // Set the SMTP port
    $mail->Username = 'billing@aber.ir';                
    // SMTP username
    $mail->Password = 'Bhmd#25736';                  
    // SMTP password
    $mail->SMTPAuth = true;                               
    // Enable SMTP authentication
    $mail->SMTPSecure = 'tls';                            
    // Enable encryption, 'ssl' also accepted
    $mail->SMTPDebug = 2;

    $mail->From = 'billing@aber.ir';
    $mail->FromName = 'Whatever';
    $mail->AddAddress('osvinandroid@gmail.com', 'Josh Adams');  
    // Add a recipient              // Name is optional

    $mail->IsHTML(false);                                  
    // Set email format to HTML

    $mail->Subject = 'Here is the subject';
    $mail->Body    = 'This is the HTML message body <strong>in bold!</strong>';
    $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if(!$mail->Send()) {
       echo 'Message could not be sent.';
       echo 'Mailer Error: ' . $mail->ErrorInfo;
       exit;
    }
  }

  public function driverStatestics_get()
  {
    // error_reporting(E_ALL);
    $driver_id = $this->input->get('driver_id');
    $UserData=array();
    if (!empty($driver_id)) {
      $checkId = $this->User_model->selectdata('tbl_users','rating',array('id'=>$driver_id,'user_type'=>2));
      if (!empty($checkId)) {
        //====Trips Declined By Driver
        $decInfo = $this->User_model->selectdata('tbl_response','count(*) as declined',array('driver_id'=>$driver_id));
        $tripsDeclined = $decInfo[0]->declined;

        //====The percentage of successful trips
        $allTrips = $this->User_model->selectdata('tbl_jobs','count(*) as acceptedTrips',array('driver_id'=>$driver_id));
        $acceptedTrips = $allTrips[0]->acceptedTrips;
        $totalTrips = $tripsDeclined+$acceptedTrips;
        $completedTrips = $this->User_model->selectdata('tbl_jobs','count(*) as successfullTrips',array('driver_id'=>$driver_id,'status'=>4));
        $successfullTrips = $completedTrips[0]->successfullTrips;
        // print_r(round(($successfullTrips/$acceptedTrips)*100));
        $totalTripsVsCompletedTrips = ($successfullTrips/$totalTrips)*100;
        $acceptedVsCompleted = ($successfullTrips/$acceptedTrips)*100;

        //====Hour comprises Spent Online
        $query = "SELECT SUM(TIMESTAMPDIFF(MINUTE,`date_created`,case when last_logout < `date_created` then now() ELSE last_logout end)) as timeSpent FROM `tbl_login` where user_id=$driver_id";
        $timeSpent = $this->User_model->select_by_query($query);
        $totalMinuteSpent = $timeSpent[0]->timeSpent;
        $totalHoursSpent=floor($totalMinuteSpent/60);

        //====Travellers scores
        $averageRating = $checkId[0]->rating;

        $UserData = array(
          'totalTrips'=>$totalTrips,
          'tripsDeclined'=>$tripsDeclined,
          'acceptedTrips'=>$acceptedTrips,
          'successfullTrips'=>$successfullTrips,
          'totalTripsVsCompletedTrips'=>$totalTripsVsCompletedTrips,
          'acceptedVsCompleted'=>$acceptedVsCompleted,
          'totalMinuteSpent'=>$totalMinuteSpent,
          'totalHoursSpent'=>$totalHoursSpent,
          'averageRating'=>$averageRating
        );

        $results = array(
          'controller'=>'User',
          'action'=>'driverStatestics',
          'ResponseCode'=>true,
          'UserData'=>$UserData
        );
      } else {
        $results = array(
          'controller'=>'User',
          'action'=>'driverStatestics',
          'ResponseCode'=>false,
          'MessageWhatHappen'=>'Invalid driver_id.'
        );
      }
      
    } else {
      $results = array(
        'controller'=>'User',
        'action'=>'driverStatestics',
        'ResponseCode'=>false,
        'MessageWhatHappen'=>'Please provide driver_id.'
      );
    }
    $this->set_response($results,REST_Controller::HTTP_OK);
  }

  function iosPush_post($param=null)
  { 
    if ($param==null) {
      $param=$_POST;
      $method=1;
      // echo "string";
    }else{
      $method=0;
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
      CURLOPT_URL => "http://phphosting.osvin.net/aberbackusa/admin/api/User/iosPush",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 10,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"token_id\"\r\n\r\n$param[token_id]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n$param[message]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sound\"\r\n\r\n$param[sound]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"action\"\r\n\r\n$param[action]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"job_id\"\r\n\r\n$param[job_id]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"ride_details\"\r\n\r\n$param[ride_details]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"id\"\r\n\r\n$param[id]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"name\"\r\n\r\n$param[name]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"profile_pic\"\r\n\r\n$param[profile_pic]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"datetime\"\r\n\r\n$param[datetime]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"fare\"\r\n\r\n$param[fare]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
        "postman-token: 54868a64-6243-9183-e0fa-4d41b72c92e5"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    // $param['addedOn']=date("Y-m-d H:i:s");
    // $param['response']=$response;
    // $param['err']=$err;
    // $this->User_model->insertdata('pushtest', $param);

    if ($method==1) {
      if ($err) {
        echo "cURL Error #:" . $err;
      } else {
        echo $response;
      }
    }

  }

  public function AP_get()
  {
    $config = array(
        "digest_alg" => "sha256",
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    );
    $res = openssl_pkey_new($config);

    // Get private key
    openssl_pkey_export($res, $privkey);

    // Get public key
    $pubkey = openssl_pkey_get_details($res);
    $pubkey = $pubkey["key"];
    var_dump($privkey);
    var_dump($pubkey);

    // get some text from command line to work with
    // $tocrypt = trim(fgets(STDIN));
    $tocrypt = '{"ao":10000,"hi":501,"htran":2014,"mo":"09015466577","walet":"2001","hop":244,"htime":"1487767459","hkey":"5cob6i1m562pkjguv04j0j8i9d"}';

    // some variables to work with
    $encryptedviaprivatekey = ""; //holds text encrypted with the private key
    $decryptedviapublickey = ""; // holds text which was decrypted by the public key after being encrypted with the private key, should be same as $tocrypt
    $encryptedviapublickey = ""; // holds text that was encrypted with the public key
    $decryptedviaprivatekey = ""; // holds text that was decrypted with the private key after being encrypted with the public key, should be the same as $tocrypt

    openssl_private_encrypt($tocrypt, $encryptedviaprivatekey, $privkey);
    echo $tocrypt . "->" . $encryptedviaprivatekey;
    echo "\n\n";
    openssl_public_decrypt($encryptedviaprivatekey, $decryptedviapublickey, $pubkey);
    echo $encryptedviaprivatekey . "->" . $decryptedviapublickey;
    echo "\n\n";

    openssl_public_encrypt($tocrypt,$encryptedviapublickey, $pubkey);
    echo $tocrypt . "->" . $encryptedviapublickey;
    echo "\n\n";
    openssl_private_decrypt($encryptedviapublickey, $decryptedviaprivatekey, $privkey);
    echo $encryptedviapublickey . "->" . $decryptedviaprivatekey;

    //

    $binary_signature = "";

    // At least with PHP 5.2.2 / OpenSSL 0.9.8b (Fedora 7)
    // there seems to be no need to call openssl_get_privatekey or similar.
    // Just pass the key as defined above
    openssl_sign($tocrypt, $binary_signature, $privkey, OPENSSL_ALGO_SHA1);
    $ok = openssl_verify($tocrypt, $binary_signature, $pubkey, OPENSSL_ALGO_SHA1);
    echo "check #1: ";
    if ($ok == 1) {
        echo "signature ok (as it should be)\n";
    } elseif ($ok == 0) {
        echo "bad (there's something wrong)\n";
    } else {
        echo "ugly, error checking signature\n";
    }

    $ok = openssl_verify('tampered'.$tocrypt, $binary_signature, $pubkey, OPENSSL_ALGO_SHA1);
    echo "check #2: ";
    if ($ok == 1) {
        echo "ERROR: Data has been tampered, but signature is still valid! Argh!\n";
    } elseif ($ok == 0) {
        echo "bad signature (as it should be, since data has beent tampered)\n";
    } else {
        echo "ugly, error checking signature\n";
    }
  }

  public function APwallet_get()
  {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "7002",
      CURLOPT_URL => "https://192.168.70.101:7002/wal/w10/501/1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{\"hsign\":\"1#1#kla+2B7skSydMSNSvaicEQtPRtPXJPEaYaUb2rcZrmDuzoEXtcdhCHlykdsNHOr6RoKGZq2kdxS0Sm37WpsBhuJStdiXDcrMg3qeK+W8fh9NtNx5wQJuqZpSHA15dijOcwS58F4PKili9OV+fq7dAHEMycSW5S9uQ8QIR50n7nr4GAoGjKUl7llNS+exGwhnsGYKHzy6ihi8D4UUw\\/b1sxxsMe+mFf7k0hm6AsuRfyLUsgNg8KE+xUiGwr4LAAUGKk7fFndCcQdgm9fnN7WqSZuzGec542KSW6CdWGN\\/S+K5QxY+aL09SJSEvPHexD1RBqHzqIDsFbfMSrAKbvn41w==\",\"hreq\":\"{\\\"ao\\\":10000,\\\"hi\\\":501,\\\"htran\\\":2001,\\\"mo\\\":\\\"09015466577\\\",\\\"walet\\\":\\\"2001\\\",\\\"hop\\\":310,\\\"htime\\\":\\\"1488279547\\\",\\\"hkey\\\":\\\"5cob6i1m562pkjguv04j0j8i9d\\\"}\",\"ver\":\"1.9.0\"}",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/plain",
        "postman-token: 421647d3-5a64-3833-fa3c-e8cd79fc22b3"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  }

  public function APwalletTest_post()
  {
    $inputString = $this->post('inputString');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "7002",
      CURLOPT_URL => "https://192.168.70.101:7002/wal/w10/501/1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "$inputString",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/plain",
        "postman-token: 35c3b5d2-339d-39ce-fd4c-50b137351682"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      echo $response;
    }
  }

  public function AddMoneyNew_post()
  {
    $job_id = 0;
    $payment_type = 2;
    $payment_method = 1;
    $user_id = $this->input->post('user_id');
    $inputString = $this->post('inputString');
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "7002",
      CURLOPT_URL => "https://192.168.70.101:7002/wal/w10/501/1",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "$inputString",
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: text/plain",
        "postman-token: 35c3b5d2-339d-39ce-fd4c-50b137351682"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    /*wallet balance*/
    $where = array('id' => $user_id, );
    $wallet_balance = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
    $walletBalance  = $wallet_balance[0]->wallet_balance;
    // $infos = round($walletBalance/500)*500;
    $infos = round($walletBalance);
    /*-------------*/
    if ($err) {
      // echo "cURL Error #:" . $err;
      $response = array(
        'controller' => "User",
        'action' => "add_money",
        'ResponseCode' => false,
        'wallet_balance' => $infos
      );
    } else {
      // echo $response;
      $Jresp = json_decode($response);
      $hresp = json_decode($Jresp->hresp);
      // print_r($hresp);die;
      if ($hresp->st==0) {
        $payment_status = 100;
        $payment_RefID = $hresp->rrn;
        $amount = $hresp->ao/10;
        $date_created = date("Y-m-d H:i:s");

        if (empty($user_id)) {
          $response = array(
            'controller' => "User",
            'action' => "add_money",
            'ResponseCode' => false,
            'MessageWhatHappen' => 'All fields are Required'
          );
        } else {
          if ($payment_status==100) {
            $this->User_model->add_wallet('tbl_users', $user_id, $amount);
          }
          
          $where = array('id' => $user_id, );
          $wallet_balance = $this->User_model->selectdata("tbl_users","wallet_balance",$where);
          $walletBalance  = $wallet_balance[0]->wallet_balance;
          // $infos = round($walletBalance/500)*500;
          $infos = round($walletBalance);

          $insert_data = array(
            'job_id'=>$job_id,
            'payment_type'=>$payment_type,
            'payment_method' => $payment_method,
            'user_id' => $user_id,
            'payment_status' => $payment_status,
            'payment_RefID' => $payment_RefID,
            'amount'=>$amount,
            'wallet_balance'=>$infos,
            'date_created'=>$date_created
          );
          $this->User_model->insertdata("tbl_payment",$insert_data);

          $response = array(
            'controller' => "User",
            'action' => "add_money",
            'ResponseCode' => true,
            'wallet_balance' => $infos
          );
        }
      }else{
        $response = array(
          'controller' => "User",
          'action' => "add_money",
          'ResponseCode' => false,
          'MessageWhatHappen' => $response
        );
      }
    }
    
    $this->set_response($response, REST_Controller::HTTP_OK);
  }

}
?>