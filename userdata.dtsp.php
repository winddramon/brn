<?php
define('GAME_PAGE', 'userdata');
define('ROOT_DIR', dirname(__FILE__));
define('CONNECTION_MODE', isset($_POST['username']) ? 'ajax' : 'normal');

include(ROOT_DIR.'/include/func.template.php');
include(ROOT_DIR.'/include/inc.init.php');
include(get_mod_path('dtsp').'/include/func.user.dtsp.php');

$user = $db->select('users', '*', array('username' => $cuser['username']));
if(count($user)>0) {
	$user = $user[0];
}else{
	$user = false;
}
if(isset($_POST['username'])){
	$info = '';
	if($_POST['username'] != $cuser['username']){
		$info = '登陆异常：用户名不对应';
	}elseif($_POST['chgpass']){
		$username = $_POST['username'];
		$oldpass = $_POST['oldpass'];
		$newpass = $_POST['newpass'];
		$newpass2 = $_POST['newpass2'];
		if(!$oldpass ||!$newpass || !$newpass2){
			$info = '密码不能为空';
		}elseif($newpass !== $newpass2){
			$info = '两次密码输入不一致';
		}else{
			$user = $db->select('users', '*', array('username' => $username));
			if($user){
				if(encode_password($username, $oldpass) !== $user['password']){
					$info = '密码错误';
				}else{
					$user['password'] = encode_password($username, $newpass);
					$_SESSION['cuser'] = array_merge($_SESSION['cuser'], $user);
					$condition = array('_id' => $user['_id']);
					$return = $db->update('users', $user, $condition);
					$info = $return ? '成功修改密码' : '数据库错误';
				}
			}else{
				$info = '该用户不存在';
			}
		}
	}else{
		if($user) {//TODO: 如果$cuser改成对象，那么这里应该同时更新$cuser
			$user['gender'] = $_POST['gender'];
			$user['iconuri'] = $_POST['iconuri'];
			$user['motto'] = $_POST['motto'];
			$user['killmsg'] = $_POST['killmsg'];
			$user['lastword'] = $_POST['lastword'];
			$_SESSION['cuser'] = array_merge($_SESSION['cuser'], $user);
			$condition = array('_id' => $user['_id']);
			$return = $db->update('users', $user, $condition);
			$info = $return ? '成功修改资料' : '数据库错误';
		}else{
			$info = '该用户不存在';
		}
	}
}
if(isset($info)){
	$data = array('#userdata-info' => array('innerHTML'=>$info));
	general_respond($data);
}elseif(false !== $cuser){
	render_page('userdata');
}else{
	$err_msg = '请先登录';
	render_page('error');
}