<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppTaobaokeDeal{

	public static function WordContent($strTemp){

	}

	public static function InfoImgLoad($imgValue,$imgBase64Value,$imgPartUrl){

	}

	public static function DataokeApi($mode, $key, $page=1, $isRetArr=true){

	}

	public static function GetKouling($goodsUrl,$goodsImg='',$goodsName=''){

	}

	public static function GetContentImg($goodsUrl){

	}

	public static function GetDataokeDetUrl($url,$id){
		return $url .'index.php?r=l/d&id='. $id;
	}

	public static function OutDataDaoru($mode,$type,$type2,$succNum=0,$maxPage=0){

	}

	public static function DataokeGoods($mode,$type,$dataArr,$numArr=array()){

	}

}

?>