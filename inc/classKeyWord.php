<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class KeyWord{

	// 本地词库
	public static function Get($str, $maxWordNum=5){
		class_exists('Str',false) or require(OT_ROOT .'inc/classStr.php');

		$filePath = OT_ROOT .'inc/keyWord.txt';
		$fp = fopen($filePath,'r'); 
		$content = fread($fp,filesize($filePath));//读文件 
		fclose($fp);

		$wordArr = explode(PHP_EOL, $content);
		$wordArr = array_unique(array_filter($wordArr));

		$retStr = '';
		$okNum = 0;
		foreach ($wordArr as $val){
			if (strpos($str,Str::Filter($val,'eol')) !== false){
				$okNum ++;
				if ($okNum==1){
					$retStr .= $val;
				}else{
					$retStr .= ','. $val;
				}
				if ($maxWordNum>0 && $maxWordNum<=$okNum){ break; }
			}
		}
		return $retStr;
	}



	// 中文分词系统
	public static function GetFc($str, $maxWordNum=5, $judErr=true){
		if (strlen($str) == 0){ return ''; }

		if (! file_exists(OT_ROOT .'tools/pscws4/etc/dict.utf8.xdb')){
			if ($judErr){
				return '分词字库缺失，请到 管理员专区→程序文件检查【可选文件下载】分词字库 处理';
			}else{
				return '';
			}
		}
		class_exists('PSCWS4',false) or require(OT_ROOT .'tools/pscws4/pscws4.class.php');

		$cws = new PSCWS4('utf-8');
		$cws -> set_charset('utf-8');
		$cws -> set_dict(OT_ROOT .'tools/pscws4/etc/dict.utf8.xdb');
		$cws -> set_rule(OT_ROOT .'tools/pscws4/etc/rules.utf8.ini');
		//$cws->set_multi(3);
		$cws -> set_ignore(true);
		//$cws->set_debug(true);
		//$cws->set_duality(true);
		$cws -> send_text(str_replace(array('&nbsp;','&ensp;','&emsp;'),'',$str));
		// 返回的是二维数组, 它又包含: word词本身, weight词重, times次数, attr词性
		$ret = $cws -> get_tops($maxWordNum, 'r,v,p');
		$retStr = '';
		if ( is_array($ret) ){
			foreach ($ret as $val){
				if (strlen($retStr) > 0){ $retStr .= ','; }
				$retStr .= $val['word'];  
			}  
		}
		return $retStr;  
	}  



	// 读取DZ词库（已不能用）
	public static function GetDz($strTitle, $strContent='', $maxWordNum=5){
		class_exists('ReqUrl',false) or require(OT_ROOT .'inc/classReqUrl.php');
		
		$data = ReqUrl::UseAuto(0, 'GET', 'http://keyword.discuz.com/related_kw.html?title='. urlencode($strTitle) .'&content='. urlencode($strContent) .'&ics=utf-8&ocs=utf-8', 'UTF-8', array(), 'note');
		preg_match_all("/<kw>(.*)A\[(.*)\]\](.*)><\/kw>/",$data, $out, PREG_SET_ORDER);

		$retStr='';
		for($i=0; $i<$maxWordNum; $i++){
			if(! empty($out[$i][2])){
				if($retStr){ $retStr .= ','; }
				$retStr .= $out[$i][2];
			}
		}
		return $retStr;

	}

}
?>