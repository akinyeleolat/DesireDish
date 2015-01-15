<?php
class restaurant_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function get_id($restaurant_name) {
        $sql = 'SELECT RestaurantID FROM Restaurant WHERE RName LIKE ? ';
        $query = $this->db->query($sql, $restaurant_name);
		//echo $query->num_rows();
        if ($query->num_rows() > 0) {
			//echo $query;
            return $query->row()->RestaurantID;
        }
        else {			
            return null;
        }
    }
	
	function get_all_by_id($restaurant_id) {
        $sql = 'SELECT * FROM Restaurant WHERE RestaurantID=? ';
        $query = $this->db->query($sql, $restaurant_id);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;
		} 
		else {
			$result['success']=0;
			$result['message']='The restaurant id does not exist';
			return $result;
		}
    }
}