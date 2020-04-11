// 初始化
$(function (){
	$('.tabBody tr').mouseover(function (){
		$(this).addClass('tabColorTrOver');
	});
	$('.tabBody tr').mouseout(function (){
		$(this).removeClass('tabColorTrOver');
	});
});


// 检查全选框
function CheckBoxAll(){
	if ($id("selAll").checked){
		for (var i=0;i<$name("selDataID[]").length;i++){
			$name("selDataID[]")[i].checked = true;
		}
	}else{
		for (var i=0;i<$name("selDataID[]").length;i++){
			$name("selDataID[]")[i].checked = false;
		}
	}
}

// 全选
function AllSelBox(){
	for (var i=0;i<$name("selDataID[]").length;i++){
		$name("selDataID[]")[i].checked = true;
	}
}

// 反选
function RevSelBox(){
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			$name("selDataID[]")[i].checked = false;
		}else{
			$name("selDataID[]")[i].checked = true;
		}
	}
}

// 检测表单
function CheckListForm(){
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要删除的记录.');return false;
	}
	if (confirm("你确定要删除这"+ selNum +"条？")==false){
		return false;
	}
	
	return true;
}
