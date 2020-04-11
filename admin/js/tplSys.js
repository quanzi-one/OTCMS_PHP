$(function (){
	try {
		$('.newsListImg label span').css({"position":"relative"})
		$('.newsListImg label span img').css({"display":"none","position":"absolute","left":"-50px","top":"22px"})
		$('.newsListImg label').mouseover(function (){
			$(this).find('img').css({"display":""})
			$(this).hover(function (){
				
			},function (){
				$(this).find('img').css({"display":"none"})
			});
		});
	}catch (e) {}

	$('.flashTrun label span,.itemMode label span').css({"position":"relative"})
	$('.flashTrun label span img').css({"display":"none","position":"absolute","left":"-75px","top":"22px"})
	$('.itemMode label span img').css({"display":"none","position":"absolute","left":"-60px","top":"22px"})
	$('.flashTrun label,.itemMode label,.verCodeBox').mouseover(function (){
		$(this).find('img').css({"display":""})
		$(this).hover(function (){
			
		},function (){
			$(this).find('img').css({"display":"none"})
		});
	});

	CheckStati();
	CheckTopLogoMode();
	CheckFlashLogo();

	CheckNavWidthMode();
	CheckNavMode();
	CheckNavNum();

	CheckHomeFlashMode();
	CheckHomeAnnoun();
	CheckHomeHot();
	CheckHomeNew();
	CheckHomeRecom();
	CheckHomeMarImg();
	CheckHomeMessage();
	CheckHomeReply();
	try{
		CheckHomeNewUsers();
		CheckHomeRankUsers();
	}catch (e){}

	try{
		CheckHomeQiandao();
		CheckQiandaoRank();
	}catch (e){}

	try{
		CheckHomeBbs();
	}catch (e){}

	try {
		CheckTopAd();
		CheckSearchArea(0);
	}catch (e) { }

	CheckSubWeb404();
	CheckMark();
	CheckMessage();

	WindowHeight(0);setTimeout("WindowHeight(0)",500);

});

// 检测表单
function CheckForm(){
	try{
		if ($id('searchArea5').checked && $id('searchArea5_addi2').value==""){
			alert("开启百度站内搜索项，搜索引擎ID不能为空");$id('searchArea5_addi2').focus();return false;
		}
	}catch (e){}

}


// 检测统计代码框
function CheckStati(){
	if ($id('isStati1').checked){
		$id("statiBox").style.display="";
	}else{
		$id("statiBox").style.display="none";
	}
	WindowHeight(0);
}


// 检测页头logo模式
function CheckTopLogoMode(){
	$id("logoExtBox").style.display="none";
	$id("logoBox").style.display="none";
	$id("fullLogoBox").style.display="none";
	if ($id('topLogoMode1').checked){
		$id("logoExtBox").style.display="";
		$id("fullLogoBox").style.display="";
	}else if ($id('topLogoMode2').checked){
		$id("logoExtBox").style.display="";
		$id("logoBox").style.display="";
	}
	WindowHeight(0);
}


// 检查是否开启flash型logo
function CheckFlashLogo(){
	if ($id('logoExt').checked){
		$id('flashLogoBox').style.display="";
		$id('logoFontSpan1').innerHTML="flash"
		$id('logoFontSpan2').innerHTML="flash"
		$id('logoFontBtn1').value="上传flash"
		$id('logoFontBtn2').value="上传flash"
	}else{
		$id('flashLogoBox').style.display="none";
		$id('logoFontSpan1').innerHTML="图片"
		$id('logoFontSpan2').innerHTML="图片"
		$id('logoFontBtn1').value="上传图片"
		$id('logoFontBtn2').value="上传图片"
	}
}


// 检查是否开启导航下面文字广告
function CheckTopAd(){
	if ($id('isTopAd0').checked){
		$id('topAdBox').style.display="none";
	}else{
		$id('topAdBox').style.display="";
	}
	WindowHeight(0);
}


// 检测导航条类型
function CheckNavWidthMode(){
	if ($id('navWidthMode1').checked){
		$id('jieriBgBox').style.display="";
	}else{
		$id('jieriBgBox').style.display="none";
	}
	WindowHeight(0);
}



// 检测导航模式
function CheckNavMode(){
	if ($id('navMode').value==0){
		$id("navMode0").style.display="";
		$id("navMode1").style.display="none";
	}else{
		$id("navMode0").style.display="none";
		$id("navMode1").style.display="";
	}
	WindowHeight(0);
}


// 检测导航数量
function CheckNavNum(){
	if ($id('navNum').value==0){
		$id("navPaddBox").style.display="";
	}else{
		$id("navPaddBox").style.display="none";
	}
}

// 检查页头搜索栏
function CheckSearchArea(num){
	if (num==0){
		for (var num=1; num<=6; num++){
			if ($id('searchArea'+ num).checked){
				$id("searchArea"+ num +"Box").style.display="";
			}else{
				$id("searchArea"+ num +"Box").style.display="none";
			}
		}
	}else{
		if ($id('searchArea'+ num).checked){
			$id("searchArea"+ num +"Box").style.display="";
		}else{
			$id("searchArea"+ num +"Box").style.display="none";
		}
	}
}


// 检测首页幻灯片
function CheckHomeFlashMode(){
	if ($id('homeFlashMode0').checked){
		$id("homeFlashBox").style.display="none";
	}else{
		$id("homeFlashBox").style.display="";
	}
	if ($id('homeFlashMode5').checked){
		$id("flashThemeBox").style.display="none";
	}else{
		$id("flashThemeBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页公告栏目
function CheckHomeAnnoun(){
	if ($id('isHomeAnnoun0').checked){
		$id("homeAnnounBox").style.display="none";
	}else{
		$id("homeAnnounBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页热门文章
function CheckHomeHot(){
	if ($id('isHomeHot0').checked){
		$id("homeHotBox").style.display="none";
	}else{
		$id("homeHotBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页最新消息
function CheckHomeNew(){
	if ($id('isHomeNew1').checked){
		$id("homeNewBox").style.display="";
	}else{
		$id("homeNewBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测首页精彩推荐
function CheckHomeRecom(){
	if ($id('isHomeRecom0').checked){
		$id("homeRecomBox").style.display="none";
	}else{
		$id("homeRecomBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页滚动图片
function CheckHomeMarImg(){
	if ($id('isHomeMarImg1').checked){
		$id("homeMarImgBox").style.display="";
	}else{
		$id("homeMarImgBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测首页最新留言
function CheckHomeMessage(){
	if ($id('isHomeMessage0').checked){
		$id("homeMessageBox").style.display="none";
	}else{
		$id("homeMessageBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页最新评论
function CheckHomeReply(){
	if ($id('isHomeReply0').checked){
		$id("homeReplyBox").style.display="none";
	}else{
		$id("homeReplyBox").style.display="";
	}
	WindowHeight(0);
}

// 检测最新会员排行
function CheckHomeNewUsers(){
	if ($id('isHomeNewUsers0').checked){
		$id("homeNewUsersBox").style.display="none";
	}else{
		$id("homeNewUsersBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页会员积分排行
function CheckHomeRankUsers(){
	if ($id('isHomeRankUsers0').checked){
		$id("homeRankUsersBox").style.display="none";
	}else{
		$id("homeRankUsersBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页签到窗口
function CheckHomeQiandao(){
	if ($id('isHomeQiandao0').checked){
		$id("homeQiandaoBox").style.display="none";
	}else{
		$id("homeQiandaoBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页签到排行
function CheckQiandaoRank(){
	if ($id('isQiandaoRank0').checked){
		$id("qiandaoRankBox").style.display="none";
	}else{
		$id("qiandaoRankBox").style.display="";
	}
	WindowHeight(0);
}

// 检测首页最新论坛帖子
function CheckHomeBbs(){
	if ($id('isHomeBbs0').checked){
		$id("homeBbsBox").style.display="none";
	}else{
		$id("homeBbsBox").style.display="";
	}
	WindowHeight(0);
}


// 检查列表页错误跳转
function CheckSubWeb404(){
	var checkedJud=false;
	for (var i=0; i<$name('subWeb404').length; i++){
		if ($name('subWeb404')[i].checked){
			checkedJud=true;break;
		}
	}
	if (checkedJud==false){
		$id('subWeb404Other').checked=true;
	}
	if ($id('subWeb404Other').checked==true){
		$id('subWeb404NewBox').style.display='';
		if ($id('subWeb404New').value==""){ $id('subWeb404New').value="other.html"; }
	}else{
		$id('subWeb404NewBox').style.display='none';
	}
}

// 检测标签页
function CheckMark(){
	if ($id('isMark0').checked){
		$id("markBox").style.display="none";
	}else{
		$id("markBox").style.display="";
	}
	WindowHeight(0);
}

// 检测是否开启留言板
function CheckMessage(){
	if ($id("isMessage0").checked){
		$id("messageBox").style.display="none";
		$id("messageCloseBox").style.display="";
	}else{
		$id("messageBox").style.display="";
		$id("messageCloseBox").style.display="none";
	}
	WindowHeight(0);
}