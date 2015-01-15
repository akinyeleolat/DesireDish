<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mylist extends CI_Controller {

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
		$this->load->model('upload_model');				
		$result = $this->upload_model->get_list_by_id($user_id);		
		$i=0;
		echo json_encode($result);
		/*foreach ($result as $re){
			$dir='./uploads/'.$re['thumbimg_dir'];
			echo $dir;
			if( file_exists($dir) ){ 
				$fp=fopen($dir,"r"); 
				$file_size=filesize($dir);
				//下载文件需要用到的头 
				Header("Pragma: public");
				Header("Content-type: image/jpg"); 
				Header("Accept-Ranges: bytes"); 
				Header("Content-Length:".$file_size); 
				Header("Content-Disposition: attachment; filename=".$re['thumbimg_dir']); 			
				//ob_clean(); //Clean (erase) the output buffer  			
				//flush(); //刷新PHP程序的缓冲，而不论PHP执行在何种情况下（CGI ，web服务器等等）。该函数将当前为止程序的所有输出发送到用户的浏览器。   
				//readfile( $dir ); 
			
				$thumb_img = fread($fp, $file_size);
				//$img[$re['thumbimg_dir']]=$thumb_img;
				//echo json_encode($img);
				echo $thumb_img;				
				$i=$i+1;
				fclose($dir);
			} else {
				echo json_encode('{"errno":1, "errmsg":"no thumbimg exist"}');
			}			
		}*/					 						
	}	
}