<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->library('session');
    }
    function index()
    {
        exit('Access denied');
    }
	function get_list(){	
		$user_id=$_POST['user_id'];
		//echo $user_id;
		$this->load->model('upload_model');				
		$result= $this->upload_model->get_list_by_id($user_id);			
		echo json_encode($result);		
		
	}
	function upload(){	
		//$data=file_get_contents($_POST['url']);$_FILES['f_file']['name']
		/*if ( 0 < $_FILES['file']['error'] ) {
			echo 'Error: ' . $_FILES['file']['error'] . '<br>';
		}
		else {
			$dest='uploads/'.$_FILES['file']['name'];
			move_uploaded_file($_FILES['file']['tmp_name'],$dest);							
		}*/

		$user_id=$this->session->userdata('user_id');
		$restaurant_name = $this->input->post('RestName');
		$description=$this->input->post('Description');
		$rating=$this->input->post('Rating');
		$is_restaurant=$this->input->post('isRestaurant');
		$dining_time=$this->input->post('time');
		$full_image_dir='uploads/'.$_FILES['f_file']['name'];			
		$small_image_dir='uploads/'.$_FILES['f_file']['name'];
		$this->load->model('upload_model');
		$this->load->model('restaurant_model');
		$this->load->model('user_model');
		if ($is_restaurant){
			$restaurant_id=$this->restaurant_model->get_id($restaurant_name);
		} else {
			$restaurant_id=null;
		}		
		$user_name=$this->session->userdata('user_name');
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
		    if ( ! $this->upload->do_upload('f_file'))
			{								
				$response['fullimg_success'] = 0;
				$response['smallimg_success'] = 0;
				$response['error'] = $this->upload->display_errors();
				echo json_encode($response); 
			} 
			else
			{
				$data = array('upload_data' => $this->upload->data());  
				
				$response['fullimg_success'] = 1;
				$response['smallimg_success'] = 1;
				echo json_encode($response); 
			}									
	}
	function facebook_login(){
		$facebook_id = $this->input->post('facebook_id');
        $user_name = $this->input->post('user_name');
		
		$this->load->model('user_model');
		$id = $this->user_model->get_id_by_facebook($facebook_id, $user_name);
			if ($id >-1) {
            // 设置session中的参数            
				$this->session->set_userdata('is_logged_in', true);
				$this->session->set_userdata('user_id', $id);   
				$this->session->set_userdata('user_name', $user_name);  
				//$this->load->view('home');
				$data['is_logged_in']=true;
				echo json_encode($data);
			}
			else {		
				$data['login_prompt']='Oops! Something went error. Try refreshing!';
				$data['sign_up_prompt']="";
				//$this->load->view('welcome',$data);
				echo json_encode($data);
		}
	}
    function get_profile()
	{
		$this->load->model('company_model');
		$data['company_id']=$this->company_model->get_id_by_name($_POST['company_name']);
		$list= $this->company_model->get_focus_by_id($data['company_id']);
		
		$data['focus_list']=explode('|',substr($list,1,strlen($list)-2));
		echo json_encode($data);
	}
	
	function show_API()
	{
		$this->load->model('company_model');
		$data['metrics']=null;//$this->company_model->get_metrics($_POST['industry']);		
		echo json_encode($data);
	}
	
    function get_message_push()
    {
	$this->load->model('follow_model');	
		
        $this->load->model('musician_model');
        $this->load->model('music_model');
        
        // notification
        $result_musician_top   = $this->musician_model->musician_attention_top_10();
        $result_music_top      = $this->music_model->music_collect_top_10();
        $data['musician_name'] = $result_musician_top[0]['name'];
        $data['music_name']    = $result_music_top[0]['name'];
        $data['url']           = "http://example.com";
        $data['status']        = "success";
        
        switch (rand(0, 1)) {
            case 0:
                $data['label'] = "收藏最多音乐";
                $data['brief'] = $data['music_name'];
                break;
            case 1:
                $data['label'] = "关注最多艺人";
                $data['brief'] = $data['musician_name'];
                break;
            default:
                # code...
                break;
        }

        if ($_POST['user_type'] == 1)
        {

        }
        else
        {

        }
        echo json_encode($data);
    }

    function skip_song()
    {
        $user_id = $this->input->get('user_id');
        $music_id = $this->input->get('music_id');
        $this->load->model('skip_song_model');
        $this->skip_song_model->addRecord($user_id, $music_id);
        echo json_encode("success");
    }

    function play_song()
    {
        $user_id = $this->input->get('user_id');
        $music_id = $this->input->get('music_id');
        $this->load->model('play_song_model');
        $this->play_song_model->addRecord($user_id, $music_id);
        echo json_encode("success");
    }

	/*
	 * 用get方法传入user_id, sid, type, h, filter参数，返回一个播放列表(json)
	 * 
	 * user_id为用户id
	 * sid为正在播放的歌曲
	 * type为用户刚刚的操作，为一个字符，含义如下:
	 *     n : new, 刚刚打开播放器
	 *     p : playing, 歌曲正在播放，队列中还有歌曲，需要重新返回歌曲列表
	 *     e : end, 当前歌曲播放完毕，但是队列中还有歌曲
	 *     c : change, 改变筛选条件
	 *     s : skip, 用户点击下一首
	 *     r : rate, 用户点击喜欢
	 *     u : unrate, 用户取消点击喜欢
	 *     b : bye, 用户点击不喜欢，不再播放
	 * h最近播放的音乐(最多9个)，格式为|song.sid:type，如|1386894:s|444482:p|460268:s|48180:s|1027376:s|188257:s
	 * filter筛选条件,格式按需求来定
	 *
	 * 返回值目前为随机10首歌
	 */
	function fetchmusic()
	{
		$user_id=$this->input->get('user_id');
		$sid=$this->input->get('sid');
		$type=$this->input->get('type');
		$h=$this->input->get('h');
	   	$filter=$this->input->get('filter');

		$this->load->model('music_model');
		$data = $this->music_model->randmore();
		echo json_encode($data);
	}
	
	/*
	    随机返回10首歌
	    希望以后能有筛选条件
	*/
	public function fetch_radio_music() 
	{
	    $this->load->model('music_model');
		$data = $this->music_model->rand_return_only_ids(10); // return 10 music_ids randomly
	    echo json_encode($data);
	}
	
	public function fetch_music_info() {
	    $this->load->model('music_model');
	    $this->load->model('user_model');
	    $this->load->model('musician_model');
	    $this->load->library('session');
	    
	    $user_id = $this->session->userdata('user_id');
	    $user_type = $this->session->userdata('user_type');
	    $music_id = $this->input->post("music_id");
	    
	    // 如果要合并 user 和 musician 两张表，修改这里就好
	    if ($user_type == 1) { // 普通用户
	        $this->load->model('collect_model');
	        $this->load->model('follow_model');
	        $this->load->model('copyright_model');
	        $user = $this->user_model->get_exposable_row($user_id);
	    }
	    else { // 音乐人
	        $this->load->model('collectm_model', 'collect_model');
	        $this->load->model('followm_model', 'follow_model');
	        $this->load->model('copyrightm_model', 'copyright_model');
	        $user = $this->musician_model->get_exposable_row($user_id);
	    }
	    
	    $music = $this->music_model->get_by_id($music_id);
	    
    	$music['musician'] = $this->musician_model->check_id($music['musician_id']);
    	$music['musician']['all_music'] = $this->music_model->getallmusic_by_musician_id($music['musician_id']);
    	$music['is_follow'] = $this->follow_model->is_follow($user_id, $music['musician_id']);
    	$music['is_collect'] = $this->collect_model->is_collect($user_id, $music['music_id']);
    	$music['is_copyright_sign'] = $this->copyright_model->is_copyright_sign($user_id, $music['music_id']);
	    
	    echo json_encode($music);
	}

    /*
     * added by 徐佳琛
     * 和$this->getmusic 返回数据的格式一样
     * 不过getmusic是随机返回一个音乐
     * 这是返回一个指定id的音乐
     */
    function get_music_by_id()
    {
		$this->load->model('music_model');
        $this->load->model('musician_model');
        $music_id = $this->input->post('music_id');
		$data = $this->music_model->get_by_id($music_id);
        $data['musician'] = $this->musician_model->check_id($data['musician_id']);

        //=====这些信息不宜返回=====
        unset($data['musician']['email']);
        unset($data['musician']['password']);
        unset($data['musician']['reg_time']);
        unset($data['musician']['identity']);
        //========================

		$data['list']= $this->music_model->getallmusic_by_musician_id($data['musician_id']);
		$data['tag']=$this->music_model->gettag_by_id($data['music_id']);
		echo json_encode($data);
	}
    function getmusic()
    {
		$this->load->model('music_model');
        $this->load->model('musician_model');
		$data=$this->music_model->rand();
        $data['musician'] = $this->musician_model->check_id($data['musician_id']);

        //=====这些信息不宜返回=====
        unset($data['musician']['email']);
        unset($data['musician']['password']);
        unset($data['musician']['reg_time']);
        unset($data['musician']['identity']);
        //========================

		$data['list']= $this->music_model->getallmusic_by_musician_id($data['musician_id']);
		$data['tag']=$this->music_model->gettag_by_id($data['music_id']);
		echo json_encode($data);
    }
    function likemusic()
    {
		$this->load->model('music_model');
    	$this->music_model->collect_inc($_POST['musician_id'],$_POST['music_id'],$_POST['user_id'],$_POST['user_type']);	
    }
    function no_likemusic()
    {
		$this->load->model('music_model');
    	$this->music_model->collect_dec($_POST['musician_id'],$_POST['music_id'],$_POST['user_id'],$_POST['user_type']);	
    }
    function attention_musician()
    {
    	$this->load->model('music_model');
        $this->load->model('musician_model');
    	$data=$this->musician_model->attention_inc($_POST['musician_id'],$_POST['music_id'],$_POST['user_id'],$_POST['user_type']);
        echo $data;
    }
    function no_attention_musician()
    {
        $this->load->model('musician_model');
    	$data=$this->musician_model->attention_dec($_POST['musician_id'],$_POST['music_id'],$_POST['user_id'],$_POST['user_type']);
        echo $data;
    }
    function islike_follow()
    {
		if ($_POST['user_type']==1)
       	{	
            $this->load->model('collect_model');
            $this->load->model('follow_model');
            $data['follow']=$this->follow_model->is_follow($_POST['user_id'],$_POST['musician_id']);
			$data['collect']=$this->collect_model->is_collect($_POST['user_id'],$_POST['music_id']);
		}
        else
        {	
            $this->load->model('collectm_model');
            $this->load->model('followm_model');
            $data['follow']=$this->followm_model->is_follow($_POST['user_id'],$_POST['musician_id']);
			$data['collect']=$this->collectm_model->is_collect($_POST['user_id'],$_POST['music_id']);
		}
        echo json_encode($data);
    }
    function copyright_sign()
    {  
       $this->load->model('copyright_model'); 
       $this->load->model('copyrightm_model'); 
       $this->load->model('user_model'); 
       $this->load->model('music_model'); 
       $this->load->model('musician_model'); 
       $tmp=$this->user_model->get_from_id($_POST['user_id']);
       $user_image=$tmp['port_dir'];//添加照片信息，方便版权申请时的信息显示
       $musician_image=$this->musician_model->check_photo($_POST['musician_id']);
       $tmp=$this->user_model->get_from_id($_POST['music_id']);
       $music_name=$tmp['name'];
       $this->copyright_model->insert_new_copyright($_POST['musician_id'],$_POST['user_id'],$_POST['music_id'],$_POST['name'],$_POST['company'],$_POST['identity'],$_POST['phone'],$_POST['email'],$_POST['content'],$user_image,$music_name,$musician_image);
       $this->copyrightm_model->insert_new_copyright($_POST['musician_id'],$_POST['user_id'],$_POST['music_id'],$_POST['name'],$_POST['company'],$_POST['identity'],$_POST['phone'],$_POST['email'],$_POST['content'],$user_image,$music_name,$musician_image);
    }
    function no_copyright_sign()
    {   
       $this->load->model('copyright_model'); 
       $this->load->model('copyrightm_model'); 
       $this->copyright_model->drop_copyright($_POST['user_id'],$_POST['music_id']);
       $this->copyrightm_model->drop_copyright($_POST['user_id'],$_POST['music_id']);
    }
    function iscopyright_sign()
    {
        $this->load->model('copyright_model'); 
       	$data=$this->copyright_model->is_copyright_sign($_POST['user_id'],$_POST['music_id']);
        echo $data;
    }
     function information_change()
    {
    	$this->load->model('user_model');
    	$this->load->model('musician_model');	
    	$data['name']=$_POST['name'];
    	$data['nickname']=$_POST['nickname'];
    	$data['gender']=$_POST['gender'];
    	$data['birthday']=$_POST['birthday'];
    	$data['introduction']=$_POST['self_intro'];
    	if($_POST['type']==1)
    	{
    	$this->user_model->update_by_id($data,$_POST['id']);   
    	}
    	else
    	{
    	$data['identity']=$_POST['identity'];
    	$this->musician_model->update_by_id($data,$_POST['id']);	
    	}
    }
    function check_constellation()
    {
        $this->load->model('user_model');
        $data=$this->user_model->check_constellation($_POST['birthday']); 
        echo $data;
    }
    function password_change()
    {   
    	$this->load->model('user_model');
    	$this->load->model('musician_model');
    	if($_POST['type']==1)
    	{
    	$user=$this->user_model->get_from_id($_POST['id']);
    	$password_old=$user['password'];
    	$password=sha1($_POST['password'].$user['reg_time']);
    	$password1=sha1($_POST['password1'].$user['reg_time']);
    	$password2=sha1($_POST['password2'].$user['reg_time']);
    	if($password!=$password_old){$data=1;} 
    	else
    	{
    		if($password1!=$password2){$data=2;}
    		else
    		{
    		if(strlen($_POST['password1'])<8){$data=3;}
    		else
    		{
    		$data1['password']=$password1;
    		$this->user_model->update_by_id($data1,$_POST['id']);
    		$data=4;
    		}
    	    }
	    }
	    }
	    else
	    {
	    $musician=$this->musician_model->get_from_id($_POST['id']);
    	$password_old=$musician['password'];
    	$password=sha1($_POST['password'].$musician['reg_time']);
    	$password1=sha1($_POST['password1'].$musician['reg_time']);
    	$password2=sha1($_POST['password2'].$musician['reg_time']);
    	if($password!=$password_old){$data=1;} 
    	else
    	{
    		if($password1!=$password2){$data=2;}
    		else
    		{
    		if(strlen($_POST['password1'])<8){$data=3;}
    		else
    		{
    		$data1['password']=$password1;
    		$this->musician_model->update_by_id($data1,$_POST['id']);
    		$data=4;
    		}
    	    }
	    }	
	    }
   	echo $data;
    }
    function copyrightm_click()
    {
    	$this->load->model('copyrightm_model');
    	$copyrights=$this->copyrightm_model->display($_POST['musician_id']);
    	$map['last_read_time']=$_POST['new_time'];
    	$data=$copyrights[$_POST['click_id']];
  		$copyright_message_time1=explode(" ", $data['created']);
		$copyright_message_time1=explode(":", $copyright_message_time1[1]);
		$copyright_message_time1=$copyright_message_time1[0]*10000+$copyright_message_time1[1]*100+$copyright_message_time1[2];
		$copyright_message_time2=explode(" ", $data['last_read_time']);
    	$copyright_message_time2=explode(":", $copyright_message_time2[1]);
	    $copyright_message_time2=$copyright_message_time2[0]*10000+$copyright_message_time2[1]*100+$copyright_message_time2[2];
	    if(($data['last_read_time']<$data['created'])&&($copyright_message_time1-$copyright_message_time2>5))
	    {
	    	$data['remind']=1;
	    }
	    else
	    {
	    	$data['remind']=0;
	    }
    	$this->copyrightm_model->update_by_id($map,$data['copyrightm_id']);
        $data['copyright_info']="申请名字为《".$data['music_name']."》的音乐的版权，音乐编号为".$data['music_id'];
        echo json_encode($data);
    }
    function copyright_message()
    {
    	$this->load->model('copyright_model');
    	$this->load->model('copyrightm_model');
    	$map1['copyright_message']=$_POST['message'];
    	$map2['copyright_message']=$_POST['message'];
    	$map1['last_read_time']=$_POST['new_time'];
        $this->copyright_model->update_by_messageid($map1,$_POST['copyright_user_id'],$_POST['copyright_musician_id'],$_POST['copyright_music_id']);
        $this->copyrightm_model->update_by_messageid($map2,$_POST['copyright_user_id'],$_POST['copyright_musician_id'],$_POST['copyright_music_id']);	
    }
    function copyright_click()
    {
    	$this->load->model('copyright_model');
    	$copyrights=$this->copyright_model->display($_POST['user_id']);
    	$map['last_read_time']=$_POST['new_time'];
    	$data=$copyrights[$_POST['click_id']];
    	$copyright_message_time1=explode(" ", $data['created']);
		$copyright_message_time1=explode(":", $copyright_message_time1[1]);
		$copyright_message_time1=$copyright_message_time1[0]*10000+$copyright_message_time1[1]*100+$copyright_message_time1[2];
		$copyright_message_time2=explode(" ", $data['last_read_time']);
    	$copyright_message_time2=explode(":", $copyright_message_time2[1]);
	    $copyright_message_time2=$copyright_message_time2[0]*10000+$copyright_message_time2[1]*100+$copyright_message_time2[2];
	    if(($data['last_read_time']<$data['created'])&&($copyright_message_time1-$copyright_message_time2>5))
	    {
	    	$data['remind']=1;
	    }
	    else
	    {
	    	$data['remind']=0;
	    }
    	$this->copyright_model->update_by_id($map,$data['copyright_id']);
        $data['copyright_info']="申请名字为《".$data['music_name']."》的音乐的版权，音乐编号为".$data['music_id'];
        echo json_encode($data);
    }
    function copyrightm_message()
    {
    	$this->load->model('copyright_model');
    	$this->load->model('copyrightm_model');
    	$map1['copyright_message']=$_POST['message'];
    	$map2['copyright_message']=$_POST['message'];
    	$map1['last_read_time']=$_POST['new_time'];
        $this->copyright_model->update_by_messageid($map2,$_POST['copyright_user_id'],$_POST['copyright_musician_id'],$_POST['copyright_music_id']);
        $this->copyrightm_model->update_by_messageid($map1,$_POST['copyright_user_id'],$_POST['copyright_musician_id'],$_POST['copyright_music_id']);	
    }
    //send private letter
	function private_letter_sign()
	{
	   $this->load->model('private_letter_model'); 
       $this->load->model('privatem_letter_model'); 
       $this->load->model('user_model'); 
       $this->load->model('musician_model'); 
       $tmp=$this->user_model->get_from_id($_POST['user_id']);
       $name=$tmp['nickname'];
       $musician_tmp=$this->musician_model->get_from_id($_POST['musician_id']);
       $musician_name=$musician_tmp['nickname'];
       $user_image=$tmp['port_dir'];//添加照片信息，方便版权申请时的信息显示
       $musician_image=$musician_tmp['portaitdir'];
       $this->private_letter_model->insert_new_letter($_POST['musician_id'],$_POST['user_id'],$_POST['content'],$user_image,$musician_image,$name,$musician_name);
       $this->privatem_letter_model->insert_new_letter($_POST['musician_id'],$_POST['user_id'],$_POST['content'],$user_image,$musician_image,$name,$musician_name);
	}
	 function private_letter_click()
    {
    	$this->load->model('private_letter_model');
    	$letters=$this->private_letter_model->display($_POST['user_id']);
    	$map['last_read_time']=$_POST['new_time'];
    	$data=$letters[$_POST['click_id']];
    	$letter_message_time1=explode(" ", $data['created']);
		$letter_message_time1=explode(":", $letter_message_time1[1]);
		$letter_message_time1=$letter_message_time1[0]*10000+$letter_message_time1[1]*100+$letter_message_time1[2];
		$letter_message_time2=explode(" ", $data['last_read_time']);
    	$letter_message_time2=explode(":", $letter_message_time2[1]);
	    $letter_message_time2=$letter_message_time2[0]*10000+$letter_message_time2[1]*100+$letter_message_time2[2];
	    if(($data['last_read_time']<$data['created'])&&($letter_message_time1-$letter_message_time2>5))
	    {
	    	$data['remind']=1;
	    }
	    else
	    {
	    	$data['remind']=0;
	    }
    	$this->private_letter_model->update_by_id($map,$data['letter_id']);
        echo json_encode($data);
    }
    function privatem_letter_click()
    {
    	$this->load->model('privatem_letter_model');
    	$letters=$this->privatem_letter_model->display($_POST['musician_id']);
    	$map['last_read_time']=$_POST['new_time'];
    	$data=$letters[$_POST['click_id']];
    	$letter_message_time1=explode(" ", $data['created']);
		$letter_message_time1=explode(":", $letter_message_time1[1]);
		$letter_message_time1=$letter_message_time1[0]*10000+$letter_message_time1[1]*100+$letter_message_time1[2];
		$letter_message_time2=explode(" ", $data['last_read_time']);
    	$letter_message_time2=explode(":", $letter_message_time2[1]);
	    $letter_message_time2=$letter_message_time2[0]*10000+$letter_message_time2[1]*100+$letter_message_time2[2];
	    if(($data['last_read_time']<$data['created'])&&($letter_message_time1-$letter_message_time2>5))
	    {
	    	$data['remind']=1;
	    }
	    else
	    {
	    	$data['remind']=0;
	    }
    	$this->privatem_letter_model->update_by_id($map,$data['letter_id']);
        echo json_encode($data);
    }
    function private_letter()
    {
    	$this->load->model('private_letter_model');
    	$this->load->model('privatem_letter_model');
    	$map1['letter']=$_POST['letter'];
    	$map2['letter']=$_POST['letter'];
    	$map1['last_read_time']=$_POST['new_time'];
        $this->private_letter_model->update_by_messageid($map1,$_POST['letter_user_id'],$_POST['letter_musician_id']);
        $this->privatem_letter_model->update_by_messageid($map2,$_POST['letter_user_id'],$_POST['letter_musician_id']);	
    }
    function privatem_letter()
    {
    	$this->load->model('private_letter_model');
    	$this->load->model('privatem_letter_model');
    	$map1['letter']=$_POST['letter'];
    	$map2['letter']=$_POST['letter'];
    	$map1['last_read_time']=$_POST['new_time'];
        $this->private_letter_model->update_by_messageid($map2,$_POST['letter_user_id'],$_POST['letter_musician_id']);
        $this->privatem_letter_model->update_by_messageid($map1,$_POST['letter_user_id'],$_POST['letter_musician_id']);	
    }
    /*
        upload music

        errno 1, parameter error
        errno 2, files have been removed
    */
    function upload_music() {
        $this->load->model('music_model');
        $this->load->model('tag_model');
        $this->load->model('genre_model');
        $this->load->model('music_tag_model');
        $this->load->model('music_genre_model');
        $this->load->model('music_musician_model');
        $this->load->model('upload_model');

        // required parameters 
        if ("" == trim($_POST['musician_id']) ||
            "" == trim($_POST['music_name']) ||
            "" == trim($_POST['music_url']))
        {
            echo '{"errno":1, "errmsg":"参数错误"}';
            return;
        }
        $musician_id = trim($_POST['musician_id']);
        $music_name= trim($_POST['music_name']);
        $music_url= trim($_POST['music_url']);

        // optional parameters
        $image_url = trim($_POST['music_image_url']);
        $album = trim($_POST['album']);
        $lyrics_by = trim($_POST['lyrics_by']);
        $composed_by = trim($_POST['composed_by']);
        $arranged_by = trim($_POST['arranged_by']);
        $disc_company = trim($_POST['disc_company']);
        $perform_time = trim($_POST['perform_time']);
        $genres = trim($_POST['genres']);
        $tags = trim($_POST['tags']);
        $story = trim($_POST['story']); 

        // insert into db
        $music_id = $this->music_model->insert_new_no_url(
            $musician_id,
            $music_name,
            //$album, currently unavailable
            $lyrics_by,
            $composed_by,
            $arranged_by,
            $disc_company,
            $perform_time,
            $story
        );

        // move music file and image file
        $new_music_url = 'upload/music/' . 'user_' . $musician_id . '/music_' . $music_id;
        $new_music_url .= '.' . pathinfo($music_url, PATHINFO_EXTENSION); 
        if (!is_dir(dirname($new_music_url))) {
            mkdir(dirname($new_music_url), 0777, true);
        }
        $music_ok = @rename($music_url, $new_music_url);

        $image_ok = true;
        $new_image_url = null;
        if (!empty($image_url)) {
            $new_image_url = 'upload/image/' . 'user_' . $musician_id . '/image_' . $music_id;
            $new_image_url .= '.' . pathinfo($image_url, PATHINFO_EXTENSION); 
            if (!is_dir(dirname($new_image_url))) {
                mkdir(dirname($new_image_url), 0777, true);
            }
            $image_ok = @rename($image_url, $new_image_url);
        }

        // check move ok, for they may be deleted in certain time
        if (!$music_ok || !$image_ok) {
            $errmsg = '您很长时间没有操作了，请重新上传';
            if(!$music_ok) {
                $errmsg .= '音乐';
            }
            if(!$image_ok && !empty($image_url)) {
                if (!$music_ok) $errmsg .= '和';
                $errmsg .= '图片';
            }
            $this->music_model->drop_music_by_id($music_id); // roll back insertion
            echo '{"errno":2, "errmsg":"' . $errmsg . '"}';
            return;
        }
        // update the url
        $this->music_model->update_url($music_id, $new_music_url, $new_image_url);

        // insert into upload table and music_musician table
        $this->upload_model->addupload($musician_id, $music_id);
        $this->music_musician_model->add($musician_id, $music_id);

        // update tag and genre
        $tag_id = $this->tag_model->get_id_by_name($tags);
        if ($tag_id == null) $tag_id = $this->tag_model->add_by_name($tags);
        $this->music_tag_model->add($tag_id, $music_id);

        $genre_id = $this->genre_model->get_id_by_name($genres);
        if ($genre_id == null) $genre_id = $this->genre_model->add_by_name($genres);
        $this->music_genre_model->add($genre_id, $music_id);

        // return success json
        echo '{"errno":0, "msg":"上传音乐 ' . $music_name . ' 成功！"}';
    }


    /*
     muscian change avatar
    */
    function change_avatar() {
        $this->load->model("musician_model");
        
        $musician_id = $_POST['musician_id'];
        $url = $_POST['url'];

        if("" == trim($musician_id) || "" == trim($musician_id) ) {
            echo '{"errno": 1, "errmsg", "参数错误"}';
            return 1;
        }

        date_default_timezone_set("PRC");
        $date = new DateTime();
        $ts = $date->getTimestamp();
        
        $new_avatar_url = 'upload/avatar/' . 'user_' . $musician_id . '/avatar_' . $ts;
        $new_avatar_url .= '.' . pathinfo($url, PATHINFO_EXTENSION);
        if (!is_dir(dirname($new_avatar_url))) {
            mkdir(dirname($new_avatar_url), 0777, true);
        }
        $avatar_ok = @rename($url, $new_avatar_url);

        if (!$avatar_ok) {
            echo '{"errno": 2, "errmsg": "头像上传失败"}';
            return 2;
        }
        else {
            $this->musician_model->update_avatar($musician_id, $new_avatar_url);
            echo '{"errno": 0, "msg": "头像上传成功", "url":"' . $new_avatar_url . '"}';
            return 0;
        }
    }


    public function search() {
        $keyword = $this->input->post('keyword');
        $this->load->model('search_model');
        $result = $this->search_model->search(array($keyword));
        echo json_encode($result);
    }
    
    public function personal_load_tab() {
        $tab_for = $this->input->post('tab_for');
        $this->load->library('session');
        $user_id = $this->session->userdata('user_id');
        $user_type = $this->session->userdata('user_type');
        $model_name = substr($tab_for, 0, strlen($tab_for) - 1);
        if ($tab_for != 'uploads')
            $model_name .= $user_type == '0' ? 'm' : '';
        $model_name .= '_model';
        
        $this->load->model($model_name, 'tab_content_model');
        $data = $this->tab_content_model->display($user_id);
    
        echo json_encode($data);
    }
    
    public function follow() {
        $this->load->library('session');
    
        $user_id = $this->session->userdata('user_id');
        $musician_id = $this->input->post("musician_id");
        $action = $this->input->post("action");
        if($this->session->userdata('user_type') == 1) {
            $this->load->model('follow_model');
        }
        else {
            $this->load->model('followm_model', 'follow_model');
        }
        if($action == "add") {
            $errno = $this->follow_model->add_follow($user_id, $musician_id);
            if($errno == 0) {
                echo '{"errno":0, "msg":"关注成功"}';
            }
            else if($errno == 1){
                echo '{"errno":1, "msg":"已经关注了该音乐人"}';
            }
            else if($errno < 0) {
                echo '{"errno":' . $errno . ', "msg":"关注失败"}';
            }
        }
        else if ($action == "delete"){
            $errno = $this->follow_model->delete_follow($user_id, $musician_id);
            if($errno == 0) {
                echo '{"errno":0, "msg":"取消关注成功"}';
            }
            else if($errno == 1){
                echo '{"errno":1, "msg":"已经取消关注了该音乐人"}';
            }
            else if($errno < 0) {
                echo '{"errno":' . $errno . ', "msg":"取消关注失败"}';
            }
        }
    }
    
    public function collect_music() { 
        $this->load->library('session');
    
        $user_id = $this->session->userdata('user_id');
        $music_id = $this->input->post("music_id");
        $action = $this->input->post("action");
        
        if($this->session->userdata('user_type') == 1) {
            $this->load->model('collect_model');
        }
        else {
            $this->load->model('collectm_model', 'collect_model');
        }
        
        if($action == "add") {
            $errno = $this->collect_model->add_collect($user_id, $music_id);
            if($errno == 0) {
                echo '{"errno":0, "msg":"收藏成功"}';
            }
            else if($errno == 1){
                echo '{"errno":1, "msg":"已经收藏过该音乐"}';
            }
            else if($errno < 0) {
                echo '{"errno":' . $errno . ', "msg":"收藏失败"}';
            }
        }
        else if ($action == "delete"){
            $errno = $this->collect_model->delete_collect($user_id, $music_id);
            if($errno == 0) {
                echo '{"errno":0, "msg":"取消收藏成功"}';
            }
            else if($errno == 1){
                echo '{"errno":1, "msg":"已经取消收藏该音乐"}';
            }
            else if($errno < 0) {
                echo '{"errno":' . $errno . ', "msg":"取消收藏失败"}';
            }
        }
    }
}
