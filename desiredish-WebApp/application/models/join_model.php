<?php
class join_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function get_friend_upload_list_by_id($user_id)
	{
		$sql = 'SELECT * FROM upload WHERE user_id IN (SELECT user2_id FROM friend WHERE user1_id=?) order by dining_time desc';
        $query = $this->db->query($sql, $user_id);
        if ($query->num_rows() > 0) {
			$result=$query->result_array();
			return $result;            
        }
        else {
            return null;
        }
	}
}