<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Area{

	// 读取验证码
	public static function VerCode($svcForm,$svcId='',$svcGeetWidth=''){
		if (OT_OpenVerCode){
			global $webPathPart,$systemArr;

			if ($systemArr['SYS_verCodeMode'] == 20){
				return '<div id="geetestDiv"></div>
						<p id="geetestWait" style="color:blue;">正在加载验证码......</p>
						<p id="geetestNote" style="display:none;">请先完成验证</p>
						'. (strlen($svcGeetWidth)>0?'<script language="javascript" type="text/javascript">geetWidth = "'. $svcGeetWidth .'";</script>':'') .'
						<script language="javascript" type="text/javascript" src="'. $webPathPart .'tools/geetest/gt.js"></script>
						';
			}else{
				if (empty($svcId)){ $svcId='verCode'; }
				return '<input type="text" id="'. $svcId .'" name="verCode" maxlength="16" class="text" style="width:60px;" autocomplete="off" onfocus=\'GetVerCode("input")\' title="如看不清验证码，可以点击验证码进行更换" />&ensp;&ensp;<span id="showVerCode" class="font2_2" onclick=\'GetVerCode("font")\' style="cursor:pointer;">点击获取验证码</span>';
			}
		}else{
			return '<span class="font2_2">已关闭</span>';
		}
	}

	// 读取验证码2 pop
	public static function VerCodePop($svcForm,$svcId='',$svcGeetWidth=''){
		if (OT_OpenVerCode){
			global $webPathPart,$systemArr;

			if ($systemArr['SYS_verCodeMode'] == 20){
				return '<div id="geePopDiv"></div>
						<p id="geePopWait" style="color:blue;">正在加载验证码......</p>
						<p id="geePopNote" style="display:none;">请先完成验证</p>
						'. (strlen($svcGeetWidth)>0?'<script language="javascript" type="text/javascript">geetPopWidth = "'. $svcGeetWidth .'";</script>':'') .'
						<script language="javascript" type="text/javascript" src="'. $webPathPart .'tools/geetest/gtPop.js"></script>
						';
			}else{
				if (empty($svcId)){ $svcId='verCodePop'; }
				return '<input type="text" id="'. $svcId .'" name="verCode" maxlength="16" class="text" style="width:60px;" autocomplete="off" onfocus=\'GetVerCode("input","pop")\' title="如看不清验证码，可以点击验证码进行更换" />&ensp;&ensp;<span id="showVerCodePop" class="font2_2" onclick=\'GetVerCode("font","pop")\' style="cursor:pointer;">点击获取验证码</span>';
			}
		}else{
			return '<span class="font2_2">已关闭</span>';
		}
	}

	// 读取验证码 H5模式
	public static function VerCodeH5($spvcForm,$spvcId=''){
		if (OT_OpenVerCode){
			global $webPathPart,$systemArr;

			if ($systemArr['SYS_verCodeMode'] == 20){
				return '<div id="geetestDiv"></div>
						<p id="geetestWait" style="color:blue;">正在加载验证码......</p>
						<p id="geetestNote" class="display">请先完成验证</p>
						<script language="javascript" type="text/javascript" src="'. $webPathPart .'tools/geetest/gt.js"></script>
						';
			}else{
				if (empty($spvcId)){ $spvcId='verCode'; }
				return '<div style="height:44px;"><input type="text" id="'. $spvcId .'" name="verCode" maxlength="16" class="text verCode" style="float:left;width:120px;" placeholder="请输入验证码" autocomplete="off" onfocus=\'GetVerCode("input")\' title="如看不清验证码，可以点击验证码进行更换" /><div id="showVerCode" class="font2_2" onclick=\'GetVerCode("font")\' style="float:left;margin:12px 0 0 8px;cursor:pointer;">点击获取验证码</div></div>';
			}
		}else{
			return '<span class="font2_2">已关闭</span>';
		}
	}

	// 显示箭头
	// menuName:菜单名称；str:显示箭头的值，str2:与str值对比；sort:上/下箭头；
	public static function ShowArrow($menuName,$str,$str2,$sort){
		$arrowStr = '';
		$URL = OT::GetParam(array('orderName','orderSort'), array('orderName'=>$str));
		if ($str==$str2){
			if ($sort=='ASC'){
				$URL .= '&amp;orderSort=DESC';
				$arrowStr = '<small>▲</small>';
			}else{
				$URL .= '&amp;orderSort=ASC';
				$arrowStr = '<small>▼</small>';
			}
		}else{
			$URL .= '&amp;orderSort=ASC';
		}
		return '<a href="'. $URL .'">'. $menuName . $arrowStr .'</a>';
	}

	// WebPathPart变形还原
	public static function WppSign($signStr,$defVal=''){
		$signStr = str_replace('a', '../', $signStr);
		if (strpos('|../|../../|../../../|','|'. $signStr .'|') === false){ $signStr=$defVal; }
		return $signStr;
	}

	// WebPathPart变形
	public static function WppMark($signStr){
		$signStr = str_replace('../', 'a', $signStr);
		return $signStr;
	}

	// 静态日期时间目录
	public static function DatetimeDirName($dirTime,$dirMode=-1){
		global $systemArr;

		$newDirName = '';
		if ($dirMode==-1){ $dirMode=$systemArr['SYS_htmlDatetimeDir']; }
		switch ($dirMode){
			case 10:	$newDirName = TimeDate::Get('Y',$dirTime); break;
			case 20:	$newDirName = TimeDate::Get('Ym',$dirTime); break;
			default:	$newDirName = TimeDate::Get('Ymd',$dirTime);
		}

		return $newDirName;
	}

	// 栏目模式路径
	public static function InfoTypeUrl($itArr=array()){
		if ($itArr['IT_mode'] == 'web'){
			$retStr = $itArr['mainUrl'] . Url::WebID($itArr['IT_webID'],0,$itArr['webPathPart']);
		}elseif ($itArr['IT_mode'] == 'topic'){
			$retStr = $itArr['mainUrl'] . Url::ListTypeID('topic',$itArr['IT_webID'],0,$itArr['webPathPart']);
		}elseif ($itArr['IT_mode'] == 'taobaoke'){
			$retStr = $itArr['mainUrl'] . Url::GoodsList($itArr['IT_webID'],0,$itArr['webPathPart']);
		}elseif ($itArr['IT_mode'] == 'idcPro'){
			$retStr = $itArr['mainUrl'] . Url::IdcProList($itArr['IT_webID'],0,$itArr['webPathPart']);
		}elseif (substr($itArr['IT_mode'],0,3) == 'url'){
			$retStr = $itArr['mainUrl'] . Url::ListUrl($itArr['IT_mode'],$itArr['IT_URL'],$itArr['IT_isEncUrl'],$itArr['IT_ID'],$itArr['webPathPart']);
			if (strlen($retStr) == 0){ $retStr='./'; }
		}else{
			$retStr = $itArr['mainUrl'] . Url::ListID('',$itArr['IT_htmlName'],$itArr['IT_ID'],0,$itArr['webPathPart']);
		}
		return $retStr;
	}

	// 文章图片的路径
	public static function InfoImgUrl($imgValue, $imgPartUrl, $mode='pc'){
		if (Is::AbsUrl($imgValue)){
			if ($mode == 'wap'){
				return str_replace($imgPartUrl, $imgPartUrl .'thumb_', $imgValue);
			}else{
				return $imgValue;
			}
		}else{
			if ($mode == 'wap' && strpos($imgValue,'/') === false){
				return $imgPartUrl .'thumb_'. $imgValue;
			}else{
				return $imgPartUrl . $imgValue;
			}
		}
	}

	// 缩略图路径
	public static function ImgThumbPath($imgStr){
		if (strpos($imgStr,'/') !== false){
			$headPart = substr($imgStr, 0, strrpos($imgStr,'/')+1);
			return str_replace($headPart, $headPart .'thumb_', $imgStr);
		}else{
			return 'thumb_'. $imgStr;
		}
	}

	// img没有alt属性的加上alt属性
	public static function AddImgAlt($contentStr, $altStr){
		$altNewStr = str_replace(' ', '_', Str::MoreReplace($altStr,'input'));
//		$contentStr = preg_replace("/(<img[\s\S]*?)(alt=['\"]{2})(.[^>]*>)/i",'${1}${3}',$contentStr);
		$contentStr = preg_replace("/(<img)(?![^<>]*?alt[^<>]*?>)(.*?>)/i",'${1} alt="'. $altNewStr .'" ${2}',$contentStr);
		$contentStr = preg_replace("/(<img[\s\S]*?)(alt=['\"])([^>\\\"\\'\\s]*)(['\"])(.[^>]*>)/i",'${1}${2}${3}${4} title="${3}" ${5}',$contentStr);
		return $contentStr;
	}

	// 过滤关键词/标签
	public static function FilterThemeKey($str){
		return Str::MoreReplace(Str::RegExp($str,'html'),'themeKey');
	}

	// 过滤内容摘要
	public static function FilterContentKey($str){
		return str_replace('[OT_page]','',Str::MoreReplace(Str::RegExp(Str::RegExp(Str::FilterMark(Str::FilterMark($str,'script'),'style'),'html'),'contentKey'),'contentKey'));
	}

	// 获取内容表的内容
	public static function GetTabContent($tabID, $dataID){
		global $DB;
		return $DB->GetOne('select IC_content from '. OT_dbPref .'infoContent'. $tabID .' where IC_ID='. $dataID);
	}


	// 过滤编辑器内容
	public static function FilterEditor($str){
		/*
		preg_match_all("/<(\w[^>|\s]*)([^>]*?)(window\.|javascript:|vbscript:|js:|about:|file:|Document\.|vbs:|cookie| name| id|&#)(.[^>]*)/si",$str,$img_array);
		$tmp = print_r($img_array,true);
		echo('<pre>'. htmlspecialchars($str) .'<br /><br /><br />'. htmlspecialchars($tmp) .'</pre>');
		*/
		// die();

		$str = preg_replace("/(<(meta|iframe|frame|tbody|layer|form)[^>]*>|<\/(iframe|frame|meta|tbody|layer|form)>)/si","",$str);
		$str = preg_replace("/<\\?\?xml[^>]*>/si","",$str);
		$str = preg_replace("/<\s*xss[^>]*>/si","",$str);
		$str = preg_replace("/<\s*(script[^>]*)>([\s\S][^<]*)<\/\s*script>/si","",$str);
		$str = preg_replace("/<\s*(script[^>]*)>/si","",$str);
		$str = preg_replace("/<\/\s*script[^>]*>/si","",$str);
		// $str = preg_replace("/<(\w[^>|\s]*)([^>]*)(on(finish|mouse|Exit|error|click|key|load|change|focus|blur))(.[^>]*)/si",'<${1}${2}',$str);
		$str = preg_replace("/<(\w[^>|\s]*)([^>]*?)(on(Abort|Activate|AfterPrint|AfterUpdate|BeforeActivate|BeforeCopy|BeforeCut|BeforeDeactivate|BeforeEditFocus|BeforePaste|BeforePrint|BeforeUnload|BeforeUpdate|Begin|Blur|Bounce|CellChange|Change|Click|ContextMenu|ControlSelect|Copy|Cut|DataAvailable|DataSetChanged|DataSetComplete|DblClick|Deactivate|Drag|DragEnd|DragLeave|DragEnter|DragOver|DragDrop|DragStart|Drop|End|Error|ErrorUpdate|FilterChange|Finish|Focus|FocusIn|FocusOut|HashChange|Help|Input|KeyDown|KeyPress|KeyUp|LayoutComplete|Load|LoseCapture|MediaComplete|MediaError|Message|MouseDown|MouseEnter|MouseLeave|MouseMove|MouseOver|MouseUp|MouseWheel|Move|MoveEnd|MoveStart|Offline|Online|OutOfSync|Paste|Pause|PopState|Progress|PropertyChange|ReadyStateChange|Redo|Repeat|Reset|Resize|ResizeEnd|ResizeStart|Resume|Reverse|RowsEnter|RowExit|RowDelete|RowInserted|Scroll|Seek|Select|SelectionChange|SelectStart|Start|Stop|Storage|SyncRestored|Submit|TimeError|TrackChange|Undo|Unload|URLFlip|mouse|Exit|key|mouseout|rowenter|rowsdelete|rowsinserted))(.[^>]*)/si",'<${1}${2}',$str);
		$str = preg_replace("/<(\w[^>|\s]*)([^>]*?)(&#|window\.|javascript:|vbscript:|js:|data:|about:|file:|Document\.|vbs:|:expression|cookie| name| id)(.[^>]*)/si",'<${1}${2}',$str);
		$str = preg_replace("/<a (.*?)>(.*?)<\/a>/si",'<a ${1} target="_blank">${2}</a>',$str);

		$str = preg_replace("/\s+/", " ", $str);			//过滤多余回车
		$str = preg_replace("/<[ ]+/si","<",$str);			//过滤<__("<"号后面带空格)
		$str = preg_replace("/<\!–.*?–>/si","",$str);		//注释
		$str = preg_replace("/<(\!.*?)>/si","",$str);		//过滤DOCTYPE
		$str = preg_replace("/<(\/?html.*?)>/si","",$str);	//过滤html标签
		$str = preg_replace("/<(\/?head.*?)>/si","",$str);	//过滤head标签
		$str = preg_replace("/<(\/?meta.*?)>/si","",$str);	//过滤meta标签
		$str = preg_replace("/<(\/?body.*?)>/si","",$str);	//过滤body标签
		$str = preg_replace("/<(\/?link.*?)>/si","",$str);	//过滤link标签
		$str = preg_replace("/<(\/?form.*?)>/si","",$str);	//过滤form标签
		$str = preg_replace("/cookie/si","COOKIE",$str);	//过滤COOKIE标签
		$str = preg_replace("/<(applet.*?)>(.*?)<(\/applet.*?)>/si","",$str);	//过滤applet标签
		$str = preg_replace("/<(\/?applet.*?)>/si","",$str);	//过滤applet标签
		$str = preg_replace("/<(style.*?)>(.*?)<(\/style.*?)>/si","",$str);		//过滤style标签
		$str = preg_replace("/<(\/?style.*?)>/si","",$str);		//过滤style标签
		$str = preg_replace("/<(title.*?)>(.*?)<(\/title.*?)>/si","",$str);		//过滤title标签
		$str = preg_replace("/<(\/?title.*?)>/si","",$str);		//过滤title标签
		$str = preg_replace("/<(object.*?)>(.*?)<(\/object.*?)>/si","",$str);	//过滤object标签
		$str = preg_replace("/<(\/?objec.*?)>/si","",$str);		//过滤object标签
		$str = preg_replace("/<(noframes.*?)>(.*?)<(\/noframes.*?)>/si","",$str);	//过滤noframes标签
		$str = preg_replace("/<(\/?noframes.*?)>/si","",$str);	//过滤noframes标签
		$str = preg_replace("/<(i?frame.*?)>(.*?)<(\/i?frame.*?)>/si","",$str);	//过滤frame标签
		$str = preg_replace("/<(\/?i?frame.*?)>/si","",$str);	//过滤frame标签
		$str = preg_replace("/<(script.*?)>(.*?)<(\/script.*?)>/si","",$str);	//过滤script标签
		$str = preg_replace("/<(\/?script.*?)>/si","",$str);	//过滤script标签
		//$str = preg_replace("/javascript/si","Javascript",$str);//过滤script标签
		//$str = preg_replace("/vbscript/si","Vbscript",$str);	//过滤script标签
		//$str = preg_replace("/on([a-z]+)\s*=/si","",$str);		//过滤script标签
		$str = preg_replace("/&#/si","&＃",$str);				//过滤script标签
		$str = preg_replace('/[\r\n]+/', "\n", $str);			//过滤多余空行

		// $str = self::RemoveXSS($str);
		// die(htmlspecialchars($str));

		return $str;
	}

	// 移除XSS攻击的可能
	public static function RemoveXSS($val) {
		// 删除所有不可打印的字符。 CR（0a）和LF（0b）和TAB（9）是允许的
		// 这可以防止某些字符重新排列，如<java \ 0script>
		// 注意你必须在\ n，\ r和\ t之后处理分割，因为它们在一些输入中被允许
		$val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);
		// 直接替换，用户不应该需要这些，因为它们是普通字符
		// 这可以防止像 <IMG SRC=@avascript:alert('XSS')>
		$search = 'abcdefghijklmnopqrstuvwxyz';
		$search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$search .= '1234567890!@#$%^&*()';
		$search .= '~`";:?+/={}[]-_|\'\\';
		for ($i = 0; $i < strlen($search); $i++) {
			//;？ 匹配;，这是可选的
			// 0 {0,7}匹配任何填充的零，这是可选的，最多可达8个字符
			// @ @搜索十六进制值
			$val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ;
			// @ @ 0 {0,7}与'0'匹配0至7次
			$val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
		}
		// 现在剩余的空白攻击是\ t，\ n和\ r
		$ra1 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'base');
		$ra2 = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
		$ra = array_merge($ra1, $ra2);
		$found = true;
		// 只要前一轮取代某些东西，就不断更换
		while ($found == true) {
			$val_before = $val;
			for ($i = 0; $i < sizeof($ra); $i++) {
				$pattern = '/';
				for ($j = 0; $j < strlen($ra[$i]); $j++) {
					if ($j > 0) {
						$pattern .= '(';
						$pattern .= '(&#[xX]0{0,8}([9ab]);)';
						$pattern .= '|';
						$pattern .= '|(&#0{0,8}([9|10|13]);)';
						$pattern .= ')*';
					}
					$pattern .= $ra[$i][$j];
				}
				$pattern .= '/i';
				$replacement = substr($ra[$i], 0, 2).'<x></x>'.substr($ra[$i], 2); // add in <> to nerf the tag
				$val = preg_replace($pattern, $replacement, $val); // 过滤出十六进制标签
				if ($val_before == $val) {
					// 没有替换，所以退出循环
					$found = false;
				}
			}
		}
		return $val;
	}


	// 锚文本替换字符串(自动过滤超链接和图片标签)
	public static function KeyWordContent($strTemp){
		global $DB;

		preg_match_all("/(\<a[^<>]+\>.+?\<\/a\>)|(\<img[^<>]+\>)/is", $strTemp, $img_array);
		$img_array = array_unique($img_array[0]);	// 去掉重复

		$tempArr = array();
		$tempNum = 0;
		foreach ($img_array as $key => $value) {
			$tempArr[$tempNum++] = $value;
			$strTemp=str_replace($strTemp,$value,'<OTre'. $tempNum .'>');
		}

		// 锚文本
		$wordexe = $DB->query('select KW_theme,KW_URL,KW_useNum from '. OT_dbPref .'keyWord where KW_isUse=1 order by KW_rank ASC');
		while ($row = $wordexe->fetch()){
			if (strpos($strTemp,$row['KW_theme']) !== false){
				if ($row['KW_useNum']>0){
					$strTemp = preg_replace('/'. $row['KW_theme'] .'/', '<a href="'. $row['KW_URL'] .'" class="keyWord" target="_blank"><strong>'. $row['KW_theme'] .'</strong></a>', $strTemp, $row['KW_useNum']);
				}else{
					$strTemp = preg_replace('/'. $row['KW_theme'] .'/', '<a href="'. $row['KW_URL'] .'" class="keyWord" target="_blank"><strong>'. $row['KW_theme'] .'</strong></a>', $strTemp);
				}
			}
		}

		if ($tempNum > 0){
			for ($i=0; $i<$tempNum; $i++){
				$strTemp = str_replace('<OTre'. $i .'>', $tempArr[$i], $strTemp);
			}
		}
		
		return $strTemp;
	}

	// 评论/留言 跟帖
	public static function GenTie($id, $str, $event, $maxNum=3, $webPathPart=''){
		$retStr = '';
		if (strlen($str) > 10){
			$currNum = 0;
			if ($maxNum < 1){ $maxNum = 3; }
			$contArr = explode('[arr]', Area::FaceSignToImg(Area::MessageEventDeal($str,$event),$webPathPart));
			$contCount = count($contArr);
			foreach ($contArr as $val){
				if ($currNum == $maxNum){
					$retStr .= '
					<div id="hide'. $id .'Btn" class="genTie">
						<div class="tieItem">
							<a href="javascript:void(0)" class="hideBox" onclick="$id(\'hide'. $id .'Btn\').style.display=\'none\';$id(\'hide'. $id .'Cont\').style.display=\'\';"><span class="txt">已经隐藏'. ($contCount - $currNum) .'层重复盖楼</span><span class="onBtn">[点击展开]</span></a>
						</div>
					</div>

					<div id="hide'. $id .'Cont" style="display:none;">
					';
				}

				$itemArr = explode('[|]',$val .'[|][|][|][|][|][|]');
				if (strlen($itemArr[3])>0 && strpos($event,'|IP|')!==false){
					$itemIpCN = '&ensp;<span class="font1_2d">['. $itemArr[3] .']</span>';
				}else{
					$itemIpCN = '';
				}
				$retStr .= '
					<div class="genTie">
						<div class="tieItem">
							<div class="author"><span class="name">'. (strlen($itemArr[6])>0 ? $itemArr[6] .'楼&ensp;' : '') . $itemArr[2] . $itemIpCN .'</span><span class="serNum">'. ($contCount - $currNum) .'</span></div>
							<p class="cont">'. $itemArr[4] .'</p>
						</div>
						';
				$currNum ++;
			}
			if ($currNum > 0){
				if ($currNum > $maxNum){ $currNum ++; }
				$retStr .= str_repeat('</div>',$currNum);
			}
		}
		return $retStr;
	}

	// 评论/留言 表情标记转换成图片标签
	public static function FaceSignToImg($str, $webPathPart=''){
		return preg_replace("/\[face:([0-9]{1,3})\]/i",'<img src="'. $webPathPart .'inc_img/face_def/${1}.gif" border="0" style="margin:0 1px 0 1px;" />',$str);
	}

	// 评论/留言 内容输出处理
	public static function MessageEventDeal($medStr, $medEvent){
		if (strpos($medEvent,'|filterUrl|') !== false){ $medStr=Str::RegExp($medStr,'filterUrl'); }
		return $medStr;
	}

	// select标签option项模糊筛选
	public static function SelectOptionFind($selOptionId,$findWidth=70){
		return ''.
		'<script language="javascript" type="text/javascript">'.
		'var '. $selOptionId .'DataArr = new Array();'.
		''. $selOptionId .'DataArr = SelectOptionArr("'. $selOptionId .'");'.
		'</script>'.
		'<input type="text" id="'. $selOptionId .'FindStr" name="'. $selOptionId .'FindStr" style="width:'. $findWidth .'px;color:#c4c3c3;" value="模糊查找" onclick="if (this.value==\'模糊查找\'){this.value=\'\';this.style.color=\'#000000\';}" onblur="if (this.value==\'\'){this.value=\'模糊查找\';this.style.color=\'#c4c3c3\';}" onkeyup="SelectOptionSearch(\''. $selOptionId .'FindStr\',\''. $selOptionId .'\','. $selOptionId .'DataArr);" title="自动筛选出符合条件的选项">'.
		'';
	}

	// 是否为外部提交(true是，false否)
	public static function CheckIsOutSubmit($mode='alertHref',$href='index.php'){
		if(Is::OutSubmit()) {
			if ($mode=='alertHref'){
				die('
				<script language="javascript" type="text/javascript">
				alert("请通过正规途径进入该页面.");document.location.href="'. $href .'";
				</script>
				');
			}
			elseif ($mode=='alertStr'){
				die('
				alert("请通过正规途径进入该页面.");;
				');
			}
		}
	}

	// 读取积分文件
	public static function UserScore($typeStr){
		global $DB;

		$scoreexe = $DB->query('select US_score1,US_score2,US_score3 from '. OT_dbPref ."userScore where US_type='". $typeStr ."'");
		if (! $row = $scoreexe->fetch()){
			JS::AlertEnd('会员积分没找到，类型：'. $typeStr);
		}
		return $row;
	}

	// 用户金额排列
	public static function UserMoneyList($money){
		$money = floatval($money);
		return AppMoneyPay::Jud() && $money > 0 ? '金额：'. $money : '';
	}

	// 用户积分排列
	public static function UserScoreList($score1,$score2,$score3,$isScore=1){
		global $userSysArr;

		if ($isScore == 1){
			$retStr = '<table cellpadding="0" cellspacing="0">';
			if ($userSysArr['US_isScore1'] == 1){
				$retStr .= '<tr><td align="right" style="padding:1px;font-size:12px;text-align:right;">'. $userSysArr['US_score1Name'] .'：</td><td style="padding:1px;">'. $score1 .'</td></tr>';
			}
			if ($userSysArr['US_isScore2'] == 1){
				$retStr .= '<tr><td align="right" style="padding:1px;font-size:12px;text-align:right;">'. $userSysArr['US_score2Name'] .'：</td><td style="padding:1px;">'. $score2 .'</td></tr>';
			}
			if ($userSysArr['US_isScore3'] == 1){
				$retStr .= '<tr><td align="right" style="padding:1px;font-size:12px;text-align:right;">'. $userSysArr['US_score3Name'] .'：</td><td style="padding:1px;">'. $score3 .'</td></tr>';
			}
			$retStr .= '</table>';
			return $retStr;
		}
	}


	// 会员上传图片删除
	public static function DelUserFile($whereStr){
		global $DB;

		$delrec=$DB->query('select UF_ID,UF_oss,UF_type,UF_name,UF_otherImgStr from '. OT_dbPref .'userFile where '. $whereStr);
		while ($row = $delrec->fetch()){
			if (in_array($row['UF_oss'],AreaApp::OssNameArr())){
				$ossJud = AreaApp::OssDel($row['UF_oss'], $row['UF_name']);
				/* if (! $ossJud){
					JS::AlertEnd($row['UF_oss'] .' 文件“'. $row['UF_name'] .'”删除失败');
				} */
			}else{
				$delPath = StrInfo::FilePath($row['UF_type'], $row['UF_name']);
				File::Del($delPath);
				$otherImgArr = explode(',',$row['UF_otherImgStr']);
				for ($i=0; $i<count($otherImgArr); $i++){
					$delPath = StrInfo::FilePath($row['UF_type'], $otherImgArr[$i]);
					File::Del($delPath);
				}
			}
		}
		
		return $DB->query('delete from '. OT_dbPref .'userFile where '. $whereStr);
	}


	// 下载附件
	public static function InfoFile($dataID, $IF_file, $IF_fileName, $IF_fileStr='', $fileStyle=0, $webPathPart=''){
		$retStr = '';
		if (strlen($IF_fileStr) > 0){
			$fileArr = array_filter(explode('<arr>', $IF_fileStr));
			$fileCount = count($fileArr);
			if ($fileCount > 0){
				$retStr .= '<div style="font-size:14px;font-weight:bold;padding:1px 0;margin:10px 0 6px 0;border-bottom:1px #c9c9c9 solid;"><img src="'. $webPathPart .'inc_img/file.gif" style="margin:0 3px;display:inline;" />附件下载</div>';
				for ($i=0; $i<$fileCount; $i++){
					list($IF_file,$IF_fileName) = explode('|', $fileArr[$i] .'||');
					if (strlen($IF_file) >= 1){
						if (strlen($IF_fileName) == 0){ $IF_fileName = '点击下载'. ($i+1); }
						$retStr .= '<div class="down_btn'. $fileStyle .'"><div class="down_left'. $fileStyle .'"></div><div class="down_bg'. $fileStyle .'"><a href="'. $webPathPart .'deal.php?mudi=download&dataID='. $dataID .'&point='. $i .'" class="font1_1" target="_blank"><b>'. $IF_fileName .'</b></a></div><div class="down_right'. $fileStyle .'"></div><div class="clr"></div></div>';
					}
				}
			}
			return $retStr .'<div class="clr"></div>';
		}else{
			if (strlen($IF_file) >= 1){
				if (strlen($IF_fileName) == 0){
					$IF_fileName = '点击下载';
				/* }else{
					$fileExt = File::GetExt($IF_file);
					if (strlen($fileExt) > 10){ $fileExt = ''; }else{ $fileExt = '.'. $fileExt; }
					$IF_fileName .= $fileExt; */
				}
				return '<div class="down_btn"><div class="down_left"></div><div class="down_bg"><a href="'. $webPathPart .'deal.php?mudi=download&dataID='. $dataID .'" class="font1_1" target="_blank"><b>'. $IF_fileName .'</b></a></div><div class="down_right"></div><div class="clr"></div></div>';
			}
		}
	}


	// 内容库分表+1
	public static function TabCurrNumAdd($tabRow = null){
		global $DB;

		if (! $tabRow){ $tabRow = $DB->GetRow('select IS_tabCurrNum,IS_tabMaxNum from '. OT_dbPref .'infoSys'); }
		$tabCurrNum = $tabRow['IS_tabCurrNum'] + 1;
		if ($tabCurrNum >= $tabRow['IS_tabMaxNum']){
			$retArr = Area::CreateInfoContent();
		}else{
			$DB->UpdateParam('infoSys', array('IS_tabCurrNum'=>'IS_tabCurrNum+1'), 'IS_ID=1');
		}

	}


	// 创建内容库分表
	public static function CreateInfoContent(){
		global $DB,$infoSysArr;

		if (empty($infoSysArr)){ $infoSysArr = Cache::PhpFile('infoSys'); }
		
		$newTabID = 0;
		$retArr = array('ret'=>false,'note'=>'');
		$maxNewsNum = $DB->GetOne('select count(IC_ID) from '. OT_dbPref .'infoContent'. $infoSysArr['IS_tabID']);
			if ($maxNewsNum > $infoSysArr['IS_tabMaxNum']){
				$newTabID = $infoSysArr['IS_tabNum'] + 1;
			}

		if ($newTabID > 0){
			$tabName = 'infoContent'. $newTabID;
			if (OT_Database == 'sqlite'){
				$res = $DB->query('CREATE TABLE "OT_'. $tabName .'" (
							"IC_ID"  INTEGER(11) NOT NULL,
							"IC_content"  TEXT,
							PRIMARY KEY ("IC_ID")
							);');
			}else{
				$res = $DB->query('CREATE TABLE '. OT_dbPref . $tabName .' (
						  IC_ID int(11) NOT NULL DEFAULT "0",
						  IC_content longtext,
						  PRIMARY KEY (IC_ID)
						) ENGINE=MyISAM DEFAULT CHARSET=utf8;');
			}

			if ($res){
				$DB->UpdateParam('infoSys', array('IS_tabID'=>$newTabID, 'IS_tabNum'=>$newTabID, 'IS_tabCurrNum'=>0), 'IS_ID=1');

				$retArr = array('ret'=>true, 'currNum'=>0, 'note'=>'创建内容表 '. $tabName .' 成功');
			}else{
				if ($infoSysArr['IS_tabCurrNum'] != $maxNewsNum){
					$DB->UpdateParam('infoSys', array('IS_tabCurrNum'=>$maxNewsNum), 'IS_ID=1');
				}
				$retArr = array('ret'=>false, 'currNum'=>$maxNewsNum, 'note'=>'创建内容表 '. $tabName .' 失败');
			}
			
			$Cache = new Cache();
			$Cache->Php('infoSys');
		}else{
			$retArr = array('ret'=>false, 'currNum'=>$maxNewsNum, 'note'=>'当前内容表'. $infoSysArr['IS_tabID'] .'文章数量为'. $maxNewsNum .'，无需创建新表');
		}

		return $retArr;
	}


	// 自动操作-定时检查-内容库分表
	public static function AutoRunInfoContent(){
		global $DB;

		$retArr = array('ret'=>false,'note'=>'');

		$infoSysArr = Cache::PhpFile('infoSys');

		if (strtotime($infoSysArr['IS_tabCheckTime']) + $infoSysArr['IS_tabCheckMin']*60 < time()){
			if ($infoSysArr['IS_tabID'] > 0){
				// $tabNum = $DB->GetRow('select IS_tabID,IS_tabNum,IS_tabMaxNum,IS_tabCurrNum from '. OT_dbPref .'infoSys');
				$retArr = self::CreateInfoContent();
				$retArr['note'] = '【内容库分表】'. $retArr['note'];
			}else{
				$retArr = array('ret'=>false,'note'=>'【内容库分表】没用到内容表，无需创建新表');
			}
			$DB->query('update '. OT_dbPref .'infoSys set IS_tabCheckTime='. $DB->ForTime(TimeDate::Get()));
			$Cache = new Cache();
			$Cache->Php('infoSys');
		}else{
			$retArr = array('ret'=>false,'note'=>'【内容库分表】时间未到，最后检查时间'. $infoSysArr['IS_tabCheckTime'] .'，每'. $infoSysArr['IS_tabCheckMin'] .'分钟检查次，');
		}

		return $retArr;
	}

	// 加星号
	public static function RedSign(){
		return '<span style="color:red;">&ensp;*&ensp;</span>';
	}

	// 显示插件标志
	public static function PluSign($str='',$align='top'){
		return '<img src="images/img_plugin.png" title="'. $str .' 插件" alt="'. $str .' 插件" align="'. $align .'" />&ensp;';
	}

	
	// 添加会员操作记录
	public static function AddLog($userID, $username, $note, $logArr=array()){
		global $DB;

		$userIP	= Users::GetIp();
		$ipInfoArr = OT::GetIpInfoArr($userIP, '');

		$record=array();
		$record['UL_time']		= TimeDate::Get();
		$record['UL_userID']	= $userID;
		$record['UL_username']	= $username;
		$record['UL_ip']		= $userIP;
		$record['UL_ipCN']		= $ipInfoArr['address'];
		$record['UL_note']		= $note;

		return $DB->InsertParam('userLog',$record);
	}

	
	// 内容列表中按顺序选择条记录出来
	public static function ListPoint($type,$list,$arrType='str'){
		global $DB;

		$retArr = array('point'=>-1, 'str'=>'');
		switch ($type){
			case 'qiandao':
				$tabName = 'qiandaoSys';
				$fieldName = 'QS_urlPoint';
				$fieldPart = 'QS_';
				break;
		
			case 'proxyIp':
				$tabName = 'system';
				$fieldName = 'SYS_proxyIpPoint';
				$fieldPart = 'SYS_';
				break;
		
			default :
				if (in_array($arrType, array('point','str'))){
					return $retArr[$arrType];
				}else{
					return $retArr;
				}
		}
		$currPoint = intval($DB->GetOne('select '. $fieldName .' from '. OT_dbPref . $tabName)) + 1;
		$currArr = explode(PHP_EOL, $list);
		$currCount = count($currArr);
		if ($currPoint >= $currCount || $currPoint > 1000){ $currPoint = 0; }
		$currStr = Str::Filter($currArr[$currPoint],'eol');
		$DB->query('update '. OT_dbPref . $tabName .' set '. $fieldName .'='. $currPoint .' where '. $fieldPart .'ID=1');

		$retArr = array('point'=>$currPoint, 'str'=>$currStr);
		if (in_array($arrType, array('point','str'))){
			return $retArr[$arrType];
		}else{
			return $retArr;
		}
	}


	// 判断邮箱、手机号是否提醒（废弃，为兼容性，后期删掉）
	public static function UserTixing($UE_authStr, $userSysArr, $level, $mode='js', $addiStr=''){
		if ($userSysArr['US_isAuthMail'] == 1 && strpos($UE_authStr,'|邮箱|') === false && AppMail::Jud()){
			if ($userSysArr['US_isMustMail'] == 1){
				$alertStr = 'alert("'. $addiStr .'您好，您需要先填写/验证下邮箱，才能进行其他操作。\n'. $userSysArr['US_mustMailStr'] .'");document.location.href="usersCenter.php?mudi=revInfo&revType=mail";';
				if ($mode == 'str'){ die($alertStr); }else{ JS::DiyEnd($alertStr); }
			}elseif ($userSysArr['US_isMustMail'] == 2 && $level >= 2){
				$alertStr = 'if (confirm("'. $addiStr .'您好，建议填写/验证下邮箱。\n'. $userSysArr['US_mustMailStr'] .'\n如现在填写/验证邮箱，请点【确定】，否则点【取消】")){ document.location.href="usersCenter.php?mudi=revInfo&revType=mail"; isTop=true; }';
				if ($mode == 'str'){ echo($alertStr); }else{ JS::Diy($alertStr); }
			}
		}
		if ($userSysArr['US_isAuthPhone'] == 1 && strpos($UE_authStr,'|手机|') === false && AppPhone::Jud()){
			if ($userSysArr['US_isMustPhone'] == 1){
				$alertStr = 'if (typeof(isTop) == "undefined"){ alert("'. $addiStr .'您好，您需要先填写/验证下手机号，才能进行其他操作。\n'. $userSysArr['US_mustPhoneStr'] .'");document.location.href="usersCenter.php?mudi=revInfo&revType=phone"; }';
				if ($mode == 'str'){ die($alertStr); }else{ JS::DiyEnd($alertStr); }
			}elseif ($userSysArr['US_isMustPhone'] == 2 && $level >= 2){
				$alertStr = 'if (typeof(isTop) == "undefined"){ if (confirm("'. $addiStr .'您好，建议填写/验证下手机号。\n'. $userSysArr['US_mustPhoneStr'] .'\n如现在填写/验证手机号，请点【确定】，否则点【取消】")){ document.location.href="usersCenter.php?mudi=revInfo&revType=phone"; isTop=true; } }';
				if ($mode == 'str'){ echo($alertStr); }else{ JS::Diy($alertStr); }
			}
		}
	}


	// 判断是否存在该插件（废弃，为兼容性，后期删掉）
	public static function JudPayApp($appID){
		global $DB;

		$chkexe = $DB->query('select PS_ID from '. OT_dbPref .'paySoft where PS_appID='. $appID .' and PS_state=1');
		if ($chkexe->fetch()){
			return true;
		}else{
			return false;
		}
		unset($chkexe);
	}


	// 登录保存状态秒数（废弃，为兼容性，后期删掉）
	public static function LoginExpSec($num){
		switch ($num){
			case 1:		return 3600*24*30;
			case 2:		return 3600*24*15;
			case 3:		return 3600*24*7;
			case 4:		return 3600*24*3;
			case 5:		return 3600*24;
			case 21:	return 3600*12;
			case 22:	return 3600*6;
			case 23:	return 3600*3;
			case 24:	return 3600*2;
			case 25:	return 3600;
			case 31:	return 1800;
			case 32:	return 900;
			default:	return 0;
		}
	}


	// 文件路径（废弃，为兼容性，后期删掉）
	public static function FilePath($str, $fileName){
		$fileDir = '';
		switch ($str){
			case 'product':
				$fileDir = ProductFileDir;
				break;

			case 'images':
				$fileDir = ImagesFileDir;
				break;

			case 'download':
				$fileDir = DownloadFileDir;
				break;

			case 'users':
				$fileDir = UsersFileDir;
				break;

			default:
				$fileDir = InfoImgDir;
				break;

		}

		return OT_ROOT . $fileDir . $fileName;
	}

	// 上传文件目录（废弃，为兼容性，后期删掉）
	public static function FileDir($str){
		switch ($str){
			case 'product':
				return ProductFileDir;

			case 'images':
				return ImagesFileDir;

			case 'download':
				return DownloadFileDir;

			case 'users':
				return UsersFileDir;

			default:
				return InfoImgDir;

		}
	}

	// 上传文件后台目录（废弃，为兼容性，后期删掉）
	public static function FileAdminDir($str){
		switch ($str){
			case 'product':
				return ProductFileAdminDir;

			case 'images':
				return ImagesFileAdminDir;

			case 'download':
				return DownloadFileAdminDir;

			case 'users':
				return UsersFileAdminDir;

			default:
				return InfoImgAdminDir;

		}
	}

	// 颜色数值显示 >0 蓝色 <0 红色  =0黑色（废弃，为兼容性，后期删掉）
	public static function ColorNum($num, $remMoney=0){
		if ($remMoney == -9){
			return '';
		}elseif ($remMoney == -7){
			return $num;
		}elseif ($num > 0){
			return '<span style="color:green;">+ '. $num .'</span>';
		}elseif ($num < 0){
			return '<span style="color:red;">'. str_replace('-','- ',$num) .'</span>';
		}else{
			return $num;
		}
	}

	// 账户余额处理（废弃，为兼容性，后期删掉）
	public static function RemMoney($num){
		if (in_array($num, array(-9,-8,-7))){	// -9发生额和余额都不显示；-8余额不显示，发生额有+-符；-8余额不显示，发生额无+-符
			return '';
		}elseif ($num < 0){
			return '<span style="color:red;">'. str_replace('-','- ',$num) .'</span>';
		}else{
			return $num;
		}
	}

}

?>