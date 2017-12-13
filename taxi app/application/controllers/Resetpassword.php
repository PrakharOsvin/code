<?php

class Resetpassword extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Admin_model', '', TRUE);
        $session_data = $this->session->userdata('logged_in');
        $this->load->view('template/header');
        //$this->load->view('header');         
        $this->load->library('form_validation');
        $this->load->library('session');
        //$this->load->helper('url');
    }

    /* reset password */

    public function Resetpassword($id) {
            $id2 = base64_decode($id);
            $explode_data = explode("_", $id2);
            $id1 = $explode_data[0];
            $data['userID'] = $id1;
            $data['encodeuserID'] = $id;

        if (isset($_POST['Resetpassword'])) {

        $this->form_validation->set_rules('newpassword', 'Password', 'trim|required');
        $this->form_validation->set_rules('cnewpassword', 'Password Confirmation', 'trim|required|matches[newpassword]');



        if ($this->form_validation->run() == FALSE) {
            $this->load->view('resetpassword', $data);
            $this->load->view('footer');
         
        
        } else {


            $message = [
                'id' => $id1,
                'password' => $this->input->post('newpassword')
            ];
            // print_r($message['password']);die;
            $this->Admin_model->Changepassword1($message);

            //$this->load->view('formsuccess');
        }

        }else{
            $this->load->view('resetpassword', $data);
            $this->load->view('footer');
        }
        
    }
    function finishTourament() {
        $this->db->select('*');
        $this->db->from('tbl_tournament');
        $stat = array('is_started' => 1, 'is_closed' => 0);
        $this->db->where($stat);
        $getTournament = $this->db->get();
        $getTournamentData = $getTournament->result();
        
        foreach ($getTournamentData as $tournament){
            $expiryDate = strtotime(date('Y-m-d H:i:s', strtotime($tournament->start_date_time.' +'.$tournament->days.' days')));
            echo $expiryDate.'<br>';
        }
        
        echo '<pre>';
        print_r($getTournamentData);die;

        
    }

}

?>