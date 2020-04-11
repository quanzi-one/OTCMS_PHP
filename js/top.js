
var refContentDef = "请输入关键字";


// 初始化
$(function (){
	WinLoadRun("");

	if (typeof(TS_navMode)=="undefined"){ TS_navMode=21; }
	if (TS_navMode>0){
		// 导航菜单子菜单
		$("ul.topnav li.b").mouseover(function() {
			if (TS_navMode==21){
				$(this).find("ul.subnav").slideDown('fast').show();

				$(this).hover(function() {
				}, function(){	
					$(this).find("ul.subnav").slideUp('slow');
				});
			}else if (TS_navMode==26){
				$(this).find("ul.subnav").show();

				$(this).hover(function() {
				}, function(){	
					$(this).find("ul.subnav").hide();
				});
			}

		}).hover(function() {		// 鼠标移到元素上要触发的函数
			$(this).addClass("subhover");
		}, function(){			// 鼠标移出元素要触发的函数
			$(this).find("ul.subnav").stop(true,true).slideToggle();
			$(this).removeClass("subhover");
		});
		
	}

	// 首页签到按钮变换
	$("#qiandaoBtn").hover(function() { 
		$id("qiandaoBtn").src = "inc_img/qiandaoBtn2.png";
	}, function(){
		$id("qiandaoBtn").src = "inc_img/qiandaoBtn.png";
	});

});
