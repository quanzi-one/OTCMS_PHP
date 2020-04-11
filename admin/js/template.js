$(function (){

	WindowHeight(0);

});


// 检测表单
function CheckForm(){
	if ($id('theme').value==""){alert("名称不能为空！");$id('theme').focus();return false}
	return true
}


// 检测文件表单
function CheckFileForm(){
	var re= new RegExp("\.(htm|html|shtm|shtml|css|js|txt|xml|wml)","ig");   
	if ( ! re.test($id('fileName').value)){alert("文件名扩展名不对，当前仅允许htm|html|shtm|shtml|css|js|txt|xml|wml");return false;}
}
