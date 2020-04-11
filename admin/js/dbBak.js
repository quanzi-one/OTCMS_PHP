window.onload=function(){WindowHeight(0)}


// 备份数据库表单
function BackupForm(){
	/*
	if ($id('backupName').value == ""){
		alert("请输入备份数据库名称.");$id('backupName').focus();return false;
	}
	*/
}

// 数据库压缩
function CompressForm(){
	if ($id('backupFileID').value == ""){
		alert("请选择要压缩的数据库.");$id('backupFileID').focus();return false;
	}

	return true;
}

// 数据库还原
function RestoreForm(){
	if ($id('backupName').value == ""){
		alert("请选择备份数据库路径.");$id('backupName').focus();return false;
	}

	if (confirm("你确定要进行数据库恢复？\n请确保当前正在使用中的数据库已进行备份.\n请谨慎使用该操作.")==false){ return false; }
}
