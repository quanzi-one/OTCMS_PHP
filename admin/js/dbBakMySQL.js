window.onload=function(){ WindowHeight(0); }


// 备份数据库表单
function BackupForm(){

}


// 是否打开数据库表列表
function CheckBakType(){
	if ($id("mode_diy").checked){
		$id("showTable").style.display = "";
	}else{
		$id("showTable").style.display = "none";
	}
	WindowHeight(20);
}


// 全选
function CheckAllSel(){
	for (i=0; i<$name("selTable[]").length; i++){
		$name("selTable[]")[i].checked = true;
	}
}


// 反选
function CheckRevSel(){
	for (i=0; i<$name("selTable[]").length; i++){
		if ($name("selTable[]")[i].checked){
			$name("selTable[]")[i].checked = false;
		}else{
			$name("selTable[]")[i].checked = true;
		}
	}
}
