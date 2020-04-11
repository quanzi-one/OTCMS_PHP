<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppAdminRightNews{

	public static function Jud(){
		return false;
	}

	public static function MemberGroupBox($lv1Num){
		return array();
	}

	public static function InfoRevCheck($rightStr, $type1ID, $type2ID, $adminID, $userID){

	}

	public static function InfoItem1($rightStr, $IF_typeStr){

	}

	public static function InfoItem2($rightStr, $refTypeStr, $userID){
		return array();
	}

}

?>