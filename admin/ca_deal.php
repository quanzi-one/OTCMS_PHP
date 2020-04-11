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
$MB->Open('','login');



switch ($mudi){
	case 'add':
		$menuFileID = 147;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'rev':
		$menuFileID = 148;
		$MB->IsSecMenuRight('alertBack',$menuFileID,$dataType);
		AddOrRev();
		break;

	case 'send':
		$menuFileID = 148;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		Send();
		break;

	case 'del':
		$menuFileID = 149;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		del();
		break;

	case 'stateUpdate':
		$menuFileID = 148;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		StateUpdate();
		break;

	case 'cacheUpdate':
		$menuFileID = 148;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		CacheUpdate();
		break;

	case 'cacheDefUpdate':
		$menuFileID = 148;
		$MB->IsSecMenuRight('alert',$menuFileID,$dataType);
		CacheDefUpdate();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 添加与修改
function AddOrRev(){
	global $DB,$MB,$mudi,$menuFileID,$menuTreeID;

	$backURL		= OT::PostStr('backURL');
	$dataType		= OT::PostStr('dataType');
	$dataTypeCN		= OT::PostStr('dataTypeCN');
	$dataID			= OT::PostInt('dataID');
	$num			= OT::PostInt('num');
	$theme			= OT::PostStr('theme');
	$code			= OT::PostStr('code');
	$upImgStr		= OT::PostStr('upImgStr');
		$upImgStr = ServerFile::EditorImgStr($upImgStr,$code);
	$divStyle_top	= OT::PostInt('divStyle_top');
	$divStyle_right	= OT::PostInt('divStyle_right');
	$divStyle_bottom= OT::PostInt('divStyle_bottom');
	$divStyle_left	= OT::PostInt('divStyle_left');
	$divStyle = 'margin-top:'. $divStyle_top .'px;margin-right:'. $divStyle_right .'px;margin-bottom:'. $divStyle_bottom .'px;margin-left:'. $divStyle_left .'px;';
	$areaStr		= OT::Post('areaStr');
		if (is_array($areaStr)){ $areaStr = implode(',',$areaStr); }

	$width		= OT::PostInt('width');
	$height		= OT::PostInt('height');
	$isTime		= OT::PostInt('isTime');
	$startTime	= OT::PostStr('startTime');
		if (! strtotime($startTime)){ $startTime=TimeDate::Get(); }
	$endTime	= OT::PostStr('endTime');
		if (! strtotime($endTime)){ $endTime='2029-12-31'; }
	$price		= OT::PostFloat('price');
	$rank		= OT::PostInt('rank');
	$state		= OT::PostInt('state');

	if ($num == 0 || $theme == ''){
		JS::AlertBackEnd('表单内容接收不全');
	}

	$checkexe = $DB->query('select AD_ID from '. OT_dbPref .'ad where AD_ID<>'. $dataID .' and AD_num='. $num);
	if ($checkexe->fetch()){
		JS::AlertBackEnd('编号重复，编号必须是唯一的');
	}
	unset($checkexe);

		$beforeURL=GetUrl::CurrDir(1);
		$imgUrl	= $beforeURL . InfoImgDir;
		$code	= str_replace($imgUrl,InfoImgAdminDir,$code);

	$record=array();
	$record['AD_num']		= $num;
	$record['AD_theme']		= $theme;
	$record['AD_code']		= $code;
	$record['AD_upImgStr']	= $upImgStr;
	$record['AD_divStyle']	= $divStyle;
	$record['AD_areaStr']	= $areaStr;
	$record['AD_width']		= $width;
	$record['AD_height']	= $height;
	$record['AD_isTime']	= $isTime;
	$record['AD_startTime']	= $startTime;
	$record['AD_endTime']	= $endTime;
	$record['AD_price']		= $price;
	$record['AD_rank']		= $rank;
	$record['AD_state']		= $state;

	$dealrec=$DB->query('select * from '. OT_dbPref .'ad where AD_ID='. $dataID);
	if (! $row = $dealrec->fetch()){
		$alertMode='添加';
		$record['AD_type'] = $dataType;

		$judResult = $DB->InsertParam('ad',$record);
	}else{
		$alertMode='修改';

		$judResult = $DB->UpdateParam('ad',$record,'AD_ID='. $dataID);
	}
	unset($dealrec);

	$dealStr='';
	if (Ad::MakeJs()){
		$todayTimeStr=TimeDate::Get('YmdHis');
		$DB->query("update ". OT_dbPref ."system set SYS_adTimeStr='". $todayTimeStr ."'");
		$dealStr='\n生成 cache/OTca.js 缓存文件成功。';
	}else{
		$dealStr='\n生成 cache/OTca.js 缓存文件失败，请检查根目录下的cache/目录是否有写入权限。';
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

	JS::AlertHref(''. $alertMode . $alertResult .'！'. $dealStr, $backURL);
}



// 数据发送
function Send(){
	global $DB;

	$dataMode		= OT::GetStr('dataMode');
	$dataModeStr	= OT::GetStr('dataModeStr');
	$numID			= OT::GetInt('typeNum');
	$dataID			= OT::GetInt('dataID');

	$sendexe=$DB->query('select * from '. OT_dbPref .'ad where AD_ID='. $dataID);
	if ($row = $sendexe->fetch()){
		JS::AlertEnd('搜索不到指定记录');
	}
	echo('
	<script language="javascript" type="text/javascript">
	parent.$id("dataID").value="'. $row['AD_ID'] .'";
	parent.$id("num").value="'. $row['AD_num'] .'";
	parent.$id("theme").value="'. $row['AD_theme'] .'";
	parent.$id("code").value="'. Str::MoreReplace($row['AD_code'],'js') .'";
	parent.$id("rank").value="'. $row['AD_rank'] .'";
	parent.$id("state").value="'. $row['AD_state'] .'";
	parent.$id("subButton").src="images/button_rev.gif";
	</script>
	');
	unset($sendexe);
}



// 删除
function del(){
	global $DB;

	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');
	$dataType	= OT::GetStr('dataType');
	$dataTypeCN	= OT::GetStr('dataTypeCN');

	if ($dataID<=0){
		JS::AlertEnd('指定ID错误！');
	}

	$numexe = $DB->query('select AD_num from '. OT_dbPref .'ad where AD_ID='. $dataID);
	if (! $row = $numexe->fetch()){
		JS::AlertEnd('搜索不到相关记录');
	}
	$num = $row['AD_num'];
	unset($numexe);

	if ( ($num>=3 && $num<=26) || ($num>=51 && $num<=70) || ($num>=101 && $num<=107) || ($num>=151 && $num<=160) ){
		JS::AlertEnd('系统内置广告禁止删除，你只能做状态：隐藏');
	}

	$judResult = $DB->query('delete from '. OT_dbPref .'ad where AD_ID='. $dataID);
	if ($judResult){
		$alertResult = '成功';

		Ad::MakeJs();
	}else{
		$alertResult = '失败';
	}

	Adm::AddLog(array(
		'theme'	=> $theme,
		'note'	=> '【'. $dataTypeCN .'】删除'. $alertResult .'！',
		));

	echo('<script language="javascript" type="text/javascript">parent.$id("data'. $dataID .'").style.display="none";</script>');
}



// 批量更新状态
function StateUpdate(){
	global $DB;

	$backURL	= OT::GetStr('backURL');
	$state		= OT::GetInt('state');
		if ($state == 1){
			$stateCN = '显示';
		}elseif ($state == 0){
			$stateCN = '隐藏';
		}else{
			JS::AlertBackEnd('操作目的错误');
		}

	$DB->query('update '. OT_dbPref .'ad set AD_state='. $state);

	$judResult = Ad::MakeJs();
	if ($judResult){
		$alertResult = '完毕';
		if ($state == 0){ $alertResult .= '\n【提醒】由于浏览器有缓存，如果前台还显示广告，请清空浏览器缓存或者换个新浏览器访问即可看到最新效果。'; }
	}else{
		$alertResult = '失败';
	}

	JS::AlertHrefEnd('状态全部'. $stateCN . $alertResult,$backURL);
}



// 更新广告缓存
function CacheUpdate(){
	$backURL	= OT::GetStr('backURL');

	$judResult = Ad::MakeJs();
	if ($judResult){
		$alertResult = '完毕';
	}else{
		$alertResult = '失败';
	}

	JS::AlertHrefEnd('广告缓存更新'. $alertResult,$backURL);
}



// 广告缓存提示化
function CacheDefUpdate(){
	$backURL	= OT::GetStr('backURL');

	$judResult = Ad::MakeTishiJs();
	if ($judResult){
		$alertResult = '完毕';
	}else{
		$alertResult = '失败';
	}

	JS::AlertHrefEnd('前台广告位置提示更新'. $alertResult .'，请到前台预览，预览完请点击【更新广告缓存】按钮恢复正常',$backURL);
}

?>