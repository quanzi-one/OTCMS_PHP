window.onload=function(){
	try {
		CheckIsTitle();
	}catch (e) {}

	CheckMode();

	try {
		CheckWapBox();
	}catch (e) {}

	try {
		WindowHeight(0);setTimeout("WindowHeight(0)",500);
	}catch (e) {}
}


// 检查加粗
function CheckThemeB(){
	if ($id("themeB").checked){
		$id("theme").style.fontWeight="bold";
	}else{
		$id("theme").style.fontWeight="normal";
	}
}


// 是否开启自定义网页title
function CheckIsTitle(){
	if ($id('isTitle').checked){
		$id('titleAddiName').innerHTML = "<span style='color:red;'>*</span>自定义网页title";
	}else{
		$id('titleAddiName').innerHTML = "标题附加内容";
	}
}


// 检测表单
function CheckDealForm(){
	if ($id('dataID').value == "0"){alert("请选择"+ $id('dataTypeCN').value +"的名称");$id('dataID').focus();return false}
//	if ($id('theme').value == ""){alert("标题不能为空");$id('theme').focus();return false}
//	if ($id('content').value == ""){alert("内容不能为空");OTtextEditor_content.document.body.focus();return false}
	return true
}



// 检测表单
function CheckForm(){
	if ($id('theme').value==""){ alert("标题不能为空！");$id('theme').focus();return false; }
	try {
		if ($id('template').value!=""){
			if ($id('dataType').value=="topic"){
				if ($id('template').value.substr(0,4)!="list"){
					alert("电脑版模板的文件名必须以list开头的，如list22.html、listTwo.html");$id('template').focus();return false;
				}
			}else{
				if ($id('template').value.substr(0,3)!="web"){
					alert("电脑版模板的文件名必须以web开头的，如web22.html、webTwo.html");$id('template').focus();return false;
				}
			}
			if ($id('template').value.substr($id('template').value.length-5)!=".html"){
				alert("电脑版模板的文件名必须以.html结尾");$id('template').focus();return false;
			}
		}
	}catch (e) {}

	return true;
}

// 检测类别表单
function CheckTypeForm(){
	if ($id('theme').value==""){ alert("名称不能为空！");$id('theme').focus();return false; }
	if ($id('rank').value==""){ alert("排序不能为空！");$id('rank').focus();return false; }
	return true
}


// 检测模式
function CheckMode(){
	try {
		$id('urlBox').style.display="none";
		$id('webBox').style.display="none";
		if ($id('mode').value=='web'){
			$id('webBox').style.display="";
		}else if ($id('mode').value=='url'){
			$id('urlBox').style.display="";
		}else if ($id('mode').value=='sitemap'){

		}
	}catch (e) {}
	WindowHeight(0);
}


// 检测手机版框
function CheckWapBox(){
	if ($id('isWap1').checked){
		$id("wapBox").style.display="";
	}else{
		$id("wapBox").style.display="none";
	}
	WindowHeight(0);
}