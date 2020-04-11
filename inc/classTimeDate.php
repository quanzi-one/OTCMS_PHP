<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class TimeDate{
	//获取时间
	/*
	第一个参数的格式分别表示:
	a - "am" 或是 "pm"
	A - "AM" 或是 "PM"
	d - 几日，二位数字，若不足二位则前面补零; 如: "01" 至 "31"
	D - 星期几，三个英文字母; 如: "Fri"
	F - 月份，英文全名; 如: "January"
	h - 12 小时制的小时; 如: "01" 至 "12"
	H - 24 小时制的小时; 如: "00" 至 "23"
	g - 12 小时制的小时，不足二位不补零; 如: "1" 至 12"
	G - 24 小时制的小时，不足二位不补零; 如: "0" 至 "23"
	i - 分钟; 如: "00" 至 "59"
	j - 几日，二位数字，若不足二位不补零; 如: "1" 至 "31"
	l - 星期几，英文全名; 如: "Friday"
	m - 月份，二位数字，若不足二位则在前面补零; 如: "01" 至 "12"
	n - 月份，二位数字，若不足二位则不补零; 如: "1" 至 "12"
	M - 月份，三个英文字母; 如: "Jan"
	s - 秒; 如: "00" 至 "59"
	S - 字尾加英文序数，二个英文字母; 如: "th"，"nd"
	t - 指定月份的天数; 如: "28" 至 "31"
	U - 总秒数
	w - 数字型的星期几，如: "0" (星期日) 至 "6" (星期六)
	Y - 年，四位数字; 如: "1999"
	y - 年，二位数字; 如: "99"
	z - 一年中的第几天; 如: "0" 至 "365"	
	*/
	public static function Get($format='datetime',$formatTime='none'){
		if ($format=='date'){
			$format='Y-m-d';
		}elseif ($format=='datetime'){
			$format='Y-m-d H:i:s';
		}elseif ($format=='datetime2'){
			$format='y-m-d H:i:s';
		}elseif ($format=='datetime3'){
			$format='y-m-d H:i';
		}elseif ($format=='date2'){
			$format='y-m-d';
		}elseif ($format=='time'){
			$format='H:i:s';
		}elseif ($format=='dateStr'){
			$format='Ymd';
		}elseif ($format=='datetimeStr'){
			$format='YmdHis';
		}elseif ($format=='cn'){
			$format='Y年m月d日 H:i';
		}elseif ($format=='timeStr'){
			$format='His';
		}

		if ($formatTime=='none'){
			return date($format);
		}else{
			if (is_numeric($formatTime)==true){
				return date($format,$formatTime);
			}elseif (strtotime($formatTime)){
				return date($format,strtotime($formatTime));
			}else{
				return '';
			}
		}
	//	return gmdate($format, time() + 3600 * 8);
	}


	//增加/减少日期的某部分值
	//$part:单位,$n:数目,$datetime:日期时间
	public static function Add($part,$n,$datetime='now'){
		if ($datetime == 'now'){ $datetime = self::Get(); }
		$datetime=strtotime($datetime);
		$datetime_array = getdate($datetime);
		$year	= $datetime_array['year'];
		$month	= $datetime_array['mon'];
		$day	= $datetime_array['mday'];
		$hours	= $datetime_array['hours'];
		$minutes= $datetime_array['minutes'];
		$seconds= $datetime_array['seconds'];

		$result=0;
		switch (strtolower($part)){
			case 'year': case 'y':
				$year	+= $n;	break;
			case 'month': case 'm':
				$month	+= $n;	break;
			case 'day': case 'd':
				$day	+= $n;	break;
			case 'hour': case 'h':
				$hours	+= $n;	break;
			case 'min': case 'i': case 'n':
				$minutes+= $n;	break;
			case 'sec': case 's':
				$seconds+= $n;	break;
			default:
				return $result;
			break;
		}
		$result=mktime($hours,$minutes,$seconds,$month,$day,$year);

		if (date('H:i:s',$result)=='00:00:00'){
			return date('Y-m-d',$result);
		} else {
			return date('Y-m-d H:i:s',$result);
		}
	}



	//计算两个日期间的间隔（年、月、天、时、分、秒）
	//$part:单位,$date1:日期时间1,$date2:日期时间2；$date2-$date1
	public static function Diff($part,$date1,$date2='',$mode=''){
		$part = strtolower($part);
		//$diff=$date2-$date1;
		$date1=strtotime($date1);
		$date1_array = getdate($date1);
		$year1	= $date1_array['year'];
		$month1	= $date1_array['mon'];
		$day1	= $date1_array['mday'];
		$hours1	= $date1_array['hours'];
		$minutes1= $date1_array['minutes'];
		$seconds1= $date1_array['seconds'];

		if ($date2 == ''){
			$date2 = time();
		}else{
			$date2 = strtotime($date2);
		}
		$date2_array = getdate($date2);
		$year2	= $date2_array['year'];
		$month2	= $date2_array['mon'];
		$day2	= $date2_array['mday'];
		$hours2	= $date2_array['hours'];
		$minutes2= $date2_array['minutes'];
		$seconds2= $date2_array['seconds'];

		$result=0;
		switch ($part){
			case 'year': case 'y':
				$result=$year2-$year1;
				break;
			case 'month': case 'm':
				$result=($year2-$year1)*12+$month2-$month1;
				break;
			case 'day': case 'd':
				$result=(mktime(0,0,0,$month2,$day2,$year2)-mktime(0,0,0,$month1,$day1,$year1))/(3600*24);
				break;
			case 'hour': case 'h':
				$result=(mktime($hours2,0,0,$month2,$day2,$year2)-mktime($hours1,0,0,$month1,$day1,$year1))/3600;
				break;
			case 'min': case 'n':
				$result=(mktime($hours2,$minutes2,0,$month2,$day2,$year2)-mktime($hours1,$minutes1,0,$month1,$day1,$year1))/60;
				break;
			case 'sec': case 's':
				$result=$date2-$date1;
				break;
			default:
				return $result;
				break;
		}
		if ($mode == 'ceil'){
			// 进一法
			switch ($part){
				case 'year': case 'y':
					if (mktime($hours2,$minutes2,$seconds2,$month2,$day2,0) > mktime($hours1,$minutes1,$seconds1,$month1,$day1,0)){ $result ++; }
					break;
				case 'month': case 'm':
					if (mktime($hours2,$minutes2,$seconds2,0,$day2,0) > mktime($hours1,$minutes1,$seconds1,0,$day1,0)){ $result ++; }
					break;
				case 'day': case 'd':
					if (mktime($hours2,$minutes2,$seconds2,0,0,0) > mktime($hours1,$minutes1,$seconds1,0,0,0)){ $result ++; }
					break;
				case 'hour': case 'h':
					if (mktime(0,$minutes2,$seconds2,0,0,0) > mktime(0,$minutes1,$seconds1,0,0,0)){ $result ++; }
					break;
				case 'min': case 'n':
					if ($seconds2 > $seconds1){ $result ++; }
					break;
			}
		}elseif ($mode == 'floor'){
			// 向下取整
			switch ($part){
				case 'year': case 'y':
					if (mktime($hours2,$minutes2,$seconds2,$month2,$day2,0) < mktime($hours1,$minutes1,$seconds1,$month1,$day1,0)){ $result --; }
					break;
				case 'month': case 'm':
					if (mktime($hours2,$minutes2,$seconds2,0,$day2,0) < mktime($hours1,$minutes1,$seconds1,0,$day1,0)){ $result --; }
					break;
				case 'day': case 'd':
					if (mktime($hours2,$minutes2,$seconds2,0,0,0) < mktime($hours1,$minutes1,$seconds1,0,0,0)){ $result --; }
					break;
				case 'hour': case 'h':
					if (mktime(0,$minutes2,$seconds2,0,0,0) < mktime(0,$minutes1,$seconds1,0,0,0)){ $result --; }
					break;
				case 'min': case 'n':
					if ($seconds2 < $seconds1){ $result --; }
					break;
			}
		}
		return $result;
	}

	public static function DiffDayCN($dateTime,$str1='',$str2='过期'){
		$currTime = self::Get();
		$diffNum = self::Diff('d',$currTime,$dateTime);
		if ($diffNum >= 1){
			return $str1 . $diffNum .'天';
		}elseif ($diffNum == 0){
			$diffNum = self::Diff('h',$currTime,$dateTime);
			if ($diffNum >= 0){
				return $str1 . $diffNum .'时';
			}else{
				return $str2 . abs($diffNum) .'时';
			}
		}elseif ($diffNum > -7){	// 过期7天
			return $str2 . abs($diffNum) .'天';
		}else{
			return $str2;
		}
	}

	public static function GetMicrotime() { 
		list($s1, $s2) = explode(' ', microtime()); 
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000); 
	}
}

?>