<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();



/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


//用户检测
$MB->Open('','login',10);
$MB->IsMenuRight('alertBack','用户管理');


switch($mudi){
	case 'deal':
		$MB->IsMenuRight('alertBack','用户维护');
		AddOrRev();
		break;

	case 'del':
		$MB->IsMenuRight('alert','用户维护');
		Del();
		break;

	case 'send':
		$MB->IsMenuRight('alert','用户维护');
		SendData();
		break;

	default:
		die('err');
} 

$MB->Close();
$DB->Close();





function AddOrRev(){
	global $DB;

	$userMode	= OT::PostStr('userMode');
	$userID		= OT::PostInt('userID');
	$userpwd	= OT::Post('userpwd');
	$username	= OT::PostStr('username');
	$realname	= OT::PostStr('realname');
	$groupID	= OT::PostInt('groupID');
	$state		= OT::PostInt('state');

	if($username=='' || $realname=='' || $userMode==''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$addrec=$DB->query('select MB_ID from '. OT_dbPref .'member where MB_username='. $DB->ForStr($username) .' and MB_ID<>'. $userID);
		if ($addrec->fetch()){
			JS::AlertBackEnd('该用户名已存在，请换个！');
		}
	$addrec=null;

	$record=array();
	$record['MB_username']	= $username;
	$record['MB_realname']	= $realname;
	$record['MB_groupID']	= $groupID;
	$record['MB_state']		= $state;

	if ($userMode=='rev'){
		$alertStr='修改';
		if (strlen($userpwd)>=6){
			$userKey = OT::RndChar(5);
			$record['MB_userKey']	= $userKey;
			$record['MB_userpwd']	= md5(md5($userpwd) . $userKey);
		}
		$DB->UpdateParam('member',$record,"MB_rightStr<>'admin' and MB_ID=". $userID);
	}else{
		$alertStr='添加';
		$record['MB_time']		= TimeDate::Get();
		$record['MB_rightStr']	= '|';
		$userKey = OT::RndChar(5);
		$record['MB_userKey']	= $userKey;
		$record['MB_userpwd']	= md5(md5($userpwd) . $userKey);
		$record['MB_itemNum']	= 20;
		$DB->InsertParam('member',$record);
	}
	JS::AlertHref($alertStr .'用户成功！','member.php?mudi=manage');
}



function Del(){
	global $DB,$MB;

	$dataID = OT::GetInt('dataID');
	$groupID=$DB->GetOne('select MB_groupID from '. OT_dbPref .'member where MB_ID>1 and MB_ID='. $dataID);
	$UGexe=$DB->query('select MG_rightStr from '. OT_dbPref .'memberGroup where MG_ID='. $groupID);
		if ($row = $UGexe->fetch()){
			if ($MB->GetRightStr() != 'admin' && strpos($row['MG_rightStr'],'|用户管理|') !== false){
				 JS::AlertEnd('你无权删除该用户！\n可能对方跟你权限同级');
			}
		}
	unset($UGexe);

	$DB->query('delete from '. OT_dbPref .'member where MB_ID>1 and MB_ID='. $dataID);

	echo('<script language="javascript" type="text/javascript">parent.$id("data'. $dataID .'").style.display="none"</script>');
}



function SendData(){
	global $DB,$MB;

	$dataID = OT::GetInt('dataID');

	$sendexe=$DB->query('select MB_ID,MB_username,MB_realname,MB_groupID,MB_state from '. OT_dbPref .'member where MB_ID='. $dataID);
		if (! $row = $sendexe->fetch()){
			JS::AlertEnd('指定ID错误！');
		}else{
			$UGexe=$DB->query('select MG_rightStr from '. OT_dbPref .'memberGroup where MG_ID='. $row['MB_groupID']);
				if ($row2 = $UGexe->fetch()){
					if ($MB->GetRightStr() != 'admin' && strpos($row2['MG_rightStr'],'|用户管理|') !== false){
						JS::AlertEnd('你无权修改该用户！\n可能对方跟你权限同级.');
					}
				}
			unset($UGexe);

			echo('
			<script language="javascript" type="text/javascript">
			parent.$id("formTitle").innerHTML = "修改用户";
			parent.$id("formSubmit").innerHTML = \'<input type="image" src="images/button_rev.gif">\';
			parent.$id("userAlertStr").innerHTML = "<br>提示：如不想修改密码，密码框请留空！";
			parent.$id("userMode").value = "rev";
			parent.$id("userID").value = "'. $row['MB_ID'] .'";
			parent.$id("username").value = "'. $row['MB_username'] .'";
			parent.$id("realname").value = "'. $row['MB_realname'] .'";
			parent.$id("state").value = '. $row['MB_state'] .';
			parent.$id("groupID").value = '. $row['MB_groupID'] .';
			parent.WindowHeight(0);
			</script>
			');
		}
	unset($sendexe);
}

?>