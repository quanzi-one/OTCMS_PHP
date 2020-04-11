$(function (){
	$('.verCodeBox').mouseover(function (){
		$(this).find('img').css({"display":""})
		$(this).hover(function (){
			
		},function (){
			$(this).find('img').css({"display":"none"})
		});
	});

//	CheckIsUrl301();
	CheckCloseK();

	try {
		CheckVerCodeMode();
	}catch (e) {}

	try {
		CheckHtmlUrlSel();
		CheckHtmlUrlDir();

		CheckDynWebUrlMode();

		CheckHtmlInfoTypeDir2();
		CheckHtmlDatetimeDir('false');

	}catch (e) {}

	WindowHeight(0);setTimeout("WindowHeight(0)",500);

});

// 检测表单
function CheckForm(){
	if ($id('title').value == ""){ alert("网站名称不能为空");$id('title').focus();return false; }

	try{
		if ($id('htmlUrlDirBox').style.display != "none"){
			var htmlNameNoStr = "|admin|cache|inc|inc_img|inc_temp|install|js|temp|template|tools|upFiles|announ|new|message|";
			if (htmlNameNoStr.indexOf('|'+ $id('htmlUrlDir').value.toLowerCase() +'|')!=-1){
				alert("静态目录名不能起跟程序目录会冲突的名称(除news/目录外)，请更换个。");$id('htmlUrlDir').focus();return false;
			}
		}
		if ($id('diyInfoTypeDir').checked){
			if ($id('htmlInfoTypeDir1').checked == false){
				alert("[去掉静态存放目录]开启，[分栏目目录存放]项就必须开启。");
				$id('newsShowUrlMode3').checked=true;
				CheckNewsShowUrlMode();
				CheckHtmlUrlDir();
				$id('htmlInfoTypeDir1').focus();
				return false;
			}
		}
		if ($id('isWap1').checked){
			if ($id('wapUrl').value == ""){ alert("301跳转到WAP增值版已开启.\nWAP增值版网址不能为空");$id('wapUrl').focus();return false; }
		}
		
	}catch (e){}
}

// 检测网站网址模式
function CheckIsUrl301(){
	if ($id("isUrl3012").checked){
		$id("webUrlBox").style.display="none";
	}else{
		$id("webUrlBox").style.display="";
	}
	WindowHeight(0);
}

// 检测关闭网站
function CheckCloseK(){
	if ($id("isClose").value=="10"){
		$id("closeNoteK").style.display="";
	}else{
		$id("closeNoteK").style.display="none";
	}
	WindowHeight(0);
}

// 检查全站/内容页禁止复制文字
function CheckSiteNoCopy(str){
	if ($id("siteNoCopy").checked && $id("siteNoCopyShow").checked){
		if (str=="siteNoCopy"){
			$id("siteNoCopyShow").checked=false;
		}else{
			$id("siteNoCopy").checked=false;
		}
	}
}

// 检查全站变灰/首页变灰
function CheckSiteGray(str){
	if ($id("siteGray").checked && $id("siteGrayHome").checked){
		if (str=="siteGray"){
			$id("siteGrayHome").checked=false;
		}else{
			$id("siteGray").checked=false;
		}
	}
}


// 检测验证码模式
function CheckVerCodeMode(){
	if ($id('verCodeMode').value == 20){
		$(".geetestClass").css("display","");
	}else{
		$(".geetestClass").css("display","none");
	}
	WindowHeight(0);
}


// 文章路径模式
function CheckHtmlUrlSel(){
	CheckNewsListUrlMode();
	CheckNewsShowUrlMode();
	CheckDynWebUrlMode();
}


// 文章列表路径选择
function CheckNewsListUrlMode(){
	if ($id('newsListUrlMode2').checked){
		$id('newsListUrlMode2box').style.display="";
	}else{
		$id('newsListUrlMode2box').style.display="none";
	}
	if ($id('newsListUrlMode3').checked || $id('newsListUrlMode5').checked){
		$id('newsListUrlMode3box').style.display="";
	}else{
		$id('newsListUrlMode3box').style.display="none";
	}

	CheckDiyInfoTypeDir2();

	if ($id('newsListUrlMode2').checked){
		$id('newsListFileName').value="news";

		$id('newsListFileName2').innerHTML="news";

	}else if ($id('newsListUrlMode3').checked){
		$id('newsListFileName').value=$id('htmlUrlDir').value;

		$id('newsListFileName3').innerHTML=$id('newsListFileName').value;

	}else if ($id('newsListUrlMode5').checked){
		$id('newsListFileName').value="news";

		$id('newsListFileName3').innerHTML="news";

	}

	WindowHeight(0);

}

// 文章内容路径选择
function CheckNewsShowUrlMode(){
	if ($id('newsShowUrlMode2').checked){
		$id('newsShowUrlMode2box').style.display="";
	}else{
		$id('newsShowUrlMode2box').style.display="none";
	}
	if ($id('newsShowUrlMode3').checked || $id('newsShowUrlMode5').checked){
		$id('newsShowUrlMode3box').style.display="";
	}else{
		$id('newsShowUrlMode3box').style.display="none";
	}

	if ($id('newsShowUrlMode3').checked){
		$id('htmlDirBox').style.display='';
	}else{
		$id('htmlDirBox').style.display='none';
		$id('htmlInfoTypeDir0').checked=true;
		$id('htmlDatetimeDir0').checked=true;
	}
	CheckHtmlInfoTypeDir2();
	CheckHtmlDatetimeDir();

	if ($id('newsShowUrlMode2').checked){
		$id('newsShowFileName').value="news";

		$id('newsShowFileName2').innerHTML="news";

	}else if ($id('newsShowUrlMode3').checked){
		$id('newsShowFileName').value=$id('htmlUrlDir').value;

		$id('newsShowFileName3').innerHTML=$id('newsShowFileName').value;

	}else if ($id('newsShowUrlMode5').checked){
		$id('newsShowFileName').value="news";

		$id('newsShowFileName3').innerHTML="news";

	}

	WindowHeight(0);
}

// 单篇动态路径选择
function CheckDynWebUrlMode(){
	if ($id('dynWebUrlMode2').checked){
		$id('dynWebUrlMode2box').style.display="";
	}else{
		$id('dynWebUrlMode2box').style.display="none";
	}
	if ($id('dynWebUrlMode3').checked || $id('dynWebUrlMode5').checked){
		$id('dynWebUrlMode3box').style.display="";
	}else{
		$id('dynWebUrlMode3box').style.display="none";
	}

	if ($id('dynWebUrlMode2').checked){
		$id('dynWebFileName').value="news";

		$id('dynWebFileName2').innerHTML="news";

	}else if ($id('dynWebUrlMode3').checked){
		$id('dynWebFileName').value=$id('htmlUrlDir').value;

		$id('dynWebFileName3').innerHTML=$id('dynWebFileName').value;

	}else if ($id('dynWebUrlMode5').checked){
		$id('dynWebFileName').value="news";

		$id('dynWebFileName3').innerHTML="news";
	}

	WindowHeight(0);
}


function CheckDiyInfoTypeDir(){
	if ($id('diyInfoTypeDir').checked){
		AjaxGetDeal("readDeal2.php?mudi=checkDiyName");
	}
	CheckDiyInfoTypeDir2();
}
function CheckDiyInfoTypeDir2(){
	if ($id('diyInfoTypeDir').checked){
		$id('newsListInfoTypeDir').innerHTML = "[栏目目录]/index";
		$id('diyListNoBox').style.display = "none";
		$id('htmlUrlDirSpan').style.display = "none";
	}else{
		$id('newsListInfoTypeDir').innerHTML = "list_[栏目ID]";
		$id('diyListNoBox').style.display = "";
		$id('htmlUrlDirSpan').style.display = "";
	}
}


function CheckHtmlInfoTypeDir(){
	if ($id('htmlInfoTypeDir1').checked){
		htmlFileName = "";
		if (! $id('diyInfoTypeDir').checked){ htmlFileName=$id('newsShowFileName').value; }
		AjaxGetDeal("readDeal2.php?mudi=checkHtmlName&newsShowFileName="+ htmlFileName);
	}
	CheckHtmlInfoTypeDir2();
}
function CheckHtmlInfoTypeDir2(){
	if ($id('htmlInfoTypeDir1').checked){
		$id('newsShowInfoTypeDir').innerHTML = "[栏目目录]/";
		$id('diyShowNoBox').style.display = "none";
	}else{
		$id('newsShowInfoTypeDir').innerHTML = "";
		$id('diyShowNoBox').style.display = "";
	}
}


function CheckHtmlDatetimeDir(jud){
	if ( (! $id('htmlDatetimeDir0').checked) && jud != 'false'){
		AjaxGetDeal("readDeal2.php?mudi=checkHtmlDirCW&newsShowFileName="+ $id('newsShowFileName').value);
	}
	if ($id('htmlDatetimeDir10').checked){
		$id('newsShowDatetimeDir').innerHTML = "[年目录]/";
	}else if ($id('htmlDatetimeDir20').checked){
		$id('newsShowDatetimeDir').innerHTML = "[年月目录]/";
	}else if ($id('htmlDatetimeDir30').checked){
		$id('newsShowDatetimeDir').innerHTML = "[年月日目录]/";
	}else{
		$id('newsShowDatetimeDir').innerHTML = "";
	}
}

function CheckHtmlUrlDir(){
	if ($id('newsListUrlMode3').checked || $id('newsShowUrlMode3').checked || $id('dynWebUrlMode3').checked){
		$id('htmlUrlDirBox').style.display="";
		if ($id('htmlUrlDir').value==""){ $id('htmlUrlDir').value="news"; }
		RewriteHtmlUrlDir();
	}else{
		$id('htmlUrlDirBox').style.display="none";
	}
	WindowHeight(0);
}

function RewriteHtmlUrlDir(){
	if ($id('newsListUrlMode3').checked){
		$id('newsListFileName').value		= $id('htmlUrlDir').value;
		$id('newsListFileName3').innerHTML	= $id('htmlUrlDir').value;
	}
	if ($id('newsShowUrlMode3').checked){
		$id('newsShowFileName').value		= $id('htmlUrlDir').value;
		$id('newsShowFileName3').innerHTML	= $id('htmlUrlDir').value;
	}
	if ($id('dynWebUrlMode3').checked){
		$id('dynWebFileName').value			= $id('htmlUrlDir').value;
		$id('dynWebFileName3').innerHTML	= $id('htmlUrlDir').value;
	}
}


// 更新缓存时间
function UpdateCacheTime(){
	AjaxGetDealToId("system_deal.php?mudi=updateCacheTime","webCacheTime");
}

// 统计缓存文件数
function CalcCacheNum(){
	AjaxGetDealToAlert("system_deal.php?mudi=calcCacheNum");
}

// 清空缓存文件
function ClearCache(){
	alert("如果缓存文件比较多要多等点时间才会弹出结果.\n如果很久没弹出结果，可能卡了，可以重新点击。")
	AjaxGetDealToAlert("system_deal.php?mudi=clearCache");
	/*
	document.getElementById("htmlCacheMinResult").innerHTML = "(如果缓存比较多要多等等)";
	$.ajaxSetup({cache:false});
	$.get("system_deal.php?mudi=clearCache", function(result){
		document.getElementById("htmlCacheMinResult").innerHTML = result;
	});
	*/
	return false;

}

// 测试 代理IP
function CheckProxyIp(){
	if ($id('proxyIpList').value == ""){ alert("代理IP列表不能为空");$id('proxyIpList').focus();return false; }
	AjaxGetDeal('readDeal2.php?mudi=checkProxyIp');
}