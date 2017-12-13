<?php
class Test extends CI_Controller { 
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form','url'));
		$this->load->library('form_validation');
	}
	function index(){  
		$this->load->view('new_map');
	}
}
?>