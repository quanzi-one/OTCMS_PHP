window.onload=function(){WindowHeight(0)}


// 检测表单
function CheckForm(){
	if ($id('theme').value==""){alert("项目不能为空！");$id('theme').focus();return false}
	return true
}
