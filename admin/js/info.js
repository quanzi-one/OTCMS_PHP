$(function (){
	try {
		CheckIsTitle();
		CheckWebUrl();
	}catch (e) {}
	try {
		ToSource('def');
		ToWriter('def');
		CheckAddition();
	}catch (e) {}
	try {
		CheckVote();
	}catch (e) {}
	try {
		$("#img").mouseover(function() {

			}).hover(function() { 
				$('#imgView').css({"display":""});
				if (IsAbsUrl($id('img').value)){
					$id('imgView').src=$id('img').value;
				}else{
					$id('imgView').src=$id('imgAdminDir').value + $id('img').value;
				}
			}, function(){
				$('#imgView').css({"display":"none"});
		});
		CheckSaveImg();
	}catch (e) {}
	try {
		CheckAudit();
	}catch (e) {}
	try {
		CheckIsCheckUser();
	}catch (e) {}
	try {
		CheckEncCont();
	}catch (e) {}
	try {
		CheckFile();
	}catch (e) {}

	WindowHeight(0);setTimeout("WindowHeight(0)",500);
});


// 检测表单
function CheckForm(){
	if ($id('time').value == ""){alert("发布时间不能为空");$id('time').focus();return false}
	if ($id('theme').value == ""){alert("标题不能为空");$id('theme').focus();return false}
	if ($id('typeStr').value == ""){alert("请选择分类");$id('typeStr').focus();return false}
	if ($id('webURL').value==""){
		try {
			if ($id('isTitle').checked){
				if ($id('titleAddi').value == ""){alert("自定义网页title不能为空");$id('titleAddi').focus();return false}
			}
			if ($id('template').value!=""){
				if ($id('template').value.substr(0,4)!="news"){
					alert("电脑版模板的文件名必须以news开头的，如news22.html、newsTwo.html");$id('template').focus();return false;
				}
				if ($id('template').value.substr($id('template').value.length-5)!=".html"){
					alert("电脑版模板的文件名必须以.html结尾");$id('template').focus();return false;
				}
			}
		}catch (e) {}
	//	if ($id('contentKey').value == ""){alert("内容摘要不能为空");$id('contentKey').focus();return false}
		if ($id('contentKey').value.length > 190){alert("内容摘要不能超过190个字符，当前有"+ $id('contentKey').value.length +"个字符");$id('contentKey').focus();return false}
		for (var i=0; i<=$id('fileNum').value; i++){
			if (i > 0){
				if ($id('file'+ i).value == ""){alert("附件文件"+ i +"不能为空");$id('file'+ i).focus();return false}
			}
		}
	}
	return true
}

// 检查加粗
function CheckThemeB(){
	if ($id("themeB").checked){
		$id("theme").style.fontWeight="bold";
	}else{
		$id("theme").style.fontWeight="normal";
	}
}

// 是否开启自定义网页title
function CheckIsTitle(){
	if ($id('isTitle').checked){
		$id('titleAddiName').innerHTML = "<span style='color:red;'>*</span>自定义网页title";
	}else{
		$id('titleAddiName').innerHTML = "文章标题附加";
	}
}

// 检查外部链接是否有内容
function CheckWebUrl(){
	if ($id('webURL').value!=""){
		$('.infoBox').css("display","none");
	}else{
		$('.infoBox').css("display","");
	}
	WindowHeight(0);
}

// 载入文章来源
function ToSource(str){
	if (str=="def"){
		if ($id('source').value==""){
			$id('source').value=$id('sourceItem').value;
		}
	}else{
		$id('source').value=$id('sourceItem').value;
	}
}

// 载入文章作者
function ToWriter(str){
	if (str=="def"){
		if ($id('writer').value==""){
			$id('writer').value=$id('writerItem').value;
		}
	}else{
		$id('writer').value=$id('writerItem').value;
	}
}

// 检查阅读等级限制
function SetUserLevel(){
	levelStr = $id('userLevelStr').value;
	levelArr = levelStr.split('|');
	$id('userLevel').value = levelArr[0];
	$id('score1').value = levelArr[1];
	$id('score2').value = levelArr[2];
	$id('score3').value = levelArr[3];
}


// 检测重复标题
function CheckRepeatTheme(){
	themeStr = $id('theme').value;

	if (themeStr==""){
		alert("标题不能为空.");return false;
	}

	$.ajaxSetup({cache:false});
	$.get("readDeal2.php?mudi=checkInfoRepeatTheme&dataID="+ $id('dataID').value +"&theme="+ encodeURIComponent(themeStr) +"", function(result){
		alert(result);
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
	$id('contentKey').value = str.substring(0,160).replace(/\r\n/g,"") +"...";
}

// 载入缩略图/图片框
function ToImg(str){
	$id('img').value = str;
	$id('editorImgBox').innerHTML = "";
}

// 获取编辑器图片
function GetEditorImg(){
	selImgStr = "";
	imgPath = $id('imgDir').value;
	imgAdminPath = $id('imgAdminDir').value;
	imgStr = $id('upImgStr').value;
	imgArr = imgStr.split('|');
	for (var i=0; i<imgArr.length; i++){
		if (imgArr[i] != ""){
			if (IsHttpUrl(imgArr[i])){
				imgHeadPart = "";
			}else{
				imgHeadPart = imgAdminPath;
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
	WindowHeight(0);
}

// 检测属性
function CheckAddition(){
	isImgBox = false;
/*
	for (var i=0; i<$name('addition').length; i++){
		if ($name('addition')[i].checked){
			if ($name('addition')[i].value=="|homeThumb|" || $name('addition')[i].value=="|thumb|" || $name('addition')[i].value=="|img|" || $name('addition')[i].value=="|flash|"){
				isImgBox = true
			}		
		}
	}
*/
	if ($id('isHomeThumb').checked || $id('isThumb').checked || $id('isImg').checked || $id('isFlash').checked){
		isImgBox = true
	}		

	if (isImgBox==true){
		$id('imgBox').style.display="";
	}else{
		$id('imgBox').style.display="none";
	}
	WindowHeight(0);
}

// 已审/未审
function CheckAudit(){
	if ($id('isAudit').value == 2){
		$id('auditBox').style.display = "";
	}else{
		$id('auditBox').style.display = "none";
	}
	WindowHeight(0);
}

// 检查是否开启下载缩略图
function CheckSaveImg(){
	try{
		if ($id('img').value.length > 10 && IsAbsUrl($id('img').value)){
			$id('saveImgBox').style.display = '';
		}else{
			$id('saveImgBox').style.display = 'none';
		}
	}catch (e){}
}

// 检测投票
function CheckVote(){
/*
	isVote1Box = isVote2Box = false;
	for (var i=0; i<$name('voteMode').length; i++){
		if ($name('voteMode')[i].checked){
			if ($name('voteMode')[i].value=="1"){
				isVote1Box = true
			}else if ($name('voteMode')[i].value=="2"){
				isVote2Box = true
			}
		}
	}
	if (isVote1Box==true){
		$id('vote1Box').style.display="";
	}else{
		$id('vote1Box').style.display="none";
	}
	if (isVote2Box==true){
		$id('vote2Box').style.display="";
	}else{
		$id('vote2Box').style.display="none";
	}
*/
	if ($id('voteMode1').checked){
		$id('vote1Box').style.display="";
	}else{
		$id('vote1Box').style.display="none";
	}
	if ($id('voteMode2').checked){
		$id('vote2Box').style.display="";
	}else{
		$id('vote2Box').style.display="none";
	}
	WindowHeight(0);
}

// 检测限制/付费阅读
function CheckIsCheckUser(){
	$id('checkUserBox').style.display='none';
	$id('userScoreBox').style.display='none';

	if ($id('isCheckUser1').checked){
		$id('checkUserBox').style.display='';
		$id('userScoreBox').style.display='';
	}
	try{
		if ($id('isCheckUser2').checked){
			$id('checkUserBox').style.display='';
		}
	}catch (e){}
	WindowHeight(0);
}


// 检测部分正文加密
function CheckEncCont(){
	if ($id('isEnc1').checked){
		$id('encContBox').style.display='';
	}else{
		$id('encContBox').style.display='none';
	}
	WindowHeight(0);
}


// 检测附件栏
function CheckFile(){
	for (var i=1; i<=9; i++){
		if (i <= $id('fileNum').value){
			$id('fileBox'+ i).style.display = '';
		}else{
			$id('fileBox'+ i).style.display = 'none';
		}
	}
	WindowHeight(0);
}


// 批量设置
function MoreSetTo(){
	if ($id('moreSetTo').value==""){ return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要设置的记录.');$id('moreSetTo').value="";return false;
	}
	selOptionText = $id('moreSetTo').options[$id('moreSetTo').selectedIndex].text;
	selOptionValue = $id('moreSetTo').value;
	$id('moreSetToCN').value = selOptionText;

	conAlert="";
	if (selOptionValue=="audit1" || selOptionValue=="audit0"){
		conAlert="\n\n该操作不会对用户积分进行增减，故用户投稿得/扣积分的文章不要用该批量操作。";
	}
	if (confirm("你确定要批量设置成【"+ selOptionText +"】？"+ conAlert)==true){
		$id('listForm').action="info_deal.php?mudi=moreSet";
		$id('listForm').submit();
	}else{
		$id('moreSetTo').value="";
	}
	
}

// 栏目批量移动
function MoreMoveTo(){
	if ($id('moreMoveTo').value==""){ return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要移动的记录.');$id('moreMoveTo').value="";return false;
	}
	selOptionText = $id('moreMoveTo').options[$id('moreMoveTo').selectedIndex].text;
	$id('moreMoveToCN').value = selOptionText;
	if (confirm("选中"+ selNum +"条记录，你确定要批量移动到栏目【"+ selOptionText +"】？")==true){
		$id('listForm').action="info_deal.php?mudi=moreMove";
		$id('listForm').submit();
	}else{
		$id('moreMoveTo').value="";
	}
	
}

// 附加内容批量移动
function MoreAddiTo(){
	if ($id('moreAddiTo').value==""){ return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要设置的记录.');$id('moreAddiTo').value="";return false;
	}
	selOptionText = $id('moreAddiTo').options[$id('moreAddiTo').selectedIndex].text;
	$id('moreAddiToCN').value = selOptionText;
	if ($id('moreTxtSel').value == 'topAddiID'){ addiType='正文头'; }else{ addiType='正文尾'; }
	if (confirm("选中"+ selNum +"条记录，你确定要批量设置 "+ addiType +"附加内容【"+ selOptionText +"】？")==true){
		$id('listForm').action="info_deal.php?mudi=moreAddi";
		$id('listForm').submit();
	}else{
		$id('moreAddiTo').value="";
	}
}


// 其他字段批量设置
function MoreTxtTo(){
	if ($id('moreTxtVal').value==""){ alert('新内容不能为空.');$id('moreTxtVal').focus();return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要设置的记录.');return false;
	}
	selOptionText = $id('moreTxtSel').options[$id('moreTxtSel').selectedIndex].text;
	if (confirm("选中"+ selNum +"条记录，你确定要批量设置【"+ selOptionText +"】？")==true){
		$id('listForm').action="info_deal.php?mudi=moreAddi";
		$id('listForm').submit();
	}else{
		$id('moreAddiTo').value="";
	}
}


// 专题批量移动
function MoreTopicTo(){
	if ($id('moreTopicTo').value==""){ return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要移动的记录.');$id('moreTopicTo').value="";return false;
	}
	selOptionText = $id('moreTopicTo').options[$id('moreTopicTo').selectedIndex].text;
	$id('moreTopicToCN').value = selOptionText;
	if (confirm("选中"+ selNum +"条记录，你确定要批量移动到专题【"+ selOptionText +"】？")==true){
		$id('listForm').action="info_deal.php?mudi=moreTopic";
		$id('listForm').submit();
	}else{
		$id('moreTopicTo').value="";
	}
	
}


function CheckMoreTxt(){
	if ($id('moreTxtSel').value == 'topAddiID' || $id('moreTxtSel').value == 'addiID'){
		$id('moreAddiTo').style.display = '';
		$id('moreTxtBox').style.display = 'none';
	}else{	//  if ($id('moreTxtSel').value == 'source' || $id('moreTxtSel').value == 'writer')
		$id('moreAddiTo').style.display = 'none';
		$id('moreTxtBox').style.display = '';
	}
}


// 检查查询栏删除模式
function CheckRefDelBtn(){
	if ($id('refDelBtn').checked){
		$id("STTCInull1").innerHTML = "文章删除模式";
		$id('refSubBtn').src = "images/button_del.gif";
		$id("refForm").method = "post";
		$id("refForm").action = "info_deal.php?mudi=refMoreDel";
	}else{
		$id("STTCInull1").innerHTML = "文章查询";
		$id('refSubBtn').src = "images/button_refer.gif";
		$id("refForm").method = "get";
		$id("refForm").action = "";
	}
}

// 检查查询栏表单
function CheckRefForm(){
	if ($id('refDelBtn').checked){
		if (confirm('你确定要进行删除？\n\n如果文章超多，可能会删除运行超时，多操作几次即可删除完。')==false){ return false; }
	}
}