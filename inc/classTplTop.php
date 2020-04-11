<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class TplTop{

	// 导航菜单
	public static function NavMenu($num,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_NavMenu_'. $num .'_'. strlen($tpl->webPathPart);

		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){
			$retStr = '';
			if ($tpl->webHost == ''){ $GB_NavHost=$tpl->webPathPart; }else{ $GB_NavHost=$tpl->webHost; }

			$retStr .= '<ul class="topnav">'. PHP_EOL;
			if ($num<=0){ $num=100; }
			$menuRow = $DB->GetLimit('select IT_ID,IT_theme,IT_themeStyle,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_level,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_isMenu=1 order by IT_rank ASC',$num);
			if ($menuRow){
				$rowCount = count($menuRow);
				for ($i=0; $i<$rowCount; $i++){
					$hrefStr = Area::InfoTypeUrl(array(
						'IT_mode'		=> $menuRow[$i]['IT_mode'],
						'IT_ID'			=> $menuRow[$i]['IT_ID'],
						'IT_webID'		=> $menuRow[$i]['IT_webID'],
						'IT_URL'		=> $menuRow[$i]['IT_URL'],
						'IT_isEncUrl'	=> $menuRow[$i]['IT_isEncUrl'],
						'IT_htmlName'	=> $menuRow[$i]['IT_htmlName'],
						'mainUrl'		=> '',
						'webPathPart'	=> $tpl->webPathPart,
						));
					if ($i > 0){
						$retStr .= '<li class="c">&ensp;</li>'. PHP_EOL;
					}
					$retStr .= '<li class="b" navNum="'. $menuRow[$i]['IT_ID'] .'">
						<div class="itemMenu"><a href="'. $hrefStr .'" target="'. $menuRow[$i]['IT_openMode'] .'" style="'. $menuRow[$i]['IT_themeStyle'] .'">'. $menuRow[$i]['IT_theme'] .'</a></div>';

						if ($menuRow[$i]['IT_level'] == 1){
							if (in_array($tpl->tplSysArr['TS_navMode'],array(21,26))){
								$menu2exe = $DB->query('select IT_ID,IT_theme,IT_themeStyle,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $menuRow[$i]['IT_ID'] .' and IT_isSubMenu=1 order by IT_rank ASC');
								if ($row2 = $menu2exe->fetch()){
									$retStr .= '<ul class="subnav">';
									do {
										$hrefStr = Area::InfoTypeUrl(array(
											'IT_mode'		=> $row2['IT_mode'],
											'IT_ID'			=> $row2['IT_ID'],
											'IT_webID'		=> $row2['IT_webID'],
											'IT_URL'		=> $row2['IT_URL'],
											'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
											'IT_htmlName'	=> $row2['IT_htmlName'],
											'mainUrl'		=> '',
											'webPathPart'	=> $tpl->webPathPart,
											));
										$retStr .= '
										<li><a href="'. $hrefStr .'" target="'. $row2['IT_openMode'] .'" style="'. $row2['IT_themeStyle'] .'">'. $row2['IT_theme'] .'</a></li>';
									}while ($row2 = $menu2exe->fetch());
									$retStr .= '</ul>';
								}
								$menu2exe = null;

							}
						}
						$retStr .= '</li>';
				}
			}
			unset($menuRow);

			$retStr .= '
			</ul>
			<div class="clr"></div>';

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}


	// 滚动信息
	public static function MarInfoBox($w,$showNum,$isMar=false,$judGet=true){
		global $DB,$tpl;

		$judCache = false;
		$cacheName='func_MarInfoBox_'. $w .'_'. $showNum .'_'. strlen($tpl->webPathPart);

		$retStr = '';
		if ( ($tpl->webTypeName != 'home' && $isMar == false) ){
			if ($tpl->webHost==''){ $webPointerUrl='./'; }else{ $webPointerUrl=$tpl->webHost; }
			$retStr = '<span class="font2_1 pointFontClass">当前位置：</span><a href="'. $webPointerUrl .'">首页</a>'. $tpl->pointStr;
		}else{
			if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
			if ($judCache == false){

				if ($showNum<=0){ $showNum=10; }
				$marRow = $DB->GetLimit('select IF_ID,IF_URL,IF_isEncUrl,IF_theme,IF_themeStyle,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1 and IF_isMarquee=1'. OT_TimeInfoWhereStr .' order by IF_time DESC',$showNum);
				if ($marRow){
					$retStr .= '
					<marquee id="marInfo" direction="left" width="'. $w .'" loop="-1" class="disClass" scrollamount="3" scrolldelay="30" onmouseover="this.stop()" onmouseout="this.start()">
					';
					$rowCount = count($marRow);
					for ($i=0; $i<$rowCount; $i++){
						if ($marRow[$i]['IF_URL'] != ''){
							$hrefStr = Url::NewsUrl($marRow[$i]['IF_URL'],$marRow[$i]['IF_isEncUrl'],$marRow[$i]['IF_ID'],$tpl->webPathPart);
						}else{
							$hrefStr = Url::NewsID($marRow[$i]['IF_infoTypeDir'],$marRow[$i]['IF_datetimeDir'],$marRow[$i]['IF_ID']);
						}
						$retStr .= '
						<a href="'. $hrefStr .'" target="_blank" class="font2_2" style="'. $marRow[$i]['IF_themeStyle'] .'">◆'. $marRow[$i]['IF_theme'] .'</a>&ensp;&ensp;&ensp;&ensp;';
					}
					$retStr .= '
					</marquee>
					';
				}
				unset($marexe);

				Cache::WriteWebCache($cacheName,$retStr);

			}
		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

	// logo图片
	public static function GetLogoImg($logo,$fullLogo){
		global $tpl;

		if ($tpl->tplSysArr['TS_topLogoMode'] == 1){
			$logoImgSrc = $fullLogo;
		}else{
			$logoImgSrc = $logo;
		}
		if ($tpl->tplSysArr['TS_logoExt'] == 1){
			return '
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="'. $tpl->tplSysArr['TS_logoW'] .'" height="'. $tpl->tplSysArr['TS_logoH'] .'">
				<param name="movie" value="'. $logoImgSrc .'" />
				<param name="quality" value="high" />
				<param name="wmode" value="transparent" />
				<embed src="'. $logoImgSrc .'" width="'. $tpl->tplSysArr['TS_logoW'] .'" height="'. $tpl->tplSysArr['TS_logoH'] .'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent"></embed>
			</object>
			';
		}else{
			$imgAlt = Str::MoreReplace($tpl->sysArr['SYS_title'],'input');
			if ($logoImgSrc == $logo){
				if ($tpl->webHost == ''){ $webLogoUrl='./'; }else{ $webLogoUrl=$tpl->webHost; }
				return '<a href="'. $webLogoUrl .'"><img src="'. $logoImgSrc .'" alt="'. $imgAlt .'" title="'. $imgAlt .'" class="logoImg" /></a>';
			}else{
				return '<img src="'. $logoImgSrc .'" alt="'. $imgAlt .'" title="'. $imgAlt .'" class="logoImg" />';
			}
		}
	}

	// banner广告
	public static function NavBanner($type='homeNav',$num=0,$judGet=true){
		if (! AppBase::Jud()){ return ''; }

		global $DB,$tpl;

		if ($tpl->tplSysArr['TS_isImgBanner'] == 0){ return ''; }

		$judCache = false;
		$cacheName='func_Banner_'. $type .'_'. $num .'_'. strlen($tpl->webPathPart);

		$retStr = '';
		if ($retStr = Cache::CheckWebCache($cacheName)){ $judCache = true; }
		if ($judCache == false){

			if ($num<=0){ $num=100; }
			$banexe = $DB->query('select * from '. OT_dbPref .'banner where BN_state=1 and BN_type='. $DB->ForStr($type) .' order by BN_rank DESC limit '. $num);
			if ($row = $banexe->fetch()){
				$retStr .= '
				<script type="text/javascript" src="'. $tpl->webPathPart .'tools/fullImgSlide/fullImgSlide.js"></script>
				<link rel="stylesheet" href="'. $tpl->webPathPart .'tools/fullImgSlide/style.css" type="text/css" media="all" id="webSkin" />
				<style>
				#fullImgBox{ height:'. $tpl->tplSysArr['TS_imgBannerH'] .'px; }
				.fullImgSlide { height:'. $tpl->tplSysArr['TS_imgBannerH'] .'px; }
				.fullImgSlide .bd li { height:'. $tpl->tplSysArr['TS_imgBannerH'] .'px; }
				.fullImgSlide .bd li a { height:'. $tpl->tplSysArr['TS_imgBannerH'] .'px; }
				</style>
				<div class="fullImgBox">
				<div class="fullImgSlide">
					<div class="bd">
						<ul>
						';

				do {
					$retStr .= '<li _src="url('. $tpl->webPathPart .ImagesFileDir . $row['BN_img'] .')" style="background:'. $row['BN_bgColor'] .' center 0 no-repeat;">'. (strlen($row['BN_url'])>7?'<a href="'. $row['BN_url'] .'" target="_blank"></a>':'') .'</li>';
				}while ($row = $banexe->fetch());

				$retStr .= '
						</ul>
					</div>
					<div class="hd">
						<ul>
						</ul>
					</div>
					<span class="prev"></span>
					<span class="next"></span>
				</div>
				</div>
				';
			}
			unset($marexe);

			Cache::WriteWebCache($cacheName,$retStr);

		}

		if ($judGet){
			return $retStr;
		}else{
			echo($retStr);
		}
	}

}

?>