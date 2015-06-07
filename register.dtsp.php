<?php
define('GAME_PAGE', 'register');
define('ROOT_DIR', dirname(__FILE__));
define('CONNECTION_MODE', isset($_POST['username']) ? 'ajax' : 'normal');

include(ROOT_DIR.'/include/func.template.php');
include(ROOT_DIR.'/include/inc.init.php');
include(get_mod_path('dtsp').'/include/func.user.dtsp.php');

if(isset($_POST['username'])){
	if($_POST['password'] !== $_POST['password_confirm']){
		$info = "两次密码输入不一致";
	}else{
		$username = $_POST['username'];//preg_replace('[\W]', '', $_POST['username']);
		$password = $_POST['password'];//preg_replace('[\W]', '', $_POST['password']);
		if($username == '' || $password == ''){
			$info = '用户名与密码不能为空';
		}else{
			$ip = real_ip();
			$users_n = $db->select('users', array('_id','username'), array('username' => $username));
			$users_i = $db->select('users', array('_id','ip'), array('ip' => $ip));
			if($users_n === false && count($users_i) < $ip_user_limit){
				$db->insert('users', array(
					'username' => $username,
					'password' => encode_password($username,$password),
					'groupid' => 0,
					'lastgame' => 0,
					'ip' => $ip,
					'credits' => 0,
					'validgames' => 0,
					'wingames' => 0,
					'gender' => 'm',
					'icon' => 0,
					'iconuri' => 'img/question.gif',
					'club' => 0,
					'motto' => '',
					'killmsg' => '',
					'lastword' => '',
					'achievement' => array()
					));
				$info = $username.' 注册成功';
			}else{
				if($users_n){
					$info = $username.' 已经存在';
				}else{
					$info = '同一IP账号数目超限';
				}
			}
		}
	}
}
if(isset($info)){
	//render_page('Register');
	$data = array('#register-info' => array('innerHTML'=>$info));
	general_respond($data);
}else{
	render_page('Register');
}

?>