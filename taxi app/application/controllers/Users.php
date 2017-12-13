<?php

class Users extends CI_Controller {

    function __construct() {
        parent::__construct();
        // error_reporting(E_ALL); ini_set('display_errors', 1);
        $this->load->model('Admin_model', '', TRUE);
        $this->load->library('upload');
        $this->load->library('session');
         // $methodList = get_class_methods($this);
    // print_r($methodList);die;
    /*foreach ($methodList as $key => $value) {
      if ($value!="__construct"&&$value!="get_instance"&&$value!="index"&&$value!="add_permission"&&$value!="add_manager"&&$value!="add_driver"&&$value!="add_holidays"&&$value!="add_vehicle"&&$value!="Add_promo") {
        $this->Admin_model->add_permission(array('permission_name'=>$value));
      }
    }
    die;*/
        $this->load->library('Email');
        $this->load->library('user_agent');
        $session_data = $this->session->userdata('logged_in');
        if (!$session_data) {
            redirect('Login');
        }
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
        $this->load->view('Dashboard/errors/403');
    }

    public function add_users() {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'First Name', 'required|min_length[1]|max_length[125]');
        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('department', 'department', 'required');
        $date_created = date('Y-m-d H:i:s');
        $userdata = array(
            'first_name' => $this->input->post('firstname'),
            'last_name' => $this->input->post('lastname'),
            'email' => $this->input->post('email'),
            'password' => md5($this->input->post('password')),
            'user_type'=>3,
            'date_created'=>$date_created,
            'added_by' => $_SESSION['logged_in']['id'],
        );
        $data['department_list'] = $this->Admin_model->select("tbl_department","*");
        
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $last_id = $this->Admin_model->add_users($userdata);
            if($last_id == 0){
             $this->session->set_flashdata('msg', 'Manager Is already added');
            }
            else{
                $result = $this->Admin_model->insert("tbl_users_department",array('user_id'=>$last_id,'department_id'=>$this->input->post('department'),));
                $this->session->set_flashdata('msg', 'Manager Added Successfully');
            }
        }
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');  
        $this->load->view('add_users', $data);
        $this->load->view('footer');
    }

    public function users_list() {
        $data['users_list'] = $this->Admin_model->users_list();
        $data['client_list'] = $this->Admin_model->users_list("0");
        $data['driver_list'] = $this->Admin_model->users_list("2");
        $data['manager_list'] = $this->Admin_model->users_list("3");
        // echo "<pre>"; print_r($data['driver_list']);  echo "</pre>"; die;
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('users_list', $data);
        $this->load->view('footer');
    }

    public function client_list() {
        $data['client_list'] = $this->Admin_model->users_list("0");
        // echo "<pre>"; print_r($data['driver_list']);  echo "</pre>"; die;
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('client_list', $data);
        $this->load->view('footer');
    }

    public function driver_list() {
        $data['driver_list'] = $this->Admin_model->users_list("2");
        // echo "<pre>"; print_r($data['driver_list']);  echo "</pre>"; die;
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('driver_list', $data);
        $this->load->view('footer');
    }

    public function manager_list() {
        $data['manager_list'] = $this->Admin_model->selectWhere('tbl_managers','*',array('user_type'=>3));
        // echo "<pre>"; print_r($data);  echo "</pre>"; die;
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('manager_list', $data);
        $this->load->view('footer');
    }

    public function changeUserType()
    {
        $details = $this->Admin_model->get_where('tbl_managers',array('id'=>$_POST[user_id]));
        $where = "(email='$_POST[email]' or phone='$_POST[phone]')";
        $table_name = "tbl_users";
        $isExist = $this->Admin_model->get_where($table_name,$where);
        if (empty($isExist)) {
            $data = array('name' => $details[0]->name,'email' => $details[0]->email,'password' => $details[0]->password, 'user_type' => $_POST[type], 'phone' => $details[0]->phone,);
            // $this->Admin_model->insert($table_name,$data);
            echo "changed";
        }else{
            echo "exist";
        }
        print_r(json_encode($this->db->last_query()));
    }

    public function postsview($id) {

        $data['posts'] = $this->Admin_model->view_post($id);
        //print_r($data['post']);
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('posts_view', $data);
        $this->load->view('footer');
    }

    public function Transaction() {

        $data['transaction'] = $this->Admin_model->Transactions();
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('transactions_view', $data);
        $this->load->view('footer');
    }

    public function actuser($user_type=NULL) {
        // print_r($user_type);die;
        $activate_user = $this->input->post('activate_user');
        $deactivate_user = $this->input->post('deactivate_user');
        
        if ($user_type==3) {
            $table_name = 'tbl_managers';
        } else {
            $table_name = 'tbl_users';
        }
        
        if ($activate_user != "") {
            $this->Admin_model->activate_user($activate_user,$table_name);
            
            $this->load->view('template/header');
            $this->load->view('header');
            $this->load->view('left-sidebar');
            if ($user_type==0) {
                $data['client_list'] = $this->Admin_model->users_list("0");
                $this->load->view('client_list', $data);
            }elseif ($user_type==2) {
                $data['driver_list'] = $this->Admin_model->users_list("2");
                $this->load->view('driver_list', $data);
            }elseif ($user_type==3) {
                $data['manager_list'] = $this->Admin_model->users_list("3");
                $this->load->view('manager_list', $data);
            }else{
                $data['users_list'] = $this->Admin_model->users_list();
                $this->load->view('users_list', $data);
            }
            
            $this->load->view('footer');
            // redirect('/Users/users_list');
        } else if ($deactivate_user != "") {
            $this->Admin_model->deactivate_user($deactivate_user,$table_name);
            $data['users_list'] = $this->Admin_model->users_list();
            $this->load->view('template/header');
            $this->load->view('header');
            $this->load->view('left-sidebar');
            if ($user_type==0) {
                $data['client_list'] = $this->Admin_model->users_list("0");
                $this->load->view('client_list', $data);
            }elseif ($user_type==2) {
                $data['driver_list'] = $this->Admin_model->users_list("2");
                $this->load->view('driver_list', $data);
            }elseif ($user_type==3) {
                $data['manager_list'] = $this->Admin_model->users_list("3");
                $this->load->view('manager_list', $data);
            }else{
                $data['users_list'] = $this->Admin_model->users_list();
                $this->load->view('users_list', $data);
            }
            $this->load->view('footer');
            // redirect('/Users/users_list');
        }
    }

    public function login_info() {
        if (isset($_GET['id'])) {
            $this->Admin_model->row_delete($_GET['id']);
        }
        $data['login_info'] = $this->Admin_model->login_info();
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('login_info', $data);
        $this->load->view('footer');
    }

    public function info($id) {
        $data['one_user_info'] = $this->Admin_model->one_user_info($id);

        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('viewuser', $data);
        $this->load->view('footer');
    }

    public function edit($id) {
        if (isset($_POST['submit'])) {
        	// echo "<pre>";
        	// print_r($_POST);
        	// echo "</pre>";die;
            $data['update_users'] = $this->Admin_model->update_users($id);
            // echo "<pre>";print_r($data);
            if( $data['update_users'] == '0'){
                // echo "hello"; die;
              $this->session->set_flashdata('msg', 'User with this phone Number already exists.');
            }
            else{

            $vi_data = array('vehicle_id'=>$_POST['vehicle_type'],
            	'vehicle_model'=>$_POST['vehicle_model'],
            	'vehicle_number'=>$_POST['registration_number'],
            	'insurance_number'=>$_POST['insurance_number'],
            	'insurance_exp_date'=>$_POST['insurance_exp_date'],
            	'state_identifier'=>$_POST['state_identifier'],
            	'registration_number'=>$_POST['registration_number'],
            	'registration_exp_date'=>$_POST['registration_exp_date'],
            	'date_modified'=>date("Y-m-d H:i:s"),
        	);
            $this->Admin_model->updateWhere('tbl_driver_vehicle_info',array('user_id'=>$id),$vi_data);
              $this->session->set_flashdata('msg', 'User Details Updated Successfully');
            // docs
            // echo "<pre>";
            // print_r($_FILES);
            // echo "</pre>";die;

	            $upload_path = "document/";
				$image = "document";
				$document = $this->do_upload($upload_path, $image);

				$upload_path = "vehicle_pic/";
				$image = "vehicle_image";
				$vehicle_pic = $this->do_upload($upload_path, $image);


				$upload_path = "license_pic/";
				$image = "driver_license_image";
				$license_pic = $this->do_upload($upload_path, $image);

				$upload_path = "insurance_pic/";
				$image = "vehicle_insurance_image";
				$insurance_pic = $this->do_upload($upload_path, $image);

				$upload_path = "registration_pic/";
				$image = "vehicle_registration_image";
				$registration_pic = $this->do_upload($upload_path, $image);

				$upload_path = "registration_plate_pic/";
				$image = "registration_plate_pic";
				$registration_plate_pic = $this->do_upload($upload_path, $image);

            $dd_data = array(
            	'vehicle_id'=>$_POST['vehicle_type'],
            	'driver_license_number'=>$_POST['driver_license_number'],
                'driver_license_exp_date'=>$_POST['driver_license_exp_date'],
            	'contract_exp_date'=>$_POST['contract_exp_date'],
            	'date_modified'=>date("Y-m-d H:i:s"),
        	);
        	if (!empty($registration_pic)) {
        		$dd_data['vehicle_registration_image'] = $registration_pic;
        	}
        	if (!empty($vehicle_pic)) {
        		$dd_data['vehicle_image'] = $vehicle_pic;
        	}
        	if (!empty($document)) {
        		$dd_data['document'] = $document;
        	}
        	if (!empty($insurance_pic)) {
        		$dd_data['vehicle_insurance_image'] = $insurance_pic;
        	}
        	if (!empty($license_pic)) {
        		$dd_data['driver_license_image'] = $license_pic;
        	}

            $this->Admin_model->updateWhere('tbl_driver_documents',array('user_id'=>$id),$dd_data);
            //
            $this->Admin_model->delete(array('driver_id'=>$id),'tbl_commission');
            if (!empty($_POST['commission'])&&$_POST['commission']>0) {
                // $haveCommission = $this->Admin_model->selectWhere('tbl_commission','id',array('driver_id'=>$id,'forever'=>$_POST['forever']));
                $date_modified = date("Y-m-d H:i:s");
                $commissionData = array(
                    'driver_id'=>$id,
                    'commission'=>$_POST['commission'],
                    'commission_from'=>$_POST['commission_from'],
                    'commission_to'=>$_POST['commission_to'],
                    'forever'=>$_POST['forever'],
                    'date_modified'=> $date_modified
                );
                $this->Admin_model->insert('tbl_commission',$commissionData);
                if ($_POST['forever']==0&&$_POST['dflt_commission']>0) {
                    $haveDfltCommission = $this->Admin_model->selectWhere('tbl_commission','id',array('driver_id'=>$id,'forever'=>1));
                    $date_modified = date("Y-m-d H:i:s");
                    $date = new DateTime($_POST['commission_to']);
                    $date->modify('+1 day');
                    $fromDate = $date->format('Y-m-d');
                    $dfltCommissionData = array(
                      'driver_id'=>$id,
                      'commission'=>$_POST['dflt_commission'],
                      'commission_from'=>$fromDate,
                      'forever'=>1,
                      'date_modified'=> $date_modified
                    );
                    if (empty($haveDfltCommission)) {
                        $this->Admin_model->insert('tbl_commission',$dfltCommissionData);
                    } else {
                        $this->Admin_model->updateWhere('tbl_commission',array('driver_id'=>$id,'forever'=>1),$dfltCommissionData);
                    }
                }
            }
            
            if (!empty($_POST['from'])) {
	            $havePermit = $this->Admin_model->selectWhere('tbl_permit','id',array('driver_id'=>$id));
	            // echo "string";
	            // print_r($havePermit);die;
	            $permit_data = array(
	            	'driver_id'=>$id,
	            	'from'=>$_POST['from'],
	            	'to'=>$_POST['to'],
	            	'addedOn'=>date("Y-m-d H:i:s"),
	        	);
	            if (!empty($havePermit)) {
	        		$this->Admin_model->updateWhere('tbl_permit',array('driver_id'=>$id),$permit_data);
                     $this->session->set_flashdata('msg', 'User Details Updated Successfully');
	            }else{
	            	$this->Admin_model->insert('tbl_permit',$permit_data);
                     $this->session->set_flashdata('msg', 'User Details Inserted Successfully');
	            }
            }
        }
    }
        $data['vehicle'] = $this->Admin_model->get_vehicle_list();
        $data['model_list'] = $this->Admin_model->select("tbl_vehicle_model","*");
        $data['one_user_info'] = $this->Admin_model->one_user_info($id);
        $data['commissionLevels'] = $this->Admin_model->select("tbl_commissionLevels","*");
        $data['commission'] = $this->Admin_model->commission($id);

        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('edit_user', $data);
        $this->load->view('footer');
    }

    public function mEdit()
    {
        $mngrData = array();
        if (isset($_POST['submit'])) {
            $mngrData = array('name'=>$_POST['first_name']." ".$_POST['last_name'],'first_name'=>$_POST['first_name'], 'last_name'=>$_POST['last_name'],);
            if (!empty($_POST['password'])) {
                $mngrData['password'] = md5($_POST['password']);
            }
            if (isset($_FILES['profile_pic'])) {
                $config['upload_path'] =  'public/profilePic/';
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size'] = 500000;
                $config['max_width'] = 10240;
                $config['max_height'] = 7680;
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('profile_pic')) {
                    $error = array('error' => $this->upload->display_errors());
                    $imagename = "";
                } else {
                    $data = $this->upload->data();
                    $fullPath = base_url() .$config['upload_path']. $data['file_name'];
                    $imagename = ", `profile_pic` = '$fullPath'";
                    $mngrData['profile_pic'] = $fullPath;
                }
            }
            // print_r($_POST);die;
            $this->Admin_model->updateWhere('tbl_managers',array('id'=>$_GET['id']),$mngrData);
        }
        $data['one_user_info'] = $this->Admin_model->selectWhere('tbl_managers','*',array('id'=>$_GET['id']));
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('edit_manager', $data);
        $this->load->view('footer');
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

    function send_message() {
        $this->load->view('template/header');
        $this->load->view('header');
        $this->load->view('left-sidebar');
        $this->load->view('send_message');
        $this->load->view('footer');
    }

    public function notifications()
    {
        $id = $this->input->get('id');
        $data['notification'] = $this->Admin_model->selectWhere("tbl_notifications","*",array('id'=>$id));
        $this->load->view('notifications',$data);
    }

    public function mapNotifications()
    {
        $db_result = $this->Admin_model->mapNotifications();
        echo(json_encode($db_result));
    }

    public function calander()
    {
        $this->load->view('calender');
    }

}

?>