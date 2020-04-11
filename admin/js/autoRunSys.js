$(function (){
	CheckTimeRunBox();
	CheckSoftBakBox();
	CheckDbBakBox();
	CheckHtmlHomeBox();
	CheckHtmlListBox();
	CheckHtmlShowBox();
	CheckCollBox();

	WindowHeight(0);setTimeout("WindowHeight(0)",500);
});


// 检查表单
function CheckForm(){
	
}


// 检测定时检查
function CheckTimeRunBox(){
	if ($id('isTimeRun1').checked){
		$id("timeRunBox").style.display="";
	}else{
		$id("timeRunBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测备份程序
function CheckSoftBakBox(){
	if ($id('isSoftBak1').checked){
		$id("softBakBox").style.display="";
	}else{
		$id("softBakBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测备份数据库
function CheckDbBakBox(){
	if ($id('isDbBak1').checked){
		$id("dbBakBox").style.display="";
	}else{
		$id("dbBakBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测首页静态页
function CheckHtmlHomeBox(){
	if ($id('isHtmlHome1').checked){
		$id("htmlHomeBox").style.display="";
	}else{
		$id("htmlHomeBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测列表静态页
function CheckHtmlListBox(){
	if ($id('isHtmlList1').checked){
		$id("htmlListBox").style.display="";
	}else{
		$id("htmlListBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测内容静态页
function CheckHtmlShowBox(){
	if ($id('isHtmlShow1').checked){
		$id("htmlShowBox").style.display="";
	}else{
		$id("htmlShowBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测自动采集
function CheckCollBox(){
	if ($id('isColl1').checked){
		$id("collBox").style.display="";
	}else{
		$id("collBox").style.display="none";
	}
	WindowHeight(0);
}
