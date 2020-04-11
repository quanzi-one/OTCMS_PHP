window.onload=function(){
	UpdateWinHeight(0);
	parent.WindowHeight(0);
}


var updateTimeStamp=(new Date()).getTime();
//调整父窗口RightContentIframe的高度
function UpdateWinHeight(num){
	try {
		if (navigator.userAgent.indexOf('Firefox') >= 0){
			parent.$id('updateV2Box').style.height=350 +'px';
		}else if (navigator.userAgent.indexOf('Chrome') >= 0){
			if ((new Date()).getTime()<updateTimeStamp+1000){ parent.$id('updateV2Box').style.height='400px'; }
		}
		parent.$id('updateV2Box').style.height=(document.body.scrollHeight<130 ? 130 : document.body.scrollHeight) + num + 50 +'px';
	}catch (e) {}
}


// 更改升级网址
function UpdateUrlBtn(updateUrl){
	var bugMode = 0;
	try{
		if ($id('isUpdateUrlBug').checked){ bugMode = 1; }
	}catch (e){}

	if (bugMode == 1){
		var a=window.open("update_deal.php?mudi=changUpdateUrl&updateUrl="+ encodeURIComponent(updateUrl));
	}else{
		AjaxGetDeal("update_deal.php?mudi=changUpdateUrl&updateUrl="+ encodeURIComponent(updateUrl));
	}
}

// 进度状态图片
function updateImg(str){
	if (str=="yes"){
		return "<img src='images/img_yes.gif' alt='连接读取完毕' />"
	}else if (str=="no"){
		return "<img src='images/img_err.gif' alt='连接读取错误' />"
	}else{
		return "<img src='images/onload.gif' alt='连接读取中' />"
	}
}

// 清空升级库数据
function ClearUpdateDb(){
	AjaxGetDeal("update_deal.php?mudi=clearUpdateData");
}

// 初始化
function ConfigUpdateBox(){
	parent.$id('updateV2Step1').innerHTML="";
	parent.$id('updateV2Step2').innerHTML="";
	parent.$id('updateV2Step3').innerHTML="";
	parent.$id('updateV2Step4').innerHTML="";
	parent.$id('updateV2Step5').innerHTML="";
	CheckUpdateDbInfo();
}

// 检查升级库容量信息
function CheckUpdateDbInfo(){
	AjaxGetDeal("update_deal.php?mudi=dbInfo");
}

// 初始化窗口
function ConfigWindow(){
	ConfigUpdateBox();

	appID_ = 0;
	appType_ = "";
	try {
		appID_ = $id('appID').value
		appType_ = $id('appType').value
	}catch (e) {}

	document.location.href="?mudi=&appID="+ appID_ +"&appType="+ appType_;
}





//  升级事件集     当前升级最后一个文件名
var updateEventStr,updateLastFileName;
var runFileSkipStr = "";	// 执行文件跳过列表
//  当前OT_fileVer的ID，升级包信息，升级文件列表，升级当前文件位置，判断当前文件是否下载完
var updateVerID,updateVerInfo,updateFileListStr,updateFilePoint,judUpdateLastFile;
//  升级更新文件数组，升级更新文件数量
var updateFileArr,updateFileEndNum;

// 开始步骤1：链接官网升级库
function StartStep1(str){
	try {
		var judUpdateUrl=false;
		var updateUrlNum=0;
		for (var i=0; i<$name("updateUrl").length; i++){
			updateUrlNum++;
			if ($name("updateUrl")[i].checked){
				judUpdateUrl=true;
			}
		}
		if (judUpdateUrl==false && updateUrlNum>0){
			alert('请先选择升级路线.');$id("updateUrl1")[0].focus();return false;
		}
	}catch (e) {}

	appID_ = 0;
	appType_ = "";
	try {
		appID_ = $id('appID').value
		appType_ = $id('appType').value
	}catch (e) {}

	parent.$id('updateV2NoteStr').innerHTML="";
	parent.$id('updateV2Step1').innerHTML=updateImg(str);
	document.location.href='?mudi=getUpdate'+ parent.$id('updateUrlGetStr').value +"&appID="+ appID_ +"&appType="+ appType_;
}

// 开始步骤2：检测所需的目录文件权限
function StartStep2(str){
//	parent.$id('updateV2NoteStr').innerHTML="正在检测所需的目录文件权限，请稍等...";
	$id('updateContent').innerHTML="正在检测所需的目录文件权限，请稍等...";
	parent.$id('updateV2Step2').innerHTML=updateImg(str);
	$id('updateEventStr').value		= parent.$id('updateEventStr').value;
	$id('updateFileNum').value		= parent.$id('updateFileNum').value;
	$id('updateFileSize').value		= parent.$id('updateFileSize').value;
	$id('updateVerInfo').value		= parent.$id('updateVerInfo').value;
	$id('checkFileListStr').value	= parent.$id('checkFileListStr').value;

	$id('updateForm').action="updateV2.php?mudi=checkRight";
	$id('updateForm').submit();
}

// 结束步骤2
function EndStep2(){
	parent.$id('updateV2NoteStr').innerHTML="";
}

// 开始步骤3：下载更新文件
function StartStep3(str){
	parent.$id('updateV2NoteStr').innerHTML="";
	parent.$id('updateV2Step3').innerHTML=updateImg(str);

	$id('updateVerInfo').value	= parent.$id('updateVerInfo').value;
	parent.updateFileListStr	= parent.$id('updateFileListStr').value;
	parent.updateFilePoint		= parseInt(parent.$id('updateFilePoint').value);
		if (isNaN(parent.updateFilePoint)){ parent.updateFilePoint=0; }
	if (parent.updateVerInfo==""){
		alert("检索更新版本信息失败。\n请刷新该页面后重新操作下。");
	}else{
		$id('updateContent').innerHTML="获取并解析升级包信息，请稍等...";

		$id('updateForm').action="updateV2.php?mudi=getVer";
		$id('updateForm').submit();
	}
}


// 步骤3_1：获取更新文件列表
function DownloadFileGet(){
	str = parent.$id('updateFileListStr').value;
	$id('updateFileListStr').value = str;
	parent.updateFileArr = str.split("|");
	parent.updateFileEndNum = parent.updateFileArr.length-1;
	parent.updateFilePoint = 0;
}


// 步骤3_2：继续下载/重新下载
function DownloadGoOn(str){
	$id('updateContent').innerHTML="<br />正获取更新文件数据中...";
	if (str=="go"){
		if (parent.judUpdateLastFile=="true" && parent.updateLastFileName!=""){
			if (parent.$id('updateFileListStr').value.indexOf('|'+ parent.updateLastFileName +',')>=0){
				for (var i=0; i<=parent.updateFileEndNum; i++){
					if (parent.updateFileArr[i].indexOf(parent.updateLastFileName +',')>=0){
						parent.updateFilePoint=i;
						parent.judUpdateLastFile='false';
						break;
					}
				}
			}
		}
	}
	DownloadNext();
}

// 步骤3_3：判断升级更新文件下载顺序
function DownloadNext(){
	if (parent.updateFilePoint<parent.updateFileEndNum){
		parent.updateFilePoint++;
		DownloadFile();
	}else{
		if (parent.updateFilePoint==parent.updateFileEndNum){
			parent.$id('updateV2Step3').innerHTML=updateImg("yes");
			$id('updateContent').innerHTML="获取更新文件完毕。<br /><input type='button' value='运行升级过程' onclick='RunUpdateStep4()' />";
			return true;
		}
	}
}

// 步骤3_4：下载升级更新文件
function DownloadFile(){
	$id('updateVerTheme').value	= parent.$id('updateVerTheme').value;
	$id('updateFileSize').value	= parent.$id('updateFileSize').value;
	$id('fileStr').value		= parent.updateFileArr[parent.updateFilePoint];

	$id('updateForm').action="updateV2.php?mudi=getFile&fileEndNum="+ parent.updateFileEndNum +"&filePoint="+ parent.updateFilePoint +"&verID="+ parent.updateVerID +"&judUpdateLastFile="+ parent.judUpdateLastFile;
	$id('updateForm').submit();
}


// 运行步骤4：更新程序文件和执行脚本文件
function RunUpdateStep4(){
	parent.$id('updateV2Step4').innerHTML=updateImg("");
	$id('updateContent').innerHTML='正在升级中(如果网站数据库比较大，那需要的时间可能会多点)，请耐心等待...';

	$id('runFileSkipStr').value		= parent.runFileSkipStr;
	$id('updateEventStr').value		= parent.$id('updateEventStr').value;

	$id('updateForm').action="updateV2.php?mudi=runUpdate&verID="+ parent.updateVerID +"";
	$id('updateForm').submit();
}


// 检测执行脚本随机数正确性
function CheckRunRndCode(){
	if ($id("runRndCode").value!="" && $id("runRndCode").value==$id("trueRndCode").value){
		$id("rndCodeAlert").innerHTML="<span style='color:green;'>(正确)</span>";
	}else{
		$id("rndCodeAlert").innerHTML="<span style='color:red;'>(错误)</span>";
	}
}

// 重新运行升级过程
function CheckUpdateStep4(){
	if ($id("runRndCode").value!="" && $id("runRndCode").value==$id("trueRndCode").value){
		if (parent.runFileSkipStr.indexOf('|'+ $id("runFileName").value +'|')==-1){
			parent.runFileSkipStr += '|'+ $id("runFileName").value +'|';
		}
	}else{
		parent.runFileSkipStr = parent.runFileSkipStr.replace('|'+ $id("runFileName").value +'|','');
	}
	if ($id("runRndCode").value==""){
		if (confirm("您确定不输入校验码，重新运行升级过程（重新运行该升级脚本）？")==false){
			$id("runRndCode").focus();return false;
		}
	}else if ($id("runRndCode").value!=$id("trueRndCode").value){
		if (confirm("您校验码输入不正确，确定重新运行升级过程（重新运行该升级脚本）？")==false){
			$id("runRndCode").focus();return false;
		}
	}
	RunUpdateStep4();
}

// 下载文件重获取按钮倒计时显示
function CalcGetSecond(){
	setTimeout("$id('reGetBtn').style.display='';",30000);
}

// 检测升级内容框 显示、关闭
function CheckUpdateBox(){
	if (parent.$id('updateBox')){
		parent.CheckUpdateBox();
	}else{
		parent.window.close();
	}
}
