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
	if (lang["isMoreDelDeal"] == undefined){
		moreDelStr = "你确定要进行批量删除？";
	}else{
		moreDelStr = lang["isMoreDelDeal"];
	}
	if (confirm(moreDelStr)==false){
		return false;
	}
}
