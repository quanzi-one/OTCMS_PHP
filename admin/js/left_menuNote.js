window.onload=function(){
	WindowHeight(20);
	parent.$id('RightFrmBg').style.backgroundColor='#f4f8fc';
	try {
		adminDirName = $id('adminDir').value;
		judConnInternet = $id('isConnInternet').value;
	}catch (e) {
		adminDirName = "";
		judConnInternet = 0;
	}

	if (judConnInternet==1){
		var readOTwebSec = 10000;

		$.ajaxSetup({cache:false});
		$.get("read.php?mudi=getSignalSec", function(result){
			eval(result.replace(/<\s*(script[^>]*)>([\s\S][^<]*)<\/\s*script>/gi,"$2"));
			if (readOTwebSec<=800){
				try {
					softVerDiffD = parseInt($id('softVerDiffDay').value);
						if (isNaN(softVerDiffD)){ softVerDiffD=0; }
					isAnnounS = parseInt($id('isAnnounShow').value);
						if (isNaN(isAnnounS)){ isAnnounS=0; }
						
					if (softVerDiffD<=0 && softVerDiffD!=-1){
						CheckSoftVer();
						CheckSoftPay();
					}
					if (isAnnounS==1){
						$id('onetiAnnoun').src=$id('OTCMS_softUpdate').value;
					}else{
						$id('onetiAnnoun').src="read.php?mudi=announNoUpdate&url="+ encodeURIComponent($id('OTCMS_softUpdate').value) +"&rnd=";
					}
				}catch (e) {}
			}else{
				$id('onetiAnnoun').src="read.php?mudi=announBlank&url="+ encodeURIComponent($id('OTCMS_softUpdate').value) +"&rnd=";
			}
		});
	}else{
		try{
			$id('onetiAnnoun').src="read.php?mudi=announNoUpdate&url="+ encodeURIComponent($id('OTCMS_softUpdate').value) +"&rnd=";
		}catch (e){}
	}
	WindowHeight(20);
}



// 检测最新版本信息
function CheckSoftPay(){
	AjaxGetDeal("appShop_deal.php?mudi=getInfo&mode=no");
}

// 检测最新版本信息
function CheckSoftVer(queryStr){
	$id('softUser_verTimeStr').innerHTML="<img src='images/onload.gif' />";
	AjaxGetDeal($id('OTCMS_userUrlSoftVer').value +"&isAlert="+ queryStr);
}


// 检测升级内容框 显示、关闭
function CheckUpdateBox(){
	WindowHeight(200);
	if ($id('updateBox').style.display==""){
		$id('updateBox').style.display="none";
	}else{
		$id('updateBox').style.display="";
		if (ToInt($id('updateV2Box').style.height.replace("px",""))>500){
			$id('updateV2Box').style.height="170px";
		}
	}
	WindowHeight(0);

	return false;
}

// 清空升级库数据
function ClearUpdateDb(){
	AjaxGetDeal("update_deal.php?mudi=clearUpdateData");
}


// 更新数据库备份提醒时间
function UpdateBackupCall(num){
	AjaxGetDeal("readDeal.php?mudi=updateBackupCall&num="+ num);
	$id('backupCallBox').style.display="none";
}


// 清空缓存文件
function ClearSiteCache(){
	alert("如果缓存文件比较多要多等点时间才会弹出结果.\n如果很久没弹出结果，可能卡了，可以重新点击。")
	AjaxGetDealToAlert("system_deal.php?mudi=clearCache");
	return false;
}


// 清空手机版缓存文件
function ClearWapHtml(){
	alert("如果缓存文件比较多要多等点时间才会弹出结果.\n如果很久没弹出结果，可能卡了，可以重新点击。")
	AjaxGetDealToAlert("wap_deal.php?mudi=clearHtml");
	return false;
}


var updateLastFileName,updateVerID,updateFileListStr,judUpdateLastFile,updateFileArr,updateFileEndNum;
var runFileSkipStr = "";