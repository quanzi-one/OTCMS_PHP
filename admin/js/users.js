$(function (){

	try {
		CheckScoreBox();
	}catch (e) {}

	try {
		CheckGroupTime();
	}catch (e) {}

	WindowHeight(0);
});


// 检查表单
function CheckForm(){
	if ($id('isGroupTime').checked){
		if ($id('groupTime').value == ""){alert("请输入会员组到期时间");$id('groupTime').focus();return false}
	}
	if ($id('username').value == ""){alert("请输入用户名");$id('username').focus();return false}
	if ($id('userpwd').value!="" && $id('dataID').value=="0"){
		if ($id('userpwd').value.length < 5){alert("登录密码长度不能小于5位");$id('userpwd').focus();return false}
	}
//	if ($id('mail').value == ""){alert("请输入邮箱");$id('mail').focus();return false}


	if ($id('userpwd').value != "" && $id('answer').value != ""){
		if (confirm("你确定要修改登录密码和密保答案？")==false){return false;}
	}else if ($id('userpwd').value!="" && $id('dataID').value!="0"){
		if (confirm("你确定要修改登录密码？")==false){return false;}
	}else if ($id('answer').value != ""){
		if (confirm("你确定要修改密保答案？")==false){return false;}
	}

	return true;
}

// 开启/关闭会员积分框
function CheckScoreBox(){
	if ($id('isRevScore1').checked){
		$id('scoreBox').style.display='';
	}else{
		$id('scoreBox').style.display='none';
	}
	WindowHeight(0);
}

// 开启/关闭会员组到期时间框
function CheckGroupTime(){
	if ($id('isGroupTime').checked){
		$id('groupTimeBox').style.display='';
	}else{
		$id('groupTimeBox').style.display='none';
	}
	WindowHeight(0);
}

// 读取QQ作为邮箱
function LoadQQmail(){
	if ($id('qq').value == ""){alert("请先输入QQ，再点击该按钮");$id('qq').focus();return false}
	$id('mail').value = $id('qq').value +"@qq.com";
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
	if (confirm("你确定要批量设置成【"+ selOptionText +"】？"+ conAlert)==true){
		$id('listForm').action="users_deal.php?mudi=moreSet";
		$id('listForm').submit();
	}else{
		$id('moreSetTo').value="";
	}
	
}


// 会员组批量移动
function GroupMoveTo(){
	if ($id('groupMoveTo').value==""){ return false; }
	var selNum = 0;
	for (var i=0;i<$name("selDataID[]").length;i++){
		if ($name("selDataID[]")[i].checked){
			selNum ++;
		}
	}
	if (selNum==0){
		alert('请先选择要移动的会员.');$id('groupMoveTo').value="";return false;
	}
	selOptionText = $id('groupMoveTo').options[$id('groupMoveTo').selectedIndex].text;
	$id('groupMoveToCN').value = selOptionText;
	if (confirm("选中"+ selNum +"个会员，你确定要批量移动到会员组【"+ selOptionText +"】？")==true){
		$id('listForm').action="users_deal.php?mudi=groupMove";
		$id('listForm').submit();
	}else{
		$id('groupMoveTo').value="";
	}
	
}
