$(function (){
	try {
		CheckMode();
		CheckIsTitle();
		$('.newsListImg label span').css({"position":"relative"})
		$('.newsListImg label span img').css({"display":"none","position":"absolute","left":"-50px","top":"22px"})
		$('.newsListImg label #showMode_5 + span img').css({"left":"-150px"})
		$('.newsListImg label #showMode_6 + span img').css({"left":"-230px"})
		$('.newsListImg label #homeIco + span img').css({"top":"-230px","left":"0px"})
		$('.newsListImg label').mouseover(function (){
			$(this).find('img').css({"display":""})
			$(this).hover(function (){
				
			},function (){
				$(this).find('img').css({"display":"none"})
			});
		});
	}catch (e) {}

	try {
		CheckFatID();
		CheckHomeItme();
		CheckWapItme();
	}catch (e) {}

	WindowHeight(0);

});

// 检查加粗
function CheckThemeB(){
	if ($id("themeB").checked){
		$id("theme").style.fontWeight="bold";
	}else{
		$id("theme").style.fontWeight="normal";
	}
}

// 检测栏目文章总数
function CheckInfoCount(){
	for (var i=0; i<$name("selDataID[]").length; i++){
		try {
			if ($name("selDataID[]")[i].value==-1){
				$id("infoType_1").innerHTML="<img src='images/onload.gif' />";
			}else{
				$id("infoType"+ $name("selDataID[]")[i].value).innerHTML="<img src='images/onload.gif' />";
			}
		}catch (e) {}
	}
	AjaxGetDeal("readDeal2.php?mudi=checkInfoCount");
}


// 是否开启自定义网页title
function CheckIsTitle(){
	try{
		if ($id('isTitle').checked){
			$id('titleAddiName').innerHTML = "<span style='color:red;'>*</span>自定义网页title";
		}else{
			$id('titleAddiName').innerHTML = "栏目名称附加内容";
		}
	}catch (e){}
}


// 检测模式
function CheckMode(){
	try {
		$id('itemBox').style.display="none";
		$id('urlBox').style.display="none";
		$id('webBox').style.display="none";
		$id('topicBox').style.display="none";
		$id('taobaokeBox').style.display="none";
		$id('idcProBox').style.display="none";
		if ($id('mode').value=='web'){
			$id('webBox').style.display="";
		}else if ($id('mode').value=='topic'){
			$id('topicBox').style.display="";
		}else if ($id('mode').value=='taobaoke'){
			$id('taobaokeBox').style.display="";
		}else if ($id('mode').value=='idcPro'){
			$id('idcProBox').style.display="";
		}else if ($id('mode').value=='url'){
			$id('urlBox').style.display="";
		}else if ($id('mode').value=='item'){
			$id('itemBox').style.display="";
		}else if ($id('mode').value=='urlHome'){
			if ( ("||留言板|论坛|积分商城|领工资|签到|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="网站首页"; }
		}else if ($id('mode').value=='urlMessage'){
			if ( ("||网站首页|论坛|积分商城|领工资|签到|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="留言板"; }
		}else if ($id('mode').value=='urlBbs'){
			if ( ("||网站首页|留言板|积分商城|领工资|签到|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="论坛"; }
		}else if ($id('mode').value=='urlGift'){
			if ( ("||网站首页|留言板|论坛|领工资|签到|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="积分商城"; }
		}else if ($id('mode').value=='urlUserWork'){
			if ( ("||网站首页|留言板|论坛|积分商城|签到|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="领工资"; }
		}else if ($id('mode').value=='urlQiandao'){
			if ( ("||网站首页|留言板|论坛|积分商城|领工资|").indexOf("|"+ $id('theme').value +"|") != -1 ){ $id('theme').value="签到"; }
		}
	}catch (e) {}
	WindowHeight(0);
}

// 检查栏目所属
function CheckFatID(){
	if ($id('fatID').value==""){
		$id('subMenuBox').style.display="none";
	}else{
		$id('subMenuBox').style.display="";
	}
	WindowHeight(0);
}


// 检测表单
function CheckForm(){
	if ($id('theme').value==""){alert("栏目名称不能为空！");$id('theme').focus();return false;}
	if ($id('dataID').value==$id('fatID').value){
		alert("栏目所属不能选择自己作为父类");$id('fatID').focus();return false;
	}
	if ($id('fatID').value!="" && ($id('showMode_5').checked || $id('showMode_6').checked)){
		alert("非顶级分类，列表页显示模式不允许选择[分类列表][分类列表2]");$id('showMode_1').focus();return false;
	}

	try {
		if ($id('isTitle').checked){
			if ($id('titleAddi').value == ""){alert("自定义网页title不能为空");$id('titleAddi').focus();return false;}
		}
	}catch (e) {}

	if ($id('mode').value=='web'){
		if ($id('webID').value==""){alert("请选择单篇页！");$id('webID').focus();return false;}
	}else if ($id('mode').value=='topic'){
		if ($id('topicID').value==""){alert("请选择专题！");$id('topicID').focus();return false;}
	}else if ($id('mode').value=='taobaoke'){
		if ($id('taobaokeID').value==""){alert("请选择淘客栏目！");$id('taobaokeID').focus();return false;}
	}else if ($id('mode').value=='idcPro'){
		if ($id('idcProID').value==""){alert("请选择IDC产品类别！");$id('idcProID').focus();return false;}
	}else if ($id('mode').value=='url'){
		if ($id('webURL').value=="" || $id('webURL').value=="http://"){alert("请输入外部链接！");$id('webURL').focus();return false;}
	}else if ($id('mode').value=='item'){
		if ($id('htmlInfoTypeDir').value==1){
			if ($id('htmlName').value==""){
				alert("静态目录名不能为空。");$id('htmlName').focus();return false;
			}
		}
		if ($id('htmlName').value!=""){
			var htmlNameNoStr = "|admin|cache|inc|inc_img|inc_temp|install|js|news|temp|template|tools|upFiles|announ|new|";
			if (htmlNameNoStr.indexOf('|'+ $id('htmlName').value +'|')!=-1){
				alert("静态目录名不能起跟程序目录会冲突的名称，请更换个。");$id('htmlName').focus();return false;
			}
		}
	}

	try {
		if ($id('template').value!=""){
			if ($id('template').value.substr(0,4)!="list"){
				alert("电脑版模板的文件名必须以list开头的，如list22.html、listTwo.html");$id('template').focus();return false;
			}
			if ($id('template').value.substr($id('template').value.length-5)!=".html"){
				alert("电脑版模板的文件名必须以.html结尾");$id('template').focus();return false;
			}
		}
	}catch (e) {}

	try {
		if ($id('htmlName').value!=""){
			var Regx = /^[A-Za-z]*$/;
			if (! Regx.test($id('htmlName').value.substr(0,1))) {
				alert("静态目录名第一个字符必须是字母");$id('htmlName').focus();return false;
			}
		}
	}catch (e) {}

	return true;
}


// 显示/隐藏二级栏目
function CheckInfoType(num){
	var displayStr="";
	if (num==2){
		if ($('#infoType2Btn').val()=="隐藏二级栏目"){ displayStr="none"; $('#infoType2Btn').val('显示二级栏目'); }else{ $('#infoType2Btn').val('隐藏二级栏目'); }
		
	}else if (num==3){
		if ($('#infoType3Btn').val()=="隐藏三级栏目"){ displayStr="none"; $('#infoType2Btn').val('显示三级栏目'); }else{ $('#infoType3Btn').val('隐藏三级栏目'); }
		
	}

	$('.infoType2').css("display",displayStr);

	WindowHeight(0);
}

// 检查首页栏目
function CheckHomeItme(){
	if ($id('isHome1').checked){
		$('.homeItemClass').css('display','');
	}else{
		$('.homeItemClass').css('display','none');
	}
	WindowHeight(0);
}

// 检查WAP首页栏目
function CheckWapItme(){
	if ($id('isWapHome1').checked){
		$('.wapItemClass').css('display','');
	}else{
		$('.wapItemClass').css('display','none');
	}
	WindowHeight(0);
}


// 批量设置
function MoreUpdateHtmlDir(str){
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要更新的栏目.');return false;
	}

	if (confirm("你确定要更新该"+ selNum +"个栏目所属文章的静态目录名？")==true){
		$id('listForm').action="infoType_deal.php?mudi="+ str;
		$id('listForm').target="DataDeal";
		$id('listForm').submit();
	}
	
}


// 批量设置
function MoreCountInfo(){
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要统计的栏目文章.');return false;
	}

	if (confirm("你确定要统计该"+ selNum +"个栏目文章？")==true){
		$id('listForm').action="infoType_deal.php?mudi=moreCountInfo";
		$id('listForm').target="DataDeal";
		$id('listForm').submit();
	}
	
}
