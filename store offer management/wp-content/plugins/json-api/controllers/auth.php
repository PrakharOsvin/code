<?php
// scheme required, here can be multiple origins concatenated by space if using credentials

/*error_reporting(E_ALL);
ini_set('display_errors','1');
*/
/*
Controller name: Auth
Controller description: Basic introspection methods

Database 

User table:- social login
Notifications table

*/


date_default_timezone_set("Asia/Dubai");
class JSON_API_Auth_Controller {


  
  public function socialLogin(){
    global $json_api,$wpdb;
    
   
    $first_name      = $_POST['first_name'];
    $last_name       = $_POST['last_name'];



    $signin_type = $_POST['signin_type'];
    $social_id = $_POST['social_id'];
    $deviceType = $_POST['deviceType'];
    $deviceToken = $_POST['deviceToken'];
    $deviceId = $_POST['deviceId'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];
    $email = $_POST['username'];
    $username = $_POST['username'];



      $user = get_user_by( 'email', $username );
      //print_r($user);die;
      $username=$user->user_login;
      $user = get_user_by( 'login', $username );


      if ( $user ) {
        $is_active = get_user_meta( $user->ID, 'user_active_status', true );
        if ( empty( $is_active ) || $is_active == 'active' ) {
         
         $user_email=$user->user_email;
         


         if($signin_type=="facebook")
        {
             $Userfb_id=$user->fb_id;

             if($Userfb_id!="")
             {
             $userinfo_info = "SELECT * from wp_bp3kqc_users where  user_email='".$user_email."' and (fb_id='".$social_id."') ";
             }else{
              $updatequery="UPDATE wp_bp3kqc_users set fb_id='".$social_id."' where id = '".$user->ID."'";
               $wpdb->query($updatequery);
               $userinfo_info = "SELECT * from wp_bp3kqc_users where  id='".$user->ID."'";
             }  

        }

        if($signin_type=="google")
        {    
               
             $Usergoogle_id=$user->google_id;
             
           if($Usergoogle_id!="")
             {
               $userinfo_info = "SELECT * from wp_bp3kqc_users where  user_email='".$user_email."' and (google_id='".$social_id."') "; 
             }else{

              $updatequery="UPDATE wp_bp3kqc_users set google_id='".$social_id."' where id = '".$user->ID."'";
               $wpdb->query($updatequery);
               $userinfo_info = "SELECT * from wp_bp3kqc_users where  id='".$user->ID."'";
             }  
        } 

      


          $user =  $wpdb->get_row($userinfo_info);
         

          if (!$user) {
                return array('status'=>'error','message'=>"Invalid Email or Social Id");
            }else{
           
          $status=1;
          $user_id=$user->ID;
          $group_info = "SELECT * from wp_bp3kqc_login where  deviceId='".$deviceId."' or deviceToken='".$deviceToken."'"; 
          $results =  $wpdb->get_row($group_info);
    
       if($results->id == "")
          {
             $query  = "INSERT into wp_bp3kqc_login(user_id,deviceType,deviceToken,deviceId,status,lastLogin) values('".$user_id."','".$deviceType."','".$deviceToken."','".$deviceId."','".$status."',NOW())";
            $wpdb->query($query);
          }else 
          {   
            $query="UPDATE wp_bp3kqc_login set user_id='".$user_id."',deviceType='".$deviceType."',deviceToken='".$deviceToken."', status='".$status."', lastLogin = NOW() where deviceId = '".$deviceId."'";
             $wpdb->query($query);
          }

            $user->data->user_firstname = get_user_meta( $user->ID, 'first_name', true );
            $user->data->user_lastname = get_user_meta( $user->ID, 'last_name', true );
            $user->data->ID = $user->ID;

            $user->data->user_login = $username;
            $user->data->user_email = $_POST['username'];

               if(!empty($latitude) && !empty($longitude))
                {
                  update_user_meta( $user->ID, 'latitude', $latitude );
                  update_user_meta( $user->ID, 'longitude', $longitude );
                }
            
            return array('status'=>'ok','message'=>'Login Successfull','data'=>$user->data);
          
            }
          

        } else {
          return array('status'=>'error','message'=>"Your account is not active.");
        }
      }else
      {

           $email = $_POST['username'];
    $username = $_POST['username'];
          $password        = '';
          $vendor          = isset( $_POST['vendor'] ) ? esc_sql( $_POST['vendor'] ) : '';
          $message         = '';

        if(empty($social_id))
            {
               return array('status'=>'error','message'=>"Social id is empty");
            }else
            {
          if ( ! empty( $email ) && ! empty( $username ) ) {
            if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
              if ( stristr( $username, " " ) === false ) {
                  if ( ! username_exists( $username ) ) {
                    if ( ! email_exists( $email ) ) {
                      
                     // print_r($signin_type);die;
                      
                      if($signin_type=="facebook")
                              {
                                  $user_id = wp_insert_user( array(
                                  'user_login' => $username,
                                  'user_email' => $email,
                                  'fb_id' => $social_id
                                ) );

                              }
                             elseif($signin_type=="google")
                              {
                                  $user_id = wp_insert_user( array(
                                  'user_login' => $username,
                                  'user_email' => $email,
                                  'google_id' => $social_id
                                ) );

                              }



                      if ( ! is_wp_error( $user_id ) ) {
                        wp_update_user( array(
                          'ID'   => $user_id,
                          'role' => 'editor'
                        ) );
                        update_user_meta( $user_id, "first_name", $first_name );
                        update_user_meta( $user_id, "last_name", $last_name );
                        update_user_meta( $user_id, 'user_active_status', 'active' );
                        update_user_meta( $user_id, 'confirmation_hash', $confirmation_hash );
                        if ( $vendor == '1' ) 
                        {
                          update_user_meta( $user_id, 'cxxl_account_type', 'vendor' );
                        } else {
                          update_user_meta( $user_id, 'cxxl_account_type', 'buyer' );
                        }


                          if ( $vendor == '1' ) {
                            wp_insert_post( array(
                              'post_type'   => 'store',
                              'post_status' => 'publish',
                              'post_title'  => esc_html__( 'Store', 'couponxxl' ) . $user_id,
                              'post_author' => $user_id
                            ) );
                          }
                          $success = true;

                          $user_firstname = get_user_meta($user_id, 'first_name', true);
                          
                          $user_lastname = get_user_meta( $user_id, 'last_name', true );

                          $user=array(
                         'ID' => $user_id, 
                         'user_login' => $username,
                         'user_email' => $email,
                         'user_firstname' => $user_firstname,
                         'user_lastname' => $user_lastname);

                         
                       
                       return array('status'=>'ok','message'=>'Login Successfull','data'=>$user);
                          



                        
                      } else {
                        return array('status'=>'error','message'=>"There was an error while trying to register you");
                      }
                    } else {
                      return array('status'=>'error','message'=>"Email is already registered");
                    }
                  } else {
                    
                    return array('status'=>'error','message'=>"Email is already registered");
                  }
                
              } else {
                return array('status'=>'error','message'=>"Email can not hold empty spaces or dots");
              }
            } else {
              return array('status'=>'error','message'=>"Email address is invalid");
            }
          } else {
            return array('status'=>'error','message'=>"All fields are required");
          }
        }
        
      }


  }


  public function login(){
    // echo "<pre>";
    // print_r($_REQUEST);
    
    $password = $_POST['password'];
    $username = $_POST['username'];
    $signin_type = $_POST['signin_type'];
    $social_id = $_POST['social_id'];
    $deviceType = $_POST['deviceType'];
    $deviceToken = $_POST['deviceToken'];
    $deviceId = $_POST['deviceId'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    
    global $json_api,$wpdb;


     if(empty($signin_type))
     {
          return array('status'=>'error','message'=>"Signin type field is empty.");
     }else
     {   

         if($signin_type=="facebook" || $signin_type=="google")
        {
            if(empty($social_id))
            {
               return array('status'=>'error','message'=>"Social id is empty");
            }else
            {
                    $socialLogin=$this->socialLogin();
                    return $socialLogin;
                    
            }
        
        }

     }



    if(empty($username) || empty($password)){  

        if(empty($username)){ 
           return array('status'=>'error','message'=>"Email field is empty.");
        }

        if(empty($password)){ 
          return array('status'=>'error','message'=>"Password field is empty.");
        }
    }


      $user = get_user_by( 'email', $username );
      $username=$user->user_login;
      $user = get_user_by( 'login', $username );
      

      if ( $user ) {
        $is_active = get_user_meta( $user->ID, 'user_active_status', true );
        if ( empty( $is_active ) || $is_active == 'active' ) {
         

          $user = wp_signon( array(
            'user_login'    => $username,
            'user_email'    => $_POST['username'],
            'user_password' => $password,
            'remember'      => isset( $_POST['remember_me'] ) ? true : false
          ), is_ssl() );
         


          if ( is_wp_error( $user ) ) {
            switch ( $user->get_error_code() ) {
              case 'invalid_username':
                return array('status'=>'error','message'=>"Invalid email");
                break;
              case 'incorrect_password':
                return array('status'=>'error','message'=>"Invalid password");
                break;
            }
          } else {
           
          $status=1;
          $user_id=$user->ID;
          $group_info = "SELECT * from wp_bp3kqc_login where  deviceId='".$deviceId."' or deviceToken='".$deviceToken."'"; 
          $results =  $wpdb->get_row($group_info);
    
       if($results->id == "")
          {
             $query  = "INSERT into wp_bp3kqc_login(user_id,deviceType,deviceToken,deviceId,status,lastLogin) values('".$user_id."','".$deviceType."','".$deviceToken."','".$deviceId."','".$status."',NOW())";
            $wpdb->query($query);
          }else 
          {   
            $query="UPDATE wp_bp3kqc_login set user_id='".$user_id."',deviceType='".$deviceType."',deviceToken='".$deviceToken."', status='".$status."', lastLogin = NOW() where deviceId = '".$deviceId."'";
             $wpdb->query($query);
          }

            $user->data->user_firstname = get_user_meta( $user->ID, 'first_name', true );
            $user->data->user_lastname = get_user_meta( $user->ID, 'last_name', true );
             $user->data->user_login =$username;
            $user->data->user_email = $_POST['username'];



               if(!empty($latitude) && !empty($longitude))
                {
                  update_user_meta( $user->ID, 'latitude', $latitude );
                  update_user_meta( $user->ID, 'longitude', $longitude );
                }
            
            return array('status'=>'ok','message'=>'Login Successfull','data'=>$user->data);
          }
        } else {
          return array('status'=>'error','message'=>"To sign in please check your email and click the link you received from Offerizer");
        }
      }else
      {
         return array('status'=>'error','message'=>"Sorry, Email is not registered");
      }


  }  


  public function register(){

    $email           = isset( $_POST['email'] ) ? esc_sql( $_POST['email'] ) : '';
    $username        = isset( $_POST['email'] ) ? esc_sql( $_POST['email'] ) : '';
    $fullName        = isset( $_POST['username'] ) ? esc_sql( $_POST['username'] ) : '';
    
   
    $fullName=explode(" ",$fullName);
    
    $first_name      = $fullName[0];
    $last_name       = $fullName[1];
    $password        = isset( $_POST['password'] ) ? esc_sql( $_POST['password'] ) : '';
    $repeat_password = $password;
    $vendor          = isset( $_POST['vendor'] ) ? esc_sql( $_POST['vendor'] ) : '';
    $message         = '';


     
          if ( ! empty( $email ) && ! empty( $username ) && ! empty( $password ) && ! empty( $repeat_password ) ) {
            if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
              if ( stristr( $username, " " ) === false ) {
                if ( $password == $repeat_password ) {
                  if ( ! username_exists( $username ) ) {
                    if ( ! email_exists( $email ) ) {
                      $user_id = wp_insert_user( array(
                        'user_login' => $username,
                        'user_pass'  => $password,
                        'user_email' => $email
                      ) );
                      if ( ! is_wp_error( $user_id ) ) {
                        wp_update_user( array(
                          'ID'   => $user_id,
                          'role' => 'editor'
                        ) );
                        $confirmation_hash = couponxxl_confirm_hash();
                        update_user_meta( $user_id, "first_name", $first_name );
                        update_user_meta( $user_id, "last_name", $last_name );
                        update_user_meta( $user_id, 'user_active_status', 'inactive' );
                        update_user_meta( $user_id, 'confirmation_hash', $confirmation_hash );
                        if ( $vendor == '1' ) 
                        {
                          update_user_meta( $user_id, 'cxxl_account_type', 'vendor' );
                        } else {
                          update_user_meta( $user_id, 'cxxl_account_type', 'buyer' );
                        }

                        $confirmation_message = couponxxl_get_option( 'registration_message' );


                        $confirmation_link    = couponxxl_get_permalink_by_tpl( 'page-tpl_register' );
                        $confirmation_link    = couponxxl_append_query_string( $confirmation_link, array(
                          'username'          => remove_accents( $username ),
                          'confirmation_hash' => $confirmation_hash
                        ) );

                        $confirmation_message = str_replace( '%LINK%', $confirmation_link, $confirmation_message );

                        $registration_subject = couponxxl_get_option( 'registration_subject' );

                        $email_sender = couponxxl_get_option( 'email_sender' );
                        $name_sender  = couponxxl_get_option( 'name_sender' );
                        $headers      = array();
                        $headers[]    = "MIME-Version: 1.0";
                        $headers[]    = "Content-Type: text/html; charset=UTF-8";
                        $headers[]    = "From: " . $name_sender . " <" . $email_sender . ">";



                        $info = wp_mail( $email, $registration_subject, $confirmation_message, $headers );
                        //$info=1;

                        if ( $info ) {
                          if ( $vendor == '1' ) {
                            wp_insert_post( array(
                              'post_type'   => 'store',
                              'post_status' => 'publish',
                              'post_title'  => esc_html__( 'Store', 'couponxxl' ) . $user_id,
                              'post_author' => $user_id
                            ) );
                          }
                          $success = true;
                          $user=array(
                         'ID' => $user_id, 
                         'user_login' => $username,
                         'user_pass'  => $password,
                         'user_email' => $email);
                       
                          return array("message"=>"Thank you for registering, an email to confirm your email address is sent to your inbox.","data"=>$user);
                       


                        } else {
                           
                           return array('status'=>'error','message'=>"There was an error trying to send an email");
                        }
                      } else {
                        return array('status'=>'error','message'=>"There was an error while trying to register you");
                      }
                    } else {
                      return array('status'=>'error','message'=>"Email is already registered");
                    }
                  } else {
                    
                    return array('status'=>'error','message'=>"Email is already registered");
                  }
                } else {
                  return array('status'=>'error','message'=>"Provided passwords do not match");
                }
              } else {
                return array('status'=>'error','message'=>"Email can not hold empty spaces or dots");
              }
            } else {
              return array('status'=>'error','message'=>"Email address is invalid");
            }
          } else {
            return array('status'=>'error','message'=>"All fields are required");
          }
        
  }


  function forgot_password() {
    $response = array();
    
      $email = $_POST['email'];
      if ( ! empty( $email ) ) {
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
          if ( email_exists( $email ) ) {
            $user                  = get_user_by( 'email', $email );
            $new_password          = couponxxl_random_string( 5 );
            $update_fields         = array(
              'ID'        => $user->ID,
              'user_pass' => $new_password,
            );
            $update_id             = wp_update_user( $update_fields );
            $lost_password_message = couponxxl_get_option( 'lost_password_message' );
            $lost_password_message = str_replace( "%USERNAME%", $user->user_login, $lost_password_message );
            $lost_password_message = str_replace( "%PASSWORD%", $new_password, $lost_password_message );

            $email_sender = couponxxl_get_option( 'email_sender' );
            $name_sender  = couponxxl_get_option( 'name_sender' );
            $headers      = array();
            $headers[]    = "MIME-Version: 1.0";
            $headers[]    = "Content-Type: text/html; charset=UTF-8";
            $headers[]    = "From: " . $name_sender . " <" . $email_sender . ">";

            $lost_password_subject = couponxxl_get_option( 'lost_password_subject' );

            $message_info = @wp_mail( $email, $lost_password_subject, $lost_password_message, $headers );
            if ( $message_info ) {
              return array('message'=>"Email with the new password and your username is sent to the provided email address"); 
            } else {
              return array('status'=>'error','message'=>"There was an error trying to send an email"); 
            }
          } else {
            return array('status'=>'error','message'=>"There is no user with the provided email address");
          }
        } else {
          return array('status'=>'error','message'=>"Email address is invalid");
        }
      } else {
        return array('status'=>'error','message'=>"Email address is empty");
      }
    
    echo json_encode( $response );
    die();
  }




  public function getProfile()
  {
    $user_id=$_REQUEST['user_id'];
    $userdata = get_user_by("ID",$user_id);
   
//print_r($userdata);die;

 
  if(!$userdata)
   {
    return array('status'=>'error','message'=>"User not exists");
   }
    $meta = get_user_meta( $user_id );


  $userdata->first_name=$meta['first_name'][0];
  $userdata->last_name=$meta['last_name'][0];

return array( 
      "data" => $userdata->data 
     ); 
}




  function update_profile() {

    $user_id    = isset( $_POST['user_id'] ) ? $_POST['user_id'] : '';
    $first_name      = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
    $last_name       = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
    $email           = isset( $_POST['email'] ) ? $_POST['email'] : '';
    $password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
    $repeat_password = isset( $_POST['repeat_password'] ) ? $_POST['repeat_password'] : '';

    $seller_payout_method  = isset( $_POST['seller_payout_method'] ) ? $_POST['seller_payout_method'] : '';
    $seller_payout_account = isset( $_POST[ 'seller_payout_account_' . $seller_payout_method ] ) ? $_POST[ 'seller_payout_account_' . $seller_payout_method ] : '';

    if ( ! empty( $email ) ) {
      $updated_password = '';
      if ( ! empty( $password ) && ! empty( $repeat_password ) ) {
        $updated_password = 'no';
        if ( $password == $repeat_password ) {
          $updated_password = 'yes';
        }
      }

      if ( ! empty( $updated_password ) && $updated_password == 'no' ) {
        return array('status'=>'error','message'=>"Passwords do not match");
      } else {
        $update_fields = array(
          'ID'           => $user_id,
          'user_email'   => $email,
          'display_name' => $first_name . ' ' . $last_name
        );
        if ( $updated_password == 'yes' ) {
          $update_fields['user_pass'] = $password;
        }
        update_user_meta( $user_id, 'first_name', $first_name );
        update_user_meta( $user_id, 'last_name', $last_name );

        wp_update_user( $update_fields );

        $old_seller_payout_method  = get_user_meta( $user_id, 'seller_payout_method', true );
        $old_seller_payout_account = get_user_meta( $user_id, 'seller_payout_account', true );
        if ( ! empty( $old_seller_payout_method ) && $old_seller_payout_method !== $seller_payout_method ) {
          do_action( 'couponxxl_deregister_payout_account', $old_seller_payout_account, $old_seller_payout_method );
        }

        if ( empty( $seller_payout_method ) || empty( $seller_payout_account ) ) {
          delete_user_meta( $user_id, 'seller_payout_method' );
          delete_user_meta( $user_id, 'seller_payout_account' );
        } else {
          update_user_meta( $user_id, 'seller_payout_method', $seller_payout_method );
          update_user_meta( $user_id, 'seller_payout_account', $seller_payout_account );
        }
        
        return array('message'=>"Profile updated Successfully.");
      }
    } else {
      return array('status'=>'error','message'=>"First name, last name and email are required");
    }
  }



  public function change_password(){
    
    if(empty($_REQUEST['user_id']) || empty($_REQUEST['old_password']) || empty($_REQUEST['new_password'])){
      return array('status'=>'error','message'=>"Please fill all fields");
    }
    else{
      $user  = get_user_by( 'ID', $_REQUEST['user_id']);     
      if(wp_check_password($_REQUEST['old_password'],$user->data->user_pass, $user->ID)){
        wp_set_password($_REQUEST['new_password'],$user->ID);
        return array('status'=>'ok', "message"=>"Password changed successfully");
      }
      else
      {
        return array('status'=>'error', 'message'=>"Old password not match");    
      }      
    }
  }



/*

   function change_password() {

    $user_id    = isset( $_POST['user_id'] ) ? $_POST['user_id'] : '';
    $password        = isset( $_POST['password'] ) ? $_POST['password'] : '';
    $repeat_password = $password;
    $old_password = isset( $_POST['old_password'] ) ? $_POST['old_password'] : '';


echo "ds";die;

    if(empty($user_id) || empty($password) || empty($old_password)){
      return array('status'=>'error','all_error'=>"Please Fill All Credentials");
    }

        $is_active = get_user_meta( $user_id, 'user_active_status', true );
        if ( empty( $is_active ) || $is_active == 'active' ) {
          $user = wp_signon( array(
            'ID'    => $user_id,
            'user_password' => $old_password,
            'remember'      => isset( $_POST['remember_me'] ) ? true : false
          ), is_ssl() );
          if ( is_wp_error( $user ) ) {
            switch ( $user->get_error_code() ) {
              case 'invalid_username':
                return array('status'=>'error','message'=>"Invalid username");
                break;
              case 'incorrect_password':
                return array('status'=>'error','message'=>"Invalid old password");
                break;
            }
          } else {
                            
                            $updated_password = '';
                            if ( ! empty( $password ) && ! empty( $repeat_password ) ) {
                              $updated_password = 'no';
                              if ( $password == $repeat_password ) {
                                $updated_password = 'yes';
                              }

                            if ( ! empty( $updated_password ) && $updated_password == 'no' ) {
                              return array('status'=>'error','message'=>"Passwords do not match");
                            } else {
                              $update_fields = array(
                                'ID'           => $user_id,
                              );
                              if ( $updated_password == 'yes' ) {
                                $update_fields['user_pass'] = $password;
                              }

                              wp_update_user( $update_fields );

                              return array('message'=>"Password updated Successfully.");
                            }
                          } else {
                            return array('status'=>'error','message'=>"password is required");
                          }
          }
        } else {
          return array('status'=>'error','message'=>"Your account is not activated");
        }
   
     
  }*/


 public function logout() {
        global $json_api,$wpdb;
         
          $user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
          $deviceId    = isset( $_REQUEST['deviceId'] ) ? $_REQUEST['deviceId'] : '';
          $user = get_user_by("ID",$user_id); 

          wp_logout();

          $query = "UPDATE wp_bp3kqc_login set status = '0',lastLogout = NOW() where  deviceId = '".$deviceId."' or user_id = '".$user_id."'";
          $wpdb->query($query);


        return array(
            'message' => 'Successfully logged out',
        );
    }


public function getsubscribeNotifications()
{

global $json_api,$wpdb;
$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$group_info = "SELECT wp_bp3kqc_notificationsSubscription.* from wp_bp3kqc_notificationsSubscription where user_id='".$user_id."'"; 
$results =  $wpdb->get_row($group_info);
return array( 
      "data" => $results 
     ); 

}


public function subscribeNotifications()
{

global $json_api,$wpdb;
$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$locationsString    = isset( $_REQUEST['locationsString'] ) ? $_REQUEST['locationsString'] : '';

if(empty($user_id))
            {
               return array('status'=>'error','message'=>"Please fill all fields");
            }else
            {

             $group_info = "SELECT wp_bp3kqc_notificationsSubscription.* from wp_bp3kqc_notificationsSubscription where user_id='".$user_id."'"; 
             $results2 =  $wpdb->get_row($group_info);
           
           if($results2)
           {
                 
            $offer_views="UPDATE wp_bp3kqc_notificationsSubscription set cities='$locationsString' where user_id = '".$user_id."'";
            $wpdb->query($offer_views);

           }else
           { 
             $date=date("Y-m-d H:i:s");
             $query  = "INSERT into wp_bp3kqc_notificationsSubscription(user_id,cities,dateTime) values('".$user_id."','".$locationsString."','".$date."')";
             $wpdb->query($query);
           }
             
             return array(
            'message' => 'Successfully Subscribed',
                );


            }


} 


public function getOffers()
{

        function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }

$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$featured    = isset( $_REQUEST['featured'] ) ? $_REQUEST['featured'] : '';
$keyword    = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '';
$userLatitude    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$userLongitude    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
$locationsString    = isset( $_REQUEST['locationsString'] ) ? $_REQUEST['locationsString'] : '';

$offset=isset( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : '0';
$limit=isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : '20';

// $userLatitude=get_usermeta( $user_id,  'latitude');
// $userLongitude=get_usermeta( $user_id,  'longitude');

global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{

$and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}


/*if(!empty($keyword))
{
 $and .= " and (b.post_title like '%$keyword%')"; 
}
*/

$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
INNER JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id)
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
 order by a.offer_id DESC";
$results =  $wpdb->get_results($offer_info);



foreach ($results as $key => $value) 
{

/*if($value->deal_price==NULL)
$results[$key]->deal_price="";

if($value->deal_sale_price==NULL)
$results[$key]->deal_sale_price="";*/
//$f= get_the_post_thumbnail_url(752);

$results[$key]->store_name=  get_the_title( $value->offer_store );


$locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));




$in='';

if(!empty($locations))
{

foreach ($locations as $keyLocations => $keyLocationsvalue) 
{
  $in.=$keyLocationsvalue->slug.",";
}
 $in=rtrim($in,",");


}

if(empty($locationsString))
 {  
   $filterLocations=$in;
 }else
 {
  $filterLocations=$locationsString;
 }

//echo $in;die; 

          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in') and find_in_set(wp_bp3kqc_store_markers.term_slug,'$filterLocations')"; 
          $results2 =  $wpdb->get_results($group_info);

//print_r($results2);
/*if($value->offer_id==14)
{

  echo $in;
  echo "<br>";
  echo $filterLocations;
  echo "<pre>";print_r($results2);die;
}*/

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/


                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }
            } 
          
          }         
          $x++;

/*
if(!empty($results2))
{
  print_r($results2);die;
}
*/
      if($results2[0]->distance>0)
      {


               usort($results2, 'sortByOrder');
            // print_r($results2);die;
      }

$results[$key]->locations=$results2;
//print_r($location);die;


$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);
$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);



if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}







               if(!empty($keyword))
              {
                    $found=0;

                     if(stripos($value->post_title, $keyword) === FALSE) 
                      {
                         
                      }else
                      {
                         $found=1;
                      }


                      if(stripos($value->store_name, $keyword) === FALSE)
                      {
                      }else
                      {
                         $found=1;
                      }

                        if(!empty($results2))
                        {
                             foreach ($results2 as  $value) 
                             {
                                   if(stripos($value->name, $keyword) === FALSE)
                                      {
                                         
                                      }else
                                      {
                                        //echo $value->name;die;
                                         $found=1;
                                      }

                                     if(stripos($value->place, $keyword) === FALSE)
                                      {
                                         
                                      }else
                                      { 
                                         //echo $value->name;die;
                                         $found=1;
                                      } 
                             }
                        }
              
                    if($found==0)
                    {
                      unset($results[$key]);
                    }
               }

if(empty($results[$key]->locations))
{
  unset($results[$key]);
}

}


$results=array_values($results);

$results = array_slice( $results, $offset, $limit );

return array( 
      "data" => $results 
     ); 

}


public function is_date( $datetime ) {
   
$unixtime = strtotime( $datetime );

if ( FALSE !== $unixtime )
{
    return true;
}else{
  return false;
} 

}

public function getOffersById()
{

global $json_api,$wpdb;

function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }

$offer_id    = isset( $_REQUEST['offer_id'] ) ? $_REQUEST['offer_id'] : '';
$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';

$current_lat    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$current_long    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
$userLatitude= $current_lat;
$userLongitude= $current_long;
          




$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items, 
 b.*,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 
from wp_bp3kqc_offers as a 
LEFT JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id)
 where  a.offer_id='$offer_id'";
$results =  $wpdb->get_results($offer_info);


foreach ($results as $key => $value) 
{


$offer_views="UPDATE wp_bp3kqc_postmeta set meta_value=meta_value+1 where post_id = '$value->ID' and meta_key='offer_views'";
$wpdb->query($offer_views);

$results[$key]->post_content="<div style='font-size:16px'>".$value->post_content."</div>";

$checkFollow = "SELECT wp_bp3kqc_user_follow_store.id from wp_bp3kqc_user_follow_store where user_id='$user_id' and offer_store='$value->offer_store'"; 
$resultsCheckFollow =  $wpdb->get_results($checkFollow);
if($resultsCheckFollow)
{
 $results[$key]->follow_store="1"; 
}else
{
  $results[$key]->follow_store="0"; 
}


$deal_images = couponxxl_smeta_images( 'deal_images', $value->post_id, array() ); 



$results[$key]->store_name=  get_the_title( $value->offer_store );

$locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));

$in='';

if(!empty($locations))
{

foreach ($locations as $keyLocations => $keyLocationsvalue) 
{
  $in.=$keyLocationsvalue->slug.",";
}
$in=rtrim($in,",");
}

          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in')"; 
          $results2 =  $wpdb->get_results($group_info);

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/
                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }
            } 
          }         
          $x++;

 if($results2[0]->distance>0)
      {
               usort($results2, 'sortByOrder');
            // print_r($results2);die;
      }

$results[$key]->locations=$results2;


$results[$key]->offer_thumbnail_urls[]= get_the_post_thumbnail_url($value->post_id, 'post-thumbnail');
foreach( $deal_images as $image_id )
{
  $results[$key]->offer_thumbnail_urls[]= wp_get_attachment_image_url( $image_id, 'post-thumbnail' );
}

$results[$key]->coupon_sale_url = get_post_meta( $value->post_id, 'coupon_sale', true );

if($results[$key]->coupon_sale_url==null)
{
  $results[$key]->coupon_sale_url="";
}


$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);


if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}
}

return array( 
      "data" => $results 
     ); 

}


public function getNearbyOffers()
{

  function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }


$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$featured    = isset( $_REQUEST['featured'] ) ? $_REQUEST['featured'] : '';
$keyword    = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '';
$current_lat    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$current_long    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
$locationsString    = isset( $_REQUEST['locationsString'] ) ? $_REQUEST['locationsString'] : '';
$offset=isset( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : '0';
$limit=isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : '20';
$distanceForSearch=50;//KM

$userLatitude= $current_lat;
$userLongitude= $current_long;
// print_r($userLatitude);echo "<pre>";print_r($userLongitude);die();

global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{

$and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}


if(!empty($keyword))
{
 $and .= " and (b.post_title like '%$keyword%')"; 
}


$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
INNER JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id)
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
 order by a.offer_id DESC";
$results =  $wpdb->get_results($offer_info);



foreach ($results as $key => $value) 
{

/*if($value->deal_price==NULL)
$results[$key]->deal_price="";

if($value->deal_sale_price==NULL)
$results[$key]->deal_sale_price="";*/
//$f= get_the_post_thumbnail_url(752);

$results[$key]->store_name=  get_the_title( $value->offer_store );
$locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));


/*if(!empty($locations) and $value->post_id=="754")
{
echo $value->offer_store;

  print_r($locations);die;
}
*/
$in='';

if(!empty($locations))
{

foreach ($locations as $keyLocations => $keyLocationsvalue) 
{
  $in.=$keyLocationsvalue->slug.",";
}
$in=rtrim($in,",");
}

if(empty($locationsString))
 {  
   $filterLocations=$in;
 }else
 {
  $filterLocations=$locationsString;
 }



          $location=array();
          $results2=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in') and find_in_set(wp_bp3kqc_store_markers.term_slug,'$filterLocations')"; 
          $results2 =  $wpdb->get_results($group_info);

//print_r($results2);


          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/


                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }

                   $results2[$key2]->offer_id=$value->offer_id;
                   $results2[$key2]->offer_type=$value->offer_type;
                   $results2[$key2]->post_title=$value->post_title;
                   $store_id = get_post_meta( $value->post_id, 'offer_store', true );
                  $results2[$key2]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);
            } 
          
          }         
          $x++;

 if(count($results2[$key2]->distance)>0)
 {
   $locations=$results2;
   foreach ($locations as $keyloc => $locvalue) 
   {
       if($locvalue->distance >=$distanceForSearch) 
       {
           unset($results2[$keyloc]);
       }
   }
 $results2=array_values($results2);


 if($results2[0]->distance>0)
      {
               usort($results2, 'sortByOrder');
            // print_r($results2);die;
      }
 
 }

/*foreach ($results2 as $array){
    if (!isset($minarr)) $minarr = $array; 
    else
      if($array->distance < $minarr->distance) 
        $minarr = $array; 
}*/

/*if(!empty($results2))
{
  print_r($minarr);die;
}*/
//$results[$key]->locations=$results2;

/*$a=array();
$a[0]=$minarr;


$results[$key]->minlocation=$a;

if($results[$key]->minlocation==null)
{
  $results[$key]->minlocation=array();
}
*/

//print_r($results[$key]->minlocation);

//unset($minarr);
//print_r($location);die;

$results[$key]->minlocation=$results2;

$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);


if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}



/*if($results[$key]->minlocation[0]->distance=="" or $results[$key]->minlocation[0]->distance >=$distanceForSearch)
{

unset($results[$key]);
}
*/
if(count($results[$key]->minlocation)<1)
{
unset($results[$key]);
}


if(empty($results[$key]->minlocation))
{
  unset($results[$key]);
}

}
$results=array_values($results);

$results = array_slice( $results, $offset, $limit );


return array( 
      "data" => $results 
     ); 

}




public function getCategories()
{

global $json_api,$wpdb;
 
$results=array();
 $offer_cats = "SELECT wp_bp3kqc_term_taxonomy.*, wp_bp3kqc_terms.name, wp_bp3kqc_terms.slug from wp_bp3kqc_term_taxonomy INNER JOIN  wp_bp3kqc_terms ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id)  where wp_bp3kqc_term_taxonomy.taxonomy='offer_cat' and wp_bp3kqc_term_taxonomy.parent='0'  and wp_bp3kqc_term_taxonomy.count<>'0' "; 
    $results =  $wpdb->get_results($offer_cats);


foreach ($results as $key => $value) {
  
  $t_id = $value->term_id;
  $term_meta = get_option( "taxonomy_$t_id" );
  $promo_text = !empty( $term_meta['promo_text'] ) ? $term_meta['promo_text'] : '';
  $category_image = !empty( $term_meta['category_image'] ) ? $term_meta['category_image'] : '';
 $image= wp_get_attachment_image_url( $category_image, 'thumbnail' );
 if($image==false)
 {
  $image="";
 }

 $results[$key]->cat_image=$image;
 $check=$this->getOffersByCategoryCheck($value->slug);
 if(empty($check))
  {
     unset($results[$key]);
  }

}
$results=array_values($results);
return array( 
      "data" => $results 
     ); 

}


public function getOffersByCategoryCheck($offer_cat)
{


global $json_api,$wpdb;

$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$action    = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
// $userLatitude=get_usermeta( $user_id,  'latitude');
// $userLongitude=get_usermeta( $user_id,  'longitude');

$userLatitude    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$userLongitude    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{
 $and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}



$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
LEFT JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id)
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
 order by a.offer_id DESC";
/* print_r($offer_info);die();*/
$results =  $wpdb->get_results($offer_info);


$finalResult=array();
foreach ($results as $key => $value) 
{

 $results[$key]->store_name=  get_the_title( $value->offer_store );

$locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));

$in='';

if(!empty($locations))
{

  foreach ($locations as $keyLocations => $keyLocationsvalue) 
  {
    $in.=$keyLocationsvalue->slug.",";
  }
  $in=rtrim($in,",");
}

          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in')"; 
          $results2 =  $wpdb->get_results($group_info);

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/
                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }

            } 
          
          }         
          $x++;


$results[$key]->locations=$results2;




$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);
$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);



if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}

if($action!="all")
{  
    $term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$offer_cat"));
}else
{
    $select_subcat = "SELECT term_id from wp_bp3kqc_terms where slug='$offer_cat'"; 
    $results_subcat =  $wpdb->get_row($select_subcat);
    $term_id=$results_subcat->term_id;
    $offer_cats = "SELECT wp_bp3kqc_term_taxonomy.*, wp_bp3kqc_terms.name, wp_bp3kqc_terms.slug from wp_bp3kqc_term_taxonomy INNER JOIN  wp_bp3kqc_terms
     ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id)  where wp_bp3kqc_term_taxonomy.taxonomy='offer_cat' and wp_bp3kqc_term_taxonomy.term_id='$term_id'"; 
    $results_all =  $wpdb->get_results($offer_cats);

    foreach ($results_all as $results_allvalue) 
    {
      $subcat_slug=$results_allvalue->slug;
      $term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$subcat_slug"));
      if(!empty($term_list))
      {
        break;
      }
    }
}

//$results[$key]->category=$term_list;
//print_r($results);die;

if(empty($term_list))
{
unset($results[$key]);
}

}

$results=array_values($results);


return $results;


}


public function getLocations()
{

global $json_api,$wpdb;

$taxonomy = 'location';

// we get the terms of the taxonomy 'course', but only top-level-terms with (parent => 0)
$top_level_terms = get_terms( array(
    'taxonomy'      => $taxonomy,
    'parent'        => '0',
    'hide_empty'    => false,
) );




foreach ($top_level_terms as $key => $value) 

{

$term_id=$value->term_id;

$second_level_terms = get_terms( array(
            'taxonomy' => $taxonomy, // you could also use $taxonomy as defined in the first lines
            'child_of' => $term_id,
            'parent' => $term_id, // disable this line to see more child elements (child-child-child-terms)
            'hide_empty' => false,
        ) );

  $top_level_terms[$key]->Childs=$second_level_terms;
  # code...
}


$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$group_info = "SELECT wp_bp3kqc_notificationsSubscription.cities from wp_bp3kqc_notificationsSubscription where user_id='".$user_id."'"; 
$results =  $wpdb->get_row($group_info);
$cities=$results->cities;



if(!empty($cities))
{

    $offer_cats = "SELECT wp_bp3kqc_terms.*, wp_bp3kqc_term_taxonomy.taxonomy from wp_bp3kqc_terms INNER JOIN wp_bp3kqc_term_taxonomy ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id and wp_bp3kqc_term_taxonomy.taxonomy='location') where 1=1 and find_in_set(wp_bp3kqc_terms.slug,'$cities')"; 
      $results =  $wpdb->get_results($offer_cats);
    //print_r($results);die;

    return array( 
      "data" => $top_level_terms,
      "notificationsSubscription" => $results 
     ); 

}else
{
    $results=array();
    return array( 
      "data" => $top_level_terms,
       "notificationsSubscription" => $results 
     ); 
}

}


public function getSubCategories()
{

global $json_api,$wpdb;

$term_taxonomy_id    = isset( $_REQUEST['term_taxonomy_id'] ) ? $_REQUEST['term_taxonomy_id'] : '';

 $offer_cats = "SELECT wp_bp3kqc_term_taxonomy.*, wp_bp3kqc_terms.name, wp_bp3kqc_terms.slug from wp_bp3kqc_term_taxonomy INNER JOIN  wp_bp3kqc_terms ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id)  where wp_bp3kqc_term_taxonomy.taxonomy='offer_cat' and wp_bp3kqc_term_taxonomy.parent='$term_taxonomy_id'  and wp_bp3kqc_term_taxonomy.count <> 0"; 
          $results =  $wpdb->get_results($offer_cats);

return array( 
      "data" => $results 
     ); 

}


public function getOffersByCategory()
{

  function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }

global $json_api,$wpdb;

$offer_cat = isset( $_REQUEST['slug'] ) ? $_REQUEST['slug'] : '';
$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$action    = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
$offset=isset( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : '0';
$limit=isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : '20';
$locationsString    = isset( $_REQUEST['locationsString'] ) ? $_REQUEST['locationsString'] : '';
// $userLatitude=get_usermeta( $user_id,  'latitude');
// $userLongitude=get_usermeta( $user_id,  'longitude');

$userLatitude    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$userLongitude    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
// var_dump($userLongitude);die();
global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{
 $and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}



$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
LEFT JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id)
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
 order by a.offer_id DESC";
/* print_r($offer_info);die();*/
$results =  $wpdb->get_results($offer_info);


$finalResult=array();
foreach ($results as $key => $value) 
{

 $results[$key]->store_name=  get_the_title( $value->offer_store );

$locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));

$in='';

if(!empty($locations))
{
  foreach ($locations as $keyLocations => $keyLocationsvalue) 
  {
    $in.=$keyLocationsvalue->slug.",";
  }
  $in=rtrim($in,",");
}


if(empty($locationsString))
 {  
   $filterLocations=$in;
 }else
 {
   $filterLocations=$locationsString;
 }


          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in') and find_in_set(wp_bp3kqc_store_markers.term_slug,'$filterLocations')"; 
          $results2 =  $wpdb->get_results($group_info);

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/
                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }

            } 
          
          }         
          $x++;


if($results2[0]->distance>0)
      {


               usort($results2, 'sortByOrder');
            // print_r($results2);die;
      }

$results[$key]->locations=$results2;
//print_r($location);die;




$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);
$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);



if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}

if($action!="all")
{  
    $term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$offer_cat"));
}else
{
    $select_subcat = "SELECT term_id from wp_bp3kqc_terms where slug='$offer_cat'"; 
    $results_subcat =  $wpdb->get_row($select_subcat);
    $term_id=$results_subcat->term_id;
    $offer_cats = "SELECT wp_bp3kqc_term_taxonomy.*, wp_bp3kqc_terms.name, wp_bp3kqc_terms.slug from wp_bp3kqc_term_taxonomy INNER JOIN  wp_bp3kqc_terms
     ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id)  where wp_bp3kqc_term_taxonomy.taxonomy='offer_cat' and wp_bp3kqc_term_taxonomy.term_id='$term_id'"; 
    $results_all =  $wpdb->get_results($offer_cats);

    foreach ($results_all as $results_allvalue) 
    {
      $subcat_slug=$results_allvalue->slug;
      $term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$subcat_slug"));
      if(!empty($term_list))
      {
        break;
      }
    }
}

//$results[$key]->category=$term_list;
//print_r($results);die;

if(empty($term_list))
{
unset($results[$key]);
}


if(empty($results[$key]->locations))
{
  unset($results[$key]);
}

}

$results=array_values($results);

$results = array_slice( $results, $offset, $limit );
//print_r($results);die;

return array( 
      "data" => $results 
     );


}



public function getOffersByLocation()
{

global $json_api,$wpdb;

function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }


$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$locationsString    = isset( $_REQUEST['locations'] ) ? $_REQUEST['locations'] : '';


/*$userLatitude=get_usermeta( $user_id,  'latitude');
$userLongitude=get_usermeta( $user_id,  'longitude');*/

$userLatitude    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$userLongitude    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';

global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{
 $and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}



$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
LEFT JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id) 
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
 order by a.offer_id DESC";
$results =  $wpdb->get_results($offer_info);


$finalResult=array();
foreach ($results as $key => $value) 
{

$checkFollow = "SELECT wp_bp3kqc_user_follow_store.id from wp_bp3kqc_user_follow_store where user_id='$user_id' and offer_store='$value->offer_store'";
$resultsCheckFollow =  $wpdb->get_results($checkFollow);

if(count($resultsCheckFollow)>0)
{
 $results[$key]->follow_store="1"; 
}else
{
  $results[$key]->follow_store="0"; 
}

 $results[$key]->store_name=  get_the_title( $value->offer_store );
 $locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));

$in='';

$in='';

if(!empty($locations))
{

foreach ($locations as $keyLocations => $keyLocationsvalue) 
{
  $in.=$keyLocationsvalue->slug.",";
}
$in=rtrim($in,",");
}

$in=$locationsString;

          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in')"; 
          $results2 =  $wpdb->get_results($group_info);

          $results2 =  $wpdb->get_results($group_info);

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/
                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }

            } 
          
          }         
          $x++;



$results[$key]->locations=$results2;



//print_r($location);die;




$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);
$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);



if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}

$term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$offer_cat"));
//$results[$key]->category=$term_list;

if(empty($results[$key]->locations))
{
  unset($results[$key]);
}

if(empty($term_list))
{
unset($results[$key]);
}

}

$results=array_values($results);
//print_r($results);die;

return array( 
      "data" => $results 
     );


}



public function getOffersByStore()
{

global $json_api,$wpdb;

function sortByOrder($a, $b) 
              {

                  return $a->distance - $b->distance;
              }


$offer_store = isset( $_REQUEST['offer_store'] ) ? $_REQUEST['offer_store'] : '';
$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';

/*$userLatitude=get_usermeta( $user_id,  'latitude');
$userLongitude=get_usermeta( $user_id,  'longitude');*/

$userLatitude    = isset( $_REQUEST['currentLat'] ) ? $_REQUEST['currentLat'] : '';
$userLongitude    = isset( $_REQUEST['currentLong'] ) ? $_REQUEST['currentLong'] : '';
$locationsString    = isset( $_REQUEST['locationsString'] ) ? $_REQUEST['locationsString'] : '';

$offset=isset( $_REQUEST['offset'] ) ? $_REQUEST['offset'] : '0';
$limit=isset( $_REQUEST['limit'] ) ? $_REQUEST['limit'] : '20';


global $json_api,$wpdb;

$offer_type    = isset( $_REQUEST['offer_type'] ) ? $_REQUEST['offer_type'] : '';
$date = strtotime(date("Y-m-d H:i:s"));
 
$and="";          
if($offer_type=="coupon")
{
 $and= "and a.offer_type='$offer_type'";
}

if($offer_type=="deal")
{
 $and= "and a.offer_type='$offer_type'"; 
}


if($featured=="yes")
{
 $and .= " and a.offer_in_slider='$featured'"; 
}



$offer_info = "SELECT a.offer_id, 
a.offer_type,
 a.post_id, 
 a.offer_start, 
 a.offer_expire,
 a.offer_has_items,
 a.offer_in_slider as featured, 
 b.ID,  
 b.post_title,
(select c.meta_value  FROM  wp_bp3kqc_postmeta as c where (c.post_id=a.post_id and c.meta_key='deal_price')) as deal_price, 
(select d.meta_value  FROM  wp_bp3kqc_postmeta as d where (d.post_id=a.post_id and d.meta_key='deal_sale_price')) as deal_sale_price, 
(select e.meta_value  FROM  wp_bp3kqc_postmeta as e where (e.post_id=a.post_id and e.meta_key='deal_items')) as deal_items, 
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views,  
(select f.meta_value  FROM  wp_bp3kqc_postmeta as f where (f.post_id=a.post_id and f.meta_key='offer_views')) as offer_views, 
(select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=a.post_id and g.meta_key='offer_store')) as offer_store 

from wp_bp3kqc_offers as a 
LEFT JOIN wp_bp3kqc_posts as b ON(b.ID=a.post_id) 
 where  b.post_status='publish' 
 and b.post_type='offer' 
 and a.offer_expire>'$date' 
 and a.offer_has_items>'0' 
 $and 
having offer_store='$offer_store' 
 order by a.offer_id DESC";
$results =  $wpdb->get_results($offer_info);


$finalResult=array();
foreach ($results as $key => $value) 
{

$checkFollow = "SELECT wp_bp3kqc_user_follow_store.id from wp_bp3kqc_user_follow_store where user_id='$user_id' and offer_store='$value->offer_store'"; 
$resultsCheckFollow =  $wpdb->get_results($checkFollow);

if(count($resultsCheckFollow)>0)
{
 $results[$key]->follow_store="1"; 
}else
{
  $results[$key]->follow_store="0"; 
}

 $results[$key]->store_name=  get_the_title( $value->offer_store );
 $locations = wp_get_post_terms($value->post_id, 'location', array("fields" => "all"));

$in='';

if(!empty($locations))
{

foreach ($locations as $keyLocations => $keyLocationsvalue) 
{
  $in.=$keyLocationsvalue->slug.",";
}
$in=rtrim($in,",");
}

if(empty($locationsString))
 {  
   $filterLocations=$in;
 }else
 {
  $filterLocations=$locationsString;
 }

          $location=array();
          $group_info = "SELECT wp_bp3kqc_store_markers.*, wp_bp3kqc_store_markers.name as place, wp_bp3kqc_terms.name from wp_bp3kqc_store_markers INNER JOIN wp_bp3kqc_terms ON(wp_bp3kqc_terms.slug=wp_bp3kqc_store_markers.term_slug)  where  wp_bp3kqc_store_markers.post_id='$value->offer_store' and find_in_set(wp_bp3kqc_store_markers.term_slug,'$in') and find_in_set(wp_bp3kqc_store_markers.term_slug,'$filterLocations')";
          $results2 =  $wpdb->get_results($group_info);

          if(count($results2)>0)
          {
            foreach ($results2 as $key2 => $value2) 
            {
                 /* $results2->term_id=$lvalue->term_id;
                  $results2->location=$lvalue->name;*/
                  $latitudeFrom =  $userLatitude;
                  $longitudeFrom =  $userLongitude;

                  $latitudeTo = $value2->latitude;
                  $longitudeTo = $value2->longitude;

                  //Calculate distance from latitude and longitude
                  $theta = $longitudeFrom - $longitudeTo;
                  $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
                  $dist = acos($dist);
                  $dist = rad2deg($dist);
                  $miles = $dist * 60 * 1.1515;
                  $distance = ($miles * 1.609344);
                  if(!empty($userLatitude) && !empty($longitudeFrom))
                  {
                    $results2[$key2]->distance=round($distance);
                  }else
                  {
                     $results2[$key2]->distance="";
                  }

            } 
          
          }         
          $x++;


if($results2[0]->distance>0)
      {

               usort($results2, 'sortByOrder');
            // print_r($results2);die;
      }

$results[$key]->locations=$results2;
//print_r($location);die;




$results[$key]->offer_thumbnail_url=get_the_post_thumbnail_url($value->post_id);
$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);



if($value->offer_start=="99999999999")
{
$results[$key]->offer_start="";    
}else
{
$results[$key]->offer_start=date('Y-m-d H:i:s',$value->offer_start);    
}

if($value->offer_expire=="99999999999")
{
$results[$key]->offer_expire="";    
}else
{
$results[$key]->offer_expire=date('Y-m-d H:i:s',$value->offer_expire);
}

//$term_list = wp_get_post_terms($value->post_id, 'offer_cat', array("fields" => "all", "slug"=>"$offer_cat"));
//$results[$key]->category=$term_list;

/*if(empty($term_list))
{
unset($results[$key]);
}*/

if(empty($results[$key]->locations))
{
  unset($results[$key]);
}


}

$results=array_values($results);
$results = array_slice( $results, $offset, $limit );
//print_r($results);die;

return array( 
      "data" => $results 
     );


}


public function followStore()
{

global $json_api,$wpdb;
$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : '';
$user_id = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$offer_store = isset( $_REQUEST['offer_store'] ) ? $_REQUEST['offer_store'] : '';

 if(empty($user_id) || empty($action) || empty($offer_store) )
    {
      return array('status'=>'error','message'=>"Please fill all fields");
    }

if($action=="follow")
{


$group_info2 = "SELECT wp_bp3kqc_user_follow_store.* from wp_bp3kqc_user_follow_store where  user_id='$user_id' and offer_store='$offer_store'";
$results2 =  $wpdb->get_results($group_info2);
if($results2)
{
  return array('status'=>'error','message'=>"You have already follow this store");
}


$query  = "INSERT into wp_bp3kqc_user_follow_store(user_id,offer_store) values('".$user_id."','".$offer_store."')";
$wpdb->query($query);
return array('status'=>'ok','message'=>'follow Successfull');
}
elseif($action=="unfollow")
{
$query  = "DELETE FROM wp_bp3kqc_user_follow_store  where user_id='$user_id'  and offer_store='$offer_store'";
$wpdb->query($query);
return array('status'=>'ok','message'=>'unfollow Successfull');
}else
{
  return array('status'=>'error','message'=>'something went wrong');
}

}


public function getFollowStores()
{

global $json_api,$wpdb;

$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';


$results=array();

$group_info = "SELECT wp_bp3kqc_user_follow_store.offer_store from wp_bp3kqc_user_follow_store where user_id='$user_id'"; 
          $results2 =  $wpdb->get_results($group_info);


foreach ($results2 as $key => $value) {
       $post = get_post( $value->offer_store ); 
       $post->store_thumbnail_url=get_the_post_thumbnail_url($value->offer_store);

      $results[$key]->offer_store="$post->ID";
      $results[$key]->post_title=$post->post_title;
      $results[$key]->post_name=$post->post_name;
      $results[$key]->store_thumbnail_url=$post->store_thumbnail_url;
      $results[$key]->follow_store=1;
}

return array( 
      "data" => $results
     );


}


public function getUnreadNotifications()
{

global $json_api,$wpdb;

$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';


$results=array();

$group_info = "SELECT wp_bp3kqc_notifications.*, wp_bp3kqc_offers.offer_id, wp_bp3kqc_offers.offer_type, (select g.meta_value  FROM  wp_bp3kqc_postmeta as g where (g.post_id=wp_bp3kqc_notifications.post_id and g.meta_key='offer_store')) as offer_store  from wp_bp3kqc_notifications  INNER JOIN wp_bp3kqc_offers ON(wp_bp3kqc_offers.post_id=wp_bp3kqc_notifications.post_id) where wp_bp3kqc_notifications.user_id='$user_id' order by wp_bp3kqc_notifications.id desc"; 
$results =  $wpdb->get_results($group_info);


foreach ($results as $key => $value) {
$results[$key]->offer_title=  get_the_title( $value->post_id );

$results[$key]->store_name=  get_the_title( $value->offer_store );

$store_id = get_post_meta( $value->post_id, 'offer_store', true );
$results[$key]->store_thumbnail_url=get_the_post_thumbnail_url($store_id);
}




return array( 
      "data" => $results
     );


}

public function readNotification()
{

global $json_api,$wpdb;

$user_id    = isset( $_REQUEST['user_id'] ) ? $_REQUEST['user_id'] : '';
$id    = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';


$results=array();

$group_info="UPDATE wp_bp3kqc_notifications set status='1' where user_id = '$user_id' and id='$id'";
$results =  $wpdb->get_results($group_info);

return array('status'=>'ok','message'=>'Unread Successfull');

}


public function deleteNotification()
{

global $json_api,$wpdb;

$id    = isset( $_REQUEST['id'] ) ? $_REQUEST['id'] : '';

$group_info = "SELECT wp_bp3kqc_notifications.* from wp_bp3kqc_notifications where id='$id'";
$results2 =  $wpdb->get_results($group_info);
if(empty($results2))
{
  return array('status'=>'error','message'=>'Notification not exists');
}

$results=array();
$group_info="DELETE FROM wp_bp3kqc_notifications where  id='$id'";
$results =  $wpdb->get_results($group_info);

return array('status'=>'ok','message'=>'Delted Successfully');

}


public function contact()
{

global $json_api,$wpdb;

/*error_reporting(E_ALL);
ini_set('display_errors','1');*/

    $errors  = array();
    $name    = isset( $_POST['name'] ) ? ( $_POST['name'] ) : '';
    $email   = isset( $_POST['email'] ) ? ( $_POST['email'] ) : '';
    $message = isset( $_POST['message'] ) ? ( $_POST['message'] ) : '';
    $subject = isset( $_POST['subject'] ) ? ( $_POST['subject'] ) : '';
    $experience = isset( $_POST['experience'] ) ? ( $_POST['experience'] ) : '';
    
      if ( ! empty( $name ) && ! empty( $email ) && ! empty( $message ) ) {
        if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
          $email_to = couponxxl_get_option( 'contact_mail' );

          $subject  = $subject;
          if ( ! empty( $email_to ) ) {
               $message = "You received a new contact form message:\n
                Name: ".$name."\n
                Email: ".$email."\n
                Experience: ".$experience."\n
                Message:".$message;
            $info    = @wp_mail( $email_to, $subject, $message );
            if ( $info ) {
              return array('status'=>'ok','message'=>'Your message was successfully submitted.');
              die();
            } else {
              return array('status'=>'error','message'=>'Unexpected error while attempting to send e-mail.');
              die();
            }
          } else {
              return array('status'=>'error','message'=>'Message is not send since the recepient email is not yet set.');
              die();
          }
        } else {
           
           return array('status'=>'error','message'=>'Email is not valid.');
            die();

          die();
        }
      } else {
           return array('status'=>'error','message'=>'All fields are required.');
            die();
      }
    

}


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



  public function matchOld()
  {
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);
    $user_id = $_REQUEST['user_id'];
    if(empty($_REQUEST['user_id'])){
      return array('status'=>'error','all_error'=>"Please Fill All Credentials");
    }
    else{

    $meta  = "SELECT * FROM `wp_rstrpfztfr_usermeta` WHERE `user_id` = '".$user_id."'";
    $resultmeta =  $wpdb->get_results($meta);

    $checkmeta = array();
    foreach($resultmeta as $key){
      $checkmeta[$key->meta_key] = $key->meta_value;
    }

    $field = $checkmeta['field_6'];
    $distance = $checkmeta['distance'];
    $minage = $checkmeta['minage'];
    $maxage = $checkmeta['maxage'];
    // return $distance;
    $sql = " AND (`meta_key` = 'distance' AND `meta_value` <= '".$distance."')";
        
    $c  = "SELECT * FROM `wp_rstrpfztfr_usermeta` WHERE `meta_key` = 'field_6' AND `meta_value` = '".$field."' $sql ";
   
    $resultc =  $wpdb->get_results($c);

    $d  = "SELECT * FROM `wp_rstrpfztfr_users` WHERE `ID` = '".$user_id."' ";
    $results =  $wpdb->get_results($d);
    
    $check = array();
    foreach($result as $key){
      $check[$key->meta_key] = $key->meta_value;
    } 

    foreach($results[0] as $key_a=>$value_ekey){
      $check[$key_a] = $value_ekey;
    } 
    
    $output = $check;
    }
  }

  public function matchUser(){
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_REQUEST['user_id'];

    $search_for=""; 
    if(empty($_REQUEST['user_id'])){
      return array('status'=>'error','all_error'=>"Please Fill All Credentials");
    }
    else{
      $profile  = "SELECT * FROM `wp_rstrpfztfr_bp_xprofile_data` where user_id=$user_id and field_id=6";
      $profileData =  $wpdb->get_results($profile);
      foreach ($profileData as $key) {
        $search_for = $key->value;
      }

      $profile  = "SELECT * FROM `wp_rstrpfztfr_bp_xprofile_data` where user_id=$user_id and field_id=232";
      $profileData =  $wpdb->get_results($profile);
      foreach ($profileData as $key) {
        $intensions = $key->value;
      }

      if($intensions=="Man" || $intensions=="Woman")
      {

      }else
      {
        
      }




      // return $search_for;
      $meta  = "SELECT * FROM `wp_rstrpfztfr_usermeta` WHERE `user_id` = $user_id";
      $resultmeta =  $wpdb->get_results($meta);
      $checkmeta = array();
      foreach($resultmeta as $key){
        $checkmeta[$key->meta_key] = $key->meta_value;
      }

      $distance = @$checkmeta['distance'];
      $latitude = @$checkmeta['latitude'];
      $longitude = @$checkmeta['longitude'];
      $minage = @$checkmeta['minage'];
      $maxage = @$checkmeta['maxage'];

      if ($minage==NULL||$maxage==NULL || $minage==''||$maxage=='') {
        $agebetween="";
      } else {
        $agebetween = " and (dob between $minage and $maxage)";
      }

     
      
      // print_r($minage);
      // var_dump($maxage);
      // die;
      // return $resultmeta;
      if ($search_for!="") {
        $and = " and (g.field_id='6' and g.`value`='$search_for')";
      } else {
        $and = "";
      }
//       print_r($and);die;
      // $search = "SELECT * FROM `wp_rstrpfztfr_bp_xprofile_data` where user_id!=$user_id and (field_id=6 $search_for) group by user_id";








        $search = "SELECT a.display_name,a.ID, 
        b.value as gender,
        c.value as intensions,
        e.value as city,
        f.value as country,
        TIMESTAMPDIFF(YEAR, d.`value`, CURDATE()) AS dob
        FROM `wp_rstrpfztfr_users` a
        JOIN `wp_rstrpfztfr_bp_xprofile_data` b on a.ID=b.user_id
        JOIN `wp_rstrpfztfr_bp_xprofile_data` c on (c.value='$intensions')
        JOIN `wp_rstrpfztfr_bp_xprofile_data` d on (a.ID=d.user_id)
        LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` e on (a.ID=e.user_id)
        LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` f on (a.ID=f.user_id)
        LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` g on (a.ID=g.user_id)
        where
        a.ID!=$user_id 
        and c.field_id=232
        and b.field_id=3
        and d.field_id=2
        and e.field_id=17
        and f.field_id=18
        $and
        group by a.ID
         limit 10";
        

         //echo $search;
        // die();
    /*  $searchData = $wpdb->get_results($search);

   echo "<pre>";
  print_r($searchData);
   die();*/




      $search = "SELECT ff.display_name,a.user_id, (SELECT count(*) as blockstatus FROM `wp_rstrpfztfr_bp_block_member` where user_id='".$user_id."' and target_id=b.user_id) as block_status, (SELECT count(*) FROM `wp_rstrpfztfr_bp_friends` where (initiator_user_id=b.user_id AND friend_user_id=$user_id) OR (initiator_user_id=$user_id AND friend_user_id=b.user_id)) as friend_status,
        TIMESTAMPDIFF(YEAR, a.`value`, CURDATE()) AS dob,
        b2.value as gender,
        d2.`meta_value` as avatar,
        d3.`value` as field_17,
        d4.`value` as field_18,
        c.meta_value as latitude, 
        d.meta_value as longitude,
        b10.value as intensions, 

        ( 6371 * acos ( cos ( radians($latitude) ) * cos( radians( c.meta_value ) ) * cos( radians( d.meta_value ) - radians($longitude) ) + sin ( radians(($latitude) ) * sin( radians( c.meta_value ) ) ) ) ) as distance

        FROM `wp_rstrpfztfr_bp_xprofile_data` a
        JOIN `wp_rstrpfztfr_bp_xprofile_data` b on a.user_id=b.user_id
        JOIN `wp_rstrpfztfr_bp_xprofile_data` b2 on a.user_id=b2.user_id
        JOIN `wp_rstrpfztfr_bp_xprofile_data` b10 on (b10.value='$intensions')
        LEFT JOIN `wp_rstrpfztfr_usermeta` c on (a.user_id=c.user_id and c.meta_key ='latitude')
        LEFT JOIN `wp_rstrpfztfr_usermeta` d on (a.user_id=d.user_id and d.meta_key ='longitude')
        LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` d3 on a.user_id=d3.user_id
        LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` d4 on a.user_id=d4.user_id
        LEFT JOIN `wp_rstrpfztfr_usermeta` d2 on a.user_id=d2.user_id
        JOIN `wp_rstrpfztfr_users` ff on a.user_id=ff.ID
        where 
        b.user_id!=$user_id 
        and b2.field_id=3
        and b10.field_id=232
        $and
        and a.field_id=2 
        

        and d2.meta_key='avatar'
        and d3.field_id='17'
        and d4.field_id='18'
        group by a.user_id
        $agebetween
        and block_status < 1 and friend_status < 1
        having (Case WHEN (c.meta_value > '0') THEN distance <= '$distance' ELSE 1=1 END )
        ";



        // echo $search;
        // die();
      $searchData = $wpdb->get_results($search);
      // echo "<pre>";
      // print_r($searchData);
      // die();

      for($i=0;$i<count($searchData);$i++){
           $searchData[$i]->img = "";
            // $avatar_options = array ( "item_id" => $searchData[$i]->user_id, "object" => "user","type" => "full", "css_id" => 1234, "class" => "avatar", "html" => false);
            
            // $results = bp_core_fetch_avatar($avatar_options);

            // if (strpos($results, 'https://phphosting.osvin.net') === false) {
            //     $results = "http:".$results;
            //     $searchData[$i]->img = $results;
            // }

             $avatar_options = array ( "item_id" => $searchData[$i]->user_id , "type" => "full", "html" => false);
           
            $results = bp_core_fetch_avatar($avatar_options);

            if (strpos($results, 'https://phphosting.osvin.net') === false) {
                $results = "http:".$results;
                $searchData[$i]->img = str_replace("#038;",'', $results);
            }
            else{
                $searchData[$i]->img = $results;
            }
        }
        
      return $searchData;
    }
  }



  public function requestUser(){
    
    global $json_api,$wpdb;
    // die('hgere');

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_REQUEST['user_id'];

    $friend = "SELECT a.initiator_user_id, 
              ff.display_name,
              TIMESTAMPDIFF(YEAR, t.`value`, CURDATE()) AS dob,
b2.value as gender ,  d2.`meta_value` as avatar, d3.`value` as field_17,
        d4.`value` as field_18, d5.`value` as state
FROM `wp_rstrpfztfr_bp_friends` a
LEFT JOIN `wp_rstrpfztfr_users` ff on a.initiator_user_id=ff.ID 
LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` t on (a.initiator_user_id=t.user_id and t.field_id=2)
LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` b2 on (a.initiator_user_id=b2.user_id and b2.field_id=3)
LEFT JOIN `wp_rstrpfztfr_usermeta` d2 on (a.initiator_user_id=d2.user_id and d2.meta_key='avatar') 
LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` d3 on (a.initiator_user_id=d3.user_id  and d3.field_id='17')
LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` d4 on (a.initiator_user_id=d4.user_id and d4.field_id='18')
LEFT JOIN `wp_rstrpfztfr_bp_xprofile_data` d5 on (a.initiator_user_id=d5.user_id and d5.field_id='316')
where a.friend_user_id=$user_id and a.is_confirmed=0";
// echo $friend;
// die();
     
      $searchData = $wpdb->get_results($friend);
      for($i=0;$i<count($searchData);$i++){
        $searchData[$i]->img = "";
        $avatar_options = array ( "item_id" => $searchData[$i]->initiator_user_id , "type" => "full", "html" => false);
        $results = bp_core_fetch_avatar($avatar_options);
        if (strpos($results, 'https://phphosting.osvin.net') === false) {
            $results = "http:".$results;
            $searchData[$i]->img = str_replace("#038;",'', $results);
        }
    else{
            $searchData[$i]->img = $results;
        }
      }
      return $searchData;
   }

  public function block(){  
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);
    $user_id = $_REQUEST['user_id'];
    $target_id = $_REQUEST['target_id'];

    $j  = "INSERT INTO `loveandlose`.`wp_rstrpfztfr_bp_block_member` (`user_id`, `target_id`) VALUES ('".$user_id."', '".$target_id."')";
    
    if(($target_id!=0) && ($user_id!=0)){
      $wpdb->query($j);
    }

    return array( 
      "user_id" => $user_id,
      "status_code" => '1',
    ); 
  }

  public function blockstatus(){
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);
    
    $user_id = $_REQUEST['user_id'];
    $target_id = $_REQUEST['target_id'];

    // if($user_id!=0 && $target_id!=0){
      
    $j  = "SELECT count(*) as blockstatus FROM `wp_rstrpfztfr_bp_block_member` where user_id='".$user_id."' and target_id='".$target_id."'"; 
    $results = $wpdb->get_results($j)[0];
    // }
    
    return array( 
      "result" => $results
    ); 
  }


  public function unblock(){
    
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);
    $user_id = $_REQUEST['user_id'];
    $target_id = $_REQUEST['target_id'];
    
    if(($target_id!=0) && ($user_id!=0)){
       $aa = $wpdb->query( "DELETE FROM `wp_rstrpfztfr_bp_block_member` WHERE `user_id` = '".$user_id."' and `target_id` = '".$target_id."'" );
    }

    return array( 
      "user_id" => $user_id,
      "target_id"=>$target_id,
      "status_code" => '0',
     ); 
  }

  public function report(){
    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_REQUEST['user_id'];
    $target_id = $_REQUEST['target_id'];
    $message = $_REQUEST['message'];

    if(($user_id!=0) && ($target_id!=0)){


    $aa  = "INSERT INTO `loveandlose`.`wp_rstrpfztfr_bp_report_member` (`user_id`, `target_id`,`message`) VALUES ('".$user_id."', '".$target_id."','".$message."')";
    
    $wpdb->query($aa); 

    $user_info  = "SELECT user_email FROM `wp_rstrpfztfr_users` WHERE `ID` = '".$user_id."' ";
    $result =  $wpdb->get_results($user_info);

    $target_info  = "SELECT user_email FROM `wp_rstrpfztfr_users` WHERE `ID` = '".$target_id."' ";

    $results =  $wpdb->get_results($target_info);
    $to = 'osvinandroid@gmail.com';
    $subject = 'Report User';
    $message = "<!DOCTYPE html>
          <head>
          <meta content=text/html; charset=utf-8 http-equiv=Content-Type /> 
          <title>Recover Password</title>
          <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600' rel='stylesheet' type='text/css'>
          </head>
          <body background-color=#655383>    
          <table width=60%  bgcolor=#fff style='border:1px solid #655383;'>
          <tr>
          <th width=20px></th>
          <th width=20px  style=padding-top:30px;padding-bottom:30px;><img src=https://phphosting.osvin.net/loveandloss/wp-content/uploads/ic_logo@2x.png width=100px;height=100px;></th>
          <th width=20px></th>
          </tr>
          <tr>
          <td width=20px></td>
          <td padding:20px;>      
          <table width=100%;>
          <tr>
          <th style=font-size:20px; font-weight:bolder; text-align:right;padding-bottom:10px;border-bottom:solid 1px #ddd;> Hello admin</th>
          </tr>
          <tr>
          <td style=font-size:16px;>
          <p>".$result[0]->user_email." reported ".$results[0]->user_email." ".$message."</p>
          </td>                    
          </tr>
          <tr>
          <td style=text-align:center; padding:20px;>
          <h2 style=margin-top:50px; font-size:29px;>Best Regards,</h2>
          <h3 style=margin:0; font-weight:100;>Customer Support</h3>
          </td>
          <tr style='border-top:1px solid #655383;text-align:center;'>
          <td> 
          <h3 style='margin:0; font-weight:100;'><img src=https://phphosting.osvin.net/loveandloss/wp-content/uploads/ic_logo@x.png width=30px;height=30px;></h3></td>
          </tr>
          </tr>
          </table>
          </td>
          <td width=20px></td>
          </tr>
          <tr>
          <td width=20px></td>
          <td style=text-align:center; color:#655383; padding:10px;> Copyright © Loveandlose All Rights Reserved</td>
          <td width=20px></td>
          </tr>
          </table>
          </body>";

    wp_mail( $to, $subject, $message );

    return array( 
      "user_id" => $user_id 
     ); 
    }
    else{
      return array("status"=>"error");
    }
  }

  public function group(){
  
  global $json_api,$wpdb;
    //$_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_GET['user_id'];
    // echo $user_id;
    // die();

    $group_info = "SELECT wp_rstrpfztfr_bp_groups.*,(SELECT count(*) FROM `wp_rstrpfztfr_bp_groups_members` as t where  t.group_id = wp_rstrpfztfr_bp_groups.id and t.user_id='$user_id') as membership FROM `wp_rstrpfztfr_bp_groups` ORDER BY `wp_rstrpfztfr_bp_groups`.`id` DESC";

    $result =  $wpdb->get_results($group_info);

    for($i=0;$i<count($result);$i++){
      // $result[$i]->id
      $avatar_options = array ( "item_id" => $result[$i]->id, "object" => "group", "type" => "full", "avatar_dir" => "group-avatars", "alt" => "Group avatar", "css_id" => 1234, "class" => "avatar", "width" => 50, "height" => 50, "html" => false );
      $results = bp_core_fetch_avatar($avatar_options);
      $result[$i]->img = $results;
   
    }
    
//print_r($result);die;

    return array( 
      "groups" => $result 
     ); 
  }


  public function groupDetail(){
  
  global $json_api,$wpdb;
    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $id = $_REQUEST['id'];
    // echo $user_id;
    // die();

     $group_info = "SELECT wp_rstrpfztfr_bp_groups.*,(SELECT count(*) FROM `wp_rstrpfztfr_bp_groups_members` as t where  t.group_id = wp_rstrpfztfr_bp_groups.id) as membership FROM `wp_rstrpfztfr_bp_groups` where id='$id' ORDER BY `wp_rstrpfztfr_bp_groups`.`id` DESC";

     $results =  $wpdb->get_results($group_info);

      $avatar_options = array ( "item_id" => $id, "object" => "group", "type" => "full", "avatar_dir" => "group-avatars", "alt" => "Group avatar", "css_id" => 1234, "class" => "avatar", "width" => 50, "height" => 50, "html" => false );
      $results[0]->img = bp_core_fetch_avatar($avatar_options);
     
   // print_r($results)die;
    return array( 
      "groupDetail" => $results 
     ); 
  }

public function fetch_avatar(){
  
     global $json_api,$wpdb;
     $_REQUEST= json_decode(file_get_contents('php://input'), true);

     $id = $_REQUEST['id'];
    // echo $user_id;
    // die();

     $avatar_options = array ( "item_id" => $id , "type" => "thumb", "html" => false);
     $avatar = bp_core_fetch_avatar($avatar_options);

        if (strpos($avatar, 'https://phphosting.osvin.net') === false) {
            $avatar = "http:".$avatar;
            $avatar = str_replace("#038;",'', $avatar);
        }
   // print_r($results)die;
    return array( 
      "Avatar" => $avatar 
     ); 
  }


  public function addActivity(){
    global $json_api,$wpdb;
    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $id = $_REQUEST['groupId'];
    $user_id = $_REQUEST['user_id'];
    $content = $_REQUEST['content'];
    $niceName = $_REQUEST['niceName'];
    $groupSlug = $_REQUEST['groupSlug'];
    $groupName = $_REQUEST['groupName'];

   $action='<a href="'.get_site_url().'/membership-account/members/'.$niceName.'/" title="'.$niceName.'">'.$niceName.'</a> posted an update in the group <a href="'.get_site_url().'/groups/'.$groupSlug.'/">'.$groupName.'</a>';

   $primary_link=get_site_url().'/membership-account/members/'.$niceName.'/';
    // echo $user_id;
    // die();
    $date=date("Y-m-d H:i:s");
      $args = array ( "item_id" => $id, "action" => $action, "content" => $content, "component" => "groups", "type" => "activity_update", "primary_link" => $primary_link, "user_id" => $user_id, "recorded_time" => $date );
    $activity_id = bp_activity_add( $args );

   // print_r($results)die;
  
  if($activity_id)
  {
      $group_info = "SELECT wp_rstrpfztfr_bp_activity.*, (SELECT count(*) FROM `wp_rstrpfztfr_bp_activity` as t where  t.item_id = t.secondary_item_id and t.item_id='$activity_id' and t.type='activity_comment') as comments, wp_rstrpfztfr_users.user_nicename, wp_rstrpfztfr_users.display_name from wp_rstrpfztfr_bp_activity   inner join wp_rstrpfztfr_users on(wp_rstrpfztfr_users.id=wp_rstrpfztfr_bp_activity.user_id) where wp_rstrpfztfr_bp_activity.id='$activity_id' ORDER BY `wp_rstrpfztfr_bp_activity`.`id` DESC";
      $results =  $wpdb->get_results($group_info);
  }

$results[0]->avatar="https://phphosting.osvin.net/loveandloss/wp-content/uploads/app/2017/272/avatar.png";


    return array( 
      "ActivityDetail" => $results 
     ); 
  
}


public function getActivities()
{
      global $json_api,$wpdb;
      $_REQUEST= json_decode(file_get_contents('php://input'), true);
      $group_id = $_REQUEST['group_id'];
    
      $group_info = "SELECT wp_rstrpfztfr_bp_activity.*, wp_rstrpfztfr_users.display_name, wp_rstrpfztfr_users.ID, (SELECT count(*) FROM `wp_rstrpfztfr_bp_activity` as t where  t.item_id = t.secondary_item_id and t.item_id=wp_rstrpfztfr_bp_activity.id and t.type='activity_comment') as comments  FROM `wp_rstrpfztfr_bp_activity` INNER JOIN wp_rstrpfztfr_users ON(wp_rstrpfztfr_users.ID=wp_rstrpfztfr_bp_activity.user_id) where wp_rstrpfztfr_bp_activity.item_id='$group_id' and wp_rstrpfztfr_bp_activity.type='activity_update' and wp_rstrpfztfr_bp_activity.component='groups'  ORDER BY `wp_rstrpfztfr_bp_activity`.`id` DESC";
      $results =  $wpdb->get_results($group_info);


     $x=1;
      foreach($results as $val)
      {
          $avatar_options = array ( "item_id" => $val->user_id, "type" => "thumb", "html" => false);
             // bp_core_fetch_avatar ( array( 'item_id' => 297, 'type' => 'full','html'=>false ) ) ;
          $thumb = bp_core_fetch_avatar($avatar_options);
          $val->avatar=$thumb;
          $final[] =$val;
          $x++;
      }
    
    return array( 
      "ActivityComments" => $final 
     ); 
  
}


public function getActivityComments()
{
      global $json_api,$wpdb;
      $_REQUEST= json_decode(file_get_contents('php://input'), true);
      $activity_id = $_REQUEST['activity_id'];
      $group_info = "SELECT wp_rstrpfztfr_bp_activity.*, wp_rstrpfztfr_users.display_name, wp_rstrpfztfr_users.ID FROM `wp_rstrpfztfr_bp_activity` INNER JOIN wp_rstrpfztfr_users ON(wp_rstrpfztfr_users.ID=wp_rstrpfztfr_bp_activity.user_id) where wp_rstrpfztfr_bp_activity.item_id = wp_rstrpfztfr_bp_activity.secondary_item_id and wp_rstrpfztfr_bp_activity.item_id='$activity_id' and wp_rstrpfztfr_bp_activity.type='activity_comment' and wp_rstrpfztfr_bp_activity.component='activity'  ORDER BY `wp_rstrpfztfr_bp_activity`.`id` DESC";
      $results =  $wpdb->get_results($group_info);
    
     $x=1;
      foreach($results as $val)
      {

         $avatar_options = array ( "item_id" => $val->user_id, "type" => "thumbnail", "html" => false);
             // bp_core_fetch_avatar ( array( 'item_id' => 297, 'type' => 'full','html'=>false ) ) ;
          $thumb = bp_core_fetch_avatar($avatar_options);
          $val->avatar=$thumb;
        $final[] =$val;
        $x++;
      }

    return array( 
      "ActivityComments" => $results 
     ); 
  
}


public function getShareText()
{
      global $json_api,$wpdb;
      $group_info = "SELECT wp_bp3kqc_sharetext.text FROM `wp_bp3kqc_sharetext`";
      $results =  $wpdb->get_row($group_info);
    
    return $results; 
  
}


  public function addActivityComment(){
    global $json_api,$wpdb;
    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $activity_id = $_REQUEST['activity_id'];
    $post_id = $_REQUEST['activity_id'];
    $user_id = $_REQUEST['user_id'];
    $content = $_REQUEST['content'];
    $niceName = $_REQUEST['niceName'];
    $groupSlug = $_REQUEST['groupSlug'];
    $groupName = $_REQUEST['groupName'];

   $action='<a href="'.get_site_url().'/membership-account/members/'.$niceName.'/" title="'.$niceName.'">'.$niceName.'</a> posted a new activity comment';

   $primary_link=get_site_url().'/membership-account/members/'.$niceName.'/';
    // echo $user_id;
    // die();
    $date=date("Y-m-d H:i:s");
      $args = array ( "item_id" => $id, "action" => $action, "content" => $content, "component" => "activity", "type" => "activity_comment", "primary_link" => $primary_link, "user_id" => $user_id, "item_id"=>$activity_id, "secondary_item_id"=>$activity_id, "recorded_time" => $date );
    $activity_id = bp_activity_add( $args );

   // print_r($results)die;
  
  if($activity_id)
  {
      $group_info = "SELECT wp_rstrpfztfr_bp_activity.*, (SELECT count(*) FROM `wp_rstrpfztfr_bp_activity` as t where  t.item_id = t.secondary_item_id and  t.item_id='$post_id') as comments, wp_rstrpfztfr_users.user_nicename, wp_rstrpfztfr_users.display_name from wp_rstrpfztfr_bp_activity   inner join wp_rstrpfztfr_users on(wp_rstrpfztfr_users.id=wp_rstrpfztfr_bp_activity.user_id) where wp_rstrpfztfr_bp_activity.id='$activity_id' ORDER BY `wp_rstrpfztfr_bp_activity`.`id` DESC";
      $results =  $wpdb->get_row($group_info);
  }

     $avatar_options = array ( "item_id" => $results->user_id, "type" => "thumbnail", "html" => false);
             // bp_core_fetch_avatar ( array( 'item_id' => 297, 'type' => 'full','html'=>false ) ) ;
          $thumb = bp_core_fetch_avatar($avatar_options);
          $results->avatar=$thumb;


    return array( 
      "ActivityDetail" => $results 
     ); 
  
}


  public function join_group(){

    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_REQUEST['user_id'];
    $group_id = $_REQUEST['group_id'];
    $date = date('y-m-d h:i:s');
    if(($group_id!=0) && ($user_id!=0)){
      $j  = "INSERT INTO `loveandlose`.`wp_rstrpfztfr_bp_groups_members` (`group_id`, `user_id`,`is_confirmed`,`date_modified`) VALUES ('".$group_id."','".$user_id."', '1','".$date."')";      
      $wpdb->query($j);
    }
    return array( 
      "user_id" => $user_id,
      "status_code" => '1',
    ); 
  }

  public function leave_group(){

    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $user_id = $_REQUEST['user_id'];
    $group_id = $_REQUEST['group_id'];
    $date = date('y-m-d h:i:s');

    $aa = $wpdb->query( "DELETE FROM `wp_rstrpfztfr_bp_groups_members` WHERE `group_id` = '".$group_id."' and `user_id` = '".$user_id."' and `is_confirmed`= '1' " );
    
    return array( 
      "user_id" => $user_id,
      "status_code" => '1',
     ); 
  }


   public function contact_us_mail(){

    global $json_api,$wpdb;

    $_REQUEST= json_decode(file_get_contents('php://input'), true);

    $message = "You received a new contact form message:<br>  
                Name: ".$_REQUEST['display_name']."<br> 
                Email: ".$_REQUEST['email']."<br> 
                Message:".$_REQUEST['message'];

    $headers = array('Content-Type: text/html; charset=UTF-8');

    if(wp_mail("osvinandroid@gmail.com","Contact Form Message",$message,$headers)){
      $results['error'] = 1;
    }
    else{
      $results['error'] =0 ;
    }

    return $results;
    // $user_id = $_REQUEST['user_id'];
    // $group_id = $_REQUEST['group_id'];
    // $date = date('y-m-d h:i:s');
    // if(($group_id!=0) && ($user_id!=0)){
    //   $j  = "INSERT INTO `loveandlose`.`wp_rstrpfztfr_bp_groups_members` (`group_id`, `user_id`,`is_confirmed`,`date_modified`) VALUES ('".$group_id."','".$user_id."', '1','".$date."')";      
    //   $wpdb->query($j);
    // }
    // return array( 
    //   "user_id" => $user_id,
    //   "status_code" => '1',
    // ); 
  }


/*  $offer_cats = "SELECT wp_bp3kqc_terms.*, wp_bp3kqc_term_taxonomy.taxonomy from wp_bp3kqc_terms INNER JOIN wp_bp3kqc_term_taxonomy ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id and wp_bp3kqc_term_taxonomy.taxonomy='location' and wp_bp3kqc_term_taxonomy.parent='0') where 1=1 and find_in_set(wp_bp3kqc_terms.slug,'$notificationSubscriptionCities')"; 
    $results2 =  $wpdb->get_results($offer_cats);
    if($results2)
    { 
      foreach ($results2 as $key => $value) {
        $offer_cats2 = "SELECT wp_bp3kqc_terms.*, wp_bp3kqc_term_taxonomy.taxonomy from wp_bp3kqc_terms INNER JOIN wp_bp3kqc_term_taxonomy ON(wp_bp3kqc_terms.term_id=wp_bp3kqc_term_taxonomy.term_id and wp_bp3kqc_term_taxonomy.taxonomy='location') where wp_bp3kqc_term_taxonomy.parent='".$value->term_id."'"; 
         $resultsCities =  $wpdb->get_results($offer_cats2);
          echo "<pre>";
         print_r($resultsCities);

      }
        
    }*/

}
?>
