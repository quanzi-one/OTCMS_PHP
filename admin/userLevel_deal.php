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



switch($mudi){
	case 'add':
		$menuFileID = 196;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 197;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	Case 'send':
		$menuFileID = 197;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		Send();
		break;

	Case 'del':
		$menuFileID = 198;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加与修改
function AddOrRev(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostRegExpStr('dataType','sql');
	$dataTypeCN		= OT::PostRegExpStr('dataTypeCN','sql');
	$dataID			= OT::PostInt('dataID');

	$num			= OT::PostInt('num');
	$theme			= OT::PostStr('theme');
	$themeStyle		= OT::PostStr('themeStyle');
	$img			= OT::PostStr('img');
	$score1			= OT::PostInt('score1');
	$score2			= OT::PostInt('score2');
	$score3			= OT::PostInt('score3');
	$infoNum		= OT::PostInt('infoNum');
	$maxAddInfo		= OT::PostInt('maxAddInfo');

	
	if ($num<1 && $theme=='' && $img==''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	if ($dataID == 1){
		$score1		= -1;
		$score2		= -1;
		$score3		= -1;
	}
	
	$record = array();
	$record['UL_num']		= $num;
	$record['UL_theme']		= $theme;
	$record['UL_themeStyle']= $themeStyle;
	$record['UL_img']		= $img;
	$record['UL_score1']	= $score1;
	$record['UL_score2']	= $score2;
	$record['UL_score3']	= $score3;
	$record['UL_infoNum']	= $infoNum;
	$record['UL_maxAddInfo']= $maxAddInfo;

	if ($dataID==0){
		$alertMode='添加';
	
		$judResult = $DB->InsertParam('userLevel',$record);
	}else{
		$alertMode='修改';

		$judResult = $DB->UpdateParam('userLevel',$record,'UL_ID='. $dataID);
	}
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}


	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】'. $alertMode . $alertResult .'！',
		));


	JS::AlertHref(''. $alertMode . $alertResult .'！',$backURL);

}



// 数据发送
function Send(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$numID			= OT::GetInt('typeNum');
	$dataID			= OT::GetInt('dataID');
	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');

	$sendexe=$DB->query('select * from '. OT_dbPref .'userLevel where UL_ID='. $dataID);
		if (! $row = $sendexe->fetch()){
			JS::AlertEnd('搜索不到指定记录');
		}

	if ($dataID == 1){
		$scoreRead = 'true';
	}else{
		$scoreRead = 'false';
	}
	$UL_themeStyle = $row['UL_themeStyle'];

	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("num").value="'. $row['UL_num'] .'";
	parent.$id("dataID").value="'. $row['UL_ID'] .'";
	parent.$id("theme").value="'. $row['UL_theme'] .'";
	');
	if (strpos($UL_themeStyle,'font-weight:bold;') !== false){
		echo('parent.$id("themeStyleB").checked=true;');
		$UL_themeStyle = str_replace('font-weight:bold;','',$UL_themeStyle);
	}else{
		echo('parent.$id("themeStyleB").checked=false;');
	}

	$UL_themeStyle = str_replace(array('color:',';'), array('',''), $UL_themeStyle);
	if (strlen($UL_themeStyle) == 7){
		echo('parent.$id("themeStyleColor").value="'. $UL_themeStyle .'";');
	}else{
		echo('parent.$id("themeStyleColor").value="";');
	}

	echo('
	parent.CheckColorBox();
	parent.$id("img").value="'. $row['UL_img'] .'";
	parent.$id("score1").value="'. $row['UL_score1'] .'";
	try { parent.$id("score2").value="'. $row['UL_score2'] .'"; }catch (e){}
	try { parent.$id("score3").value="'. $row['UL_score3'] .'"; }catch (e){}
	try { parent.$id("num").readOnly='. $scoreRead .'; }catch (e){}
	try { parent.$id("score1").readOnly='. $scoreRead .'; }catch (e){}
	try { parent.$id("score2").readOnly='. $scoreRead .'; }catch (e){}
	try { parent.$id("score3").readOnly='. $scoreRead .'; }catch (e){}
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	unset($sendexe);
}



// 删除
function del(){
	global $DB,$mudi,$menuFileID,$menuTreeID,$dataType,$dataTypeCN;

	$dataID	= OT::GetInt('dataID');
	$theme	= OT::GetStr('theme');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误！');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'userLevel where UL_ID='. $dataID);
		if ($judResult){
			$alertResult = '成功';
		}else{
			$alertResult = '失败';
		}


	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));


	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}

?>