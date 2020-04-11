$(function (){
	CheckUserBox();
	CheckRegBox();
	CheckLoginBox();
	CheckMissPwdBox();
	CheckScoreBox();

	try{
		CheckNewsUpImg();
		CheckNewsUpFile();
	}catch (e){}

	try{
		CheckGainScore();
	}catch (e){}

	WindowHeight(0);setTimeout("WindowHeight(0)",500);
});

// 检测表单
function CheckForm(){
	if ($id('isOnlyMail1').checked){
		if ( (! $id('regField_mail').checked) || (! $id('regField_mail2').checked) ){
			alert('启用 邮箱唯一性，注册字段集 中 邮箱和邮箱必填项必须打钩.');$id('isOnlyMail0').focus();return false;
		}
	}
	if ($id('isOnlyPhone1').checked){
		if ( (! $id('regField_phone').checked) || (! $id('regField_phone2').checked) ){
			alert('启用 手机唯一性，注册字段集 中 手机和手机必填项必须打钩.');$id('isOnlyPhone0').focus();return false;
		}
	}
	try{
		if ($id('regAuthMail1').checked){
			if ( (! $id('regField_mail').checked) || (! $id('regField_mail2').checked) ){
				alert('启用注册时 邮箱验证，注册字段集 中 邮箱和邮箱必填项必须打钩.');$id('regAuthMail0').focus();return false;
			}
		}
	}catch (e){}
	try{
		if ($id('regAuthPhone1').checked){
			if ( (! $id('regField_phone').checked) || (! $id('regField_phone2').checked) ){
				alert('启用注册时 手机验证，注册字段集 中 手机和手机必填项必须打钩.');$id('regAuthPhone0').focus();return false;
			}
		}
	}catch (e){}
	for (var i=1; i<=3; i++){
		if ($id('isScore'+ i).checked){
			if ($id('score'+ i +'Name').value==""){
				alert('积分'+ i +'的积分名称不能为空.');$id('score'+ i +'Name').focus();return false;
			}
		}
	}
}


// 检测是否开启会员系统框
function CheckUserBox(){
	if ($id("isUserSys1").checked){
		$id("userBox").style.display="";
	}else{
		$id("userBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测是否开启会员登录框
function CheckLoginBox(){
	if ($id("isLogin1").checked){
		$(".loginBoxClass").css("display","");
	}else{
		$(".loginBoxClass").css("display","none");
	}
	WindowHeight(0);
}

// 检测是否开启会员注册框
function CheckRegBox(){
	if ($id("isReg1").checked){
		$(".regBoxClass").css("display","");
	}else{
		$(".regBoxClass").css("display","none");
	}
	WindowHeight(0);
}

// 检测是否开启会员忘记密码找回框
function CheckMissPwdBox(){
	if ($id("isMissPwd1").checked){
		$(".missPwdBoxClass").css("display","");
	}else{
		$(".missPwdBoxClass").css("display","none");
	}
	WindowHeight(0);
}

// 检测文章属性
function CheckNewsUpImg(){
	if ($id('isNewsUpImg0').checked){
		$(".addiImg").attr("disabled",true)
		$id('imgSizeBox').style.display="none";
	}else{
		$(".addiImg").attr("disabled",false)
		$id('imgSizeBox').style.display="";
	}
	WindowHeight(0);
}

// 检测文章属性
function CheckNewsUpFile(){
	if ($id('isNewsUpFile0')){
		if ($id('isNewsUpFile0').checked){
			$id('fileSizeBox').style.display="none";
		}else{
			$id('fileSizeBox').style.display="";
		}
	}else{
		if ($id('isNewsUpFile').value > 0){
			$id('fileSizeBox').style.display="";
		}else{
			$id('fileSizeBox').style.display="none";
		}
	}
	WindowHeight(0);
}

// 检测积分框
function CheckScoreBox(){
	if ($id("isScore1").checked){
		$(".score1Class").css("display","");
	}else{
		$(".score1Class").css("display","none");
	}
	if ($id("isScore2").checked){
		$(".score2Class").css("display","");
	}else{
		$(".score2Class").css("display","none");
	}
	if ($id("isScore3").checked){
		$(".score3Class").css("display","");
	}else{
		$(".score3Class").css("display","none");
	}
	WindowHeight(0);
}


// 检测是否开启会员投稿积分框
function CheckGainScore(){
	if ($id("isGainScore").checked){
		$id("gainScoreBox").style.display="";
	}else{
		$id("gainScoreBox").style.display="none";
	}
	WindowHeight(0);
}


// 检测上传图片保存至 网站 原图
function CheckUpImgOri(){
	if ($id("newsUpImgOri").checked){
		if (confirm("前台上传图片保存原图，虽然可以保证图片的质量，但容易被携带木马图片，确定？\n如果网站面对各种不熟悉用户，建议不要打钩，容易被挂马") == false){
			$id("newsUpImgOri").checked = false;
		}
	}
}


// 范例
function FanLi(str){
	if (str=="recomLinkStr"){
		$id(str).value=''+
			'【单条范例】\n'+
			'<a href="{%推广链接%}" title="网钛CMS注册有好礼" target="_blank">网钛CMS注册有好礼</a>\n'+
			'\n'+
			'【多条范例】\n'+
			'<a href="{%推广链接%}" title="网钛CMS注册有好礼" target="_blank">网钛CMS注册有好礼</a>\n'+
			'[arr]\n'+
			'<a href="{%推广链接%}" title="注册得积分，积分换礼品" target="_blank">注册得积分，积分换礼品</a>';

	}else if (str=="recomImgStr"){
		$id(str).value=''+
			'【单条范例】\n'+
			'<a href="{%推广链接%}" title="香港VPS最低45元起" target="_blank"><img src="http://www.yuntaiidc.com/temp/vps/300_1.jpg" border="0" width="300"></a>\n'+
			'\n'+
			'【多条范例】\n'+
			'<a href="{%推广链接%}" title="香港VPS最低45元起" target="_blank"><img src="http://www.yuntaiidc.com/temp/vps/300_1.jpg" border="0" width="300"></a>\n'+
			'[arr]\n'+
			'<a href="{%推广链接%}" title="香港VPS最低45元起" target="_blank"><img src="http://www.yuntaiidc.com/temp/vps/300_2.jpg" border="0" width="300"></a>\n'+
			'[arr]\n'+
			'<a href="{%推广链接%}" title="香港VPS最低45元起" target="_blank"><img src="http://www.yuntaiidc.com/temp/vps/300_3.jpg" border="0" width="300"></a>';

	}else if (str=="recomFontStr"){
		$id(str).value=''+
			'【单条范例】\n'+
			'朋友介绍给我的，香港VPS高配买3送1，最低45元起，我买了台很不错，现在推荐给你，点击下面的连接即可 {%推广链接%} 如果觉得还不错的话，记得把我的这个链接发给你的朋友哦，谢谢。\n'+
			'\n'+
			'【多条范例】\n'+
			'朋友介绍给我的，香港VPS高配买3送1，最低45元起，我买了台很不错，现在推荐给你，点击下面的连接即可 {%推广链接%} 如果觉得还不错的话，记得把我的这个链接发给你的朋友哦，谢谢。\n'+
			'[arr]\n'+
			'我刚再该平台购买了台服务器，好用性价比高，买3送1，最低45元起，现在推荐给你，点击下面的连接即可 {%推广链接%}';

	}else if (str=="recomNoteStr"){
		$id(str).value=''+
			'1、通过QQ、微信、旺旺等在线聊天工具，把您的推广链接发给您的朋友，访问注册立刻成为您的线下会员！<br />\n'+
			'2、通过论坛、微博、朋友圈等交流平台，发布推广文案推广。<br />\n'+
			'3、在您的网站显眼位置放置一条广告，当客户访问注册，将自动成为您线下会员，终身分享收益！';

	}
}