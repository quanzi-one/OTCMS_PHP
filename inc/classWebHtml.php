<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class WebHtml{
	protected $mCharset;		// 系统编码
	public $mErrStr;			// 错误信息
	public $mGetUrlMode;		// 获取网址模式
	public $judProxy = false;	// 是否使用代理
	public $proxyIp = '';		// 使用代理IP:端口
	public $proxyErr = '';		// 使用代理错误信息

	function __construct($getUrlMode=99, $charset='UTF-8'){
		global $sysAdminArr;
		if ($getUrlMode==99 && isset($sysAdminArr['SA_collUrlMode'])){
			$getUrlMode = $sysAdminArr['SA_collUrlMode'];//die($getUrlMode);
		}
		$this->mErrStr		= '';
		$this->mCharset		= $charset;
		$this->mGetUrlMode	= $getUrlMode;
	}


	// 获取网页源码（限制读取时间）
	// URL：网页地址；charset：编码
	function GetCode($URL, $charset='UTF-8'){
		global $DB,$systemArr;

		if (empty($URL)){
			$this->mErrStr='网址错误';
			return 'False';
		}
		
		class_exists('ReqUrl',false) or require(OT_ROOT .'inc/classReqUrl.php');

		if ($this->judProxy && strlen($systemArr['SYS_proxyIpList']) > 8){
			$proxyIp = '';
			$proxyPort = 80;
			$currArr = Area::ListPoint('proxyIp',$systemArr['SYS_proxyIpList'],'arr');
			$oneArr = explode(':', $currArr['str']);
			$proxyIp = $oneArr[0];
			if (count($oneArr) >= 2){ $proxyPort = $oneArr[1]; }
			$this->proxyIp =  '【第'. (intval($currArr['point'])+1) .'行】'. $proxyIp .':'. $proxyPort;

			$retArr = ReqUrl::ProxyCurl('GET', $URL, array('ip'=>$proxyIp,'port'=>$proxyPort), $charset);
			if ($retArr['res']){ $this->proxyErr = ''; }else{ $this->proxyErr = $retArr['note']; }
			// print_r($retArr);die('IP:'. $proxyIp .':'. $proxyPort);
		}else{
			$retArr = ReqUrl::UseAuto($this->mGetUrlMode, 'GET', $URL, $charset);
		}
		if (! $retArr['res']){ $retStr='False'; }else{ $retStr=$retArr['note']; }

		return $retStr;
	}



	// 截取字符串
	// contentStr：要截取的字符串；startCode：开始字符串；endCode：结束字符串；incStart：是否包含startCode；incEnd：是否包含endCode
	function GetStr($contentStr,$startCode,$endCode,$incStart=false,$incEnd=false){
		if (empty($contentStr)==true || empty($startCode)==true || empty($endCode)==true){
			$this->mErrStr='源代码、开始标签、结束标签任意一个都不能为空';
			return 'False';
		}
		$contentTemp='';
		$Start=-1;
		$Over=-1;
		$contentTemp=strtolower($contentStr);
		$startCode=strtolower($startCode);
		$endCode=strtolower($endCode);
		$Start = strpos($contentTemp, $startCode);
		if (! is_numeric($Start)){
			$this->mErrStr='开始标签定位不到内容';
			return 'False';
		}else{
			if ($incStart==false){
				$Start += strlen($startCode);
			}
		}
		$Over=$Start + strpos(substr($contentTemp,$Start),$endCode);
		if ($Over<=0 || $Over<=$Start){
			$this->mErrStr='结束标签定位不到内容';
			return 'False';
		}else{
			if ($incEnd==true){
				$Over += strlen($endCode);
			}
		}

		return substr($contentStr,$Start,$Over-$Start);
	}


	// 提取链接地址，以[OT]分隔
	// contentStr：提取地址的原字符；startCode：开始字符串；endCode：结束字符串；incStart：是否包含startCode；incEnd：是否包含endCode
	function GetArrStr($contentStr,$startCode,$endCode,$incStart=false,$incEnd=false){
		if (empty($contentStr)==true || empty($startCode)==true || empty($endCode)==true){
			$this->mErrStr='源代码、开始标签、结束标签任意一个都不能为空';
			return 'False';
		}
		$TempStr='';
		$Templisturl='';
		//die(Str::MoreReplace('/('. $startCode .')(.+?)('. $endCode .')/i','html'));
		preg_match_all('/('. Str::MoreReplace($startCode,'regexp') .')(.+?)('. Str::MoreReplace($endCode,'regexp') .')/i',$contentStr,$matches);
	//	print_r($matches);
	//	die();
		foreach ($matches[2] as $val){
			if ($Templisturl!=$val){
				if ($TempStr==''){
					$TempStr = ($incStart==true ? $startCode : '') . $val . ($incEnd==true ? $endCode : '');
				}else{
					$TempStr .= '[OT]'. $val;
				}
			}
		}

		if ($TempStr==''){
			$this->mErrStr='获取数据集为空';
			return 'False';
		}

		$TempStr=str_replace(array('"',"'",' '),array('','',''),$TempStr);

		if ($TempStr==''){
			$this->mErrStr='获取数据集为空';
			return 'False';
		}else{
			return $TempStr;
		}
	}


	// 将相对地址转换为绝对地址
	// getStrUrl:要转换的相对地址；currUrl:当前网页地址
	function RealUrl($getStrUrl,$currUrl){
		$retValue = '';
		$Pi=0;
		$Ci=0;
		$getStrUrlArr=array();
		$currUrlArr=array();

		if ($getStrUrl=='' || $currUrl=='' || $getStrUrl=='False' || $currUrl=='False'){
			$retValue = $getStrUrl;
		}
		if (substr(strtolower($currUrl),0,7)!='http://' && substr(strtolower($currUrl),0,8)!='https://'){
			$currUrl= GetUrl::HttpHead() . $currUrl;
		}
		$currUrl	= str_replace(array("\\","://"),array("/",":\\\\"),$currUrl);
		$getStrUrl	= str_replace("\\","/",$getStrUrl);

		if (substr($currUrl,-1)!='/' && substr($getStrUrl,0,1)!='#'){
			if (strpos($currUrl,'/') !== false){
				if (strpos(substr($currUrl,strrpos($currUrl,'/')+1),'.') !== false){
				}else{
					$currUrl .= '/';
				}
			}else{
				$currUrl .= '/';
			}
		}
		$currUrlArr=explode('/',$currUrl);

		if (substr(strtolower($getStrUrl),0,7) == 'http://' || substr(strtolower($getStrUrl),0,8) == 'https://'){
			$retValue = str_replace("://",":\\\\",$getStrUrl);

		}else if (substr($getStrUrl,0,1) == '/'){
			$retValue = $currUrlArr[0] . $getStrUrl;

		}else if (substr($getStrUrl,0,2) == './'){
			$getStrUrl=substr($getStrUrl,2);
			if (substr($currUrl,-1)=='/'){   
				$retValue = $currUrl . $getStrUrl;
			}else{
				$retValue = substr($currUrl,0,strrpos($currUrl,'/')+1) . $getStrUrl;
			}

		}else if (substr($getStrUrl,0,3)=='../'){
			while (substr($getStrUrl,0,3)=='../'){
				$getStrUrl=substr($getStrUrl,3);
				$Pi ++;
			}
			for ($Ci=0; $Ci<count($currUrlArr)-1-$Pi; $Ci++){
				if ($retValue!=''){
					$retValue = $retValue .'/'. $currUrlArr[$Ci];
				}else{
					$retValue = $currUrlArr[$Ci];
				}
			}
			if ($retValue==''){ $retValue = $currUrlArr[0]; }
			$retValue = $retValue .'/'. $getStrUrl;

		}else if (substr($getStrUrl,0,1)=='?'){
			if (strpos($currUrl,'?') !== false){   
				$retValue = substr($currUrl,0,strpos($currUrl,'?')) . $getStrUrl;
			}else{
				$retValue = $currUrl . $getStrUrl;
			}

		}else if (substr($getStrUrl,0,1)=='#'){
			if (strpos($currUrl,'#') !== false){   
				$retValue = substr($currUrl,0,strpos($currUrl,'#')) . $getStrUrl;
			}else{
				$retValue = $currUrl . $getStrUrl;
			}

		}else{
			if (strpos($getStrUrl,'/') !== false){
				$getStrUrlArr=explode('/',$getStrUrl);
				if (strpos($getStrUrlArr[0],'.') !== false){
					if (substr($getStrUrl,-1)=='/'){
						$retValue = 'http:\\'. $getStrUrl;
					}else{
						if (strpos($getStrUrlArr[count($getStrUrlArr)-2],'.') !== false){
							$retValue = 'http:\\'. $getStrUrl;
						}else{
							$retValue = 'http:\\'. $getStrUrl .'/';
						}
					}
				}else{
					if (substr($currUrl,-1)=='/'){ 
						$retValue = $currUrl . $getStrUrl;
					}else{
						$retValue = substr($currUrl,0,strrpos($currUrl,'/')+1) . $getStrUrl;
					}
				}
			}else{
				if (strpos($getStrUrl,'.') !== false){
					if (substr($currUrl,-1)=='/'){
						if (in_array(substr(strtolower($getStrUrl),-3),array('.cn','com','net','org'))){
							$retValue = 'http:\\'. $getStrUrl .'/';
						}else{
							$retValue = $currUrl . $getStrUrl;
						}
					}else{
						if (in_array(substr(strtolower($getStrUrl),-3),array('.cn','com','net','org'))){
							$retValue = 'http:\\'. $getStrUrl .'/';
						}else{
							$retValue = substr($currUrl,0,strrpos($currUrl,'/')+1) .'/'. $getStrUrl;
						}
					}
				}else{
					if (substr($currUrl,-1)=='/'){
						$retValue = $currUrl . $getStrUrl .'/';
					}else{
						$retValue = substr($currUrl,0,strrpos($currUrl,'/')+1) .'/'. $getStrUrl .'/';
					}         
				}
			}
		}

		if (substr($retValue,0,1)=='/'){
			$retValue = substr($retValue,1);
		}
		if ($retValue!=''){
			$retValue = str_replace('//','/',$retValue);
			$retValue = str_replace(":\\\\","://",$retValue);
		}else{
			$retValue = $getStrUrl;
		}
		return $retValue;
	}


	// 过滤掉字符中所有的tab和回车和换行
	function ReplaceTrim($strContent,$additionStr){
		if (strpos($additionStr,'|comp|') !== false){
			$strContent = preg_replace('/(\t|\r|\n)/i','',$strContent);
		}

		return $strContent;
	}


	function Jud($isStr){
		if (in_array(strtolower($isStr),array(1,'true',true))){
			return true;
		}else{
			return false;
		}
	}
}



?>