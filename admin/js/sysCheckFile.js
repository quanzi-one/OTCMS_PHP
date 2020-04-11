$(function (){
	WindowHeight(0);setTimeout("WindowHeight(0)",500);setTimeout("WindowHeight(0)",3000);
});



var startNum,errFileNum,okFileNum,existFileNum;
function CheckFile(){
	startNum=1;
	errFileNum=0;
	okFileNum=0;
	existFileNum=0;
	$id("step2Btn").disabled="true";
	RunCheckFile();
}


function RunCheckFile(){
	if ($id('runStopState').value=="1"){
		$id('runStopAlert').innerHTML = "（已暂停）";return false;
	}

	fileTotalInt = parseInt($id('fileTotal').value);
	fileStepInt = parseInt($id('fileStep').value);
	if (startNum>fileTotalInt){
		for (var i=1; i<=fileTotalInt; i++){
			if ($id("state"+ i).value=="0"){ $id("data"+ i).style.display='none'; }
		}
		WindowHeight(0);
		return false;
	}

	$id('fileStart').value=startNum;
	
	for (var i=startNum; i<startNum+fileStepInt; i++){
		try {
			$id('result'+ i).innerHTML="<span style='color:red;'>正在检测中...</span>";
		}catch (e) { }
	}

	if ($id('isBugMode').checked){
		$id("listForm").target = '_blank';
		$id("listForm").submit();
	}

//	AjaxPostDeal("listForm");
	formNameObj = document.getElementById("listForm");
//	formNameObj.target="_blank";
//	formNameObj.submit();
	var formNameUrl = formNameObj.getAttribute("action");
	// var formNameContent = formValueToStr(formNameObj);
	var formNameContent = "fileStart="+ $id('fileStart').value +"&fileStep="+ $id('fileStep').value +"&fileTotal="+ $id('fileTotal').value +"&fileData="+ encodeURIComponent($id('fileData').value) +"";
	//alert(formNameContent);
	$.post(formNameUrl +"&rnd="+ Math.random(),formNameContent,function(result){
		eval(result.replace(/<\s*(script[^>]*)>([\s\S][^<]*)<\/\s*script>/gi,"$2"));

		startNum = startNum+fileStepInt;
		if (startNum>fileTotalInt){ finishNum=fileTotalInt; }else{ finishNum = startNum-1; }
		$id("resultAlertStr").innerHTML = "（已检测 "+ finishNum +"/"+ fileTotalInt +"，异常文件数：<b style='color:red;'>"+ errFileNum +"</b>，匹配文件数：<b style='color:green;'>"+ okFileNum +"</b>，存在文件数：<b style='color:#000;'>"+ existFileNum +"</b>）";
		setTimeout("RunCheckFile();",1000);
	});

}


// 复制异常文件列表
function CopyErrFile(){
	errFileListStr = "";
	try{
		fileTotalInt = parseInt($id('fileTotal').value);
		for (var i=1; i<=fileTotalInt; i++){
			if ($id("errFile"+ i).value=="1"){ errFileListStr += "\r\n"+ $id('filePath'+ i).innerHTML; }
		}
	}catch (e){}
	if (errFileListStr==""){
		alert("暂无异常文件.");return false;
	}
	if (window.clipboardData){
		window.clipboardData.setData("Text", errFileListStr);
		alert("复制成功.");
	}else if(navigator.userAgent.indexOf("Opera") != -1){
		window.location = errFileListStr;
	}else if(window.netscape){
		try {
			netscape.security.PrivilegeManager
					.enablePrivilege("UniversalXPConnect");
		}catch (e){
			alert("你使用的FireFox浏览器,复制功能被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车。\n然后将“signed.applets.codebase_principal_support”双击，设置为“true”");
			return;
		}
		var clip = Components.classes['@mozilla.org/widget/clipboard;1']
				.createInstance(Components.interfaces.nsIClipboard);
		if (!clip)
			return;
		var trans = Components.classes['@mozilla.org/widget/transferable;1']
				.createInstance(Components.interfaces.nsITransferable);
		if (!trans)
			return;
		trans.addDataFlavor('text/unicode');
		var str = new Object();
		var len = new Object();
		var str = Components.classes["@mozilla.org/supports-string;1"]
				.createInstance(Components.interfaces.nsISupportsString);
		str.data = errFileListStr;
		trans.setTransferData("text/unicode", str, errFileListStr.length * 2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans, null, clipid.kGlobalClipboard);
	}else{
		alert("你的浏览器不支持一键复制功能，请手动复制。");
		$id('errFileList').value = errFileListStr;
		$id('errFileList').style.display = "";
		return;
	}

}


// 暂停/回复
function RunStop(){
	stopVal = $id('runStopState').value;
	if (stopVal=="0"){
		$id('runStopState').value = "1";
		$id('runStopAlert').innerHTML = "（正在暂停中...）";
		$id('runStopBtn').value = "继续运行";
	}else{
		$id('runStopState').value = "0";
		$id('runStopAlert').innerHTML = "";
		$id('runStopBtn').value = "暂停";
		RunCheckFile();
	}
}



// 检查非程序目录
function CheckSoftDir(){
	$id("dirErrList").innerHTML = "正在检查中，请稍等...";
	AjaxGetDealToId("sysCheckFile_deal.php?mudi=checkSoftDir","dirErrList");
}



// upFiles/目录图片木马检查
function CheckUpFilesDir(){
	$id("upFilesErrImgList").innerHTML = "正在检查中，请稍等...";
	AjaxGetDealToId("sysCheckFile_deal.php?mudi=checkUpFilesDir","upFilesErrImgList");
}


// 异常文件检查
var rateCurr=0;rateNum=0;rateArr=new Array();
function CheckSoftDirFile(){
	rateArr.length = 0;
	rateNum = 0;
	rateCurr = 1;
	for (var i=0;i<$name("area[]").length;i++){
		$id($name("area[]")[i].value +"StateStr").innerHTML = "<img src='images/onload.gif' />";
		if ($name("area[]")[i].checked == true){
			rateNum ++;
			selName = $name("area[]")[i].value;
			rateArr[rateNum] = selName;
		}
	}
	if (rateNum == 0){
		alert("请先选择要检查的范围。");return false;
	}
	$id(rateArr[rateCurr] +"ErrList").innerHTML = ""+ rateArr[rateCurr] +"/ 检查中，请稍等....";
	AjaxRateToId("sysCheckFile_deal.php?mudi=checkSoftDirFile&dirName="+ rateArr[rateCurr] +"&rnd="+ Math.random(), rateArr[rateCurr] +"ErrList");
}

function RateDealNext(){
	rateCurr ++;
	if (rateCurr<=rateNum){
		$id(rateArr[rateCurr] +"StateStr").innerHTML = "<img src='images/onload.gif' />";
		$id(rateArr[rateCurr] +"ErrList").innerHTML = ""+ rateArr[rateCurr] +"/ 检查中，请稍等....";
		AjaxRateToId("sysCheckFile_deal.php?mudi=checkSoftDirFile&dirName="+ rateArr[rateCurr] +"&rnd="+ Math.random(), rateArr[rateCurr] +"ErrList");
	}
}


function AjaxRateToId(urlStr,outputID){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		document.getElementById(outputID).innerHTML = result;
		try{
			$id(rateArr[rateCurr] +"StateStr").innerHTML = "<img src='images/img_yes.gif' />";
			RateDealNext();
			WindowHeight(0);
		}catch (e){}
	});
	return false;
}



// 计算网站占用空间
function CalcSiteSize(){
	AjaxGetDeal("sysCheckFile_deal.php?mudi=calcSiteSize");
}


// 检查文件权限 查找按钮事件
function RefLimitList(){
	pathVal = $id('refPath').value;
	limitNumVal = $id('refLimitNum').value;
	fileCountVal = $id('fileCount').value;

	for (var i=1; i<=fileCountVal; i++){
		$id('state'+ i).value = 1;
		$id('data'+ i).style.display = '';
		$id('sel'+ i).checked = false;
	}

	if (pathVal.length > 0){
		for (var i=1; i<=fileCountVal; i++){
			if ($id('filePath'+ i).innerHTML.indexOf(pathVal) == -1){
				$id('state'+ i).value = 0;
				$id('data'+ i).style.display = 'none';
			}
		}
	}

	if (limitNumVal.length > 0){
		for (var i=1; i<=fileCountVal; i++){
			if ($id('limitinfo'+ i).value.indexOf(limitNumVal) == -1){
				$id('state'+ i).value = 0;
				$id('data'+ i).style.display = 'none';
			}
		}
	}
}


// 检查文件权限 批量修改权限值按钮事件
function CheckListForm(){
	var selNum = 0;
	var selListStr = '';
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
			if (selListStr.length > 0){
				selListStr += '[arr]'+ $id('pathinfo'+ $name("selDataID[]")[i].value).value
			}else{
				selListStr += $id('pathinfo'+ $name("selDataID[]")[i].value).value
			}
		}
	}

	if (selNum==0){
		alert('请先选择要修改权限值的记录.');return false;
	}

	if ($id('newLimitNum').value == ''){
		alert('请输入要修改权限值.');$id('newLimitNum').focus();return false;
	}
	if ($id('newLimitNum').value.length != 3){
		alert('要修改的权限值必须为三位数值.');$id('newLimitNum').focus();return false;
	}
	if (confirm("你确定这"+ selNum +"个文件要进行批量修改权限值？")==false){
		return false;
	}else{
		$id('limitFileList').value = selListStr;
	}
	
}


// 获取数据库结构
function CheckDbForm(str){
	var itemName;
	switch (str){
		case 'check':		itemName = '检查'; break;
		case 'optimize':	itemName = '优化'; break;
		case 'repair':		itemName = '修复'; break;
		case 'analyze':		itemName = '分析'; break;
	}

	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要 '+ itemName +' 的表记录.');return false;
	}
	if (confirm('你确定要 '+ itemName +' 这'+ selNum +'条？')==false){
		return false;
	}

	$id('mode').value = str;

	$id('listForm').submit();

	return true;
}


// SQL语句调试
function CheckSqlForm(){
	if ($id('sqlContent').value == ""){
		alert('请输入SQL语句');$id('sqlContent').focus();return false;
	}
	if ($id('userpwd').value.length < 5){
		alert('登录密码不能为空或长度低于5位');$id('userpwd').focus();return false;
	}

	EncPwdData('userpwd');

	return true;

}


// 清空升级库数据
function ClearUpdateDb(){
	AjaxGetDeal("update_deal.php?mudi=clearUpdateData");
}
