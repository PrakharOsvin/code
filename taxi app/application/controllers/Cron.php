<?php

class Cron extends CI_Controller {

    function __construct() {
      error_reporting(E_ALL); ini_set('display_errors', 1);
        parent::__construct();
        $this->load->model('User_model_new','User_model');
        $this->load->database();
    }

    public function index()
    {
/*//$baseurl = base_url();
// echo base_url()."api/UserNew/respond?user_id=".$keyvalue->id."&job_id=".$keyvalue->pjob_id."&response=1"; die;
      echo "string";
      // $this->db->query("INSERT INTO test set time=now()");
      // Busy driver due to phone turn off
      $busy_foult = $this->User_model->busy_foult();
      if (count($busy_foult)>0) {
              foreach ($busy_foult as $keyvalue) {
// print_r($keyvalue->pjob_id);die;
                $channel = curl_init();
                curl_setopt($channel, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
                curl_setopt( $channel, CURLOPT_URL, "http://91.232.66.67/Admin/api/UserNew/respond?user_id=".$keyvalue->id."&job_id=".$keyvalue->pjob_id."&response=1" );
                curl_setopt( $channel, CURLOPT_RETURNTRANSFER, 1 ); 
                $response= curl_exec ( $channel );
                curl_close ( $channel );

          }
      }
      echo "<pre>"; 
      print_r($busy_foult); 
      // print_r($json);
      // print_r($response);
      echo "</pre>";
      // redirect();
      $this->User_model->autoDeactivate();
      $arriving = $this->User_model->set_arriving();
      return true;*/
    }

    function paymentCron()
    {
      // echo "string";
      $walletCap = 0;
      $datetime = date("Y-m-d H:i:s");

      $this->db->trans_start();
      $users = $this->User_model->selectdata('tbl_users', 'id,wallet_balance', array('user_type'=>2,'wallet_balance > '=>$walletCap));
      // var_dump($users);
      $this->User_model->updatedata('tbl_users', array('user_type'=>2,'wallet_balance >'=>$walletCap), array('wallet_balance'=>0));

      foreach ($users as $value) {
        // print_r($value->id);echo "<br>";
        $amountPaid = $value->wallet_balance-$walletCap;
        $remaining_wb = $value->wallet_balance-$amountPaid;
        $paymentData = array('user_id'=>$value->id,'amountPaid'=>$amountPaid,'paidOn' => $datetime,'wallet_balance'=>$value->wallet_balance,'remaining_wb'=>$remaining_wb);
        $this->User_model->insertdata("tbl_adminPayment",$paymentData);
      }
      $this->db->trans_complete();
    }
}
