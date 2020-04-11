window.onload=function(){WindowHeight(0)}


// 文字友情链接 检测表单
function CheckDealForm(){
	if ($id('theme').value == ""){alert("标题不能为空");$id('theme').focus();return false;}
	if ($id('webURL').value == "" || $id('webURL').value == "http://"){alert("链接不能为空");$id('webURL').focus();return false;}
	return true
}

function CheckLogoMode(){
	isImgUrl = isUpImg = false;
	for (var i=0; i<$name('imgMode').length; i++){
		if ($name('imgMode')[i].checked){
			if ($name('imgMode')[i].value=='upImg'){
				isUpImg = true;
			}else if ($name('imgMode')[i].value=='URL'){
				isImgUrl = true;
			}
		}
	}
	if (isImgUrl == true){
		$id('imgUrlBox').style.display='';
	}else{
		$id('imgUrlBox').style.display='none';
	}
	if (isUpImg == true){
		$id('upImgBox').style.display='';
	}else{
		$id('upImgBox').style.display='none';
	}
	WindowHeight(0);
}

// 在线客服 检测表单
function CheckDealForm3(){
	if ($id('imgMode').value == ""){alert("类型不能为空");$id('imgMode').focus();return false}
	if ($id('theme').value == ""){alert("名称不能为空");$id('theme').focus();return false}
	if ($id('webURL').value == ""){alert("帐号/号码/ID不能为空");$id('webURL').focus();return false}
	return true
}



function GetEndTime(num){
	startDateStr = $id('startDate').value;
	if (startDateStr==""){
		alert('请先输入加入时间');$id('startDate').focus();return false;
	}
	switch (num){
		case -1:
			$id('endDate').value="2029-12-31";
			break;
	
		default:
			$id('endDate').value=DateAdd('m',num,startDateStr);
			break;
	
	}
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
	selOptionValue = $id('moreSetTo').options[$id('moreSetTo').selectedIndex].text;
	$id('moreSetToCN').value = selOptionValue;
	if (confirm("你确定该"+ selNum +"条记录要批量设置成【"+ selOptionValue +"】？")==true){
		$id('listForm').action="infoMove_deal.php?mudi=moreSet";
		$id('listForm').submit();
	}else{
		$id('moreSetTo').value="";
	}
	
}
