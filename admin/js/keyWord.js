window.onload=function(){WindowHeight(0)}


// 检测表单
function CheckForm(){
	if ($id('theme').value==""){alert("关键字不能为空.");$id('theme').focus();return false}
	if ($id('webURL').value=="" || $id('webURL').value=="http://"){alert("链接地址不能为空.");$id('webURL').focus();return false}
	return true
}
