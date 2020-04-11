<?php
header('Content-Type: text/html; charset=UTF-8');
define('OT_ROOT', dirname(__FILE__) .'/');
require(OT_ROOT .'configVer.php');


$mudi = trim(@$_GET['mudi']);
switch ($mudi){
	case 'autoRun':
		AutoRun();
		break;

	default:
		die('err');
}





// 自动操作
function AutoRun(){
	$mode = trim(@$_GET['mode']);
	$sec = trim(@$_GET['sec']);
		if (is_numeric($sec) == false){ $sec = 300; }
		if ($sec < 60){ $sec = 300; }
	?>

	<!DOCTYPE html>
	<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<title>自动操作检测</title>
		<script language="javascript" type="text/javascript" src="js/inc/jquery.min.js?v=<?php echo(OT_VERSION); ?>"></script>
		<script language="javascript" type="text/javascript" src="cache/js/autoRunSys.js?v=<?php echo(date('dHis')); ?>"></script>
	</head>
	<body>
		<div id="countSec" style="color:red"></div>

		<script language='javascript' type='text/javascript'>
		var dbPathPart='';
		var pcPathPart="";
		var webPathPart='';
		var jsPathPart='';
		var webTypeName="home";

		var runSec = <?php echo($sec); ?>;	// 秒数
		function CountDown(){
			if (runSec >= 0){
				document.getElementById("countSec").innerHTML = "还有 "+ runSec +" 秒刷新本页面";
				runSec --;
			}else{
				clearInterval(timer);
				window.location.reload();
			}
		}
		timer = setInterval("CountDown()", 1000);

		// GET提交AJAX处理
		function AjaxGetDeal(urlStr){
			$.ajaxSetup({cache:false});
			$.get(urlStr, function(result){
				eval(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,""));
			});
			return false;
		}

		var myDate = new Date();
		var timestamp = Date.parse(myDate);
		var mode = "<?php echo($mode); ?>";
		timestamp = timestamp/1000;
		var isRun = 0;
		if (mode == "wap"){
			if (ARS_isHtmlHome == 1 && ARS_htmlHomeWapTimer + ARS_htmlHomeMin * 60 < timestamp){
				isRun = 1;
			}
			if (isRun == 0 && ARS_isHtmlList == 1 && ARS_htmlListWapTimer + ARS_htmlListMin * 60 < timestamp){
				isRun = 1;
			}
			if (isRun == 0 && ARS_isHtmlShow == 1 && ARS_htmlShowWapTimer + ARS_htmlShowMin * 60 < timestamp){
				isRun = 1;
			}
		}else{
			if (ARS_isHtmlHome == 1 && ARS_htmlHomeTimer + ARS_htmlHomeMin * 60 < timestamp){
				isRun = 1;
			}
			if (isRun == 0 && ARS_isHtmlList == 1 && ARS_htmlListTimer + ARS_htmlListMin * 60 < timestamp){
				isRun = 1;
			}
			if (isRun == 0 && ARS_isHtmlShow == 1 && ARS_htmlShowTimer + ARS_htmlShowMin * 60 < timestamp){
				isRun = 1;
			}
		}
		if (isRun == 0 && ARS_isColl == 1 && ARS_collTimer + ARS_collMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 0 && ARS_isTimeRun == 1 && ARS_timeRunTimer + ARS_timeRunMin * 60 < timestamp){
			isRun = 1;
		}
		// if (isRun == 1){ // #topUserBox
			ARS_runMode=0;
			$("body").append(''+
				'<iframe id="autoRun_time" name="autoRun_time" src="about:blank" width="250" height="200"></iframe>'+
				'<iframe id="autoRun_home" name="autoRun_home" src="about:blank" width="250" height="200"></iframe>'+
				'<iframe id="autoRun_list" name="autoRun_list" src="about:blank" width="250" height="200"></iframe>'+
				'<iframe id="autoRun_show" name="autoRun_show" src="about:blank" width="250" height="200"></iframe>'+
				'<iframe id="autoRun_coll" name="autoRun_coll" src="about:blank" width="750" height="200"></iframe>'+
				'');
			var arHome_window=window.frames["autoRun_home"];
			arHome_window.window.alert=function(){ return false; };
			var arList_window=window.frames["autoRun_list"];
			arList_window.window.alert=function(){ return false; };
			var arShow_window=window.frames["autoRun_show"];
			arShow_window.window.alert=function(){ return false; };
			var arColl_window=window.frames["autoRun_coll"];
			arColl_window.window.alert=function(){ return false; };

			if (mode == "wap"){
				AjaxGetDeal("wap/autoRun.php?type=duli&isAjaxRun="+ ARS_runMode +"&rnd="+ timestamp);
			}else{
				AjaxGetDeal("autoRun.php?type=duli&isAjaxRun="+ ARS_runMode +"&rnd="+ timestamp);
			}
		/* }else{
			document.write("暂无可执行的自动操作。");
		} */
		</script>
	</body>
	</html>

	<?php
}

?>