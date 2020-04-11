// 初始化
$(function (){
	// 评论加载
	LoadMessageList();
	LoadMessageWrite();
});


var messageWaitTime = 0;
var messageCutWaitFunc = null;
// 评论指定用户回复
function ReplyUser(reID,reName){
	$id('replyUserID').value = reID;
	$id('replyUserStr').innerHTML = "回复："+ reName +"&ensp;&ensp;<span style='color:#000;cursor:pointer;' onclick='ReplyUserCancel();'>【取消回复】</span>";
	document.location.href="#replyArea";
}

// 指定用户回复取消
function ReplyUserCancel(){
	$id('replyUserID').value = 0;
	$id('replyUserStr').innerHTML = "";
}


// 检测留言填写框
function CheckMessageForm(){
	if ($id('messContent').value==""){
		alert('留言内容不能为空');$id('messContent').focus();return false;
	}
	if ($id('messContent').value.length<5){
		alert('留言内容不能少于5字符');$id('messContent').focus();return false;
	}
	strMaxLen = parseInt($id('messContentMaxLen').value);
	if ($id('messContent').value.length>strMaxLen && strMaxLen>0){
		alert('留言内容超过最大'+ strMaxLen +'字符限制');$id('messContent').value=$id('messContent').value.substring(0,strMaxLen);CalcReplyLen();return false;
	}
	if ($id('messUser').value==""){
		alert('昵称不能为空');$id('messUser').focus();return false;
	}
	try {
		if (SYS_verCodeMode == 20){
			if ($("input[name='geetest_challenge']").val() == "") {
				alert('请点击验证码按钮进行验证');return false;
			}
		}else{
			if ($id('verCode').value==""){
				alert('验证码不能为空');$id('verCode').focus();return false;
			}
		}
	}catch (e) {}

	if (messageWaitTime>0){
		alert("已提交，请稍等一会儿...("+ messageWaitTime +")");return false;
	}
		messageWaitTime = 3;
		messageCutWaitFunc = window.setInterval("CutMessageWaitTime()",1000);

	AjaxPostDeal("messageForm");
	return false;
}

function CutMessageWaitTime(){
	if (messageWaitTime<=0){
		window.clearInterval(messageCutWaitFunc);
		return false;
	}else{
		messageWaitTime --;
	}
}

// 读取留言信息
function LoadMessageList(){
	$id("messageList").innerHTML = "<center style='padding:10px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />数据加载中...</center>"+ $id("messageList").innerHTML;
	AjaxGetDealToId(webPathPart +"deal.php?mudi=messageSend&dataType=message","messageList");
}

// 读取评留言填写框
function LoadMessageWrite(){
	$id("replyWrite").innerHTML = "<center style='padding:10px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />数据加载中...</center>";
	$.ajaxSetup({cache:false});
	$.get(webPathPart +"deal.php?mudi=messageWrite&dataType=message", function(result){
		document.getElementById("replyWrite").innerHTML = result;
		try {
			CheckReplyMaxLen();
		}catch (e) {}
		try {
			if (SYS_verCodeMode == 20){
				geetWidth = "260px";
				LoadJsFile('geetestJs',webPathPart +'tools/geetest/gt.js?v=1.0',1);
			}
		}catch (e) {}
	});
}


// 检测回复内容字符
function CheckReplyMaxLen(){
	strMaxLen = parseInt($id('messContentMaxLen').value);
	if (strMaxLen>0){
		$id('conMaxLenBox').innerHTML = "(<span id='conCurrLen'>0</span>/"+ strMaxLen +")&ensp;";
		$('#messContent').keyup(function (){
			CalcReplyLen();
		});
	}
}

// 计算回复内容字符数
function CalcReplyLen(){
	try {
		$id('conCurrLen').innerHTML = $id('messContent').value.length;
	}catch (e) {}
}