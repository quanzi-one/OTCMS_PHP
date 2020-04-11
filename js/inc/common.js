// 获取元素id
function $id(str){
	return document.getElementById(str);
}

// 获取元素name
function $name(str){
	return document.getElementsByName(str);
}

// 把Option的text值覆盖toID文本框
// 应用例子 onchange="OptionTextTo('labItemID','labItemName');"
function OptionTextTo(sourceID,toID){
	document.getElementById(toID).value=document.getElementById(sourceID).options[document.getElementById(sourceID).selectedIndex].text;
}

// 获取下拉框的文本
function SelectGetText(selectName){
	return document.getElementById(selectName).options[document.getElementById(selectName).options.selectedIndex].text;
}

// 下拉框是否存在value值
function SelectValInArr(selectName,selVal){
	var jud = false;

	for (soi=0; soi<document.getElementById(selectName).options.length; soi++){
		if (selVal == document.getElementById(selectName).options[soi].value){ jud = true; return jud; }
	}
	return jud;
}

// 下拉框是否存在text值
function SelectTextInArr(selectName,selVal){
	var jud = false;

	for (soi=0; soi<document.getElementById(selectName).options.length; soi++){
		if (selVal == document.getElementById(selectName).options[soi].text){ jud = true; return jud; }
	}
	return jud;
}


// 判断是否含特殊符号
function Str_IsSign(str){
	var txt=new RegExp("[ ,\\`,\\~,\\!,\\@,\#,\\$,\\%,\\^,\\+,\\*,\\&,\\\\,\\/,\\?,\\|,\\:,\\.,\\<,\\>,\\{,\\},\\(,\\),\\',\\;,\\=,\"]");
	// 特殊字符正则表达式
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

// 计算字符串的字节数
/*function Str_Byte(str){
	strLen = str.length;
	//str=str.replace(/[^\w\u4E00-\u9FA5]/g, '')
	str=str.replace(/[^\x00-\xff]/g, '');
	strLen2 = str.length;
	strTotalLen = strLen2 + (strLen - strLen2) * 2;
	return strTotalLen;
}*/

function GetCookieStr(offset){
	var endstr = document.cookie.indexOf (";", offset);
	if (endstr == -1)
	endstr = document.cookie.length;
	return unescape(document.cookie.substring(offset, endstr));
}

// 获取cookie信息
function GetCookie(name){
	var arg = name + "=";
	var alen = arg.length;
	var clen = document.cookie.length;
	var i = 0;
	while (i < clen){
		var j = i + alen;
		if (document.cookie.substring(i, j) == arg)
		return GetCookieStr (j);
		i = document.cookie.indexOf(" ", i) + 1;
		if (i == 0) break;
	}
	return null;
}

function GetCookie2(name){
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}

// 设置cookie信息
function SetCookie(name, value){
	var argv = SetCookie.arguments;
	var argc = SetCookie.arguments.length;
	var path = "/";
	var domain = "";
	var secure = (argc > 6) ? argv[6] : false;
	document.cookie = name +"=;expires="+(new Date(0)).toGMTString();
	document.cookie = name +"="+ encodeURIComponent(value)+((path == null) ? "" : ("; path=" + path)) +((domain == null) ? "" : ("; domain=" + domain))+((secure == true) ? "; secure" : "");
	// alert(name +"="+ value +"|"+ document.cookie);
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

function ToPinYinId(fromId,toId,mode){
	if ($id(fromId).value == ''){
		alert('中文内容不能为空.');$id(fromId).focus();return false;
	}
	//var a=window.open('read.php?mudi=pinyin&str='+ $id(fromId).value +'&mode='+ mode);
	return AjaxGetDealToInput('read.php?mudi=pinyin&str='+ $id(fromId).value +'&mode='+ mode, toId, 'base64');
}

// 检测邮箱的合法性。
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
	return re.test(fileValue)
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

function FiltHtmlTag(str) {
	str = str.replace(/<\/?[^>]*>/g,'');			// 去除HTML tag
	str = str.replace(/(\t|\r|\n| |\&nbsp;|\&ensp;)/g,'');	// 去除空格、换行、制表符
	return str;
}


// 过滤字符串
// 应用例子 onkeyup="if (this.value!=FiltChar(this.value)){this.value=FiltChar(this.value)}"
// 应用例子 onkeyup="this.value=FiltChar(this.value)"
function FiltChar(str){
	return str.replace(/[^\w\u4E00-\u9FA5]/g, '');
}

// 过滤小数
// 应用例子 onkeyup="if (this.value!=FiltDecimal(this.value)){this.value=FiltDecimal(this.value)}"
// 应用例子 onkeyup="this.value=FiltDecimal(this.value)"
function FiltDecimal(str){
	return str.replace(/[^\d*\.?\d{0,2}$]/g,'')
}

// 过滤小数保留2位小数
// 应用例子 onkeyup="if (this.value!=FiltDecimal2(this.value)){this.value=FiltDecimal2(this.value)}"
// 应用例子 onkeyup="this.value=FiltDecimal2(this.value)"
function FiltDecimal2(str){
	return str.replace(/(^\d*\.?\d{0,2}).*/g,'$1')
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

// 加载JS文件
function LoadJsFile(fileId,filePath,mode){
	var scriptTag = document.getElementById(fileId);
	var headObj = document.getElementsByTagName('head').item(0);
	if(scriptTag){
		if (mode==1){
			headObj.removeChild(scriptTag);
			jsFile = document.createElement('script');
			jsFile.src = filePath;
			jsFile.type = 'text/javascript';
//			jsFile.defer = 'defer';
			jsFile.id = fileId;
			headObj.appendChild(jsFile);
		}
	}else{
		jsFile = document.createElement('script');
		jsFile.src = filePath;
		jsFile.type = 'text/javascript';
//		jsFile.defer = 'defer';
		jsFile.id = fileId;
		headObj.appendChild(jsFile);
	}
}

// 加载CSS文件
function LoadCssFile(fileId,filePath,mode){
	var cssTag = document.getElementById(fileId);
	var headObj = document.getElementsByTagName('head').item(0);
	if(cssTag){
		if (mode==1){
			headObj.removeChild(cssTag);
			cssFile = document.createElement('link');
			cssFile.href = filePath;
			cssFile.rel = 'stylesheet';
			cssFile.type = 'text/css';
			cssFile.id = fileId;
			headObj.appendChild(cssFile);
		}
	}else{
		cssFile = document.createElement('link');
		cssFile.href = filePath;
		cssFile.rel = 'stylesheet';
		cssFile.type = 'text/css';
		cssFile.id = fileId;
		headObj.appendChild(cssFile);
	}
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
		var closeStr = "<span id='floatSec'></span>";
	}else{
		var closeStr = "<div style='margin:0 auto;text-align:center;padding:5px;color:blue;cursor:pointer;' onclick='HiddenMengceng()'>×关闭该窗口</div>";
	}
	//清除之前的样式
	$("#fullScreen,#floatLayer").remove();
	$("body").append(
		//占据整个屏幕Div
		"<div id='fullScreen'></div>"+
		//浮层区
		"<div id='floatLayer'>"+ str + closeStr +"</div>"
	);
}

// 隐藏浮层
function HiddenMengceng(){
	window.clearInterval(djt);
	$("#fullScreen,#floatLayer").remove();
}


// 下拉框内容载入数组变量
function SelectOptionArr(selectName){
	var SelectOptionArray = new Array();

	for (soi=0; soi<document.getElementById(selectName).options.length; soi++){
		SelectOptionArray[document.getElementById(selectName).options[soi].value] = document.getElementById(selectName).options[soi].text;
	}
	return SelectOptionArray;
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

// 光标待的地方添加字符串
function FocusAddText(inputId,str){
	var ubb=document.getElementById(inputId);
	var ubbLength=ubb.value.length;
	ubb.focus();
	if(typeof document.selection !="undefined"){
		document.selection.createRange().text=str;  
	}else{
		ubb.value=ubb.value.substr(0,ubb.selectionStart)+str+ubb.value.substring(ubb.selectionStart,ubbLength);
	}
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
		alert("你的浏览器不支持一键复制功能");
		return;
	}
	alert("复制成功")
	return false;
}

// webPathPart变形
function WppSign(str){
	return str.replace(/\.\.\//g,"a");
}

if (typeof(webPathPart) == "undefined"){ webPathPart = ""; }

// 改变验证码
function ChangeCode(type){
	if (type == 'pop'){ ccId='verCodePop'; }else{ ccId='verCode';type=''; }
	try {
		$id("show"+ type +"code").src=webPathPart +"inc/VerCode/VerCode"+ SYS_verCodeMode +".php?mudi="+ Math.random();
		$id(ccId).value = "";
		$id(ccId).focus();
	}catch (e) {}
}

// 点击验证码框获取验证码
function GetVerCode(str,type){
	if (type == 'pop'){ ccId='showVerCodePop'; }else{ ccId='showVerCode';type=''; }
	try {
		if ($id(ccId).innerHTML.lastIndexOf('VerCode')==-1){
			$id(ccId).innerHTML = "<img id='show"+ type +"code' src='"+ webPathPart +"inc/VerCode/VerCode"+ SYS_verCodeMode +".php?mudi="+ Math.random() +"' align='top' style='cursor:pointer;' onclick='ChangeCode(\""+ type +"\")' alt='点击更换' />";	
		}else if (str == "change"){
			ChangeCode(type);
		}
	}catch (e) {}
}

// 重置验证码
function ResetVerCode(type){
	if (SYS_verCodeMode == 20){
		ResetGeetest(type);
	}else{
		GetVerCode("change",type);
	}
}

function WebCurrAnchor(str){
	webCurrUrl = document.location.href;
	if (webCurrUrl.indexOf("#")>-1){
		document.location.href=webCurrUrl.substring(0,webCurrUrl.lastIndexOf("#")) +"#"+ str;
	}else{
		document.location.href=webCurrUrl +"#"+ str;
	}
}

// 顶部会员专区
function TopUser(){
	if (webTypeName!="api"){
		$.ajaxSetup({cache:false});
		$.get(webPathPart +"users.php", function(result){
			document.getElementById("topUserBox").innerHTML = result.replace(/\"\.\//g,'"'+ webPathPart);
		});
	}

	return false;

}

// 加入收藏夹
function AddFavorite(sURL, sTitle){
	try{
		window.external.addFavorite(sURL, sTitle);
	}catch (e){
		try{
			window.sidebar.addPanel(sTitle, sURL, "");
		}catch (e){
			alert("加入收藏失败，请使用Ctrl+D进行添加");
		}
	}
}

// 设为首页
function SetHome(obj,vrl){
	try{
		obj.style.behavior='url(#default#homepage)';obj.setHomePage(vrl);
	}catch(e){
		if(window.netscape) {
			try {
				netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
			}catch (e){
				alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
			}
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage',vrl);
		}else{
			alert("您的浏览器不支持，请按照下面步骤操作：1.打开浏览器设置。2.点击设置网页。3.输入："+ vrl +"点击确定。");
		}
	}
}

// 设置RSS网址
function SetRssUrl(){
	$id('rssValue').value=$id('rssUrl').value +"?typeStr="+ $id('rssTypeStr').value +"&maxNum="+ $id('rssMaxNum').value;
}

// 页头搜索框显示默认值
function RefFormWord(){
	if ($id('refContent').value == ''){
		$id('refContent').value = refContentDef;
		$id('refContent').style.color = '#a59ea3';
	}
}

// 页头搜索框不显示默认值
function RefFormNoWord(){
	if ($id('refContent').value == refContentDef){
		$id('refContent').value = '';
		$id('refContent').style.color = '#000000';
	}
}

// 页头搜索表单检测
function CheckRefForm(){
	refContStr = $id("refContent").value;
	if (refContStr == '' || refContStr == refContentDef){
		alert("请输入要搜索的关键字");
		//$id("refContent").value='';$id("refContent").focus();
		return false;
	}
	switch ($id("refMode").value){
		case "theme": case "content": case "source": case "writer": 
			refContEncodeStr=(refContStr);
			// if (SYS_newsListUrlMode=="static-3.x"){
			// 	document.location.href=jsPathPart + SYS_newsListFileName +"/list_refer-"+ $id("refMode").value +"-"+ refContEncodeStr +".html";
			// }else 
			if (SYS_newsListUrlMode=="dyn-2.x" || SYS_newsListUrlMode=="html-2.x" || SYS_newsListUrlMode=="static-3.x"){
				if (typeof(SYS_htmlUrlSel)=="undefined"){ SYS_htmlUrlSel=""; }
				if (SYS_htmlUrlSel!="1"){ SYS_newsListFileName="news"; }
				document.location.href=jsPathPart +"news/?list_refer-"+ $id("refMode").value +"-"+ refContEncodeStr +".html";
			}
			return false;
			break;

		case "baidu":
			bdzhannei_domain = "";
			bdzhannei_id = "";
			try {
				bdzhannei_domain = $id('zhannei_domain').value;
				bdzhannei_id = $id('zhannei_id').value;
			}catch (e) {}
			if (bdzhannei_domain==""){ bdzhannei_domain="zhannei.baidu.com"; }
			$id("referForm").action="http://"+ bdzhannei_domain +"/cse/search?s="+ bdzhannei_id +"&entry=1&q="+ refContStr;
			$id("referForm").method="post";
			$id("referForm").target="_blank";
			return true;
			break;

		case "360":
			$id("referForm").action="https://www.so.com/s?q="+ refContStr +"&ie=utf8&src="+ $id('zhannei_src').value +"&site="+ $id('zhannei_site').value +"&rg=1";
			$id("referForm").method="post";
			$id("referForm").target="_blank";
			return true;
			break;

		default:
			return false;
			break;
	}
}

// 全局执行内容
function WinLoadRun(str){
	if (typeof(SYS_isClose)=="undefined"){ SYS_isClose=20; }
	if (SYS_isClose==20){			// 网站开启
		
	}else if (SYS_isClose==10){		// 网站关闭
		document.write(""+
		"<!DOCTYPE html>"+
		"<html>"+
		"<head>"+
			"<title>网站暂时关闭中...</title>"+
		"</head>"+
		"<body>"+
			"<table align='center' cellpadding='0' cellspacing='0'><tr><td align='left' style='font-size:14px;'>"+ SYS_closeNote +"</td></tr></table>"+
		"</body>"+
		"</html>");
	}

	if (typeof(US_isUserSys) == "undefined"){ US_isUserSys = 0; }
	if (typeof(US_isLogin) == "undefined"){ US_isLogin = ""; }
	if (US_isUserSys==1 && US_isLogin==1){ TopUser(); }

	try {
		// 初始化搜索框
		RefFormWord();
		$('#refContent').blur(function (){
			RefFormWord();
		});
		$('#refContent').click(function (){
			RefFormNoWord();
		});
	}catch (e) {}

	try {
		if (typeof(SYS_isWap) == "undefined"){ SYS_isWap = 0; }
		if (typeof(SYS_wapUrl) == "undefined"){ SYS_wapUrl = ""; }
		if (SYS_isWap==1 && SYS_wapUrl.length>0){
			LoadJsFile("qrcodeFile",webPathPart +"js/inc/qrcode.min.js",0);
			$('#topWapBtn').mouseover(function (){
				$id('wapQrBox').innerHTML=""+
					"<div style='position:absolute;border:1px #000 solid;background:#ffffff;'>"+
						"<div id='wapQrImg' style='padding:10px 10px 5px 10px;'></div>"+
						"<div style='text-align:center;color:red;font-weight:bold;'>手机扫描访问手机版</div>"+
					"</div>"+
					"";
				var qrcode = new QRCode(document.getElementById("wapQrImg"), { width:135, height:135 });
				qrcode.makeCode(SYS_wapUrl);
				$id('wapQrBox').style.display="";
			});
			/* 旧版获取二维码 $('#topWapBtn').mouseover(function (){
				$id('wapQrBox').style.display="";
				$id('wapQrBox').innerHTML=""+
					"<div style='position:absolute;border:1px #000 solid;background:#ffffff;'>"+
						"<img src='"+ webPathPart +"qrcode.php?text="+ encodeURIComponent(SYS_wapUrl) +"&logo=&size=5&margin=2' width='150' />"+
						"<div style='text-align:center;color:red;font-weight:bold;'>手机扫描访问手机版</div>"+
					"</div>"+
					"";
			}); */
			$('#topWapBtn').mouseout(function (){
				$id('wapQrBox').style.display="none";
			});
		}
	}catch (e) {}

	// 快捷登录页面
	if (webTypeName=="api"){
		setTimeout("CheckUserName(document.getElementById('username').value);",500);
	}

	// 是否更新
	var myDate = new Date();
	var timestamp = Date.parse(myDate);
	timestamp = timestamp/1000;
	$.getScript(jsPathPart + 'cache/js/autoRunSys.js?v='+ myDate.getDate() + myDate.getHours() + myDate.getMinutes() ,function(res,state){
		var isRun = 0;
		if (ARS_isHtmlHome == 1 && ARS_htmlHomeTimer + ARS_htmlHomeMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 0 && ARS_isHtmlList == 1 && ARS_htmlListTimer + ARS_htmlListMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 0 && ARS_isHtmlShow == 1 && ARS_htmlShowTimer + ARS_htmlShowMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 0 && ARS_isColl == 1 && ARS_collTimer + ARS_collMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 0 && ARS_isTimeRun == 1 && ARS_timeRunTimer + ARS_timeRunMin * 60 < timestamp){
			isRun = 1;
		}
		if (isRun == 1){ // #topUserBox
			if (typeof(ARS_runMode)=="undefined"){ ARS_runMode=0; }
			$("body").append(''+
				'<iframe id="autoRun_time" name="autoRun_time" src="about:blank" width="0" height="0" style="display:none"></iframe>'+
				'<iframe id="autoRun_home" name="autoRun_home" src="about:blank" width="0" height="0" style="display:none"></iframe>'+
				'<iframe id="autoRun_list" name="autoRun_list" src="about:blank" width="0" height="0" style="display:none"></iframe>'+
				'<iframe id="autoRun_show" name="autoRun_show" src="about:blank" width="0" height="0" style="display:none"></iframe>'+
				'<iframe id="autoRun_coll" name="autoRun_coll" src="about:blank" width="0" height="0" style="display:none"></iframe>'+
				'');
			var arTime_window=window.frames["autoRun_time"];
			arTime_window.window.alert=function(){ return false; };
			var arHome_window=window.frames["autoRun_home"];
			arHome_window.window.alert=function(){ return false; };
			var arList_window=window.frames["autoRun_list"];
			arList_window.window.alert=function(){ return false; };
			var arShow_window=window.frames["autoRun_show"];
			arShow_window.window.alert=function(){ return false; };
			var arColl_window=window.frames["autoRun_coll"];
			arColl_window.window.alert=function(){ return false; };

			AjaxGetDeal(webPathPart +"autoRun.php?type=qiantai&isAjaxRun="+ ARS_runMode +"&rnd="+ timestamp);
		}
	});
	/*
	
	.done(function(script, textStatus) {
		// alert('done');
	})
	.fail(function(jqxhr, settings, exception) {
		// alert('fail'+ jqxhr +'|'+ settings +'|'+ exception);
	})
	.complete(function(){
		// alert('complete');
	})
	*/

	if (typeof(SYS_isFloatAd)=="undefined"){ SYS_isFloatAd=0; }
	if (SYS_isFloatAd==1){
		// 对class=floatWin的元素进行智能浮动定位
		$.fn.smartFloat = function() {
			var position = function(element) {
				var top = element.position().top, pos = element.css("position");
				var fWinTop = parseInt($("#floatWin").val());
				if (isNaN(fWinTop)){ fWinTop=0; }
				$(window).scroll(function() {
					var scrolls = $(this).scrollTop();
					if (scrolls > top) {
						if (window.XMLHttpRequest) {
							element.css({
								position: "fixed",
								top: fWinTop
							});
						} else {
							element.css({
								top: scrolls+fWinTop
							});	
						}
					}else {
						element.css({
							position: pos,
							top: top+fWinTop
						});	
					}
				});
			};
			return $(this).each(function() {
				position($(this));						 
			});
		};
		//绑定
		$(".floatWin").smartFloat();
	}

}

// 判断是否跳转到手机版
function JudGoWap(){
	var browser = {
		versions: function () {
			var u = navigator.userAgent, app = navigator.appVersion;
			return {//移动终端浏览器版本信息 
				trident: u.indexOf('Trident') > -1, //IE内核
				presto: u.indexOf('Presto') > -1, //opera内核
				webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
				gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
				mobile: !!u.match(/AppleWebKit.*Mobile.*/) || !!u.match(/AppleWebKit/), //是否为移动终端
				ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
				android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
				iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
				iPad: u.indexOf('iPad') > -1, //是否iPad
				webApp: u.indexOf('Safari') == -1, //是否web应该程序，没有头部与底部
				winphone:u.indexOf('Windows Phone') > -1 //Windows Phone
			};
		} (),
		language: (navigator.browserLanguage || navigator.language).toLowerCase(),
		appcookie:GetCookie('wap_otcms')
	}

	listType="";
	if (webTypeName=="list"){
		currUrl = document.location.href;
		if (currUrl.lastIndexOf('list_')!=-1){
			listKeyStr = currUrl.substring(currUrl.lastIndexOf('list_')+5);
			if (listKeyStr.indexOf('_')!=-1){
				listType = listKeyStr.substring(0,listKeyStr.indexOf('_'));
			}else if (listKeyStr.indexOf('.')!=-1){
				listType = listKeyStr.substring(0,listKeyStr.indexOf('.'));
			}
		}
	}

	var hascookie = browser.appcookie;
	// var ref = document.referrer;
	// if(ref.toLocaleLowerCase().indexOf("/wap/")!=-1){ hascookie="pc"; }
	if ((hascookie==null || hascookie!="pc") && ( browser.versions.android == true || browser.versions.iPhone == true || browser.versions.winphone == true)){
		SetCookie("wap_otcms","wap");
		// document.location.href = wapUrl;
		document.location.replace(wapUrl);
	}
}

// 底部JS输出
function JsWriteBottom(){
	// 是否加载淘宝客JS
	if (typeof(TS_pid) == "undefined"){ TS_pid = ''; }
	if (typeof(TS_appkey) == "undefined"){ TS_appkey = ''; }
	if (typeof(TS_signCode) == "undefined"){ TS_signCode = ''; }
	if (TS_pid != '' && TS_signCode != ''){
		document.write('<script type="text/javascript" charset="utf-8" src="'+ webPathPart +'js/app/taobaoke.js?v=3.67"></script>');
		// LoadJsFile('appTaobaoke',webPathPart +'js/app/taobaoke.js',0);
	}
	if (webTypeName == 'show'){
		document.write('<script type="text/javascript" charset="utf-8" src="'+ webPathPart +'tools/swfobject/swfobject.js"></script>');
		// LoadJsFile("swfobjectJs",webPathPart +"tools/swfobject/swfobject.js",0);
	}

}



// 通用Ajax提交表单
function CheckAjaxForm(formName){
	AjaxPostDeal(formName);
	return false;
}

// Ajax导航链接
function AjaxNavHref(){
	var outputID = arguments[0] ? arguments[0] : "";
	var urlStr = arguments[1] ? arguments[1] : "";
	var pageNum = arguments[2] ? arguments[2] : "";

	if (outputID==""){ outputID="dialogBody"; }
	if (urlStr==""){ urlStr=document.location.href; }
	if (! isNaN(parseInt(pageNum))){ pageNum="&page="+ pageNum; }else{ pageNum=""; }

	document.getElementById(outputID).innerHTML="<br /><br /><center style='font-size:14px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />数据加载中...</center><br /><br />";
	$.ajaxSetup({cache:false});
	$.get(webPathPart + urlStr + pageNum, function(result){
		document.getElementById(outputID).innerHTML=result;
		try {
			backNavBtn = document.getElementById("backAjaxNavHeader").href;
			if (backNavBtn.indexOf ("#")!=-1){
				webCurrUrl = document.location.href;
				if (webCurrUrl.indexOf ("#")>-1){
					webCurrUrl=webCurrUrl.substring(0,webCurrUrl.lastIndexOf("#"));
				}
				webCurrUrl=webCurrUrl +"#"+ backNavBtn.substring(backNavBtn.lastIndexOf("#")+1,backNavBtn.length);
//				alert(webCurrUrl);
				document.getElementById("backAjaxNavHeader").href = webCurrUrl;
				setTimeout("document.getElementById('backAjaxNavHeader').click();",300);
			}
		}catch (e) {}
	});

}

// 分页链接
function ListPageHref(pageNum,mode1Url){
	if (pageNum<2){
		pageUrl = mode1Url.replace("_[page]","").replace("[page]",pageNum);
		if (pageUrl.substr(pageUrl.length-10)=="index.html"){ pageUrl = pageUrl.substr(0,pageUrl.length-10); }
		document.location.href=pageUrl;
	}else{
		document.location.href=mode1Url.replace("[page]",pageNum);
	}
}


ajaxDealStr = "数据处理中...";
ajaxLoadStr = "数据读取中...";

// POST表单AJAX处理
function AjaxPostDeal(formName){
	try {
		document.getElementById("loadingStr").innerHTML = "<span style='font-size:14px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />"+ ajaxDealStr +"</span>";
	}catch (e) {}

	formNameObj = document.getElementById(formName);
	var formNameUrl = formNameObj.getAttribute("action"), formNameContent = formValueToStr(formNameObj);
	$.post(formNameUrl,formNameContent,function(result){
		try {
			document.getElementById("loadingStr").innerHTML = "";
		}catch (e) {}
		// alert('['+ result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,"") +']');
		eval(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,""));
		try {
			document.getElementById("loadingStr").innerHTML = "";
		}catch (e) {}
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
							qstr += and+elem.name +"="+ encodeURIComponent(selMul[w].value);
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
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
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

// GET提交AJAX处理返回值到input标签里
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

// GET提交AJAX处理返回值到id标签下
function AjaxGetDealToId(urlStr,outputID,addiEvent){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		document.getElementById(outputID).innerHTML = result;
		AjaxAddiEvent(result,addiEvent)
	});

	return false;
}

// GET提交AJAX处理
function AjaxGetDealToIdNo(urlStr,outputID,badWords){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		if (result.lastIndexOf(badWords)!=-1){
			eval(result.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"").replace(/(<meta[^>]*>|<\/meta>)/gi,""));
		}else{
			document.getElementById(outputID).innerHTML = result;
		}
	});
	return false;
}

// GET提交AJAX处理（允许执行JS）
function AjaxGetDealToIdJs2(urlStr,outputID,addiEvent){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		document.getElementById(outputID).innerHTML = result;
//<script src="../tools/fckeditor/flvPlayer/swfobject.js" type="text/javascript"></script>
		var jsFileStr="";
		var jsFileArr=result.match(/src\s*=\s*[\""|\']?\s*[^>\""\'\s]*\.js/gi);
		if(jsFileArr!=null){
			for(var i=0;i<jsFileArr.length;i++){
				//jsFileStr += jsFileArr[i].replace(/src\s*=\s*[\""|\']?(\s*[^>\""\'\s]*\.js)/gi,"$1");
				LoadJsFile("contJsFile"+ i,jsFileArr[i].replace(/src\s*=\s*[\""|\']?(\s*[^>\""\'\s]*\.js)/gi,"$1"),0);
			}
		}
		//alert(jsFileStr);

		var jsStr="";
		var jsArr=result.match(/<\s*(script[^>]*)>([\s\S][^<]*)<\/\s*script>/gi);
		if(jsArr!=null){
			for(var i=0;i<jsArr.length;i++){
				jsStr += jsArr[i];
			}
		}
		jsStr = jsStr.replace(/<.*?(script[^>]*?)>/gi,"").replace(/<\/.*?script.*?>/gi,"");
		window.setTimeout(jsStr,1000);

		AjaxAddiEvent(result,addiEvent)
	});

	return false;
}

// GET提交AJAX处理（允许执行JS）
function AjaxGetDealToIdJs(urlStr,outputID,addiEvent){
	$.ajaxSetup({cache:false});
	$.get(urlStr, function(result){
		set_innerHTML(outputID,result);
		AjaxAddiEvent(result,addiEvent)
	});

	return false;
}


// Ajax附加事件
function AjaxAddiEvent(str,addiEvent){
	if (typeof(addiEvent)=="undefined"){ addiEvent=''; }
	if (addiEvent.indexOf('vote') != -1){
		try {
			VoteStyle();
		}catch (e) {}
	}else if (addiEvent.indexOf('video') != -1){
		try {
			LoadVideoFile(str);
		}catch (e) {}
	}else if (addiEvent.indexOf('geetest') != -1){
		try {
			if (SYS_verCodeMode == 20){
				LoadJsFile('geetestJs',webPathPart +'tools/geetest/gt.js?v=1.0',1);
			}
		}catch (e) {}
	}
}


/* innerhtml.js
 * Copyright Ma Bingyao <andot@ujn.edu.cn>
 * Version: 1.9
 * LastModified: 2006-06-04
 * This library is free.  You can redistribute it and/or modify it.
 */

var global_html_pool = [];
var global_script_pool = [];
var global_script_src_pool = [];
var global_lock_pool = [];
var innerhtml_lock = null;
var document_buffer = "";

// js输出允许执行js脚本，obj_id：innerHTML的ID；html：输出字符串；time：延时多少秒执行，可以忽略
function set_innerHTML(obj_id, html, time){
	if (innerhtml_lock == null) {
		innerhtml_lock = obj_id;
	}
	else if (typeof(time) == "undefined") {
		global_lock_pool[obj_id + "_html"] = html;
		window.setTimeout("set_innerHTML('" + obj_id + "', global_lock_pool['" + obj_id + "_html']);", 10);
		return;
	}
	else if (innerhtml_lock != obj_id) {
		global_lock_pool[obj_id + "_html"] = html;
		window.setTimeout("set_innerHTML('" + obj_id + "', global_lock_pool['" + obj_id + "_html'], " + time + ");", 10);
		return;
	}

	function get_script_id() {
		return "script_" + (new Date()).getTime().toString(36) + Math.floor(Math.random() * 100000000).toString(36);
	}

	document_buffer = "";

	document.write = function (str) {
		document_buffer += str;
	}
	document.writeln = function (str) {
		document_buffer += str + "\n";
	}

	global_html_pool = [];

	var scripts = [];
	html = html.split(/<\/script>/i);
	for (var i = 0; i < html.length; i++) {
		global_html_pool[i] = html[i].replace(/<script[\s\S]*$/ig, "");
		scripts[i] = {text: '', src: '' };
		scripts[i].text = html[i].substr(global_html_pool[i].length);
		scripts[i].src = scripts[i].text.substr(0, scripts[i].text.indexOf('>') + 1);
		scripts[i].src = scripts[i].src.match(/src\s*=\s*(\"([^\"]*)\"|\'([^\']*)\'|([^\s]*)[\s>])/i);
		if (scripts[i].src) {
			if (scripts[i].src[2]) {
				scripts[i].src = scripts[i].src[2];
			}
			else if (scripts[i].src[3]) {
				scripts[i].src = scripts[i].src[3];
			}
			else if (scripts[i].src[4]) {
				scripts[i].src = scripts[i].src[4];
			}
			else {
				scripts[i].src = "";
			}
			scripts[i].text = "";
		}
		else {
			scripts[i].src = "";
			scripts[i].text = scripts[i].text.substr(scripts[i].text.indexOf('>') + 1);
			scripts[i].text = scripts[i].text.replace(/^\s*<\!--\s*/g, "");
		}
	}

	var s;
	if (typeof(time) == "undefined") {
		s = 0;
	}
	else {
		s = time;
	}

	var script, add_script, remove_script;

	for (var i = 0; i < scripts.length; i++) {
		var add_html = "document_buffer += global_html_pool[" + i + "];\n";
		add_html += "document.getElementById('" + obj_id + "').innerHTML = document_buffer;\n";
		script = document.createElement("script");
		if (scripts[i].src) {
			script.src = scripts[i].src;
			if (typeof(global_script_src_pool[script.src]) == "undefined") {
				global_script_src_pool[script.src] = true;
				s += 2000;
			}
			else {
				s += 10;
			}
		}
		else {
			script.text = scripts[i].text;
			s += 10;
		}
		script.defer = true;
		script.type =  "text/javascript";
		script.id = get_script_id();
		global_script_pool[script.id] = script;
		add_script = add_html;
		add_script += "document.getElementsByTagName('head').item(0)";
		add_script += ".appendChild(global_script_pool['" + script.id + "']);\n";
		window.setTimeout(add_script, s);
		remove_script = "document.getElementsByTagName('head').item(0)";
		remove_script += ".removeChild(document.getElementById('" + script.id + "'));\n";
		remove_script += "delete global_script_pool['" + script.id + "'];\n";
		window.setTimeout(remove_script, s + 10000);
	}

	var end_script = "if (document_buffer.match(/<\\/script>/i)) {\n";
	end_script += "set_innerHTML('" + obj_id + "', document_buffer, " + s + ");\n";
	end_script += "}\n";
	end_script += "else {\n";
	end_script += "document.getElementById('" + obj_id + "').innerHTML = document_buffer;\n";
	end_script += "innerhtml_lock = null;\n";
	end_script += "}";
	window.setTimeout(end_script, s);
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

function StrToB2(str) {
	return base64encode(base64encode(str));
}

function B2ToStr(str) {
	return base64decode(base64decode(str));
}

// 让IE核心浏览器也支持placeholder属性；jQuery placeholder, fix for IE6,7,8,9
var JPlaceHolder = {
	// 检测
	_check : function(){
		return 'placeholder' in document.createElement('input');
	},
	// 初始化
	init : function(){
		if(!this._check()){
			this.fix();
		}
	},
	// 修复
	fix : function(){
		jQuery(':input[placeholder]').each(function(index, element) {
			var self = $(this), txt = self.attr('placeholder');
			self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));
			var pos = self.position(), h = self.outerHeight(true), paddingleft = self.css('padding-left');
			var holder = $('<span></span>').text(txt).css({position:'absolute', left:pos.left+15, top:pos.top+12, height:h, lienHeight:h, paddingLeft:paddingleft, color:'#aaa'}).appendTo(self.parent());
			self.focusin(function(e) {
				holder.hide();
			}).focusout(function(e) {
				if(!self.val()){
					holder.show();
				}
			});
			holder.click(function(e) {
				holder.hide();
				self.focus();
			});
		});
	}
};
// 执行
jQuery(function(){
	JPlaceHolder.init();	
});


// 加载城市数据
function LoadCityData(idName,prov){
	AjaxGetDeal('read.php?mudi=getCityData&idName='+ idName +'&prov='+ prov);
}



var isJsNicEdit=false;
var ke4Editor = [];

// 编辑器加载
function CheckJsNicEdit(){
	if (isJsNicEdit==false){
		LoadJsFile("kindeditorJs",webPathPart +"tools/kindeditor4/kindeditor-all-min.js",0);
		isJsNicEdit=true;
	}
}

// 加载编辑器
function LoadEditor(str,minSec) {
	CheckJsNicEdit();
	setTimeout("InitEditor('"+ str +"');",minSec);
}

function InitEditor(inputId){
	/* if (modeStr.indexOf('|miniMenu|')!=-1){
		itemsVal=[
			'source', '|','cut', 'copy', 'paste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			'fontname', 'fontsize', 'forecolor', '|', 'bold',
			'italic', 'underline', 'strikethrough', 'removeformat', '|', 'link', 'unlink', 'image'
		];
	}else{ */
		// ,  '|', 'fullscreen', '|', 'about'
		itemsVal=[
			'source', '|', 'undo', 'redo', '|', 'code', 'selectall','cut', 'copy', 'paste',
			'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
			'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
			'superscript', 'clearhtml', 'quickformat', '/',
			'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
			'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'image',
			'flash', 'media', 'insertfile', 'table', 'hr', 'baidumap', 'anchor', 'link', 'unlink'
		];
	// }
	ke4Editor[inputId] = KindEditor.create("#"+ inputId,{
		allowImageUpload : false,
		allowFlashUpload : false,
		allowMediaUpload : false,
		allowFileUpload : false,
		allowFileManager : false,
		afterBlur:function(){ this.sync(); },
		minWidth : 506,
		minHeight : 220,
		newlineTag : 'p',
		items : itemsVal,
		designMode : true,
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
		cssPath : [jsPathPart +'tools/kindeditor.css']
	});

}

// 获取编辑器中HTML内容
function GetEditorHTML(EditorName) {
	return ke4Editor[EditorName].html();
}

// 获取编辑器中文字内容
function GetEditorText(EditorName) {
	return ke4Editor[EditorName].text();
}

// 设置编辑器中内容
function SetEditorHtml(EditorName, ContentStr) {
	ke4Editor[EditorName].html(ContentStr);
}

// 插入字符串到编辑器中
function InsertStrToEditor(EditorName, ContentStr) {
	ke4Editor[EditorName].insertHtml(ContentStr);
}

// 同步编辑器数据
function SyncEditor(EditorName) {
	ke4Editor[EditorName].sync();
}


// 重置极验
function ResetGeetest(str){
	if (str == 'pop'){
		$id('geePopDiv').innerHTML = "";
		LoadJsFile('geePopJs',webPathPart +'tools/geetest/gtPop.js?v=1.0',1);
	}else{
		$id('geetestDiv').innerHTML = "";
		LoadJsFile('geetestJs',webPathPart +'tools/geetest/gt.js?v=1.0',1);
	}
}


// 加载音视频文件
function LoadVideoFile(str){
	if (ToInt($id('isUserCheck').value)>0){
		if (str.indexOf('CuPlayer') != -1){
			if (typeof(vType) == "undefined"){ vType = ""; }
			if (vType == 'mp3' && vMp3url.length > 0){
				var flashvars = { 
					autostart: true,
					skin: "st1.swf",
					streamer: vServer,
					file: vMp3url
				}; 
				var params = { 
					movie: vPlayer,
					allowFullScreen: true,
					allowScriptAccess: "always",
					wmode: "Transparent",
					menu: "false" 
				}; 
				var attributes = { 
					id: "myPlayer", 
					name: "myPlayer" 
				}; 
				swfobject.embedSWF(vPlayer, "CuPlayer", vWidth, vHeight, "9.0.0", "expressInstall.swf", flashvars, params, attributes);
			}else if (vType == 'mp4' && vMp4url.length > 0){
				var flashvars = { 
					JcScpFile: vFile,
					JcScpVideoPath: vMp4url,
					JcScpImg: vPic
				}; 
				var params = { 
					menu: "false" 
				}; 
				var attributes = { 
					id: "myPlayer", 
					name: "myPlayer" 
				}; 
				swfobject.embedSWF(vPlayer, "CuPlayer", vWidth, vHeight, "9.0.0", "expressInstall.swf", flashvars, params, attributes);
			}
		}
	}
}


// 上传图片框
function OT_OpenUpImg(fileMode,fileFormName,fileDir,otherPara){
	var arr = window.open(jsPathPart +"usersNewsUpImg.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir +"&upFileType=images"+ otherPara,"","top=150,left="+ ((window.screen.width-600)/2) +",width=600,height=300,menubar=no,scrollbars=yes,status=no,resizable=yes");
}

// 上传文件框
function OT_OpenUpFile(fileMode,fileFormName,fileDir,otherPara){
	var arr = window.open(jsPathPart +"usersNewsUpFile.php?fileMode="+ fileMode +"&fileFormName="+ fileFormName +"&upPath="+ fileDir + otherPara,"","top=150,left="+ ((window.screen.width-600)/2) +",width=600,height=300,menubar=no,scrollbars=yes,status=no,resizable=yes");
}

// 表单图片数量
function CheckFormImg(str){
	var num = $id(str +'_num').value;
	for (i=1; i<=9; i++){
		if (i <= num){
			$id(str +'_'+ i +'box').style.display = '';
		}else{
			$id(str +'_'+ i +'box').style.display = 'none';
		}
	}
}


// 检测邮箱的合法性
function CheckMail(){
	if ($id("mail").value == ''){
		$id("mailIsOk").innerHTML = "";
		$id("mailStr").style.display = "none";
	}else if (IsMail($id("mail").value)){
		$id("mailIsOk").innerHTML = "<img src='"+ webPathPart +"inc_img/share_yes.gif' />";
		$id("mailStr").style.display = "none";
	}else{
		$id("mailIsOk").innerHTML = "<img src='"+ webPathPart +"inc_img/share_no.gif' />";
		$id("mailStr").style.display = "";
		$id("mailStr").innerHTML = "邮箱格式错误！";
	}
}

// 发送邮件按钮倒计时
var mailSec = 0;
var mailCalcFunc;
function MailBtnCalc(btnId,sec){
	$id(btnId).disabled = true;
	mailSec = sec;
	$id(btnId).value = ""+ mailSec +"秒后可重试";
	mailCalcFunc = window.setInterval("CutMailCalc('"+ btnId +"')",1000);
}

function CutMailCalc(btnId){
	if (mailSec<=0){
		window.clearInterval(mailCalcFunc);
		$id(btnId).disabled = false;
		$id(btnId).value = "发送邮件验证码";
		return false;
	}else{
		mailSec --;
		$id(btnId).value = ""+ mailSec +"秒后可重试";
	}
}

// 发送邮件验证码
function SendMailCode(btnId,mailId,type,userId){
	var mailStr = $id(mailId).value;
	if (mailStr==""){
		alert("请先输入邮箱！");
		try{ $id(mailId).focus(); }catch (e){}
		return false;
	}
	if (! IsMail(mailStr)){
		alert("邮箱格式错误！");
		try{ $id(mailId).focus(); }catch (e){}
		return false;
	}

	var userStr = '';
	if (userId.length > 0 && $id(userId)){
		userStr = $id(userId).value;
	}

	$id(btnId).value = "处理中...";
	AjaxGetDeal(webPathPart +'users_deal.php?mudi=mailSend&type='+ type +'&btnId='+ btnId +'&mail='+ mailStr +'&username='+ userStr);
}


// 检测手机号的合法性
function CheckPhone(){
	if ($id("phone").value == ''){
		$id("phoneIsOk").innerHTML = "";
		$id("phoneStr").style.display = "none";
	}else if (IsPhone($id("phone").value)){
		$id("phoneIsOk").innerHTML = "<img src='"+ webPathPart +"inc_img/share_yes.gif' />";
		$id("phoneStr").style.display = "none";
	}else{
		$id("phoneIsOk").innerHTML = "<img src='"+ webPathPart +"inc_img/share_no.gif' />";
		$id("phoneStr").style.display = "";
		$id("phoneStr").innerHTML = "手机号格式错误！";
	}
}

// 发送短信按钮倒计时
var phoneSec = 0;
var phoneCalcFunc;
function PhoneBtnCalc(btnId,sec){
	$id(btnId).disabled = true;
	phoneSec = sec;
	$id(btnId).value = ""+ phoneSec +"秒后可重试";
	phoneCalcFunc = window.setInterval("CutPhoneCalc('"+ btnId +"')",1000);
}

function CutPhoneCalc(btnId){
	if (phoneSec<=0){
		window.clearInterval(phoneCalcFunc);
		$id(btnId).disabled = false;
		$id(btnId).value = "发送短信验证码";
		return false;
	}else{
		phoneSec --;
		$id(btnId).value = ""+ phoneSec +"秒后可重试";
	}
}

// 开启发送短信验证码表单
function SendPhoneCode(btnId,phoneId,type,userId){
	var phoneStr = $id(phoneId).value;
	if (phoneStr==""){
		alert("请先输入手机");
		try{ $id(phoneId).focus(); }catch (e){}
		return false;
	}
	if (! IsPhone(phoneStr)){
		alert("手机号格式错误，应该1开头，长度11位！");
		try{ $id(phoneId).focus(); }catch (e){}
		return false;
	}

	var userStr = '';
	if (userId.length > 0 && $id(userId)){
		userStr = $id(userId).value;
	}

	$id(btnId).value = "处理中...";
	$.ajaxSetup({cache:false});
	$.get(webPathPart +'readDeal2.php?mudi=sendPhoneForm&type='+ type +'&btnId='+ btnId +'&phone='+ phoneStr +'&username='+ userStr, function(result){
		ShowMengceng(result, 0);
	});
}

// 发送短信验证码表单检测
function SendPhoneForm(){
	try {
		if (SYS_verCodeMode == 20){
			if ($("#geePopDiv input[name='geetest_challenge']").val() == "") {
				alert('请点击验证码按钮进行验证');return false;
			}
		}else{
			if ($id("verCodePop").value==""){alert("验证码不能为空.");$id("verCodePop").focus();return false;}
		}
	}catch (e){}

	AjaxPostDeal('phoneForm');
	return false;
}


// 签到
function QiandaoDeal(){
	AjaxGetDeal(webPathPart +'plugin_deal.php?mudi=qiandao');
}


// 密码加密
function EncPwdData(pwdName){
	if ($id(pwdName).value == $id('pwdEnc').value){ return false; }
	$.ajaxSetup({cache:false, async:false});
	$.get(webPathPart +"read.php?mudi=encPwd&str="+ base64encode($id(pwdName).value) +"&exp=35", function(result){
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

// 会员退出
function UserExit(){
	if (confirm('您确定要退出？')==true){
		document.location.href = webPathPart +'users_deal.php?mudi=exit&backURL='+ encodeURIComponent(document.location.href);
	}
}

// 评论/留言 踩顶+1
function UserVote(type, id, num){
	AjaxGetDeal(webPathPart +'deal.php?mudi=userVote&type='+ type +'&dataID='+ id +'&selItem='+ num);
}