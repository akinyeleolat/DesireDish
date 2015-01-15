 <?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friendslist extends CI_Controller {

    function __construct(){     
        parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->library('session');
        
    }
    
    public function index() {       
        $user_id= $this->input->post('UserId');     
        $this->load->model('join_model');               
        $result = $this->join_model->get_friend_upload_list_by_id($user_id);        
        
        echo json_encode($result);                                  
    }   
}