
var refContentDef = "请输入关键字";


// 初始化
$(function (){
	var fixtop = $(".mainMenu");fixtop.scrollFix({distanceTop:0});	// 导航菜单浮顶部

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

		}).hover(function() { 
			$(this).addClass("subhover");
		}, function(){
			$(this).find("ul.subnav").stop(true,true).slideToggle();
			$(this).removeClass("subhover");
		});
		
	}

	//搜索框效果
	jQuery('.referTheme').click(function() {
		jQuery('.referTheme_p').show();
	}).mouseout(function(){
		jQuery('.referTheme_p').hide();
	});

	jQuery(".referTheme_p").mouseover(function(){
		jQuery(this).show();  
	}).mouseout(function(){
		jQuery(this).hide();
	});

	jQuery(".referTheme_p  a").click(function() {
		var hu=jQuery(this).text();
		var va=jQuery(this).attr("valu");
		jQuery(".referTheme_t").text(hu);
		jQuery('.referTheme_p').hide();
		// $('#refContent').focus();
		$('#refMode').val(va);
	})


});

// 内容页 3种字号
function FontZoom(fsize){
	var ctext = document.getElementById("newsContent");ctext.style.fontSize = fsize +"px";
}
