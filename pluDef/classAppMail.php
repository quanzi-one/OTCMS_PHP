<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppMail{
	public static function Jud(){
		return false;
	}

	public static function Send($paraArr=array()){
		return array('res'=>false, 'note'=>'');
	}

	public static function ContentSend($type, $mail, $userID, $username, $dataArr=array()){
		return array('res'=>false, 'note'=>'');
	}

	public static function RndCodeSend($type, $userID, $username, $mail){
		return array('res'=>false,'note'=>'');
	}

	public static function WaitSec($sec,$sign=''){
		return false;
	}

	public static function CheckMailCode($type, $mail, $mailCode, $userID=-1){
			return array('res'=>false, 'note'=>'');
	}

	public static function UserSysTrBox1($US_isAuthMail, $US_isLockMail, $US_isMustMail, $US_mustMailStr){

	}

	public static function UserSysTrBox2($US_regAuthMail){

	}

	public static function UserSysTrBox3($US_isMissPwdMail, $US_event){

	}

}

?>