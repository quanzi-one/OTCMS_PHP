
// 检测发表文章
var wNewsWaitTime = 0;
var wNewsCutWaitFunc = null;
function CheckNewsForm(){
	if ($id("theme").value==""){alert("标题不能为空！");$id("theme").focus();return false;}
	if ($id("typeStr").value==""){alert("请选择栏目！");$id("typeStr").focus();return false;}
	contentStr = FiltHtmlTag(GetEditorText('content'));
	if (contentStr.length<10){alert("内容不能小于10字符！");return false;}
	try {
		jsPageNum = parseInt($id("pageNum").value);
		if (isNaN(jsPageNum)){ jsPageNum=0; }
		if (jsPageNum>0 && jsPageNum<500){
			alert("自动分页字数最少500设置起");$id("pageNum").focus();return false;
		}
	}catch (e) {}
	if ($id('contentKey').value.length > 180){alert("内容摘要不能超过180个字符，当前有"+ $id('contentKey').value.length +"个字符");$id('contentKey').focus();return false}
	try{
		if ($id('imgBox').style.display=="" && $id("img").value==""){
			alert("缩略图/图片不能为空！");$id("img").focus();return false;
		}
	}catch (e){}
	try{
		for (var i=0; i<=$id('fileNum').value; i++){
			if (i > 0){
				if ($id('file'+ i).value == ""){alert("附件文件"+ i +"不能为空");$id('file'+ i).focus();return false}
			}
		}
	}catch (e){}
	try{
		if ($id('isCheckUser1').checked){
			if ($id('isScore1').value == 1 &&  ToInt($id('infoScore1').value) > 0){
				if (ToInt($id('score1').value) > ToInt($id('infoScore1').value)){
					alert("限制阅读积分【"+ $id('score1Name').value +"】不能超过该会员组限制"+ $id('infoScore1').value);
					$id('score1').value = $id('infoScore1').value; $id('score1').focus(); return false;
				}
				if (ToInt($id('cutScore1').value) > ToInt($id('infoScore1').value)){
					alert("付费阅读积分【"+ $id('score1Name').value +"】不能超过该会员组限制"+ $id('infoScore1').value);
					$id('cutScore1').value = $id('infoScore1').value; $id('cutScore1').focus(); return false;
				}
			}
			if ($id('isScore2').value == 1 &&  ToInt($id('infoScore2').value) > 0){
				if (ToInt($id('score2').value) > ToInt($id('infoScore2').value)){
					alert("限制阅读积分【"+ $id('score2Name').value +"】不能超过该会员组限制"+ $id('infoScore2').value);
					$id('score2').value = $id('infoScore2').value; $id('score2').focus(); return false;
				}
				if (ToInt($id('cutScore2').value) > ToInt($id('infoScore2').value)){
					alert("付费阅读积分【"+ $id('score2Name').value +"】不能超过该会员组限制"+ $id('infoScore2').value);
					$id('cutScore2').value = $id('infoScore2').value; $id('cutScore2').focus(); return false;
				}
			}
			if ($id('isScore3').value == 1 &&  ToInt($id('infoScore3').value) > 0){
				if (ToInt($id('score3').value) > ToInt($id('infoScore3').value)){
					alert("限制阅读积分【"+ $id('score3Name').value +"】不能超过该会员组限制"+ $id('infoScore3').value);
					$id('score3').value = $id('infoScore3').value; $id('score3').focus(); return false;
				}
				if (ToInt($id('cutScore3').value) > ToInt($id('infoScore3').value)){
					alert("付费阅读积分【"+ $id('score3Name').value +"】不能超过该会员组限制"+ $id('infoScore3').value);
					$id('cutScore3').value = $id('infoScore3').value; $id('cutScore3').focus(); return false;
				}
			}
		}
	}catch (e){}

	if (wNewsWaitTime>0){
		alert("已提交，请稍等一会儿...("+ wNewsWaitTime +")");return false;
	}

	try {
		if (SYS_verCodeMode == 20){
			if ($("input[name='geetest_challenge']").val() == "") {
				alert('请点击验证码按钮进行验证');return false;
			}
		}else{
			if ($id("verCode").value==""){alert("验证码不能为空.");$id("verCode").focus();return false;}
		}
	}catch (e){}

		wNewsWaitTime = 10;
		wNewsCutWaitFunc = window.setInterval("CutWnewsWaitTime()",1000);

	bugModeVal = 0;
	try {
		if ($id('bugMode').checked){ bugModeVal=1; }
	}catch (e) {}

//	if (webPathPart=="../"){ $id('dealForm').action = webPathPart + $id('dealForm').action; }
/*	if (bugModeVal != 1){
		AjaxPostDeal("dealForm");
		return false;
	} */
}

function CutWnewsWaitTime(){
	if (wNewsWaitTime<=0){
		window.clearInterval(wNewsCutWaitFunc);
		return false;
	}else{
		wNewsWaitTime --;
	}
}


// 清空文章表单内容
function NewsClearBtn(){
	if (confirm('确定要清空？')){
		$id('theme').value = "";
		$id('source').value = "";
		$id('writer').value = "";
		$id('typeStr').value = "";
		$id('content').value = "";
		$id('themeKey').value = "";
		$id('contentKey').value = "";
	}
}

// 删除文章
function DelNews(dataID,isAudit){
	auditStr = "";
	if (isAudit==1){
		auditStr = "\n\n删除该文章将扣除";
		if ($id('isScore1').value == 1){
			auditStr += "\n\n"+ $id('score1Name').value +"："+ $id('delScore1').value;
		}
		if ($id('isScore2').value == 1){
			auditStr += "\n\n"+ $id('score2Name').value +"："+ $id('delScore2').value;
		}
		if ($id('isScore3').value == 1){
			auditStr += "\n\n"+ $id('score3Name').value +"："+ $id('delScore3').value;
		}
	}else{
		auditStr = "";
	}
	if (confirm('你确定要删除该文章？'+ auditStr)){
		AjaxGetDeal(webPathPart +'usersNews_deal.php?mudi=del&dataID='+ dataID);
	}
	
}


// 检测重复标题
function CheckRepeatTheme(){
	themeStr = $id('theme').value;

	if (themeStr==""){
		alert("标题不能为空.");return false;
	}

	$.ajaxSetup({cache:false});
	$.get(webPathPart +"readDeal2.php?mudi=checkInfoRepeatTheme&dataID="+ $id('dataID').value +"&theme="+ encodeURIComponent(themeStr) +"", function(result){
		alert(result);
	});
	return false;

}


// 获取关键词(标签)
function GetKeyWord(type){
	themeStr = $id('theme').value;
	contentStr = FiltHtmlTag(GetEditorText('content'));

	if (themeStr==""){
		alert("获取关键词(标签)，标题不能为空.");return false;
	}

	$id('onloadThemeKey').innerHTML = "<img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />请稍等，关键词获取中...</center>";
	$.ajaxSetup({cache:false});
	$.post(webPathPart +"read.php?mudi=getKeyWord&type="+ type,"theme="+ encodeURIComponent(themeStr) +"&content="+ encodeURIComponent(contentStr), function(result){
		$id('onloadThemeKey').innerHTML = "";
		if (result=="0"){ alert("获取不到关键词(标签)，请手动添加。");return false; }
		$id('themeKey').value = result;
	});
	return false;

}


// 载入内容摘要
function ToContentKey(){
	str = FiltHtmlTag(GetEditorText('content'));
	try {
		str = LTrim(str);
	}catch (e) {}
	if (str==""){
		alert('请先填写内容');return false;
	}
	$id('contentKey').value = str.substring(0,140).replace(/\r\n/g,"") +"...";
}


// 检测属性
function CheckAddition(){
	isImgBox = false;
	try {
		if ($id('isHomeThumb').checked){ isImgBox = true; }
	}catch (e) {}
	try {
		if ($id('isThumb').checked){ isImgBox = true; }
	}catch (e) {}
	try {
		if ($id('isImg').checked){ isImgBox = true; }
	}catch (e) {}
	try {
		if ($id('isFlash').checked){ isImgBox = true; }
	}catch (e) {}

	try {
		if (isImgBox==true){
			$id('imgBox').style.display="";
		}else{
			$id('imgBox').style.display="none";
		}
	}catch (e) {}
}

// 载入缩略图/图片框
function ToImg(str){
	$id('img').value = str;
	$id('editorImgBox').innerHTML = "";
}


// 缩略图路径
function ImgThumbPath(imgStr){
	if (imgStr.indexOf('/')!=-1){
		headPart = imgStr.substring(0,imgStr.lastIndexOf("/")+1);
		return imgStr.replace(headPart,headPart +"thumb_");
	}else{
		return "thumb_"+ imgStr;
	}
}

// 获取缩略图名
function GetImgName(imgStr){
	if (imgStr.indexOf('/')!=-1){
		return imgStr.substring(imgStr.lastIndexOf("/")+1);
	}else{
		return imgStr;
	}
}


// 获取编辑器图片
function GetEditorImg(){
	selImgStr = "";
	imgPath = $id('infoFileDir').value;
	imgStr = $id('upImgStr').value;
	imgArr = imgStr.split('|');
	for (var i=0; i<imgArr.length; i++){
		if (imgArr[i] != ""){
			if (IsHttpUrl(imgArr[i])){
				imgHeadPart = "";
			}else{
				imgHeadPart = imgPath;
			}
			if (GetImgName(imgArr[i]).substr(0,6)!="thumb_"){
				selImgStr += "<div id='edImg"+ i +"' style='width:80px; height:80px; border:1px #707070 solid; overflow:hidden; margin:3px; float:left;'><img src='"+ imgHeadPart + imgArr[i] +"' width='80' class='pointer' onclick=\"ToImg('"+ imgArr[i] +"');\" alt='原图' title='原图' onerror=\"$id('edImg"+ i +"').style.display='none';\" /></div>";
				selImgStr += "<div id='edSmallImg"+ i +"' style='width:50px; height:50px; border:1px #707070 solid; overflow:hidden; margin:3px; float:left;'><img src='"+ imgHeadPart + ImgThumbPath(imgArr[i]) +"' width='50' class='pointer' onclick=\"ToImg('"+ ImgThumbPath(imgArr[i]) +"');\" alt='缩略图' title='缩略图' onerror=\"$id('edSmallImg"+ i +"').style.display='none';\" /></div>";
			}else{
				selImgStr += "<div id='edSmallImg"+ i +"' style='width:50px; height:50px; border:1px #707070 solid; overflow:hidden; margin:3px; float:left;'><img src='"+ imgHeadPart + imgArr[i] +"' width='80' class='pointer' onclick=\"ToImg('"+ imgArr[i] +"');\" alt='缩略图' title='缩略图' onerror=\"$id('edSmallImg"+ i +"').style.display='none';\" /></div>";
			}
		}
	}

	str = GetEditorHTML('content');
	retImgArr = str.match(/src\s*=\s*[\"|\']?\s*[^>\"\'\s]*\.(jpg|jpeg|png|gif|bmp)/gi);
	retImgStr = "";
	for (var i=0; i<retImgArr.length; i++){
		retImgArr[i] = retImgArr[i].replace(/src\s*=\s*[\"|\']?/,'');
		if (retImgArr[i]!="" && selImgStr.indexOf(retImgArr[i])==-1 && retImgStr.indexOf(retImgArr[i])==-1 && retImgArr[i].indexOf(imgPath)==-1){
			retImgStr += retImgArr[i] +"|";
			selImgStr += "<div id='edImg"+ i +"' style='width:80px; height:80px; border:1px #707070 solid; overflow:hidden; margin:3px; float:left;'><img src='"+ retImgArr[i] +"' width='80' class='pointer' onclick=\"ToImg('"+ retImgArr[i] +"');\" alt='远程图片' title='远程图片' onerror=\"$id('edImg"+ i +"').style.display='none';\" /></div>";
		}
	}

	if (selImgStr==""){ selImgStr="<span class='font2_2'>编辑器中无图片</span>"; }
	$id('editorImgBox').innerHTML = selImgStr;
}


// 文章管理 查询框
function CheckRefNewsForm(){
	var refUrlStr = '';
	var refNum = 0;
	if ($id('refTypeStr').value != ""){ refNum ++; refUrlStr += '&refTypeStr='+ encodeURIComponent($id("refTypeStr").value) +''; }
	if ($id('refTheme').value != ""){ refNum ++; refUrlStr += '&refTheme='+ encodeURIComponent($id("refTheme").value) +''; }
	if ($id('refSource').value != ""){ refNum ++; refUrlStr += '&refSource='+ encodeURIComponent($id("refSource").value) +''; }
	if ($id('refWriter').value != ""){ refNum ++; refUrlStr += '&refWriter='+ encodeURIComponent($id("refWriter").value) +''; }
	if (refNum == 0){ alert("请先输入查询条件");$id('refTheme').focus();return false; }
	document.location.href='usersCenter.php?mudi=newsManage'+ refUrlStr;
	return false;
}



// 检测附件栏
function CheckFile(){
	for (var i=1; i<=9; i++){
		try{
			if (i <= $id('fileNum').value){
				$id('fileBox'+ i).style.display = '';
			}else{
				$id('fileBox'+ i).style.display = 'none';
			}
		}catch (e){ break; }
	}
}


// 检测限制/付费阅读
function CheckIsCheckUser(){
	try {
		if ($id('isCheckUser1').checked){
			$id('checkUserBox').style.display = '';
		}else{
			$id('checkUserBox').style.display = 'none';
		}
	}catch (e) {}
}


// 检测部分正文加密
function CheckEncCont(){
	if ($id('isEnc1').checked){
		$id('encContBox').style.display='';
	}else{
		$id('encContBox').style.display='none';
	}
}
