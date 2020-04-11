<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}

class JS{
	public static function Diy($str){
		echo('<script language="javascript" type="text/javascript">'. $str .'</script>');
	}

	public static function DiyEnd($str){
		die('<script language="javascript" type="text/javascript">'. $str .'</script>');
	}


	public static function Href($str){
		echo('
		<script language="javascript" type="text/javascript">
		document.location.href="'. $str .'";
		</script>
		');
	}

	public static function HrefEnd($str){
		die('
		<script language="javascript" type="text/javascript">
		document.location.href="'. $str .'";
		</script>
		');
	}

	public static function HrefTimeout($str,$sec){
		echo('
		<script language="javascript" type="text/javascript">
		setTimeout("document.location.href=\''. $str .'\';",'. ($sec*1000) .');
		</script>
		');
	}

	public static function HrefTimeoutEnd($str,$sec){
		die('
		<script language="javascript" type="text/javascript">
		setTimeout("document.location.href=\''. $str .'\';",'. ($sec*1000) .');
		</script>
		');
	}


	public static function Write($str){
		echo('
		<script language="javascript" type="text/javascript">
		document.write("'. $str .'");
		</script>
		');
	}

	public static function WriteEnd($str){
		die('
		<script language="javascript" type="text/javascript">
		document.write("'. $str .'");
		</script>
		');
	}


	public static function Alert($str){
		echo('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");
		</script>
		');
	}

	public static function AlertEnd($str){
		die('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");
		</script>
		');
	}

	public static function AlertClose($str){
		echo('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");window.close();
		</script>
		');
	}

	public static function AlertCloseEnd($str){
		die('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");window.close();
		</script>
		');
	}


	public static function AlertBack($str){
		echo('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");history.back();
		</script>
		');
	}

	public static function AlertBackEnd($str){
		die('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");history.back();
		</script>
		');
	}


	public static function AlertHref($str,$url){
		echo('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");document.location.href="'. $url .'";
		</script>
		');
	}

	public static function AlertHrefEnd($str,$url){
		die('
		<script language="javascript" type="text/javascript">
		alert("'. $str .'");document.location.href="'. $url .'";
		</script>
		');
	}


	public static function ModeDeal($mode,$jud,$value='',$href=''){
		if ($mode=='jud'){
			return $jud;

		}elseif ($mode=='show'){
			die($value);

		}elseif ($mode=='get'){
			return $value;

		}elseif ($mode=='alert'){
			self::AlertEnd($value);

		}elseif ($mode=='alertBack'){
			self::AlertBackEnd($value);

		}elseif ($mode=='alertClose'){
			self::AlertCloseEnd($value);

		}elseif ($mode=='alertHref'){
			self::AlertHrefEnd($value,$href);

		}elseif ($mode=='alertStr'){
			die('alert("'. $value .'");');

		}elseif ($mode=='alertBackStr'){
			die('alert("'. $value .'");history.back();');

		}elseif ($mode=='alertHrefStr'){
			die('alert("'. $value .'");document.location.href="'. $href .'";');

		}
	}
}

?>