$(function (){
	try {
		judUserBox();
	}catch (e) {}
	WindowHeight(0);
});


//检测表单内容
function CheckForm(){
	if ($id('judUsername').checked==false && $id('judUserpwd').checked==false){
		alert("请选择其中一项修改！");$id('judUsername').focus();return false;
	}

	if ($id('judUsername').checked){
		var name=$id('username').value;
		if (name == ""){
			alert("用户名不能为空！");$id('username').focus();return false;
		}

		var nameNum=0;
		for (i=0; i<name.length; i++){
			if (name.charCodeAt(i) > 127 || name.charCodeAt(i) < 0){
				nameNum = nameNum + 2;
			}else{
				nameNum = nameNum + 1;
			}
		}
		if(nameNum < 4){
			alert("用户长度不能少于4字节,当前长度" + nameNum + "字节!\n一个汉字2字节,其他1字节!");$id('username').focus();return false;
		}
	}


	if ($id('judUserpwd').checked){
		if ($id('userpwd0').value == ""){
			alert("原密码不能为空！");$id('userpwd0').focus();return false;
		}
		if ($id('userpwd').value == ""){
			alert("新密码不能为空！");$id('userpwd').focus();return false;
		}
		if ($id('userpwd').value.length < 5){
			alert("新密码长度不能低于5位！");$id('userpwd').focus();return false;
		}
		if ($id('userpwd').value != $id('userpwd2').value){
			alert("两次密码不一致！");$id('userpwd2').focus();return false;
		}
	}

	return true;
}


// 检测启动框
function judUserBox(){
	if ($id('judUsername').checked){
		$id('usernameBox').style.display = "";
	}else{
		$id('usernameBox').style.display = "none";
	}

	if ($id('judUserpwd').checked){
		$id('userpwdBox').style.display = "";
	}else{
		$id('userpwdBox').style.display = "none";
	}
}