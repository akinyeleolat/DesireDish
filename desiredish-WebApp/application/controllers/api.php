<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Api extends CI_Controller {
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
    }
	
	public function index() {
		exit('Access denied');        
    }	
	public function restaurant(){
		$name = $this->input->get('name');
		//echo $name;
		$this->load->model('restaurant_model');	
		$id=$this->restaurant_model->get_id($name);
		//echo $id;
		if ($id!=null) {
			$rest=$this->restaurant_model->get_all_by_id($id);
			$data['restaurant']=$rest;
		}
		
		$this->load->model('upload_model');	
		$info=$this->upload_model->get_by_restaurant_name($name);
				
		$data['data']=$info;
		echo json_encode($data);     
		return;
	}
}