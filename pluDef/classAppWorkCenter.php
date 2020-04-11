<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppWorkCenter{

	public static function Jud(){
		return false;
	}

	public static function UcMenu($mode='pc'){

	}

	public static function UcManage($userID,$mode='pc'){

	}

	public static function Deal($mode='pc'){

	}

	public static function TypeArr(){
		$retArr = array();
		$retArr['reply']		= '回复';
		$retArr['read']			= '阅读';
		$retArr['qiandao']		= '签到';
		$retArr['news']			= '文章';
		$retArr['score1']		= '积分1';
		$retArr['score2']		= '积分2';
		$retArr['score3']		= '积分3';
		$retArr['regTime']		= '注册时长';
		$retArr['chongzhi']		= '充值金额';
		$retArr['xiaofei']		= '消费金额';
		$retArr['tkOrderMoney']			= '淘客订单总金额';
		$retArr['tkOrderMonthMoney']	= '淘客订单月金额';
		$retArr['phone']		= '完善手机号';
		$retArr['mail']			= '完善邮箱';
		$retArr['userInfo']		= '完善所有个人信息';
		$retArr['question']		= '回答问题';
		$retArr['audit']		= '手动审核';

		return $retArr;
	}

	public static function TypeCN($str){

	}

	public static function TypeOptionList($str){

	}

}
?>