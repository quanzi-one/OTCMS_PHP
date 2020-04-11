<?php
require(dirname(__FILE__) .'/check.php');


$type		= OT::GetStr('type');
$isAjaxRun	= OT::GetInt('isAjaxRun');
$beforeURL	= GetUrl::CurrDir();

$nowDate	= TimeDate::Get('date');
$nowMonth	= TimeDate::Get('m');

// 获取模板参数数组
if ($autoRunSysFile = @include(OT_ROOT .'cache/php/autoRunSys.php')){
	$autoRunSysArr = unserialize($autoRunSysFile);
}else{
	$Cache = new Cache();
	$Cache->Php('autoRunSys');
	die('/* 加载autoRunSys配置文件失败，请重新刷新 */');
}

if (strlen($type) == 0 || strpos($autoRunSysArr['ARS_runArea'],'|'. $type .'|') === false){
	StrShow('time', $type .' 不再 '. $autoRunSysArr['ARS_runArea'] .' 范围内');
	die();
}

if (strtotime($autoRunSysArr['ARS_dayDate']) != strtotime($nowDate)){
	$judTodayRun = true;
}else{
	$judTodayRun = false;
}

// 生成configJs.js 基本信息
if ($judTodayRun){
	if (Cache::UpdateConfigJs()){
		StrShow('time', '生成configJs.js成功');
	}else{
		StrShow('time', '生成configJs.js失败');
	}
}


// 判断是否运行定时检查
if ($autoRunSysArr['ARS_isTimeRun'] == 1){
	if (strtotime($autoRunSysArr['ARS_timeRunTime']) + $autoRunSysArr['ARS_timeRunMin']*60 < time()){
		$logStr = '';
		if (strpos($autoRunSysArr['ARS_timeRunItem'],'|infoContent|') !== false){
			$retArr = Area::AutoRunInfoContent();
			$logStr .= $retArr['note'] .';';
			StrShow('time', '【定时检查】'. $retArr['note']);
		}
		if (strpos($autoRunSysArr['ARS_timeRunItem'],'|taobaokeDataoke|') !== false){
			$retArr = AppTaobaoke::AutoRunDeal();
			if (strlen($logStr) > 3){ $logStr .= '<br />'; }
			$logStr .= $retArr['note'] .';';
			StrShow('time', '【定时检查】'. $retArr['note']);
		}

		$todayTime = TimeDate::Get();
		$DB->query('update '. OT_dbPref .'autoRunSys set ARS_timeRunTime='. $DB->ForTime($todayTime));

		$logArr = array();
		$logArr['ARL_time']		= $todayTime;
		$logArr['ARL_type']		= 'time';
		$logArr['ARL_dataID']	= 0;
		$logArr['ARL_content']	= ($type=='duli' ? '『独立页』' : '') . $logStr;
		$DB->InsertParam('autoRunLog',$logArr);

		$Cache = new Cache();
		$Cache->Php('autoRunSys');
		$Cache->Js('autoRunSys');

	}else{
		StrShow('time', '【定时检查】未到时间 '. $autoRunSysArr['ARS_timeRunTime'] .'['. $autoRunSysArr['ARS_timeRunMin'] .']');
	}
}else{
	StrShow('time', '【定时检查】未开启');
}


// 判断是否生成首页静态页
if ($autoRunSysArr['ARS_isHtmlHome'] == 1 && $systemArr['SYS_isHtmlHome'] == 1){
	if ($judTodayRun || strtotime($autoRunSysArr['ARS_htmlHomeTime']) + $autoRunSysArr['ARS_htmlHomeMin']*60 < time()){
		ResShow('home', 'makeHtml_run.php?mudi=homeHtml');
	}else{
		StrShow('home', '生成首页静态页 index.html 未到时间 '. $autoRunSysArr['ARS_htmlHomeTime'] .'['. $autoRunSysArr['ARS_htmlHomeMin'] .']');
	}
}else{
	StrShow('home', '生成首页静态页 index.html 未开启');
}



// 判断是否生成列表静态页
if ($autoRunSysArr['ARS_isHtmlList'] == 1){
	if (strtotime($autoRunSysArr['ARS_htmlListTime']) + $autoRunSysArr['ARS_htmlListMin']*60 < time()){
		AppAutoHtml::PcList();
	}else{
		StrShow('list', '生成列表静态页 index.html 未到时间 '. $autoRunSysArr['ARS_htmlListTime'] .'['. $autoRunSysArr['ARS_htmlListMin'] .']');
	}
}else{
	StrShow('list', '生成列表静态页 index.html 未开启');
}


// 判断是否生成内容静态页
if ($autoRunSysArr['ARS_isHtmlShow'] == 1){
	if (strtotime($autoRunSysArr['ARS_htmlShowTime']) + $autoRunSysArr['ARS_htmlShowMin']*60 < time()){
		AppAutoHtml::PcShow();
	}else{
		StrShow('show', '生成内容静态页 index.html 未到时间 '. $autoRunSysArr['ARS_htmlShowTime'] .'['. $autoRunSysArr['ARS_htmlShowMin'] .']');
	}
}else{
	StrShow('show', '生成内容静态页 index.html 未开启');
}



$isAjaxRun = 0;
// 判断是否自动采集
if ($autoRunSysArr['ARS_isColl'] == 1){
	if (strtotime($autoRunSysArr['ARS_collTime']) + $autoRunSysArr['ARS_collMin']*60 < time()){
		StrShow('coll', '采集配置初始化中......');
		ResShow('coll', 'collRun2.php?mudi=start');

	}else{
		StrShow('coll', '自动采集 未到时间 '. $autoRunSysArr['ARS_collTime'] .'['. $autoRunSysArr['ARS_collMin'] .']');
	}
}else{
	StrShow('coll', '自动采集 未开启');
}




function GetSession($str){
	global $sessID;
	if (empty($sessID)){ $sessID = OT_SiteID; }

	$retStr = @$_SESSION[$sessID .'make_'. $str];
	if (strlen($retStr)==0){
		$retStr = @$_COOKIE['make_html'];
	}
	return $retStr;
}

function SetSession($str,$value){
	global $sessID;
	if (empty($sessID)){ $sessID = OT_SiteID; }

	$_SESSION[$sessID .'make_'. $str] = $value;
	// setcookie('make_html', $value);
	$_SESSION[$sessID .'makeWap_'. $str] = $value;
	// setcookie('makeWap_html', $value);
}


function ResShow($type,$str){
	global $isAjaxRun;
	if ($isAjaxRun == 1 || $isAjaxRun == 11){
		$runStr = '
			try { console.log("【'. $type .'】'. $str .'"); }catch(e){}
			if (typeof(jsPathPart) == "undefined"){ jsPathPart = ""; }
			$.ajaxSetup({cache:false});
			$.get(jsPathPart +"'. $str .'", function(result){
				try { console.log("【'. $type .'结果】"+ result); }catch(e){}
			});
			';
		if ($isAjaxRun == 11){
			die($runStr);
		}else{
			echo($runStr);
		}
	}else{
		$runStr = '
			try { console.log("【'. $type .'】[iframe]'. $str .'"); }catch(e){}
			if (typeof(jsPathPart) == "undefined"){ jsPathPart = ""; }
			autoRun_'. $type .'.location.href=jsPathPart +"'. $str .'";
			';
		if ($isAjaxRun == 10){
			die($runStr);
		}else{
			echo($runStr);
		}
	}
}


function StrShow($type,$str){
	global $isAjaxRun;
	if ($isAjaxRun == 1 || $isAjaxRun == 11){
		echo('
		try { console.log("【'. $type .'】'. $str .'"); }catch(e){}
		/* 【'. $type .'】'. $str .' */
		');
	}else{
		echo('
		try { console.log("【'. $type .'】[iframe]'. $str .'"); }catch(e){}
		var doc = document.getElementById("autoRun_'. $type .'").contentDocument || document.frames["autoRun_'. $type .'"].document;
		doc.body.innerHTML += "'. $str .'";
		');
	}
}
?>