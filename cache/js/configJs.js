
		// 系统参数
		
						var SYS_isClose=20;
						var SYS_closeNote="网站测试中http:\/\/otcms.com~请稍后查看 123555";
						var SYS_verCodeMode=1;
						var SYS_isAjaxErr=0;
						var SYS_isFloatAd=0;
						var SYS_eventStr="|alipayErr|,|weixinErr|";
						var SYS_newsListUrlMode="dyn-2.x";
						var SYS_newsListFileName="news";
						var SYS_isWap=0;
						var SYS_isPcToWap=0;
						var SYS_wapUrl="";
						var SYS_jsTimeStr="20191117200000";
						var SYS_adTimeStr="20151203150724";
						
// create for 2019-11-17 20:00:00

		// 模板参数
		
						var TS_skinPopup="red";
						var TS_navMode=21;
						var TS_homeFlashMode=5;
						

		// 会员参数
		
						var US_isUserSys=1;
						var US_isLogin=1;
						
// create for 2019-11-17 19:58:09

		// 淘客参数
		

		// 文章参数
		
					var IS_isNewsReply=1;
					var IS_newsReplyMode=0;
					var IS_isNoCollPage=0;
					var IS_eventStr="";
					var IS_copyAddiStr="";
					
// create for 2019-11-17 19:58:09

		if (GetCookie("wap_otcms") != "pc"){
			// 判断是否为手机端访问，跳转到相应页面
			if (typeof(SYS_isWap) == "undefined"){ SYS_isWap = 1; }
			if (typeof(SYS_isPcToWap) == "undefined"){ SYS_isPcToWap = 0; }
			if (SYS_isWap==1 && SYS_isPcToWap>=1 && ("|home|list|show|web|users|message|bbsHome|bbsList|bbsShow|bbsWrite|gift|form|goodsList|").indexOf("|"+ webTypeName +"|")!=-1){
				JudGoWap();
			}
		}
		