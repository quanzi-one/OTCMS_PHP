<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class Ad{

	// 广告缓存
	public static function MakeJs(){
		global $DB;

		$newStr='
		var showHiddenAd=false;
		if (typeof(webTypeName) == "undefined"){ webTypeName = ""; }
		function OTca(str){
			switch (str){
			';

		$adStyleStr = '';
		$adexe=$DB->query('select * from '. OT_dbPref .'ad');
		while ($row = $adexe->fetch()){
			$AD_code = $row['AD_code'];

			$newStr .= '
			case "ot'. Str::FixLen($row['AD_num'],3) .'":
			';
			if ($row['AD_state'] == 1){
				$adStyleStr .= '.ad'. $row['AD_num'] .'Style,.ca'. $row['AD_num'] .'Style {'. $row['AD_divStyle'] .'}';

				if (strlen($row['AD_areaStr']) > 1){
					$newStr .= '
					if (("'. $row['AD_areaStr'] .'").indexOf("["+ webTypeName +"]")!=-1){
						'. OT::HtmlToJs($AD_code) .'
					}
					';
				}else{
					$newStr .= '
						'. OT::HtmlToJs($AD_code) .'
						';
				}
				$newStr .= '
				break;
				';
			}else{
				$newStr .= '
				if (showHiddenAd==true){
					'. OT::HtmlToJs($AD_code) .'
				}
				break;
				';
			}
		
		}
		unset($adexe);

		$newStr .= '
				}
			}
			';

		$retStr = str_replace('[siteTitle]', '"+ encodeURIComponent(document.title) +"', $newStr);
		$retStr = str_replace(str_replace("/","\/",InfoImgAdminDir), "\"+ webPathPart +\"". str_replace("/","\/",InfoImgDir), $retStr);
		return File::Write( OT_ROOT .'cache/js/OTca.js' , $retStr . OT::HtmlToJs('<style type="text/css">'. $adStyleStr .'</style>') .'/* '. TimeDate::Get() .' */');
	}


	// 广告缓存提示化
	public static function MakeTishiJs(){
		global $DB;

		$newStr='
		var showHiddenAd=false;
		function OTca(str){
			switch (str){
			';

		$adStyleStr = '';
		$adexe=$DB->query('select AD_num,AD_theme,AD_divStyle from '. OT_dbPref .'ad');
		while ($row = $adexe->fetch()){
			$newStr .= '
			case "ot'. Str::FixLen($row['AD_num'],3) .'":
			';
				$adStyleStr .= '.ad'. $row['AD_num'] .'Style,.ca'. $row['AD_num'] .'Style {'. $row['AD_divStyle'] .'}';

				$newStr .= '
				'. OT::HtmlToJs('<table style="width:100%;border:2px blue dashed;background:#cdcdfe;"><tr><td align="center" style="padding:15px 0 15px 0;font-size:14px;color:blue;">编号：'. $row['AD_num'] .'，'. $row['AD_theme'] .'</td></tr></table>') .'
				break;
				';
		}
		unset($adexe);

		$newStr .= '
				}
			}
			';

		return File::Write( OT_ROOT .'cache/js/OTca.js' , $newStr . OT::HtmlToJs('<style type="text/css">'. $adStyleStr .'</style>') );
	}

}
?>