<?php
class upload_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	function insert_new(
		$dining_time,
		$full_image_dir,
		$small_image_dir,
        $description,
			$user_id,
			$user_name,
            $restaurant_id,
			$restaurant_name,
			$rating)
    {   
        $sql='INSERT INTO 
            upload(dining_time, img_dir, thumbimg_dir, description, restaurant_id, restaurant_name, user_id, user_name, rating) 
            values (?,?,?,?,?,?,?,?,?)';
		
        $params = array(
            $dining_time,$full_image_dir, $small_image_dir,$description, $restaurant_id, $restaurant_name, $user_id, $user_name, $rating
        );
        if (!$this->db->query($sql, $params)) {
            echo $this->db->_error_message();
            return null;
        }
        return $this->get_id($user_id);
    }
	function get_list_by_id($user_id)
	{
		$sql = 'SELECT * FROM upload WHERE user_id=? order by dining_time desc';
        $query = $this->db->query($sql, $user_id);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
	function get_thumb_by_upload_id($upload_id)
	{
		$sql = 'SELECT thumbimg_dir FROM upload WHERE upload_id=?';
        $query = $this->db->query($sql, $upload_id);
        if ($query->num_rows() > 0) {			
			return $query->row()->thumbimg_dir;            
        }
        else {
            return null;
        }
	}
	function get_id($user_id) {
        $sql = 'SELECT upload_id FROM upload WHERE user_id=? order by dining_time desc LIMIT 1';
        $query = $this->db->query($sql, $user_id);
        if ($query->num_rows() > 0) {
            return $query->row()->upload_id;
        }
        else {
            return null;
        }
    }
	function upload_url($upload_id,$full_image_dir,$small_image_dir){
		$sql = 'UPDATE upload SET img_dir=? WHERE upload_id=?';		
        $this->db->query($sql, array($full_image_dir,$upload_id));
		$sql = 'UPDATE upload SET thumbimg_dir=? WHERE upload_id=?';		
        $this->db->query($sql, array($small_image_dir,$upload_id));		
	}
	function get_by_restaurant_name($name){
		$sql = 'SELECT img_dir, thumbimg_dir, description, rating FROM upload WHERE restaurant_name=? order by dining_time desc';
        $query = $this->db->query($sql, $name);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
}