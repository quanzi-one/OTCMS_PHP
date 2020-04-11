if (typeof(editorMode)=="undefined"){
	editorMode='kindeditor4.x';	// 后台使用的编辑器模式：值有 kindeditor3.x 、 kindeditor4.x 、 ckeditor
}else{
	if (editorMode.length<5){ editorMode="kindeditor4.x"; }
}

// 插入编辑器控件
if (editorMode=="kindeditor3.x"){
	document.write("<script language='javascript' type='text/javascript' src='tools/kindeditor/kindeditor-min.js'></script>");
}else if (editorMode=="kindeditor4.x"){
	var ke4Editor = [];
	document.write("<script language='javascript' type='text/javascript' src='tools/kindeditor4/kindeditor-all-min.js'></script>");
}else if (editorMode=="ckeditor"){
	document.write("<script language='javascript' type='text/javascript' src='tools/ckeditor/ckeditor.js'></script>");
}else if (editorMode=="ckeditor4.x"){
	document.write("<script language='javascript' type='text/javascript' src='tools/ckeditor4/ckeditor.js?v=1.01' charset='utf-8'></script>");
}else if (editorMode=="fckeditor"){
	document.write("<script language='javascript' type='text/javascript' src='tools/fckeditor/fckeditor.js'></script>");
}else if (editorMode=="ueditor"){
	var ue;
	document.write(""+
	"<script language='javascript' type='text/javascript' src='tools/ueditor/ueditor.config.js' charset='utf-8'></script>"+
	"<script language='javascript' type='text/javascript' src='tools/ueditor/ueditor.all.min.js' charset='utf-8'></script>"+
	"");
}else{
	var ke4Editor = [];
	document.write("<script language='javascript' type='text/javascript' src='tools/kindeditor4/kindeditor-all-min.js'></script>");
}
var startupModeStr="";


$(function (){
	if ( $(".tabMenu").attr("rel") != 'no'){
		$(".tabMenu li").click(function() {
			$(".tabMenu li").each(function(){
				$(this).removeClass("selected");
				$("#"+ $(this).attr("rel")).css("display","none");
			});

			$(this).addClass("selected");
			$("#"+ $(this).attr("rel")).css("display","");

			WindowHeight(0);setTimeout("WindowHeight(0)",500);
		});
	}
});


// 获取元素id
function $id(str){
	return document.getElementById(str);
}

// 获取元素name
function $name(str){
	return document.getElementsByName(str);
}


// 文字水印参数，默认关闭 isWatermark=font&watermarkPos=centerMiddle&watermarkPadding=6&watermarkFontContent=网钛科技&watermarkFontSize=14&watermarkFontColor=black
// 图片水印参数，默认关闭 isWatermark=img&watermarkPath=upFile/water.gif&watermarkPos=centerMiddle&watermarkPadding=6
// 其他参数 otherPara
// 区域模式 areaMode=
// 上传模式 upMode：more批量上传，其他单文件上传
// 打开上传载入图片窗口
function OT_OpenUpImg(fileMode,fileFormName,fileDir,otherPara){
	var winWidth = 650;
	var winHeight = 380;
	var arr = window.open('info_upImg.php?mudi=selectFile&fileMode='+ fileMode +'&fileFormName='+ fileFormName +'&upPath='+ fileDir +'&upFileType=images'+ otherPara,'','top=150, left='+ ((window.screen.width-winWidth)/2) +', width='+ winWidth +'px, height='+ winHeight +'px,menubar=no,scrollbars=yes,status=no,resizable=yes');
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// 打开上传文件窗口
function OT_OpenUpFile(fileMode,fileFormName,fileDir,otherPara){
	var winWidth = 650;
	var winHeight = 380;
	var arr = window.open('info_upFile.php?mudi=selectFile&fileMode='+ fileMode +'&fileFormName='+ fileFormName +'&upPath='+ fileDir + otherPara,'','top=150,left='+ ((window.screen.width-winWidth)/2) +',width='+ winWidth +',height='+ winHeight +',menubar=no,scrollbars=yes,status=no,resizable=yes');
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// 打开上传大文件窗口
function OT_OpenUpBigFile(fileMode,fileFormName,fileDir,upMode,otherPara){
	var winWidth = 650;
	var winHeight = 380;
	var arr = window.open('info_upBigFile.php?mudi=selectFile&fileMode='+ fileMode +'&fileFormName='+ fileFormName +'&upPath='+ fileDir +'&upMode='+ upMode + otherPara,'','top=150,left='+ ((window.screen.width-winWidth)/2) +',width='+ winWidth +',height='+ winHeight +',menubar=no,scrollbars=yes,status=no,resizable=yes');
//	if (arr != null){
//		OT_InsertImg(arr);
//	}
}

// 打开在线拍照框
function OT_OpenUpFace(fileMode,fileFormName,fileDir,otherPara){
	var winWidth = 720;
	var winHeight = 340;
//	var arr = showModalDialog('info_upFace.php?mudi=selectFile&fileMode='+ fileMode +'&fileFormName='+ fileFormName +'&upPath='+ fileDir + otherPara,'','dialogWidth:750px; dialogHeight:400px; status:0; help:0;');
	var arr = window.open('info_upFace.php?mudi=selectFile&openMode=face&fileMode='+ fileMode +'&fileFormName='+ fileFormName +'&upPath='+ fileDir + otherPara,'','top=150,left='+ ((window.screen.width-winWidth)/2) +',width='+ winWidth +'px,height='+ winHeight +'px,menubar=no,scrollbars=yes,status=no,resizable=yes');
	
}

// 打开颜色框
function OT_OpenColor(retMode,inputName,input2Name){
	var arr = MiniWinOpen('info_setColor.htm?retMode='+ retMode +'&inputName='+ inputName +'&input2Name='+ input2Name,'',350,300);	// showModalDialog dialogWidth:350px; dialogHeight:300px; status:0; help:0
	if (arr != null){
		if (retMode=="input"){
			document.getElementById(inputName).value = arr;
			document.getElementById(input2Name).style.color = arr;
		}else if (retMode=="function"){
			DealColorBox(arr);
		}
	}
	
}

// 开启插件平台升级系统
function OT_OpenAppShopUpdate(appId, otherStr){
	var winWidth = 700;
	var winHeight = 420;
	var a=window.open('appShop.php?mudi=update&nohrefStr=close&appID='+ appId + otherStr, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, left='+ ((window.screen.width-winWidth)/2) +', top=120');
//	var arr = showModalDialog('appShop.php?mudi=update&nohrefStr=close&appID='+ appId + otherStr,'','dialogWidth:650px; dialogHeight:380px; status:0; help:0');
//	if (arr != null){

//	}
}

// 开启用户积分管理
function OT_OpenUserScore(otherStr){
	var winWidth = 800;
	var winHeight = 500;
	var a=window.open('userMoney.php?mudi=miniManage&nohrefStr=close&1=1'+ otherStr, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, scrollbars=yes, status=no, resizable=yes, left='+ ((window.screen.width-winWidth)/2) +', top=120');
}

// 开启用户财务管理
function OT_OpenUserMoney(otherStr){
	var winWidth = 800;
	var winHeight = 500;
	var a=window.open('moneyRecord.php?mudi=moneyManage&nohrefStr=close&1=1'+ otherStr, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, scrollbars=yes, status=no, resizable=yes, left='+ ((window.screen.width-winWidth)/2) +', top=120');
}

// 开启财务信息
function OT_OpenMoneyRecord(otherStr){
	var winWidth = 800;
	var winHeight = 500;
	var a=window.open('moneyRecord.php?mudi=miniRefer&nohrefStr=close&1=1'+ otherStr, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, scrollbars=yes, status=no, resizable=yes, left='+ ((window.screen.width-winWidth)/2) +', top=120');
}

// 开启单条财务信息
function OT_OpenMoneyRecordDet(dataID){
	var winWidth = 500;
	var winHeight = 400;
	var a=window.open('moneyRecord.php?mudi=show&nohrefStr=close&dataID='+ dataID, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, scrollbars=yes, status=no, resizable=yes, left='+ ((window.screen.width-winWidth)/2) +', top=120');
}

// 开启推广提现记录
function OT_OpenGainMoney(otherStr){
	var winWidth = 800;
	var winHeight = 500;
	var a=window.open('gainMoney.php?mudi=miniRefer&nohrefStr=close&1=1'+ otherStr, '', 'width='+ winWidth +'px, height='+ winHeight +'px, location=0, menubar=0, toolbar=0, scrollbars=yes, status=no, resizable=yes, left='+ ((window.screen.width-winWidth)/2) +', top=120');
}

function OpenShowAlert(str,color){
	try{
		parent.ShowAlert(str,color);
	}catch (e){
		ShowAlert(str,color);
	}
}

// 弹出框google Chrome执行的是open
function MiniWinOpen(url, args, width, height) {
	var retStr = null;
	if (navigator.userAgent.indexOf("Chrome") > 0) {
		var params = 'height=' + height + ', width=' + width + ', top=' + (((window.screen.height - height) / 2) - 50) +
		',left=' + ((window.screen.width - width) / 2) + ',toolbar=no, menubar=no, scrollbars=no, resizable=no, location=no, status=no';
		if (url.indexOf('?') == -1){ url += '?isRet=1' }else{ url += '&isRet=1' }
		window.open(url, "newwindow", params);
	}else{
		var params = 'dialogWidth:' + width + 'px;dialogHeight:' + height + 'px;status:no;dialogLeft:'
		+ ((window.screen.width - width) / 2) + 'px;dialogTop:' + (((window.screen.height - height) / 2) - 50) + 'px;';
		if (url.indexOf('?') == -1){ url += '?isRet=0' }else{ url += '&isRet=0' }
		retStr = window.showModalDialog(url, args, params);
	}
	return retStr;
}


// 点击弹出浮层
var djt;
function ShowMengceng(str, sec){
	if (sec > 0){
		var djSec = 0;
		// djt = window.setInterval("djSecFunc()",1000); 
		djt = window.setInterval(function(){
				djSec += 1;
				$("#floatSec").html('&ensp;'+ djSec +'s');
				if (djSec > sec){
					window.clearInterval(djt);
					HiddenMengceng();
				}
			},1000); 
	}
	//清除之前的样式
	$("#fullScreen,#floatLayer").remove();
	$("body").append(
		//占据整个屏幕Div
		"<div id='fullScreen'></div>"+
		//浮层区
		"<div id='floatLayer'>"+ str +"<span id='floatSec'></span></div>"
	);
}

// 隐藏浮层
function HiddenMengceng(){
	window.clearInterval(djt);
	$("#fullScreen,#floatLayer").remove();
}


// 显示浮层警示框  ShowAlert('操作成功！','')
function ShowAlert(str,color){
	if (color=='red'){
		$id('divAlert').style.backgroundColor="#db0340";
	}

	$id('divAlert').innerHTML=str;
	$id('divAlert').style.display="";
	setTimeout("CloseAlert()",2000);
}

// 关闭浮层警示框
function CloseAlert(){
	$id('divAlert').innerHTML='';
	$id('divAlert').style.display="none";
}


// 及时检测用户名的合法性
function CheckUserName(id,val){
	$id(id +"Str").innerHTML="检测中...";
	$.ajaxSetup({cache:false});
	$.get("../users_deal.php?mudi=checkUserName&method=write&userName="+ encodeURIComponent(val), function(result){
		$id(id +"Str").innerHTML=result;
		if (result.lastIndexOf('green')>=0){
			$id(id +"IsOk").innerHTML="<img src='images/img_yes.gif' />";
		}else{
			$id(id +"IsOk").innerHTML="<img src='images/img_err.gif' />";
		}
	});

}


// 获取关键词(标签)
function GetKeyWord(type){
	themeStr = $id('theme').value;
	contentStr = FiltHtmlTag(GetEditorText('content'));

	if (themeStr==""){
		alert("获取关键词(标签)，标题不能为空.");return false;
	}

	$id('onloadThemeKey').innerHTML = "<img src='../inc_img/onload.gif' style='margin-right:5px;' />请稍等，关键词获取中...</center>";

	$.ajaxSetup({cache:false});
	$.post("read.php?mudi=getKeyWord&type="+ type,"theme="+ encodeURIComponent(themeStr) +"&content="+ encodeURIComponent(contentStr), function(result){
		$id('onloadThemeKey').innerHTML = "";
		if (result=="0"){ alert("获取不到关键词(标签)，请手动添加。");return false; }
		$id('themeKey').value = result;
	});
	return false;

}


// 保存sysParam参数信息
function RevSysParam(idName, idVal){
	AjaxGetDeal('readDeal2.php?mudi=updateSysParam&idName='+ idName +'&idVal='+ encodeURIComponent(idVal));
}


// 检测用户名并反馈信息
function CheckUserInfo(username,other){
	if (username.length < 1){
		$id("usernameRes").innerHTML="";
	}else{
		$id("usernameRes").innerHTML="检测中...";
		$.ajaxSetup({cache:false});
		$.get("readDeal2.php?mudi=checkUserInfo&method=write&userName="+ encodeURIComponent(username) +"&outId=usernameRes"+ other, function(result){
			$id("usernameRes").innerHTML=result;
		});
	}
}

// 开启用户查询框 outId：结果反馈到ID栏；outField：结果下拉value值；outMode：模块名称
function OpenSelUserBox(outId, outField, outMode){
	if ($id('selUserBox').style.display == ''){
		$id('selUserBox').style.display = 'none';
	}else{
		$id('selUserBox').style.display = '';
		if ($id('selUserBox').innerHTML.length < 50){
			AjaxGetDealToId('read.php?mudi=selUserBox&outId='+ outId +'&outField='+ outField +'&outMode='+ outMode, 'selUserBox');
		}
	}
}

// 检查用户查询表单
function CheckSelUserForm(){
	if ($id('selUsername').value=="" && $id('selRealname').value=="" && $id('selQq').value=="" && $id('selWw').value==""){ alert("用户名、昵称、QQ、旺旺 三者至少填一个。");$id('selUsername').focus();return false; }
	AjaxGetDeal('readDeal2.php?mudi=loadUsersInfo&username='+ $id('selUsername').value +'&realname='+ $id('selRealname').value +'&qq='+ $id('selQq').value +'&ww='+ $id('selWw').value +'&outId='+ $id('outId').value +'&outField='+ $id('outField').value +'&outMode='+ $id('outMode').value);
}

// 用户查询结果表单
function SetSelUserRes(){
	$id($id('outId').value).value = $id('selUserRes').value;
	var text = SelectGetText('selUserRes');
	$id('selUserResText').value = text;
	if ($id('outMode').value == 'vpsProUsers'){
		$id('usernameRes').innerHTML = '<input type="hidden" id="isUserExist" name="isUserExist" value="1" /><span style="color:green;">'+ GetSignStr(text,'（','）',true,true) +'</span>';
	}
}

// 开启用户临时创建框
function OpenCreateUserBox(outId, outField, outMode){
	if ($id('createUserBox').style.display == ''){
		$id('createUserBox').style.display = 'none';
	}else{
		$id('createUserBox').style.display = '';
		if ($id('createUserBox').innerHTML.length < 50 || $id('createUserBox').innerHTML.indexOf('<input')==-1){
			AjaxGetDealToId('read.php?mudi=createUserBox&outId='+ outId +'&outField='+ outField +'&outMode='+ outMode, 'createUserBox');
		}
	}
}

// 检查用户临时创建表单
function CheckCreateUserForm(){
	if ($id('newUsername').value==""){ alert("用户名不能为空。");$id('newUsername').focus();return false; }
	if ($id('newRealname').value=="" && $id('newMail').value=="" && $id('newQq').value=="" && $id('newWw').value=="" && $id('newPhone').value==""){
		alert("昵称、邮箱、QQ、旺旺、手机号 五项至少填一个。");$id('newRealname').focus();return false;
	}
	AjaxGetDeal('readDeal2.php?mudi=createUsersInfo&username='+ $id('newUsername').value +'&realname='+ $id('newRealname').value +'&mail='+ $id('newMail').value +'&qq='+ $id('newQq').value +'&ww='+ $id('newWw').value +'&phone='+ $id('newPhone').value +'&outId='+ $id('outId').value +'&outField='+ $id('outField').value +'&outMode='+ $id('outMode').value);
}

// 用户临时创建表单 使用QQ邮箱
function CreateUserQQmail(){
	if ($id('newQq').value == ""){alert("请先输入QQ，再点击该按钮");$id('newQq').focus();return false}
	$id('newMail').value = $id('newQq').value +"@qq.com";
}

// 用户临时创建结果表单
function SetCreateUserRes(){
	$id($id('outId').value).value = $id('createUserRes').value;
	var text = SelectGetText('createUserRes');
	$id('createUserResText').value = text;
	if ($id('outMode').value == 'vpsProUsers'){
		$id('usernameRes').innerHTML = '<input type="hidden" id="isUserExist" name="isUserExist" value="1" /><span style="color:green;">'+ GetSignStr(text,'（','）',true,true) +'</span>';
	}
}

// 检查是否选中 显示/隐藏区
function CheckedShowHidden(chkId,idStr){
	if ($id(chkId).checked){
		$id(idStr).style.display = '';
	}else{
		$id(idStr).style.display = 'none';
	}
	try {
		WindowHeight(0);
	}catch (e) {}
}

// 点击开启隐藏区，再点击隐藏
function ClickShowHidden(idStr){
	if ($id(idStr).style.display == ''){
		$id(idStr).style.display = 'none';
	}else{
		$id(idStr).style.display = '';
	}
	try {
		WindowHeight(0);
	}catch (e) {}
}

// 点击开启明码/暗码
function PwdTextBtn(textId,btnId){
	if ($id(textId).type == 'password'){
		$id(textId).type = 'text';
		$id(textId +'Btn').value = '暗码';
	}else{
		$id(textId).type = 'password';
		$id(textId +'Btn').value = '明码';
	}
}

// 检测远程链接是否正常
function CheckCollUrl(str){
	AjaxGetDealToAlert("read.php?mudi=checkCollUrl&urlMode="+ str)
}


// 更新缓存文件
function UpdateWebCache(){
	AjaxGetDeal("readDeal.php?mudi=updateWebCache");
}

// 清空页面缓存
function ClearWebCache(modeStr){
	AjaxGetDeal("readDeal.php?mudi=clearWebCache&mode="+ modeStr);
}


// 更新缓存文件、清空页面缓存、更新皮肤样式
function UpdateAllCache(){
	$.ajaxSetup({cache:false});
	$.get("readDeal.php?mudi=updateWebCache", function(result){
		alertStr1 = result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,"").replace("alert(\"","").replace("\");","");
		$.get("readDeal.php?mudi=clearWebCache&mode=", function(result){
			alertStr2 = result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,"").replace("alert(\"","").replace("\");","");
			alert("1、"+ alertStr1.replace(/[\r\n\t]/g,"") +"\r\n2、"+ alertStr2.replace(/[\r\n\t]/g,""));
		});
	});

}


// if(isNaN(value))execCommand('undo');

// 过滤特殊符号
// 应用例子 onkeyup="if (this.value!=FiltVarStr(this.value)){this.value=FiltVarStr(this.value)}"
// 应用例子 onkeyup="this.value=FiltVarStr(this.value)"
function FiltVarStr(str){
	return str.replace(/[^\w\u4E00-\u9FA5]/g,'')
}

// 过滤特殊符号(不过滤空格)
// 应用例子 onkeyup="if (this.value!=FiltVarStr(this.value)){this.value=FiltVarStr(this.value)}"
// 应用例子 onkeyup="this.value=FiltVarStr(this.value)"
function FiltVarStr2(str){
	return str.replace(/[^\w \u4E00-\u9FA5]/g,'')
}

// 过滤小数
// 应用例子 onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}"
// 应用例子 onkeyup="this.value=FiltDecimal(this.value)"
function FiltDecimal(str){
	return str.replace(/[^\d*\.?\d{0,2}$]/g,'')
}

// 过滤整数
// 应用例子 onkeyup="if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}"
// 应用例子 onkeyup="this.value=FiltInt(this.value)"
function FiltInt(str){
	return str.replace(/\D/g,'')
}

// 过滤非数字、字母
// 应用例子 onkeyup="if (this.value!=FiltABCNum(this.value)){this.value=FiltABCNum(this.value)}"
// 应用例子 onkeyup="this.value=FiltABCNum(this.value)"
function FiltABCNum(str){
	return str.replace(/[^A-Za-z0-9]/ig,'')
}

// 过滤非数字、字母、下划线
// 应用例子 onkeyup="if (this.value!=FiltAbcNum_(this.value)){this.value=FiltAbcNum_(this.value)}"
// 应用例子 onkeyup="this.value=FiltAbcNum_(this.value)"
function FiltAbcNum_(str){
	return str.replace(/[^A-Za-z0-9_]/ig,'')
}

// 过滤非数字、字母、下划线和点符号
// 应用例子 onkeyup="if (this.value!=FiltAbcNum_d(this.value)){this.value=FiltAbcNum_d(this.value)}"
// 应用例子 onkeyup="this.value=FiltAbcNum_d(this.value)"
function FiltAbcNum_d(str){
	return str.replace(/[^A-Za-z0-9_\.]/ig,'')
}

// 过滤数字
// 应用例子 onkeyup="if (this.value!=FiltNumber(this.value)){this.value=FiltNumber(this.value)}"
// 应用例子 onkeyup="this.value=FiltNumber(this.value)"
function FiltNumber(str) {
	var newStr="";
	if(str.substring(0,1)=="-"){ newStr="-"; }
	var newStr2=(str.replace(/[^0-9.]/g,'')).replace(/[.][0-9]*[.]/, '.');

	if (newStr2.substring(0,1)=="."){ newStr2="0"+ newStr2; }
	newStr = newStr + newStr2
	return newStr;
	/*
	if (/^(\+|-)?\d+($|\.\d+$)/.test( str )) {
	}else {
		execCommand('undo');
	}
	*/
}

// 过滤掉HTML标签和空格、换行、制表符
function FiltHtmlTag(str) {
	str = str.replace(/<\/?[^>]*>/g,'');			// 去除HTML tag
	str = str.replace(/(\t|\r|\n| |\&nbsp;|\&ensp;)/g,'');	// 去除空格、换行、制表符
	return str;
}

// 把Option的text值覆盖toID文本框
// 应用例子 onchange="OptionTextTo('labItemID','labItemName');"
function OptionTextTo(sourceID,toID){
	document.getElementById(toID).value=document.getElementById(sourceID).options[document.getElementById(sourceID).selectedIndex].text;
}


// 判断是否含特殊符号
function Str_IsSign(str){
	var txt=new RegExp("[ ,\\`,\\~,\\!,\\@,\#,\\$,\\%,\\^,\\+,\\*,\\&,\\\\,\\/,\\?,\\|,\\:,\\.,\\<,\\>,\\{,\\},\\(,\\),\\',\\;,\\=,\"]");
	//特殊字符正则表达式
	if (txt.test(str)){
		return true;
	}else{
		return false;
	}
}


// 计算字符串的字节数
function Str_Byte(str){
	var newStr = 0;
//	newStr=str.replace(/[^\u7F51\u949B\u5DE5\u4F5C\u5BA4]/g, '***');
	newStr=str.replace(/[^\u0000-\u00ff]/g, '***');
	return newStr.length;
}

// 检测邮箱的合法性。不合法，返回-1
function IsMail(str){
	if (str.search(/^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/)!=-1){
		return true;
	}else{
		return false;
	}
}

// 检测手机号的合法性。
function IsPhone(str){
	if (str.search(/^1\d{10}$/)!=-1){
		return true;
	}else{
		return false;
	}
}

// 检测文件框是否为图片文件
function IsImgFile(fileValue){
	var re = new RegExp("\.(gif|jpg|jpeg|png|bmp)","ig");
	return re.test(fileValue);
}

// 检测是否为http、https协议网址
function IsHttpUrl(urlStr){
	if (urlStr.substr(0,7).toLowerCase()=="http://" || urlStr.substr(0,8).toLowerCase()=="https://"){
		return true;
	}else{
		return false;
	}
}

function IsAbsUrl(urlStr){
	if (urlStr.substr(0,7).toLowerCase()=="http://" || urlStr.substr(0,8).toLowerCase()=="https://" || urlStr.substr(0,1)=="/"){
		return true;
	}else{
		return false;
	}
}


// 生成随机数
// num：生成个数
function RndNum(num) {
   var a = new Array("1", "2", "3", "4", "5", "6", "7", "8", "9", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "Z", "K", "L", "M", "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
   var b = "", c;
   for(i=1; i<=num; i++){
      c = Math.floor(Math.random() * a.length);
      b = b + a[c];
//      a = a.del(c);
   }
   return b;
}

function RndNumSort(num) {
   var a = new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
   var b = "", c;
   for(i=1; i<=num; i++){
      c = Math.floor(Math.random() * a.length);
      b = b + a[c];
//      a = a.del(c);
   }
   return b;
}


function ToGetStr(name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = window.location.search.substr(1).match(reg);
	if(r!=null)return unescape(r[2]); return '';
}

function ToGetPara(str,name){
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
	var r = (str +'').match(reg);
	if(r!=null)return unescape(r[2]); return '';
}

function ToInt(str){
	var newInt = parseInt(str);
    if(isNaN(newInt)) { newInt = 0; }
	return newInt;
}

function ToFloat(str){
	var newFloat = parseFloat(str);
    if(isNaN(newFloat)) { newFloat = 0; }
	return newFloat;
}

function IsRadioChecked(idName){
	var checkedJud=false;
	for (var i=0; i<$name(idName).length; i++){
		if ($name(idName)[i].checked){ checkedJud=true;break; }
	}
	return checkedJud;
}

function JsToHtml(contentStr){
	//contentStr = contentStr.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"");
	contentStr = contentStr.replace("<script language='javascript' type='text/javascript'>",'');
	contentStr = contentStr.replace('<script language="javascript" type="text/javascript">','');
	contentStr = contentStr.replace('<'+'/scr'+'ipt>','');
	contentStr = contentStr.replace(/document\.writeln\(\"/gi,'').replace(/\"\);/gi,'');
	contentStr = contentStr.replace(/\\\"/gi,"\"");
	contentStr = contentStr.replace(/\\\'/gi,"'");
	contentStr = contentStr.replace(/\\\//gi,"/");
	contentStr = contentStr.replace(/\\\\/gi,"\\");
	return contentStr;
}

function ToPinYinId(fromId,toId,mode){
	if ($id(fromId).value == ''){
		alert('中文内容不能为空.');$id(fromId).focus();return false;
	}
	//var a=window.open('../read.php?mudi=pinyin&str='+ $id(fromId).value +'&mode='+ mode);
	return AjaxGetDealToInput('../read.php?mudi=pinyin&str='+ $id(fromId).value +'&mode='+ mode, toId, 'base64');
}

// 使用AJAX异步无刷新
function UseAjax(urlStr){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		eval(result);
	});
}


// 复制内容
function StrToCopy(copy){
	if (window.clipboardData){
		window.clipboardData.setData("Text", copy);
	}else if(navigator.userAgent.indexOf("Opera") != -1){
		window.location = copy;
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
		str.data = copy;
		trans.setTransferData("text/unicode", str, copy.length * 2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans, null, clipid.kGlobalClipboard);
	}else{
		alert("你的浏览器不支持一键复制功能");
		return;
	}
	OpenShowAlert('复制成功！','');
	return false;
}


// 复制内容(获取ID所在的value)
function ValueToCopy(id){
	copy = $id(id).value
	if (window.clipboardData){
		window.clipboardData.setData("Text", copy);
	}else if(navigator.userAgent.indexOf("Opera") != -1){
		window.location = copy;
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
		str.data = copy;
		trans.setTransferData("text/unicode", str, copy.length * 2);
		var clipid = Components.interfaces.nsIClipboard;
		if (!clip)
			return false;
		clip.setData(trans, null, clipid.kGlobalClipboard);
	}else{
		//alert("你的浏览器不支持一键复制功能，请手动复制。");
		OpenShowAlert('复制失败，请手动复制','red');
		try{
			$id(id).style.display = '';
			$id(id).focus();
			$id(id).select();
		}catch (e){}
		try{ WindowHeight(0); }catch (e){}
		return;
	}
	OpenShowAlert('复制成功！','');
	return false;
}



// 数组变量获取下拉框全部选项
function SelectOptionArr(selectName){
	var SelectOptionArray = new Array();

	for (soi=0; soi<document.getElementById(selectName).options.length; soi++){
		SelectOptionArray[document.getElementById(selectName).options[soi].value] = document.getElementById(selectName).options[soi].text;
	}
	return SelectOptionArray;
}


// 确定提示框并跳转
function ConfirmHref(str, urlStr, waitStr){
	if (confirm(str)==false){
		return false;
	}else{
		if (waitStr.lenght > 0){
			ShowMengceng(waitStr,300);
		}

		document.location.href = urlStr;
	}
}


// 下拉框内容检索
function SetOptionData(selectName,arrObj){
	document.getElementById(selectName).options.length=0;
	for (var key in arrObj){
		document.getElementById(selectName).options.add(new Option(arrObj[key],key));
	}
}

// 下拉框内容检索
function SelectOptionSearch(sourceID,selectName,arrObj){
	document.getElementById(selectName).options.length=0;
	for (var key in arrObj){
		if (arrObj[key].lastIndexOf(document.getElementById(sourceID).value)>=0){
			document.getElementById(selectName).options.add(new Option(arrObj[key],key));
		}
	}
}

// 清理下拉框内容
function SelectOptionClear(selectName,defText){
	document.getElementById(selectName).options.length=0; 
	document.getElementById(selectName).options.add(new Option(defText,""));
	document.getElementById(selectName).value = "";
}

// 增加下拉框选项
function SelectOptionAdd(selectName,defText,defValue){
	document.getElementById(selectName).options.add(new Option(defText, defValue));
}

// 获取下拉框的文本
function SelectGetText(selectName){
	return document.getElementById(selectName).options[document.getElementById(selectName).options.selectedIndex].text;
}

// 获取下拉框的值
function SelectGetVaule(selectName){
	return document.getElementById(selectName).options[document.getElementById(selectName).options.selectedIndex].value;
}

// 获取编辑器中HTML内容
function GetEditorHTML(EditorName) {
	switch (editorMode){
		case 'kindeditor3.x':
			return KE.html(EditorName);
			break;

		case 'kindeditor4.x':
			return ke4Editor[EditorName].html();
			break;

		case 'ckeditor': case 'ckeditor4.x':
			var ckObj = CKEDITOR.instances[EditorName];
			return ckObj.document.getBody().getHtml();
			break;

		case 'fckeditor':
			var fckObj = FCKeditorAPI.GetInstance(EditorName);
			return fckObj.GetXHTML(true);
			break;

		case 'ueditor':
			var ueObj = UE.getEditor(EditorName);
			return ueObj.getContent();
			break;
	}
}

// 获取编辑器中文字内容
function GetEditorText(EditorName) {
	switch (editorMode){
		case 'kindeditor3.x':
			return KE.text(EditorName);
			break;
	
		case 'kindeditor4.x':
			return ke4Editor[EditorName].text();
			break;

		case 'ckeditor': case 'ckeditor4.x':
			var ckObj = CKEDITOR.instances[EditorName];
			return ckObj.document.getBody().getText();
			break;
	
		case 'fckeditor':
			var fckObj = FCKeditorAPI.GetInstance(EditorName);
			return fckObj.EditorDocument.body.innerText;
			break;

		case 'ueditor':
			var ueObj = UE.getEditor(EditorName);
			return ueObj.getContentTxt();
			break;
	}
}

// 设置编辑器中内容
function SetEditorHtml(EditorName, ContentStr) {
	switch (editorMode){
		case 'kindeditor3.x':
			KE.html(EditorName,ContentStr);
			break;
	
		case 'kindeditor4.x':
			ke4Editor[EditorName].html(ContentStr);
			break;

		case 'ckeditor': case 'ckeditor4.x':
			var ckObj = CKEDITOR.instances[EditorName];
			ckObj.setData(ContentStr);
			break;
	
		case 'fckeditor':
			var fckObj = FCKeditorAPI.GetInstance(EditorName) ;
			fckObj.SetHTML(ContentStr) ;
			break;

		case 'ueditor':
			var ueObj = UE.getEditor(EditorName);
			ueObj.setContent(ContentStr);
			break;
	}
}

// 插入字符串到编辑器中
function InsertStrToEditor(EditorName, ContentStr) {
	switch (editorMode){
		case 'kindeditor3.x':
			KE.insertHtml(EditorName, ContentStr);
			break;
	
		case 'kindeditor4.x':
			ke4Editor[EditorName].insertHtml(ContentStr);
			break;

		case 'ckeditor': case 'ckeditor4.x':
			var ckObj = CKEDITOR.instances[EditorName];
			ckObj.insertHtml(ContentStr);
			break;
	
		case 'fckeditor':
			var fckObj = FCKeditorAPI.GetInstance(EditorName) ;
			fckObj.InsertHtml(ContentStr);
			break;

		case 'ueditor':
			var ueObj = UE.getEditor(EditorName);
			ueObj.execCommand('insertHtml',ContentStr);
//			return ue.insertHtml(ContentStr);
			break;
	}
}


// 加载城市数据
function LoadCityData(idName,prov){
	AjaxGetDeal('../read.php?mudi=getCityData&idName='+ idName +'&prov='+ prov);
}


// 加载编辑器
function LoadEditor(inputId,editorW,editorH,modeStr) {
	if (editorW==0){ editorW=800; }
	if (editorH==0){ editorH=500; }
	switch (editorMode){
		case 'kindeditor3.x':
			if (modeStr.indexOf('|source|')!=-1){ wyswygModeVal=false; }else{ wyswygModeVal=true; }
			if (modeStr.indexOf('|miniMenu|')!=-1){
				itemsVal=[
					'source', '|','cut', 'copy', 'paste', '|', 'justifyleft', 'justifycenter', 'justifyright',
					'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold',
					'italic', 'underline', 'strikethrough', 'removeformat', '|', 'link', 'unlink', 'image'
				];
			}else{
				itemsVal=[
					'source', '|', 'fullscreen', 'undo', 'redo', 'selectall', '|', 'cut', 'copy', 'paste',
					'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
					'superscript', '-',
					'title', 'fontname', 'fontsize', '|', 'textcolor', 'bgcolor', 'bold',
					'italic', 'underline', 'strikethrough', 'removeformat', '|', 'image',
					'flash', 'media', 'advtable', 'hr', 'link', 'unlink', '|', 'about'
				];
			}
			KE.init({
				id : inputId,
				allowUpload : false,
				minWidth : editorW,
				minHeight : editorH,
				newlineTag : 'p',
				items : itemsVal,
		//		resizeMode : 1,	// 2 或1 或0，2 时可以拖动改变宽度和高度，1 时只能改变高度，0 时不能拖动。默认值：2
				wyswygMode : wyswygModeVal,
				cssPath : ['tools/kindeditor.css'],
				afterCreate : function(id) {
					KE.util.focus(id);
				}
			});
		//	KE.create(inputId);
			setTimeout("KE.create('"+ inputId +"');",600);
			break;

		case 'kindeditor4.x':
			if (modeStr.indexOf('|source|')!=-1){ designModeVal=false; }else{ designModeVal=true; }
			if (modeStr.indexOf('|miniMenu|')!=-1){
				itemsVal=[
					'source', '|','cut', 'copy', 'paste', '|', 'justifyleft', 'justifycenter', 'justifyright',
					'fontname', 'fontsize', 'forecolor', '|', 'bold',
					'italic', 'underline', 'strikethrough', 'removeformat', '|', 'link', 'unlink', 'image'
				];
			}else{
				itemsVal=[
					'source', '|', 'undo', 'redo', '|', 'code', 'selectall','cut', 'copy', 'paste',
					'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
					'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
					'superscript', 'clearhtml', 'quickformat' ,  '|', 'fullscreen', '/',
					'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
					'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
					'flash', 'media', 'insertfile', 'table', 'hr', 'baidumap', 'anchor', 'link', 'unlink', '|', 'about'
				];
			}
			ke4Editor[inputId] = KindEditor.create("#"+ inputId,{
				allowImageUpload : false,
				allowFlashUpload : false,
				allowMediaUpload : false,
				allowFileUpload : false,
				allowFileManager : false,
				minWidth : editorW,
				minHeight : editorH,
				newlineTag : 'p',
				items : itemsVal,
				designMode : designModeVal,
/*
				colorTable : [	
					["#000000","#003300","#006600","#009900","#00cc00","#00ff00","#330000","#333300","#336600","#339900","#33cc00","#33ff00","#660000","#663300","#666600","#669900","#66cc00","#66ff00","#000000"],
					["#000033","#003333","#006633","#009933","#00cc33","#00ff33","#330033","#333333","#336633","#339933","#33cc33","#33ff33","#660033","#663333","#666633","#669933","#66cc33","#66ff33","#333333"],
					["#000066","#003366","#006666","#009966","#00cc66","#00ff66","#330066","#333366","#336666","#339966","#33cc66","#33ff66","#660066","#663366","#666666","#669966","#66cc66","#66ff66","#666666"],
					["#000099","#003399","#006699","#009999","#00cc99","#00ff99","#330099","#333399","#336699","#339999","#33cc99","#33ff99","#660099","#663399","#666699","#669999","#66cc99","#66ff99","#999999"],
					["#0000cc","#0033cc","#0066cc","#0099cc","#00cccc","#00ffcc","#3300cc","#3333cc","#3366cc","#3399cc","#33cccc","#33ffcc","#6600cc","#6633cc","#6666cc","#6699cc","#66cccc","#66ffcc","#cccccc"],
					["#0000ff","#0033ff","#0066ff","#0099ff","#00ccff","#00ffff","#3300ff","#3333ff","#3366ff","#3399ff","#33ccff","#33ffff","#6600ff","#6633ff","#6666ff","#6699ff","#66ccff","#66ffff","#ffffff"],
					["#990000","#993300","#996600","#999900","#99cc00","#99ff00","#cc0000","#cc3300","#cc6600","#cc9900","#cccc00","#ccff00","#ff0000","#ff3300","#ff6600","#ff9900","#ffcc00","#ffff00"],
					["#990033","#993333","#996633","#999933","#99cc33","#99ff33","#cc0033","#cc3333","#cc6633","#cc9933","#cccc33","#ccff33","#ff0033","#ff3333","#ff6633","#ff9933","#ffcc33","#ffff33"],
					["#990066","#993366","#996666","#999966","#99cc66","#99ff66","#cc0066","#cc3366","#cc6666","#cc9966","#cccc66","#ccff66","#ff0066","#ff3366","#ff6666","#ff9966","#ffcc66","#ffff66"],
					["#990099","#993399","#996699","#999999","#99cc99","#99ff99","#cc0099","#cc3399","#cc6699","#cc9999","#cccc99","#ccff99","#ff0099","#ff3399","#ff6699","#ff9999","#ffcc99","#ffff99"],
					["#9900cc","#9933cc","#9966cc","#9999cc","#99cccc","#99ffcc","#cc00cc","#cc33cc","#cc66cc","#cc99cc","#cccccc","#ccffcc","#ff00cc","#ff33cc","#ff66cc","#ff99cc","#ffcccc","#ffffcc"],
					["#9900ff","#9933ff","#9966ff","#9999ff","#99ccff","#99ffff","#cc00ff","#cc33ff","#cc66ff","#cc99ff","#ccccff","#ccffff","#ff00ff","#ff33ff","#ff66ff","#ff99ff","#ffccff","#ffffff"]
				],
*/
				cssPath : ['tools/kindeditor.css']
			});
			break;

		case 'ckeditor': case 'ckeditor4.x':
				if (modeStr.indexOf('|source|')!=-1){ startupModeStr="source"; }else{ startupModeStr="wysiwyg"; }
				if (modeStr.indexOf('|miniMenu|')!=-1){ toolbarStr="Basic"; }else{ toolbarStr="SY"; }
				var editor = CKEDITOR.replace(inputId,
				{
					width : editorW,
					height : editorH,
//					removePlugins : 'elementspath', //去掉底部一行的元素路径显示
//					resize_enabled : true, //拖拽功能开关
					startupMode : startupModeStr,
					toolbar : toolbarStr
				});
			break;

		case 'fckeditor':
				if (modeStr.indexOf('|source|')!=-1){ startupModeStr="source"; }else{ startupModeStr=""; }
				if (modeStr.indexOf('|miniMenu|')!=-1){ toolbarStr="Basic"; }else{ toolbarStr="SY"; }
				var editor=new FCKeditor(inputId);
				editor.BasePath='tools/fckeditor/';	//fck根目录
				editor.Width=editorW;
				editor.Height=editorH;
				editor.EditMode=-1;
				editor.ToolbarSet=toolbarStr;
				editor.ReplaceTextarea() ; 
			break;

		case 'ueditor':
				if (modeStr.indexOf('|source|')!=-1){ editorFirstMode=true; }else{ editorFirstMode=false; }
				if (modeStr.indexOf('|miniMenu|')!=-1){
					toolbarsMenu = [
							['source', '|','forecolor', 'backcolor', 'bold', 'italic', 'underline', 'strikethrough', '|','fontfamily', 'fontsize', '|','link', 'unlink', '|', 'insertimage','spechars']
						];
					minFrameH = editorH-28;
				}else{
					/* V1.2.6.2
					toolbarsMenu = [
							['fullscreen', 'source', '|', 'undo', 'redo', '|','selectall','pasteplain', 'forecolor', 'backcolor','removeformat', 'formatmatch','autotypeset', '|',
								'bold', 'italic', 'underline', 'strikethrough',  '|', 'paragraph', '|','rowspacingtop', 'rowspacingbottom','lineheight', '|','fontfamily', 'fontsize', '|',
								'justifyleft', 'justifycenter','|','link', 'unlink', '|', 'insertimage', 'insertvideo','map', 'gmap', 'highlightcode','template', '|',
								'spechars', 'wordimage', '|', 'searchreplace','help', '|','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', '|','syupimg']
						];
					*/
					/*  */
					toolbarsMenu = [
							['fullscreen', 'source', '|', 'undo', 'redo', '|','selectall','pasteplain', 'forecolor', 'backcolor','removeformat', 'formatmatch','autotypeset', '|',
								'bold', 'italic', 'underline', 'strikethrough',  '|', 'paragraph', '|','rowspacingtop', 'rowspacingbottom','lineheight', '|','fontfamily', 'fontsize', '|',
								'justifyleft', 'justifycenter','|','link', 'unlink', '|', 'insertimage', 'insertvideo','map', 'gmap', 'highlightcode','template', '|',
								'spechars', 'wordimage', '|', 'searchreplace','help', '|','inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols', 'charts', 'drafts', '|','syupimg']
						];
					minFrameH = editorH-84;
				}
				ue = new UE.ui.Editor({
					// 这里可以选择自己需要的工具按钮名称,此处仅选择如下五个
					toolbars:toolbarsMenu,
					// 最小高度,默认320
					minFrameHeight:minFrameH,
					// 允许的最大字符数
					maximumWords:1000000,
					// 是否启用元素路径，默认是显示
					elementPathEnabled:false,
					// 可以最多回退的次数,默认20
					maxUndoCount:99,
					// 是否自动长高,默认true
					autoHeightEnabled:false,
					// 编辑器初始化完成后是否进入源码模式，默认为否。
					sourceEditorFirst:editorFirstMode,
					// 编辑器内部样式,可以用来改变字体等
					initialStyle:'body{font-size:14px;margin:3px;padding:0; line-height:1.4;} p{margin:0;padding:0;}',
					// 关闭字数统计
					wordCount:false
				});
				ue.render(inputId);
			break;
	}

	setTimeout("WindowHeight(0)",1000);
}

// FCKeditor专用-默认显示源代码模式
function FCKeditor_OnComplete(editorInstance){
	if (editorMode=="fckeditor" && startupModeStr=="source"){
		editorInstance.SwitchEditMode();
	}
}

// 缩略图路径
function ImgThumbPath(imgStr){
	if (imgStr.indexOf('/')!=-1){
		headPart = imgStr.substring(0,imgStr.lastIndexOf("/")+1);
		return imgStr.replace(headPart,headPart +"thumb_");
	}else{
		return "thumb_"+ imgStr;
	}
}

// 获取缩略图名
function GetImgName(imgStr){
	if (imgStr.indexOf('/')!=-1){
		return imgStr.substring(imgStr.lastIndexOf("/")+1);
	}else{
		return imgStr;
	}
}



// 截取字符串部分内容
function GetSignStr(contentStr, startCode, endCode, incStart, incEnd){
	if (contentStr.length == 0 || startCode.length == 0 || endCode.length == 0){
		return "";
	}
	var contentTemp="";
	var startPos=-9, endPos=-9;
	/* contentTemp = contentStr.toLowerCase();
	startCode = startCode.toLowerCase();
	endCode = endCode.toLowerCase(); */
	contentTemp = contentStr;
	startCode = startCode;
	endCode = endCode;
	startPos = contentTemp.indexOf(startCode);
	if (startPos < 0){
		return "";
	}else{
		if (incStart == false)
		{
			startPos += startCode.length;
		}
	}
	endPos = contentTemp.substr(startPos).indexOf(endCode);
	if (endPos >= 0) { endPos += startPos; }
	if (endPos <= 0 || endPos <= startPos)
	{
		return "";
	}
	else
	{
		if (incEnd == true)
		{
			endPos += endCode.length;
		}
	}
	return contentStr.substr(startPos, endPos - startPos);
}


function FocusAddText(inputId,str){
	var idObj=document.getElementById(inputId);
	var strLen=idObj.value.length;
	idObj.focus();
	if(typeof document.selection !="undefined"){
		document.selection.createRange().text=str;  
	}else{
		idObj.value=idObj.value.substr(0,idObj.selectionStart)+str+idObj.value.substring(idObj.selectionStart,strLen);
	}
}

function InputAddText(inputId,str){
	document.getElementById(inputId).value=document.getElementById(inputId).value+str;
}

function InputToText(inputId,str){
	document.getElementById(inputId).value=str;
}

function IsChecked(inputId){
	if (document.getElementById(inputId).checked){
		return 1;
	}else{
		return 0;
	}
}


function StyleInput(inputId,toId,type){
	if (type == "b"){
		if ($id(inputId).checked){
			$id(toId).style.fontWeight = "bold";
		}else{
			$id(toId).style.fontWeight = "normal";
		}
	}
}


// date 可以是时间对象也可以是字符串，如果是后者，形式必须为: yyyy-mm-dd hh:mm:ss 其中分隔符不定。"2006年12月29日 16点01分23秒" 也是合法的
function DateAdd(interval,number,date){
	number = parseInt(number);
	if (date.indexOf(":") != -1){
		type = "datetime"
	}else{
		type = "date"
	}

	if (typeof(date)=="string"){
		date = date.split(/\D/);
		--date[1];
		eval("var date = new Date("+date.join(",")+")");
	}
	if (typeof(date)=="object"){
		var date = date
	}
	switch(interval){
		case "y": date.setFullYear(date.getFullYear()+number); break;
		case "m": date.setMonth(date.getMonth()+number); break;
		case "d": date.setDate(date.getDate()+number); break;
		case "w": date.setDate(date.getDate()+7*number); break;
		case "h": date.setHours(date.getHours()+number); break;
		case "n": date.setMinutes(date.getMinutes()+number); break;
		case "s": date.setSeconds(date.getSeconds()+number); break;
		case "l": date.setMilliseconds(date.getMilliseconds()+number); break;
	}
	if (type == "datetime"){
		return date.getFullYear() +"-"+ (date.getMonth()+1) +"-"+ date.getDate() +" "+ date.getHours() +"-"+ date.getMinutes() +"-"+ date.getSeconds();
	}else{
		return date.getFullYear() +"-"+ (date.getMonth()+1) +"-"+ date.getDate();
	}
}


function EncPwdData(pwdName){
	if ($id(pwdName).value == $id('pwdEnc').value){ return false; }
	$.ajaxSetup({cache:false, async:false});
	$.get("../read.php?mudi=encPwd&str="+ base64encode($id(pwdName).value) +"&exp=35", function(result){
		var strArr = (result +'||||').split("|");
		if (strArr[3].length > 3){
			$id('pwdMode').value = strArr[1];
			$id('pwdKey').value = strArr[2];
			$id('pwdEnc').value = strArr[3];
			$id(pwdName).value = strArr[3];
			try{
				$id(pwdName +'2').value = strArr[3];
			}catch (e){ }
		}
		// alert($id('pwdMode').value +'|'+ $id('pwdKey').value +'|'+ $id(pwdName).value);
	});

}


	ajaxDealStr = "数据处理中...";
	ajaxLoadStr = "数据读取中...";

// POST表单AJAX处理
function AjaxPostDeal(formName){
	try {
		document.getElementById("loadingStr").innerHTML = "<span style='font-size:14px;'><img src='images/onload.gif' style='margin-right:5px;' />"+ ajaxDealStr +"</span>";
	}catch (e) {}

	formNameObj = document.getElementById(formName);
	var formNameUrl = formNameObj.getAttribute("action"), formNameContent = formValueToStr(formNameObj);
	$.post(formNameUrl,formNameContent,function(result){
		try {
			document.getElementById("loadingStr").innerHTML = "";
		}catch (e) {}
		eval(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,""));
	});
	return false;
}


// POST提交AJAX处理
function AjaxPostDealToId(formName,outputID){
	try {
		document.getElementById("loadingStr").innerHTML = "<span style='font-size:14px;'><img src='images/onload.gif' style='margin-right:5px;' />"+ ajaxDealStr +"</span>";
	}catch (e) {}
	formNameObj = document.getElementById(formName);
	var formNameUrl = formNameObj.getAttribute("action"), formNameContent = formValueToStr(formNameObj);
	$.post(formNameUrl,formNameContent,function(result){
		try {
			document.getElementById("loadingStr").innerHTML = "";
		}catch (e) {}
		document.getElementById(outputID).innerHTML = result;
	});
	return false;
}


// 通过表单name获取该表单所有元素并组成GET字符串
function formValueToStr(formObj) {
	var qstr = "", and = "", elem, value;
	for(var i = 0; i< formObj.length; ++i) {
		elem = formObj[i];
		if (elem.name!='') {
			value=undefined;
			switch(elem.type) {
				case "select-one":
					if(elem.selectedIndex > -1) {
						value = elem.options[elem.selectedIndex].value;
					}
					else {
						value = "";
					}
					break;
				case"select-multiple":
					var selMul=elem.options;
					for(var w=0;w<selMul.length;++w){
						if(selMul[w].selected){
							qstr += and+elem.name+"="+ encodeURIComponent(selMul[w].value);
							and = "&";
						}
					}
					break;
				case "checkbox":
				case "radio":
					if (elem.checked == true) {
						value = elem.value;
					}
					break;
				default:
					value = elem.value;
			}
			if(value!=undefined){
				value = encodeURIComponent(value);
				qstr += and + elem.name + "=" + value;
				and = "&";
			}
		}
	}
	return qstr;
}


// GET提交AJAX处理
function AjaxGetDeal(urlStr){
	try {
		document.getElementById("loadingStr").innerHTML = "<span style='font-size:14px;'><img src='images/onload.gif' style='margin-right:5px;' />"+ ajaxDealStr +"</span>";
	}catch (e) {}

	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		try {
			document.getElementById("loadingStr").innerHTML = "";
		}catch (e) {}
		eval(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,""));
	});
	return false;
}


// GET提交AJAX处理
function AjaxGetDealToAlert(urlStr){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		alert(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,""));
	});
	return false;
}


// GET提交AJAX处理
function AjaxGetDealToId(urlStr,outputID){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		document.getElementById(outputID).innerHTML = result;
		try{
			WindowHeight(0);
		}catch (e){}
	});
	return false;
}


// GET提交AJAX处理
function AjaxGetDealToInput(urlStr, outputID, dealMode){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		if (dealMode == 'base64'){ result = base64decode(result); }
		document.getElementById(outputID).value = result;
		try{
			WindowHeight(0);
		}catch (e){}
	});
	return false;
}

/* JS版base64编解码算法。示例:
 * b64 = base64encode(data);
 * data = base64decode(b64);
 */
var base64EncodeChars = [
	"A", "B", "C", "D", "E", "F", "G", "H",
	"I", "J", "K", "L", "M", "N", "O", "P",
	"Q", "R", "S", "T", "U", "V", "W", "X",
	"Y", "Z", "a", "b", "c", "d", "e", "f",
	"g", "h", "i", "j", "k", "l", "m", "n",
	"o", "p", "q", "r", "s", "t", "u", "v",
	"w", "x", "y", "z", "0", "1", "2", "3",
	"4", "5", "6", "7", "8", "9", "+", "/"
];

var base64DecodeChars = [
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1,
	-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63,
	52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1,
	-1,  0,  1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14,
	15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1,
	-1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
	41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1
];

function base64encode(str) {
	var out, i, j, len;
	var c1, c2, c3;

	len = str.length;
	i = j = 0;
	out = [];
	while (i < len) {
		c1 = str.charCodeAt(i++) & 0xff;
		if (i == len)
		{
			out[j++] = base64EncodeChars[c1 >> 2];
			out[j++] = base64EncodeChars[(c1 & 0x3) << 4];
			out[j++] = "==";
			break;
		}
		c2 = str.charCodeAt(i++) & 0xff;
		if (i == len)
		{
			out[j++] = base64EncodeChars[c1 >> 2];
			out[j++] = base64EncodeChars[((c1 & 0x03) << 4) | ((c2 & 0xf0) >> 4)];
			out[j++] = base64EncodeChars[(c2 & 0x0f) << 2];
			out[j++] = "=";
			break;
		}
		c3 = str.charCodeAt(i++) & 0xff;
		out[j++] = base64EncodeChars[c1 >> 2];
		out[j++] = base64EncodeChars[((c1 & 0x03) << 4) | ((c2 & 0xf0) >> 4)];
		out[j++] = base64EncodeChars[((c2 & 0x0f) << 2) | ((c3 & 0xc0) >> 6)];
		out[j++] = base64EncodeChars[c3 & 0x3f];
	}
	return out.join('');
}

function base64decode(str) {
	var c1, c2, c3, c4;
	var i, j, len, out;

	len = str.length;
	i = j = 0;
	out = [];
	while (i < len) {
		/* c1 */
		do {
			c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		} while (i < len && c1 == -1);
		if (c1 == -1) break;

		/* c2 */
		do {
			c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
		} while (i < len && c2 == -1);
		if (c2 == -1) break;

		out[j++] = String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));

		/* c3 */
		do {
			c3 = str.charCodeAt(i++) & 0xff;
			if (c3 == 61) return out.join('');
			c3 = base64DecodeChars[c3];
		} while (i < len && c3 == -1);
		if (c3 == -1) break;

		out[j++] = String.fromCharCode(((c2 & 0x0f) << 4) | ((c3 & 0x3c) >> 2));

		/* c4 */
		do {
			c4 = str.charCodeAt(i++) & 0xff;
			if (c4 == 61) return out.join('');
			c4 = base64DecodeChars[c4];
		} while (i < len && c4 == -1);
		if (c4 == -1) break;
		out[j++] = String.fromCharCode(((c3 & 0x03) << 6) | c4);
	}
	return out.join('');
}