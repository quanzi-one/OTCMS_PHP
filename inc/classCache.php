<?php
if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class Cache{
	// 构造函数
	public function __construct(){

	}


	// 更新配置文件（表特定字段所有内容）
	function PhpTypeArr($tabName,$mode=''){
		global $DB;

		$retArr = array();

		switch ($tabName){
			case 'paySoft':
				$ccexe = $DB->query('select PS_type,PS_useDate,PS_currTime,PS_state from '. $DB->mDbPref .'paySoft');
				while ($row = $ccexe->fetch()){
					$retArr[$row['PS_type']] = $row;
				}
				unset($ccexe);
				break;

			case 'userGroup':
				$ccexe = $DB->query('select UG_ID,UG_theme from '. $DB->mDbPref .'userGroup');
				while ($row = $ccexe->fetch()){
					$retArr[$row['UG_ID']] = $row['UG_theme'];
				}
				unset($ccexe);
				break;

			default :
				die('表名：'. $tabName .' 不正确');
				break;
		}

		//print_r($retArr);die();
		$content = "return '". str_replace("'","\'",serialize($retArr)) ."';";
		
		return $this->WritePhp($tabName,$content);
	}


	// 更新配置文件（表所有内容）
	function Php($tabName,$mode=''){
		global $DB,$collDB;
/*
		if (in_array($tabName,array(
			'system', 'sysAdmin', 'sysImages', 'userSys', 'infoSys', 'bbsSys', 'tplSys', 'wap', 'taokeSys', 'taokeSys', 'actSys', 'moneySys'
				))==false){
			die('该表不在允许更新缓存列表中.');
		}*/
		$fileName=str_replace($DB->mDbPref,'',$tabName);

		if ($tabName == 'collSys'){
			if (OT_Database == 'sqlite'){
				$ccexe = $collDB->query('select * from '. $collDB->mDbPref . $tabName);
			}else{
				$ccexe = $DB->query('select * from '. $DB->mDbPref . $tabName);
			}
		}else{
			$ccexe = $DB->query('select * from '. $DB->mDbPref . $tabName);
		}
		if ($mode == 'arr'){
			$content = "\$". $fileName .'Arr = array('. PHP_EOL;
			foreach ($ccexe->fetch() as $key => $value){
				$content .= "\t\"". addslashes($key)."\" => ". $this->FieldStr($value) .",". PHP_EOL;
			}
			$content .= "\t'creatTime' => '". TimeDate::Get() ."'". PHP_EOL;
			$content .= ');';
		
		}else{
			$ccRow = $ccexe->fetchAll();
			$content = "return '". str_replace("'","\'",serialize($ccRow[0])) ."';";
			// print_r($ccRow[0]);die();

		}
		unset($ccexe);

		return $this->WritePhp($fileName,$content);
	}


	// 数组缓存
	function ArrToPhp($tabName,$dataArr,$mode=0){
		global $DB;

		// 列出所有记录（一维数组）
		$ccexe = $DB->query('select * from '. $DB->mDbPref . $tabName);
			$fileName=str_replace($DB->mDbPref,'',$tabName);
			$content = "\$". $fileName ."Arr = array(". PHP_EOL;
			foreach ($dataArr as $key => $value){
				$content .= "\t\"". addslashes($key)."\" => ". $this->FieldStr($value,$mode) .",". PHP_EOL;
			}
			$content .= "\t'creatTime' => '". TimeDate::Get() ."'". PHP_EOL;
			$content .= ');';
		unset($ccexe);

		return $this->WritePhp($fileName,$content);
	}


	// JS缓存
	function Js($tabName){
		global $DB;

		$jsFileStr = '';
		switch ($tabName){
			case 'infoSys':
				$jsexe=$DB->query('select IS_isNewsReply,IS_newsReplyMode,IS_isNoCollPage,IS_eventStr,IS_copyAddiStr from '. $DB->mDbPref .'infoSys');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
					var IS_isNewsReply='. $row['IS_isNewsReply'] .';
					var IS_newsReplyMode='. $row['IS_newsReplyMode'] .';
					var IS_isNoCollPage='. $row['IS_isNoCollPage'] .';
					var IS_eventStr="'. $row['IS_eventStr'] .'";
					var IS_copyAddiStr="'. Str::MoreReplace($row['IS_copyAddiStr'],'js') .'";
					';
				}
				unset($jsexe);
				break;

			case 'system':
				$jsexe=$DB->query('select SYS_isClose,SYS_closeNote,SYS_verCodeMode,SYS_isAjaxErr,SYS_isFloatAd,SYS_eventStr,SYS_newsListUrlMode,SYS_newsListFileName,SYS_isWap,SYS_isPcToWap,SYS_wapUrl,SYS_jsTimeStr,SYS_adTimeStr from '. $DB->mDbPref .'system');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
						var SYS_isClose='. $row['SYS_isClose'] .';
						var SYS_closeNote="'. Str::MoreReplace($row['SYS_closeNote'],'js') .'";
						var SYS_verCodeMode='. $row['SYS_verCodeMode'] .';
						var SYS_isAjaxErr='. $row['SYS_isAjaxErr'] .';
						var SYS_isFloatAd='. $row['SYS_isFloatAd'] .';
						var SYS_eventStr="'. $row['SYS_eventStr'] .'";
						var SYS_newsListUrlMode="'. $row['SYS_newsListUrlMode'] .'";
						var SYS_newsListFileName="'. $row['SYS_newsListFileName'] .'";
						var SYS_isWap='. $row['SYS_isWap'] .';
						var SYS_isPcToWap='. $row['SYS_isPcToWap'] .';
						var SYS_wapUrl="'. Str::MoreReplace($row['SYS_wapUrl'],'js') .'";
						var SYS_jsTimeStr="'. $row['SYS_jsTimeStr'] .'";
						var SYS_adTimeStr="'. $row['SYS_adTimeStr'] .'";
						';
				}
				unset($jsexe);
				break;

			case 'tplSys':
				$jsexe=$DB->query('select TS_skinPopup,TS_navMode,TS_homeFlashMode from '. $DB->mDbPref .'tplSys');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
						var TS_skinPopup="'. $row['TS_skinPopup'] .'";
						var TS_navMode='. $row['TS_navMode'] .';
						var TS_homeFlashMode='. $row['TS_homeFlashMode'] .';
						';
				}
				unset($jsexe);
				break;

			case 'userSys':
				$jsexe=$DB->query('select US_isUserSys,US_isLogin from '. $DB->mDbPref .'userSys');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
						var US_isUserSys='. $row['US_isUserSys'] .';
						var US_isLogin='. $row['US_isLogin'] .';
						';
				}
				unset($jsexe);
				break;

			case 'taokeSys':
				$jsexe=$DB->query('select TS_pid,TS_pid2,TS_appkey,TS_signCode,TS_goodsJs,TS_isGoodsBox,TS_isNewsGoods from '. $DB->mDbPref .'taokeSys');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
						var TS_pid="'. $row['TS_pid'] .'";
						var TS_pid2="'. $row['TS_pid2'] .'";
						var TS_appkey="'. $row['TS_appkey'] .'";
						var TS_signCode="'. $row['TS_signCode'] .'";
						var TS_goodsJs='. $row['TS_goodsJs'] .';
						var TS_isGoodsBox='. $row['TS_isGoodsBox'] .';
						var TS_isNewsGoods='. $row['TS_isNewsGoods'] .';
						';
				}
				unset($jsexe);
				break;

			case 'autoRunSys':
				$jsexe=$DB->query('select ARS_dayDate,ARS_runMode,ARS_runArea,ARS_isSoftBak,ARS_softBakTime,ARS_softBakMin,ARS_isDbBak,ARS_dbBakTime,ARS_dbBakMin,ARS_isTimeRun,ARS_timeRunMin,ARS_timeRunTime,ARS_isHtmlHome,ARS_htmlHomeTime,ARS_htmlHomeWapTime,ARS_htmlHomeMin,ARS_isHtmlList,ARS_htmlListTime,ARS_htmlListWapTime,ARS_htmlListMin,ARS_isHtmlShow,ARS_htmlShowTime,ARS_htmlShowWapTime,ARS_htmlShowMin,ARS_isColl,ARS_collTime,ARS_collMin from '. $DB->mDbPref .'autoRunSys');
				while ($row = $jsexe->fetch()){
					if (! strtotime($row['ARS_timeRunTime'])){ $row['ARS_timeRunTime'] = '2010-01-01'; }
					$jsFileStr = '
						var ARS_dayDate="'. $row['ARS_dayDate'] .'";
						var ARS_runMode='. intval($row['ARS_runMode']) .';
						var ARS_runArea="'. $row['ARS_runArea'] .'";
						var ARS_isTimeRun='. $row['ARS_isTimeRun'] .';
						var ARS_timeRunMin='. $row['ARS_timeRunMin'] .';
						var ARS_timeRunTime="'. TimeDate::Get('datetime',$row['ARS_timeRunTime']) .'";
						var ARS_isSoftBak='. $row['ARS_isSoftBak'] .';
						var ARS_softBakMin='. $row['ARS_softBakMin'] .';
						var ARS_softBakTime="'. TimeDate::Get('datetime',$row['ARS_softBakTime']) .'";
						var ARS_isDbBak='. $row['ARS_isDbBak'] .';
						var ARS_dbBakMin='. $row['ARS_dbBakMin'] .';
						var ARS_dbBakTime="'. TimeDate::Get('datetime',$row['ARS_dbBakTime']) .'";
						var ARS_isHtmlHome='. $row['ARS_isHtmlHome'] .';
						var ARS_htmlHomeTime="'. TimeDate::Get('datetime',$row['ARS_htmlHomeTime']) .'";
						var ARS_htmlHomeWapTime="'. TimeDate::Get('datetime',$row['ARS_htmlHomeWapTime']) .'";
						var ARS_htmlHomeMin='. $row['ARS_htmlHomeMin'] .';
						var ARS_isHtmlList='. $row['ARS_isHtmlList'] .';
						var ARS_htmlListTime="'. TimeDate::Get('datetime',$row['ARS_htmlListTime']) .'";
						var ARS_htmlListWapTime="'. TimeDate::Get('datetime',$row['ARS_htmlListWapTime']) .'";
						var ARS_htmlListMin='. $row['ARS_htmlListMin'] .';
						var ARS_isHtmlShow='. $row['ARS_isHtmlShow'] .';
						var ARS_htmlShowTime="'. TimeDate::Get('datetime',$row['ARS_htmlShowTime']) .'";
						var ARS_htmlShowWapTime="'. TimeDate::Get('datetime',$row['ARS_htmlShowWapTime']) .'";
						var ARS_htmlShowMin='. $row['ARS_htmlShowMin'] .';
						var ARS_isColl='. $row['ARS_isColl'] .';
						var ARS_collTime="'. TimeDate::Get('datetime',$row['ARS_collTime']) .'";
						var ARS_collMin='. $row['ARS_collMin'] .';
						var ARS_timeRunTimer='. strtotime($row['ARS_timeRunTime']) .';
						var ARS_htmlHomeTimer='. strtotime($row['ARS_htmlHomeTime']) .';
						var ARS_htmlHomeWapTimer='. strtotime($row['ARS_htmlHomeWapTime']) .';
						var ARS_htmlListTimer='. strtotime($row['ARS_htmlListTime']) .';
						var ARS_htmlListWapTimer='. strtotime($row['ARS_htmlListWapTime']) .';
						var ARS_htmlShowTimer='. strtotime($row['ARS_htmlShowTime']) .';
						var ARS_htmlShowWapTimer='. strtotime($row['ARS_htmlShowWapTime']) .';
						var ARS_collTimer='. strtotime($row['ARS_collTime']) .';
						';
				}
				unset($jsexe);
				break;

			case 'wap':
				$jsexe=$DB->query('select WAP_isCopyKouling,WAP_copyKoulingStr from '. $DB->mDbPref .'wap');
				while ($row = $jsexe->fetch()){
					$jsFileStr = '
						var WAP_isCopyKouling="'. $row['WAP_isCopyKouling'] .'";
						var WAP_copyKoulingStr="'. Str::MoreReplace($row['WAP_copyKoulingStr'],'js') .'";
						';
				}
				unset($jsexe);
				break;

		}

		return $this->WriteJs($tabName,$jsFileStr . PHP_EOL .'// create for '. TimeDate::Get());
	}


	function FieldStr($fieldValue,$mode=0){
		if ($fieldValue != '' && is_numeric($fieldValue)){
			return $fieldValue;
		}else{
			if ($mode == 1){
				return "\"". str_replace(array('?'. chr(62), chr(60) .'?'), array('?&gt;', '&lt;?'), $fieldValue) ."\"";
			}else{
				return "\"". str_replace(array('?'. chr(62), chr(60) .'?', '"'), array('?&gt;', '&lt;?', '\"'), $fieldValue) ."\"";
			}
		}
	}


	// 写入缓存文件(php格式)
	function WritePhp($fileName, $cachedata = ''){
		$cacheDir = OT_ROOT .'cache/php/';
		$filePath = $cacheDir . $fileName .'.php';

		$judRes = true;
		try{
			if( ! is_dir($cacheDir) ){
				@mkdir($cacheDir, 0755);
			}
			if($fp = @fopen($filePath, 'wb')) {
				@fwrite($fp, '<?php'. PHP_EOL .'// Created on '. TimeDate::Get() . PHP_EOL . PHP_EOL ."if (! defined('OT_ROOT')){ exit('Access Denied'); }". PHP_EOL . PHP_EOL . $cachedata . PHP_EOL . PHP_EOL .'?>');
				@fclose($fp);
				@chmod($filePath, 0755);
			} else {
				$judRes = false;
				//echo('Can not write to cache files, please check directory ./cache/php/ .');
				//exit;
			}
		}catch (Exception $e){
			$judRes = false;
		}
		return $judRes;
	}


	// 写入缓存文件(htm格式)
	function WriteHtml($fileName, $cachedata = ''){
		$cacheDir = OT_ROOT .'cache/html/';
		$filePath = $cacheDir . $fileName .'.html';

		$judRes = true;
		try{
			if( ! is_dir($cacheDir) ){
				@mkdir($cacheDir, 0755);
			}
			if($fp = @fopen($filePath, 'wb')) {
				@fwrite($fp, $cachedata);
				@fclose($fp);
				@chmod($filePath, 0755);
			} else {
				$judRes = false;
				//echo('不能写入该缓存文件，请检查该目录 ./cache/html/ .');
				//exit;
			}
		}catch (Exception $e){
			$judRes = false;
		}
		return $judRes;
	}


	// 写入页面缓存文件
	function WriteWeb($fileName, $cachedata = ''){
		$cacheDir = OT_ROOT .'cache/web/';
		$filePath = $cacheDir . $fileName;

		$judRes = true;
		try{
			if( ! is_dir($cacheDir) ){
				@mkdir($cacheDir, 0755);
			}
			if($fp = @fopen($filePath, 'wb')) {
				@fwrite($fp, $cachedata);
				@fclose($fp);
				@chmod($filePath, 0755);
			} else {
				$judRes = false;
				//echo('不能写入该缓存文件，请检查该目录 ./cache/html/ .');
				//exit;
			}
		}catch (Exception $e){
			$judRes = false;
		}
		return $judRes;
	}


	// 写入缓存文件(js格式)
	function WriteJs($fileName, $cachedata = ''){
		$cacheDir = OT_ROOT .'cache/js/';
		$filePath = $cacheDir . $fileName .'.js';

		$judRes = true;
		try{
			if( ! is_dir($cacheDir) ){
				@mkdir($cacheDir, 0755);
			}
			if($fp = @fopen($filePath, 'wb')) {
				@fwrite($fp, $cachedata);
				@fclose($fp);
				@chmod($filePath, 0755);
			} else {
				$judRes = false;
				//echo('不能写入该缓存文件，请检查该目录 ./cache/js/ .');
				//exit;
			}
		}catch (Exception $e){
			$judRes = false;
		}
		return $judRes;
	}


	// 读取缓存文件
	public static function PhpFile($fileName){
		if ($str = @include(OT_ROOT .'cache/php/'. $fileName .'.php')){
			return unserialize($str);
		}else{
			$Cache = new Cache();
			$Cache->Php($fileName);
			die('
			<br /><br />
			<center>
				加载'. $fileName .'配置文件失败，<a href="#" onclick="document.location.reload();">[点击重新刷新]</a>
			</center>
			');
//			return array();
		}
	}


	// 判断读取页面缓存内容
	public static function CheckWebCache($fileName,$isTimeInfo=false){
		global $systemArr;

		$retStr = false;
		if ($systemArr['SYS_htmlCacheMin'] >0){
			$filePath = OT_ROOT .'cache/html/'. str_replace('|',',',$fileName) .'.html';
			if (File::IsExists($filePath)){
				$fileRevTime = File::GetRevTime($filePath);
				if (TimeDate::Diff('n',$systemArr['SYS_htmlCacheTime'],$fileRevTime)>=0 && TimeDate::Diff('n',$fileRevTime,TimeDate::Get())<$systemArr['SYS_htmlCacheMin']){
					$retStr = File::Read($filePath) . ($isTimeInfo ? '<!-- Cache Html For '. $fileRevTime .'['. $systemArr['SYS_htmlCacheMin'] .'] -->' : '');
				}
			}
		}
		return $retStr;
	}


	// 判断读取页面缓存内容
	public static function WriteWebCache($fileName,$cacheData){
		global $systemArr;

		if ($systemArr['SYS_htmlCacheMin'] >0){
			$filePath = OT_ROOT .'cache/html/'. str_replace('|',',',$fileName) .'.html';
			$retResult = File::Write($filePath,$cacheData);
			return $retResult;
		}else{
			return false;
		}
	}


	// 判断读取页面缓存内容
	public static function UpdateConfigJs(){
		global $DB;

		$configFileStr = '
		// 系统参数
		'. File::Read(OT_ROOT .'cache/js/system.js') .'

		// 模板参数
		'. File::Read(OT_ROOT .'cache/js/tplSys.js') .'

		// 会员参数
		'. File::Read(OT_ROOT .'cache/js/userSys.js') .'

		// 淘客参数
		'. File::Read(OT_ROOT .'cache/js/taokeSys.js') .'

		// 文章参数
		'. File::Read(OT_ROOT .'cache/js/infoSys.js') .'

		if (GetCookie("wap_otcms") != "pc"){
			// 判断是否为手机端访问，跳转到相应页面
			if (typeof(SYS_isWap) == "undefined"){ SYS_isWap = 1; }
			if (typeof(SYS_isPcToWap) == "undefined"){ SYS_isPcToWap = 0; }
			if (SYS_isWap==1 && SYS_isPcToWap>=1 && ("|home|list|show|web|users|message|bbsHome|bbsList|bbsShow|bbsWrite|gift|form|goodsList|").indexOf("|"+ webTypeName +"|")!=-1){
				JudGoWap();
			}
		}
		';

		if (File::Write(OT_ROOT .'cache/js/configJs.js', $configFileStr)){
			$DB->query('update '. OT_dbPref .'autoRunSys set ARS_dayDate='. $DB->ForTime(TimeDate::Get('date')));
			$Cache = new Cache();
			$Cache->Php('autoRunSys');
			$Cache->Js('autoRunSys');

			return true;
		}else{
			return false;
		}
	}


	// 获取php缓存文件
	public static function GetPhpFile($fileName){
		$filePath = OT_ROOT .'cache/php/'. $fileName .'.php';
		if (! file_exists($filePath)){
			return array('res'=>false, 'note'=>$fileName .'缓存文件不存在');
		}
		$retStr = trim(file_get_contents($filePath));
		if (strlen($retStr) < 15){
			return array('res'=>false, 'note'=>$fileName .'获取到的内容长度不足15字符');
		}
		$retStr = trim(substr($retStr, 15));
		$retArr = @unserialize($retStr);
		if (! $retArr){
			return array('res'=>false, 'note'=>$fileName .'获取到的内容不可解序列化');
		}
		if (! is_array($retArr)){
			return array('res'=>false, 'note'=>$fileName .'获取到的内容序列化后不是数组');
		}
		return array_merge(array('res'=>true, 'timestamp'=>0), $retArr);
	}

	// 设置php缓存文件
	public static function SetPhpFile($fileName, $content){
		$filePath = OT_ROOT .'cache/php/'. $fileName .'.php';
		$fp = fopen($filePath, 'w');
		fwrite($fp, '<?php exit();?>'. serialize($content));
		fclose($fp);
	}

}
?>