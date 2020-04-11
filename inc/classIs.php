<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Is{

	// 判断是否一样，一样返回checked,否者返回空值
	public static function Checked($str,$str2){
		if ( strval($str) == strval($str2) ){ return 'checked="checked"'; }else{ return ''; }
	}

	// 判断是否一样，一样返回selected,否者返回空值
	public static function Selected($str,$str2){
		if ( strval($str) == strval($str2) ){ return 'selected="selected"'; }else{ return ''; }
	}


	// 判断是否一样，一样返回checked,否者返回空值
	public static function InstrChecked($str,$str2){
		if (strpos($str,$str2) !== false){ return 'checked="checked"'; }else{ return ''; }
	}

	// 判断是否一样，一样返回selected,否者返回空值
	public static function InstrSelected($str,$str2){
		if (strpos($str,$str2) !== false){ return 'selected="selected"'; }else{ return ''; }
	}

	// 如果字符串含有字符为true，否则为false
	public static function IncPos($str,$val){
		if (strpos($str,$val) !== false){
			return true;
		}else{
			return false;
		}
	}


	// 判断是否为正确IP地址
	public static function Ip($str){
		if (strlen($str) == 0){
			return false;
		}
		if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/i",$str)){
			return true;
		}else{
			return false;
		}
	}

	// 判断是否为邮箱格式
	public static function Mail($str){
		//	return filter_var($str, FILTER_VALIDATE_EMAIL);
		//	if (strlen($str) > 6 && preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $str)){
		if (preg_match("/^([\.a-zA-Z0-9_\-]){2,30}@([a-zA-Z0-9_\-]){2,30}(\.([a-zA-Z0-9]){2,}){1,15}$/i",$str)){
			return true;
		}else{
			return false;
		}
	}

	// 判断是否为手机号格式
	public static function Phone($str){
		//	^1[34578]\d{9}$
		if (preg_match("/^1\d{10}$/i",$str)){
			return true;
		}else{
			return false;
		}
	}

	// 检测网址URL的正确性
	public static function Url($strUrl){
		// if (preg_match('#(https|http)://([\w\-]+\.)+[\w\-]+(/[\w\-\./\?%&=]*)?#i',$strUrl)){
		if (preg_match('/(http|https):\/\/([\w\d\-_]+[\.\w\d\-_]+)[:\d+]?([\/]?[\w\/\.\?=&;%@#\+,]+)/i',$strUrl)){
			return true;
		}else{
			return false;
		}
	}

	// 检测地址是否为http/https协议开头
	public static function HttpUrl($strUrl){
		if (strtolower(substr($strUrl,0,7)) == 'http://' || strtolower(substr($strUrl,0,8)) == 'https://'){
			return true;
		}else{
			return false;
		}
	}

	// 检测地址是否为绝对路径
	public static function AbsUrl($strUrl){
		if (strtolower(substr($strUrl,0,7)) == 'http://' || strtolower(substr($strUrl,0,8)) == 'https://' || substr($strUrl,0,1) == '/' || substr($strUrl,0,8) == 'magnet:?' || substr($strUrl,0,10) == 'thunder://'){
			return true;
		}else{
			return false;
		}
	}

	// 判断是否为注册域名格式
	public static function RegDomain($domain){  
		return !empty($domain) && strpos($domain, '--') === false && 
		preg_match('/^[a-zA-Z0-9\-]+$/i', $domain) ? true : false;
	}

	// 判断是否为域名格式
	public static function Domain($domain){  
		return !empty($domain) && strpos($domain, '--') === false && 
		preg_match('/^[a-zA-Z0-9\-\.\:]+$/i', $domain) ? true : false;
		// /(?=.{2,46}$)www\.([a-zA-Z0-9]\w*?[a-zA-Z0-9]\.(com\.cn|com|net|org|info|mobi))|([a-zA-Z0-9]((?!CHINA|CHINESE)\w)+[a-zA-Z0-9]\.cn)/i
		// /^([a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?\.)?[a-z0-9]+([a-z0-9-]*(?:[a-z0-9]+))?(\.us|\.tv|\.org\.cn|\.org|\.net\.cn|\.net|\.mobi|\.me|\.la|\.info|\.hk|\.gov\.cn|\.edu|\.com\.cn|\.com|\.co\.jp|\.co|\.cn|\.cc|\.biz)$/i
	}

	// 判断是否是微信浏览
	public static function Weixin(){
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}else{
			return false;
		}
	}

	// 判断是否是QQ浏览
	public static function QQ(){
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') !== false ) {
			return true;
		}else{
			return false;
		}
	}

	// 判断字符串是否全是中文
	public static function StrChinese($str){
		if(preg_match('/^[\x7f-\xff]+$/', $str)){
			return true;//全是中文
		}else{
			return false;//不全是中文
		}
	}

	// 使用淘宝接口 判断IP是国内true还是国外false
	public static function ChinaIp($ip=''){
		if (strlen($ip) == 0){ $ip = OT::GetServIp(); }
		$url = 'http://ip.taobao.com/service/getIpInfo.php?ip='. $ip;
		$res = file_get_contents($url);
		if (! empty($res)){
			$ipData = json_decode($res,true);
			if (isset($ipData['code']) && $ipData['code']==0 && in_array($ipData['data']['country_id'],array('CN','HK','TW'))) {
				return true;
			}
		}
		return false;
	}

	// 判断是否为图片木马
	public static function ImgMuma($imgPath){
		if (! file_exists($imgPath)){
			die('该文件('. $imgPath .')不存在，无法查是否有木马。');
			//return false;
		}
		$resource = fopen($imgPath, 'rb');
		$fileSize = filesize($imgPath);
		fseek($resource, 0);
		if ($fileSize > 512) {	// 取头和尾
			$hexCode = bin2hex(fread($resource, 512));
			fseek($resource, $fileSize - 512);
			$hexCode .= bin2hex(fread($resource, 512));
		} else {	// 取全部
			$hexCode = bin2hex(fread($resource, $fileSize));
		}
		fclose($resource);
		/* 匹配16进制中的 <% ( ) %> */ 
		/* 匹配16进制中的 <? ( ) ? > */ 
		/* 匹配16进制中的 <script | /script> 大小写亦可*/ 
		if (preg_match("/(3c25.*?28.*?29.*?253e)|(3c3f.*?28.*?29.*?3f3e)|(3C534352495054)|(2F5343524950543E)|(3C736372697074)|(2F7363726970743E)/is", $hexCode)){
			return true;
		}else{ 
			return false;
		}

		/*
		$imgStr = File::Read($imgPath);
		// if (strpos($imgStr,'eval')!==false && ( strpos($imgStr,'<?')!==false || strpos($imgStr,'<%')!==false )){
		if (( strpos($imgStr,'<?')!==false && strpos($imgStr,'?>')!==false ) || ( strpos($imgStr,'<%')!==false && strpos($imgStr,'%>')!==false )){
			return true;
		}else{
			return false;
		}
		*/
	}


	// 正则判断true/flase
	public static function RegExp($str, $Fnum){
		switch ($Fnum){
			case 'markIdStr':
				$pattern = "/[\d\,]/i";
				break;

			default:
				die('RegExpJud: no para');
				break;
		}
		if (preg_match($pattern,$str)){
			return true;
		}else{
			return false;
		}
	}

	//是否为外部提交(true是，false否)
	public static function OutSubmit(){
		if(empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) {
			return true;
		}else{
			return false;
		}
	}

	//是否为外部POSY提交(true是，false否)
	public static function OutPostSubmit(){
		if($_SERVER['REQUEST_METHOD'] == 'POST' && (empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST']))) {
			return true;
		}else{
			return false;
		}
	}

}

?>