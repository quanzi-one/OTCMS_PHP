window.onload=function(){
	try{
		CheckAppType();
	}catch (e){}
	WindowHeight(0);
}


//检测表单
function CheckForm(){
	if ($id('appType').value != 'wxmp'){
		if ($id('appId').value == ""){alert("请输入接口ID");$id('appId').focus();return false}
		if ($id('appKey').value == ""){alert("请输入接口KEY");$id('appKey').focus();return false}
	}
	return true;
}


function CheckAppType(){
	if ($id('appType').value == 'wxmp'){
		$id('appKeyBox').style.display = 'none';
		$id('wxmpBox').style.display = '';
	}else{
		$id('appKeyBox').style.display = '';
		$id('wxmpBox').style.display = 'none';
	}
}