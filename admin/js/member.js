$(function (){
	WindowHeight(0);
});

//添加用户表单：输入默认密码
function ResetPwd(){
	alert("默认密码：123456");
	$id('userpwd').value = "123456";
}

//检测添加用户表单
function CheckForm(){
	if ($id('username').value == ""){alert("用户名不能为空");$id('username').focus();return false}
		if ($id('userMode').value != "rev" || $id('userpwd').value != ""){
	if ($id('userpwd').value.length < 6){alert("密码长度不能小于6位");$id('userpwd').focus();return false}
		}
	if ($id('realname').value == ""){alert("称呼不能为空");$id('realname').focus();return false}
	if ($id('groupID').value == ""){alert("请选择用户组");$id('groupID').focus();return false}
		
	if ($id('userMode').value == "rev"){
		if (confirm("您确定修改？")==false){return false}
	}
}