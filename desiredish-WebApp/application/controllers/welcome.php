<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	function __construct(){		
		parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
	}
	
	public function index() {
		// required parameters 
        /*if ("" == trim($this->input->post('UserId')) 
        {
            echo json_encode('{"errno":1, "errmsg":"parameter error"}');
            return;
        }*/
		$user_id= $this->input->post('UserId');
		$restaurant_name = $this->input->post('RestName');
		$description=$this->input->post('Description');
		$rating=$this->input->post('Rating');
		$is_restaurant=$this->input->post('isRestaurant');
		$dining_time=$this->input->post('time');
		$full_image_dir='uploads/'.$_FILES['f_file']['name'];			
		$small_image_dir='uploads/'.$_FILES['f_file2']['name'];
		$this->load->model('upload_model');
		$this->load->model('restaurant_model');
		$this->load->model('user_model');
		if ($is_restaurant){
			$restaurant_id=$this->restaurant_model->get_id($restaurant_name);
		} else {
			$restaurant_id=null;
		}		
		$user_name=$this->user_model->get_name($user_id);
		$upload_id = $this->upload_model->insert_new(  
			$dining_time,
			$full_image_dir,
			$small_image_dir,
			$description,
			$user_id,
			$user_name,
            $restaurant_id,
			$restaurant_name,
			$rating
        );
		
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size'] = '20000000';
		
		$this->load->library('upload', $config);
				
		
		//$this->upload_model->update_url($upload_id,$full_image_dir,$small_image_dir);
			 if ( ! $this->upload->do_upload('f_file'))
			{
				
				$error = array('error' => $this->upload->display_errors());     
				echo json_encode($error); 
			} 
			else
			{
				$data = array('upload_data' => $this->upload->data());  
				
				$response["fullimg_success"] = 1;
				echo json_encode($response); 
			}
						
			if ( ! $this->upload->do_upload('f_file2'))
			{				
				$error = array('error' => $this->upload->display_errors());     
				echo json_encode($error); 
			} 
			else
			{
				$data = array('upload_data' => $this->upload->data());   
				$respon["smallimg_success"] = 1;
				echo json_encode($respon);
			}
			
	}	
}