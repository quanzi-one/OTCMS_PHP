$(function (){

	WindowHeight(0);

});


// 检测增值服务
function CheckPayForm(){

}


// 授权登录检测
function CheckLoginForm(){
	if ($id("username").value == ""){alert("用户名不能为空.");$id("username").focus();return false;}
	if ($id("userpwd").value == ""){alert("密码不能为空.");$id("userpwd").focus();return false;}

	// EncPwdData('userpwd');
	AjaxPostDeal('loginForm');
	return false;
}


function CheckPayBtn(str){
	if (str.length > 0){
		payIdArr = str.split(',');
		for(var payId in payIdArr){
			try{
				$id('downBtn'+ payIdArr[payId]).innerHTML = "<span style='color:green;'>已购买</span>";
			}catch (e){}
		}
	}
}

var updateLastFileName,updateVerID,updateFileListStr,judUpdateLastFile,updateFileArr,updateFileEndNum;
var runFileSkipStr = "";