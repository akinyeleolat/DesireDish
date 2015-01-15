<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Followfriend extends CI_Controller {

	function __construct(){		
		parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
	}
	
	public function index() {		
		$user_id= $this->input->post('UserId');		
		$friend_name= $this->input->post('FriendName');
		$this->load->model('friend_model');							
		$result['success']=$this->friend_model->follow($user_id,$friend_name);	
		if ($result['success']==0){
			$result['message']='You have already followed this friend!';
		}else if ($result['success']==-1){
			$result['message']='The user name does not exist!';
		}
		echo json_encode($result);			 						
	}	
}