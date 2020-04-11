window.onload=function(){WindowHeight(130)}


// 检测更新占用情况 按钮
function CheckUseState(){
	AjaxGetDeal("serverFile_deal.php?mudi=upFileUseCheck");
}

// 删除占用数为0的文件 按钮
function CheckUseClear(){
	if (confirm("你确定要删除占用数为0的文件（7天前图片，占用数最后检测时间为今天）？\n\n该占用数统计精确性还在测试中，请谨慎使用。\n如发现哪里统计不正确，请及时反馈给我们网钛，以帮助我们完善程序。\n详细跟我们描述下哪里占用了图片，却没统计进去。")==true){
		document.location.href="serverFile_deal.php?mudi=upFileUseClear";
	}
}


// 点击选择文件
var isSend = true;
function SelectFile(oldFileName,filePath,str,ext){
	ext = ext.toLowerCase();
	if (isSend == false){isSend = true;return false;}
	fileMode = $id('fileMode').value;
	fileFormName = $id('fileFormName').value;
	if (fileMode=="editor"){
//		if (ext!="gif" && ext!="jpg" && ext!="jpeg" && ext!="bmp" && ext!="png"){
//			alert("非图片文件，无法插入编辑器。");return false;
//		}
		opener.document.getElementById('upImgStr').value += str +"|";
		opener.InsertStrToEditor(fileFormName,ExtHtmlStr(oldFileName,filePath + str,ext));

	}else if (fileMode=="input"){
		opener.document.getElementById(fileFormName).value=str;

	}else if (fileMode=="productImgStr"){
		opener.document.getElementById(fileFormName).value += str +"|";
		opener.MoreImgList();

	}else{
		opener.fileFormName.value = str;

	}
	window.close();
}


// 编辑器载入模式根据后缀名加载不同html
function ExtHtmlStr(oldFileName, newFilePath, fileExt){
	extPath = document.getElementById('urlPart').value +"ext/";
	switch (fileExt){
		case "bmp": case "jpg": case "jpeg": case "gif": case "png":
			retStr = "<img src='"+ newFilePath +"' border='0'>";
			break;

		case "swf": case "doc": case "xls": case "ppt": case "pdf": case "txt": case "docx": case "xlsx": case "pptx": case "rar": case "zip": case "iso": case "js": case "mdb": case "psd": case "xml":
			retStr = "<div><a href='"+ newFilePath +"' target='_blank'><img src='"+ extPath + fileExt +".gif' border='0' alt='"+ fileExt +"类型' title='"+ fileExt +"类型'>"+ oldFileName +"</a></div>";
			break;

		case "avi": case "mpeg": case "mpg": case "ra": case "rm": case "rmvb": case "mov": case "qt": case "asf": case "wmv": case "mp3": case "wma": case "wav": case "mod": case "cd": case "md": case "aac": case "mid": case "ogg": case "m4a":
			retStr = "<div><a href='"+ newFilePath +"' target='_blank'><img src='"+ extPath +"mov.gif' border='0' alt='视频音频类型' title='视频音频类型'>"+ oldFileName +"</a></div>";
			break;

		default:
			retStr = "<div><a href='"+ newFilePath +"' target='_blank'><img src='"+ extPath +"file.gif' border='0' alt='"+ fileExt +"类型' title='"+ fileExt +"类型'>"+ oldFileName +"</a></div>";

	}
	return retStr
}



// 批量选择文件
function SelectMoreFile(){
	fileMode	= $id('fileMode').value;
	fileFormName= $id('fileFormName').value;
	imgUrlPart	= document.getElementById('urlPart').value;
	var selNum = 0;
	upImgStr = "";
	editorImgStr = "";
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			data_id = $name("selDataID[]")[i].value;
			data_name = $id("data"+ data_id +"_name").value;
			data_oldName = $id("data"+ data_id +"_oldName").value;
			data_ext = $id("data"+ data_id +"_ext").value.toLowerCase();
//			if (data_ext=="gif" || data_ext=="jpg" || data_ext=="jpeg" || data_ext=="bmp" || data_ext=="png"){
				selNum ++;
				upImgStr += data_name +"|";
				editorImgStr += ExtHtmlStr(data_oldName, imgUrlPart + data_name, data_ext);
//				editorImgStr += "<img src='"+ imgUrlPart + data_name +"' border='0' />";
//			}
		}
	}

	if (selNum==0){
		alert('请先选择要批量载入的图片/文件.');return false;
	}

	opener.document.getElementById('upImgStr').value += upImgStr;
	opener.InsertStrToEditor(fileFormName,editorImgStr);

	window.close();
}
