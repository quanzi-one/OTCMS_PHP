
// 会员组开通 按钮
function SubmitKaitong(groupID, groupName, day, money, score1, score2, score3){
	$id('gkGroupID').value = groupID;
	$id('gkGroupName').value = groupName;
	$id('gkDay').value = day;
	$id('gkMoney').value = money;
	$id('gkScore1').value = score1;
	$id('gkScore2').value = score2;
	$id('gkScore3').value = score3;

	var addiStr = '';
	if ($id('gkBtnVal').value == '更换'){ addiStr = '\n请谨慎操作！谨慎操作！更换后组权限和到期时间将重新按照新会员组。'; }
	if (confirm("确定要 "+ $id('gkBtnVal').value +"会员组【"+ $id('gkGroupName').value +"】？"+ addiStr +"\n"+ $id('scoreList'+ $id('gkGroupID').value).innerText) == false){
		return false;
	}else{
		$id('groupKaitongForm').submit();
	}
}

// 会员组开通表单检测
function CheckGroupKaitong(){
	var addiStr = '';
	if ($id('gkBtnVal').value == '更换'){ addiStr = '\n请谨慎操作！谨慎操作！更换后组权限和到期时间将重新按照新会员组。'; }
	if (confirm("确定要 "+ $id('gkBtnVal').value +"会员组【"+ $id('gkGroupName').value +"】？"+ addiStr +"\n"+ $id('scoreList'+ $id('gkGroupID').value).innerText) == false){
		return false;
	}else{
		return true;
	}
}

// 会员组续费表单检测
function CheckGroupXufei(){
	var endDayStr = '';
	if ($id('gxEndDay').value > 0){ endDayStr = '离会员组到期还有 '+ $id('gxEndDay').value +' 天'; }
	if (confirm("确定要 会员组续费/延长 "+ $id('gxDayFont').innerHTML +" 天？"+ endDayStr) == false){
		return false;
	}
}

// 金额兑换积分表单检测
function CheckScoreMul(){
	if (confirm("确定要兑换 "+ $id('usMoney').value +" 元") == false){
		return false;
	}
}

// 金额兑换积分
function CalcScoreMul(){
	try{
		var score1 = accMul($id('usMul1').value, $id('usMoney').value);
		$id('usScore1').innerHTML = score1;
		$id('usNewScore1').value = score1;
	}catch (e){}
	try{
		var score2 = accMul($id('usMul2').value, $id('usMoney').value);
		$id('usScore2').innerHTML = score2;
		$id('usNewScore2').value = score2;
	}catch (e){}
	try{
		var score3 = accMul($id('usMul3').value, $id('usMoney').value);
		$id('usScore3').innerHTML = score3;
		$id('usNewScore3').value = score3;
	}catch (e){}
}

//乘法
function accMul(arg1,arg2){
	var m=0,s1=arg1.toString(),s2=arg2.toString();
	try{m+=s1.split(".")[1].length}catch(e){}
	try{m+=s2.split(".")[1].length}catch(e){}
	return Number(s1.replace(".",""))*Number(s2.replace(".",""))/Math.pow(10,m);
}
Number.prototype.mul = function (arg){
	return accMul(arg, this);
}

