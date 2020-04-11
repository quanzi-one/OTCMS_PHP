<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class UserGroup{
	public $userID		= 0;
	public $groupID		= 0;
	public $row			= null;
	public $fieldStr	= '';
	public $errMode	= '';

	public function __construct($userID=0, $fieldStr='UG_theme,UG_infoTotalNum,UG_infoDayNum,UG_infoScore1,UG_infoScore2,UG_infoScore3,UG_event', $errMode='die'){
		$this->GetId($userID);
		if ($fieldStr != 'no'){
			$this->GetData($fieldStr);
		}
		$this->fieldStr	= $fieldStr;
		$this->errMode	= $errMode;
	}


	// 获取当前真实会员组ID
	public static function CurrId($groupID, $isTime, $endTime){
		if ($isTime == 1){
			if (strtotime($endTime) >= time()){
				return $groupID;
			}else{
				return 1;
			}
		}else{
			return $groupID;
		}
	}


	// 获取会员组名称
	public static function CurrName($groupID, $userGroupArr=array()){
		if (count($userGroupArr) == 0){
			$userGroupArr = Cache::PhpFile('userGroup');
		}
		return isset($userGroupArr[$groupID]) ? $userGroupArr[$groupID] : '无';
	}

	// 获取会员组名称（旧函数，为兼容保留，后期废弃）
	public static function ArrName($groupID, $userGroupArr=array()){
		return self::CurrName($groupID, $userGroupArr);
	}


	// 获取会员组ID
	public function GetId($userID=0){
		if ($userID > 0){
			global $DB;
			$this->userID = $userID;
			$urow = $DB->GetRow('select UE_groupID,UE_isGroupTime,UE_groupTime from '. OT_dbPref .'users where UE_ID='. $userID);
			$this->groupID = self::CurrId($urow['UE_groupID'], $urow['UE_isGroupTime'], $urow['UE_groupTime']);
		}
		return $this->groupID;
	}


	// 获取会员组名称
	public function GetName($userGroupArr=array()){
		if (isset($this->row['UG_theme'])){
			return $this->row['UG_theme'];
		}else{
			if (count($userGroupArr) == 0){
				$userGroupArr = Cache::PhpFile('userGroup');
			}
			return isset($userGroupArr[$this->groupID]) ? $userGroupArr[$this->groupID] : '无';
		}
	}


	// 获取会员组字段信息
	public function GetData($fieldStr=''){
		if ($this->row && strlen($fieldStr)==0){
		
		}else{
			global $DB;
			if (strlen($fieldStr)==0){ $fieldStr = $this->fieldStr; }
			$this->row = $DB->GetRow('select '. $fieldStr .' from '. OT_dbPref .'userGroup where UG_ID='. $this->groupID);
		}
		return $this->row;
	}


	// 获取会员总投稿数限制
	public function InfoTotalNumArr(){
		if (isset($this->row['UG_infoTotalNum'])){
			if ($this->row['UG_infoTotalNum'] > 0){
				global $DB;
				$currInfoNum = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_userID='. $this->userID);
				$diffNum = $this->row['UG_infoTotalNum'] - $currInfoNum;
				if ($diffNum <= 0){
					if ($diffNum < 0){ $diffNum = 0; }
					return array('res'=>false, 'note'=>'您当前总投稿数限制 '. $this->row['UG_infoTotalNum'] .' 篇，已投稿 '. $currInfoNum .' 篇，还剩 '. $diffNum .' 篇。');
				}else{
					return array('res'=>true, 'note'=>'您当前总投稿数限制 '. $this->row['UG_infoTotalNum'] .' 篇，已投稿 '. $currInfoNum .' 篇，还剩 '. $diffNum .' 篇。');
				}
			}else{
				return array('res'=>true, 'note'=>'');	// 您当前会员组总投稿数无限制。
			}
		}else{
			return array('res'=>false, 'note'=>'您当前会员组不存在，有问题请联系管理员。');
		}
	}


	// 获取会员每日投稿限制
	public function InfoDayNumArr(){
		if (isset($this->row['UG_infoDayNum'])){
			global $DB;
			$todayDate	= TimeDate::Get('date');
			$todayTomo	= TimeDate::Add('d',1,$todayDate);
			$currInfoNum = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_userID='. $this->userID .' and IF_time>='. $DB->ForTime($todayDate) .' and IF_time<'. $DB->ForTime($todayTomo));
			$diffNum = $this->row['UG_infoDayNum'] - $currInfoNum;
			if ($diffNum <= 0){
				if ($diffNum < 0){ $diffNum = 0; }
				return array('res'=>false, 'note'=>'您当前每日投稿限制 '. $this->row['UG_infoDayNum'] .' 篇，已投稿 '. $currInfoNum .' 篇，还剩 '. $diffNum .' 篇。');
			}else{
				return array('res'=>true, 'note'=>'您当前每日投稿限制 '. $this->row['UG_infoDayNum'] .' 篇，已投稿 '. $currInfoNum .' 篇，还剩 '. $diffNum .' 篇。');
			}
		}else{
			return array('res'=>false, 'note'=>'您当前会员组不存在，有问题请联系管理员。');
		}
	}



	// 权限列表
	public static function RightList(){
		global $DB,$mudi,$userRow,$userSysArr;

		$retStr = '
		<div style="padding:6px;">
			积分1='. $userSysArr['US_score1Name'] .'，
			积分2='. ($userSysArr['US_isScore2'] == 1 ? $userSysArr['US_score2Name'] : '无') .'，
			积分3='. ($userSysArr['US_isScore3'] == 1 ? $userSysArr['US_score3Name'] : '无') .'
		</div>
		<table cellpadding="0" cellspacing="0" border="0" class="tabList1">
		<thead>
		<tr>
			<td width="6%" align="center" style="text-align:center;">编号</td>
			<td width="25%" align="center" style="text-align:center;">会员组名称</td>
			<td width="69%" align="center" style="text-align:center;">权限信息</td>
		</tr>
		</thead>
		';
		$dataNum = 0;
		$showexe = $DB->query('select * from '. OT_dbPref .'userGroup where UG_state=1 order by UG_rank ASC');
		while ($row = $showexe->fetch()){
			if ($dataNum % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
			$dataNum ++;
			$tougaoStr = '<span style="color:green;font-weight:bold;">允许</span>';
			$shenheStr = '<span style="color:red;font-weight:bold;">关闭</span>';
			if (strpos($row['UG_event'],'|禁止投稿|') !== false){
				$tougaoStr = '<span style="color:red;font-weight:bold;">禁止</span>';
			}
			if (strpos($row['UG_event'],'|投稿免审核|') !== false){
				$shenheStr = '<span style="color:green;font-weight:bold;">开启</span>';
			}
			$retStr .= '
			<tr '. $bgcolor .'>
				<td align="center" style="text-align:center;">'. $dataNum .'</td>
				<td align="center" style="text-align:center;">'. $row['UG_theme'] .'</td>
				<td align="left" style="line-height:1.5;padding:5px;">
					总投稿数'. ($row['UG_infoTotalNum'] > 0 ? '≤ <span style="color:red;font-weight:bold;">'. $row['UG_infoTotalNum'] .'</span> 篇' : '<span style="color:red;font-weight:bold;">无限制</span>') .'；
					每日投稿≤ <span style="color:red;font-weight:bold;">'. $row['UG_infoDayNum'] .'</span> 篇；
					投稿：'. $tougaoStr .'；投稿免审核：'. $shenheStr .'
					<div>最大阅读/扣积分（积分1≤ <span style="color:red;font-weight:bold;">'. $row['UG_infoScore1'] .'</span>，积分2≤ <span style="color:red;font-weight:bold;">'. $row['UG_infoScore2'] .'</span>，积分3≤ <span style="color:red;font-weight:bold;">'. $row['UG_infoScore3'] .'</span>）</div>
					'. AppUserGroup::UserGroupItem($row['UG_kaitongDay'], $row['UG_kaitongMoney'], $row['UG_kaitongScore1'], $row['UG_kaitongScore2'], $row['UG_kaitongScore3'], $row['UG_xufeiDay'], $row['UG_xufeiMoney'], $row['UG_xufeiScore1'], $row['UG_xufeiScore2'], $row['UG_xufeiScore3']) .'
					'. AppUserGroupWork::UserGroupItem($row['UG_workDay'], $row['UG_workMoney'], $row['UG_workScore1'], $row['UG_workScore2'], $row['UG_workScore3']) .'
				</td>
			</tr>
			';
		}
		unset($showexe);
		$retStr .= '
		</tbody>
		</table>
		';

		return $retStr;
	}
}
?>