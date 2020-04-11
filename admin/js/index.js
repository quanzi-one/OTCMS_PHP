// 改变验证码
function ChangeCode(){
	try {
		$id("showcode").src="inc/VerCode/VerCode"+ SYS_verCodeMode +".php?mudi="+ Math.random();
		$id("verCode").value = "";
		$id("verCode").focus();
	}catch (e) {}
}

// 点击验证码框获取验证码
function GetVerCode(str){
	try {
		if ($id("showVerCode").innerHTML.lastIndexOf('VerCode')==-1){
			$id("showVerCode").innerHTML = "<img id='showcode' src='inc/VerCode/VerCode"+ SYS_verCodeMode +".php?mudi="+ Math.random() +"' align='top' style='cursor:pointer;' onclick='ChangeCode()' alt='点击更换' />";	
		}else if (str == "change"){
			ChangeCode();
		}
	}catch (e) {}
}


// 登录检测
function CheckLoginForm(){
	if ($id("username").value == ""){alert("用户名不能为空.");$id("username").focus();return false;}
	if ($id("userpwd").value == ""){alert("密码不能为空.");$id("userpwd").focus();return false;}
	try {
		if (SYS_verCodeMode == 20){
			if ($("input[name='geetest_challenge']").val() == "") {
				alert('请点击验证码按钮进行验证');return false;
			}
		}else{
			if ($id("verCode").value == ""){alert("验证码不能为空.");$id("verCode").focus();return false;}
		}
	}catch (e) {}

	EncPwdData('userpwd');

	return true;
}
