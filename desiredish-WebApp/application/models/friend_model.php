<?php
class friend_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	function follow($user_id, $friend_name)
	{		
		$this->load->model('user_model');		
		$friend_id=$this->user_model->get_id($friend_name);
		if ($friend_id<0){
			return -1;
		}
		$sql='INSERT INTO 
            friend(user1_id, user2_id) 
            values (?,?)';		
        $params = array(
            $user_id, $friend_id
        );
		$this->db->query($sql, $params);
		if ($this->db->_error_message()){
			return 0;
		}else{
			return 1;
		}                   
	}
}