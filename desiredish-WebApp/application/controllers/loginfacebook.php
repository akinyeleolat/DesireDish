 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loginfacebook extends CI_Controller {

    function __construct(){     
        parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');        
    }
    
    public function index() {   
		$facebook_id = $this->input->post('FacebookId');
        $user_name = $this->input->post('Email');
		
		$this->load->model('user_model');
		$id = $this->user_model->get_id_by_facebook($facebook_id, $user_name);
		if ($id >-1) {      
			$data['success']=1;
			$data['user_id']=$id;
			echo json_encode($data);
		}
		
		else {		
			$data['success']=0;
			echo json_encode($data);
		}        
    }   
}