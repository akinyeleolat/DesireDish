<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login_web extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
		
    }
	
	public function index() {
		//$facebook_id = $this->input->post('user_id');
        $user_name = $this->input->post('user_name');
		$password = $this->input->post('password');
		$this->load->model('user_model');	
		
		/*if ($facebook_id!=""){//user comes from facebook
			$id = $this->user_model->get_id_by_facebook($facebook_id, $user_name);
			if ($id >-1) {
            // 设置session中的参数            
				$this->session->set_userdata('is_logged_in', true);
				$this->session->set_userdata('user_id', $id);   
				$this->session->set_userdata('user_name', $user_name);  
				$this->load->view('home');
				return;
			}
			else {		
				$data['login_prompt']='Oops! Something went error. Try refreshing!';
				$data['sign_up_prompt']="";
				$this->load->view('welcome',$data);
				return;
			}
		}else{//normal user*/
			$result = $this->user_model->login($user_name, $password);
			if ($result >0) { // >0表示登陆成功
			// 设置session中的参数
				$row = $this->user_model->get_exposable_row_by_username($user_name);
				$this->session->set_userdata('is_logged_in', true);
				$this->session->set_userdata('user_id', $row['user_id']);   
				$this->session->set_userdata('user_name', $user_name);  
				$this->load->view('home');
				return;
			}
			else {		
				$data['login_prompt']='user name or password is not correct!';
				$data['sign_up_prompt']="";
				$this->load->view('welcome',$data);
				return;
			}
			
		//}				   	   				
    }
}