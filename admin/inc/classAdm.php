<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Adm{

	// 后台授权信息/页面获取路线
	public static function UrlWebMode($num){
		switch ($num){
			case 0:		return '主路线';	
			case 1:		return '国内备用路线';	
			case 2:		return '香港备用路线';	
			case 3:		return '美国备用路线';	
			default :	return '未知路线'. $num;
		}
	}

	// 后台授权/采集调用模式
	public static function UrlGetMode($num){
		switch ($num){
			case 0:		return '自动';	
			case 1:		return 'Snoopy插件';	
			case 2:		return 'curl模式';	
			case 3:		return 'fsockopen模式';	
			case 4:		return 'fopen模式';	
			default :	return '未知模式'. $num;
		}
	}

	// 添加用户操作记录
	public static function AddLog($logArr=array()){
		global $DB,$menuFileID,$menuTreeID,$userIpInfo,$user_ID,$user_realname,$user_time,$sysAdminArr;

		if (isset($logArr['userID'])==false || intval($logArr['userID'])<=0){
			$logArr['userID']	= $user_ID;
		}
		if (isset($logArr['realname'])==false || $logArr['realname']==''){
			$logArr['realname'] = $user_realname;
		}
		if (isset($logArr['menuFileID'])==false || intval($logArr['menuFileID'])<=0){
			$logArr['menuFileID']	= $menuFileID;
		}
		if (isset($logArr['menuTreeID'])==false || intval($logArr['menuTreeID'])<=0){
			$logArr['menuTreeID']	= $menuFileID;
		}
		if (isset($logArr['theme'])==false || trim($logArr['theme'])==''){
			$logArr['theme']	= '';
		}else{
			if (isset($logArr['title'])==false || trim($logArr['title'])==''){
				$logArr['title'] = '标题';
			}
			$logArr['theme']	= '（'. $logArr['title']  .':'. $logArr['theme'] .'）';
		}

	/*
		if ($sysAdminArr['SA_memberLogRank']==0){
			return '';

		}elseif ($sysAdminArr['SA_memberLogRank']==10 && $logArr['menuFileID']!=0){
			return '';

		}elseif ($sysAdminArr['SA_memberLogRank']==20){
			$logArr['theme']	= '';

		}
	*/
		$record=array();
		$record['ML_time']		= TimeDate::Get();
		$record['ML_date']		= TimeDate::Get('date');
		$record['ML_userID']	= $logArr['userID'];
		$record['ML_realname']	= $logArr['realname'];
		$record['ML_menuFileID']= $logArr['menuFileID'];
		$record['ML_menuTreeID']= $logArr['menuTreeID'];
		$record['ML_note']		= $logArr['note'] . $logArr['theme'];
		$record['ML_readNum']	= 1;
	//	print_r($logArr);die();

		return $DB->InsertParam('memberLog',$record);
	}

	// 项目切换文字
	public static function SwitchCN($num,$fieldName,$styleStr='text-decoration:underline;'){
		$newStr = '';
		if ($num==1){
			switch ($fieldName){
				case 'stateAudit':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">已审</span>';
					break;
			
				case 'state2': case 'state3':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">正常</span>';
					break;
			
				case 'state4':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">已使用</span>';
					break;
			
				case 'state': case 'wapState': case 'userState': case 'isMenu': case 'isSubMenu': case 'isHome': case 'isWap': case 'isWapHome': case 'isHomeWap':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">显示</span>';
					break;
			
				case 'isLock': case 'isUser': case 'isRunCode': case 'isList':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">是</span>';
					break;
			
				case 'isRecom':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">推荐</span>';
					break;

				case 'isUse': case 'isSel':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">启用</span>';
					break;

				case 'isUse2':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">已启用</span>';
					break;

				case 'rightStr':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">已确认</span>';
					break;

				case 'sign':
					$newStr = '<span style="color:green;">√</span>';
					break;

				default :
					$newStr = '<span class="font3_2">'. $fieldName .'</span>';
					break;

			}

		}elseif ($num==0){
			switch ($fieldName){
				case 'stateAudit':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">未审</span>';
					break;
			
				case 'state2':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">冻结</span>';
					break;
			
				case 'state3':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">作废</span>';
					break;
			
				case 'state4':
					$newStr = '<span class="font3_2" style="'. $styleStr .'">未使用</span>';
					break;
			
				case 'state': case 'wapState': case 'userState': case 'isMenu': case 'isSubMenu': case 'isHome': case 'isWap': case 'isWapHome': case 'isHomeWap':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">隐藏</span>';
					break;
			
				case 'isLock': case 'isUser': case 'isRunCode': case 'isList':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">否</span>';
					break;
			
				case 'isRecom':
					$newStr = '<span class="font1_2d" style="'. $styleStr .'">不推荐</span>';
					break;
			
				case 'isUse': case 'isSel':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">禁用</span>';
					break;

				case 'isUse2':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">已禁用</span>';
					break;

				case 'rightStr':
					$newStr = '<span class="font2_2" style="'. $styleStr .'">未确认</span>';
					break;

				case 'sign':
					$newStr = '<span style="color:red;">ㄨ</span>';
					break;

				default :
					$newStr = '<span class="font2_2">'. $fieldName .'</span>';
					break;
			
			}

		}
		return $newStr;
	}

	// 项目切换2文字
	public static function Switch2CN($name, $fieldValue){
		$newStr = '';
		if (is_numeric($fieldValue)){
			if ($fieldValue == 1){
				$newStr = '<span class="font3_2" style="text-decoration:underline;">'. $name .'</span>';
			}elseif ($fieldValue == 2){
				$newStr = '<span style="text-decoration:underline;color:red;">拒绝</span>';
			}else{
				$newStr = '<span class="font1_2d fontDel">'. $name .'</span>';
			}
		}else{
			if (strlen($fieldValue) > 0){
				$newStr = '<span class="font3_2" style="text-decoration:underline;">'. $name .'</span>';
			}else{
				$newStr = '<span class="font1_2d fontDel" style="color:#d9d9d9;">'. $name .'</span>';
			}
		}
		return $newStr;
	}

	public static function Switch3CN($fieldName, $fieldValue){
		$newStr = '';
		if (strlen($fieldValue)==0){
			switch ($fieldName){
				case 'reply':
					$newStr = '<span class="font2_2" style="text-decoration:underline;">未回复</span>';
					break;
			}

		}else{
			switch ($fieldName){
				case 'reply':
					$newStr = '<span class="font3_2" style="text-decoration:underline;">已回复</span>';
					break;
			}
			
		}

		return $newStr;
	}

	// 项目切换代码
	public static function SwitchBtn($tabName,$dataID,$fieldValue,$fieldName,$fieldName2=''){
		global $dataType;

		if ($fieldName2==''){$fieldName2=$fieldName;}
		return '
		<a id="'. $fieldName2 . $dataID .'" href="#" class="font1_2" onclick=\'DataDeal.location.href="share_switch.php?mudi=switch&dataType='. $dataType .'&fieldName='. $fieldName .'&fieldName2='. $fieldName2 .'&tabName='. $tabName .'&dataID='. $dataID .'";return false;\'>'. self::SwitchCN($fieldValue,$fieldName2) .'</a>	
		';
	}

	// 项目切换代码
	public static function SwitchNo($tabName,$dataID,$fieldValue,$fieldName,$fieldName2=''){
		global $dataType;

		if ($fieldName2==''){$fieldName2=$fieldName;}
		return self::SwitchCN($fieldValue,$fieldName2,'');
	}

	// 项目切换代码
	public static function SwitchColl($tabName,$dataID,$fieldValue,$fieldName,$fieldName2=''){
		global $dataType;

		if ($fieldName2==''){$fieldName2=$fieldName;}
		return '<a id="'. $fieldName2 . $dataID .'" href="javascript:void(0);" class="font1_2" onclick=\'DataDeal.location.href="share_switch.php?mudi=switchColl&dataType='. $dataType .'&fieldName='. $fieldName .'&fieldName2='. $fieldName2 .'&tabName='. $tabName .'&dataID='. $dataID .'";return false;\'>'. self::SwitchCN($fieldValue,$fieldName2) .'</a>';
	}

	// 项目切换代码_属性
	public static function SwitchAddi($tabName, $dataID, $name, $fieldValue, $fieldName, $fieldName2='', $fieldValue2='', $alt=''){
		global $dataType;

		if ($fieldName2==''){ $fieldName2=$fieldName .'_'. $fieldName2 .'_'; }
		return '<a id="'. $fieldName2 . $dataID .'" href="javascript:void(0);" class="font1_2" style="padding:0 3px 0 3px;" onclick=\'DataDeal.location.href="share_switch.php?mudi=switchAddi&dataType='. $dataType .'&fieldValue='. $fieldValue .'&fieldName='. $fieldName .'&fieldName2='. $fieldName2 .'&fieldValue2='. $fieldValue2 .'&tabName='. $tabName .'&dataID='. $dataID .'&name='. urlencode($name) .'";return false;\' title="'. $alt .'">'. self::Switch2CN($name,$fieldValue) .'</a>';
	}


	// 项目切换代码_存在与不存在
	public static function SwitchExist($tabName, $dataID, $fieldAllVal, $fieldValue, $fieldName, $fieldName2='',$addiExt=''){
		global $dataType;

		if ($fieldName2==''){$fieldName2=$fieldName;}
		return '<a id="'. $fieldName2 . $dataID . $addiExt .'" href="javascript:void(0);" class="font1_2" onclick=\'DataDeal.location.href="share_switch.php?mudi=switchExsit&dataType='. $dataType .'&fieldName='. $fieldName .'&fieldName2='. $fieldName2 .'&tabName='. $tabName .'&fieldValue='. $fieldValue .'&dataID='. $dataID .'&addiExt='. $addiExt .'";return false;\'>'. self::SwitchCN(strpos($fieldAllVal,$fieldValue)===false?0:1, $fieldName2) .'</a>';
	}


	// 操作系统名称颜色
	public static function AdminName($adminID,$adminName){
		if ($adminID==0){
			return '<span class="font3_2">'. $adminName .'</span>';
		}else{
			return '<span class="font1_2">'. $adminName .'</span>';
		}
	}


	// 类别option化
	public static function TypeOptionList($type, $noNote='', $addiArr = array()){
		global $DB;
	
		$retStr = '';
		$typeexe = $DB->query('select TP_theme from '. OT_dbPref ."type where TP_type='". $type ."' order by TP_rank ASC");
		if ($row = $typeexe->fetch()){
			do {
				if ($type == 'writer' && isset($addiArr['realname'])){
					$theme = str_replace('{%昵称%}', $addiArr['realname'], $row['TP_theme']);
				}else{
					$theme = $row['TP_theme'];
				}
				$retStr .= '<option value="'. $theme .'">'. $theme .'</option>';
			}while ($row = $typeexe->fetch());
		}else{
			if (strlen($noNote) > 0){ $retStr .= '<option value="">'. $noNote .'</option>'; }
		}
		unset($typeexe);

		return $retStr;
	}


	// 过滤编辑器内容
	public static function FilterEditor($str){
		$str = preg_replace('/[\r\n]+/', "\n", $str);
		return $str;
	}

}
?>