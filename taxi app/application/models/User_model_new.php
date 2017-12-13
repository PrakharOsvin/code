<?php
class User_model_new extends CI_Model

{
  function __construct()
  {

    // Call the Model constructor

    parent::__construct();
    // $this->db->query("SET time_zone='+3:30'");
  }

  public

  function signup($signupData)
  {
    /*------   Code for default vehicle type ------*/
    $vehicle = $this->db->select('id')->from('tbl_vehicle')->where('vehicle_type', 'M Car')->get()->row();
    if ($vehicle == "")
    {
      $signupData['vehicle_id'] = 0;
    }
    else
    {
      $signupData['vehicle_id'] = $vehicle->id;
    }

    // print_r($signupData);die;
    $this->db->insert('tbl_users',$signupData);
    $insert_id = $this->db->insert_id();

    /*----    For refral code    -----*/
    // $rest = substr($signupData['first_name'], 0, 5);

    $selection = 'aeuoyibcdfghjklmnpqrstvwxz1234567890QWERTYUIOPLKJHGFDSAZXCVBNM';
    $cupon = "";
    for($i=0; $i<10; $i++) {
      $current_letter = $selection ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
      $cupon .=  $current_letter;
      if ($i==10) {
        $is_clone = $this->db->get_where('tbl_users',array('promo_code'=>$cupon))->row();
        if (!empty($is_clone)) {
          $cupon = "";
          $i = 0;
          // print_r($is_clone);die;
        }
      }
    }           
          // print_r($cupon);die;

    // $rest = "ABER";
    // $magic = substr(mt_rand() , 0, 4);
    // $cupon = $rest . $magic . $insert_id;
    $date = date("Y-m-d H:i:s");
    $value = $this->db->query("SELECT value from tbl_cupon_value where cupon_type = 1")->row();
    $value = $value->value;
    $update = $this->db->query("UPDATE tbl_users set promo_code = '" . $cupon . "' where id=$insert_id");
    $insert_cupon = $this->db->query("INSERT INTO tbl_cupon(promo_code,user_id,cupon_type,value,date_created) values ('$cupon','$insert_id','1','$value','$date')");
    /*----    For welcome promo start -------- */
    /*$promo_info = $this->db->get_where('tbl_cupon', array(
      'cupon_type' => '2'
    ))->row();

    // print_r($promo_info);die;
    if (!empty($promo_info)) {
      $promo = $promo_info->promo_code;
      $provider = $promo_info->user_id;
      $benificiary = $userid;
      $value = $promo_info->value;
      // $insert_promo = $this->db->query("INSERT INTO tbl_promocode(promocode,promocode_provider_id,promocode_beneficiary_id,cupon_type,value,date_created) values ('$promo','$provider','$benificiary','2','$value','$date')");
      $this->add_wallet('tbl_users', $insert_id, $value);
      $wb = $this->db->select('wallet_balance')->from('tbl_users')->where('id',$insert_id)->get()->row();
      $wallet_balance = $wb->wallet_balance;
      $this->db->query("INSERT INTO `tbl_payment`(`job_id`, `user_id`, `payment_type`, `payment_status`, `payment_method`, `payment_RefID`, `amount`, `wallet_balance`, `date_created`) VALUES (0,$insert_id,2,100,3,'$promo',$value,$wallet_balance,now())");
    }*/

    
    /*---    end     -----*/

    return $insert_id;
  }

  public

  function userByPhone($data)
  {
    $userData = $this->db->query("select * from `tbl_users` where phone =" . $data['phone'])->row();

    // print_r($userData);

    if ($data['limit'] == '')
    {
      $result = "order by date_created desc limit 10";
    }
    else
    {
      $result = "order by date_created desc limit " . $data['limit'];
    }

    $resultData = $this->db->query("SELECT * FROM `tbl_jobs` WHERE ((user_id =" . $userData->id . " or driver_id =" . $userData->id . ") and date_created BETWEEN '" . $data['start_date'] . "' AND '" . $data['end_date'] . "') " . $result)->result();
    return $resultData;

    //  print_r($resultData);die;
    // print_r($this->db->last_query());die();

  }

  public

  function userbyDate($data)
  {
    if ($data['limit'] == '')
    {
      $result = "order by date_created desc limit 10";
    }
    else
    {
      $result = "order by date_created desc limit " . $data['limit'];
    }

    $result1 = $this->db->query("SELECT * FROM `tbl_jobs` WHERE (date_created BETWEEN '" . $data['start_date'] . "' AND '" . $data['end_date'] . "') " . $result)->result_array();

    // print_r($this->db->last_query());die;
    // print_r($result1);die();

    $i = 0;
    foreach($result1 as $data1)
    {
      $data2[$i]['tripinfo'] = $data1;

      // print_r($result1); die();

      $data2[$i]['userDetails'] = $this->db->query("select * from tbl_users where id=" . $data1['user_id'])->row();
      $data2[$i]['driverDetails'] = $this->db->query("select * from tbl_users where id=" . $data1['driver_id'])->row();
      $i++;
    }

    return $data2;
  }

  public

  function insertContact($data)
  {

    // print_r($data[job_id]);die;

    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    $selectPassenger = $this->db2->query("select * from contact where MobileNumber=" . $data['p_num'])->result();
    $selectDriver = $this->db2->query("select * from contact where MobileNumber=" . $data['d_num'])->result();

    // print_r($selectPassenger);print_r($selectDriver);die();

    if (empty($selectPassenger))
    {
      $result = $this->db2->query("insert into contact (Name,family,MobileNumber,type) values ('" . $data['p_name'] . "','family'," . $data['p_num'] . ",'0')");
      $insert_id = $this->db2->insert_id();
    }

    if (empty($selectDriver))
    {
      $result1 = $this->db2->query("insert into contact (Name,family,MobileNumber,type) values ('" . $data['d_name'] . "','family'," . $data['d_num'] . ",'1')");
      $insert_id1 = $this->db2->insert_id();
    }

    if (!empty($selectPassenger))
    {
      $updatePassenger = $this->db2->query("update contact set name='" . $data['p_name'] . "' where MobileNumber=" . $data['p_num']);
    }

    if (!empty($selectDriver))
    {
      $updateDriver = $this->db2->query("update contact set name='" . $data['d_name'] . "' where MobileNumber=" . $data['d_num']);
    }

    $result2 = $this->db2->query("select * from contact where MobileNumber=" . $data['p_num'])->result();
    $result3 = $this->db2->query("select * from contact where MobileNumber=" . $data['d_num'])->result();
    $insertData = $this->db2->query("insert into proute ( `SourceID`, `DestinationID`, `TripNumber`, `Type`, `CallTime`, `BeepTime`) values (".$result2[0]->ID.", ".$result3[0]->ID." ,".$data['TripNumber'].",".$data['Type'].",".$data['CallTime'].",".$data['BeepTime'].")");
    // $qq = $this->db2->last_query();
    // print_r($result2[0]->ID);die;

    $insert_id2 = $this->db2->insert_id();
    $insertData1 = $this->db2->query("insert into CallDetail (RelationID) values ('" . $insert_id2 . "')");

    // print_r($qq);die;

    return 1;
  }

  public

  function insertType($data)
  {

    // print_r($data['p_name']);die;

    $CI = & get_instance();

    // //setting the second parameter to TRUE (Boolean) the function will return the database object.

    $this->db2 = $CI->load->database('otherdb', TRUE);

    //  $result = $this->db->query("insert into contact (Name,family,MobileNumber,type) values ('".$data['p_name']."','family',".$data['p_num'].",".$data['call_type'].")");
    //  // print_r($this->db2->last_query());die;
    //  $insert_id = $this->db->insert_id();
    //  $result1 = $this->db->query("insert into contact (Name,family,MobileNumber,type) values ('".$data['d_name']."','family',".$data['d_num'].",".$data['call_type'].")");
    //  $insert_id1 = $this->db->insert_id();
    //  // print_r($insert_id1);die;
    //  $result2 = $this->db->query("select * from contact where id=".$insert_id)->result();
    //  $result3 = $this->db->query("select * from contact where id=".$insert_id1)->result();
    // $insertData = $this->db->query("insert into proute (SourceID,DestinationID) values (".$result3[0]->ID.",".$result2[0]->ID.")");
    //  // print_r($result2[0]->ID);die;
    //  $insert_id2 = $this->db->insert_id();
    // $insertData1 = $this->db->query("insert into CallDetail (RelationID) values ('".$insert_id2."')");
    // // print_r($result3);die;
    // return 1;

    $selectPassenger = $this->db2->query("select * from contact where MobileNumber=" . $data['p_num'])->result();
    $selectDriver = $this->db2->query("select * from contact where MobileNumber=" . $data['d_num'])->result();
    if (empty($selectPassenger))
    {
      $result = $this->db2->query("insert into contact (Name,family,MobileNumber,type) values ('" . $data['p_name'] . "','family'," . $data['p_num'] . ",'0')");
      $insert_id = $this->db2->insert_id();
    }

    if (empty($selectDriver))
    {
      $result1 = $this->db2->query("insert into contact (Name,family,MobileNumber,type) values ('" . $data['d_name'] . "','family'," . $data['d_num'] . ",'1')");
      $insert_id1 = $this->db2->insert_id();
    }

    if (!empty($selectPassenger))
    {
      $updatePassenger = $this->db->query("update contact set name='" . $data['p_name'] . "' where MobileNumber=" . $data['p_num']);
    }

    if (!empty($selectDriver))
    {
      $updateDriver = $this->db->query("update contact set name='" . $data['d_name'] . "' where MobileNumber=" . $data['d_num']);
    }

    $result2 = $this->db2->query("select * from contact where MobileNumber=" . $data['p_num'])->result();
    $result3 = $this->db2->query("select * from contact where MobileNumber=" . $data['d_num'])->result();

    // print_r($result2);print_r($result3);die();

    $insertData = $this->db2->query("insert into proute (SourceID,DestinationID,TripNumber,Type, CallTime, BeepTime) values (".$result3[0]->ID.",".$result2[0]->ID.",$data[TripNumber],$data[Type],$data[CallTime],$data[BeepTime])");

    // print_r($result2[0]->ID);die;

    $insert_id2 = $this->db2->insert_id();
    $insertData1 = $this->db2->query("insert into CallDetail (RelationID) values ('" . $insert_id2 . "')");

    // print_r($result3);die;

    return 1;
  }

  public

  function data_chek($message)
  {
    $select = $this->db->query("SELECT * from tbl_users where email = '" . $message['email'] . "'");
    $row = $select->result();
    $userid = $row[0]->id;
    if ($row)
    {
      $update1 = $this->db->query("UPDATE tbl_login set status = '0', last_logout=now() where unique_device_id='" . $message['unique_device_id'] . "' ORDER BY `id` DESC LIMIT 1");
      $insert = $this->db->query("INSERT into tbl_login(user_Id,token_id,device_id,unique_device_id,status,date_created) values ('" . $userid . "', '" . $message['token_id'] . "', '" . $message['device_id'] . "', '" . $message['unique_device_id'] . "', 1 ,NOW())");
      return $row;
    }
  }

  public

  function getUserWithEmail($email)
  {

    // print_r($email);
    // die();

    $select = $this->db->query("SELECT * from tbl_users where email = '" . $email . "'");
    $row = $select->result();
    return $row;
  }

  public

  function login($loginData)
  {
    $this->db->trans_start();
    $online = $this->db->query("UPDATE `tbl_users` set availability= '1' WHERE id = '" . $loginData['user_id'] . "'");

    $data['user_info'] = $this->db->select('*')->from('tbl_users')->where('id',$loginData['user_id'])->get()->result();
    
    $id = $data['user_info'][0]->vehicle_id;
    if ($loginData['user_type']==0) {
      $data['vehicle_info'] = $this->db->get_where('tbl_vehicle', array('id' => $id))->result();
    } else {
      $data['vehicle_info'] = $this->db->select('vehicle_type')->from('tbl_driver_vehicle_info')->WHERE('user_id', $loginData['user_id'])->get()->result();
    }
    
    $data['ride_status'] = $this->check_status($loginData['user_id']);
    $data['notifications'] = $this->notification($loginData['user_id'],$loginData['user_type']);

    unset($loginData['user_type']);
    $iosUsers = $this->db->select('max(id),token_id')->from('tbl_login')->where('status',1)->where('user_id',$loginData['user_id'])->where('device_id',1)->get()->row();
    // print_r($iosUsers->token_id);die;
    if (!empty($iosUsers)) {
      $token_id = $iosUsers->token_id;
      $message = "loged off";
      $action = "You have been logged in on another device, if this is not authorized please contact us at 021-2222 5464";
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
        CURLOPT_URL => "http://173.248.157.82/admin/api/User/iosPush",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"token_id\"\r\n\r\n$token_id\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"message\"\r\n\r\n$message\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"sound\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"action\"\r\n\r\n$action\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"job_id\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"ride_details\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"id\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"name\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"profile_pic\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"datetime\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"fare\"\r\n\r\n\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
        CURLOPT_HTTPHEADER => array(
          "cache-control: no-cache",
          "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW; charset=utf-8",
          "postman-token: 54868a64-6243-9183-e0fa-4d41b72c92e5"
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      // if ($err) {
      //   echo "cURL Error #:" . $err;
      // } else {
      //   echo $response;
      // }
    }
    // print_r($iosUsers);die;
    // print_r($this->db->last_query());die;
    $update1 = $this->db->query("UPDATE tbl_login set `status` = '0', `last_logout`=now() where `user_id`='" . $loginData['user_id'] . "' ORDER BY `id` DESC LIMIT 1");
    $insert = $this->db->insert('tbl_login',$loginData);

    //----auto logout
    $logData = array('user_id' => $loginData['user_id'],'logedOutBy'=>'auto','logedOutOn'=>date("Y-m-d H:i:s") );
    $this->User_model->insertdata('tbl_logout', $logData);
    /*------*/
    $this->db->trans_complete();

    // $data['insert_id'] = $this->db->insert_id();
    return $data;
  }

  public function notification($user_id,$user_type)
  {
    $query = $this->db->query("SELECT count(id) as unread FROM `tbl_notifications`
where (`notification_type` = 100 or `notification_type` = $user_type or user_id=$user_id) and id NOT IN(SELECT notification_id from tbl_notification_status WHERE `user_id`=$user_id and `status`=1)")->row();
    return $query;
  }

  public function notifications($user_id,$user_type)
  {
    $query = $this->db->query("SELECT a.*, if((SELECT `STATUS` from tbl_notification_status WHERE `user_id`=$user_id and `notification_id`=a.id) is null, 0, 1) as `status` FROM `tbl_notifications` as a where (`notification_type` = 100 or `notification_type` = $user_type or user_id=$user_id) ORDER BY a.id desc")->result();

    // $query['unread'] = $this->db->query("SELECT count(id) as count FROM `tbl_notifications` where (`notification_type` = 100 or `notification_type` = $user_type) and id NOT IN(SELECT notification_id from tbl_notification_status WHERE `user_id`=$user_id and `status`=1)")->row();

    return $query;
  }

  public function driver_login($inputs)
  {

    // print_r($inputs);

    if ($inputs['email'] != "")
    {
      $db_result['user_info'] = $this->db->select('*')->where('email', $inputs['email'])->where('user_type', 2)->get('tbl_users')->result();
      if (count($db_result['user_info']) > 0)
      {

        // print_r($db_result['user_info'][0]);die;

        if ($db_result['user_info'][0]->email_verified != 1 && $db_result['user_info'][0]->phone_verified != 1)
        {
          return 409;
        }
        elseif ($db_result['user_info'][0]->status != 1)
        {
          return "f1";
        }
        elseif ($inputs['password'] != $db_result['user_info'][0]->password)
        {
          return 2;
        }
        else
        {
          $this->db->trans_start();
          $online = $this->db->query("UPDATE `tbl_users` set availability= '1' WHERE email = '" . $inputs['email'] . "'");

          // print_r($online);die;

          $update1 = $this->db->query("UPDATE tbl_login set `status` = '0', `last_logout`=now() where `user_id`='" . $db_result['user_info'][0]->id . "' ORDER BY `id` DESC LIMIT 1");
          $insert = $this->db->query("INSERT into tbl_login(user_Id,token_id,device_id,unique_device_id,status,date_created) values ('" . $db_result['user_info'][0]->id . "', '" . $inputs['token_id'] . "', '" . $inputs['device_id'] . "', '" . $inputs['unique_device_id'] . "', 1 ,NOW())");

          $this->db->trans_complete();
          // $db_result['insert_id'] = $this->db->insert_id();

          $db_result['user_info'] = $this->db->select('*')->where('email', $inputs['email'])->get('tbl_users')->result();
          $driver_id = $db_result['user_info'][0]->id;
          $db_result['vehicle_info'] = $this->db->select('vehicle_type')->from('tbl_driver_vehicle_info')->WHERE('user_id', $driver_id)->get()->result();
          $db_result['ride_status'] = $this->check_status($db_result['user_info'][0]->id);
          return $db_result;
        }
      }
      else
      {
        return 404;
      }
    }
    else
    {
      $db_result['user_info'] = $this->db->select('*')->where('phone', $inputs['phone'])->where('user_type', 2)->get('tbl_users')->result();
      if (count($db_result['user_info']) > 0)
      {

        // print_r($db_result['user_info'][0]);die;

        if ($db_result['user_info'][0]->phone_verified != 1)
        {
          return 409;
        }
        elseif ($db_result['user_info'][0]->status != 1)
        {
          return "f1";
        }
        elseif ($inputs['password'] != $db_result['user_info'][0]->password)
        {
          return 2;
        }
        else
        {
          $this->db->trans_start();
          $online = $this->db->query("UPDATE `tbl_users` set availability= '1' WHERE phone = '" . $inputs['phone'] . "'");

          // print_r($online);die;

          $update1 = $this->db->query("UPDATE tbl_login set status = '0', `last_logout`=now() where user_id='" . $db_result['user_info'][0]->id . "' ORDER BY `id` DESC LIMIT 1");
          $insert = $this->db->query("INSERT into tbl_login(user_Id,token_id,device_id,unique_device_id,status,date_created) values ('" . $db_result['user_info'][0]->id . "', '" . $inputs['token_id'] . "', '" . $inputs['device_id'] . "', '" . $inputs['unique_device_id'] . "', 1 ,NOW())");
          $this->db->trans_complete();
          $db_result['user_info'] = $this->db->select('*')->where('phone', $inputs['phone'])->get('tbl_users')->result();
          $driver_id = $db_result['user_info'][0]->id;
          $db_result['vehicle_info'] = $this->db->select('vehicle_type')->from('tbl_driver_vehicle_info')->WHERE('user_id', $driver_id)->get()->result();
          $db_result['ride_status'] = $this->check_status($db_result['user_info'][0]->id);
          return $db_result;
        }
      }
      else
      {
        return 404;
      }
    }
  }

  public

  function forgotpassword($email, $user_type)
  {
    $select_user = $this->db->query("SELECT * from tbl_users where email = '" . $email . "'");
    $get_user = $select_user->result();
    $userid = $get_user[0]->id;
    if ($userid == "" || $user_type != $get_user[0]->user_type)
    {
      return "Email does not exist in our database.";
    }
    else
    {
      $static_key = bin2hex(mcrypt_create_iv(11, MCRYPT_DEV_URANDOM));
      $static_key2 = bin2hex(mcrypt_create_iv(11, MCRYPT_DEV_URANDOM));
      $id = $static_key2 . "_" . $userid . "_" . $static_key;
      $result['b_id'] = base64_encode($id);
      /*$userid=base64_decode($result['b_id']);
      $useridArr = explode("_", $userid);
      $userid = $useridArr[1];
      $result['id'] = $userid;
      print_r($result);die;*/
      $result['userid'] = $get_user[0]->id;
      $result['username'] = $get_user[0]->fullname;
      $datetime = date("Y-m-d H:i:s");
      $this->db->insert("tbl_password_recovery", array(
        'user_id' => $result['userid'],
        'reset_token' => $result['b_id'],
        'date_created' => $datetime
      ));
      return $result;
    }
  }

  public

  function updateNewpassword($message)
  {

    //      echo "<pre>"; print_r($message);

    $update_pwd = $this->db->query("UPDATE tbl_users set password = '" . md5($message['password']) . "' where id = '" . $message['id'] . "'");
    if ($update_pwd)
    {
      $this->session->set_flashdata('msg', '<span style="color:green">Password Changed Successfully</span>');
      $this->updatedata("tbl_password_recovery", array(
        'user_id' => $message['id']
      ) , array(
        'token_status' => 1
      ));
      redirect("api/user/newpassword?id=" . $message['id']);
    }
    else
    {
      $this->session->set_flashdata('msg', '<span style="color:red">Error in Updating Password</span>');
      redirect("api/user/newpassword?id=" . $message['id']);
    }
  }

  public

  function changepassword($message)
  {
    $select_pwd = $this->db->query("SELECT * from tbl_users where id = '" . $message['user_id'] . "' and password = '" . md5($message['oldpassword']) . "'");
    $get_pwd = $select_pwd->result();
    if (!empty($get_pwd))
    {
      $update = $this->db->query("UPDATE tbl_users set password = '" . md5($message['newpassword']) . "' where id = '" . $message['user_id'] . "'");
      return "updated";
    }
    else
    {
      return "not updated";
    }
  }

  public

  function updateLatLong($message)
  {
    $update = $this->db->query("UPDATE tbl_users set latitude = '" . $message['latitude'] . "', longitude = '" . $message['longitude'] . "' where id = '" . $message['user_id'] . "'");
    $user_id = $message['user_id'];
    $query = $this->db->select('id')->from('tbl_jobs')->where('driver_id', $user_id)->where('status', 3)->get()->row();
    if ($query->id != "")
    {
      $job_id = $query->id;
      $latitude = $message['latitude'];
      $longitude = $message['longitude'];

      // $data = array('job_id' => $job_id, '' => , );

      $insertdata = $this->db->query("INSERT into tbl_job_distance(job_id,latitude,longitude) VALUES($job_id,$latitude,$longitude)");
    }

    // print_r($query->id);die;

    if ($update)
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public

  function getLatLong($message)
  {
    $latlong = $this->db->query("SELECT latitude, longitude FROM tbl_users where id = '" . $message['user_id'] . "'")->result();

    // die(print_r($latlong));

    if ($latlong)
    {
      return $latlong;
    }
    else
    {
      return 0;
    }
  }

  public

  function jobs($message)
  {
    $insert = $this->db->query("INSERT into tbl_jobs(user_id,pickup_location,drop_off_location,pickup_lat,pickup_long,dropoff_lat,dropoff_long,job_datetime,date_created) values ('" . $message['userId'] . "','" . $message['pickup_location'] . "','" . $message['drop_off_location'] . "','" . $message['pickup_lat'] . "','" . $message['pickup_long'] . "','" . $message['dropoff_lat'] . "','" . $message['dropoff_long'] . "','" . $message['job_datetime'] . "',NOW())");
    $insert_id = $this->db->insert_id();
    if ($insert)
    {
      $noti_insert = $this->db->query("INSERT into tbl_notification(user_id,from_id,message,type, job_id,is_seen,date_created) values ('" . $message['userId'] . "','" . $message['userId'] . "','" . $message['message'] . "','" . $message['type'] . "','" . $insert_id . "','" . $message['is_seen'] . "',NOW())");
      return $insert_id;
    }
  }

  public

  function logout($message)
  {
    // print_r($message);die;
    $select_user = $this->db->query("SELECT * from tbl_login where unique_device_id = '" . $message['unique_device_id'] . "' AND user_id = '" . $message['user_id'] . "'AND token_id = '" . $message['token_id'] . "'");
    $get_user = $select_user->result();
    // print_r($get_user);die;
    if (empty($get_user))
    {
      return "0";
    }
    else
    {
      $this->db->trans_start();
      $update = $this->db->query("UPDATE tbl_login set status = '0', last_logout=now() where user_id = '" . $message['user_id'] . "' ORDER BY `id` DESC LIMIT 1");
      $online = $this->db->query("UPDATE `tbl_users` set availability= '0' WHERE id = '" . $message['user_id'] . "'");
      $this->db->trans_complete();
      return "1";
    }
  }

  public

  function driversNearby($message)
  {
    $q = $this->db->select('value')->from('tbl_appSettings')->where('name','searchRange')->get()->row();
    $range = $q->value;

    $latitude = $message['latitude'];
    $longitude = $message['longitude'];
    $select_users = $this->db->query("SELECT  ROUND(( 6371 * acos ( cos ( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( latitude ) ) ) ),3) AS `distance`, tbl_users.id, tbl_driver_vehicle_info.`vehicle_type`, `gender`, `smoker`, `spk_english`, restriction, tbl_users.latitude, tbl_users.longitude FROM `tbl_users` JOIN tbl_driver_vehicle_info ON (tbl_driver_vehicle_info.user_id = tbl_users.id) JOIN tbl_login ON (tbl_login.user_id = tbl_users.id) JOIN tbl_vehicle ON (tbl_vehicle.id = tbl_driver_vehicle_info.vehicle_id) where tbl_users.user_type = '2' AND tbl_login.status = '1' AND tbl_users.is_free = '1' and tbl_users.status = '1' and tbl_users.availability = '1' and tbl_users.is_restricted != '1' having distance <= $range ORDER BY distance");
    $qry_users = $select_users->result();
    if (!empty($qry_users))
    {
      return $qry_users;
    }
  }

  public

  function clientslist()
  {
    $select = $this->db->query("SELECT * from tbl_users where user_type='2'");
    $result = $select->result();
    if (!empty($result))
    {
      return $result;
    }
    else
    {
    }
  }

  public

  function phone_verification($phone)
  {
    $update = $this->db->query("UPDATE `tbl_users` set `phone_verified` = '1' where `phone` = '$phone'");
    return "Updated";
  }

  public

  function get_vehicle_list()
  {
    $this->db->select("*");
    $this->db->from('tbl_vehicle');
    $query = $this->db->get();
    return $query->result();
  }

  public

  function get_location_list($location)
  {
    $select = $this->db->query("SELECT * FROM `tbl_locations` WHERE `location_name` LIKE '" . $location . "%' ");
    $row = $select->result();
    return $row;

    // print_r($row);
    // die();

  }

  public

  function is_unique_phone($phone)
  {
    $select = $this->db->query("SELECT * from tbl_users where phone = '" . $phone . "'");
    $row = $select->result();

    // print_r($row);
    // die();

    return $row;
  }

  public

  function book_ride($data)
  {
    $latitude = $data['pickup_lat'];
    $longitude = $data['pickup_long'];

    // print_r($data);
    // die();

    $insert = $this->db->query("INSERT into tbl_jobs(user_id,pickup_location,dropoff_location,pickup_lat,pickup_long,dropoff_lat,dropoff_long, way_points, smoker, gender, spk_english, vehicle_id, job_datetime,date_created,payment_method,estimate,blue_mode,modified_on) values ('" . $data['user_id'] . "','" . $data['pickup_location'] . "','" . $data['dropoff_location'] . "','" . $data['pickup_lat'] . "','" . $data['pickup_long'] . "','" . $data['dropoff_lat'] . "', '" . $data['dropoff_long'] . "','" . $data['way_points'] . "','" . $data['smoker'] . "', '" . $data['gender'] . "', '" . $data['spk_english'] . "', '" . $data['vehicle_id'] . "','" . $data['job_datetime'] . "',NOW(), '" . $data['payment_method'] . "','" . $data['estimate'] . "','" . $data['blue_mode'] . "',NOW())");
    $job_id = $this->db->insert_id();

    // print_r($job_id);
    // die();

    /*----- Code for checking if user selected cupon or not ----*/
    $select_cupon = $this->db->select('*')->from('tbl_promocode')->where(array(
      'promocode_beneficiary_id' => $data['user_id'],
      'status' => '2'
    ))->get()->result();

    // print_r($select_cupon);die;

    if (count($select_cupon) > 0)
    {
      $this->db->where(array(
        'promocode_beneficiary_id' => $data['user_id'],
        'status' => '2'
      ))->update('tbl_promocode', array(
        'job_id' => $job_id
      ));
    }
    else
    {
      /*=============Auto select promo available===========*/
      // $this->db->where(array(
      //   'promocode_beneficiary_id' => $data['user_id'],
      //   'cupon_type' => '2',
      //   'status !=' => '1'
      // ))->update('tbl_promocode', array(
      //   'job_id' => $job_id,
      //   'status' => '2'
      // ));

      // print_r($this->db->last_query());

    }

    if ($job_id)
    {
      return $job_id;
    }
    else
    {
      return "false";
    }
  }

  public

  function find($data)
  {

    // print_r($data);
    //     die();

    $latitude = $data['pickup_lat'];
    $longitude = $data['pickup_long'];
    $dropoff_lat = $data['dropoff_lat'];
    $dropoff_long = $data['dropoff_long'];
    /*------------------------------------------*/
    $vertices_x = array(); // x-coordinates of the vertices of the polygon
    $lat = $this->db->select("latitude")->get("tbl_polygon1")->result();
    foreach($lat as $key => $value)
    {
      foreach($value as $key => $lat)
      {
        array_push($vertices_x, $lat);
      }
    }

    $vertices_y = array(); // y-coordinates of the vertices of the polygon
    $long = $this->db->select("longitude")->get("tbl_polygon1")->result();
    foreach($long as $key => $value)
    {
      foreach($value as $key => $long)
      {
        array_push($vertices_y, $long);
      }
    }

    $points_polygon = count($vertices_x); // number vertices
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude_x, $longitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon - 1; $i < $points_polygon; $j = $i++)
      {
        if ((($vertices_y[$i] > $longitude_y != ($vertices_y[$j] > $longitude_y)) && ($latitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($longitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))) $c = !$c;
      }

      return $c;
    }

    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude, $longitude))
    {
      $rz = 1;
    }
    else $rz = 0;
    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $dropoff_lat, $dropoff_long))
    {
      $drz = 1;
    }
    else $drz = 0;

    // -------------------------------

    $vertices_x_oe = array(); // x-coordinates of the vertices of the polygon
    $lat2 = $this->db->select("latitude")->get("tbl_polygon2")->result();
    foreach($lat2 as $key => $value)
    {
      foreach($value as $key => $lat)
      {
        array_push($vertices_x_oe, $lat);
      }
    }

    $vertices_y_oe = array(); // y-coordinates of the vertices of the polygon
    $long2 = $this->db->select("longitude")->get("tbl_polygon2")->result();
    foreach($long2 as $key => $value)
    {
      foreach($value as $key => $long)
      {
        array_push($vertices_y_oe, $long);
      }
    }

    $points_polygon_oe = count($vertices_x_oe); // number vertices
    if (is_in_polygon($points_polygon_oe, $vertices_x_oe, $vertices_y_oe, $latitude, $longitude))
    {
      $oe = 1;
    }
    else $oe = 0;
    if (is_in_polygon($points_polygon_oe, $vertices_x_oe, $vertices_y_oe, $dropoff_lat, $dropoff_long))
    {
      $doe = 1;
    }
    else $doe = 0;

    // echo "$rz";
    // echo "$drz";
    // echo "\n";
    // echo "$oe";
    // echo "$doe";
    // die;
    if ($rz!=0) {
      $pzone="restricted";
    } elseif ($oe!=0) {
      $pzone="odd/even";
    } else{
      $pzone="other";
    }

    if ($drz!=0) {
      $dzone="restricted";
    } elseif ($doe!=0) {
      $dzone="odd/even";
    } else{
      $dzone="other";
    }
    
    $this->db->query("INSERT INTO `tbl_jobZones`(`job_id`, `pickup_zone`, `dropoff_zone`, `addedOn`) VALUES (".$data['job_id'].",'$pzone','$dzone',now())");
    /*----------------------------------*/
    $select = $this->db->query("SELECT status FROM tbl_jobs WHERE id = '" . $data['job_id'] . "' ")->result();

    // print_r($select);
    // die;

    if ($select[0]->status == 50 || $select[0]->status == 52 || $select[0]->status == 51 || $select[0]->status == 6)
    {
      $this->db->query("UPDATE tbl_promocode SET status = '0' WHERE job_id = '" . $data['job_id'] . "' ");
      return "false";
    }
    else
    {
      $latitude = $data['pickup_lat'];
      $longitude = $data['pickup_long'];
      $job_id = $data['job_id'];

      // print_r($data);
      // die();

      $and = "";
      if ($data['gender'] != "0")
      {
        $and.= " and tbl_users.gender = '" . $data['gender'] . "' ";
      }

      if ($data['spk_english'] != "0")
      {
        $and.= " and tbl_users.spk_english != 0 ";
      }

      if ($data['vehicle_id'] != "0")
      {
        $and.= " and tbl_driver_vehicle_info.vehicle_id = '" . $data['vehicle_id'] . "' ";
      }

      if ($data['smoker'] != "1")
      {
        $and.= " and tbl_users.smoker = '" . $data['smoker'] . "' ";
      }

      $today = date("Y-m-d");
      $time = date('Gi');
      $select_date = $this->db->query("SELECT * from tbl_holidays WHERE holiday = '$today' ")->result();

      // print_r($select_date);
      // date('l') = "Monday";
      // $date = new DateTime();
      // $date->setDate(2001, 2, 28);
      // echo date('l');die;
      $q = $this->db->select('value')->from('tbl_appSettings')->where('name','searchRange')->get()->row();
      $range = $q->value;
      // print_r($range);

      if (count($select_date) > 0 || date('l') == "Friday")
      {

        // echo "string";die;
        /*
        * haversine formula.
        */
        $select_users = $this->db->query("SELECT  ROUND(( 6371 * acos ( cos ( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( latitude ) ) ) ),3) AS `distance`, tbl_users.id, tbl_login.device_id, tbl_login.token_id FROM `tbl_users` JOIN tbl_login ON  (tbl_users.id=tbl_login.user_id) JOIN tbl_driver_vehicle_info ON (tbl_users.id=tbl_driver_vehicle_info.user_id) where tbl_users.id NOT IN (SELECT driver_id FROM tbl_response WHERE job_id = $job_id AND job_id IS NOT NULL AND response BETWEEN 1 and 2)  AND tbl_users.user_type = '2' AND tbl_users.is_free = '1' and tbl_login.status = '1' and tbl_users.status = '1' and tbl_users.availability = '1' $and  having distance <= $range order by distance asc");

        // print_r($this->db->last_query());die;

        $qry_usersd = $select_users->row();

        // print_r($qry_usersd);
        // die();

        if ($qry_usersd != "")
        {
          $insert = $this->db->query("INSERT into tbl_response(job_id,driver_id,response) values ($job_id,$qry_usersd->id,0)");
          return array(
            'driver' => $qry_usersd,
            'job_id' => $job_id
          );
        }
        else
        {
          $update = $this->db->query("UPDATE tbl_jobs SET status = '6', is_active = '0', modified_on = NOW(),notified= '0' WHERE id = '" . $data['job_id'] . "' ");
          return "false";
        }
      }
      else
      {
        $permit = "";
        
        // // print_r($rz_timing);
        // die;
        $isExist = $this->db->query("SELECT * FROM `tbl_zone_setting` where now() between start_date and end_date")->row();
        /*====Scenario B for All tehran as odd/even zone===*/
        if (($oe == 1 || $doe == 1)||$isExist->scenario=='B')
        {
        // print_r($doe);
          $oe_timing = $this->db->query("SELECT id,day, DATE_FORMAT(`start_time`,'%H%i') as start_time, DATE_FORMAT(end_time,'%H%i') as end_time FROM `tbl_rzone_time` where zone = 'oe'")->result();

          foreach ($oe_timing as $key => $value) {
            if ($value->day=="Sunday") {
              $oeSunday_start = $value->start_time;
              $oeSunday_end = $value->end_time;
            }elseif ($value->day=="Monday") {
              $oeMonday_start = $value->start_time;
              $oeMonday_end = $value->end_time;
            }elseif ($value->day=="Tuesday") {
              $oeTuesday_start = $value->start_time;
              $oeTuesday_end = $value->end_time;
            }elseif ($value->day=="Wednesday") {
              $oeWednesday_start = $value->start_time;
              $oeWednesday_end = $value->end_time;
            }elseif ($value->day=="Thursday") {
              $oeThursday_start = $value->start_time;
              $oeThursday_end = $value->end_time;
            }elseif ($value->day=="Friday") {
              $oeFriday_start = $value->start_time;
              $oeFriday_end = $value->end_time;
            }elseif ($value->day=="Saturday") {
              $oeSaturday_start = $value->start_time;
              $oeSaturday_end = $value->end_time;
            }
          }

          //<<===Scenario A for odd/even as restrticted zone===>>
          if (($rz == 1 || $drz == 1)||$isExist->scenario=='A')
          {
            // print_r("$rz:$drz:$oe:$doe");
            $rz_timing = $this->db->query("SELECT id,day, DATE_FORMAT(`start_time`,'%H%i') as start_time, DATE_FORMAT(end_time,'%H%i') as end_time FROM `tbl_rzone_time` where zone = 'rz'")->result();

            foreach ($rz_timing as $key => $value) {
              if ($value->day=="Sunday") {
                $rzSunday_start = $value->start_time;
                $rzSunday_end = $value->end_time;
              }elseif ($value->day=="Monday") {
                $rzMonday_start = $value->start_time;
                $rzMonday_end = $value->end_time;
              }elseif ($value->day=="Tuesday") {
                $rzTuesday_start = $value->start_time;
                $rzTuesday_end = $value->end_time;
              }elseif ($value->day=="Wednesday") {
                $rzWednesday_start = $value->start_time;
                $rzWednesday_end = $value->end_time;
              }elseif ($value->day=="Thursday") {
                $rzThursday_start = $value->start_time;
                $rzThursday_end = $value->end_time;
              }elseif ($value->day=="Friday") {
                $rzFriday_start = $value->start_time;
                $rzFriday_end = $value->end_time;
              }elseif ($value->day=="Saturday") {
                $rzSaturday_start = $value->start_time;
                $rzSaturday_end = $value->end_time;
              }
            }

            if ( (date('l') == "Sunday" && ($time >= $rzSunday_start && $time <= $rzSunday_end))
             || (date('l') == "Monday" && ($time >= $rzMonday_start && $time <= $rzMonday_end))
             || (date('l') == "Tuesday" && ($time >= $rzTuesday_start && $time <= $rzTuesday_end))
             || (date('l') == "Wednesday" && ($time >= $rzWednesday_start && $time <= $rzWednesday_end))
             || (date('l') == "Thursday" && ($time >= $rzThursday_start && $time <= $rzThursday_end))
             || (date('l') == "Saturday" && ($time >= $rzSaturday_start && $time <= $rzSaturday_end)) )
            {
              $permit.= "AND (tbl_driver_vehicle_info.vehicle_permit_type = 'A' or tbl_users.id in(select driver_id from tbl_permit where now() between `from` and `to`))";
            }
            else
            {
              $permit = "";
            }
          }
          elseif ( (date('l') == "Sunday" && ($time >= $rzSunday_start && $time <= $rzSunday_end))
            || (date('l') == "Tuesday" && ($time >= $rzTuesday_start && $time <= $rzTuesday_end))
            || (date('l') == "Thursday" && ($time >= $rzThursday_start && $time <= $rzThursday_end)) )
          {
            $permit.= "AND (tbl_driver_vehicle_info.vehicle_permit_type = 'B' OR tbl_driver_vehicle_info.vehicle_permit_type = 'A' or tbl_users.id in(select driver_id from tbl_permit where now() between `from` and `to`))";
          }
          elseif ((date('l') == "Monday" && ($time >= $rzMonday_start && $time <= $rzMonday_end))
           || (date('l') == "Wednesday" && ($time >= $rzWednesday_start && $time <= $rzWednesday_end))
           || (date('l') == "Saturday" && ($time >= $rzSaturday_start && $time <= $rzSaturday_end)) )
          {
            $permit.= "AND (tbl_driver_vehicle_info.vehicle_permit_type = 'C' OR tbl_driver_vehicle_info.vehicle_permit_type = 'A' or tbl_users.id in(select driver_id from tbl_permit where now() between `from` and `to`))";
          }
          else
          {
            $permit = "";
          }
        }

        // print_r($permit);die;

        $select_users = $this->db->query("SELECT  ROUND(( 6371 * acos ( cos ( radians($latitude) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians($longitude) ) + sin ( radians($latitude) ) * sin( radians( latitude ) ) ) ),3) AS `distance`, tbl_users.id, tbl_login.device_id, tbl_login.token_id FROM `tbl_users` JOIN tbl_login ON  (tbl_users.id=tbl_login.user_id) JOIN tbl_driver_vehicle_info ON (tbl_users.id=tbl_driver_vehicle_info.user_id) where tbl_users.id NOT IN (SELECT driver_id FROM tbl_response WHERE job_id = $job_id AND job_id IS NOT NULL AND response BETWEEN 1 and 2) $permit  AND tbl_users.user_type = '2' AND tbl_users.is_free = '1' and tbl_login.status = '1' and tbl_users.status = '1' and tbl_users.availability = '1' and tbl_users.is_restricted !='1' $and  having distance <= $range order by distance asc");

        // $qry_usersd="";
        $qry_usersd = $select_users->row();
        // print_r($permit);
        if ($qry_usersd == "")
        {

          // print_r("expression");die;

          $update = $this->db->query("UPDATE tbl_jobs SET status = '6', is_active = '0', modified_on = NOW(),notified = '0' WHERE id = '" . $data['job_id'] . "' ");
          $this->db->query("UPDATE tbl_promocode SET status = '0' WHERE job_id = " . $data['job_id'] . " ");

          $insert = $this->db->query("UPDATE tbl_response SET response=6,modifiedOn=now() where job_id=".$data['job_id']." ORDER BY id DESC LIMIT 1 ");

          if ($rz == 1 && $oe == 1)
          {
            return "RZ0";
          }
          elseif ($drz == 1 && $doe == 1)
          {
            return "RZ1";
          }
          elseif ($rz == 0 && $oe == 1)
          {
            return "POE";
          }
          elseif ($drz == 0 && $doe == 1)
          {
            return "DOE";
          }
          else
          {
            return "false";
          }
        }
        else
        {
          $insert = $this->db->query("INSERT into tbl_response(job_id,driver_id,response) values ($job_id,$qry_usersd->id,0)");

          /*
          *to prevent driver from getting another request
          */
          /*$this->db->query(" UPDATE tbl_users set push_time = NOW(), is_free = 0, pjob_id=$job_id WHERE id = ". $qry_usersd->id ." ");*/

          return array(
            'driver' => $qry_usersd,
            'job_id' => $job_id
          );
        }
      }

      // print_r($qry_usersd);die;
      // $qry_users = $select_users->row();

    }

    // print_r($this->db->last_query());die;

  }

  public

  function push_time($id)
  {
    $this->db->query(" UPDATE tbl_users set push_time = NOW(), is_free = 0 WHERE id = $id ");
  }

  public

  function next($data)
  {

    // print_r($data);
    // die;
    $this->db->trans_start();
    $update = $this->db->query("UPDATE `tbl_users` set push_time = null, is_free = '1' WHERE id = '" . $data['user_id'] . "' ");

    $this->db->query("UPDATE `tbl_response` set response=".$data['resp'].",`modifiedOn`=now() WHERE job_id=".$data['job_id']." and driver_id=".$data['user_id']." ");

    $this->db->trans_complete();
    return true;

    // print_r($insert_id);die();

  }

  public

  function get_job($data)
  {

    // print_r($data);die;

    $select = $this->db->query("SELECT * FROM tbl_jobs WHERE id = '" . $data['job_id'] . "'");
    $query = $select->result_array();

    // $array = array_values($query);

    return $query[0];

    // print_r($query[0]['fare']);
    // die;

  }

  public

  function accept_ride($data)
  {
    $track_url = base_url('/location/track') . '/' . base64_encode($data['job_id'] . "--mycheckall");
    $this->db->trans_start();
    
    $update = $this->db->query("UPDATE tbl_jobs SET driver_id = '" . $data['user_id'] . "', accept_datetime = NOW(), is_active = '1', status = '1', track_url = '" . $track_url . "', modified_on=NOW(),notified=0 WHERE id = '" . $data['job_id'] . "'");

    /*
    *push_time=null so that cron do not get it
    *is_free=0 to ensure
    */
    $update = $this->db->query("UPDATE tbl_users SET push_time = null, is_free = '0' WHERE id = '" . $data['user_id'] . "' ");

    $this->db->query("UPDATE `tbl_response` set response=5,`modifiedOn`=now() WHERE job_id=".$data['job_id']." and driver_id=".$data['user_id']." ");

    $this->db->trans_complete();
    return true;
  }

  public

  function driver_info($data)
  {
    $query = $this->db->query("SELECT name, first_name, last_name, phone, profile_pic, tbl_driver_vehicle_info.id, vehicle_type, registration_number, state_identifier, vehicle_model FROM tbl_users JOIN tbl_driver_vehicle_info ON (tbl_users.id = tbl_driver_vehicle_info.user_id) WHERE tbl_users.id = '" . $data['user_id'] . "' ");
    return $result = $query->result();
  }

  public

  function start_ride($data)
  {
    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    $update = $this->db2->query("UPDATE proute SET Type = '3' WHERE TripNumber = ".$data['job_id']."");
    $update = $this->db->query("UPDATE tbl_jobs SET driver_id = '" . $data['user_id'] . "', job_start_datetime = NOW(), is_active = '1', status = '3', modified_on= NOW(),notified=0 WHERE id = '" . $data['job_id'] . "' ");
    return true;
  }

  public

  function finish_ride($data)
  {
    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    // print_r($data['user_id']);die;
    $update = $this->db2->query("UPDATE proute SET Type = '4' WHERE TripNumber = ".$data['job_id']."");
    $update = $this->db->query("UPDATE tbl_jobs SET job_completed_datetime = NOW(), is_active = '0', job_completed_datetime = NOW(), waiting_time_cost = '" . $data['fare_cal']['waiting_time_cost'] . "', fare = '" . $data['fare_cal']['fare'] . "', distance = '" . $data['fare_cal']['distance'] . "', modified_on = NOW(),notified=0 WHERE id = '" . $data['job_id'] . "' ");
    /*$update = $this->db->query("UPDATE tbl_users SET push_time = null, is_free = '1' WHERE id = '" . $data['user_id'] . "' ");*/

    //  Driver referral code
    //--- check for five rides----
    $targetRides = $this->db->query("SELECT COUNT(*) as rides FROM `tbl_jobs` where driver_id=".$data['user_id']." and status=4 having COUNT(*)>4")->row();
    // print_r($targetRides->rides);die;
    $ridesCompleted = $targetRides->rides;
    if ($ridesCompleted==4) {
      $checkDrivRef = $this->db->query("SELECT * from tbl_promocode WHERE promocode_beneficiary_id``='" . $data['user_id'] . "' and status!=1 and cupon_type!=2")->row();
      if (!empty($checkDrivRef))
      {
        $this->db->query("UPDATE tbl_promocode SET status=1 where id='" . $checkDrivRef->id . "'");
        $this->db->query("UPDATE tbl_users SET `wallet_balance`=`wallet_balance`+'" . $checkDrivRef->value . "',`referral_money`= `referral_money`+'" . $checkDrivRef->value . "' WHERE id='" . $checkDrivRef->promocode_provider_id . "'");
      }
    }
    //-------------

    // -- end
    /*--------Selected Promo----------*/
    $this->db->query("UPDATE tbl_promocode SET status = '1' WHERE job_id = '" . $data['job_id'] . "' ");
    $select_cupon_type = $this->db->query("SELECT * from tbl_promocode WHERE job_id = '" . $data['job_id'] . "' AND status = '1'")->row();

    $select = $this->db->query("SELECT job_start_datetime, job_completed_datetime, tbl_jobs.id, tbl_jobs.user_id, tbl_users.name, tbl_users.profile_pic,tbl_jobs.payment_method FROM tbl_jobs JOIN tbl_users ON (tbl_jobs.user_id = tbl_users.id) WHERE tbl_jobs.id = '" . $data['job_id'] . "' ");
    $query = $select->row();

    // print_r($query);die;

    return $query;
  }

  // set the driver status arived

  public

  function set_arrived($job_id)
  {
    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    $update = $this->db2->query("UPDATE proute SET Type = '2' WHERE TripNumber = ".$job_id."");
    $datetime = date("Y-m-d H:i:s");
    $data = array(
      'status' => 2,
      'is_active' => 1,
      'arrived_datetime' => $datetime,
      'modified_on' => $datetime,
      'notified' => 0
    );
    $this->db->where('id', $job_id);
    if ($this->db->update('tbl_jobs', $data))
    {
      return true;
    }
    else
    {
      return false;
    }
  }

  public

  function rate_ride($data)
  {
    if ($data['user_type'] != 2)
    {
      $update = $this->db->query("UPDATE tbl_jobs SET rating = '" . $data['rating'] . "', feedback = '" . $data['feedback'] . "', CissueTitle= '" . $data['issueTitle'] . "' WHERE id = '" . $data['job_id'] . "' AND user_id = '" . $data['user_id'] . "' ");
      $driver_id = $this->db->query("SELECT driver_id FROM tbl_jobs WHERE id = '" . $data['job_id'] . "' ");
      $q = $driver_id->result_array();
      $driver_id = $q[0]['driver_id'];

      // print_r($q[0]['driver_id']);
      // die;

      $select = $this->db->query("SELECT feedback, rating FROM tbl_jobs WHERE driver_id = $driver_id ");
      $query = $select->result();

      // print_r($query[0]->rating);

      $c = count($query);

      // echo $c;

      for ($i = 0; $i < $c; $i++)
      {
        $r+= ($query[$i]->rating);
      }

      // echo($r);
      // die;

      $avg_rating = $r / $c;

      // echo($avg_rating);
      // die;

      $update_user = $this->db->query("UPDATE tbl_users SET rating = $avg_rating WHERE id = $driver_id ");
    }
    else
    {
      $update = $this->db->query("UPDATE tbl_jobs SET driver_rating = 5, driver_feedback = '" . $data['feedback'] . "', driver_issue = '" . $data['driver_issue'] . "', DissueTitle= '" . $data['issueTitle'] . "' WHERE id = '" . $data['job_id'] . "' AND driver_id = '" . $data['user_id'] . "' ");
      $user_id = $this->db->query("SELECT user_id FROM tbl_jobs WHERE id = '" . $data['job_id'] . "' ");
      $q = $user_id->result_array();
      $user_id = $q[0]['user_id'];

      // print_r($q[0]['driver_id']);
      // die;

      $select = $this->db->query("SELECT driver_feedback, driver_rating FROM tbl_jobs WHERE user_id = $user_id ");
      $query = $select->result();

      // print_r($query[0]->rating);

      $c = count($query);

      // echo $c;

      for ($i = 0; $i < $c; $i++)
      {
        $r+= ($query[$i]->driver_rating);
      }

      // echo($r);
      // die;

      $avg_rating = $r / $c;

      // echo($avg_rating);
      // die;

      $update_user = $this->db->query("UPDATE tbl_users SET rating = $avg_rating WHERE id = $user_id ");
    }

    return 1;
  }

  public

  function ios($token_id, $message, $ride_details = NULL, $action = NULL, $job_id = NULL, $id = NULL, $name = NULL, $profile_pic = NULL, $datetime = NULL, $sound = NULL, $fare = NULL)
  {
    if ($sound == "")
    {
      $sound = "default";
    }

    $deviceToken = $token_id;

    //        $passphrase = '@osvin123';

    $passphrase = '';
    $ctx = stream_context_create();

    //        stream_context_set_option($ctx, 'ssl', 'local_cert', base_url().'certs/Flamer.pem');

    stream_context_set_option($ctx, 'ssl', 'local_cert', './certs/notify_challenge.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    // Open a connection to the APNS server

    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    //        $fp = stream_socket_client('gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    //

    if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);
    if ($message == "Driver cancelled trip")
    {
      $body['aps'] = array(
        'alert' => $action,
        'sound' => $sound,
        'message' => "Due to some reasons , driver has cancelled the trip",
      );
    }
    else
    {
      $body['aps'] = array(
        'alert' => $action,
        'sound' => $sound,
        'message' => $message,
        'job_id' => $job_id,
        'ride_details' => $ride_details,
        'id' => $id,
        'name' => $name,
        'profile_pic' => $profile_pic,
        'datetime' => $datetime,
        'content-available' => '1',
        'fare' => $fare,
      );
    }

    //        print_r($body);die;
    // Encode the payload as JSON

    $payload = json_encode($body);

    // Build the binary notification

    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

    // Send it to the server

    $result = fwrite($fp, $msg, strlen($msg));
    socket_close($fp);

    // print_r($result);die;

    fclose($fp);
    return $result;
  }

  public

  function android($token_id, $message, $ride_details = NULL, $action = NULL, $job_id = NULL, $id = NULL, $name = NULL, $profile_pic = NULL, $datetime = NULL)
  {

    //    echo $message;die;

    $api_key = "AIzaSyB-nEUOm298Ps46jVn3csbuBByR7FFdxKo";
    $registration_ids = $token_id;
    $gcm_url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
      'registration_ids' => array(
        $registration_ids
      ) ,

      // 'data' => array( "message" => $message,"itemname"=>$itemname,"purchased_amount"=>$purchased_amount),

      'data' => array(
        "message" => $message,
        'job_id' => $job_id,
        'ride_details' => $ride_details,
        'action' => $action,
        'id' => $id,
        'name' => $name,
        'profile_pic' => $profile_pic,
        'datetime' => $datetime,
      ) ,
    );
    $headers = array(
      'Authorization: key=' . $api_key,
      'Content-Type: application/json'
    );
    $curl_handle = curl_init();

    // set CURL options

    curl_setopt($curl_handle, CURLOPT_URL, $gcm_url);
    curl_setopt($curl_handle, CURLOPT_POST, true);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($fields));

    // send

    $response = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $response;
  }

  public

  function push_ios($push_data)
  {
    if ($sound == "")
    {
      $sound = "default";
    }

    $deviceToken = $push_data['token_id'];

    //        $passphrase = '@osvin123';

    $passphrase = '';
    $ctx = stream_context_create();

    //        stream_context_set_option($ctx, 'ssl', 'local_cert', base_url().'certs/Flamer.pem');

    stream_context_set_option($ctx, 'ssl', 'local_cert', './certs/notify_challenge.pem');
    stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);

    // Open a connection to the APNS server

    $fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);

    //        $fp = stream_socket_client('gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
    //

    if (!$fp) exit("Failed to connect: $err $errstr" . PHP_EOL);
    if ($message == "Driver cancelled trip")
    {
      $body['aps'] = array(
        'alert' => $push_data['action'],
        'sound' => $sound,
        'message' => "Due to some reasons , driver has cancelled the trip",
      );
    }
    else
    {
      $body['aps'] = array(
        'alert' => $push_data['action'],
        'sound' => $sound,
        'message' => $push_data['message'],
        'job_id' => $push_data['job_id'],
        'ride_details' => $push_data['ride_details'],
        'distance' => $push_data['distance'],
        'time' => $push_data['time'],
        'estimated_price' => $push_data['estimated_price'],
        'content-available' => '1',
      );
    }

    //        print_r($body);die;
    // Encode the payload as JSON

    $payload = json_encode($body);

    // Build the binary notification

    $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;

    // Send it to the server

    $result = fwrite($fp, $msg, strlen($msg));
    socket_close($fp);

    // print_r($result);die;

    fclose($fp);
    return $result;
  }

  public

  function push_android($push_data)
  {

    //    echo $message;die;

    $api_key = "AIzaSyB-nEUOm298Ps46jVn3csbuBByR7FFdxKo";
    $registration_ids = $push_data['token_id'];
    $gcm_url = 'https://android.googleapis.com/gcm/send';
    $fields = array(
      'registration_ids' => array(
        $registration_ids
      ) ,
      'data' => array(
        "message" => $push_data['message'],
        'job_id' => $push_data['job_id'],
        'ride_details' => $push_data['ride_details'],
        'action' => $push_data['action'],
        'distance' => $push_data['distance'],
        'time' => $push_data['time'],
        'estimated_price' => $push_data['estimated_price'],
      ) ,
    );
    $headers = array(
      'Authorization: key=' . $api_key,
      'Content-Type: application/json'
    );
    $curl_handle = curl_init();

    // set CURL options

    curl_setopt($curl_handle, CURLOPT_URL, $gcm_url);
    curl_setopt($curl_handle, CURLOPT_POST, true);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode($fields));

    // send

    $response = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $response;
  }

  public

  function apply_promo($data)
  {
    $select = $this->db->query("SELECT id,promo_code, (select value from tbl_cupon_value where cupon_type ='1') as value FROM tbl_users WHERE promo_code = '" . $data['promo_code'] . "' AND id != '" . $data['user_id'] . "' and user_type=0");
    $query = $select->result();
    $value = $query[0]->value;
    $promo = $query[0]->promo_code;
    $provider = $query[0]->id;
    if ($promo != "")
    {
      $select = $this->db->query("SELECT * FROM tbl_promocode WHERE promocode_beneficiary_id = '" . $data['user_id'] . "' AND cupon_type = '1'");
      $query = $select->result();
      if ($query[0]->id == "")
      {
        $insert = $this->db->query("INSERT INTO tbl_promocode (promocode,promocode_provider_id,promocode_beneficiary_id,value, cupon_type, status,date_created) VALUES('" . $promo . "','" . $provider . "','" . $data['user_id'] . "', $value,'1','0',NOW())");
        return "yo";
      }
      else
      {
        return "exist";
      }
    }
    else
    {
      return false;
    }

    // print_r($query);
    // die;

  }

  public

  function apply_cupon($data)
  {

    // print_r($data);die;
    // for referral

    $select = $this->db->query("SELECT id,promo_code, (select value from tbl_cupon_value where cupon_type ='1') as value FROM tbl_users WHERE promo_code = '" . $data['promo_code'] . "' AND id != '" . $data['user_id'] . "' and user_type=0");
    $query = $select->result();
    $value = $query[0]->value;
    $promo = $query[0]->promo_code;
    $provider = $query[0]->id;
    if ($promo != "")
    {
      $isNew = $this->db->query("SELECT * from tbl_jobs where user_id='" . $data['user_id'] . "' and status=4")->row();
      if (!empty($isNew))
      {
        return "not";
      }
      else
      {
        $select = $this->db->query("SELECT * FROM tbl_promocode WHERE promocode_beneficiary_id = '" . $data['user_id'] . "' AND cupon_type = '1'");
        $query = $select->result();
        if ($query[0]->id == "")
        {
          $insert = $this->db->query("INSERT INTO tbl_promocode (promocode,promocode_provider_id,promocode_beneficiary_id,value, cupon_type, status,date_created) VALUES('" . $promo . "','" . $provider . "','" . $data['user_id'] . "', $value,'1','0',NOW())");
          /*-----------------*/
          $this->db->query("UPDATE tbl_users SET `wallet_balance`=`wallet_balance`+'".$value."' WHERE id='".$data['user_id']."'");

          $wb = $this->db->select('wallet_balance')->from('tbl_users')->where('id',$data['user_id'])->get()->row();
          // print_r($wb);die;
          $this->db->query("INSERT INTO `tbl_payment`(`job_id`, `user_id`, `payment_type`, `payment_status`, `payment_method`, `payment_RefID`, `amount`, `wallet_balance`, `date_created`) VALUES (0,$data[user_id],2,100,4,'$promo',$value,$wb->wallet_balance,now())");
          return "refferral";
        }
        else
        {
          return "exist";
        }
      }
    }
    else
    { //for promo
      $sql = $this->db->query("SELECT * from tbl_coupon where promo_code = '" . $data['promo_code'] . "'")->row();
      // print_r($sql);die;
      if (!empty($sql))
      {
        $promocode = $sql->promo_code;
        $dsc_type = $sql->amt_type;
        $value = ($dsc_type==1) ? $sql->disc_amt : $sql->disc_percent ;
        $promo_id = $sql->id;

        $select = $this->db->query("SELECT * FROM tbl_promocode WHERE promocode_beneficiary_id = '" . $data['user_id'] . "' AND promocode = '" . $data['promo_code'] . "'");
        $query = $select->result();
        // print_r($query);die;
        if ($query[0]->id == "")
        {
          $this->db->where(array('promocode_beneficiary_id'=>$data['user_id'],'cupon_type'=>2))->update('tbl_promocode', array('status' => '0'));

          $insert = $this->db->query("INSERT INTO tbl_promocode (promocode,promocode_beneficiary_id,value,dsc_type,promo_id,cupon_type,status,job_id,date_created) VALUES('$promocode','" . $data['user_id'] . "',$value,$dsc_type,$promo_id,'2',2,".$data['job_id'].",NOW())");
          $info = $this->db->query("SELECT * FROM tbl_coupon WHERE promo_code = '" . $data['promo_code'] . "'")->row();
          return $info;
        }else{
          $this->db->query("UPDATE tbl_promocode set status=2,job_id=".$data['job_id']." WHERE promocode_beneficiary_id = '" . $data['user_id'] . "' AND promocode = '" . $data['promo_code'] . "'");
          $info = $this->db->query("SELECT * FROM tbl_coupon WHERE promo_code = '" . $data['promo_code'] . "'")->row();
          return $info;
        }
      }
      else
      {
        return false;
      }
    }
  }

  public function check_referral($data)
  {
    $select = $this->db->query("SELECT id,promo_code, (select value from tbl_cupon_value where cupon_type ='3') as value FROM tbl_users WHERE user_type=2 and promo_code = '" . $data['promo_code'] . "'");
    $query = $select->result();
    return $query;
  }

  public function apply_driver_ref($data)
  {
    $select = $this->db->query("SELECT id,promo_code, (select value from tbl_cupon_value where cupon_type ='3') as value FROM tbl_users WHERE user_type=2 and promo_code = '" . $data['promo_code'] . "' AND id != '" . $data['user_id'] . "'");
    $query = $select->result();
    $value = $query[0]->value;
    $promo = $query[0]->promo_code;
    $provider = $query[0]->id;
    if ($promo != "")
    {
      $isNew = $this->db->query("SELECT * from tbl_jobs where user_id='" . $data['user_id'] . "' and status=4")->row();
      if (!empty($isNew))
      {
        return "not";
      }
      else
      {
        $select = $this->db->query("SELECT * FROM tbl_promocode WHERE promocode_beneficiary_id = '" . $data['user_id'] . "' AND cupon_type = '3'");
        $query = $select->result();
        if ($query[0]->id == "")
        {
          $insert = $this->db->query("INSERT INTO tbl_promocode (promocode,promocode_provider_id,promocode_beneficiary_id,value, cupon_type, status,date_created) VALUES('" . $promo . "','" . $provider . "','" . $data['user_id'] . "', $value,'1','0',NOW())");
          /*-----------------*/
          $this->db->query("UPDATE tbl_users SET `wallet_balance`=`wallet_balance`+'".$value."' WHERE id='".$data['user_id']."'");

          $wb = $this->db->select('wallet_balance')->from('tbl_users')->where('id',$data['user_id'])->get()->row();
          // print_r($wb);die;
          $this->db->query("INSERT INTO `tbl_payment`(`job_id`, `user_id`, `payment_type`, `payment_status`, `payment_method`, `payment_RefID`, `amount`, `wallet_balance`, `date_created`) VALUES (0,$data[user_id],2,100,4,'$promo',$value,$wb->wallet_balance,now())");
          return "refferral";
        }
        else
        {
          return "exist";
        }
      }
    }
  }

  public

  function select_cupon($where)
  {

    // print_r($this->input->get());die;

    if ($this->input->get('job_id') != "")
    {
      $job_id = $this->input->get('job_id');
    }
    else
    {
      $job_id = 0;
    }

    // print_r($where['promocode_beneficiary_id']);die;

    /*----deselect previous----*/
    $this->db->where(array('promocode_beneficiary_id' => $where['promocode_beneficiary_id'], 'status' => '2', 'cupon_type'=>2))->update('tbl_promocode', array('status' => '0'));

    /*-----select---------*/
    $this->db->where($where)->update('tbl_promocode', array('status' => '2','job_id' => $job_id));

    $select_cupon = $this->db->select('*')->from('tbl_promocode')->where($where)->where('status', '2')->get()->result();

    // print_r($select_cupon);die;

    return $select_cupon;
  }

  public

  function customer_support($data)
  {
    $insert = $this->db->query("INSERT INTO tbl_query (user_id,name,email,query,date_created) VALUES('" . $data['user_id'] . "','" . $data['name'] . "','" . $data['email'] . "','" . $data['query'] . "',NOW())");
    return true;
  }

  public

  function updateprofile($message)
  {

    // print_r($message);
    // die();

    if ($message['imagename'] != "")
    {
      $image = $this->config->base_url() . 'public/profilePic/' . $message['imagename'];
      $profile_pic = ", profile_pic='$image'";
    }
    else
    {
      $profile_pic = "";
    }

    $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
    $update = $this->db->query("UPDATE tbl_users set name = '" . $name . "', first_name = '" . $message['first_name'] . "', last_name = '" . $message['last_name'] . "', dob = '" . $message['dob'] . "', email = '" . $message['email'] . "', gender = '" . $message['gender'] . "' $profile_pic where id='" . $message['user_id'] . "'");

    if (isset($_POST['user_type']) && ($_POST['user_type'] == 2))
    {
      $name = $this->input->post('first_name') . " " . $this->input->post('last_name');
      $this->db->query("UPDATE tbl_users set name = '" . $name . "', first_name  = '" . $this->input->post('first_name') . "', last_name = '" . $this->input->post('last_name') . "', dob = '" . $this->input->post('dob') . "',  email = '" . $message['email'] . "' where id='" . $message['user_id'] . "'");
    }

    $query = $this->db->get_where('tbl_users', array(
      'id' => $message['user_id']
    ))->result();

    // $query = $this->db->get_where('tbl_users', array('id' => $message['user_id']))->result();

    if ($query)
    {
      return 1;
    }
    else
    {
      return 0;
    }
  }

  public

  function viewprofile($user_id)
  {

    // print_r($user_id);

    $select_profile = $this->db->query("SELECT id, name,first_name,last_name, profile_pic, email, dob, gender, phone, wallet_balance from tbl_users where id = '" . $user_id . "'");
    $profile_result = $select_profile->result();

    // print_r($profile_result);die;

    $result['userdata'] = $profile_result;
    if (!empty($result['userdata']))
    {
      return $result;
    }
    else
    {
      return "";
    }
  }

  public

  function ride_status($data)
  {

    //  print_r($data);
    // die;

    $select = $this->db->query("SELECT estimate,way_points,track_url,pickup_location,is_waiting,driver_id,dropoff_location,pickup_lat,pickup_long,dropoff_lat,dropoff_long, status,fare, distance, payable_amount, job_start_datetime, job_completed_datetime, waiting_time,is_waiting,payment_method from tbl_jobs where id = '" . $data['job_id'] . "'")->result();
    if ($select[0]->status == '6')
    {
      return 0;
    }
    elseif ($select[0]->status != '0')
    {
      $query = $this->db->query("SELECT tbl_driver_vehicle_info.user_id,name, phone, gender, profile_pic, latitude, longitude, vehicle_type, vehicle_model, registration_number, state_identifier, vehicle_registration_plate_image, rating FROM tbl_users 
                JOIN tbl_driver_vehicle_info ON (tbl_users.id = tbl_driver_vehicle_info.user_id) 
                JOIN tbl_driver_documents ON (tbl_users.id = tbl_driver_documents.user_id)
                WHERE tbl_users.id = '" . $select[0]->driver_id . "' ")->result();
      return $result = array_merge($select, $query);
    }

    return 1;

    // print_r($select);
    // die;

  }

  public

  function cancel_ride($data)
  {

    // echo "string";die;
    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    $data = $this->db->escape($data);
    $this->db->trans_start();
    if ($data['user_type'] == "'0'")
    {

      // print_r($data); die;

      $selectJob = $this->db->query("SELECT driver_id,status FROM tbl_jobs WHERE id=".$data['job_id']." ")->row();
      
      if ($selectJob->status!=6) {
        $update = $this->db2->query("UPDATE proute SET Type = '50' WHERE TripNumber = ".$data['job_id']."");
        $update = $this->db->query("UPDATE tbl_jobs SET `status` = '50', is_active = '0',modified_on = NOW(),notified=0 WHERE id = " . $data['job_id'] . " AND user_id = " . $data['client_id'] . " ");
      }
      
      if ($data['flag']==2) {
        $driver_id = $selectJob->driver_id;
        $update = $this->db->query("UPDATE tbl_users SET  push_time = null, is_free = '1' WHERE id = $driver_id ");
      } else {
        $update = $this->db->query("UPDATE tbl_users SET  push_time = null, is_free = '1' WHERE pjob_id = " . $data['job_id'] . " ");
      }
      

      $this->db->query("UPDATE `tbl_response` set response=50,`modifiedOn`=now() WHERE job_id=".$data['job_id']."ORDER BY id DESC LIMIT 1 ");
    }
    elseif ($data['user_type'] == "'2'")
    {
      $update = $this->db2->query("UPDATE proute SET Type = '52' WHERE TripNumber = ".$data['job_id']."");
      $update = $this->db->query("UPDATE tbl_jobs SET status = '52', is_active = '0', modified_on = NOW(),notified=0 WHERE id = " . $data['job_id'] . " AND driver_id = " . $data['driver_id'] . " ");

      // print_r($data['driver_id']);die;

      $update = $this->db->query("UPDATE tbl_users SET push_time = null, is_free = '1' WHERE id = " . $data['driver_id'] . " ");

      $this->db->query("UPDATE `tbl_response` set response=52,`modifiedOn`=now() WHERE job_id=".$data['job_id']." and driver_id=".$data['driver_id']." ");
    }
    $this->db->trans_complete();

    $this->db->query("UPDATE tbl_promocode SET status = '0' WHERE job_id = " . $data['job_id'] . " ");
    return true;
  }

  public

  function check_status($data)
  {
    $phone = $this->db->query("SELECT phone_verified, user_type FROM tbl_users WHERE id = '" . $data . "'")->result();
    $paymentdriver = $this->db->select('*')->where('is_completed_ride', 0)->where('status', 4)->where('driver_id', $data)->get('tbl_jobs')->result();
    $payment1 = $this->db->select('id,driver_id')->where('is_completed_ride', 0)->where('status', 4)->where('user_id', $data)->get('tbl_jobs')->result();
    if ($phone[0]->user_type == 0)
    {

      // die(print_r($phone));
      // echo "SELECT id,driver_id,fare,payable_amount, distance, job_start_datetime, job_completed_datetime, waiting_time FROM tbl_jobs WHERE user_id = '".$data."' AND status = '4' AND payment_status != '100' AND payment_method = '1'";die;

      $payment = $this->db->query("SELECT id,driver_id,fare,payable_amount, distance, job_start_datetime, job_completed_datetime, waiting_time, payment_status, payment_method FROM tbl_jobs WHERE user_id = '" . $data . "' AND status = '4' AND payment_status != '100' AND payment_status != '101' AND (payment_method = '1' OR payment_method=3)")->result();

      // print_r($payment);die;
      // $checkPaymentststus = $this->db->query("SELECT * FROM `tbl_payment` WHERE job_id=")

      $rating = $this->db->select('id,driver_id')->where('rating', NULL)->where('status', 4)->where('user_id', $data)->get('tbl_jobs')->result();
      $paymentarray = array();
      foreach($payment as $pay)
      {
        $checkPaymentstatus = $this->db->query("SELECT * FROM `tbl_payment` WHERE job_id='" . $pay->id . "' and payment_status='100'")->row();

        // print_r($checkPaymentstatus);die;

        if (empty($checkPaymentstatus))
        {
          $paymentarray[] = $pay;
        }
      }

      // die;

      // $ride = $this->db->query("SELECT tbl_jobs.*,tbl_promocode.value FROM tbl_jobs left JOIN tbl_promocode on tbl_jobs.id=tbl_promocode.job_id WHERE user_id = '" . $data . "' AND is_active ='1' OR tbl_jobs.`status`=0 ")->result();
      $ride = $this->db->query("SELECT tbl_jobs.*,tbl_promocode.value FROM tbl_jobs left JOIN tbl_promocode on tbl_jobs.id=tbl_promocode.job_id WHERE user_id = '" . $data . "' AND is_active ='1' ")->result();

      $query = $this->db->query("SELECT tbl_users.id,name, phone, profile_pic, vehicle_type, rating,registration_number, state_identifier, vehicle_registration_plate_image, vehicle_model 
                FROM tbl_users 
                JOIN tbl_driver_vehicle_info ON (tbl_users.id = tbl_driver_vehicle_info.user_id)
                JOIN tbl_driver_documents ON (tbl_users.id = tbl_driver_documents.user_id)
                WHERE tbl_users.id = '" . $ride[0]->driver_id . "' OR tbl_users.id = '" . $payment[0]->driver_id . "' ")->result();
//       print_r($ride);
// print_r($this->db->last_query());die;
      return $data = array(
        'is_verified' => $phone[0]->phone_verified,
        'ongoing_ride' => $ride,
        'driver_info' => $query,
        'pending' => $payment,

        // 'pending'=> $paymentarray,

        'rating' => $rating,
        'serverTime' => date("Y-m-d H:i:s")
      );
    }
    elseif ($phone[0]->user_type == 2)
    {
      $payment = $this->db->query("SELECT * FROM tbl_jobs WHERE driver_id = '" . $data . "' AND status = '4' AND (driver_issue = '0' and driver_rating = '0') ")->result();
      $ride = $this->db->query("SELECT tbl_jobs.*,tbl_promocode.value FROM tbl_jobs
left JOIN tbl_promocode on tbl_jobs.id=tbl_promocode.job_id WHERE driver_id = '" . $data . "' AND is_active ='1' ")->result();
      if (!empty($ride[0]->user_id)) {
        $info = $this->db->query("SELECT id, name, first_name, last_name, latitude, longitude, profile_pic, email, phone,(SELECT device_id FROM `tbl_login` where user_id='" . $ride[0]->user_id . "' order by id desc limit 1) as device_id from tbl_users where id = '" . $ride[0]->user_id . "' ")->result();
      } else {
        $info = [];
      }
      

      return $data = array(
        'is_verified' => $phone[0]->phone_verified,
        'ongoing_ride' => $ride,
        'client_info' => $info,
        'pending' => $paymentdriver,
        'serverTime' => date("Y-m-d H:i:s")
      );
    }
  }

  public

  function set_status($data)
  {

    // print_r($data);die;

    $update = $this->db->query("UPDATE tbl_users SET availability = '" . $data['status'] . "' WHERE id = '" . $data['user_id'] . "'");
    $status = $this->db->query("SELECT availability FROM tbl_users WHERE id = '" . $data['user_id'] . "'")->result();

    // print_r($status[0]->status);die;

    return $data = array(
      'code' => $status[0]->availability,
    );
  }

  public

  function ride_history($data)
  {
    $newarray = array();
    $i = 0;
    if ($data['user_type'] == 2)
    {
      $result['ride_history'] = $this->db->select('a.name as client_name, a.profile_pic as client_pic, a.phone as client_phone, b.name as driver_name, b.phone as driver_phone, b.profile_pic as driver_pic,v.id as vehicle_id, v.vehicle_type, v.base_rate,v.per_km, v.per_min, v.minimum_fare,v.waiting_charge,vi.registration_number, vi.vehicle_model, state_identifier, j.*,(CASE WHEN tbl_promocode.value IS NULL THEN 0 ELSE tbl_promocode.value END ) AS disc_amt')->from('tbl_jobs as j')->join('tbl_users as a', "j.user_id = a.id")->join('tbl_users as b', 'j.driver_id = b.id', 'left')->join('tbl_vehicle as v', 'v.id = j.vehicle_id', 'left')->join('tbl_driver_vehicle_info vi', 'vi.user_id = j.driver_id', 'left')->join('tbl_promocode', 'tbl_promocode.job_id = j.id', 'left')->where("j.driver_id = '" . $data['id'] . "' AND j.status = '4'")->order_by("j.id", "desc")->limit($data['limit'], $data['offset'])->get()->result_array();
      foreach($result['ride_history'] as $value)
      {
        $newarray[$i] = $value;
        /*----------time estimate------------*/
        $date1 = $value['job_start_datetime'];
        $date2 = $value['job_completed_datetime'];

        // echo $date1;die;

        $start_date = new DateTime($date1);
        $end_date = new DateTime($date2);
        $interval = $start_date->diff($end_date);
        $time_taken = $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days, " . $interval->h . ":" . $interval->i . ":" . $interval->s;
        $minutes = ($interval->y * 365 * 24 * 60) + ($interval->m * 30 * 24 * 60) + ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
        $newarray[$i]['time_taken'] = $time_taken;
        $newarray[$i]['minutes'] = $minutes;

        // echo $minutes.'</br>';

        /*--------------Lat long--------------*/
        $newarray[$i]['lat_long'] = $this->db->select('latitude,longitude')->from('tbl_job_distance')->where('job_id', $value['id'])->get()->result_array();
        $i++;
      }

      $result['ride_history'] = $newarray;
    }
    elseif ($data['user_type'] == 0)
    {
      $result['ride_history'] = $this->db->select('a.name as client_name, a.profile_pic as client_pic, a.phone as client_phone, b.name as driver_name, b.phone as driver_phone, b.profile_pic as driver_pic,v.id as vehicle_id, v.vehicle_type, v.base_rate,v.per_km, v.per_min, v.minimum_fare,v.waiting_charge,vi.registration_number, vi.vehicle_model, state_identifier, j.*,(CASE WHEN tbl_promocode.value IS NULL THEN 0 ELSE tbl_promocode.value END ) AS disc_amt')->from('tbl_jobs as j')->join('tbl_users as a', "j.user_id = a.id")->join('tbl_users as b', 'j.driver_id = b.id', 'left')->join('tbl_vehicle as v', 'v.id = j.vehicle_id', 'left')->join('tbl_driver_vehicle_info vi', 'vi.user_id = j.driver_id', 'left')->join('tbl_promocode', 'tbl_promocode.job_id = j.id', 'left')->where("j.driver_id != 0 AND j.user_id = '" . $data['id'] . "' AND (j.status='4'|| j.status ='51' || j.status ='52' || j.status = '50' || j.status = '6')")->order_by("j.id", "desc")->limit($data['limit'], $data['offset'])->get()->result_array();
      foreach($result['ride_history'] as $value)
      {
        $newarray[$i] = $value;
        /*----------time estimate------------*/
        $date1 = $value['job_start_datetime'];
        $date2 = $value['job_completed_datetime'];

        // echo $date1;die;

        $start_date = new DateTime($date1);
        $end_date = new DateTime($date2);
        $interval = $start_date->diff($end_date);
        $time_taken = $interval->y . " years, " . $interval->m . " months, " . $interval->d . " days, " . $interval->h . ":" . $interval->i . ":" . $interval->s;
        $minutes = ($interval->y * 365 * 24 * 60) + ($interval->m * 30 * 24 * 60) + ($interval->d * 24 * 60) + ($interval->h * 60) + $interval->i;
        $newarray[$i]['time_taken'] = $time_taken;
        $newarray[$i]['minutes'] = $minutes;

        // echo $minutes.'</br>';

        /*--------------Lat long--------------*/
        $newarray[$i]['lat_long'] = $this->db->select('latitude,longitude')->from('tbl_job_distance')->where('job_id', $value['id'])->get()->result_array();

        // print_r($newarray);die;

        $i++;
      }

      // die;

      $result['ride_history'] = $newarray;
    }
    else
    {
      return 0;
    }

    return $result;
  }

  public function totalRecords($user_id)
  {
    $totalRecords = $this->db->select('count(j.id) as totalRecords')->from('tbl_jobs as j')->where("j.user_id = '" . $user_id. "' AND (j.status='4' || j.status ='52' || j.status = '50' || j.status = '6')")->get()->row();
    return $totalRecords->totalRecords;
  }

  public

  function restricted_zone()
  {
    $data['even'] = $this->db->get('tbl_even')->result();
    $data['camera'] = $this->db->get('tbl_camera')->result();
    // $data['video'] = $this->db->get('tbl_video')->result();
    $data['tehran'] = $this->db->get('tbl_tehran')->result();
    return $data;
  }

  public

  function update_vehicle($data)
  {
    $vehicle_id = array(
      'vehicle_id' => $data['vehicle_id'],
    );
    $record = $this->db->where('id', $data['user_id'])->update('tbl_users', $vehicle_id);
    return true;

    // print_r($record);die;

  }

  public

  function client_info($data)
  {

    // print_r($data);die;

    $profile = $this->db->select('tbl_users.id, name, first_name, last_name, profile_pic, email, phone, language,pickup_location,dropoff_location');
    $this->db->from('tbl_users');
    $this->db->join('tbl_jobs', "tbl_jobs.id = '" . $data['job_id'] . "'");
    $this->db->where('tbl_users.id', $data['user_id']);
    $prof = $this->db->get();
    $profile_result = $prof->row();

    // print_r($this->db->last_query());die;

    $userdata['user_data'] = $profile_result;

    // $userdata=json_encode($userdata);
    // print_r($userdata);die;

    if (!empty($userdata))
    {
      return $userdata;
    }
    else
    {
      return "";
    }
  }

  public

  function ride_details($data)
  {
    $client_id = $this->db->select('user_id');
    $this->db->from('tbl_jobs');
    $this->db->where('tbl_jobs.id', $data['job_id']);
    $prof = $this->db->get();
    $client_id = $prof->row();

    // print_r($client_id->user_id);die;

    if (!empty($client_id))
    {
      $profile = $this->db->select('tbl_users.id, name, first_name, last_name, latitude,longitude, profile_pic, email, phone, language,pickup_location,dropoff_location,pickup_lat,pickup_long,dropoff_lat,dropoff_long,way_points,tbl_jobs.track_url,tbl_jobs.destination_changed,tbl_jobs.blue_mode,(SELECT device_id FROM `tbl_login` where user_id='.$client_id->user_id.' order by id desc limit 1) as device_id');
      $this->db->from('tbl_users');
      $this->db->join('tbl_jobs', "tbl_jobs.user_id = tbl_users.id");

      // $this->db->join('tbl_login', "tbl_login.user_id = tbl_users.id");

      $this->db->where('tbl_jobs.id', $data['job_id']);
      $this->db->where('tbl_users.id', $client_id->user_id);
      $prof = $this->db->get();
      $profile_result = $prof->row();
      $userdata['user_data'] = $profile_result;

      // print_r($userdata);die;

      return $userdata;
    }
    else
    {
      return "";
    }
  }

  public

  function push_to($data)
  {
    $query = $this->db->select('token_id,device_id');
    $this->db->from('tbl_login');
    /*=========================   To get token id of client ======================================*/
    if ($data['user_type'] == '2')
    {

      // print_r($data);die;

      $this->db->join('tbl_jobs', "tbl_login.user_id = tbl_jobs.user_id");
    }

    /*=========================   To get token id of Driver ======================================*/
    elseif ($data['user_type'] == '0')
    {
      $this->db->join('tbl_jobs', "tbl_login.user_id = tbl_jobs.driver_id");
    }

    $this->db->where('tbl_jobs.id', $data['job_id']);
    $this->db->where('tbl_login.status', 1);
    $result = $this->db->get();
    $result = $result->row();
    return $result;
  }

  public

  function getUserLogin($user_id)
  {

    // $query = $this->db->select('tbl_login.*,tbl_users.*,tbl_login.device_id as mb_device_id,tbl_login.token_id
    // as mb_token_id');
    // $query = $this->db->select('tbl_login.*,tbl_users.*,tbl_login.device_id as mb_device_id,tbl_login.token_id as mb_token_id');

    $query = $this->db->select('tbl_login.device_id as mb_device_id,tbl_login.token_id as mb_token_id');
    $this->db->from('tbl_login');
    /*=========================   To get token id of client ======================================*/

    // $this->db->join('tbl_users', "tbl_login.user_id = tbl_users.id","left");
    // $this->db->join('tbl_users', "tbl_login.user_id = tbl_users.id","inner");

    $this->db->where('tbl_login.user_id', $user_id);
    $this->db->where('tbl_login.status', 1);
    $this->db->order_by('id', 'desc');
    $result = $this->db->get();
    $result = $result->result();

    // print_r($result);die;

    return $result;
  }

  /*-----------------------------------------DRY--------------------------------------------*/
  public

  function insertdata($table_name, $data)
  {
    $this->db->insert($table_name, $data);
  }

  public

  function updatedata($table_name, $where, $data)
  {
    $this->db->where($where);
    $this->db->update($table_name, $data);
    return 1;
  }

  public

  function selectdata($table_name, $select, $where = NULL, $or_where = NULL, $order = NULL, $by = NULL, $limit = NULL)
  {
    $this->db->select($select);
    $this->db->from($table_name);
    if ($limit != "")
    {
      $this->db->limit($limit, 0);
    }

    if ($where != "")
    {
      $this->db->where($where);
    }

    if ($or_where != "")
    {
      $this->db->or_where($or_where);
    }

    if ($order != "")
    {
      $this->db->order_by($order, $by);
    }

    $query = $this->db->get()->result();

    // print_r($this->db->last_query());die;

    return $query;
  }

  public

  function listData($where)
  {
    $sel_query = $this->db->query("select * from tbl_promocode where promocode_beneficiary_id = '" . $where['user_id'] . "' and status != '1'")->result_array();
    return $sel_query;
  }

  public

  function selectJob($id)
  {

    // print_r($id['insert_id']);die();

    $sqlQuery = $this->db->query("select id from tbl_jobs where user_id =" . $id['insert_id'] . " order by id desc")->result();
    if (!empty($sqlQuery))
    {
      $job_id = $sqlQuery[0]->id;
      return $job_id;
    }
    else
    {
      return 1;
    }
  }

  public

  function getWhere($column, $tableName, $where, $limit = NULL)
  {

    // echo "sdds";die;

    if ($limit == "1")
    {
      $query = $this->db->select($column)->from($tableName)->where($where)->get()->row();
    }
    else
    {
      $query = $this->db->select($column)->from($tableName)->where($where)->get()->result();
    }

    return $query;
  }

  public

  function select_by_query($query)
  {
    $result_query = $this->db->query($query)->result();
    return $result_query;
  }

  /*--------------------------------------DRY------------------------------------*/
  public

  function fare_cal($data)
  {

    // print_r($data);die;

    $query = $this->db->query("SELECT * FROM `tbl_vehicle`
        where tbl_vehicle.id = (select vehicle_id from tbl_driver_vehicle_info where tbl_driver_vehicle_info.user_id = (select driver_id from tbl_jobs where id = '" . $data['job_id'] . "') )")->row();

    // print_r($query);die;

    return $query;
  }

  public

  function verify_password($data)
  {
    $result = $this->db->get_where('tbl_users', $data)->result();
    if ($result['0']->id == $data['id'])
    {
      return '1';
    }
    else
    {
      return '0';
    }

    // print_r($result['0']->id);die;

  }

  public

  function update_address($data, $id)
  {
    $this->db->where('id', $id)->update('tbl_users', $data);
    $result = $this->db->select('home_address,work_address,home_address_farsi,work_address_farsi,home_latlng,work_latlng')->from('tbl_users')->where('id', $id)->get()->row();
    return $result;
  }

  public

  function last_ride($id, $type)
  {

    // print_r($type);die;
    // $result = array();
    ini_set('memory_limit', '256M');
    if ($type == "2")
    {
      $result['ride_info'] = $this->db->query("SELECT tbl_jobs.*, tbl_vehicle.vehicle_type, vehicle_model, base_rate, per_km, per_min, (select registration_number FROM tbl_driver_vehicle_info WHERE user_id = driver_id) as registration_number, (select state_identifier FROM tbl_driver_vehicle_info WHERE user_id = driver_id) as state_identifier FROM `tbl_jobs` left JOIN tbl_vehicle on tbl_vehicle.id = tbl_jobs.vehicle_id left JOIN tbl_driver_vehicle_info on tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id where tbl_jobs.`status`>3 and tbl_jobs.`status`!=7 and driver_id = '" . $id . "' ORDER BY tbl_jobs.id DESC LIMIT 1")->row();

      // print_r($result);die;

      $result['client_info'] = $this->db->query("SELECT id,name, first_name, last_name, phone, profile_pic FROM tbl_users WHERE tbl_users.id = '" . $result['ride_info']->user_id . "'")->result();
    }
    else
    {
      $result['ride_info'] = $this->db->query("SELECT tbl_jobs.*, tbl_vehicle.vehicle_type, vehicle_model, base_rate, per_km, per_min, (select registration_number FROM tbl_driver_vehicle_info WHERE user_id = driver_id) as registration_number, (select state_identifier FROM tbl_driver_vehicle_info WHERE user_id = driver_id) as state_identifier FROM `tbl_jobs` left JOIN tbl_vehicle on tbl_vehicle.id = tbl_jobs.vehicle_id left JOIN tbl_driver_vehicle_info on tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id where tbl_jobs.`status`>3 and tbl_jobs.`status`!=7 and tbl_jobs.user_id = '" . $id . "' ORDER BY tbl_jobs.id DESC LIMIT 1")->row();

      // print_r($result);die;
      if (!empty($result['ride_info'])) {
        $result['driver_info'] = $this->db->query("SELECT id,name, first_name, last_name, phone, profile_pic FROM tbl_users WHERE tbl_users.id = '" . $result['ride_info']->driver_id . "'")->result();
      }
    }
    if (!empty($result['ride_info'])) {
      $result['latlng'] = $this->db->get_where('tbl_job_distance', $result['ride_info']->id)->result();
    }
    // print_r($result);die;

    return $result;
  }

  public

  function issue_list($data = NULL)
  {
    if ($data == NULL)
    {
      $result = $this->db->query("SELECT * FROM `tbl_issue_list`")->result();
      return $result;
    }
    else
    {
      $result = $this->db->get_where('tbl_issue_list', $data)->result();
      return $result;
    }
  }

  public

  function get_points($job_id)
  {
    $result = $this->db->get_where('tbl_job_distance', $job_id)->result();
    return $result;
  }

  public

  function submit_issue($data)
  {
    if ($data['user_type'] == '2')
    {
      $result = $this->db->where('id', $data['id'])->update('tbl_jobs', array(
        'DissueTitle' => $data['issueTitle'],
        'driver_comment' => $data['comment']
      ));
      return $result;
    }
    else
    {
      $result = $this->db->where('id', $data['id'])->update('tbl_jobs', array(
        'CissueTitle' => $data['issueTitle'],
        'client_comment' => $data['comment']
      ));
      return $result;
    }
  }

  function distance($lat1, $lon1, $lat2, $lon2, $unit)
  {

    // $distance =  6371 * acos ( cos ( radians($lat1) ) * cos( radians( $lat2 ) ) * cos( radians( $lon2 ) - radians($lon1) ) + sin ( radians($lat1) ) * sin( radians( $lon2 ) ) );
    // print_r($distance);die;
    // print_r($lat1);print_r($lat2);die;

    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);

    // print_r($miles * 1.609344);die;

    if ($unit == "K")
    {
      return ($miles * 1.609344);
    }
    else
    if ($unit == "N")
    {
      return ($miles * 0.8684);
    }
    else
    {
      return $miles;
    }
  }

  public

  function job_details($job_id)
  {
    $find = $this->db->select('id')->where('id', $job_id)->get('tbl_jobs')->result();

    // print_r($find);die;
    // $c = count($find);
    // print_r($c);die;

    if (count($find) > 0)
    {
      $query = $this->db->query("SELECT b.name as client_name, b.first_name as client_first_name, b.last_name as client_last_name, b.profile_pic as client_pic,b.phone as client_phone, c.name as driver_name, c.first_name as driver_first_name, c.last_name as driver_last_name, c.phone as driver_phone,c.profile_pic as driver_pic,tbl_driver_vehicle_info.vehicle_id as vehicle,  vehicle_model, tbl_vehicle.vehicle_type,tbl_vehicle.base_rate,tbl_vehicle.per_km,tbl_vehicle.per_min,tbl_driver_vehicle_info.registration_number,state_identifier, tbl_driver_documents.vehicle_registration_plate_image, a.*,(CASE WHEN a.rating IS NULL THEN '' ELSE a.rating END ) AS rating, (CASE WHEN tbl_promocode.dsc_type=1 THEN tbl_promocode.value ELSE (tbl_promocode.value/100)*fare END ) AS disc_amt
                FROM tbl_jobs a 
                join tbl_users b on b.id = a.user_id 
                left join tbl_users c on c.id = a.driver_id 
                left join tbl_promocode on a.id=tbl_promocode.job_id
                left join tbl_driver_vehicle_info on tbl_driver_vehicle_info.user_id = a.driver_id 
                left join tbl_vehicle on tbl_vehicle.id = tbl_driver_vehicle_info.vehicle_id 
                left join tbl_driver_documents on a.driver_id = tbl_driver_documents.user_id 
                WHERE a.id = $job_id")->result();

      // print_r($query);die;

      $query[0]->lat_long = $this->db->select('latitude,longitude')->from('tbl_job_distance')->where('job_id', $query[0]->id)->get()->result();

      // print_r($query);die;

      return $query;
    }
    else
    {
      return 0;
    }
  }

  public

  function prev_rating($driver_id)
  {
    $result = $this->db->query("SELECT tbl_jobs.rating, tbl_jobs.feedback, tbl_users.name, tbl_users.profile_pic FROM `tbl_jobs` left join tbl_users on tbl_users.id = tbl_jobs.user_id where driver_id = $driver_id and tbl_jobs.rating > 0")->result();
    return $result;
  }

  public

  function chat_history($data)
  {

    // print_r($data);die;

    $query = $this->db->query("SELECT * FROM `tbl_chat` where (job_id = '" . $data['job_id'] . "' and from_id = '" . $data['from_id'] . "' and to_id = '" . $data['to_id'] . "') or (job_id = '" . $data['job_id'] . "' and from_id = '" . $data['to_id'] . "' and to_id = '" . $data['from_id'] . "')")->result();

    // print_r($query);die;

    return $query;
  }

  public

  function busy_foult()
  {

    // echo "string";


    // $insert = $this->db->query("INSERT into test set time = NOW()");
    // print_r($query);die;

    /*---for auto logout more than 3 refusal---*/
      /*$message = $this->db->query("SELECT (select COUNT(`tbl_response`.`id`) from tbl_response where driver_id=tbl_login.user_id and `tbl_login`.`date_created` < `tbl_response`.`date_created` and  `tbl_login`.status=1) as `data`,`tbl_login`.user_id,`tbl_login`.unique_device_id,`tbl_login`.`token_id` from `tbl_login` join `tbl_response`on (`tbl_login`.`user_id`=`tbl_response`.`driver_id`) where `tbl_login`.`date_created` < `tbl_response`.`date_created` and  `tbl_login`.status=1 group by user_id ORDER by tbl_login.id desc")->result_array();

      //     echo "<pre>";
      // print_r($message);die;

      foreach($message as $mess)
      {
          // $val = $this->logout($mess);
        if ($mess['data'] >= 3)
        {

          $val = $this->logout($mess);
          $logData = array('user_id' => $mess['user_id'],'logedOutBy'=>'cron','logedOutOn'=>date("Y-m-d H:i:s") );
          $this->User_model->insertdata('tbl_logout', $logData);

          // print_r($mess);die;
          // $info = array();
          // $this->User_model->set_status();
        }
      }*/
    /*---auto logout end---*/

    /*--------auto free-----------*/
    
    $q = $this->db->select('value')->from('tbl_appSettings')->where('name','requestTime')->get()->row();
    $requestTime = $q->value;
    $cronTime = $requestTime+2;
    $query = $this->db->query("SELECT tbl_users.id, pjob_id FROM `tbl_users` where push_time is not null and TIMESTAMPDIFF(SECOND,`push_time`,now()) > $cronTime")->result();
    // print_r($query);die;
    if (count($query) > 0)
    {
      for ($i = 0; $i < count($query); $i++)
      {
        foreach($query[$i] as $key => $value)
        {

          // echo "$value";

          $update = $this->db->query("UPDATE `tbl_users` set push_time = null, is_free = '1' WHERE id = '" . $value . "' ");
        }
      }
    }

    $freethem = $this->db->query("SELECT tbl_users.id from tbl_users  join tbl_jobs on pjob_id=tbl_jobs.id where is_active=0 and is_free=0 and push_time is null")->result();
    $cnt = count($freethem[0]);
    foreach ($freethem[0] as $key => $value) {
      $ids .= $value;
      if ($cnt!=$key+1) {
        $ids .= ",";
      }
    }
    $this->db->query("UPDATE `tbl_users` set is_free = '1' WHERE id IN('".$ids."') ");

    return $query;
  }

  public

  function push_info($id)
  {

    $q = $this->db->select('value')->from('tbl_appSettings')->where('name','requestTime')->get()->row();
    $requestTime = $q->value;
    $query = $this->db->query("SELECT tbl_users.pjob_id, $requestTime-TIMESTAMPDIFF(SECOND,tbl_users.`push_time`,now()) as time, tbl_jobs.user_id, tbl_jobs.pickup_location, tbl_jobs.dropoff_location, tbl_jobs.estimate, tbl_jobs.destination_changed , b.name, b.profile_pic, b.email, b.phone, b.language FROM tbl_users join tbl_jobs on tbl_jobs.id = tbl_users.pjob_id join tbl_users b on b.id = tbl_jobs.user_id where tbl_users.id = $id and tbl_users.push_time != 'NULL'")->result();

    // print_r($this->db->last_query()); die;

    return $query;
  }

  public

  function job_status($job_id, $driver_id=null)
  {

    // print_r($driver_id);die;

    if ($driver_id != "")
    {
      $status = $this->db->query("SELECT status,payment_status,user_id FROM `tbl_jobs` where driver_id = $driver_id  ORDER BY `id` desc limit 1")->row();

      // echo "<pre>";
      // print_r($status);
      // die();

      return $status;
    }
    else
    {
      return $this->db->select('status,payment_status')->from('tbl_jobs')->where('id', $job_id)->get()->row();
    }
  }

  public function job_status2($id)
  {
    $status = $this->db->query("SELECT status,payment_status,user_id FROM `tbl_jobs` where id = $id  ORDER BY `id` desc limit 1")->row();
    return $status;
  }

  public

  function set_arriving()
  {

    // print_r("dsfs");die;
    $CI = & get_instance();
    $this->db2 = $CI->load->database('otherdb', TRUE);
    
    $query = $this->db->query("SELECT tbl_jobs.id, ROUND(6371 * acos( cos( radians(pickup_lat) ) * cos( radians( latitude ) ) * cos( radians( pickup_long ) - radians(longitude) ) + sin( radians(pickup_lat) ) * sin( radians( latitude ) ) ),3) AS DISTANCE from tbl_jobs join tbl_users on tbl_users.id = tbl_jobs.driver_id where tbl_jobs.status = 1 having distance < 0.300")->result();

    // print_r($query);

    if ($query != "")
    {
      foreach($query as $value)
      {

        // print_r($value->id);
        // echo "</br>";
        $update = $this->db2->query("UPDATE proute SET Type = '7' WHERE TripNumber = ".$value->id."");
        $datetime = date("Y-m-d H:i:s");
        $this->db->where('id', $value->id)->update('tbl_jobs', array(
          'status' => 7,
          'modified_on' => $datetime,
          'notified' => 0
        ));
      }
    }
  }

  public

  function find_job_id($id)
  {
    $query = $this->db->select('id')->from('tbl_jobs')->where('user_id', $id)->order_by('id', 'desc')->get()->row();
    return $query;
  }

  public

  function add_wallet($tbl_name, $id, $amount)
  {
    $query = $this->db->query("UPDATE $tbl_name set `wallet_balance` = `wallet_balance` + $amount WHERE `id` = $id");
  }

  public

  function sub_wallet($tbl_name, $id, $amount)
  {
    $query = $this->db->query("UPDATE $tbl_name set `wallet_balance` = `wallet_balance` - $amount WHERE `id` = $id");
  }

  public

  function payment($id)
  {
    $query = $this->db->select('tbl_payment.*,wallet_balance')->from('tbl_payment')->JOIN('tbl_users', 'tbl_users.id=tbl_payment.user_id')->where('user_id', $id)->order_by('id', 'desc')->get()->row();
    return $query;
  }

  public

  function findAdmin()
  {
    return $this->db->select('id')->where('user_type', '1')->get('tbl_managers')->row();
  }

  public

  function deleteWhere($tbl_name, $where)
  {
    $this->db->delete($tbl_name, $where);
  }

  public

  function loginData($data)
  {
    $online = $this->db->query("UPDATE `tbl_users` set availability= '1' WHERE id = $data[user_id]");

    // print_r($online);die;
    $update1 = $this->db->query("UPDATE tbl_login set status = '0', last_logout=now() where user_id=$data[user_id] ORDER BY `id` DESC LIMIT 1");
    
    $insert = $this->db->query("INSERT into tbl_login(user_Id,token_id,device_id,unique_device_id,status,date_created) values ($data[user_id], '" . $data['token_id'] . "', '" . $data['device_id'] . "', '" . $data['unique_device_id'] . "', 1 ,NOW())");
    $data['user_info'] = $this->db->select('*')->where('id', $data[user_id])->get('tbl_users')->result();
    if ($data['user_info'][0]->user_type == 0)
    {
      $vehicle_id = $data['user_info'][0]->vehicle_id;
      $data['vehicle_info'] = $this->db->get_where('tbl_vehicle', array(
        'id' => $vehicle_id
      ))->result();

      // print_r($data['user_info']);die;

    }
    elseif ($data['user_info'][0]->user_type == 2)
    {
      $driver_id = $data['user_info'][0]->id;
      $data['vehicle_info'] = $this->db->select('vehicle_type')->from('tbl_driver_vehicle_info')->WHERE('user_id', $driver_id)->get()->result();
    }
    else
    {
      $data['vehicle_info'] = "not found";
    }

    /*===============  To get ride status on login   */
    if ($data['user_info'][0]->user_type == 0)
    {
      $data['ride_status'] = $this->check_status($data['user_info'][0]->id);

      // print_r($data['row'][0]->id);die;

    }
    elseif ($data['user_info'][0]->user_type == 2)
    {
      $data['ride_status'] = $this->check_status($data['user_info'][0]->id);
    }

    return $data;
  }

  public

  function checkPosition($data)
  {
    $latitude = $data['pickup_lat'];
    $longitude = $data['pickup_long'];
    $dropoff_lat = $data['dropoff_lat'];
    $dropoff_long = $data['dropoff_long'];
    /*------------------------------------------*/
    $vertices_x = array(); // x-coordinates of the vertices of the polygon
    $lat = $this->db->select("latitude")->get("tbl_polygon1")->result();
    foreach($lat as $key => $value)
    {
      foreach($value as $key => $lat)
      {
        array_push($vertices_x, $lat);
      }
    }

    $vertices_y = array(); // y-coordinates of the vertices of the polygon
    $long = $this->db->select("longitude")->get("tbl_polygon1")->result();
    foreach($long as $key => $value)
    {
      foreach($value as $key => $long)
      {
        array_push($vertices_y, $long);
      }
    }

    $points_polygon = count($vertices_x); // number vertices
    function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude_x, $longitude_y)
    {
      $i = $j = $c = 0;
      for ($i = 0, $j = $points_polygon - 1; $i < $points_polygon; $j = $i++)
      {
        if ((($vertices_y[$i] > $longitude_y != ($vertices_y[$j] > $longitude_y)) && ($latitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($longitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]))) $c = !$c;
      }

      return $c;
    }

    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $latitude, $longitude))
    {
      $rz = 1;
    }
    else $rz = 0;
    if (is_in_polygon($points_polygon, $vertices_x, $vertices_y, $dropoff_lat, $dropoff_long))
    {
      $drz = 1;
    }
    else $drz = 0;

    // -------------------------------

    $vertices_x_oe = array(); // x-coordinates of the vertices of the polygon
    $lat2 = $this->db->select("latitude")->get("tbl_polygon2")->result();
    foreach($lat2 as $key => $value)
    {
      foreach($value as $key => $lat)
      {
        array_push($vertices_x_oe, $lat);
      }
    }

    $vertices_y_oe = array(); // y-coordinates of the vertices of the polygon
    $long2 = $this->db->select("longitude")->get("tbl_polygon2")->result();
    foreach($long2 as $key => $value)
    {
      foreach($value as $key => $long)
      {
        array_push($vertices_y_oe, $long);
      }
    }

    $points_polygon_oe = count($vertices_x_oe); // number vertices
    if (is_in_polygon($points_polygon_oe, $vertices_x_oe, $vertices_y_oe, $latitude, $longitude))
    {
      $oe = 1;
    }
    else $oe = 0;
    if (is_in_polygon($points_polygon_oe, $vertices_x_oe, $vertices_y_oe, $dropoff_lat, $dropoff_long))
    {
      $doe = 1;
    }
    else $doe = 0;

    // echo "$rz";
    // echo "$drz";
    // echo "\n";
    // echo "$oe";
    // echo "$doe";
    // die;

    $result = array(
      'rz' => $rz,
      'drz' => $drz,
      'oe' => $oe,
      'doe' => $doe
    );
    return $result;
    /*----------------------------------*/
  }

  public

  function driverPermit($permit, $id)
  {
    $result = $this->db->query("SELECT a.id,di.vehicle_permit_type FROM `tbl_users` as a join tbl_driver_vehicle_info as di on di.user_id=a.id where a.id not in(select driver_id from tbl_permit where now() between `from` and `to`) and di.vehicle_permit_type='$permit' and a.id=$id")->row();

    // echo $this->db->last_query();
    // print_r($result);die;

    return $result;
  }

  public

  function permited($id)
  {
    $result = $this->db->query("SELECT a.id,di.vehicle_permit_type FROM `tbl_users` as a join tbl_driver_vehicle_info as di on di.user_id=a.id where (a.id in(select driver_id from tbl_permit where now() between `from` and `to`) or di.vehicle_permit_type='A') and a.id=$id")->row();

    // echo $this->db->last_query();
    // print_r($result);die;

    return $result;
  }

  public

  function driverCommission($driver_id)
  {
    $result = $this->db->query("SELECT cl.commissionPrcnt FROM `tbl_commission` c JOIN `tbl_commissionLevels` cl on cl.id=c.commission where c.driver_id=$driver_id and((forever=0 and now() BETWEEN commission_from and commission_to)or(forever=1 and now()>=commission_from))")->row();
      $commission = $result->commissionPrcnt;
    if (empty($result))
    {
      $result = $this->db->query("SELECT commission FROM `tbl_commission` where driver_id = 'default'")->row();
      $commission = $result->commission;
    }

    return $commission;
  }

  public function autoDeactivate()
  {
      // echo "st";die;
      // $query = $this->db->query("SELECT `tbl_users`.id, `name`, `first_name`, `last_name`, `email` FROM `tbl_users` JOIN `tbl_driver_vehicle_info` ON `tbl_users`.`id` = `tbl_driver_vehicle_info`.`user_id` JOIN `tbl_driver_documents` ON `tbl_driver_documents`.`user_id` = `tbl_users`.`id` WHERE (`insurance_exp_date` < '2016-07-05') OR (`driver_license_exp_date` < '2016-07-05') OR (`registration_exp_date` < '2016-07-05')")->result();
      
      $this->db->query("UPDATE `tbl_users` JOIN `tbl_driver_vehicle_info` ON `tbl_users`.`id` = `tbl_driver_vehicle_info`.`user_id` JOIN `tbl_driver_documents` ON `tbl_driver_documents`.`user_id` = `tbl_users`.`id` set `tbl_users`.`status` = 0 WHERE `insurance_exp_date` < now() OR `driver_license_exp_date` < now() OR `registration_exp_date` < now()");
      // echo "<pre>"; print_r($query); echo "</pre>";
  }

  function add_driver($md5_pass, $profile_pic, $license_pic, $registration_pic, $insurance_pic, $vehicle_pic, $document, $registration_plate_pic)
  {

    // echo "<pre>"; print_r($_POST); echo "</pre>"; die;

    $vehicle_id = $this->input->post('vehicle_type');
    $vehicle = $this->db->select('vehicle_type')->from('tbl_vehicle')->where('id', $vehicle_id)->get()->row();
    $vehicle_type = $vehicle->vehicle_type;

    // print_r($vehicle_type);die;

    $status = '0';
    $user_type = 2;
    $date_created = date('Y-m-d H:i:s');
    $this->db->trans_start();

    $query = $this->db->insert('tbl_users', array(
      'first_name' => $this->input->post('fname') ,
      'last_name' => $this->input->post('lname') ,
      'name' => $this->input->post('fname') . " " . $this->input->post('lname') ,
      'gender' => $this->input->post('gender') ,
      'email' => $this->input->post('email') ,
      'password' => $md5_pass,
      'driver_type' => $this->input->post('driver_type'),
      'user_type' => $user_type,
      'status' => $status,
      'phone' => $this->input->post('phone') ,
      'profile_pic' => $profile_pic,
      'address' => $this->input->post('address') ,
      'cell_provider' => $this->input->post('cell_provider') ,
      'spk_english' => $this->input->post('spk_english') ,
      'smoker' => $this->input->post('smoker') ,
      'vehicle_id' => $vehicle_id,
      'added_by' => 0,
      'date_created' => $date_created
    ));
    $last_id = $this->db->insert_id();

    if (!empty($last_id))
    {
      /*----    For refral code    -----*/
      $selection = 'aeuoyibcdfghjklmnpqrstvwxz1234567890QWERTYUIOPLKJHGFDSAZXCVBNM';
      $cupon = "";
      for($i=0; $i<10; $i++) {
        $current_letter = $selection ? (rand(0,1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];            
        $cupon .=  $current_letter;
        if ($i==10) {
          $is_clone = $this->db->get_where('tbl_users',array('promo_code'=>$cupon))->row();
          if (!empty($is_clone)) {
            $cupon = "";
            $i = 0;
            // print_r($is_clone);die;
          }
        }
      }           
      $date = date("Y-m-d H:i:s");
      $value = $this->db->query("SELECT value from tbl_cupon_value where cupon_type = 1")->row();
      $value = $value->value;
      $update = $this->db->query("UPDATE tbl_users set promo_code = '" . $cupon . "' where id='" . $last_id . "'");
      $insert_cupon = $this->db->query("INSERT INTO tbl_cupon(promo_code,user_id,cupon_type,value,date_created) values ('$cupon','$last_id','1','$value','$date')");
      /*-------------------------*/
      // -- permit type provided
      if ($this->input->post('vehicle_permit_type'))
      {
        $idate = date('Y-m-d', strtotime($this->input->post('insurance_exp_date')));
        $rdate = date('Y-m-d', strtotime($this->input->post('registration_exp_date')));
        $query = $this->db->insert('tbl_driver_vehicle_info', array(
          'user_id' => $last_id,
          'vehicle_id' => $vehicle_id,
          'vehicle_type' => $vehicle_type,
          'vehicle_model' => $this->input->post('vehicle_model') ,

          // 'vehicle_number' => $this->input->post('vehicle_number'),

          'vehicle_permit_type' => $this->input->post('vehicle_permit_type') ,
          'insurance_number' => $this->input->post('insurance_number') ,
          'insurance_exp_date' => $idate,
          'registration_number' => $this->input->post('registration_number') ,
          'state_identifier' => $this->input->post('state_identifier') ,
          'registration_exp_date' => $rdate,
          'date_created' => $date_created
        ));
        $vehicle_id = $this->db->insert_id();

        // return true;

      }
      //---- if permit type not provide then calculated
      else
      {
        $idate = date('Y-m-d', strtotime($this->input->post('insurance_exp_date')));
        $rdate = date('Y-m-d', strtotime($this->input->post('registration_exp_date')));
        $str = $this->input->post('registration_number');
        $rest = substr("$str", -2);
        if ($rest % 2 == 0)
        {
          $type = "C";
        }
        else
        {
          $type = "B";
        }

        $query = $this->db->insert('tbl_driver_vehicle_info', array(
          'user_id' => $last_id,
          'vehicle_id' => $vehicle_id,
          'vehicle_type' => $vehicle_type,
          'vehicle_model' => $this->input->post('vehicle_model') ,

          // 'vehicle_number' => $this->input->post('vehicle_number'),

          'vehicle_permit_type' => $type,
          'insurance_number' => $this->input->post('insurance_number') ,
          'insurance_exp_date' => $idate,
          'registration_number' => $this->input->post('registration_number') ,
          'state_identifier' => $this->input->post('state_identifier') ,
          'registration_exp_date' => $rdate,
          'date_created' => $date_created
        ));

        // echo $this->db->last_query();die;
        // $vehicle_id = $this->db->insert_id();
        // return "another type";

      }

      $ldate = date('Y-m-d', strtotime($this->input->post('license_exp_date')));
      $query = $this->db->insert('tbl_driver_documents', array(
        'user_id' => $last_id,
        'document' => $document,
        'vehicle_id' => $vehicle_id,
        'vehicle_image' => $vehicle_pic,
        'vehicle_insurance_image' => $insurance_pic,
        'vehicle_registration_image' => $registration_pic,
        'vehicle_registration_plate_image' => $registration_plate_pic,
        'driver_license_image' => $license_pic,
        'driver_license_number' => $this->input->post('license_number') ,
        'driver_license_exp_date' => $ldate,
        'contract_exp_date' => $this->input->post('contract_exp_date'),
        'date_created' => $date_created
      ));
      if ($this->input->post('permit_a_to')!="") {
        $query = $this->db->insert('tbl_permit', array(
          'driver_id' => $last_id,
          'from' => $this->input->post('permit_a_from'),
          'to' => $this->input->post('permit_a_to'),
          'addedOn' => $date_created
        ));
      }

      $this->db->trans_complete();
      return $last_id;
    }
    else
    {
      return 0;
    }
  }

  public function promo_list($user_id)
  {
    $query['applied'] = $this->db->query("SELECT a.*, b.status  FROM `tbl_coupon` as a JOIN `tbl_promocode` as b on a.id = b.promo_id and `promocode_beneficiary_id` = $user_id and `status`!=1 where now() BETWEEN `start_date` and `end_date`")->result();

    $query['available'] = $this->db->query("SELECT * FROM `tbl_coupon` where now() BETWEEN `start_date` and `end_date` and `id` not IN(SELECT promo_id FROM tbl_promocode where `promocode_beneficiary_id`=$user_id)")->result();

    return $query;
  }

  public function userDetail($inputs)
  {
    $this->db->select("`a`.id,a.user_type,`a`.first_name,`a`.last_name,`a`.phone,`a`.email,`a`.profile_pic,gender,address,smoker,spk_english,promo_code,cell_provider,wallet_balance, `vi`.`vehicle_type`, `vi`.`vehicle_model`, `vi`.`vehicle_permit_type`, `vi`.`insurance_number`, `vi`.`insurance_exp_date`, `vi`.`registration_number`, `vi`.`registration_exp_date`, `dd`.`document`, `dd`.`vehicle_image`, `dd`.`vehicle_insurance_image`, `dd`.`vehicle_registration_image`, `dd`.`vehicle_registration_plate_image`, `dd`.`driver_license_image`, `dd`.`driver_license_number`, `dd`.`driver_license_exp_date`, `p`.`from` as a_perm_from, `p`.`to` as a_perm_to, `c`.`commission`, `c`.`commission_from`, `c`.`commission_to`, `ec`.first_name as ec_first_name,`ec`.last_name as ec_last_name,`ec`.cell,`ec`.home_phone,a.date_created");
    $this->db->from('tbl_users as a');
    $this->db->join('tbl_driver_vehicle_info as vi', 'vi.user_id = a.id', 'left');
    $this->db->join('tbl_driver_documents as dd', 'dd.user_id = a.id', 'left');
    $this->db->join('tbl_permit as p', 'a.id = p.driver_id', 'left');
    $this->db->join('`tbl_commission` as `c`', 'a.id = c.driver_id', 'left');
    $this->db->join('`tbl_emergencyContact` as `ec`', '`ec`.`user_id` = `a`.`id`', 'left');
    $this->db->where('a.user_type', $inputs['user_type']);
    if (!empty($inputs['start_date'])) {
      $this->db->where('DATE(a.date_created) >=', $inputs['start_date']);
      if (!empty($inputs['end_date'])) {
        $this->db->where('DATE(a.date_created) <=', $inputs['end_date']);
      }
    }
    $this->db->like('a.phone', $inputs['phone'], 'after');
    $this->db->like('a.first_name', $inputs['first_name'], 'after');
    $this->db->like('a.last_name', $inputs['last_name'], 'after');
    if (!empty($inputs['limit'])) {
      $this->db->limit($inputs['limit'], $inputs['offset']);
    }
    $query = $this->db->get()->result();
    // print_r($this->db->last_query());die;
    return $query;
  }
}

?>