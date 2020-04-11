window.onload=function(){WindowHeight(0)}


// 检测表单
function CheckForm(){
	if ($id('dataID').value=="" || $id('dataID').value=="0"){alert("请先选择要修改的记录");return false;}
	return true
}
