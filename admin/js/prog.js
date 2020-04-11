window.onload=function(){WindowHeight(0)}


// 检测主菜单表单
function CheckMainMenuForm(){
	if ($id('theme').value==""){alert("名称不能为空！");$id('theme').focus();return false}
	if ($id('rank').value==""){alert("排序不能为空！");$id('rank').focus();return false}
	return true
}


// 检测子菜单表单
function CheckMenuForm(){
	if ($id('fileID').value == ""){alert("请先引用菜单文件");$id('fileID').focus();return false}
	if ($id('theme').value == ""){alert("子菜单名不能为空");$id('theme').focus();return false}
//	if ($id('getMudi').value == ""){alert("GET参数mudi不能为空");$id('getMudi').focus();return false}
	return true
}

// 菜单文件数据发送
function MenuFileSend(){
	DataDeal.location.href="prog_deal.php?mudi=menuFileDataSend&dataID="+ $id("fileID").value;
}


// 读取菜单文件 演示网址
function LoadExaUrl(){
	$id('example').value = $id('fileName').value +"?mudi="+ $id('getMudi').value +"&dataType=类别名&dataTypeCN="+ $id('theme').value.replace("新增","").replace("管理","").replace("添加","").replace("维护","");
}