<?php

class Admin_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        // $this->db->query("SET time_zone='+3:30'");
    }

    function login($email, $password) {
        // print_r($email);print_r($password);die;
        $this->db->select('id, email, name, user_type, department_id');
        $this->db->from('tbl_managers');
        $this->db->join('tbl_users_department as ud','ud.user_id = tbl_managers.id');
        $this->db->where('email', $email);
        $this->db->where('user_type !=', 0);
        $this->db->where('user_type !=', 2);
        $this->db->where('password',$password);
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row();
        // print_r($result);die;
        // print_r($this->db->last_query());die;
        return $result;
    }

    public function check_permission($department_id,$permission_id)
    {
        $query = $this->db->select('permission_id')->from('tbl_department_permission')->where('department_id',$department_id)->where('permission_id',$permission_id)->get()->row();
        // print_r($this->db->last_query());die;
        // print_r($query->permission_id);die; 
        return $query->permission_id;
    }
    public function ExportCSV()
{
        $this->load->dbutil();
        $this->load->helper('file');
        $this->load->helper('download');
        $delimiter = ",";
        $newline = "\r\n";
        $filename = "Excel.csv";
        $query = "SELECT * FROM tbl_coupon";
        $result = $this->db->query($query);
        $data = $this->dbutil->csv_from_result($result, $delimiter, $newline);
        force_download($filename, $data);
}


    public function methodId($permission_name)
    {
        $query = $this->db->select('permission_id')->from('tbl_permission')->where('permission_name',$permission_name)->get()->row();
        // print_r($query->permission_id);
        return $query;
    }

    /* Dashboard */

    function dashboard_details() {
        // $select_user = $this->db->get_where('tbl_users');
        // $get_user = $select_user->result();

        $data['query1'] = $this->db->query("SELECT user_type,count(*) as countid FROM `tbl_users` where user_type != 1")->result();
        $data['query'] = $this->db->query("SELECT user_type,count(user_type) as countiiii FROM `tbl_users` group by user_type")->result();
        $data['wallet_balance'] = $this->db->query("SELECT wallet_balance FROM `tbl_managers` Where id = '2'")
                    ->row();
        return $data;
    }

    /* Users */

    function users_list($type=NULL) {
        if ($type != "") {
            if ($type==3) {
                $table_name = 'tbl_managers';
            } else {
                $table_name = 'tbl_users';
            }
            
            $query = $this->db->get_where($table_name,array('user_type' => $type));
        } else {
            $query = $this->db->select('*')->from('tbl_users')->where('user_type !=','1')->get();
        }
        return $query->result();
    }

    function driver_list() {
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('id', 2);
        $query = $this->db->get();
        return $query->result();
    }

    function user_email() {
        $this->db->select('email');
        $this->db->from('tbl_users');
        $this->db->where('status', 1);
        $query = $this->db->get();
        //print_r($this->db->last_query());
        return $query->result();
    }

    function one_user_info($id) {
        //print_r($id);die;
        $this->db->select('a.*,vi.vehicle_type,vi.vehicle_model,vi.vehicle_permit_type,vi.insurance_number,vi.insurance_exp_date,vi.registration_number,vi.registration_exp_date,vi.state_identifier,b.name as added_by,dd.document,dd.vehicle_image,dd.vehicle_insurance_image,dd.vehicle_registration_image,dd.vehicle_registration_plate_image,dd.driver_license_image,dd.driver_license_number,dd.driver_license_exp_date,dd.contract_exp_date,p.from,p.to');
        $this->db->from('tbl_users as a');
        $this->db->join('tbl_users as b', 'b.id = a.added_by', 'left');
        $this->db->join('tbl_driver_vehicle_info as vi', 'vi.user_id = a.id', 'left');
        $this->db->join('tbl_driver_documents as dd', 'dd.user_id = a.id', 'left');
        $this->db->join('tbl_permit as p', 'a.id = p.driver_id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get()->result();
        // print_r($this->db->last_query());
         // echo"<pre>"; print_r($query); echo "</pre>"; die;
        // $query = $this->db->query("SELECT *,(select name from tbl_users where id = added_by) as added_by FROM tbl_users where id = $id")->result();
        return $query;
    }

    function one_mngr_info($id) {
        //print_r($id);die;
        $this->db->select('a.*,vi.vehicle_type,vi.vehicle_model,vi.vehicle_permit_type,vi.insurance_number,vi.insurance_exp_date,vi.registration_number,vi.registration_exp_date,b.name as added_by,dd.document,dd.vehicle_image,dd.vehicle_insurance_image,dd.vehicle_registration_image,dd.vehicle_registration_plate_image,dd.driver_license_image,dd.driver_license_number,dd.driver_license_exp_date,p.from,p.to,c.commission,c.commission_from,c.commission_to');
        $this->db->from('tbl_managers as a');
        $this->db->join('tbl_managers as b', 'b.id = a.added_by', 'left');
        $this->db->join('tbl_driver_vehicle_info as vi', 'vi.user_id = a.id', 'left');
        $this->db->join('tbl_driver_documents as dd', 'dd.user_id = a.id', 'left');
        $this->db->join('tbl_permit as p', 'a.id = p.driver_id', 'left');
        $this->db->join('tbl_commission as c', 'a.id = c.driver_id', 'left');
        $this->db->where('a.id', $id);
        $query = $this->db->get()->result();
        // print_r($this->db->last_query());
         // echo"<pre>"; print_r($query); echo "</pre>"; die;
        // $query = $this->db->query("SELECT *,(select name from tbl_users where id = added_by) as added_by FROM tbl_users where id = $id")->result();
        return $query;
    }

    function Transactions() {

        $this->db->select('*');
        $this->db->from('tbl_transaction');
        $query = $this->db->get();

        return $query->result();
    }

   function update_users($id) {

        if ($this->input->post('addFunds')!=""&&$this->input->post('subtractFunds')=="") {
            $addSubtract = ", `wallet_balance` = `wallet_balance`+'".$this->input->post('addFunds')."'";
        }elseif ($this->input->post('subtractFunds')!=""&&$this->input->post('addFunds')=="") {
            $addSubtract = ", `wallet_balance` = `wallet_balance`-'".$this->input->post('subtractFunds')."'";
        } else {
            $addSubtract = "";
        }
            $this->load->library('upload');
        if (isset($_FILES['profile_pic'])) {
            $config['upload_path'] =  './public/profilePic/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = 500000;
            $config['max_width'] = 10240;
            $config['max_height'] = 7680;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_pic')) {
                $error = array('error' => $this->upload->display_errors());
                $imagename .= "";
            } else {
                $data = $this->upload->data();
                $fullPath = base_url() .$config['upload_path']. $data['file_name'];
                $imagename .= ", `profile_pic` = '$fullPath'";
            }
        }
        if(!empty($this->input->post('phone'))){
            $dataQuery =$this->db->query("select * from tbl_users where phone =".$this->input->post('phone'));
            $Query1 = $dataQuery->num_rows();
            $query2 = $dataQuery->result();
        }
        $dataQuery1 =$this->db->query("select * from tbl_users where id =".$id);
        $quer32 = $dataQuery1->result();
        // echo "<pre>";
        // print_r($query2);die;
        $name = $this->input->post('first_name')." ".$this->input->post('last_name');
        if(isset($_POST['password']) && !empty($_POST['password'])){
            if(($Query1 == 0) || $quer32[0]->phone == $this->input->post('phone')){
                $query = $this->db->query("UPDATE `tbl_users` SET `name` = '$name', `first_name` = '".$this->input->post('first_name')."', `last_name` = '".$this->input->post('last_name')."', `phone` = '".$this->input->post('phone')."', `password` = '".md5($this->input->post('password'))."', `address` = '".$this->input->post('address')."' $addSubtract $imagename WHERE `id` = $id");
            }
            else {
                return 0;
            }
        }
        else{
            if(($Query1 == 0) || $quer32[0]->phone == $this->input->post('phone')){
             $query = $this->db->query("UPDATE `tbl_users` SET `name` = '$name', `first_name` = '".$this->input->post('first_name')."', `last_name` = '".$this->input->post('last_name')."', `phone` = '".$this->input->post('phone')."', `address` = '".$this->input->post('address')."' $addSubtract $imagename WHERE `id` = $id");
            }
            else{
                return 0;
            }
        }

        if ($query) {
            return "updated";
        } else {
            return 0;
        }
    }

    function row_delete($id) {
        //print_r($id);die;
        $this->db->where('id', $id);
        $this->db->delete('tbl_app_login');
    }

    function getUsername($user_id) {

        $query = $this->db->get_where('tbl_users', array('id' => $user_id));

        $result = $query->result_array();
        return $result;

        print_r($result);
    }

    function user_info() {
        $query = $this->db->select('*')->from('tbl_users')->get();
        return $query->row();
    }

    function login_info() {
        $query = $this->db->select('*')->from('tbl_app_login')->get();
        return $query->result();
    }

    function charged() {
        $query = $this->db->get_where('tbl_transactions', array('splitId' => 0));
        return $query->result();
    }

    function transaction() {
        $query = $this->db->get_where('tbl_transactions');
        return $query->result();
    }

    function Changepassword($attribute) {

        //print_r($attribute);
        $data = array(
            'password' => md5($attribute['password'])
        );
        $this->db->where('id', $attribute['id']);

        $query = $this->db->update('tbl_managers', $data);
        if ($query) {
            $this->session->set_flashdata('msg', 'Password Updated Successfully');
            redirect("Updatepassword/Updatepassword");
        } else {
            $this->session->set_flashdata('msg', 'Error in Updating Password');
            redirect("Updatepassword/Updatepassword");
        }
    }

    function Changepassword1($attribute) {


        $data = array(
            'password' => md5($attribute['password'])
        );
        $this->db->where('id', $attribute['id']);

        $query = $this->db->update('tbl_users', $data);
        if ($query) {
            $this->session->set_flashdata('msg', 'Password Updated Successfully');
            redirect("Resetpassword/resetpassword");
        } else {
            $this->session->set_flashdata('msg', 'Error in Updating Password');
            redirect("Resetpassword/resetpassword");
        }
    }

    function checkpassword($id, $password) {
        $this->db->select('email');
        $this->db->from('tbl_managers');
        $this->db->where('id', $id);
        $this->db->where('password', md5($password));
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->result();
        } else {
            return false;
        }
    }

    function record_delete($tbl_name, $id) {
        //print_r($id);die;
        $this->db->where('id', $id);
        $this->db->delete($tbl_name);
    }

    function country() {
        $select_country = $this->db->query('Select country, (Count(country)* 100 / (Select Count(*) From tbl_users_info)) as percentage From tbl_users_info  Group By country ORDER BY percentage desc limit 4');
        $get_country = $select_country->result();

        return $get_country;
    }

    function count_rows() {

        $select_count = $this->db->query('select count(*)from tbl_meditation');
        $get_count = $select_count->result();
    }

    function addx() {
        $data = array("value" => $this->input->post('cname'));
        $this->db->where('id', '1');
        $addx_query = $this->db->update('tbl_xvalue', $data);
    }

    function x_info() {
        $this->db->select('*');
        $this->db->from('tbl_xvalue');
        $this->db->where('id', '1');
        $query = $this->db->get()->result();
        return $query;
    }

    function activate_user($activateid,$tbl_name=null) {
        $data = array(
            'status' => '1'
        );
        $this->db->where('id', $activateid);
        $query = $this->db->update($tbl_name, $data);
        if ($query) {
            return "updated";
        } else {
            return "not updated";
        }
    }

    function deactivate_user($deactivateid,$tbl_name) {
        $data = array(
            'status' => '0'
        );
        $this->db->where('id', $deactivateid);
        $query = $this->db->update($tbl_name, $data);
        if ($query) {
            return "updated";
        } else {
            return "not updated";
        }
    }

    function add_driver($md5_pass, $profile_pic, $license_pic, $registration_pic, $insurance_pic, $vehicle_pic, $document, $registration_plate_pic) {
        // echo "<pre>"; print_r($_POST); echo "</pre>"; die;
        $vehicle_id = $this->input->post('vehicle_type');

        $vehicle = $this->db->select('vehicle_type')->from('tbl_vehicle')->where('id',$vehicle_id)->get()->row();
        $vehicle_type = $vehicle->vehicle_type;
        // print_r($vehicle_type);die;
        $status = '0';
        $user_type = 2;
        $date_created = date('Y-m-d H:i:s');
        $this->db->trans_start();
        if($queryCheck == 1){
            $querySelect = $this->db->query("select * from tbl_users where email='".$this->input->post('email')."'")->num_rows();
            if($querySelect == 1){
        $query = $this->db->insert('tbl_users', array('first_name' => $this->input->post('fname'),
            'last_name' => $this->input->post('lname'),
            'name' => $this->input->post('fname')." ".$this->input->post('lname'),
            'gender' => $this->input->post('gender'),
            'email' => $this->input->post('email'),
            'password' => $md5_pass,
            'user_type' => $user_type,
            'status' => $status,
            'phone' => $this->input->post('phone'),
            'profile_pic' => $profile_pic,
            'address' => $this->input->post('address'),
            'cell_provider' => $this->input->post('cell_provider'),
            'spk_english' => $this->input->post('spk_english'),
            'smoker' => $this->input->post('smoker'),
            'vehicle_id' => $vehicle_id,
            'added_by' => $_SESSION['logged_in']['id'],
            'date_created' => $date_created));
        $last_id = $this->db->insert_id();
           }
        }
        else{
               $query = $this->db->insert('tbl_users', array('first_name' => $this->input->post('fname'),
                'last_name' => $this->input->post('lname'),
                'name' => $this->input->post('fname')." ".$this->input->post('lname'),
                'gender' => $this->input->post('gender'),
                'email' => $this->input->post('email'),
                'password' => $md5_pass,
                'user_type' => $user_type,
                'status' => $status,
                'phone' => $this->input->post('phone'),
                'profile_pic' => $profile_pic,
                'address' => $this->input->post('address'),
                'cell_provider' => $this->input->post('cell_provider'),
                'spk_english' => $this->input->post('spk_english'),
                'smoker' => $this->input->post('smoker'),
                'vehicle_id' => $vehicle_id,
                'added_by' => $_SESSION['logged_in']['id'],
                'date_created' => $date_created));
            $last_id = $this->db->insert_id();
           
        }

        if(!empty($last_id)){
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
            $date=date("Y-m-d H:i:s");
            $value = $this->db->query("SELECT value from tbl_cupon_value where cupon_type = 1")->row();
            $value = $value->value;
            $update = $this->db->query("UPDATE tbl_users set promo_code = '".$cupon."' where id='".$last_id."'");
            $insert_cupon = $this->db->query("INSERT INTO tbl_cupon(promo_code,user_id,cupon_type,value,date_created) values ('$cupon','$last_id','1','$value','$date')"); 
            /*-------------------------*/
            /*------- Apply Referral-----*/
            if (!empty($this->input->post('referral_code'))) {
                $refData = array('user_id'=>$last_id,'promo_code'=>$this->input->post('referral_code'));
                $this->apply_referral($refData);
            }
            /*-------------------*/
            if ($this->input->post('vehicle_permit_type')) {
                $idate = date('Y-m-d', strtotime($this->input->post('insurance_exp_date')));
                $rdate = date('Y-m-d', strtotime($this->input->post('registration_exp_date')));
                $query = $this->db->insert('tbl_driver_vehicle_info', array('user_id' => $last_id,
                    'vehicle_id' => $vehicle_id,
                    'vehicle_type' => $vehicle_type,
                    'vehicle_model' => $this->input->post('vehicle_model'),
                    // 'vehicle_number' => $this->input->post('vehicle_number'),
                    'vehicle_permit_type' => $this->input->post('vehicle_permit_type'),
                    'insurance_number' => $this->input->post('insurance_number'),
                    'insurance_exp_date' => $idate,
                    'registration_number' => $this->input->post('registration_number'),
                    'state_identifier' => $this->input->post('state_identifier'),
                    'registration_exp_date' => $rdate,
                    'date_created' => $date_created));

                $vehicle_id = $this->db->insert_id();
                // return true;
            } else {
                $idate = date('Y-m-d', strtotime($this->input->post('insurance_exp_date')));
                $rdate = date('Y-m-d', strtotime($this->input->post('registration_exp_date')));

                $str = $this->input->post('registration_number');
                $rest = substr("$str", -2);
                if ($rest % 2 == 0) {
                    $type = "C";
                } else {
                    $type = "B";
                }
                $query = $this->db->insert('tbl_driver_vehicle_info', array('user_id' => $last_id,
                    'vehicle_id' => $vehicle_id,
                    'vehicle_type' => $vehicle_type,
                    'vehicle_model' => $this->input->post('vehicle_model'),
                    // 'vehicle_number' => $this->input->post('vehicle_number'),
                    'vehicle_permit_type' => $type,
                    'insurance_number' => $this->input->post('insurance_number'),
                    'insurance_exp_date' => $idate,
                    'registration_number' => $this->input->post('registration_number'),
                    'state_identifier' => $this->input->post('state_identifier'),
                    'registration_exp_date' => $rdate,
                    'date_created' => $date_created));

                //echo $this->db->last_query();die;
                // $vehicle_id = $this->db->insert_id();
                // return "another type";
            }
            $ldate = date('Y-m-d', strtotime($this->input->post('license_exp_date')));
            $query = $this->db->insert('tbl_driver_documents', array('user_id' => $last_id,
                'document' => $document,
                'vehicle_id' => $vehicle_id,
                'vehicle_image' => $vehicle_pic,
                'vehicle_insurance_image' => $insurance_pic,
                'vehicle_registration_image' => $registration_pic,
                'vehicle_registration_plate_image' => $registration_plate_pic,
                'driver_license_image' => $license_pic,
                'driver_license_number' => $this->input->post('license_number'),
                'driver_license_exp_date' => $ldate,
                'date_created' => $date_created));
            $this->db->trans_complete();
            return $last_id;
        }
        else 
        {
            return 0;
        }
    }

    function add_vehicle_type() {
        $data = array('vehicle_type' => $this->input->post('vehicle_type'),
            'capacity' => $this->input->post('capacity'),
            'base_rate' => $this->input->post('base_rate'),
            'per_km' => $this->input->post('per_km'),
            'per_min' => $this->input->post('per_min'),
            'waiting_charge' => $this->input->post('waiting_charge'),
            'traffic_charges' => $this->input->post('traffic_charges'),
            );
        $query = $this->db->insert('tbl_vehicle',$data);
    }

    function get_vehicle_list() {
        $this->db->select("*");
        $this->db->from('tbl_vehicle');
        $query = $this->db->get();
        return $query->result();
    }

    function add_users($data) {
        $data1=$this->db->query("select * from tbl_managers where email='".$data['email']."'");
        $data2 = $data1->result();
        $dataRows= $data1->num_rows();
                if($dataRows  == 1){
        $this->db->insert('tbl_managers', $data);
        return $this->db->insert_id();
        }
        elseif($dataRows > 1 ){
            return 0;
        }
        else{
            $this->db->insert('tbl_managers', $data);
            return $this->db->insert_id();
        }
    }

    function select($table_name,$select,$order_by=NULL,$order=NULL) {
        $this->db->select("$select");
        $this->db->from("$table_name");
        if ($order_by!="") {
            $this->db->order_by("$order_by","$order");
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function selectWhere($table_name,$select,$where)
    {
        $this->db->select("$select");
        $this->db->from("$table_name");
        if ($where!="") {
            $this->db->where($where);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function commission($driver_id)
    {
        $result = $this->db->query("SELECT *  FROM `tbl_commission` WHERE `driver_id`=$driver_id
ORDER BY forever asc")->result();
        return $result;
    }

    function delete($where,$table_name) {
        $this->db->delete($table_name, $where);
    }

    public function add_department($data) {
        $this->db->insert('tbl_department', $data);
    }

    public function add_permission($data)
    {
        $this->db->insert('tbl_permission', $data);
    }

    public function unbook(){
       $data= $this->db->query("select tbl_priceQuoteLog.*,(select tbl_jobs.estimate from tbl_jobs where tbl_jobs.id = tbl_priceQuoteLog.trip_id) as estimate,tbl_users.first_name from tbl_priceQuoteLog
        JOIN tbl_users on tbl_priceQuoteLog.user_id = tbl_users.id order by tbl_priceQuoteLog.id desc limit 1000")->result();
        // echo "<pre>"; 
        // print_r($data);die(); 
            return $data;
    }

    public function unbookdata($conditions)
    {
        if ($conditions['search']!="") {
            $like .= "and first_name like '%".$conditions['search']."%' or end_address like '%".$conditions['search']."%'";
        } else {
            $like = "";
        }
        

        $data= $this->db->query("select tbl_priceQuoteLog.*,(select tbl_jobs.estimate from tbl_jobs where tbl_jobs.id = tbl_priceQuoteLog.trip_id) as estimate,tbl_users.first_name from tbl_priceQuoteLog
        JOIN tbl_users on tbl_priceQuoteLog.user_id = tbl_users.id
        where 1=1 $like order by ".$conditions['order_by']." ".$conditions['order']." limit ".$conditions['limit']." offset ".$conditions['offset']."")->result();
        // print_r($this->db->last_query());die;
        $tot_filter= $this->db->query("select tbl_priceQuoteLog.*,(select tbl_jobs.estimate from tbl_jobs where tbl_jobs.id = tbl_priceQuoteLog.trip_id) as estimate,tbl_users.first_name from tbl_priceQuoteLog
        JOIN tbl_users on tbl_priceQuoteLog.user_id = tbl_users.id
        where 1=1 $like");
        // print_r($tot_filter->conn_id->affected_rows);die;
        $data['filteredRows'] = $tot_filter->conn_id->affected_rows;
        // echo "<pre>"; 
        // print_r($data);die(); 
            return $data;
    }

    public function booked(){
        $data= $this->db->query("SELECT tbl_jobs.date_created, tbl_jobs.pickup_location, tbl_jobs.dropoff_location, tbl_jobs.fare, tbl_jobs.driver_id, tbl_jobs.user_id, tbl_jobs.id AS trip_id, (SELECT tbl_users.name FROM tbl_users WHERE tbl_jobs.driver_id = tbl_users.id ) AS driver, ( SELECT tbl_users.name FROM tbl_users WHERE tbl_jobs.user_id = tbl_users.id ) AS client, (SELECT tbl_driver_vehicle_info.vehicle_permit_type FROM tbl_driver_vehicle_info WHERE tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id) AS vehicle_type FROM tbl_jobs JOIN tbl_users ON tbl_users.id = tbl_jobs.user_id where tbl_jobs.status = 4 order by tbl_jobs.id desc limit 1000")->result();     
        return $data;
   }


    function archive($status) {
        $this->db->select('tbl_users.*,tbl_jobs.*,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.user_id) as cleint,(select vehicle_type from tbl_driver_vehicle_info where tbl_users.id=tbl_driver_vehicle_info.user_id) as vehicle, (select registration_number from tbl_driver_vehicle_info where tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id) as vehicle_number');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.driver_id');
        // $this->db->join('tbl_driver_vehicle_info', 'tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id');
        $this->db->where('tbl_jobs.status',$status);
        $this->db->order_by("tbl_jobs.id desc");

        $query = $this->db->get();
    //    print_r($this->db->last_query());die();
        $result = $query->result();
        
        return $result;
    }

    public function requestCancelled()
    {
        $query = $this->db->query("SELECT tbl_jobs.*,tbl_users.name FROM `tbl_jobs`
JOIN tbl_users on tbl_users.id=tbl_jobs.user_id
where driver_id=0
and tbl_jobs.status=50 ORDER BY tbl_jobs.id desc")->result();
        return $query;
    }

    public function userInfo($id)
    {
        //         select tbl_priceQuoteLog.*,(SELECT tbl_jobs.date_created FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS date_create,(SELECT tbl_jobs.modified_on FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS modified_date,(SELECT tbl_jobs.estimate FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS estimate,tbl_users.* from tbl_priceQuoteLog
        // JOIN tbl_users on tbl_priceQuoteLog.user_id = tbl_users.id where tbl_priceQuoteLog.id =

        $data= $this->db->query("select tbl_priceQuoteLog.*,(SELECT tbl_jobs.date_created FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS date_create,(SELECT tbl_jobs.pickup_lat FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS pickup_lat,(SELECT tbl_jobs.pickup_long FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS pickup_long,(SELECT tbl_jobs.dropoff_lat FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS dropoff_lat,(SELECT tbl_jobs.dropoff_long FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS dropoff_long,(SELECT tbl_jobs.modified_on FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS modified_date,(SELECT tbl_jobs.estimate FROM tbl_jobs WHERE tbl_jobs.id = tbl_priceQuoteLog.trip_id ) AS estimate,tbl_users.* from tbl_priceQuoteLog JOIN tbl_users on tbl_priceQuoteLog.user_id = tbl_users.id where tbl_priceQuoteLog.id =$id")->result();          
        return $data;
    }
    public function userBookedInfo($id)
    {
        $data= $this->db->query(" SELECT tbl_jobs.date_created, tbl_jobs.pickup_location,tbl_jobs.accept_datetime,tbl_jobs.arrived_datetime,tbl_jobs.job_start_datetime,tbl_jobs.job_completed_datetime,tbl_jobs.dropoff_location, tbl_jobs.fare, tbl_jobs.driver_id, tbl_jobs.user_id, tbl_jobs.payment_method,tbl_jobs.rating , tbl_jobs.id AS trip_id, (SELECT tbl_users.name FROM tbl_users WHERE tbl_jobs.driver_id = tbl_users.id ) AS driver,(SELECT tbl_users.id FROM tbl_users WHERE tbl_jobs.driver_id = tbl_users.id ) AS driver_id,(SELECT tbl_users.id FROM tbl_users WHERE tbl_jobs.user_id = tbl_users.id ) AS client_id, ( SELECT tbl_users.name FROM tbl_users WHERE tbl_jobs.user_id = tbl_users.id ) AS client, (SELECT tbl_driver_vehicle_info.vehicle_permit_type FROM tbl_driver_vehicle_info WHERE tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id
        ) AS vehicle_type FROM tbl_jobs JOIN tbl_users ON tbl_users.id = tbl_jobs.user_id
        where tbl_jobs.id = $id")->result();         
        return $data;
    }

    public function userDatas($id){
      $result = $this->db->query("select * from tbl_coupon where id =$id")->row();
      return $result;
    }   

    
    function trip_info($id){
        $this->db->select('name,email,phone,profile_pic,tbl_jobs.*,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.user_id) as cleint,(select vehicle_type from tbl_driver_vehicle_info where tbl_users.id=tbl_driver_vehicle_info.user_id) as vehicle');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.driver_id','left');
        $this->db->where('tbl_jobs.id',$id);

        $this->db->order_by("driver_id");

        $query = $this->db->get();

        $result = $query->result();

        return $result;
    }

    function get_coordinates($data) {
        
        $return = array();
        $this->db->select("tbl_users.id,tbl_users.name,first_name,last_name,vehicle_type,(CASE WHEN (SELECT id from tbl_permit where now() BETWEEN `from` and `to` and driver_id=tbl_users.id) IS NOT NULL THEN 'A' ELSE vehicle_permit_type END ) AS vehicle_permit_type,latitude,longitude,is_free");
        $this->db->from("tbl_users");
        $this->db->join('tbl_driver_vehicle_info', 'tbl_driver_vehicle_info.user_id = tbl_users.id');
        $this->db->join('tbl_login', 'tbl_login.user_id = tbl_users.id');

        if($data == "" || $data == "0"){
            $this->db->where('user_type', '2')->where('availability','1')->where('latitude !=', '')->where('tbl_login.status',1);

        }elseif($data == 1){
            $this->db->where('user_type', '2')->where('is_free', '1')->where('availability','1')->where('tbl_login.status',1);
        }elseif($data == 2){
            $this->db->join('tbl_jobs', 'tbl_jobs.driver_id = tbl_users.id');

            $this->db->where('user_type', '2')->where('is_free', '0')->where('availability','1')->where('tbl_login.status',1)->where('tbl_jobs.status <=',3);
            $this->db->group_by('tbl_users.id');
        }
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $row) {
                array_push($return, $row);
            }
        }
     // print_r($this->db->last_query());
        return $return;
    }

    public function get_latlng($id)
    {
        $return = array();
        $this->db->select("tbl_users.id,tbl_users.name,first_name,last_name,vehicle_type,(CASE WHEN (SELECT id from tbl_permit where now() BETWEEN `from` and `to` and driver_id=tbl_users.id) IS NOT NULL THEN 'A' ELSE vehicle_permit_type END ) AS vehicle_permit_type,latitude,longitude,is_free");
        $this->db->from("tbl_users");
        $this->db->join('tbl_driver_vehicle_info', 'tbl_driver_vehicle_info.user_id = tbl_users.id','left');
        $this->db->join('tbl_login', 'tbl_login.user_id = tbl_users.id','left');
        $this->db->where('tbl_users.id',$id);
        $query = $this->db->get()->row();
        // if ($query->num_rows() > 0) {
        //     foreach ($query->result() as $row) {
        //         array_push($return, $row);
        //     }
        // }
     // print_r($this->db->last_query());
        return $query;
    }

    public function get_coordinatesClient()
    {
        $user_info = $this->db->query("SELECT id,name,email,phone,latitude,longitude FROM `tbl_users` where tbl_users.id IN (select user_id from tbl_jobs where is_active = 0) and latitude != 0 and tbl_users.id IN (SELECT user_id FROM `tbl_login` where status = 1)")->result();
       
        return $user_info;
    }

    public function getUserLogin($to)
    {
        if ($to==1) {
            $query = $this->db->query("SELECT token_id FROM `tbl_login` where user_id IN (select id from tbl_users where user_type=2 and availability=1 and is_free=1) and status = 1")->result();
        }elseif ($to==2) {
            $query = $this->db->query("SELECT token_id FROM `tbl_login` where user_id IN (select id from tbl_users where user_type=2 and availability=1 and is_free=0) and status = 1")->result();
        } else {
            $query = $this->db->query("SELECT token_id FROM `tbl_login` where user_id IN (select id from tbl_users where user_type=0) and status = 1")->result();
        }
        return $query;
    }

    public function android($token_id, $message, $ride_details=NULL, $action=NULL,$job_id=NULL,$id=NULL, $name=NULL, $profile_pic=NULL, $datetime=NULL) {
    //    echo $message;die;  
        $api_key = "AIzaSyB-nEUOm298Ps46jVn3csbuBByR7FFdxKo";
        $registration_ids = $token_id;
        $gcm_url = 'https://android.googleapis.com/gcm/send';
        $fields = array(
            'registration_ids' => array($registration_ids),
            //'data' => array( "message" => $message,"itemname"=>$itemname,"purchased_amount"=>$purchased_amount),
            'data' => array("message" => $message,
                'job_id' => $job_id,
                'ride_details' => $ride_details,
                'action' => $action,
                'id' => $id,
                'name' => $name,
                'profile_pic' => $profile_pic,
                'datetime' => $datetime,
                ),
        );
        $headers = array(
            'Authorization: key=' .$api_key,
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

    public function add_holiday($data)
    {
         // print_r($data);die;
        $this->db->insert('tbl_holidays',$data);
    }

    public function get_even()
    {
        $q = $this->db->get('tbl_even')->result();
        return $q;
    }

    public function user_cancelled_trips()
    {
        $this->db->select('name,email,phone,profile_pic,tbl_jobs.id,user_id,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.user_id) as cleint, count(user_id) as count');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.user_id');
        $this->db->where('tbl_jobs.status',50);
        $this->db->where('tbl_jobs.driver_id !=',0);
        // $this->db->order_by("driver_id");
        $this->db->group_by("user_id");
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    public function user_cancelled_trips_detail($id)
    {
        // print_r($id);
        $this->db->select('name,email,phone,profile_pic,tbl_jobs.*,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.user_id) as driver,(select vehicle_type from tbl_driver_vehicle_info where tbl_jobs.user_id=tbl_driver_vehicle_info.user_id) as vehicle');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.user_id');
        $this->db->where('tbl_jobs.status',50);
        $this->db->where('tbl_jobs.user_id',$id);
        $this->db->where('tbl_jobs.driver_id != ',0);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function driver_cancelled_trips()
    {
        $this->db->select('name,email,phone,profile_pic,tbl_jobs.id,driver_id,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.driver_id) as driver, count(driver_id) as count');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.driver_id');
        $this->db->where('tbl_jobs.status',52);
        // $this->db->order_by("driver_id");
        $this->db->group_by("driver_id");
        $query = $this->db->get();

        $result = $query->result();

        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';die;
        return $result;
    }

    public function driver_cancelled_trips_detail($id)
    {
        // print_r($id);
        $this->db->select('name,email,phone,profile_pic,tbl_jobs.*,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.driver_id) as driver,(select vehicle_type from tbl_driver_vehicle_info where tbl_jobs.driver_id=tbl_driver_vehicle_info.user_id) as vehicle');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.driver_id');
        $this->db->where('tbl_jobs.status',52);
        $this->db->where('tbl_jobs.driver_id',$id);


        $query = $this->db->get();

        $result = $query->result();

        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';die;
        return $result;
    }

    public function low_rating()
    {
        $this->db->select('tbl_jobs.id,name,email,phone,profile_pic,tbl_jobs.*,(select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.driver_id) as driver,(select vehicle_type from tbl_driver_vehicle_info where tbl_jobs.driver_id=tbl_driver_vehicle_info.user_id) as vehicle');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users', 'tbl_users.id = tbl_jobs.driver_id');
        // $this->db->where('tbl_users.user_type',2);
        $this->db->where('tbl_jobs.rating >',0);
        $this->db->where('tbl_jobs.rating <',3);


        $query = $this->db->get();

        $result = $query->result();

            // echo '<pre>';
            // print_r($result);
            // echo '</pre>';die;
        return $result;
    }

    public function low_overall_rating()
    {
        $this->db->select('id,name,email,phone,profile_pic,rating');
        $this->db->from('tbl_users');
        $this->db->where('user_type',2);
        $this->db->where('rating >',0);
        $this->db->where('rating <',3.5);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    public function exp_date()
    {
        $date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+30 days"));
        $this->db->select('tbl_driver_vehicle_info.*,name,email,phone,profile_pic,tbl_driver_documents.*');
        $this->db->from('tbl_driver_vehicle_info');
        $this->db->join('tbl_users','tbl_users.id = tbl_driver_vehicle_info.user_id');
        $this->db->join('tbl_driver_documents','tbl_driver_documents.user_id = tbl_driver_vehicle_info.user_id');
        $this->db->where("(insurance_exp_date >='$date' AND insurance_exp_date <= '$end_date') OR (driver_license_exp_date >='$date' AND driver_license_exp_date <= '$end_date') OR (registration_exp_date >='$date' AND registration_exp_date <= '$end_date')" );
        $this->db->order_by('driver_license_exp_date');
        $query = $this->db->get();
        $result = $query->result();
        // print_r($this->db->last_query());die;
        return $result;
    }

    public function driver_refuses($id=NULL)            // dashboard controller
    {
        
        $this->db->select('tbl_jobs.*,c.name as client_name,b.name,b.first_name,b.last_name,b.profile_pic,b.phone,b.email');
        $this->db->from('tbl_jobs');
        $this->db->join('tbl_users b','b.id = tbl_jobs.driver_id'); /* DRIVER DETAILS */
        $this->db->join('tbl_users c','c.id = tbl_jobs.user_id'); /* CLIENT DETAILS */
        if ($id!=NULL) {
           $this->db->where('driver_id',$id);
        }
        $this->db->order_by('id');
        $query = $this->db->get();

        $result = $query->result();

        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';die;
        return $result;
    }

    public function get_where($table_name,$where)
    {
        $query = $this->db->get_where("$table_name",$where)->result();
        return $query;
    }

    public function update($table_name,$id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update($table_name, $data); 
    }

    public function updateWhere($table_name,$where,$data)
    {
        $this->db->where($where);
        $this->db->update($table_name, $data); 
    }

    public function insert($table_name,$data)
    {
        $this->db->insert("$table_name",$data);
    }

    public function getCoords()
    {
        $q = $this->db->select('latitude,longitude')->from('tbl_users')->where('id',16)->get()->result();
        return $q;
    }

    public function driver_detail($job_id)
    {
        $query = $this->db->select("tbl_jobs.driver_id, tbl_users.name,tbl_users.latitude,tbl_users.longitude")
                            ->from("tbl_jobs")
                            ->join("tbl_users","tbl_users.id = tbl_jobs.driver_id")
                            ->where("tbl_jobs.id",$job_id)
                            ->get()
                            ->result();
        return $query ;
    }
     public function cancel_ride($data)
    {
        // echo "string";die;
        $data = $this->db->escape($data);     
             // print_r($data); die;
           $update = $this->db->query("UPDATE tbl_jobs SET status = '51', is_active = '0', modified_on = NOW(),notified=0 WHERE id = ".$data['job_id']." AND user_id = ".$data['client_id']." ");

            $update = $this->db->query("UPDATE tbl_users SET  push_time = null, is_free = '1' WHERE pjob_id = ".$data['job_id']." ");

        $this->db->query("UPDATE tbl_promocode SET status = '0' WHERE job_id = ".$data['job_id']." ");
        return true;
    }


    public function driverInfo($id)
    {
        $query = $this->db->query("SELECT u.id,u.name,u.phone,vi.insurance_exp_date,dd.driver_license_exp_date,dd.contract_exp_date,p.from as permit_start, p.to as permit_exp FROM tbl_users as u LEFT JOIN `tbl_driver_vehicle_info` as vi on vi.user_id=u.id LEFT JOIN `tbl_driver_documents` as dd on dd.user_id=u.id LEFT JOIN `tbl_permit` as p on p.driver_id=u.id where u.id=$id")->row();
        return $query;
    }

    public function add_promo($data)
    {
        $query = $this->db->insert('tbl_coupon',$data);
    }

    public function cupon_list()
    {
        $query = $this->db->query("select * from tbl_coupon")->result();
        return $query;
    }

    public function get($table_name)
    {
        return $this->db->get("$table_name")->result();
    }

    public function autoDeactivate()
    {
        // echo "st";die;
        // $query = $this->db->query("SELECT `tbl_users`.id, `name`, `first_name`, `last_name`, `email` FROM `tbl_users` JOIN `tbl_driver_vehicle_info` ON `tbl_users`.`id` = `tbl_driver_vehicle_info`.`user_id` JOIN `tbl_driver_documents` ON `tbl_driver_documents`.`user_id` = `tbl_users`.`id` WHERE (`insurance_exp_date` < '2016-07-05') OR (`driver_license_exp_date` < '2016-07-05') OR (`registration_exp_date` < '2016-07-05')")->result();
        
        $this->db->query("UPDATE `tbl_users` JOIN `tbl_driver_vehicle_info` ON `tbl_users`.`id` = `tbl_driver_vehicle_info`.`user_id` JOIN `tbl_driver_documents` ON `tbl_driver_documents`.`user_id` = `tbl_users`.`id` set `tbl_users`.`status` = 0 WHERE `insurance_exp_date` < now() OR `driver_license_exp_date` < now() OR `registration_exp_date` < now()");
        // echo "<pre>"; print_r($query); echo "</pre>";
    }

    public function getReports($dayIndex)
    {
        if ($dayIndex==6) {
            $db_result = $this->db->query("SELECT tbl_adminPayment.*,DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())-1 DAY) latest_sun, tbl_users.id, name,phone,profile_pic,email,tbl_users.wallet_balance as currentWB FROM `tbl_adminPayment` join tbl_users on tbl_users.id=tbl_adminPayment.user_id having DATE(paidOn)=latest_sun")->result();
        }elseif ($dayIndex==2) {
            $db_result = $this->db->query("SELECT tbl_adminPayment.*,DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+3 DAY) latest_wed, tbl_users.id, name,phone,profile_pic,email,tbl_users.wallet_balance as currentWB FROM `tbl_adminPayment` join tbl_users on tbl_users.id=tbl_adminPayment.user_id having DATE(paidOn)=latest_wed")->result();
        }elseif ($dayIndex==4) {
            $db_result = $this->db->query("SELECT tbl_adminPayment.*,DATE_SUB(DATE(NOW()), INTERVAL DAYOFWEEK(NOW())+1 DAY) latest_fri, tbl_users.id, name,phone,profile_pic,email,tbl_users.wallet_balance as currentWB FROM `tbl_adminPayment` join tbl_users on tbl_users.id=tbl_adminPayment.user_id having DATE(paidOn)=latest_fri")->result();
        } else {
            $db_result = $this->db->query("SELECT tbl_adminPayment.*, tbl_users.id, name,phone,profile_pic,email,tbl_users.wallet_balance as currentWB FROM `tbl_adminPayment` join tbl_users on tbl_users.id=tbl_adminPayment.user_id order by paidOn desc")->result();
        }
        
        // print_r($db_result);die;
        return $db_result;
    }

    public function mapNotifications()
    {
        // $this->db->trans_start();
        // $this->db->query("UPDATE tbl_jobs set modified_on=now()");
        $query = $this->db->query("SELECT a.id,a.user_id,a.driver_id,(select tbl_vehicle.vehicle_type from tbl_vehicle where tbl_vehicle.id=a.vehicle_id) as client,(select tbl_issue_list.issue from tbl_issue_list where tbl_issue_list.id=a.reason_id) as reason,a.estimate,a.fare,a.status,a.pickup_location,a.dropoff_location,a.status,d.issue as reason,b.name as passenger,c.name as driver,a.modified_on,TIMESTAMPDIFF(MICROSECOND,`modified_on`,now()) as diff FROM `tbl_jobs` a
            join tbl_users b on (a.user_id=b.id)
            left join tbl_users c on (a.driver_id=c.id)
            left join tbl_issue_list d on (a.reason_id=d.id)
            where TIMESTAMPDIFF(MICROSECOND,`modified_on`,now()) <= 1000000
            -- and notified = 0
            ORDER BY a.modified_on desc")->result();
        // $this->db->query("UPDATE tbl_jobs set notified=0 where TIMESTAMPDIFF(SECOND,`modified_on`,now()) < 3");
        // $this->db->trans_complete();
        return $query;
    }

    public function mapNotificationsAll()
    {
        // $this->db->query("UPDATE tbl_jobs set modified_on=now()");
        $query = $this->db->query("SELECT a.id,(select tbl_vehicle.vehicle_type from tbl_vehicle where tbl_vehicle.id=a.vehicle_id) as client,(select tbl_issue_list.issue from tbl_issue_list where tbl_issue_list.id=a.reason_id) as reason,a.fare,a.estimate,a.status,a.vehicle_id,a.pickup_location,a.driver_id,a.user_id,a.dropoff_location,a.status,b.first_name as passenger,c.name as driver,a.modified_on,TIMESTAMPDIFF(SECOND,`modified_on`,now()) as diff FROM `tbl_jobs` a
            join tbl_users b on (a.user_id=b.id)
            left join tbl_users c on (a.driver_id=c.id)
            -- and notified = 0
            ORDER BY a.modified_on desc limit 30")->result();
        // $this->db->query("UPDATE tbl_jobs set notified=0 where TIMESTAMPDIFF(SECOND,`modified_on`,now()) < 3");
        return $query;
    }

    public function ongoingRide($driver_id)
    {
        // $this->db->trans_start();
        // $this->db->query("UPDATE tbl_jobs set modified_on=now()");
        $query = $this->db->query("SELECT a.id,a.user_id,a.driver_id,(select tbl_vehicle.vehicle_type from tbl_vehicle where tbl_vehicle.id=a.vehicle_id) as client,(select tbl_issue_list.issue from tbl_issue_list where tbl_issue_list.id=a.reason_id) as reason,a.estimate,a.fare,a.status,a.pickup_location,a.dropoff_location,a.status,d.issue as reason,b.name as passenger,c.name as driver,a.modified_on,TIMESTAMPDIFF(SECOND,`modified_on`,now()) as diff FROM `tbl_jobs` a
            join tbl_users b on (a.user_id=b.id)
            left join tbl_users c on (a.driver_id=c.id)
            left join tbl_issue_list d on (a.reason_id=d.id)
            where TIMESTAMPDIFF(SECOND,`modified_on`,now()) < 3
            -- and notified = 0
            and a.driver_id=$driver_id and a.is_active=1
            ORDER BY a.modified_on desc")->result();
        // $this->db->query("UPDATE tbl_jobs set notified=0 where TIMESTAMPDIFF(SECOND,`modified_on`,now()) < 3");
        // $this->db->trans_complete();
        return $query;
    }

    public function ongoingRideAll($driver_id)
    {
        $query = $this->db->query("SELECT a.id,(select tbl_vehicle.vehicle_type from tbl_vehicle where tbl_vehicle.id=a.vehicle_id) as client,(select tbl_issue_list.issue from tbl_issue_list where tbl_issue_list.id=a.reason_id) as reason,a.fare,a.estimate,a.status,a.vehicle_id,a.pickup_location,a.driver_id,a.user_id,a.dropoff_location,a.status,b.first_name as passenger,c.name as driver,a.modified_on,TIMESTAMPDIFF(SECOND,`modified_on`,now()) as diff FROM `tbl_jobs` a
            join tbl_users b on (a.user_id=b.id)
            left join tbl_users c on (a.driver_id=c.id)
            -- and notified = 0
            where a.driver_id=$driver_id and a.is_active=1
            ORDER BY a.modified_on desc limit 20")->result();
        // $this->db->query("UPDATE tbl_jobs set notified=0 where TIMESTAMPDIFF(SECOND,`modified_on`,now()) < 3");
        return $query;
    }

    public function managerInfo()
    {
        $query = $this->db->select('tbl_users.id,tbl_users.name,tbl_users.email,tbl_users.date_created,tbl_users_department.*,tbl_department.department_name')->from('tbl_users')->join('tbl_users_department','tbl_users.id=tbl_users_department.user_id','left')->join('tbl_department','tbl_users_department.department_id=tbl_department.department_id','left')->where('tbl_users.user_type','3')->get()->result();
        return $query;
    }

    public function logedInDrivers()
    {
        $query = $this->db->query("SELECT tbl_login.*,tbl_users.first_name,tbl_users.last_name,tbl_users.is_free as on_ride, tbl_users.email,tbl_users.phone, availability, (CASE WHEN (SELECT id from tbl_permit where now() BETWEEN `from` and `to` and driver_id=tbl_users.id) IS NOT NULL THEN 'A' ELSE vehicle_permit_type END ) AS vehicle_permit_type FROM tbl_login
            join tbl_users on tbl_users.id = tbl_login.user_id
            join tbl_driver_vehicle_info on tbl_users.id = tbl_driver_vehicle_info.user_id
            where tbl_login.user_id in (select id from tbl_users where user_type=2) and tbl_login.status=1 and tbl_users.availability=1 ")->result();
        return $query;
    }

    public function idleRides()
    {
        $query = $this->db->query("SELECT `tbl_users`.*, `tbl_jobs`.*, (select tbl_users.name from tbl_users where tbl_users.id=tbl_jobs.user_id) as cleint, (select vehicle_type from tbl_driver_vehicle_info where tbl_users.id=tbl_driver_vehicle_info.user_id) as vehicle, (select registration_number from tbl_driver_vehicle_info where tbl_driver_vehicle_info.user_id = tbl_jobs.driver_id) as vehicle_number FROM `tbl_jobs` JOIN `tbl_users` ON `tbl_users`.`id` = `tbl_jobs`.`driver_id` where (tbl_jobs.status=2 AND TIMESTAMPDIFF(MINUTE,`arrived_datetime`,now()) > 29) OR (tbl_jobs.status=3 AND TIMESTAMPDIFF(MINUTE,`job_start_datetime`,now()) > 61)")->result();
        // $query = $this->db->query("SELECT *, TIMESTAMPDIFF(MINUTE,`arrived_datetime`,now()) as idleOnLocTime, TIMESTAMPDIFF(MINUTE,`job_start_datetime`,now()) as idleInCarTime FROM `tbl_jobs` where (status=2 AND TIMESTAMPDIFF(MINUTE,`arrived_datetime`,now()) > 29) OR (status=3 AND TIMESTAMPDIFF(MINUTE,`job_start_datetime`,`job_completed_datetime`) > 61)")->result();
        return $query;
    }

    public function apply_referral($data)
    {
        $select = $this->db->query("SELECT id,promo_code, (select value from tbl_cupon_value where cupon_type ='3') as value FROM tbl_users WHERE promo_code = '".$data['promo_code']."' AND id != '".$data['user_id']."' and user_type=2");
        $query = $select->result();
        $value = $query[0]->value;
        $promo = $query[0]->promo_code;
        $provider = $query[0]->id;
        if ($promo != "") {
            $select = $this->db->query("SELECT * FROM tbl_promocode WHERE promocode_beneficiary_id = '".$data['user_id']."' AND cupon_type = '3'");
            $query = $select->result();
            if($query[0]->id == ""){
                $insert = $this->db->query("INSERT INTO tbl_promocode (promocode,promocode_provider_id,promocode_beneficiary_id,value, cupon_type, status,date_created) VALUES('".$promo."','".$provider."','".$data['user_id']."', $value,'3','0',NOW())");
                return "yo";
            }else{
                return "exist";
            }
        }else{
            return false;
        }
        // print_r($query);
        // die;
    }

    public function checkReferral($data)
    {
        $select = $this->db->query("SELECT id,promo_code FROM tbl_users WHERE promo_code = '".$data['promo_code']."' and user_type=2")->result();
        return $select;
    }

    public function driverStats()
    {
        $data = $this->db->query("SELECT a.driver_id,email,phone,count(a.id) as accepted,canceled,refused,completed FROM `tbl_jobs` as a 
            left join (select driver_id,count(id) as canceled from tbl_jobs where status=52 group by driver_id) b ON a.driver_id=b.driver_id
            left join (select driver_id,count(id) as completed from tbl_jobs where status=4 group by driver_id) b2 ON a.driver_id=b2.driver_id
            right join (select driver_id,count(id) as refused from tbl_response where response BETWEEN 1 and 2 group by driver_id) c ON a.driver_id=c.driver_id
            join tbl_users as d on a.driver_id=d.id 
            where a.driver_id!=0
            group by driver_id")->result();
        return $data;
    }

    public function forgotpassword($email) {
        $select_user = $this->db->query("SELECT * from tbl_users where email = '" . $email . "'");
        $get_user = $select_user->result();
        $userid = $get_user[0]->id;

        $static_key = bin2hex(mcrypt_create_iv(11, MCRYPT_DEV_URANDOM));
        $static_key2 = bin2hex(mcrypt_create_iv(11, MCRYPT_DEV_URANDOM));
        $id = $static_key2."_".$userid . "_" . $static_key;
        $result['b_id'] = base64_encode($id);

        $result['userid'] = $get_user[0]->id;
        $result['username'] = $get_user[0]->name;
        $datetime = date("Y-m-d H:i:s");
        $this->db->insert("tbl_password_recovery",array('user_id'=>$result['userid'],'reset_token'=>$result['b_id'],'date_created'=>$datetime));

        return $result;
    }

    public function findUser($match)
    {
        $this->db->select('id,phone');
        $this->db->like('phone', $match);
        $this->db->limit(5);
        $res = $this->db->get('tbl_users')->result();
        return $res;
    }
}

?>