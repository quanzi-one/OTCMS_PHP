window.onload=function(){WindowHeight(0)}


// 检查表单
function CheckDealForm(){
	if ($id('webType').value == ""){alert("请选择类型");$id('webType').focus();return false}
	if ($id('ip').value == ""){alert("请输入IP地址");$id('ip').focus();return false}
}