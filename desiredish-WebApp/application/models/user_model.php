<?php
class user_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}

	//查询用户是否存在和获取用户所有信息
	function check($user_name){
		$sql="SELECT * FROM user WHERE user_name=?";
		$query=$this->db->query($sql,array($user_name));
		return $query->result_array();
	}
	//查询用户是否存在相片
	function check_photo($id){
		$sql="SELECT port_dir FROM user WHERE user_id=?";
		$query=$this->db->query($sql,array($id));
  	    $query=$query->row();
  	    return $query->port_dir;
	}
	//通过id获取用户所有信息
	function get_from_id($id){
		$sql="SELECT * FROM user WHERE user_id=?";
		$query=$this->db->query($sql,array($id));
		$result=$query->result_array();
        return $result[0];
	}
	//通过id获取用户所有name
	function get_name($id){
		$sql="SELECT user_name FROM user WHERE user_id=?";
		$query=$this->db->query($sql,array($id));
		$query=$query->row();
        return $query->user_name;
	}
	//通过name获取用户所有id
	function get_id($name){
		$sql="SELECT user_id FROM user WHERE user_name=?";
		$query=$this->db->query($sql,array($name));
		if ($query->num_rows() > 0) {
			$query=$query->row();
			return $query->user_id;
		}else{
			return -1;
		}
	}
	//登录
	function login($user_name,$password){//登录检查
		$sql="SELECT * FROM user WHERE user_name=?";
		$query=$this->db->query($sql,array($user_name));
		$result1=$query->result_array();
		$query=$query->row();
		if($result1)//检查账户密码是否正确
		{
			$result2=($query->password==sha1($password.$query->reg_time));
			if($result2){return $query->user_id;}//用户名与密码匹配
			else{return -1;}//密码错误
		}
     	else{return -2;}//无此用户	
	}
	function get_id_by_facebook($facebook_id, $email){
		$sql="SELECT * FROM user WHERE facebook_id=?";
		$query=$this->db->query($sql,array($facebook_id));
		$result1=$query->result_array();		
		if($result1)//old user
		{	
			return $query->row()->user_id;
		}
     	else{//insert new user
			$sql="INSERT INTO user (user_name,facebook_id) VALUES (?,?)";
			$this->db->query($sql,array($email,$facebook_id));	
		
			$sql="SELECT * FROM user WHERE facebook_id=?";
			$query=$this->db->query($sql,$facebook_id);
			if ($query->num_rows() > 0) {				
				return $query->row()->user_id;
			}
			else {			
				return -1;
			}		
		}
	}
	//注册
	function register_all($email,$password,$name,$nickname,$gender,$birthday,$reg_time){//全部项
		$sql="INSERT INTO user (email,password,name,nickname,gender,birthday,reg_time) VALUES (?,?,?,?,?,?,?)";
		$this->db->query($sql,array($email,$password,$name,$nickname,$gender,$birthday,$reg_time));	
	}
	function register_simple($user_name,$password,$reg_time){//必填项
		$sql="INSERT INTO user (user_name,password,reg_time) VALUES (?,?,?)";
		$this->db->query($sql,array($user_name,$password,$reg_time));	
		
		$sql="SELECT * FROM user WHERE user_name=?";
		$query=$this->db->query($sql,$user_name);
		if ($query->num_rows() > 0) {
			//echo $query;
            return $query->row()->user_id;
        }
        else {			
            return -1;
        }
	}
	
	
	//更新
	function update_by_email($map,$email){//更改某些项
		/*例如：
			$map['gender']='1';
			$map['name']='hello';
			$this->user_model->update_by_email($map,$email);
		 * *
		 */
		foreach($map as $key=>$var){
	    	$sql="UPDATE user SET ".$key."=? WHERE email=?";
			$result=$this->db->query($sql,array($var,$email));
		}
	}
	function update_by_id($map,$id){//更改某些项
		/*例如：
			$map['gender']='1';
			$map['name']='hello';
			$this->user_model->update_by_id($map,$id);
		 * *
		 */
		foreach($map as $key=>$var){
	    	$sql="UPDATE user SET ".$key."=? WHERE user_id=?";
			$result=$this->db->query($sql,array($var,$id));
		}
	}

	function update_by_all($email,$password,$name,$nickname,$gender,$birthday,$introdution){//更改全部项email除外
			$sql="UPDATE user SET password=?,name=?,nickname=?,gender=?,birthday=?,introduction=?  WHERE email=?";
			$result=$this->db->query($sql,array($password,$name,$nickname,$gender,$birthday,$introdution,$email));
	}

	
	//注销用户
	function cancel_by_email($email){
		$sql="DELETE FROM user WHERE email=?";
		$this->db->query($sql,array($email));
	}
	
    // 获取一行中可以暴露的信息（所以不包括密码，注册时间(用来加密密码)）
    function get_exposable_row($id) {
    	$sql = "SELECT * FROM user WHERE user_id=? LIMIT 1";
    	$query = $this->db->query($sql,array($id));
    	$result = $query->row_array();
    	
    	// unset user password, user reg_time
    	unset($result['password']);
    	unset($result['reg_time']);
    	
        return $result;
    }
    
    function get_exposable_row_by_username($username) {
    	$sql = "SELECT * FROM user WHERE user_name=? LIMIT 1";
    	$query = $this->db->query($sql,array($username));
    	$result = $query->row_array();
    	
    	// unset user password, user reg_time
    	unset($result['password']);
    	unset($result['reg_time']);
    	
        return $result;
    }
}