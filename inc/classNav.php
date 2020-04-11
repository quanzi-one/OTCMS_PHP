<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class Nav{

	//导航
	public static function Show($URL,$pageCount,$pageSize,$recordCount,$skin='img',$pageMode='pageNum',$mode=''){
		$retStr = '';

		//页码风格
		switch($skin){
			case 'CN':
				$first_page	='[第一页]';
				$prev_page	='[上一页]';
				$next_page	='[下一页]';
				$last_page	='[最后页]';
				$first_page2='<span class="fontNav_2d">[第一页]</span>';
				$prev_page2	='<span class="fontNav_2d">[上一页]</span>';
				$next_page2	='<span class="fontNav_2d">[下一页]</span>';
				$last_page2	='<span class="fontNav_2d">[最后页]</span>';
				break;
			default:
	//		case 'img':
				$first_page	= '<img src="inc_img/navigation/narStart.gif" border="0" alt="第一页" style="margin-top:5px;" class="navBtnD" />';
				$prev_page	= '<img src="inc_img/navigation/narLast.gif" border="0" alt="上一页" style="margin-top:5px;" />';
				$next_page	= '<img src="inc_img/navigation/narNext.gif" border="0" alt="下一页" style="margin-top:5px;" />';
				$last_page	= '<img src="inc_img/navigation/narEnd.gif" border="0" alt="最后页" style="margin-top:5px;" />';
				$first_page2= '<img src="inc_img/navigation/narStart2.gif" border="0" alt="第一页" style="margin-top:5px;" class="navBtnD" />';
				$prev_page2	= '<img src="inc_img/navigation/narLast2.gif" border="0" alt="上一页" style="margin-top:5px;" />';
				$next_page2	= '<img src="inc_img/navigation/narNext2.gif" border="0" alt="下一页" style="margin-top:5px;" />';
				$last_page2	= '<img src="inc_img/navigation/narEnd2.gif" border="0" alt="最后页" style="margin-top:5px;" />';
				break;
	/*
			default:
				$first_page	='<span style="font-family:webdings;">9</span>';
				$prev_page	='<span style="font-family:webdings;">7</span>';
				$next_page	='<span style="font-family:webdings;">8</span>';
				$last_page	='<span style="font-family:webdings;">:</span>';
				$first_page2='<span style="font-family:webdings;" class="fontNav_2d">9</span>';
				$prev_page2	='<span style="font-family:webdings;" class="fontNav_2d">7</span>';
				$next_page2	='<span style="font-family:webdings;" class="fontNav_2d">8</span>';
				$last_page2	='<span style="font-family:webdings;" class="fontNav_2d">:</span>';
				break;
	*/
		}

		//往URL里填补GET参数
		$URL = OT::GetParam(array('page'));
		$parSign = OT::ParamSign($URL);

		$page = intval(@$_GET['page']);


		if ($page<1 || $page>$pageCount){$page=1;}
			if ($pageMode == 'pageNum'){
				$retStr .= '<div id="navShowInfo" class="navBtn fontNav_2">第'. $page .'/'. $pageCount .'页&ensp;&ensp;共'. $recordCount .'条记录</div>';
			}

		if ($page<=1){
			$retStr .= '
			<span class="navBtn">'. $first_page2 .'</span>
			<span class="navBtn">'. $prev_page2 .'</span>
			';
		}else{
			$retStr .= '
			<a href="'. $URL .'" class="navBtnPointer fontNav_2">'. $first_page .'</a>
			<a href="'. $URL . $parSign .'page='. ($page - 1) .'" class="navBtnPointer fontNav_2">'. $prev_page .'</a>
			';
		}

		$showPageNum=8;	//显示页码个数
		$pageNumHalf=intval($showPageNum/2);

		if ($pageCount <= $showPageNum){
			$startpage = 1;
			$endpage = $pageCount;
		}elseif (($page-$pageNumHalf) >= 1 && ($page+$pageNumHalf) <= $pageCount){
			$startpage = $page-$pageNumHalf;
			$endpage = $page+$pageNumHalf;
		}elseif (($page-$pageNumHalf) < 1){
			$startpage = 1;
			$endpage = $showPageNum;
		}elseif (($page+$pageNumHalf) > $pageCount){
			$startpage = $pageCount-($showPageNum-1);
			$endpage = $pageCount;
		}
		
		for ($i=$startpage; $i<=$endpage; $i++){
			if ($i == $page){
				$retStr .= '<span class="navBtn fontNav2_2">'. $i .'</span>';
			}else{
				$retStr .= '<a href="'. $URL . ($i<=1 ? '' : $parSign .'page='. $i) .'" class="navBtnPointer fontNav_2">'. $i .'</a>';
			}
		}

		if ($page >= $pageCount){
			$retStr .= '
			<span class="navBtn">'. $next_page2 .'</span>
			<span class="navBtn">'. $last_page2 .'</span>
			';
		}else{
			$retStr .= '
			<a href="'. $URL . $parSign .'page='. ($page + 1) .'" class="navBtnPointer fontNav_2">'. $next_page .'</a>
			<a href="'. $URL . $parSign .'page='. $pageCount .'" class="navBtnPointer fontNav_2">'. $last_page .'</a>
			';
		}

		$retStr .= '
		<div id="navShowGoPage" class="navBtn">
		<div>
			<select onchange="if(this.value!=\'\'){if (this.value==\'1\'){document.location.href=\''. $URL .'\';}else{document.location.href=\''. $URL . $parSign .'page=\'+ this.value}}" style="padding:0px;">
				<option value=""></option>
				';
				for ($i=1; $i<=$pageCount; $i++){
					$retStr .= '<option value="'. $i .'">'. $i .'</option>';
				}
			$retStr .= '
			</select>
		</div>
		</div>';

		global $tpl,$userRow;
		if (isset($tpl) && isset($userRow) && $tpl->webTypeName == 'usersCenter'){
			$optionStr = '';
			for ($i=10; $i<=100; $i+=10){
				$optionStr .= '<option value="'. $i .'">'. $i .'</option>';	//  '. Is::Selected($userRow['UE_pageNum'],$i) .'
			}
			$retStr .= '
			<span class="fontNav_2">&ensp;&ensp;每页<select onchange="RevUserPageNum(this.value)"><option value="'. $userRow['UE_pageNum'] .'">'. $userRow['UE_pageNum'] .'</option>'. $optionStr .'</select>条</span>
			';
		}

		if ($mode == 'get'){
			return $retStr;
		}else{
			echo($retStr);
		}

	}



	// 导航
	public static function Ajax($outputID,$pageCount,$pageSize,$recordCount,$skin=''){
		$retStr = '';

		//页码风格
		switch($skin){
			case 'CN':
				$first_page	='[第一页]';
				$prev_page	='[上一页]';
				$next_page	='[下一页]';
				$last_page	='[最后页]';
				$first_page2='<span class="fontNav_2d">[第一页]</span>';
				$prev_page2	='<span class="fontNav_2d">[上一页]</span>';
				$next_page2	='<span class="fontNav_2d">[下一页]</span>';
				$last_page2	='<span class="fontNav_2d">[最后页]</span>';
				break;
			case 'img':
				$first_page	= '<img src="inc_img/navigation/narStart.gif" border="0" alt="第一页" style="margin-top:5px;" class="navBtnD" />';
				$prev_page	= '<img src="inc_img/navigation/narLast.gif" border="0" alt="上一页" style="margin-top:5px;" />';
				$next_page	= '<img src="inc_img/navigation/narNext.gif" border="0" alt="下一页" style="margin-top:5px;" />';
				$last_page	= '<img src="inc_img/navigation/narEnd.gif" border="0" alt="最后页" style="margin-top:5px;" />';
				$first_page2= '<img src="inc_img/navigation/narStart2.gif" border="0" alt="第一页" style="margin-top:5px;" class="navBtnD" />';
				$prev_page2	= '<img src="inc_img/navigation/narLast2.gif" border="0" alt="上一页" style="margin-top:5px;" />';
				$next_page2	= '<img src="inc_img/navigation/narNext2.gif" border="0" alt="下一页" style="margin-top:5px;" />';
				$last_page2	= '<img src="inc_img/navigation/narEnd2.gif" border="0" alt="最后页" style="margin-top:5px;" />';
				break;
			default:
				$first_page	='<span style="font-family:webdings;">9</span>';
				$prev_page	='<span style="font-family:webdings;">7</span>';
				$next_page	='<span style="font-family:webdings;">8</span>';
				$last_page	='<span style="font-family:webdings;">:</span>';
				$first_page2='<span style="font-family:webdings;" class="fontNav_2d">9</span>';
				$prev_page2	='<span style="font-family:webdings;" class="fontNav_2d">7</span>';
				$next_page2	='<span style="font-family:webdings;" class="fontNav_2d">8</span>';
				$last_page2	='<span style="font-family:webdings;" class="fontNav_2d">:</span>';
				break;
		}

		// 往URL里填补GET参数
		$URL = basename($_SERVER['PHP_SELF']) . OT::GetParam(array('page'));

		$page = intval(@$_GET['page']);


		if ($page<1 || $page>$pageCount){$page=1;}
			$retStr .= '<div id="navAjaxInfo" class="navBtn fontNav_2">第'. $page .'/'. $pageCount .'页&ensp;&ensp;共'. $recordCount .'条记录</div>';

		if ($page<=1){
			$retStr .= '
			<span class="navBtn">'. $first_page2 .'</span>
			<span class="navBtn">'. $prev_page2 .'</span>
			';
		}else{
			$retStr .= '
			<a href="#" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\',1);return false;" class="navBtnPointer fontNav_2">'. $first_page .'</a>
			<a href="#" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\','. ($page - 1) .');return false;" class="navBtnPointer fontNav_2">'. $prev_page .'</a>
			';
		}

		$showPageNum=8;	//显示页码个数
		$pageNumHalf=intval($showPageNum/2);

		if ($pageCount <= $showPageNum){
			$startpage = 1;
			$endpage = $pageCount;
		}elseif (($page-$pageNumHalf) >= 1 && ($page+$pageNumHalf) <= $pageCount){
			$startpage = $page-$pageNumHalf;
			$endpage = $page+$pageNumHalf;
		}elseif (($page-$pageNumHalf) < 1){
			$startpage = 1;
			$endpage = $showPageNum;
		}elseif (($page+$pageNumHalf) > $pageCount){
			$startpage = $pageCount-($showPageNum-1);
			$endpage = $pageCount;
		}
		
		for ($i=$startpage; $i<=$endpage; $i++){
			if ($i == $page){
				$retStr .= '<span id="clickNowPage" class="navBtn fontNav2_2" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\','. $page .')">'. $i .'</span>';
			}else{
				$retStr .= '<a class="navBtnPointer fontNav_2" href="#" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\','. $i .');return false;">'. $i .'</a>';
			}
		}

		if ($page >= $pageCount){
			$retStr .= '
			<span class="navBtn">'. $next_page2 .'</span>
			<span class="navBtn">'. $last_page2 .'</span>
			';
		}else{
			$retStr .= '
			<a href="#" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\','. ($page + 1) .');return false;" class="navBtnPointer fontNav_2">'. $next_page .'</a>
			<a href="#" onclick="AjaxNavHref(\''. $outputID .'\',\''. $URL .'\','. $pageCount .');return false;" class="navBtnPointer fontNav_2">'. $last_page .'</a>
			';
		}

		$retStr .= '
		<div id="navAjaxGoPage" class="navBtn" style="padding-top:1px;height:22px;">
		<div>
			<select onchange="if(this.value!=\'\'){AjaxNavHref(\''. $outputID .'\',\''. $URL .'\',this.value);}" style="padding:0px;">
				<option value="">&ensp;</option>
				';
				for ($i=1; $i<=$pageCount; $i++){
					$retStr .= '<option value="'. $i .'">'. $i .'</option>';
				}
			$retStr .= '
			</select>
		</div>
		</div>
		';

		return $retStr;
	}
}

?>