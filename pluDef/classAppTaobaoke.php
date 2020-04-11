<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppTaobaoke{

	public static function Jud(){
		return false;
	}

	public static function InfoEncUrl($IF_isEncUrl){

	}

	public static function InfoTypeEncUrl($IT_isEncUrl){

	}

	public static function InfoWebEncUrl($IW_isEncUrl){

	}

	public static function InfoTypeOptionBox1($IT_mode){

	}

	public static function InfoTypeTrBox1($IT_webID){

	}

	public static function TopShop($judGet=true){

	}

	public static function GoodsBox($webTypeName,$infoTypeID,$judGet=true){

	}

	public static function RankBox($webTypeName,$infoTypeID,$judGet=true){

	}

	public static function RecomWordBox($judGet=true){

	}

	public static function FrameBox($judGet=true){

	}

	public static function SubRightGoodsWord($str,$judGet=true){

	}

	public static function SImgItem($typeStr,$typeLevel,$num,$judDate,$judGet=true){

	}

	public static function NewsGoodsBox($mode, $infoTypeID, $taoSysArr, $webPathPart, $jsPathPart, $judGet=true){

	}

	public static function TplGoodsList(){

	}

	public static function TplGoodsDet(){

	}

	public static function GoodsList($paraStr,$judGet=true){

	}

	public static function TypeCN($type1,$type2=''){
		$retStr = '';
		if ($type1 == 'site'){
			$retStr = '网站';
		}elseif ($type1 == 'dataoke'){
			$retStr = '大淘客';
			if ($type2 == 'web'){
				$retStr .= '-选品';
			}elseif ($type2 == 'quan'){
				$retStr .= '-领券';
			}elseif ($type2 == 'top100'){
				$retStr .= '-人气';
			}elseif ($type2 == 'paoliang'){
				$retStr .= '-跑量';
			}elseif ($type2 == 'rank1'){
				$retStr .= '-实时榜';
			}elseif ($type2 == 'rank2'){
				$retStr .= '-全天榜';
			}elseif ($type2 == 'rank3'){
				$retStr .= '-热推榜';
			}elseif ($type2 == 'rank4'){
				$retStr .= '-复购榜';
			}elseif ($type2 == 'rank5'){
				$retStr .= '-热词飙升';
			}elseif ($type2 == 'rank6'){
				$retStr .= '-热词排行';
			}elseif ($type2 == 'rank7'){
				$retStr .= '-热搜榜';
			}elseif ($type2 == 'less10'){
				$retStr .= '-9.9包邮';
			}elseif ($type2 == 'add'){
				$retStr .= '-新增';
			}elseif ($type2 == 'update'){
				$retStr .= '-更新';
			}
		}
		return $retStr;
	}

	public static function ComTypeCN($str){
		switch ($str){
			case 0:		return '通用';
			case 1:		return '定向';
			case 2:		return '高佣';
			case 3:		return '营销';
			default :	return $str;
		}
	}

	public static function AutoRunSysItem($ARS_timeRunItem=''){

	}

	public static function AutoRunDeal(){
		return array('ret'=>true,'note'=>'淘宝客-大淘客数据导入 无');
	}
	
	public static function GoodsDataItem($mode, $webTypeName, $infoTypeID, $taoSysArr, $webPathPart, $judGet=true){

	}
}

?>