<?php
class Verification extends CI_Controller { 
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library('form_validation');
	}
	function index(){  
		$id = $_GET['id'];
		$id2 = base64_decode($id);
		$explode_data = explode("_", $id2);
		// print_r($explode_data);
		// die();
		$id1 = $explode_data[0];
		$static_key = "afvsdsdjkldfoiuy4uiskahkhsajbjksasdasdgf43gdsddsf";
		$salt = $explode_data[1];
		// print_r($salt);die;
		if ($salt!=$static_key) {
			$this->load->view('errors/404');
		}else{
			$data = array(
			'email_verified' => 1,
			);

			$this->db->where('id',$id1);
			$this->db->update('tbl_users', $data);       
			$data['query'] = $this->db->select('name,first_name,last_name,id')->from('tbl_users')->where('id',$id1)->get()->result();
			// print_r($data);die;
			$this->load->view('verification_view',$data);
			// $this->form_validation->set_rules('password2', 'Password', 'trim|required|matches[password1]');
			// if ($this->form_validation->run() == FALSE)
			// {
			// 	$this->load->view('verification_view',$data);
			// }
			// else
			// {
			// 	$data['password'] = array('password' => md5($this->post('password1')), );

			// 	$this->load->view('verification_view',$data);
			// }
			// redirect('http://www.masirapp.com/verifiedemail');
			$this->output->set_header('refresh:1; url=http://www.masirapp.com/verifiedemail');
		}		
	}

	function update_email()
	{
		// print_r("hekllllll");
		$id = $_GET['id']."<br>";
		$id2 = base64_decode($id);
		$explode_data = explode("_", $id2);
		$id1 = $explode_data[0];
		// print_r($explode_data);die;
		$email = $_GET['email'];
		$data = array(
			'email_verified' => 1,
			'email' => $email,
			);

		$this->db->where('id',$id1);
		$this->db->update('tbl_users', $data);       
		$data['query'] = $this->db->select('name,first_name,last_name,id')->from('tbl_users')->where('id',$id1)->get()->result();
		// print_r($data['query']);die;
		$this->load->view('verification_view',$data);
	}
}
?>