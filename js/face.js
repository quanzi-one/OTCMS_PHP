var faceStartNum=1; faceEndNum=30;

function FaceInit(faceId,inputId){
	var faceStr = "";
	for (var i=faceStartNum; i<=faceEndNum; i++){
		faceStr += "<img src='"+ webPathPart +"inc_img/face_def/"+ i + ".gif' border='0' style='margin:1px;' class='pointer' onclick=\"FocusAddText('"+ inputId +"','[face:"+ i + "]');\" alt='[face:"+ i + "]' />";
	}
	$id(faceId).innerHTML = faceStr;
	
}

function FaceShow(faceId,inputId){
	if ($id(faceId).innerHTML==""){
		FaceInit(faceId,inputId);
		$id(faceId).style.display="";
	}else{
		if ($id(faceId).style.display==""){
			$id(faceId).style.display="none";
		}else{
			$id(faceId).style.display="";
		}
	}
}

function FaceSignToImg(innerId){
	innerStr = $id(innerId).innerHTML;
	var reg,stringObj,newStr; 

	for (var i=faceStartNum; i<=faceEndNum; i++){
		reg=new RegExp("[face:"+ i +"]","g"); //创建正则RegExp对象
		newstr=innerStr.replace(reg,"<img src='"+ webPathPart +"inc_img/face_def/"+ i + ".gif' border='0' />"); 
	}
	$id(innerId).innerHTML=newstr;
}