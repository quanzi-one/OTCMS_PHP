window.onload=function(){WindowHeight(0)}


// 检测表单
function CheckForm(){
	if ($id('theme').value==""){ alert('请输入等级名称.');$id('theme').focus();return false; }
	if ($id('img').value==""){ alert('请上传等级图片.');$id('img').focus();return false; }
}


// 设置标题样式
function DealColorBox(color){
	$id('themeStyleColor').value = color;
	CheckColorBox();
}

function CheckColorBox(){
	var styleStr="";
	if ($id('themeStyleB').checked){
		styleStr += "font-weight:bold;";
		$id('theme').style.fontWeight = "bold";
	}else{
		$id('theme').style.fontWeight = "normal";
	}

	styleStr += "color:"+ $id('themeStyleColor').value +";";
	$id('theme').style.color = $id('themeStyleColor').value;
	
	$id('themeStyle').value = styleStr;
}
