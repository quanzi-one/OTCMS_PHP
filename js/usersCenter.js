$(function (){
	$('.tabBody tr').mouseover(function (){
		$(this).addClass('tabColorTrOver');
	});
	$('.tabBody tr').mouseout(function (){
		$(this).removeClass('tabColorTrOver');
	});

	try{
		$("#dashangImg1").mouseover(function() {
			}).hover(function() { 
				$('#img1View').css({"display":""});
				if (IsAbsUrl($id('dashangImg1').value)){
					$id('img1View').src=$id('dashangImg1').value;
				}else{
					$id('img1View').src=$id('imgDir').value + $id('dashangImg1').value;
				}
			}, function(){
				$('#img1View').css({"display":"none"});
		});
		$("#dashangImg2").mouseover(function() {
			}).hover(function() { 
				$('#img2View').css({"display":""});
				if (IsAbsUrl($id('dashangImg2').value)){
					$id('img2View').src=$id('dashangImg2').value;
				}else{
					$id('img2View').src=$id('imgDir').value + $id('dashangImg2').value;
				}
			}, function(){
				$('#img2View').css({"display":"none"});
		});
		$("#dashangImg3").mouseover(function() {
			}).hover(function() { 
				$('#img3View').css({"display":""});
				if (IsAbsUrl($id('dashangImg3').value)){
					$id('img3View').src=$id('dashangImg3').value;
				}else{
					$id('img3View').src=$id('imgDir').value + $id('dashangImg3').value;
				}
			}, function(){
				$('#img3View').css({"display":"none"});
		});
	}catch (e){}
});


// 修改信息_类型检测
function CheckRevInfoType(){
	for (var i=0; i<$name("revType").length; i++){
		if ($name("revType")[i].checked==true){
			$id($name("revType")[i].value +"Box").style.display="";
		}else{
			$id($name("revType")[i].value +"Box").style.display="none";
		}
	}
}

// 修改信息_检测表单
function CheckRevForm(){
	var selValue = "";
	for (var i=0; i<$name("revType").length; i++){
		if ($name("revType")[i].checked==true){
			selValue = $name("revType")[i].value;
		}
	}
	if (selValue==""){
		alert("请先选择修改类型.");$name("revType")[0].focus();return false;
	}

	if (selValue=="info"){
		if ($id("realname_must")){
			if ($id("realname_must").value==1 && $id("realname").value==""){alert("昵称不能为空！");$id("realname").focus();return false;}
		}
		if ($id("sex_must")){
			if ($id("sex_must").value==1 && $id("sex1").checked==false && $id("sex0").checked==false){alert("请选择性别！");$id("sex1").focus();return false;}
		}
		if ($id("address_must")){
			if ($id("address_must").value==1 && $id("address").value==""){alert("收货地址不能为空！");$id("address").focus();return false;}
		}
		if ($id("postCode_must")){
			if ($id("postCode_must").value==1 && $id("postCode").value==""){alert("邮编不能为空！");$id("postCode").focus();return false;}
		}
		if ($id("qq_must")){
			if ($id("qq_must").value==1 && $id("qq").value==""){alert("QQ不能为空！");$id("qq").focus();return false;}
		}
		if ($id("weixin_must")){
			if ($id("weixin_must").value==1 && $id("weixin").value==""){alert("微信不能为空！");$id("weixin").focus();return false;}
		}
		if ($id("alipay_must")){
			if ($id("alipay_must").value==1 && $id("alipay").value==""){alert("支付宝不能为空！");$id("alipay").focus();return false;}
		}
		if ($id("ww_must")){
			if ($id("ww_must").value==1 && $id("ww").value==""){alert("旺旺不能为空！");$id("ww").focus();return false;}
		}
		if ($id("web_must")){
			if ($id("web_must").value==1 && $id("web").value==""){alert("个人主页不能为空！");$id("web").focus();return false;}
		}
		if ($id("note_must")){
			if ($id("note_must").value==1 && $id("note").value==""){alert("备注不能为空！");$id("note").focus();return false;}
		}

	}else if (selValue=="username"){
		if ($id("isRevUsername").value=="0"){alert("不允许修改用户名");return false;}

	}else if (selValue=="password"){
		try {
			if ($id("userpwdOld").value==""){alert("原密码不能为空！");$id("userpwdOld").focus();return false;}
		}catch (e) {}
		if ($id("userpwd").value==""){alert("新密码不能为空！");$id("userpwd").focus();return false;}
		if ($id("userpwd").value.length<6){alert("新密码长度不能少于6位！");$id("userpwd").focus();return false;}
		if ($id("userpwd").value!=$id("userpwd2").value){alert("两次密码不一致！");$id("userpwd2").focus();return false;}

	}else if (selValue=="mail"){
		if ($id("isAuthMail").value=="1"){
			if ($id("mail").value==""){alert("当前邮箱不能为空！");$id("mail").focus();return false;}
			if (! IsMail($id("mail").value)){alert("当前邮箱格式错误！");$id("mail").focus();return false;}
			if ($id("mailCode").value==""){alert("邮件验证码不能为空！");$id("mailCode").focus();return false;}
		}else{
			try{
				if ($id("mailOldCode").value==""){alert("当前邮件验证码不能为空！");$id("mailOldCode").focus();return false;}
			}catch (e){}
			if ($id("mail").value==""){alert("新邮箱不能为空！");$id("mail").focus();return false;}
			if (! IsMail($id("mail").value)){alert("新邮箱格式错误！");$id("mail").focus();return false;}
			try{
				if ($id("mailCode").value==""){alert("新邮件验证码不能为空！");$id("mailCode").focus();return false;}
			}catch (e){}
		}
		if ($id("mailPwd").value==""){alert("登录密码不能为空！");$id("mailPwd").focus();return false;}

	}else if (selValue=="phone"){
		if ($id("isAuthPhone").value=="1"){
			if ($id("phone").value==""){alert("当前手机不能为空！");$id("phone").focus();return false;}
			if (! IsPhone($id("phone").value)){alert("当前手机格式错误！");$id("phone").focus();return false;}
			if ($id("phoneCode").value==""){alert("短信验证码不能为空！");$id("phoneCode").focus();return false;}
		}else{
			try{
				if ($id("phoneOldCode").value==""){alert("当前短信验证码不能为空！");$id("phoneOldCode").focus();return false;}
			}catch (e){}
			if ($id("phone").value==""){alert("新手机不能为空！");$id("phone").focus();return false;}
			if (! IsPhone($id("phone").value)){alert("新手机格式错误！");$id("phone").focus();return false;}
			try{
				if ($id("phoneCode").value==""){alert("新短信验证码不能为空！");$id("phoneCode").focus();return false;}
			}catch (e){}
		}
		if ($id("phonePwd").value==""){alert("登录密码不能为空！");$id("phonePwd").focus();return false;}

	}else if (selValue=="question"){
		if ($id("question").value==""){alert("密保问题不能为空！");$id("question").focus();return false;}
		if ($id("answer").value==""){alert("密保答案不能为空！");$id("answer").focus();return false;}

	}

//	if (webPathPart=="../"){ $id('revForm').action = webPathPart + $id('revForm').action; }
//	if ($id("verCode").value==""){alert("验证码不能为空！");$id("verCode").focus();return false;}
/*	if (selValue=="face"){
		$id("revForm").target = "DataDeal";
	}else{
		AjaxPostDeal("revForm");
		return false;
	} */
}


// 账号绑定-API解除绑定
function CheckApiCancel(str,strCN){
	if (confirm('确定要解除【'+ strCN +'】绑定？')){
		AjaxGetDeal(jsPathPart +"api.php?mudi=cancel&apiType="+ str);
//		var a=window.open('api.php?mudi=cancel&apiType='+ str);
	}
}

// 账号绑定-增加绑定
function AddApiUser(str){
	var a=window.open(jsPathPart +"api.php?mudi=login&dataID=-1&apiType="+ str);
}


// 提现记录
function CheckRecomMoneyForm(){
	if (ToFloat($id('money').value) == 0){
		alert("请输入要提现的金额");$id("money").focus();return false;
	}minMoney
	if ($id('mode_userMoney').checked){
		alertStr = '确定要转入到帐户余额 '+ $id('money').value +' 元？';
	}else{
		alertStr = '确定要提现到支付宝 '+ $id('money').value +' 元？\n支付宝：'+ $id('alipayMail').innerHTML +'（姓名：'+ $id('alipayUser').innerHTML +'）\n\n请认真检查 姓名和支付宝账号 是否正确？\n如支付宝账号错误导致提现错人概不负责。';
	}
	if (confirm(alertStr)){
		AjaxPostDeal("moneyForm");
		return false;
	}else{
		return false;
	}
}

// 每页显示数量
function RevUserPageNum(page){
	document.location.href = "usersCenter_deal.php?mudi=revPageNum&pageNum="+ page +"&backUrl="+ encodeURIComponent(document.location.href);
}

// 会员退出  放在js/common.js