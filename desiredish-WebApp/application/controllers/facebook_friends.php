 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_friends extends CI_Controller {

    function __construct(){     
        parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');  
		$this->load->library('facebook');		
    }
    
    public function index() {   		
		$user_id = $this->input->post('UserId');
		$friends=$this->facebook->get_friends();
		//echo json_encode($friends);
		if($friends){
			try{								
				$facebook_id = $user['id'];
				$user_name = $user['name'];		
				$this->load->model('user_model');
				$id = $this->user_model->get_id_by_facebook($facebook_id, $user_name);
		
				$this->session->set_userdata('is_logged_in', true);
				$this->session->set_userdata('user_id', $id);   
				$this->session->set_userdata('user_name', $user_name);  							
		        //$this->session->set_userdata($ses_user);
				
				$this->load->view('home');							           
		
			}catch(FacebookApiException $e){
				error_log($e);
				$user = NULL;
			}		
		}
    }   
}