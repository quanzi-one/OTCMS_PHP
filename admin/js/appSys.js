$(function (){
	try {
		CheckAliyun();
	}catch (e) { }

	try {
		CheckQiniu();
	}catch (e) { }

	try {
		CheckUpyun();
	}catch (e) { }

	try {
		CheckFtp();
	}catch (e) { }

	WindowHeight(0);setTimeout("WindowHeight(0)",500);
});


// 检查表单
function CheckForm(){
	
}


// 检测七牛云
function CheckQiniu(){
	if ($id('isQiniu1').checked){
		$id("qiniuBox").style.display="";
	}else{
		$id("qiniuBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测又拍云
function CheckUpyun(){
	if ($id('isUpyun1').checked){
		$id("upyunBox").style.display="";
	}else{
		$id("upyunBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测阿里云OSS
function CheckAliyun(){
	if ($id('isAliyun1').checked){
		$id("aliyunBox").style.display="";
	}else{
		$id("aliyunBox").style.display="none";
	}
	WindowHeight(0);
}

// 检测FTP云存储
function CheckFtp(){
	if ($id('isFtp1').checked){
		$id("ftpBox").style.display="";
	}else{
		$id("ftpBox").style.display="none";
	}
	WindowHeight(0);
}

// 测试FTP云存储连接
function CheckFtpConn(){
	var a = window.open("appSys_deal.php?mudi=checkFtpConn&ip="+ $id('ftpIp').value +"&port="+ $id('ftpPort').value +"&user="+ $id('ftpUser').value +"&pwd="+ $id('ftpPwd').value +"");
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