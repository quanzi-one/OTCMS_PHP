$(function (){
	$('#bdMapForm span').css({"position":"relative"})
	$('#bdMapForm span img').css({"display":"none","position":"absolute","left":"150px","top":"22px"})
	$('#bdMapInput').mouseover(function (){
		$(this).find('img').css({"display":""})
		$(this).hover(function (){
			
		},function (){
			$(this).find('img').css({"display":"none"})
		});
	});

	CheckScoreMode();

	WindowHeight(0);
});


function CheckMapForm(str){
	$id('dealMode').value=str;
	pageNum = parseInt($id('pageMaxNum').value);
	if (pageNum<100 || pageNum>5000){
		alert("每个文件最大条数取值范围内：100 至 5000");$id('pageMaxNum').focus();return false;
	}
	if ($id('updateTime2').checked==true && $id('updateTimeStr').value==""){
		alert("选择了指定时间，请输入时间");$id('updateTimeStr').focus();return false;
	}
	$id('mapForm').submit();
}


function ResetMapForm(){
	$id('mapForm').reset();
	CheckScoreMode();
}

function CheckScoreMode(){
	if ($id('scoreMode1').checked){
		$id('infoScoreBox').style.display='';
	}else{
		$id('infoScoreBox').style.display='none';
	}
	WindowHeight(0);
}

function CheckBdMapForm(num, type){
	$id("bdMapForm").action = "siteMap_deal.php?mudi=dbMap&type="+ type +"&num="+ num;
	AjaxPostDealToId("bdMapForm","resShow");
	setTimeout("WindowHeight(0)",2000);
	setTimeout("WindowHeight(0)",5000);
}