<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sign_up extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		header('Content-Type: text/html;charset=utf-8');
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->library('session');
	}
	public function index() {
		date_default_timezone_set("EST");
        $time_stamp = date("Y-m-d H:i:s");
        // 获得参数		
        $user_name = $this->input->post('user_name');
        $password = sha1( $this->input->post('password').$time_stamp);    
        // 读取model        
		$this->load->model('user_model');		
		// 检查是否username已被注册
		$result=$this->user_model->check($this->input->post('user_name'));            
        if(!$result){ 
			// 设置session中的参数，自动登录
			$re=$this->user_model->register_simple($user_name,$password,$time_stamp);
			$response["user_id"]=$re;
			$response["success"] = 1;
			echo json_encode($response);      
			return;
		}
		else {
			$response["success"] = 0;
			$response["error_message"]='the user name has been used, please change another one.';			
			echo json_encode($response); 
			return;						
		}	 
    }
}
?>
