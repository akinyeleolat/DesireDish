<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
    }
	
	public function index() {
				
        // 获得参数
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');
        
        // 读取model
        
		$this->load->model('user_model');	
	
	    // 判断是否通过登陆
		$result = $this->user_model->login($user_name, $password);
        if ($result >0) { // >0表示登陆成功
            $response["success"] = 1;$response["user_id"]=$result;
			echo json_encode($response);                    			
			return;
        }
        else {
            $response["success"] = 0;
			$response["error_message"]='user name or password is not correct!';			
			echo json_encode($response);     
			return;
        }
    }	
}