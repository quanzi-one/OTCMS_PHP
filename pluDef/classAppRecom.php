<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppRecom{

	public static function Jud(){
		return false;
	}

	public static function UcMenu($mode='pc'){

	}

	public static function UcManage($userID,$mode='pc'){

	}

	public static function AppSysTrBox1($AS_recomAnnoun, $AS_recomLinkStr, $AS_recomImgStr, $AS_recomFontStr, $AS_recomNoteStr){
		return '
			<tr>
				<td align="right">邀请好友注册：</td>
				<td>您尚未购买该插件</td>
			</tr>
			';
	}

	public static function UsersBox1($urlPara){

	}

	public static function UsersTabStr($recomId, $isRecomScore){

	}

	public static function AdmManage(){

	}

	public static function RecomTypeCN($str){
		switch ($str){
			case 'id':		return '邀请';
			case 'user':	return '填写';
			default :		return '';
		}
	}

	public static function GetRegArr($mode='pc'){
		return array('', 0, '');
	}

	public static function RegScore($userID, $username, $recomId, $recomUser, $mode='pc'){

	}

	public static function AuditScore($dataID, $mode='pc'){

	}
}
?>