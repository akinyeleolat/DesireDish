<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct(){
		parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
	}
	
	public function index()
	{		
		if (!$this->session->userdata('is_logged_in')){
			$data['login_prompt']="";
			$data['sign_up_prompt']="";
			$this->load->view('welcome',$data);
		} else{
			//echo $this->session->userdata('user_id');
			$this->load->view('home');
		}
		
	}	
	/*function fblogin(){
		$base_url=$this->config->item('base_url');
					
		$user = $this->facebook->get_user();
		if(!empty($user)){
			try{
				//$user_profile = $facebook->api('/me');
				$params = array('next' => $base_url.'fbci/logout');
				$ses_user=array('User'=>$user,
				   'logout' =>$this->facebook->logout_url()//$facebook->getLogoutUrl($params)
				);
		        $this->session->set_userdata($ses_user);
				header('Location: '.$base_url);
			}catch(FacebookApiException $e){
				error_log($e);
				$user = NULL;
			}		
		}	
	}*/
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */