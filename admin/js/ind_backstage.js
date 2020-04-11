$(function(){

	// TAB点击
	$(document).on('click','#subMenuArea li',function(){
		// 如果UL还在动画中则不处理，以免发生多次位移
		if($('#subMenuCont').is(":animated")){
			return false;
		}
		var data_id = $(this).attr('data-id'),
			data_title = $(this).attr('data-title');
		$(this).addClass('curr').siblings('li').removeClass('curr');
		// frame页面切换
		$('#RightFrm'+ data_id).show().siblings('iframe').hide();
		$id('currSubMenuID').value = data_id;
		$id("RightText").innerHTML = data_title;
	});

	// 刷新TAB
	$(document).on('click','#subMenuCont a.reload',function(){
		var _li = $(this).parent().parent(),
			data_id = _li.attr('data-id'),
			data_url = _li.attr('data-url');
		var _li = $('#subMenuArea li[data-id='+ data_id +']');
		_li.trigger('click');
		// $id('RightFrm'+ data_id).contentWindow.location.reload(true);
		$('#RightFrm'+ data_id).attr('src', data_url);
	});

	//上一个TAB
	$('#prevBtn').click(function(){
		$('#subMenuCont .curr').prev().trigger('click');
	});

	//下一个TAB
	$('#nextBtn').click(function(){
		$('#subMenuCont .curr').next().trigger('click');
	});

	// 关闭TAB
	$(document).on('click','#subMenuCont a.del',function(){
		var _li = $(this).parent().parent(),
			_prev_li = _li.prev('li'),
			data_id = _li.attr('data-id');
		_li.hide(60,function() {
			$(this).remove();
			$('#RightFrm'+ data_id).remove();
			var _curr_li = $('#subMenuCont li.curr');
			if(!_curr_li[0]){
				_prev_li.addClass('curr').trigger('click');
				$('#RightFrm'+_prev_li.attr('data-id')).show();
				$id('currSubMenuID').value = _prev_li.attr('data-id');
			}
		});
		$id("subMenuContBox").style.backgroundColor = "";
		return false;
	});
});


// 如果最上层窗体不是该页面重新加载最上层窗体
if(self.location!=top.location){ window.top.location.href=document.location.href; }

var calcTimeFunc
// 点击主菜单后的效果
function ClickMenu(CNname,ENname,autoOpen,mudi){
//	LeftK.location.href="left_menu.php?mudiCN="+ encodeURIComponent(CNname) +"&mudi="+ ENname +"&autoOpen="+ autoOpen;
	$id("leftMenuContent").innerHTML=$id("leftMenuID"+ ENname).innerHTML;
	$id("LeftText").innerHTML=CNname;
	$id($id("menuStr").value).style.background = "";
	$id($id("menuStr").value).innerHTML = $id($id("menuStr").value).innerHTML.replace("font1_2","fontMenu");
	if (mudi != "userManage"){
		$id("mainMenu"+ ENname).style.background = "url('images/top_menuBg.gif')";
		$id("mainMenu"+ ENname).innerHTML = $id("mainMenu"+ ENname).innerHTML.replace("fontMenu","font1_2");
		$id("menuStr").value="mainMenu"+ ENname;
	}
	if (autoOpen==true && isSubMenu != "1"){
		eval($id("input"+ ENname).value);
	}else{
		RightFrm0.location.href="left_menuNote.php?mudi="+ ENname;
	}
}


// 左侧菜单伸缩
function TurnMenu(){
	if ($id("ShowLeft").style.display == "")
		{$id("ShowLeft").style.display = "none";$id("LeftImg").src="images/left_open.gif";}
	else
		{$id("ShowLeft").style.display = "";$id("LeftImg").src="images/left_close.gif";}
}


// 链接右边内容框
function HrefTo(mainMenuName,menuName,URLstr,menuID){
	var titleStr = $id("LeftText").innerHTML +"→"+ menuName;
	if (mainMenuName == '引导页' || mainMenuName == '插件平台'){
		titleStr = mainMenuName;
	}
	$id("RightText").innerHTML = titleStr;
	if (isSubMenu == "1"){
		$('#subMenuCont li').removeClass('curr');
		var _li = $('#subMenuArea li[data-id='+ menuID +']');
		if(_li[0]){
			// 存在则直接点击
			_li.trigger('click');
		}else{
			var totalWidth = $('#subMenuContBox').width(), calcWidth = 0;
			$("#subMenuCont li").each(function(){
				calcWidth += $(this).outerWidth();
			});
			if (calcWidth + 56 + 12*menuName.length > totalWidth){
				$id("subMenuContBox").style.backgroundColor = "#fdece9";
				alert("已没多余空间增加菜单卡，请先删除没用的菜单卡，再操作。"); return;
			}
			// 不存在新建frame和tab
			var rframe = $('<iframe/>', {
				src					: URLstr,
				id					: 'RightFrm'+ menuID,
				name				: 'RightFrm'+ menuID,
				allowtransparency	: true,
				frameborder			: 0,
				scrolling			: 'auto',
				width				: '100%',
				height				: '100%',
				style				: 'width:100%; height:100%; min-height:400px;'
			}).appendTo('#iframeArea');
			$(rframe[0].contentWindow.document).ready(function(){
				rframe.siblings().hide();
				var _li = $(''+
					'<li data-id="'+ menuID +'" data-url="'+ URLstr +'" data-title="'+ titleStr +'" title="'+ titleStr +'"><span>'+
						'<a class="reload" title="页面重加载">刷新</a>'+
						'<a>'+ menuName +'</a>'+
						'<a class="del" title="关闭此页">关闭</a>'+
					'</span></li>').addClass('curr');
				_li.appendTo('#subMenuCont').siblings().removeClass('curr');
				_li.trigger('click');
			});
		}
		$('#RightFrm'+ menuID).attr('src', URLstr);	// $('#RightFrm'+ menuID).attr('src')
		$id('currSubMenuID').value = menuID;

	}else{
		RightFrm0.location.href = URLstr;
	}
}

// 右边内容框链接引导页
function HrefToDef(){
	HrefTo("引导页","引导页","left_menuNote.php?mudi=","0");
}

// 右边内容框链接插件平台
function HrefToShop(queryStr){
	if (typeof(queryStr) == "undefined"){ queryStr = ""; }
	if (queryStr.length < 3){ queryStr = "appShop.php"; }
	HrefTo("插件平台","插件平台", queryStr, "999999");
}

// 倒计时
function calcTime(){
	Mnum=parseInt($id("exitM").innerHTML)
	Snum=parseInt($id("exitS").innerHTML)
	Snum=Snum-1
	if (Snum<0){$id("exitM").innerHTML=Mnum-1;$id("exitS").innerHTML="59"}else{$id("exitS").innerHTML=Snum}
	if (Mnum==0 && Snum==0){
		nowDiffTime = 0;

		$.ajaxSetup({cache:false});
		$.get("read.php?mudi=exitTimeDiff", function(result){
			nowDiffTime = result; 

			try{
				nowDiffTime = parseInt(nowDiffTime);
			}
			catch (e){}

			if (nowDiffTime<=0){
				alert("您超过"+ exitMinute +"分钟没动静，为了后台安全，请重新登录。");
				document.location.href="admin_cl.php?mudi=exit&nohrefStr=close";
			}else{
				exitNewM = parseInt(nowDiffTime / 60);
				exitNewS = parseInt(nowDiffTime % 60);

				clearTimeout(calcTimeFunc);
				$id("exitM").innerHTML = exitNewM;
				$id("exitS").innerHTML = exitNewS;
				calcTime();
			}
		});

//		alert("您超过"+ exitMinute +"分钟没动静，为了后台安全，请重新登录。");document.location.href="admin_cl.php?mudi=exit&nohrefStr=close";
	}else{
		calcTimeFunc = window.setTimeout("calcTime()",1000);
	}
	
}

// 初始化倒计时，并开始倒计时
function StartCalcTime(){
	if (exitMinute==0){$id("exitTimeK").style.display="none";return false;}
	clearTimeout(calcTimeFunc);
	$id("exitM").innerHTML = exitMinute;
	$id("exitS").innerHTML = 0;
	calcTime();
}

/*
function CheckInfoRefK(form){
	if (form.refDate1.value == "" && form.refDate2.value != "")
		{alert("末尾日期既然填了，起始日期就不能为空");return false}
	
	if (form.refDate1.value != "" && form.refDate2.value != ""){
		var OneMonth = form.refDate1.value.substring(5,form.refDate1.value.lastIndexOf ("-"));
		var OneDay = form.refDate1.value.substring(form.refDate1.value.length,form.refDate1.value.lastIndexOf ("-")+1);
		var OneYear = form.refDate1.value.substring(0,form.refDate1.value.indexOf ("-"));

		var TwoMonth = form.refDate2.value.substring(5,form.refDate2.value.lastIndexOf ("-"));
		var TwoDay = form.refDate2.value.substring(form.refDate2.value.length,form.refDate2.value.lastIndexOf ("-")+1);
		var TwoYear = form.refDate2.value.substring(0,form.refDate2.value.indexOf ("-"));

		if (Date.parse(OneMonth+"/"+OneDay+"/"+OneYear) > Date.parse(TwoMonth+"/"+TwoDay+"/"+TwoYear))
			{alert("起始日期不能大于末尾日期");return false}
	}
	
	RightText.innerHTML="动态信息→查询结果"
}
*/



// 打开/关闭快捷&工具栏
function CheckFdbox(str){
	if (str=="open"){
		$id("fdBox").style.display = "";
	}else if (str=="close"){
		$id("fdBox").style.display = "none";
	}else{
		if ($id("fdBox").style.display==""){
			$id("fdBox").style.display = "none";
		}else{
			$id("fdBox").style.display = "";
		}
	}
}


var rightBoxObj;
function startRboxCountdown(){
	$id("rightBoxK").style.display="";
	$id("rightBoxSec").innerHTML=180;
	clearInterval(rightBoxObj);
	rightBoxObj = setInterval("rboxCountdown()",1000);
}

function endRboxCountdown(){
	$id("rightBoxK").style.display="none";
	$id("rightBoxSec").innerHTML=0;
	clearInterval(rightBoxObj);
}

function rboxCountdown(){
	var idVal = parseInt($id("rightBoxSec").innerHTML);
	if(isNaN(idVal)){ clearInterval(rightBoxObj); return false; }
	idVal--;
	// if (idVal<0){ clearInterval(rightBoxObj); RightFrm0.location.reload(); return false; }
	if (idVal<=0){ RightFrm0.history.back(); idVal=180; }
	$id("rightBoxSec").innerHTML=idVal;
}


function CheckRightHeight(){
	var csmID = ToInt($id("currSubMenuID").value);
	$id("RightFrm"+ csmID).style.height="100px";
	window.frames['RightFrm'+ csmID].WindowHeight(30);
}
