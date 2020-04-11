<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class TplArea{

	// 顶部通用区域1
	public static function AreaTop1($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
			$retStr .= AppTaobaoke::TopShop();
		}

		$retStr .= AppToTop::TplBottom();

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 顶部通用区域2
	public static function AreaTop2($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
			$retStr .= AppTaobaoke::RecomWordBox();
			$retStr .= AppTaobaoke::RankBox($tpl->webTypeName, $tpl->typeID);
			$retStr .= AppTaobaoke::GoodsBox($tpl->webTypeName, $tpl->typeID);
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 顶部通用区域3
	public static function AreaTop3($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 底部通用区域1
	public static function AreaBottom1($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
			$retStr .= '<script language="javascript" type="text/javascript" src="'. $tpl->webPathPart .'js/inc/jquery_lazyLoad.js?v='. OT_VERSION .'"></script>';
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 内容页头部通用区域1
	public static function AreaNewsTop1($IF_ID, $IF_topAddiID, $judGet=true){
		global $DB,$tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
			// $retStr .= AppTaobaoke::NewsGoodsBox();
			$retStr .= '<div id="goodsJsNews">';
			$retStr .= '<script language="javascript" type="text/javascript">$(function (){ try{ LoadGoodsJsNews('. $tpl->typeID .'); }catch(e){} });</script>';
			// $retStr .= AppTaobaoke::NewsGoodsBox('stage', $tpl->typeID, $tpl->taokeSysArr, $tpl->webPathPart, $tpl->jsPathPart);
			$retStr .= '</div>';
		}

		if ($IF_topAddiID > 0){
			$addiContent = '';
			$addiexe=$DB->query('select IW_content from '. OT_dbPref .'infoWeb where IW_ID='. $IF_topAddiID ." and IW_state=1 and IW_type='news'");
				if ($row = $addiexe->fetch()){
					$addiContent = '<div>'. $row['IW_content'] .'</div>';
					if ($tpl->webPathPart != '../'){ $addiContent = str_replace(InfoImgAdminDir,$tpl->webPathPart . InfoImgDir,$addiContent); }
				}
			unset($addiexe);		
			$retStr .= $addiContent;
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// 内容页头部通用区域1
	public static function AreaNewsBottom1($IF_ID, $IF_addiID, $IF_userID=0, $judGet=true){
		global $DB,$tpl,$infoSysArr;

		$retStr = '';

		if ($IF_addiID > 0){
			$addiContent = '';
			$addiexe=$DB->query('select IW_content from '. OT_dbPref .'infoWeb where IW_ID='. $IF_addiID ." and IW_state=1 and IW_type='news'");
				if ($row = $addiexe->fetch()){
					$addiContent = '<div>'. $row['IW_content'] .'</div>';
					if ($tpl->webPathPart != '../'){ $addiContent = str_replace(InfoImgAdminDir,$tpl->webPathPart . InfoImgDir,$addiContent); }
				}
			unset($addiexe);		
			$retStr .= $addiContent;
		}

		if ($tpl->isAppDashang){
			$retStr .= AppDashang::NewsBox($IF_userID);
		}

		if ($infoSysArr['IS_isWapQrcode']==1){
			$retStr .= '<div id="contWapQrCode"></div>';
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// WAP顶部通用区域1
	public static function WapTop1($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}



	// WAP顶部通用区域2
	public static function WapTop2($judGet=true){
		global $tpl;

		$retStr = '';

		if ($tpl->isAppTaoke){
			global $taokeSysArr;
			if (empty($taokeSysArr)){ $taokeSysArr = Cache::PhpFile('taokeSys'); }
			if (strpos($taokeSysArr['TS_wapArea'],'|'. $tpl->webTypeName .'|') !== false){
				$retStr .= '
					<script src="'. $tpl->webPathPart .'js/app/taobaoke.js?v='. OT_VERSION .'" type="text/javascript"></script>
					<script src="'. $tpl->webPathPart .'js/productSlide.js?v='. OT_VERSION .'" type="text/javascript"></script>
					<div id="goodsJsNews">
						<script language="javascript" type="text/javascript">$(function (){ try{ LoadGoodsJsNews('. $tpl->typeID .'); }catch(e){} });</script>
					</div>
					';
				// $retStr .= AppTaobaokeWap::GoodsBox($tpl->webTypeName, $tpl->typeID);
				// $retStr .= AppTaobaokeWap::GoodsBox('stage', $tpl->typeID, $tpl->taokeSysArr, $tpl->webPathPart, $tpl->jsPathPart);
			}
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}
}

?>