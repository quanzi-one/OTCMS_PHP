<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class Encrypt{

	// 可逆转_加密
	public static function PwdEncode($txt, $key) {
		srand((double)microtime() * 1000000);
		$encrypt_key = md5(rand(0, 32000)); 
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $encrypt_key[$ctr].($txt[$i] ^ $encrypt_key[$ctr++]);
		}
		return base64_encode(self::PwdKey($tmp, $key));
	}

	// 可逆转_解密
	public static function PwdDecode($txt, $key) {
		$txt = self::PwdKey(base64_decode($txt), $key);
		$tmp = '';
		for ($i = 0; $i < strlen($txt); $i++) {
			$tmp .= $txt[$i] ^ $txt[++$i];
		}
		return $tmp;
	}

	// 可逆转_加解密钥匙
	public static function PwdKey($txt, $encrypt_key) {
		$encrypt_key = md5($encrypt_key);
		$ctr = 0;
		$tmp = '';
		for($i = 0; $i < strlen($txt); $i++) {
			$ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
			$tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
		}
		return $tmp;
	}



	/*
		$str = 'abcdef'; 
		$key = 'otcms.com'; 
		echo AuthCode($str,'ENCODE',$key,0); //加密 
		$str = '56f4yER1DI2WTzWMqsfPpS9hwyoJnFP2MpC8SOhRrxO7BOk'; 
		echo AuthCode($str,'DECODE',$key,0); //解密	
	*/
	// 非常给力的AuthCode加密函数，Discuz!经典代码
	// $string：字符串，明文或密文；$operation：DECODE表示解密，其它表示加密；$key：密匙；$expiry：密文有效期。
	public static function AuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		$ckey_length = 4;
	
		// 密匙
		$key = md5($key ? $key : 'OTCMS_COM');
		
		// 密匙a会参与加解密
		$keya = md5(substr($key, 0, 16));
		// 密匙b会用来做数据完整性验证
		$keyb = md5(substr($key, 16, 16));
		// 密匙c用于变化生成的密文
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
		// 参与运算的密匙
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)， 
		// 解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		// 产生密匙簿
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
		// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
		// 核心加解密部分
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			// 从密匙簿得出密匙进行异或，再转成字符
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
		if($operation == 'DECODE') {
			// 验证数据有效性，请看未加密明文的格式
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}


	/*
		$str = 'abc'; 
		$key = 'otcms.com'; 
		$token = DataEncode($str, 'E', $key); 
		echo '加密:'.DataEncode($str, 'E', $key); 
		echo '解密：'.DataEncode($str, 'D', $key); 	
	*/
	// $string：需要加密解密的字符串；$operation：判断是加密还是解密，E表示加密，D表示解密；$key：密匙。
	public static function DataEncode($string,$operation,$key=''){
		$key=md5($key);
		$key_length=strlen($key);
		$string=$operation=='D'?base64_decode($string):substr(md5($string.$key),0,8).$string;
		$string_length=strlen($string);
		$rndkey=$box=array();
		$result='';
		for($i=0;$i<=255;$i++){
			$rndkey[$i]=ord($key[$i%$key_length]);
			$box[$i]=$i;
		}
		for($j=$i=0;$i<256;$i++){
			$j=($j+$box[$i]+$rndkey[$i])%256;
			$tmp=$box[$i];
			$box[$i]=$box[$j];
			$box[$j]=$tmp;
		}
		for($a=$j=$i=0;$i<$string_length;$i++){
			$a=($a+1)%256;
			$j=($j+$box[$a])%256;
			$tmp=$box[$a];
			$box[$a]=$box[$j];
			$box[$j]=$tmp;
			$result.=chr(ord($string[$i])^($box[($box[$a]+$box[$j])%256]));
		}
		if($operation=='D'){
			if(substr($result,0,8)==substr(md5(substr($result,8).$key),0,8)){
				return substr($result,8);
			}else{
				return '';
			}
		}else{
			return str_replace('=','',base64_encode($result));
		}
	}


	public static function SyEnDe($mode, $str){
		if ($mode == 'en'){
			$str = base64_encode($str);
			if (strlen($str) < 12) { $str .= '|O|T|C|M|S|.|C|O|M|'; }
		}else{
			if (substr($str,0,4) == '[SY]'){ $str = substr($str,4); }else{ return $str; }
		}

		$strArr = array();
		$strNum = strlen($str);
		$oneNum = intval($strNum / 6);

		$strArr[0] = strrev(substr($str, 0, $strNum - ($oneNum * 5)));
		$strArr[1] = strrev(substr($str, $strNum - ($oneNum * 5), $oneNum));
		$strArr[2] = strrev(substr($str, $strNum - ($oneNum * 4), $oneNum));
		$strArr[3] = strrev(substr($str, $strNum - ($oneNum * 3), $oneNum));
		$strArr[4] = strrev(substr($str, $strNum - ($oneNum * 2), $oneNum));
		$strArr[5] = strrev(substr($str, $strNum - ($oneNum * 1), $oneNum));

		if ($mode == 'en'){
			$retStr = '[SY]'. $strArr[0] . $strArr[2] . $strArr[4] . $strArr[5] . $strArr[3] . $strArr[1];
			return $retStr;
		}else{
			$retStr = $strArr[0] . $strArr[5] . $strArr[1] . $strArr[4] . $strArr[2] . $strArr[3];
			return base64_decode(str_replace('|O|T|C|M|S|.|C|O|M|', '', $retStr));
		}
	}
}
?>