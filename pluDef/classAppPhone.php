<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppPhone{
	public static function Jud(){
		return false;
	}

	public static function Send($pro, $paraArr=array()){
		return array('res'=>false, 'note'=>'短信验证通知插件尚未有');
	}

	public static function ContentSend($type, $phone, $userID, $username, $dataArr=array(), $isLimit=true){
		return array('res'=>false, 'note'=>'');
	}

	public static function RndCodeSend($type, $userID, $username, $phone){
		return array('res'=>false,'note'=>'');
	}

	public static function WaitSec($sec,$sign=''){
		return false;
	}

	public static function CheckPhoneCode($type, $phone, $phoneCode, $userID=-1){
		return array('res'=>false, 'note'=>'');
	}

	public static function UserSysTrBox1($US_isAuthPhone, $US_isLockPhone, $US_isMustPhone, $US_mustPhoneStr){

	}

	public static function UserSysTrBox2($US_regAuthPhone){

	}

	public static function UserSysTrBox3($US_isMissPwdPhone, $US_event){

	}

}

?>