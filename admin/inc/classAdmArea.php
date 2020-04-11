<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class AdmArea{

	// 用户QQ信息
	public static function UserQQ($qq, $mark='div'){
		$retStr = '';
		if (strlen($qq) > 0){
			$qqArr = explode(',',$qq);
			for ($n=0; $n<count($qqArr); $n++){
				$retStr .= '<'. $mark .'>'. $qqArr[$n] .'<a href="http://wpa.qq.com/msgrd?v=3&uin='. $qqArr[$n] .'&site=OTCMS&menu=yes" target="_blank"><img height="17" alt="点击这里给我发消息" hspace="2" src="http://wpa.qq.com/pa?p=1:'. $qqArr[$n] .':17" width="25" align="absBottom" border="0" /></a></'. $mark .'>'. ($mark=='span'?'&ensp;&ensp;':'');
			}
		}
		return $retStr;
	}


	// 用户旺旺信息
	public static function UserWw($ww){
		$retStr = '';
		if (strlen($ww)>0 ){
			$wwUrlEncode = urlencode($ww);
			$retStr = $ww .'<a target="blank" href="http://www.taobao.com/webww/ww.php?ver=3&touid='. $wwUrlEncode .'&siteid=cntaobao&status=1&charset=utf-8"><img border="0" src="http://amos.alicdn.com/realonline.aw?v=2&uid='. $wwUrlEncode .'&site=cntaobao&s=2&charset=utf-8" alt="点击这里给我发消息('. $ww .')"  width="16" /></a>';
		}
		return $retStr;
	}


	// 打开用户信息
	public static function UserInfoImg($userID){
		if ($userID > 0){
			$retStr = '<a href="users.php?mudi=show&nohrefStr=close&dataMode=&dataModeStr=&dataType=&dataTypeCN='. urlencode('会员') .'&dataID='. $userID .'" target="_blank"><img src="images/img_user.gif" style="cursor:pointer;margin-left:3px;" valign="top" alt="查看会员详细信息" title="查看会员详细信息" /></a>';
		}else{
			$retStr = '';
		}
		return $retStr;
	}


	// 读取模板
	public static function TplFileOption($mode, $type, $selVal){
		global $DB;
		$retStr = '';
		if ($mode == 'wap'){
			$WAP_templateDir = $DB->GetOne('select WAP_templateDir from '. OT_dbPref .'wap');
			$xmlFileStr = File::Read(OT_ROOT .'wap/template/'. $WAP_templateDir .'config.xml');
		}else{
			$SYS_templateDir = $DB->GetOne('select SYS_templateDir from '. OT_dbPref .'system');
			$xmlFileStr = File::Read(OT_ROOT .'template/'. $SYS_templateDir .'config.xml');
		}
		$fileListStr = Str::GetMark($xmlFileStr,'<'. $type .'File>','</'. $type .'File>');
		if (strlen($fileListStr)>0){
			$fileListArr = explode(',',$fileListStr);
			$fileListCount = count($fileListArr);
			$noteListStr = Str::GetMark($xmlFileStr,'<'. $type .'FileNote>','</'. $type .'FileNote>') . str_repeat(',',$fileListCount);
			$noteListArr = explode(',',$noteListStr);
			for ($i=0; $i<$fileListCount; $i++){
				if (strlen($noteListArr[$i]) > 0){ $noteListArr[$i] = '('. $noteListArr[$i] .')'; }
				$retStr .= '<option value="'. $fileListArr[$i] .'" '. Is::Selected($selVal,$fileListArr[$i]) .'>'. $fileListArr[$i] .' '. $noteListArr[$i] .'</option>';
			}
		}
		return $retStr;
	}



	// 生成cache/web/site.css
	public static function MakeSiteCss(){
		global $DB;

		$cssStr = '';
		$eventStr = $DB->GetOne('select SYS_eventStr from '. OT_dbPref .'system');
		$tplArr = $DB->GetRow('select TS_navWidthMode,TS_jieriImg,TS_jieriHeight,TS_subWebLR from '. OT_dbPref .'tplSys');
		if (strpos($eventStr,'|siteGray|') !== false){
			$cssStr .= '
				html {
					filter: grayscale(100%);
					-webkit-filter: grayscale(100%);
					-moz-filter: grayscale(100%);
					-ms-filter: grayscale(100%);
					-o-filter: grayscale(100%);
					filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
					filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
					-webkit-filter: grayscale(1);
				}	/* 全站变灰 */
				';
		}elseif (strpos($eventStr,'|siteGrayHome|') !== false){
			$cssStr .= '
				html.site_home {
					filter: grayscale(100%);
					-webkit-filter: grayscale(100%);
					-moz-filter: grayscale(100%);
					-ms-filter: grayscale(100%);
					-o-filter: grayscale(100%);
					filter: url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\'><filter id=\'grayscale\'><feColorMatrix type=\'matrix\' values=\'0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0\'/></filter></svg>#grayscale");
					filter: progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);
					-webkit-filter: grayscale(1);
				}	/* 全站变灰 */
				';
		}
		if ($tplArr['TS_navWidthMode'] == 1 && strlen($tplArr['TS_jieriImg']) > 3){
			$cssStr .= '
				body { background:#ffffff url(../../inc_img/jieriBg/'. $tplArr['TS_jieriImg'] .') no-repeat 50% 0; padding-top:'. $tplArr['TS_jieriHeight'] .'px; }	/* 头背景 */
				';
		}
		if ($tplArr['TS_subWebLR'] == 1){
			$cssStr .= '
				.subWebLR { float:right;padding-left:8px; padding-right:0px; }
				.subWebLR .itemBox { margin-right:6px; }
				';
		}
		return File::Write(OT_ROOT .'cache/web/site.css', $cssStr);
	}



	// 快捷登录管理 生成API
	public static function UserApiHtml(){
		global $DB;

		$fileStr1 = $fileStr2 = $fileStr3 = $fileWapStr2 = $fileWapStr3 = '';

		$apiexe = $DB->query('select UA_ID,UA_appType,UA_isHome,UA_isTop,UA_isReg,UA_isLogin from '. OT_dbPref .'userApi where UA_state=1 order by UA_rank ASC');
		while ($row = $apiexe->fetch()){
			if ($row['UA_isTop'] == 1){
				$fileStr1 .= self::UserApiImg($row['UA_appType'],$row['UA_ID'],'Small');
			}
			if ($row['UA_isReg'] == 1){
				$fileStr2 .= self::UserApiImg($row['UA_appType'],$row['UA_ID'],'Long');
				$fileWapStr2 .= self::UserApiImg($row['UA_appType'],$row['UA_ID'],'Long','wap');
			}
			if ($row['UA_isLogin'] == 1){
				$fileStr3 .= self::UserApiImg($row['UA_appType'],$row['UA_ID'],'Long');
				$fileWapStr3 .= self::UserApiImg($row['UA_appType'],$row['UA_ID'],'Long','wap');
			}
		}
		unset($apiexe);
		
		if ($fileStr1 != ''){ $fileStr1 = '<div class="left uebox_api">'. $fileStr1 .'</div>'; }
		
		$Cache = new Cache();
		$Cache->ArrToPhp('userApi',array('imgTop'=>$fileStr1, 'imgReg'=>$fileStr2, 'imgLogin'=>$fileStr3, 'imgRegWap'=>$fileWapStr2, 'imgLoginWap'=>$fileWapStr3));

	}


	// 快捷登录管理 生成图片
	public static function UserApiImg($appType, $apiID, $apiMode, $mode='pc'){
		$goUrl = './api.php?mudi=login&dataID='. $apiID;
		$target = 'target="_blank"';
		switch ($appType){
			case 'qq':		$appName = 'QQ';		break;
			case 'weibo':	$appName = '新浪微博';	break;
			case 'weixin':	$appName = '微信';		break;
			case 'wxmp':	$appName = '微信公众号'; $goUrl = './users.php?mudi=login&mode=wxmp'; $target = ''; break;
			case 'taobao':	$appName = '淘宝';		break;
			case 'alipay':	$appName = '支付宝';	break;
			default:		$appName = '未知';		break;
		}
		if ($mode == 'wap'){
			// $beforeURL	= GetUrl::CurrDir(1);
			if (in_array($appType, array('weixin','wxmp'))){
				return '';
			}else{
				return '<a href="'. $goUrl .'"><img src="./images/api/'. $appType .'.png" alt="用'. $appName .'账号登录" style="width:75px;margin:8px;" /></a>';
			}

		}else{
			return '<a href="'. $goUrl .'" '. $target .'><img src="./inc_img/api/'. $appType . $apiMode .'.png" alt="用'. $appName .'账号登录" style="margin-right:1px;" /></a>';
		}
	}


	// 插件平台 已购买插件 改插件文件名
	public static function PaySoftStateDeal($dataID=0,$type='ID'){
		global $DB;

		if ($dataID == 0){
			$whereStr = '';
		}else{
			if ($type != 'appID'){ $type = 'ID'; }
			$whereStr = ' where PS_'. $type .'='. $dataID;
		}

		$appexe = $DB->query('select PS_ID,PS_appID,PS_state from '. OT_dbPref .'paySoft'. $whereStr);
		while ($row = $appexe->fetch()){
			if ($row['PS_state'] == 0){
				$oldPart = '';
				$newPart = 'del_';
			}else{
				$oldPart = 'del_';
				$newPart = '';
			}
			$appArr = array(
				2	=> array('classAppRss.php'),
				4	=> array('classAppBase.php'),
				6	=> array('classAppWap.php'),
				7	=> array('classAppLogin.php'),
				9	=> array('classAppBbs.php','classAppBbsDeal.php','classAppBbsTpl.php','classAppBbsTplWap.php'),
				10	=> array('classAppTaobaoke.php','classAppTaobaokeWap.php','classAppTaobaokeDeal.php'),
				12	=> array('classAppTplInfo.php'),
				13	=> array('classAppTplBlog.php'),
				14	=> array('classAppTplBlue.php'),
				15	=> array('classAppWeixin.php'),
				17	=> array('classAppMapBaidu.php'),
				18	=> array('classAppUpload.php'),
				19	=> array('classAppForm.php'),
				21	=> array('classAppTopic.php'),
				22	=> array('classAppLogoAdd.php'),
				23	=> array('classAppMoneyPay.php','classAppOnlinePay.php'),
				26	=> array('classAppUserScore.php'),
				28	=> array('classAppGift.php'),
				31	=> array('classAppDashang.php'),
				33	=> array('classAppNewsEnc.php'),
				39	=> array('classAppVideo.php'),
				41	=> array('classAppChangyan.php'),
				42	=> array('classAppBuyOrders.php'),
				43	=> array('classAppNewsVerCode.php'),
				46	=> array('classAppRecom.php'),
				47	=> array('classAppQiandao.php'),
				54	=> array('classAppQuan.php'),
				55	=> array('classAppMail.php'),
				56	=> array('classAppPhone.php'),
				58	=> array('classAppNewsGain.php'),
				62	=> array('classAppWeixinJs.php'),
				63	=> array('classAppToTop.php'),
				64	=> array('classAppTplQiyeBlue.php'),
				65	=> array('classAppIdcPro.php'),
				66	=> array('classAppAutoHtml.php'),
				67	=> array('classAppAutoColl.php'),
				68	=> array('classAppAdminRightNews.php'),
				70	=> array('classAppTplYule.php'),
				71	=> array('classAppTplBlack.php'),
				72	=> array('classAppOssQiniu.php','classAppOssQiniuDeal.php'),
				73	=> array('classAppOssAliyun.php','classAppOssAliyunDeal.php'),
				74	=> array('classAppOssJingan.php','classAppOssJinganDeal.php'),
				75	=> array('classAppOssUpyun.php','classAppOssUpyunDeal.php'),
				76	=> array('classAppUserState1.php'),
				77	=> array('classAppUserGroup.php'),
				78	=> array('classAppUserGroupWork.php'),
				// 79	=> array(''),
				80	=> array('classAppWorkCenter.php'),
				81	=> array('classAppTplWhite.php'),
				82	=> array('classAppWapTplWhite.php'),
				83	=> array('classAppMoneyGive.php'),
				84	=> array('classAppLoginPhone.php'),
				85	=> array('classAppLoginMail.php'),
				86	=> array('classAppOssFtp.php','classAppOssFtpDeal.php'),
				87	=> array('classAppCopyKouling.php'),
				//	=> array('classAppMoneyRecord.php'),	// 财务管理
				);
			$appPath = OT_ROOT .'plugin/';
			if (! empty($appArr[$row['PS_appID']])){
				foreach ($appArr[$row['PS_appID']] as $val){
					if (file_exists($appPath . $oldPart . $val)){
						rename($appPath . $oldPart . $val, $appPath . $newPart . $val);
					}
				}
			}
		}
		unset($appexe);
	}


	// 列表页显示模式
	public static function ListMode($title, $name, $val=1, $className=''){
		return '
		<tr>
			<td align="right" class="'. $className .'">'. $title .'：</td>
			<td align="left" class="newsListImg">
				<label><input type="radio" id="'. $name .'_7" name="'. $name .'" value="7" '. Is::Checked($val,7) .' /><span><img src="temp/newsList7.jpg" style="display:none;" /></span>标题</label>&ensp;&ensp;
				<label><input type="radio" id="'. $name .'_1" name="'. $name .'" value="1" '. Is::Checked($val,1) .' /><span><img src="temp/newsList1.jpg" style="display:none;" /></span>标题+摘要</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="2" '. Is::Checked($val,2) .' /><span><img src="temp/newsList2.jpg" style="display:none;" /></span>图+摘要1</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="4" '. Is::Checked($val,4) .' /><span><img src="temp/newsList4.jpg" style="display:none;" /></span>图+摘要2</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="3" '. Is::Checked($val,3) .' /><span><img src="temp/newsList3.jpg" style="display:none;" /></span>图+标题</label>&ensp;&ensp;
				<label><input type="radio" id="'. $name .'_5" name="'. $name .'" value="5" '. Is::Checked($val,5) .' /><span><img src="temp/newsList5.jpg" style="display:none;" /></span>分类列表</label>&ensp;&ensp;
				<label><input type="radio" id="'. $name .'_6" name="'. $name .'" value="6" '. Is::Checked($val,6) .' /><span><img src="temp/newsList6.jpg" style="display:none;" /></span>分类列表2</label>&ensp;&ensp;
			</td>
		</tr>
		';
	}

	// WAP列表页显示模式
	public static function WapListMode($title, $name, $val=1, $className=''){
		return '
		<tr>
			<td align="right" class="'. $className .'">'. $title .'：</td>
			<td align="left" class="newsListImg">
				<label><input type="radio" id="'. $name .'_7" name="'. $name .'" value="7" '. Is::Checked($val,7) .' /><span><img src="temp/newsList7.jpg" style="display:none;" /></span>标题</label>&ensp;&ensp;
				<label><input type="radio" id="'. $name .'_1" name="'. $name .'" value="1" '. Is::Checked($val,1) .' /><span><img src="temp/newsList1.jpg" style="display:none;" /></span>标题+摘要</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="2" '. Is::Checked($val,2) .' /><span><img src="temp/newsList2.jpg" style="display:none;" /></span>图+摘要1</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="4" '. Is::Checked($val,4) .' /><span><img src="temp/newsList4.jpg" style="display:none;" /></span>图+摘要2</label>&ensp;&ensp;
				<label><input type="radio" name="'. $name .'" value="3" '. Is::Checked($val,3) .' /><span><img src="temp/newsList3.jpg" style="display:none;" /></span>图+标题</label>&ensp;&ensp;
			</td>
		</tr>
		';
	}


	public static function StyleInput($inputName,$inputStyle,$hiddenStr=''){
		$style_b		= Str::GetMark($inputStyle,'font-weight:',';');
		$style_color	= Str::GetMark($inputStyle,'color:',';');

		$retStr = '';
		if (strpos($hiddenStr,'|noB|') === false){
			$retStr .= '&ensp;<label><input type="checkbox" id="'. $inputName .'B" name="'. $inputName .'B" value="1" '. Is::Checked($style_b,'bold') .' onclick=\'StyleInput(this.id, "'. $inputName .'", "b")\'>加粗</label>&ensp;';
		}
		if (strpos($hiddenStr,'|noColor|') === false){
			$retStr .= '&ensp;颜色：<input type="text" id="'. $inputName .'Color" name="'. $inputName .'Color" style="width:52px;" value="'. $style_color .'" />'. Skin::ColorBox('input',$inputName .'Color',$inputName) .'&ensp;';
		}

		return $retStr;
	}

}
?>