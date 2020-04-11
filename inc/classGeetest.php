<?php 

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Geetest{

	public function __construct(){
		require_once OT_ROOT .'/tools/geetest/class.geetestlib.php';
	}


	// 检查是否验证通过
	public function IsTrue($mode='web'){
		global $systemArr;

		if (empty($systemArr)){ $systemArr = Cache::PhpFile('system'); }

		$GtSdk = new GeetestLib($systemArr['SYS_geetestID'], $systemArr['SYS_geetestKey']);
		$data = array(
				"user_id"		=> isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0, # 网站用户id
				"client_type"	=> $mode, #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
				"ip_address"	=> Users::GetIp() # 请在此处传输用户请求验证时所携带的IP
			);

		if (isset($_SESSION['gtserver']) && $_SESSION['gtserver'] == 1) {   //服务器正常
			$result = $GtSdk->success_validate(@$_POST['geetest_challenge'], @$_POST['geetest_validate'], @$_POST['geetest_seccode'], $data);
			if ($result) {
				return true;
			}else{
				return false;
			}
		}else{  //服务器宕机,走failback模式
			if ($GtSdk->fail_validate(@$_POST['geetest_challenge'],@$_POST['geetest_validate'],@$_POST['geetest_seccode'])) {
				return true;
			}else{
				return false;
			}
		}
	}


	// 显示验证结果
	public function ShowRes($mode='web'){
		global $systemArr;

		if (empty($systemArr)){ $systemArr = Cache::PhpFile('system'); }

		@session_start();
		$GtSdk = new GeetestLib($systemArr['SYS_geetestID'], $systemArr['SYS_geetestKey']);
		$data = array(
				"user_id"		=> "otcms". OT::RndChar(5), # 网站用户id
				"client_type"	=> $mode, #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
				"ip_address"	=> Users::GetIp() # 请在此处传输用户请求验证时所携带的IP
			);

		$status = $GtSdk->pre_process($data, 1);
		$_SESSION['gtserver'] = $status;
		$_SESSION['user_id'] = $data['user_id'];
		echo $GtSdk->get_response_str();
	}
}
?>
