$(function (){
	try {
		$('.newsReplyImg, .fileStyleImg').css({"position":"relative"})
		$('.newsReplyImg img').css({"display":"none","position":"absolute","left":"380px","top":"-140px"})
		$('.fileStyleImg img').css({"display":"none","position":"absolute","left":"0px","top":"16px"})
		$('.newsReplyMode2Class, .fileStyleClass').mouseover(function (){
			$(this).find('img').css({"display":""})
			$(this).hover(function (){
				
			},function (){
				$(this).find('img').css({"display":"none"})
			});
		});

		CheckNewsVote();
		CheckMarkNews();
		CheckNewsReply();
		CheckShareNews();
	}catch (e) { }

	try {
		CheckPrevAndNext();
		CheckNewsReplyMode();
		CheckEventStr();
	}catch (e) { }
	try {
		CheckXiongzhang();
	}catch (e) { }

	WindowHeight(0);setTimeout("WindowHeight(0)",500);
});


// 检查表单
function CheckForm(){
	
}


// 检测内容页“分享到”代码框
function CheckShareNews(){
	if ($id('isShareNews1').checked){
		$id("shareNewsBox").style.display="";
	}else{
		$id("shareNewsBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测文章投票
function CheckNewsVote(){
	if ($id('isNewsVote1').checked){
		$id("newsVoteBox").style.display="";
	}else{
		$id("newsVoteBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测上/下一篇
function CheckPrevAndNext(){
	if ($id('prevAndNext0').checked){
		$id("prevAndNextBox").style.display="none";
	}else{
		$id("prevAndNextBox").style.display="";
	}
	WindowHeight(0);
}

// 检测相关文章
function CheckMarkNews(){
	if ($id('isMarkNews1').checked){
		$id("markNewsBox").style.display="";
	}else{
		$id("markNewsBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测文章评论
function CheckNewsReply(){
	if ($id('isNewsReply0').checked){
		$id("newsReplyBox").style.display="none";
	}else{
		$id("newsReplyBox").style.display="";
	}
	WindowHeight(0);
}

// 检查评论模式
function CheckNewsReplyMode(){
	if ($id('newsReplyMode2').checked){
		$('.newsReplyMode0Class').css('display','none');
		$('.newsReplyMode2Class').css('display','');
	}else{
		$('.newsReplyMode0Class').css('display','');
		$('.newsReplyMode2Class').css('display','none');
	}
	WindowHeight(0);
}

// 检查附加设置
function CheckEventStr(){
	if ($id('eventCopy').checked){
		$id('eventCopyBox').style.display="";
	}else{
		$id('eventCopyBox').style.display="none";
	}
	WindowHeight(0);
}

// 检查熊掌号
function CheckXiongzhang(){
	if ($id('isXiongzhang1').checked){
		$('.xiongzhangClass').css('display','');
	}else{
		$('.xiongzhangClass').css('display','none');
	}
	WindowHeight(0);
}


function InputDefShare(str){
	if (str=="shareNewsCode"){
		$id(str).value=""+
			"<!-- Baidu Button BEGIN -->\n"+
			"<div id=\"bdshare\" class=\"bdshare_t bds_tools get-codes-bdshare\">\n"+
			"	<span class=\"bds_more\">分享到：</span>\n"+
			"	<a class=\"bds_qzone\">QQ空间</a>\n"+
			"	<a class=\"bds_tsina\">新浪微博</a>\n"+
			"	<a class=\"bds_renren\">人人网</a>\n"+
			"	<a class=\"bds_kaixin001\">开心网</a>\n"+
			"	<a class=\"bds_hi\">百度空间</a>\n"+
			"	<a class=\"bds_hx\">和讯</a>\n"+
			"	<a class=\"bds_ty\">天涯社区</a>\n"+
			"	<a class=\"shareCount\"></a>\n"+
			"</div>\n"+
			"<script type=\"text/javascript\" id=\"bdshare_js\" data=\"type=tools&amp;uid=379763\" ></script>\n"+
			"<script type=\"text/javascript\" id=\"bdshell_js\"></script>\n"+
			"<script type=\"text/javascript\">\n"+
			"	document.getElementById(\"bdshell_js\").src = \"http://bdimg.share.baidu.com/static/js/shell_v2.js?t=\" + new Date().getHours();\n"+
			"</script>\n"+
			"<!-- Baidu Button END -->";
	}else if (str=="newsVoteCode"){
		$id(str).value=""+
			"<div class=\"bdlikebutton\"></div>\n"+
			"<script id=\"bdlike_shell\"></script>\n"+
			"<script>\n"+
			"var bdShare_config = {\n"+
			"	\"type\":\"large\",\n"+
			"	\"color\":\"blue\",\n"+
			"	\"uid\":\"0\",\n"+
			"	\"likeText\":\"该文章不错，谢谢分享\",\n"+
			"	\"likedText\":\"谢谢支持！\",\n"+
			"	\"share\":\"yes\"\n"+
			"};\n"+
			"document.getElementById(\"bdlike_shell\").src=\"http://bdimg.share.baidu.com/static/js/like_shell.js?t=\" + Math.ceil(new Date()/3600000);\n"+
			"</script>\n"+
			"";
	}
}

// 清空自动存储的上/下一篇信息
function ClearSavePrevNextId(){
	AjaxGetDeal("infoSys_deal.php?mudi=clear&mudi2=prevNextId");
}

// 清空自动存储相关文章信息
function ClearSaveMarkNewsId(){
	AjaxGetDeal("infoSys_deal.php?mudi=clear&mudi2=markNewsId");
}

// 创建infoContent表
function CreateTab(tabID){
	if (confirm("你确定要创建 infoContent"+ tabID +" 表？\n该操作会跳转页面，如有参数修改，请先保存再操作。")==false){
		return false;
	}
	document.location.href="infoSys_deal.php?mudi=createTab&tabID="+ tabID +"&backURL="+ encodeURIComponent(document.location.href);
}