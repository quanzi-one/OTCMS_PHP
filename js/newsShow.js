// 初始化
$(function (){

	try {
		// 复制并转载信息
		if (IS_eventStr.indexOf("|copy|")>=0 && (window.clipboardData || navigator.userAgent.indexOf("Opera")!=-1 || window.netscape)){
			document.body.oncopy=function(){
				try { event.returnValue=false; }catch (e) {}
				var isIE = !!window.ActiveXObject;
				var t,copyText;
				if (isIE){
					t=document.selection.createRange().text; // text获取纯文本；htmlText获取含HTML内容
				}else{
					t=document.getSelection().getRangeAt(0);
				}
				var s=IS_copyAddiStr.replace('{%当前网址%}',document.location.href);

				copyText = ''+ t +'\r\n\r\n'+ s +'\r\n'
				if (window.clipboardData){
					window.clipboardData.clearData();
					window.clipboardData.setData("Text", copyText)
				}else if(navigator.userAgent.indexOf("Opera") != -1){
					window.location = copyText;
				}else if(window.netscape){
					try {
						netscape.security.PrivilegeManager
								.enablePrivilege("UniversalXPConnect");
					}catch (e){
						// alert("你使用的FireFox浏览器,复制功能被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车。\n然后将“signed.applets.codebase_principal_support”双击，设置为“true”");
						return;
					}
					var clip = Components.classes['@mozilla.org/widget/clipboard;1']
							.createInstance(Components.interfaces.nsIClipboard);
					if (!clip)
						return;
					var trans = Components.classes['@mozilla.org/widget/transferable;1']
							.createInstance(Components.interfaces.nsITransferable);
					if (!trans)
						return;
					trans.addDataFlavor('text/unicode');
					var str = new Object();
					var len = new Object();
					var str = Components.classes["@mozilla.org/supports-string;1"]
							.createInstance(Components.interfaces.nsISupportsString);
					str.data = copyText;
					trans.setTransferData("text/unicode", str, copyText.length * 2);
					var clipid = Components.interfaces.nsIClipboard;
					if (!clip)
						return false;
					clip.setData(trans, null, clipid.kGlobalClipboard);
				}else{
					// alert("你的浏览器不支持一键复制功能");
					return;
				}

			}
		}
	}catch (e) {}

	// 数据加载
	try {
		if ($id('voteMode').value>0 && $id('voteMode').value!=11){
			try {
				AjaxGetDealToIdJs(webPathPart +'news_deal.php?mudi=vote&dataID='+ $id('infoID').value +'&webPathPart='+ WppSign(webPathPart), 'voteBox', '|vote|');
			}catch (e) {}
		}	

		// 评论加载
		if (IS_isNewsReply>0 && $id('isReply').value>0 && IS_newsReplyMode!=1){
			LoadReplyList($id('infoID').value);
			LoadReplyWrite($id('infoID').value);
		}
	}catch (e) {}

	$('#newsContent div,#newsContent span').removeClass('clear');
	ContentImgDeal();
	setTimeout("ContentImgDeal()",1000);
	setTimeout("ContentImgDeal()",2000);

	try {
		CheckSendContent();
	}catch (e) {}
	$('.fangdajingBox').mouseover(function (){
		$(this).find('.fangdajing').css({"display":""})
		$(this).hover(function (){
			
		},function (){
			$(this).find('.fangdajing').css({"display":"none"})
		});
	});
	if (wapUrl.length>0){
		$('#newsQRcodeBtn').mouseover(function (){
			$id('newsQrBox').style.display="";
			$id('newsQrBox').innerHTML=""+
				"<div style='position:absolute;border:1px #a0a1a3 solid;background:#ffffff;padding:2px 2px 8px 2px;'>"+
					"<img src='"+ webPathPart +"qrcode.php?text="+ encodeURIComponent(wapUrl) +"&logo=&size=5&margin=2' width='170' />"+
					"<div style='text-align:center;color:red;font-weight:bold;'>手机扫描访问该文章</div>"+
				"</div>"+
				"";
		});
		$('#newsQRcodeBtn').mouseout(function (){
			$id('newsQrBox').style.display="none";
		});

		try{
			$id('contWapQrCode').innerHTML=""+
				"<img src='"+ webPathPart +"qrcode.php?text="+ encodeURIComponent(wapUrl) +"&logo=&size=5&margin=2' width='170' />"+
				"<div class='qrTitle'>手机扫一扫，访问该文章</div>"+
				"";
		}catch (e){}
	}else{
		try{
			$id('newsQRcodeBtn').style.display="none";
		}catch (e){}
	}


});

function ContentImgDeal(){
//	$('#newsContent img').click(function (){
//		var a=window.open(this.src);
//	});
	conImgMaxWidth = 665;
	try {
		conImgMaxWidth = parseInt($id('contentImgMaxWidth').value);
		if (isNaN(conImgMaxWidth)){
			conImgMaxWidth = 665;
		}else{
			if (conImgMaxWidth < 50){ conImgMaxWidth = 665; }
		}
	}catch (e) {}
	
	$('#newsContent img').each(function (i){
//		this.style.margin='5px 0 0 0';
		if (this.width > conImgMaxWidth){
			var newHeight = parseInt(this.height * conImgMaxWidth / this.width);
			this.height = newHeight;
			this.width = conImgMaxWidth;
			this.style.width = conImgMaxWidth +"px";
			this.style.height = newHeight +"px";
			// this.css({"width":conImgMaxWidth +"px","height":newHeight +"px"});
//			this.alt='点击查看原图';
//			this.style.cursor='pointer';
//			$(this).after("<div style='margin:0 auto;width:100px;margin-bottom:5px;'><a href='"+ $(this).attr('src') +"' target='_blank' style='font-size:12px;color:red;' title='该图片原图过大，需单击该处才可查看到原图。'>[点击查看原图]</a></div>");
			$(this).wrap("<div class='fangdajingBox'></div>");
			$(this).before("<div class='fangdajing' style='position:relative;display:none;'><div style='position:absolute;left:0px;top:4px;width:"+ (conImgMaxWidth-2) +"px;height:30px;text-align:right;filter:alpha(opacity=80);-moz-opacity:0.80;opacity:0.80;z-index:999;'><img src='"+ jsPathPart +"inc_img/fangda.gif' onclick=\"var a=window.open('"+ $(this).attr('src') +"');return false;\" title='该图片原图过大，需单击该处才可查看到原图。' class='pointer' /></div></div>");
//			$(this).after("<div class='fangdajing' style='position:relative;display:none;'><div style='position:absolute;left:0px;top:-32px;width:"+ (conImgMaxWidth-2) +"px;height:30px;text-align:right;filter:alpha(opacity=80);-moz-opacity:0.80;opacity:0.80;z-index:999;'><img src='"+ jsPathPart +"inc_img/fangda.gif' onclick=\"var a=window.open('"+ $(this).attr('src') +"');return false;\" title='该图片原图过大，需单击该处才可查看到原图。' class='pointer' /></div></div>");
		}
	});
}

// 检查是否发送文章内容
function CheckSendContent(){
	if (ToInt($id('isUserCheck').value) > 0){
		if ($id('isEnc').value == 1){ retId="newsEncCont"; }else{ retId="newsContent"; }
		AjaxGetDealToIdJs2(webPathPart +"news_deal.php?mudi=contentSend&dataID="+ $id('infoID').value +"&isEnc="+ $id('isEnc').value +"&page="+ $id('pageValue').value +"&webPathPart="+ WppSign(webPathPart), retId, '|video|');
	}else{
		LoadVideoFile($id('newsContent').innerHTML);
	}
}

// 确定阅读
function CutScoreBtn(){
	if ($id('isEnc').value == 1){ retId="newsEncCont"; }else{ retId="newsContent"; }
	AjaxGetDealToIdJs2(webPathPart +"news_deal.php?mudi=contentSend&dataID="+ $id('infoID').value +"&isEnc="+ $id('isEnc').value +"&page="+ $id('pageValue').value +"&webPathPart="+ WppSign(webPathPart) +"&isCut=true", retId, '|video|');
}

// 分页链接
function ContentPageHref(modeStr,infoID,pageNum,mode1Url){
	if (modeStr!=""){
		AjaxGetDealToId(webPathPart +"news_deal.php?mudi=contentSend&dataID="+ infoID +"&page="+ pageNum +"&webPathPart="+ WppSign(webPathPart), modeStr, '|video|');
	}else{
		document.location.href=mode1Url.replace("[page]",pageNum);
	}
}

// 投票样式
function VoteStyle(){
	// 心情投票
	$(".webBox .d li").hover(function() { 
			$(this).addClass("font2_2 fontB");
		}, function(){
			$(this).removeClass("font2_2 fontB");
	});

	// 顶踩投票
	$(".webBox .d .upDown .up").hover(function() { 
			$(this).addClass("up2");
		}, function(){
			$(this).removeClass("up2");
	});
	$(".webBox .d .upDown .down").hover(function() { 
			$(this).addClass("down2");
		}, function(){
			$(this).removeClass("down2");
	});
}

// 投票点击
var isUseVote=false
function VoteDeal(num){
	if (isUseVote==true){
		alert('您已投票过，请下次再投.');return false;
	}
	AjaxGetDealToIdNo(webPathPart +'news_deal.php?mudi=vote&dataID='+ $id('infoID').value +'&selItem='+ num +'&webPathPart='+ WppSign(webPathPart),'voteBox','验证码禁用');
	isUseVote = true;
}


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


// 检测发表评论框
function CheckReplyForm(){
	if ($id('replyContent').value==""){
		alert('评价内容不能为空');$id('replyContent').focus();return false;
	}
	if ($id('replyContent').value.length<5){
		alert('评价内容不能少于5字符');$id('replyContent').focus();return false;
	}
	strMaxLen = parseInt($id('replyContentMaxLen').value);
	if (strMaxLen>0 && $id('replyContent').value.length>strMaxLen){
		alert('评价内容超过最大'+ strMaxLen +'字符限制');$id('replyContent').value=$id('replyContent').value.substring(0,strMaxLen);CalcReplyLen();return false;
	}
	if ($id('replyUser').value==""){
		alert('昵称不能为空');$id('replyUser').focus();return false;
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
	AjaxPostDeal("replyForm");
	return false;
}


// 读取评论区信息
function LoadReplyList(repID){
	$id("replyList").innerHTML = "<center style='padding:10px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />数据加载中...</center>"+ $id("replyList").innerHTML;
	AjaxGetDealToId(webPathPart +"news_deal.php?mudi=messageSend&dataID="+ repID +'&webPathPart='+ WppSign(webPathPart),"replyList");
}

// 读取评论区填写框
function LoadReplyWrite(repID){
	var dataTypeVal="",isReplyVal="";
	try {
		dataTypeVal=$id('dataType').value;
	}catch (e) {}
	try {
		isReplyVal=$id('isReply').value;
	}catch (e) {}

	$id("replyWrite").innerHTML = "<center style='padding:10px;'><img src='"+ webPathPart +"inc_img/onload.gif' style='margin-right:5px;' />数据加载中...</center>";
	$.ajaxSetup({cache:false});
	$.get(webPathPart +"news_deal.php?mudi=messageWrite&dataID="+ repID +'&dataType='+ dataTypeVal +'&isReply='+ isReplyVal +'&webPathPart='+ WppSign(webPathPart), function(result){
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
	try {
		strMaxLen = parseInt($id('replyContentMaxLen').value);
		if (strMaxLen>0){
			$id('conMaxLenBox').innerHTML = "(<span id='conCurrLen'>0</span>/"+ strMaxLen +")&ensp;";
			$('#replyContent').keyup(function (){
				CalcReplyLen();
			});
		}
	}catch (e) {}
}

// 计算回复内容字符数
function CalcReplyLen(){
	try {
		$id('conCurrLen').innerHTML = $id('replyContent').value.length;
	}catch (e) {}
}

