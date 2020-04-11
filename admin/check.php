<?php
define('OT_adminROOT', dirname(__FILE__) .'/');

require(dirname(OT_adminROOT) .'/conobj.php');
require(OT_adminROOT .'inc/classMember.php');
require(OT_adminROOT .'inc/classSkin.php');



// 更新缓存文件--网站模式配置信息
if ($sysAdminFile = @include(OT_ROOT .'cache/php/sysAdmin.php')){
	$sysAdminArr = unserialize($sysAdminFile);
}else{
	$Cache = new Cache();
	$Cache->Php('sysAdmin');
	die('
	<br /><br />
	<center>
		加载缓存文件失败，请重新刷新该页面。<a href="#" onclick="document.location.reload();return false;">点击刷新</a>
	</center>
	');
}


// 远程路径排序选择
if ($sysAdminArr['SA_checkUrlMode'] == 1){
	$otcmsUrl1 = 'http://otcms2.oneti.cn/';
	$otcmsUrl2 = 'http://check2.otcms.com/';
	$otcmsUrl3 = 'http://otcms2.bai35.com/';
	$otcmsUrl4 = 'http://otcms2.otcms.org/';
}elseif ($sysAdminArr['SA_checkUrlMode'] == 2){
	$otcmsUrl1 = 'http://otcms2.bai35.com/';
	$otcmsUrl2 = 'http://check2.otcms.com/';
	$otcmsUrl3 = 'http://otcms2.oneti.cn/';
	$otcmsUrl4 = 'http://otcms2.otcms.org/';
}elseif ($sysAdminArr['SA_checkUrlMode'] == 3){
	$otcmsUrl1 = 'http://otcms2.otcms.org/';
	$otcmsUrl2 = 'http://check2.otcms.com/';
	$otcmsUrl3 = 'http://otcms2.oneti.cn/';
	$otcmsUrl4 = 'http://otcms2.bai35.com/';
}else{
	$otcmsUrl1 = 'http://check2.otcms.com/';
	$otcmsUrl2 = 'http://otcms2.oneti.cn/';
	$otcmsUrl3 = 'http://otcms2.bai35.com/';
	$otcmsUrl4 = 'http://otcms2.otcms.org/';
}



// 检测超时退出
$exitNewTime = time();
$exitOldTime = intval(@$_SESSION[OT_SiteID .'exitOldTime']);
if ($sysAdminArr['SA_exitMinute'] > 0 && $exitOldTime + ($sysAdminArr['SA_exitMinute'] * 60) < $exitNewTime){
	if (in_array(basename($_SERVER['PHP_SELF']),array('index.php','admin_cl.php'))==false){
		if (@$exitTimeSkip != 'true'){
			if ($exitOldTime == 0){
				JS::AlertHrefEnd('请先登录。','admin_cl.php?mudi=exit&nohrefStr=close');
			}else{
				JS::AlertHrefEnd('您超过'. $sysAdminArr['SA_exitMinute'] .'分钟没动静，为了后台安全，请重新登录。','admin_cl.php?mudi=exit&nohrefStr=close');
			}
		}
	}
}else{
	$_SESSION[OT_SiteID .'exitOldTime'] = $exitNewTime;
}


// 获取用户登录信息
$user_ID	= intval(@$_SESSION[OT_SiteID .'memberID']);
$user_name	= Str::RegExp(@$_SESSION[OT_SiteID .'memberUsername'],'sql');
$user_pwd	= Str::RegExp(@$_SESSION[OT_SiteID .'memberUserpwd'],'sql');
$user_ip	= @$_SESSION[OT_SiteID .'memberUserIP'];
$user_time	= @$_SESSION[OT_SiteID .'memberTime'];
$user_realname	= @$_SESSION[OT_SiteID .'memberRealName'];

//$user_info		= trim(@$_SESSION['OT_userInfo']);
If ($user_ID == 0 && $sysAdminArr['SA_userSaveMode'] == 2){
	$user_info		= trim(@$_COOKIE[OT_SiteID .'memberInfo']);
	$user_realname	= trim(@$_COOKIE[OT_SiteID .'memberRealName']);
	list($user_ID, $user_name, $user_pwd, $user_ip, $user_time, $user_realname) = $user_info ? explode("\t",Encrypt::PwdDecode($user_info,$sysAdminArr['SA_adminLoginKey'])) : array(0,'','','','','');

	$_SESSION[OT_SiteID .'memberID'] = $user_ID;
	$_SESSION[OT_SiteID .'memberUsername'] = $user_name;
	$_SESSION[OT_SiteID .'memberUserpwd'] = $user_pwd;
	$_SESSION[OT_SiteID .'memberUserIP'] = $user_ip;
	$_SESSION[OT_SiteID .'memberTime'] = $user_time;
	$_SESSION[OT_SiteID .'memberRealName'] = $user_realname;
}


// 定义配置和常用变量
$dbPathPart	= '../';
$rightBody	= '<body style="overflow-y: hidden; margin-top: 4px;">';
$menuFileID = 0;
$menuTreeID = 0;
$nohrefStr	= OT::GetStr('nohrefStr');
$mudi		= OT::GetStr('mudi');
$dataType	= OT::GetRegExpStr('dataType','sql');
$dataTypeCN	= OT::GetStr('dataTypeCN');
$pcTplArr = array('default/','idc_def/','qiye_def/','qiye_blue/','def_blog/','def_blue/','def_female/','def_info/','def_yule/','def_black/','def_white/','def_xiaodao/','def_media/');
$wapTplArr = array('default/','def_white/','def_xiaodao/','def_media/');
	//if (empty($dataType)){ $dataType = OT::PostRegExpStr('dataType','sql'); }


// 创建用户对象
$MB = new Member( OT_SiteID, $DB, $sysAdminArr, array(
	'userID'		=> $user_ID,
	'userName'		=> $user_name,
	'userPwd'		=> $user_pwd,
	'userIp'		=> $user_ip,
	'userTime'		=> $user_time,
	'userRealname'	=> $user_realname,
	'userRightStr'	=> @$_SESSION[OT_SiteID .'memberRight'],
	) );


// 创建皮肤对象
$skin = new Skin( $sysAdminArr, array(
	'mudi'		=> $mudi,
	'rightBody'		=> $rightBody,
	'nohrefStr'		=> $nohrefStr,
	) );



// 网钛授权必备数组
function OTauthArr(){
	global $sysAdminArr;

	$AT_URL		= GetUrl::CurrDir(1);
	$AT_userIP	= Users::GetIp();
	$AT_sign	= md5($sysAdminArr['SA_softID'] .'|'. $sysAdminArr['SA_domainID'] .'|'. $AT_userIP . $AT_URL . OT_UPDATETIME . OT_Database);

	$retArr = array(
		'softID'	=> $sysAdminArr['SA_softID'] ,
		'softCode'	=> $sysAdminArr['SA_softCode'] ,
		'domainID'	=> $sysAdminArr['SA_domainID'] ,
		'domainCode'=> $sysAdminArr['SA_domainCode'] ,
		'OT_URL'	=> $AT_URL ,
		'OT_userIP' => $AT_userIP ,
		'OT_ver'	=> OT_UPDATETIME ,
		'OT_db'		=> OT_Database ,
		'OT_sign'	=> $AT_sign ,
		'OT_time'	=> TimeDate::Get()
		);

	return $retArr;
}

// 网钛授权
function OTauthWeb($authType, $authFile, $paraArr){
	global $sysAdminArr,$otcmsUrl1,$otcmsUrl2,$otcmsUrl3;

	$paraArr = array_merge($paraArr, OTauthArr());

	if ($sysAdminArr['SA_sendUrlMode']==0 || $authType=='coll'){
		$retWebHtml = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $otcmsUrl1 . $authFile, 'UTF-8', $paraArr, 'note') .'<!-- [1:'. $otcmsUrl1 . $authFile .'] -->';
		if (strpos($retWebHtml,'(OTCMS)') === false){
			$retWebHtml = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $otcmsUrl2 . $authFile, 'UTF-8', $paraArr, 'note') .'<!-- [2:'. $otcmsUrl2 . $authFile .'] -->';
			if (strpos($retWebHtml,'(OTCMS)') === false){
				$retWebHtml = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $otcmsUrl3 . $authFile, 'UTF-8', $paraArr, 'note') .'<!-- [3:'. $otcmsUrl3 . $authFile .'] -->';
				if (strpos($retWebHtml,'(OTCMS)') === false){
					$retWebHtml = ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'POST', $otcmsUrl4 . $authFile, 'UTF-8', $paraArr, 'note') .'<!-- [4:'. $otcmsUrl4 . $authFile .'] -->';
				}
			}
		}/**/
		// die($retWebHtml);
		if (substr($retWebHtml,0,5)=='False'){
			switch ($authType){
				case 'coll':
					$retWebStr = '采集系统无法访问外部网站';
					break;
			
				case 'sysCheckFile': case 'makeHtml': case 'userApi':
					$retWebStr = '系统无法访问网钛验证系统';
					break;
			
				default :
					$retWebStr = '系统无法访问外部网站';
					break;
			}
			$retWebHtml=$retWebStr .'，请检查该空间是否支持访问外网。请重试下，还不行可以切换模式看看（[管理员专区]-[后台参数配置] 后台授权页面模式 选择 AJAX）
				&ensp;&ensp;<input type="button" value="测试百度连接" onclick=\'CheckCollUrl("baidu")\' />
				&ensp;&ensp;<input type="button" value="测试网易连接" onclick=\'CheckCollUrl("163")\' />
				&ensp;&ensp;<input type="button" value="测试网钛连接" onclick=\'CheckCollUrl("otcms")\' />
				&ensp;&ensp;<input type="button" value="测试网钛2连接" onclick=\'CheckCollUrl("otcms2")\' />
				&ensp;&ensp;<input type="button" value="测试网钛3连接" onclick=\'CheckCollUrl("otcms3")\' />
				<!-- authFalse --><!-- noRemote --><input type="hidden" id="authState" name="authState" value="false" />
				';
		}

		return $retWebHtml;

	}else{
		if (is_array($paraArr)){
			$paraStr = http_build_query($paraArr);	// 相反函数 parse_str()
		}else{
			$paraStr = $paraArr;
		}
		echo('
		<div id="ajaxAuth"></div>
		<script language="javascript" type="text/javascript">
		$.ajax({
			type : "post",
			async: false,
			url : "'. $otcmsUrl1 . $authFile .'",
			dataType : "text",
			data:"'. $paraStr .'",
			success : function(dataStr){
				// alert(JsToHtml(dataStr));
				document.getElementById("ajaxAuth").innerHTML = JsToHtml(dataStr);
			');
					if ($authType == 'system'){
						echo('
						CheckHtmlUrlDir();
						CheckDynWebUrlMode();
						CheckHtmlInfoTypeDir2();
						CheckHtmlDatetimeDir();
						');
					}
					
			echo('
			},
			error:function(){
				alert("AJAX获取授权页面信息失败，请重试下，还不行可以切换模式看看（[管理员专区]-[后台参数配置] 后台授权页面模式 选择 默认）");
			}
		});

		WindowHeight(0);setTimeout("WindowHeight(0)",2000);
		</script>
		');
		return '<!-- (OTCMS) -->';

	}

}
?>