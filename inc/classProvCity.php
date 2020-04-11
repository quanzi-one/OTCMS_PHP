<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class ProvCity{

	public static $dataArr = array(
		'安徽'		=> array('安庆','蚌埠','巢湖','池州','滁州','阜阳','合肥','淮北','淮南','黄山','六安','马鞍山','宿州','铜陵','芜湖','宣城','亳州'),
		'北京'		=> array('北京'),
		'福建'		=> array('福州','龙岩','南平','宁德','莆田','泉州','三明','厦门','漳州'),
		'甘肃'		=> array('白银','定西','甘南藏族自治州','嘉峪关','金昌','酒泉','兰州','临夏回族自治州','陇南','平凉','庆阳','天水','武威','张掖'),
		'广东'		=> array('潮州','东莞','佛山','广州','河源','惠州','江门','揭阳','茂名','梅州','清远','汕头','汕尾','韶关','深圳','阳江','云浮','湛江','肇庆','中山','珠海'),
		'广西'		=> array('百色','北海','崇左','防城港','桂林','贵港','河池','贺州','来宾','柳州','南宁','钦州','梧州','玉林'),
		'贵州'		=> array('安顺','毕节','贵阳','六盘水','黔东南苗族侗族自治州','黔南布依族苗族自治州','黔西南布依族苗族自治州','铜仁','遵义'),
		'海南'		=> array('白沙黎族自治县','保亭黎族苗族自治县','昌江黎族自治县','澄迈县','定安县','东方','海口','乐东黎族自治县','临高县','陵水黎族自治县','琼海','琼中黎族苗族自治县','三亚','屯昌县','万宁','文昌','五指山','儋州'),
		'河北'		=> array('保定','沧州','承德','邯郸','衡水','廊坊','秦皇岛','石家庄','唐山','邢台','张家口'),
		'河南'		=> array('安阳','鹤壁','济源','焦作','开封','洛阳','南阳','平顶山','三门峡','商丘','新乡','信阳','许昌','郑州','周口','驻马店','漯河','濮阳'),
		'黑龙江'	=> array('大庆','大兴安岭','哈尔滨','鹤岗','黑河','鸡西','佳木斯','牡丹江','七台河','齐齐哈尔','双鸭山','绥化','伊春'),
		'湖北'		=> array('鄂州','恩施土家族苗族自治州','黄冈','黄石','荆门','荆州','潜江','神农架林区','十堰','随州','天门','武汉','仙桃','咸宁','襄樊','孝感','宜昌'),
		'湖南'		=> array('常德','长沙','郴州','衡阳','怀化','娄底','邵阳','湘潭','湘西土家族苗族自治州','益阳','永州','岳阳','张家界','株洲'),
		'吉林'		=> array('白城','白山','长春','吉林','辽源','四平','松原','通化','延边朝鲜族自治州'),
		'江西'		=> array('抚州','赣州','吉安','景德镇','九江','南昌','萍乡','上饶','新余','宜春','鹰潭'),
		'辽宁'		=> array('鞍山','本溪','朝阳','大连','丹东','抚顺','阜新','葫芦岛','锦州','辽阳','盘锦','沈阳','铁岭','营口'),
		'内蒙古'	=> array('阿拉善盟','巴彦淖尔盟','包头','赤峰','鄂尔多斯','呼和浩特','呼伦贝尔','通辽','乌海','乌兰察布盟','锡林郭勒盟','兴安盟'),
		'西藏'		=> array('拉萨','昌都','山南','日喀则地','那曲','阿里','林芝'),
		'四川'		=> array('成都','自贡','攀枝花','泸州','德阳','绵阳','广元','遂宁','内江','乐山','南充','眉山','宜宾','广安','达州','雅安','巴中','资阳','阿坝藏族羌族自治州','甘孜藏族自治州','凉山彝族自治州'),
		'宁夏'		=> array('固原','石嘴山','吴忠','银川','中卫'),
		'青海'		=> array('果洛藏族自治州','海北藏族自治州','海东','海南藏族自治州','海西蒙古族藏族自治州','黄南藏族自治州','西宁','玉树藏族自治州'),
		'山东'		=> array('滨州','德州','东营','菏泽','济南','济宁','莱芜','聊城','临沂','青岛','日照','泰安','威海','潍坊','烟台','枣庄','淄博'),
		'山西'		=> array('长治','大同','晋城','晋中','临汾','吕梁','朔州','太原','忻州','阳泉','运城'),
		'陕西'		=> array('安康','宝鸡','汉中','商洛','铜川','渭南','西安','咸阳','延安','榆林'),
		'上海'		=> array('上海'),
		'天津'		=> array('天津'),
		'新疆'		=> array('阿克苏','阿拉尔','巴音郭楞蒙古自治州','博尔塔拉蒙古自治州','昌吉回族自治州','哈密','和田','喀什','克拉玛依','克孜勒苏柯尔克孜自治州','石河子','图木舒克','吐鲁番','乌鲁木齐','五家渠','伊犁哈萨克自治州','塔城','阿勒泰'),
		'云南'		=> array('保山','楚雄彝族自治州','大理白族自治州','德宏傣族景颇族自治州','迪庆藏族自治州','红河哈尼族彝族自治州','昆明','丽江','临沧','怒江傈僳族自治州','曲靖','思茅','文山壮族苗族自治州','西双版纳傣族自治州','玉溪','昭通'),
		'江苏'		=> array('南京','无锡','徐州','常州','苏州','南通','连云港','淮安','盐城','扬州','镇江','泰州','宿迁'),
		'浙江'		=> array('杭州','湖州','嘉兴','金华','丽水','宁波','绍兴','台州','温州','舟山','衢州'),
		'重庆'		=> array('重庆'),
		'台湾'		=> array('台北','基隆','新竹','台中','嘉义','台南','宜兰','桃园','新竹苗栗','高雄','屏东','台东','花莲','澎湖'),
		'香港特别行政区'	=> array('香港'),
		'澳门特别行政区'	=> array('澳门')
		);

	public static function GetProvArr(){
		return array_keys(self::$dataArr);
	}

	public static function GetCityArr($prov){
		if (isset(self::$dataArr[$prov])){
			return self::$dataArr[$prov];
		}else{
			return array();
		}
	}

	public static function GetProvOptionList($sel,$defVal='no'){
		$provArr = self::GetProvArr();
		if ($defVal != 'no'){
			$retStr = '<option value="">'. $defVal .'</option>';
		}else{
			$retStr = '';
		}
		foreach ($provArr as $val){
			$retStr .= '<option value="'. $val .'" '. Is::Selected($val,$sel) .'>'. $val .'</option>';
		}
		return $retStr;
	}

	public static function GetCityOptionList($sel,$prov='',$defVal='no'){
		if (strlen($prov) > 0){
			$provArr = self::GetCityArr($prov);
		}else{
			$provArr = array();
			foreach (self::$dataArr as $val){
				if (in_array($sel,$val)){
					$provArr = $val;
					break;
				};
			}
		}
		$retStr = '';
		if ($defVal != 'no'){
			if (count($provArr) == 0 && strlen($defVal) == 0){
				$retStr = '<option value="">请先选择省份</option>';
			}else{
				$retStr = '<option value="">'. $defVal .'</option>';
			}
		}
		foreach ($provArr as $val){
			$retStr .= '<option value="'. $val .'" '. Is::Selected($val,$sel) .'>'. $val .'</option>';
		}
		return $retStr;
	}
	 
	
	

	public static function GetCityOptionJs($idName,$prov,$defVal='no'){
		$provArr = self::GetCityArr($prov);

		$retStr = 'document.getElementById("'. $idName .'").options.length=0;';
		if ($defVal != 'no'){
			if (count($provArr) == 0 && strlen($defVal) == 0){
				$retStr .= 'document.getElementById("'. $idName .'").options.add(new Option("请先选择省份",""));';
			}else{
				$retStr .= 'document.getElementById("'. $idName .'").options.add(new Option("'. $defVal .'",""));';
			}
		}
		foreach ($provArr as $val){
			$retStr .= 'document.getElementById("'. $idName .'").options.add(new Option("'. $val .'","'. $val .'"));';
		}
		return $retStr;
	}
}

?>