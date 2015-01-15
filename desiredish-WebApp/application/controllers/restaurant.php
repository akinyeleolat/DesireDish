<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Restaurant extends CI_Controller {

	function __construct(){		
		parent::__construct();
        header('Content-Type: text/html;charset=utf-8');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('session');
		
	}
	
	public function index() {		
		$restaurant_id= $this->input->post('RestaurantID');
	    $upload_id=$this->input->post('UploadID');
		
		$this->load->model('restaurant_model');
		$rest=$this->restaurant_model->get_all_by_id($restaurant_id);
		$this->load->model('upload_model');
		$rest['thumb_img']=$this->upload_model->get_thumb_by_upload_id($upload_id);
		echo json_encode($rest);				
	}	
}