window.onload=function(){WindowHeight(0)}


// 检测表单
function CheckForm(form){
	if ($id('theme').value == ""){
		alert("标题不能为空.");$id('theme').focus();return false;
	}
	if ($id('content').value == ""){
		alert("项目内容不能为空.");$id('content').focus();return false;
	}
	if ($id('startTime').value == ""){
		alert("请选择投票开始时间.");$id('startTime').focus();return false;
	}
}

