var MyMar,speed;

$(function (){
	try {
		//横向滚动
		speed=30;
		if ($id('caseMarX').offsetWidth<$id('caseMarX1').offsetWidth){
			$id('caseMarX2').innerHTML=$id('caseMarX1').innerHTML;
			$id('caseMarX3').innerHTML=$id('caseMarX1').innerHTML;
		}
		MyMar=setInterval(Marquee,speed)
		$id('caseMarX').onmouseover=function() { clearInterval(MyMar); }
		$id('caseMarX').onmouseout=function() { MyMar=setInterval(Marquee,speed); }
	}catch (e) {}

	try {
		$('.newMessItem').mouseover(function (){
			$(this).addClass('fontU');
			$(this).hover(function (){
			}, function (){
				$(this).removeClass('fontU');
			});
		});
	}catch (e) {}
});

function Marquee(){
	try {
		if($id('caseMarX2').offsetWidth-$id('caseMarX').scrollLeft<=0){
			$id('caseMarX').scrollLeft-=$id('caseMarX1').offsetWidth;
		}else{
			$id('caseMarX').scrollLeft+=1.8;
		}
	}catch (e) { clearInterval(MyMar); }
}

// 最新消息翻页
var newTabPage = 1;
function newGoPage(mode){
	var tabCount = $('#newListArea').children('ul').length;
	if (mode == 1){
		newTabPage --;
		if (newTabPage < 1){ newTabPage = 1; }
	}else{
		newTabPage ++;
		if (newTabPage > tabCount){ newTabPage = tabCount; }
	}
	$("#newListArea > ul").css("display","none");
	$("#newListArea > ul:eq("+ (newTabPage-1) +")").css("display","");
	if (newTabPage > 1){
		$("#newTabMark").html(newTabPage +"/"+ tabCount);
	}else{
		$("#newTabMark").html("　");
	}
	
}
