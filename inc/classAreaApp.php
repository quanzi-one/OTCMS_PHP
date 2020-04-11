<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AreaApp{

	// 判断是否存在该插件
	public static function Jud($appID){
		global $DB;

		$chkexe = $DB->query('select PS_ID from '. OT_dbPref .'paySoft where PS_appID='. $appID .' and PS_state=1');
		if ($chkexe->fetch()){
			return true;
		}else{
			return false;
		}
		unset($chkexe);
	}


	// 判断邮箱、手机号是否提醒
	public static function UserTixing($UE_authStr, $userSysArr, $level, $mode='js', $addiStr=''){
		if ($userSysArr['US_isAuthMail'] == 1 && strpos($UE_authStr,'|邮箱|') === false && AppMail::Jud()){
			if ($userSysArr['US_isMustMail'] == 1){
				$alertStr = 'alert("'. $addiStr .'您好，您需要先填写/验证下邮箱，才能进行其他操作。\n'. $userSysArr['US_mustMailStr'] .'");document.location.href="usersCenter.php?mudi=revInfo&revType=mail";';
				if ($mode == 'str'){ die($alertStr); }else{ JS::DiyEnd($alertStr); }
			}elseif ($userSysArr['US_isMustMail'] == 2 && $level >= 2){
				$alertStr = 'if (confirm("'. $addiStr .'您好，建议填写/验证下邮箱。\n'. $userSysArr['US_mustMailStr'] .'\n如现在填写/验证邮箱，请点【确定】，否则点【取消】")){ document.location.href="usersCenter.php?mudi=revInfo&revType=mail"; isTop=true; }';
				if ($mode == 'str'){ echo($alertStr); }else{ JS::Diy($alertStr); }
			}
		}
		if ($userSysArr['US_isAuthPhone'] == 1 && strpos($UE_authStr,'|手机|') === false && AppPhone::Jud()){
			if ($userSysArr['US_isMustPhone'] == 1){
				$alertStr = 'if (typeof(isTop) == "undefined"){ alert("'. $addiStr .'您好，您需要先填写/验证下手机号，才能进行其他操作。\n'. $userSysArr['US_mustPhoneStr'] .'");document.location.href="usersCenter.php?mudi=revInfo&revType=phone"; }';
				if ($mode == 'str'){ die($alertStr); }else{ JS::DiyEnd($alertStr); }
			}elseif ($userSysArr['US_isMustPhone'] == 2 && $level >= 2){
				$alertStr = 'if (typeof(isTop) == "undefined"){ if (confirm("'. $addiStr .'您好，建议填写/验证下手机号。\n'. $userSysArr['US_mustPhoneStr'] .'\n如现在填写/验证手机号，请点【确定】，否则点【取消】")){ document.location.href="usersCenter.php?mudi=revInfo&revType=phone"; isTop=true; } }';
				if ($mode == 'str'){ echo($alertStr); }else{ JS::Diy($alertStr); }
			}
		}
	}


	// 云存储 上传文件处理
	public static function OssNameArr(){
		return array('qiniu','upyun','aliyun','jingan','ftp');
	}

	// 云存储 上传文件处理
	public static function OssTypeCN($oss,$type=''){
		switch ($oss){
			case 'qiniu':	return '七牛云';
			case 'upyun':	return '又拍云';
			case 'aliyun':	return '阿里云';
			case 'jingan':	return '景安云';
			case 'ftp':		return 'FTP云存储';
			default :		return $type;
		}
	}

	// 云存储 获取文件名
	public static function OssFileName($filePath,$filePath2=''){
		if (strlen($filePath2) <= 3){
			if (strpos($filePath,'/') !== false){
				$filePath = substr($filePath,strrpos($filePath,'/')+1);
			}
		}
		return $filePath;
	}

	// 云存储 上传文件处理
	public static function OssDeal($type, $fileName, $filePath){
		global $autoloadItem;
		$autoloadItem = $type;

		$retArr = array('res'=>false, 'code'=>'', 'note'=>'', 'path'=>'');
		switch ($type){
			case 'qiniu':
				$retArr = AppOssQiniuDeal::UpFile($fileName, $filePath);
				break;
		
			case 'upyun':
				$retArr = AppOssUpyunDeal::UpFile($fileName, $filePath);
				break;
		
			case 'aliyun':
				$retArr = AppOssAliyunDeal::UpFile($fileName, $filePath);
				break;
		
			case 'ftp':
				$retArr = AppOssFtpDeal::UpFile($fileName, $filePath);
				break;
		
			default :
				die('type参数目的不明确（'. $type .'）');
				$retArr = array('res'=>false, 'code'=>1, 'note'=>'type参数目的不明确（'. $type .'）', 'path'=>'');
				break;
		}
		// if ($retArr['res']){
			File::Del($filePath);
		// }
		return array_merge($retArr,array('type'=>$type, 'name'=>self::OssTypeCN($type)));
	}

	// 云存储 删除文件处理
	public static function OssDel($type, $fileName){
		global $autoloadItem;
		$autoloadItem = $type;

		$retArr = array('res'=>false, 'code'=>'', 'note'=>'');
		switch ($type){
			case 'qiniu':
				$retArr = AppOssQiniuDeal::DelFile($fileName);
				if (strpos($fileName,'/') !== false){	// $retArr['res'] == false && 
					$retArr = AppOssQiniuDeal::DelFile(AreaApp::OssFileName($fileName));
				}
				break;
		
			case 'upyun':
				$retArr = AppOssUpyunDeal::DelFile($fileName);
				if (strpos($fileName,'/') !== false){	// $retArr['res'] == false && 
					$retArr = AppOssUpyunDeal::DelFile(AreaApp::OssFileName($fileName));
				}
				break;
		
			case 'aliyun':
				$retArr = AppOssAliyunDeal::DelFile($fileName);
				if (strpos($fileName,'/') !== false){	// $retArr['res'] == false && 
					$retArr = AppOssAliyunDeal::DelFile(AreaApp::OssFileName($fileName));
				}
				break;
		
			case 'ftp':
				$retArr = AppOssFtpDeal::DelFile($fileName);
				/* if ($retArr['res'] == false && strpos($fileName,'/') !== false){
					$retArr = AppOssFtpDeal::DelFile(AreaApp::OssFileName($fileName));
				} */
				break;
		
			default :
				die('type参数目的不明确（'. $type .'）');
				$retArr = array('res'=>false, 'code'=>1, 'note'=>'type参数目的不明确（'. $type .'）');
				break;
		}
		return $retArr;
	}

	// 采集系统 获取内容页标题
	public static function CollGetTheme($str){
		return trim(Str::MoreReplace(Str::RegExp($str,'html'),'input'));
	}

	// 采集系统 获取内容页正文
	public static function CollGetContent($contentStr, $hrefCode, $webHtml, $dataArr){
		$contentBakNum = 0;
		if ($contentStr == 'False' && $dataArr['CI_contentBakNum'] > 0){
			if ($dataArr['CI_contentBakNum'] >= 1 && strlen($dataArr['CI_contentBak1Code1']) > 0 && strlen($dataArr['CI_contentBak1Code2']) > 0){
				$contentBakNum = 1;
				$contentStr	= $webHtml->GetStr($hrefCode,$dataArr['CI_contentBak1Code1'],$dataArr['CI_contentBak1Code2'],Is::IncPos($dataArr['CI_incCodeList'],'|contentBak1Code1|'),Is::IncPos($dataArr['CI_incCodeList'],'|contentBak1Code2|'));
			}
			if ($contentStr == 'False' && $dataArr['CI_contentBakNum'] >= 2 && strlen($dataArr['CI_contentBak2Code1']) > 0 && strlen($dataArr['CI_contentBak2Code2']) > 0){
				$contentBakNum = 2;
				$contentStr	= $webHtml->GetStr($hrefCode,$dataArr['CI_contentBak2Code1'],$dataArr['CI_contentBak2Code2'],Is::IncPos($dataArr['CI_incCodeList'],'|contentBak2Code1|'),Is::IncPos($dataArr['CI_incCodeList'],'|contentBak2Code2|'));
			}
		}
		return array('content'=>$contentStr, 'bakNum'=>$contentBakNum);
	}

}

?>