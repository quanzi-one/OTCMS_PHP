window.onload=function(){
	WindowHeight(0);setTimeout("WindowHeight(0)",500);
}


// 检测表单
function CheckForm(){
	if ($id('num').value==""){alert("编号不能为空！");$id('num').focus();return false}
	if ($id('theme').value==""){alert("项目不能为空！");$id('theme').focus();return false}
//	if ($id('code').value==""){alert("代码不能为空！");$id('code').focus();return false}
	return true
}

function ShowCode(num){
	if ($id('code'+ num).style.display==''){
		$id('code'+ num).style.display='none';
	}else{
		$id('code'+ num).style.display='';
	}
	WindowHeight(0);
}


function DefAdContent(num){
	contStr="";
	switch (num){
		case 4:
			contStr=""+
			"<table cellpadding='0' cellspacing='0' style='border-right: #eaeaea 1px solid; border-top: #eaeaea 1px solid; margin-top: 5px; border-left: #eaeaea 1px solid; border-bottom: #eaeaea 1px solid' width='100%'>\n"+
			"	<colgroup align='middle' span='5' width='20%'>\n"+
			"	</colgroup>\n"+
			"	<tbody>\n"+
			"		<tr>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://otcms.com/news/2646.html' style='color: #2222ff' target='_blank'>网钛文章管理系统V2.2免费版下载</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://otcms.com/news/2931.html' style='color: #2222ff' target='_blank'>其他文章系统迁移到网钛系统</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://otcms.com/news/430.html' style='color: #2222ff' target='_blank'>采集教程</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' target='_blank'><strong>网站名称</strong></a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #000000' target='_blank'><b>网站名称</b></a></td>\n"+
			"		</tr>\n"+
			"		<tr>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://demo.oneti.cn/OTCMS/' style='color: red' target='_blank'>网钛文章管理系统V2.3商业版演示</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://otcms.com/news/web_11.html' style='color: red' target='_blank'>商业版程序价格和购买流程</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='http://otcms.com/news/2651.html' style='color: red' target='_blank'>免费版与商业版的功能区别</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<strong><a href='#' style='font-weight: bold' target='_blank'>网站名称</a></strong></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' target='_blank'><span style='color: #000000'><strong>网站名称</strong></span></a></td>\n"+
			"		</tr>\n"+
			"		<tr>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #b5b1b4' target='_blank'>文字广告45元/月</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #b5b1b4' target='_blank'>文字广告45元/月</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #b5b1b4' target='_blank'>文字广告45元/月</a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #000000' target='_blank'><b>网站名称</b></a></td>\n"+
			"			<td style='padding-right: 3px; padding-left: 3px; padding-bottom: 3px; padding-top: 3px'>\n"+
			"				<a href='#' style='color: #000000' target='_blank'><b>网站名称</b></a></td>\n"+
			"		</tr>\n"+
			"	</tbody>\n"+
			"</table>"+
			"";
			break;
	
	}
	SetEditorHtml("code",contStr);
}


// 显示/隐藏 停用广告位区
function CheckStopBox(){
	if ($id('adStopBox').style.display==""){
		$id('adStopStr').innerHTML = $id('adStopStr').innerHTML.replace("隐藏","显示");
		$id('adStopStr').style.color="red";
		$id('adStopBox').style.display="none";
	}else{
		$id('adStopStr').innerHTML = $id('adStopStr').innerHTML.replace("显示","隐藏");
		$id('adStopStr').style.color="green";
		$id('adStopBox').style.display="";
	}
	WindowHeight(0);
}


// 选择未添加的内置广告位
function SelAdId(){
	var selVal = $id("selAdId").value;
	if (selVal==""){ return false; }
	var valArr = selVal.split("]");
	$id("num").value = valArr[0].replace("[编号","");
	$id("theme").value = valArr[1];
	$id("selAdId").value="";
}