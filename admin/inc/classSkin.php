<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class Skin{

	// 后台参数设置
	protected $mSysArr;

	// 后台GET参数
	protected $mMudi;
	protected $mRightBody;
	protected $mNohrefStr;


	// 构造函数
	public function __construct($sysArr, $optionArr){
		$this->mSysArr		= $sysArr;
		$this->mMudi		= $optionArr['mudi'];
		$this->mRightBody	= $optionArr['rightBody'];
		$this->mNohrefStr	= $optionArr['nohrefStr'];
	}

	// 通用配置文件
	function WebConfig(){
		echo('
		<!-- big5  gb2312 gbk utf-8 -->
		<meta http-equiv="Content-Type" content="text/html; charset='. OT_Charset .'" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
		<!-- 插入常用JS -->
		<script language="javascript" type="text/javascript">var editorMode="'. $this->mSysArr['SA_editorMode'] .'";</script>
		<script language="javascript" type="text/javascript" src="js/inc/jquery.min.js?v='. OT_VERSION .'"></script>
		<script language="javascript" type="text/javascript" src="js/inc/common.js?v='. OT_VERSION .'"></script>
		<!-- 插入日历控件 -->
		<script language="javascript" type="text/javascript" src="tools/My97DatePicker/WdatePicker.js?v='. OT_VERSION .'"></script>
		<!-- 插入CSS -->
		<link href="style.css?v='. OT_VERSION .'" type="text/css" rel="stylesheet" />
		');
	}

	// 通用内容
	function WebCommon(){
		echo('
		<noscript><iframe src="*.htm"></iframe></noscript><!-- 防止另存为 -->

		<script language="javascript" type="text/javascript">
		var timeStamp=(new Date()).getTime();
		//调整父窗口RightContentIframe的高度
		function WindowHeight(num){
			var csmID = 0;
			try {
				csmID = parent.$id("currSubMenuID").value;
			}catch (e){}
			try {
				if (navigator.userAgent.indexOf("Firefox") >= 0){
					parent.$id("RightFrm"+ csmID).style.height=5000 +"px";
				}else if (navigator.userAgent.indexOf("Chrome") >= 0){
					if ((new Date()).getTime()<timeStamp+1000){
						parent.$id("RightFrm"+ csmID).style.height="400px";
					}
				}
				parent.$id("RightFrm"+ csmID).style.height=((document.body.scrollHeight<400 ? 400 : document.body.scrollHeight) + num) +"px";
			}catch (e){}

			try {
				parent.StartCalcTime();
			}catch (e){}
		}
		</script>

		<iframe id="DataDeal" name="DataDeal" src="about:blank" width="0" height="0" style="display:none"></iframe>
		<div id="divAlert" class="divAlert" style="display:none;"></div><!-- 浮层警示框 -->
		');
	}

	// 页头通用
	function WebTop($title='',$bodyStr=''){
		echo('
		<!DOCTYPE html>
		<html>
		<head>
			<title>'. $title .'</title>
		');
			$this->WebConfig();
		echo('
		</head>
		');
		if ($bodyStr != ''){
			echo('<body '. $bodyStr .'>');
		}elseif (in_array($this->mMudi,array('show','show2','selectFile','meihua')) || $this->mNohrefStr == 'close'){
			echo('<body '. $bodyStr .'>');
		}else{
			echo($this->mRightBody);
		}
		$this->WebCommon();
	}

	// 页尾通用
	function WebBottom(){
		echo('
		<div class="clr"></div>
		<div style="height:40px;">&ensp;</div>
		</body></html>
		');
	}


	// *****      通用表格框架 START     *****
	function TableTopId($str){
		static $STTCInum=1;
		$STTCInum ++;
		if ($str==''){
			return 'STTCInull'. $STTCInum;
		}else{
			return $str;
		}
	}

	function TableTop($img,$IDname,$CNname){
		echo('
		<table style="width:99%;" align="center" cellpadding="0" cellspacing="0" border="0" class="tabWeb1" summary="">
		<tr><td style="height:24px; padding-left:6px; background:url(images/right_titleBg.gif);">
			<table cellpadding="0" cellspacing="0" summary=""><tr>
			<td style="padding-right:6px;"><img src="images/'. $img .'" alt="" /></td>
			<td style="font-weight:bold;padding-top:4px;letter-spacing:2px" class="fontMenu" id="'. $this->TableTopId($IDname) .'">'. $CNname .'</td>
			</table>
		</td></tr>
		<tr><td style="padding:5px">
		');
	}

	function TableBottom(){
		echo('
		</td></tr>
		<tr><td style="height:11px;" class="tabColorB" colspan="20"></td></tr>
		</table>
		');
	}

	function TableTop2($img,$IDname,$CNname){
		echo('
		<table style="width:99%;" align="center" cellpadding="0" cellspacing="0" border="0" summary="" class="tabList1">
		<tr><td style="height:24px; background:url(images/right_titleBg.gif); padding-left:6px" colspan="30" class="border1_1">
			<table cellpadding="0" cellspacing="0" summary=""><tr>
			<td style="padding-right:6px;"><img src="images/'. $img .'" alt="" /></td>
			<td style="font-weight:bold;padding-top:4px;letter-spacing:2px;" class="fontMenu" id="'. $this->TableTopId($IDname) .'">'. $CNname .'</td>
			</table>
		</td></tr>
		');
	}

	function TableItemTitle($perSZ,$nameSZ){
		$perSZ	= explode(',',$perSZ);
		$nameSZ	= explode(',',$nameSZ);
		$perCount = count($perSZ);

		echo('
		<tr class="tabColor1">');
		for ($STIi=0; $STIi<$perCount; $STIi++){
			if ($STIi==0){ $STIclass='border1_1'; }else{ $STIclass='border1_2'; }
			echo('
			<td width="'. $perSZ[$STIi] .'" align="center" class="'. $STIclass .' font1_2" style="padding:5px">'. $nameSZ[$STIi] .'</td>');
		}
		echo('
		</tr>');
	}

	function TableNoData($str='暂无内容'){
		echo('<tr><td style="padding:8px;background" colspan="30" class="font2_1" align="center">'. $str .'</td></tr>');
	}

	function TableBottom2($pageCount, $pageSize, $recordCount){
		echo('
		<tr class="tabColor2"><td style="height:3px;" colspan="30"></td></tr>
		</table>

		<table style="width:99%;" align="center" cellpadding="0" cellspacing="0" border="0" summary="">
		<tr><td>
		');
		
		$this->ShowNavigation('?mudi='. $this->mMudi, $pageCount, $pageSize, $recordCount, 'img');

		echo('
		</td></tr>
		</table>
		');
	}
	//*****         通用表格框架 END      *****



	// 检查是否在框架中
	function CheckIframe(){
		if ($this->mNohrefStr == 'close2'){
			if(empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) !== preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])){
				echo('
				<center style="padding:10px;">
					<div style="width:500px; padding:6px; border:1px red dashed; color:red; font-size:13px">
						由于你打开浏览器的多窗口（强制在新标签中打开链接）功能，<br />
						导致该页面脱离框架，部分功能将失效和无法使用！<br />
						建议你关闭浏览器的多窗口功能来使用该网站后台.
					</div>
				</center>
				');
			}
		}
	}


	// 显示箭头
	// menuName:菜单名称；str:显示箭头的值，str2:与str值对比；sort:上/下箭头；
	function ShowArrow($menuName,$str,$str2,$sort){
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
		return '<a href="'. $URL .'" class="font1_2">'. $menuName . $arrowStr .'</a>';
	}


	// 提示框
	function TishiBox($tishiStr,$tishiStr2=''){
		static $tishiNum = 0;
		$tishiNum ++;

		if (strlen($tishiStr2) == 0){ $tishiStr2 = $tishiStr; }
		return '<img src="images/img_tishi3.gif" alt="'. $tishiStr .'" title="'. $tishiStr .'" style="cursor:pointer;" align="center" onclick=\'if ($id("tishiStr'. $tishiNum .'").style.display==""){$id("tishiStr'. $tishiNum .'").style.display="none";}else{$id("tishiStr'. $tishiNum .'").style.display="";}\' /><span id="tishiStr'. $tishiNum .'" style="color:red;display:none;">('. $tishiStr2 .')</span>';
	}


	// 获取不到远程授权页面内容提示框
	function PaySoftBox($idStr,$contStr,$isShow=false,$isGet=false){
		$retStr = '
			<table id="'. $idStr .'" style="display:'. ($isShow?'':'none') .';" cellpadding="0" cellspacing="0" summary="" class="padd3">
			<tr><td style="width:150px;"></td><td></td></tr>
			<tr>
				<td align="right">原因：</td>
				<td>'. $contStr .'</td>
			</tr>
			</table>		
			';
		if ($isGet){
			return $retStr;
		}else{
			echo $retStr;
		}
	}


	// 内网模式提示语
	function LanPaySoft(){
		return '内网模式不加载.';
	}


	//导航
	function ShowNavigation($URL, $pageCount, $pageSize, $recordCount,$skin){
		//页码风格
		switch($skin){
			case 'zn':
				$first_page='[第一页]';
				$prev_page='[上一页]';
				$next_page='[下一页]';
				$last_page='[最后页]';
				break;
			case 'img':
				$first_page='<img src="images/right_narStart.gif" border="0" alt="" />';
				$prev_page='<img src="images/right_narLast.gif" border="0" alt="" />';
				$next_page='<img src="images/right_narNext.gif" border="0" alt="" />';
				$last_page='<img src="images/right_narEnd.gif" border="0" alt="" />';
				break;
			default:
				$first_page='<span style="font-family:webdings;">9</span>';
				$prev_page='<span style="font-family:webdings;">7</span>';
				$next_page='<span style="font-family:webdings;">8</span>';
				$last_page='<span style="font-family:webdings;">:</span>';
				break;
		}

		$URL = OT::GetParam(array('page'));

		$page=intval(@$_GET['page']);

		echo('
		<table style="width:100%;border:1px #dadada solid;border-top:none;" cellpadding="0" cellspacing="0" border="0" summary="">
		<tr>
			<td align="center" class="tabColorB padd5">');

		if ($page<1 || $page>$pageCount){$page=1;}
			echo('<span class="font1_2">第'. $page .'页/共'. $pageCount .'页　　共'. $recordCount .'条记录</span>　　');

		if ($page<=1){
			echo('
			<span class="font1_2d">'. $first_page .'</span>&ensp;
			<span class="font1_2d">'. $prev_page .'</span>');
		}else{
			echo('
			<a href="'. $URL .'&page=1" class="font1_2">'. $first_page .'</a>&ensp;
			<a href="'. $URL .'&page='. ($page - 1) .'" class="font1_2">'. $prev_page .'</a>');
		}

		echo('&ensp;');

		if ($pageCount <= 7){
			$startpage = 1;
			$endpage = $pageCount;
		}elseif ($page-3 >= 1 && $page+3 <= $pageCount){
			$startpage = $page-3;
			$endpage = $page+3;
		}elseif ($page-3 < 1){
			$startpage = 1;
			$endpage = 7;
		}elseif ($page+3 > $pageCount){
			$startpage = $pageCount-6;
			$endpage = $pageCount;
		}
		
		for ($i=$startpage; $i<=$endpage; $i++){
			if ($i == $page){
				echo('<span class="font2_2">'. $i .'</span>&ensp;');
			}else{
				echo('<a class="font1_2" href="'. $URL .'&page='. $i .'">'. $i .'</a>&ensp;');
			}
		}

		if ($page >= $pageCount){
			echo('
			<span class="font1_2d">'. $next_page .'</span>&ensp;
			<span class="font1_2d">'. $last_page .'</span>');
		}else{
			echo('
			<a href="'. $URL .'&page='. ($page + 1) .'" class="font1_2">'. $next_page .'</a>&ensp;
			<a href="'. $URL .'&page='. $pageCount .'" class="font1_2">'. $last_page .'</a>');
		}

		if ($pageCount<100){
			echo('　<select onchange=\'if(this.value!=""){document.location.href="'. $URL .'&amp;page="+ this.value}\'><option value="">&ensp;</option>');
				for ($i=1; $i<=$pageCount; $i++){
			echo('<option value="'. $i .'">'. $i .'</option>');
				}
			echo('</select>');
		}else{
			echo('　<input type="text" id="goPageNum" name="goPageNum" size="2" style="width:25px;" value="" onkeyup=\'if (this.value!=FiltInt(this.value)){this.value=FiltInt(this.value)}\' /><input type="button" value="GO" onclick=\'if ($id("goPageNum").value==""){alert("请输入要跳转的页码.");$id("goPageNum").focus();}else{document.location.href="'. $URL .'&amp;page="+ $id("goPageNum").value}\' />');
		}


		echo('
			</td>
			<td style="padding:5px" align="center" class="tabColorB">
				<form name="itemNumForm" method="post" action="admin_cl.php?mudi=itemNum">
				<script language="javascript" type="text/javascript">document.write (\'<input type="hidden" name="itemNumURL" value="\'+ document.location.href +\'" />\')</script>
				每页显示记录数：<input type="text" name="itemNum" size="2" style="width:30px;" value="'. $pageSize .'" onclick="this.select();" />
				&ensp;<input type="submit" value="保存" />
				</form>
			</td>
		</tr>
		</table>
		');
	}

	// 显示必填标志
	public static function RedSign($num=true){
		if ($num){ return '<span style="color:red;">*</span>&ensp;'; }
	}

	// 显示插件标志
	public static function PluSign($str='',$align='top'){
		return '<img src="images/img_plugin.png" title="'. $str .' 插件" alt="'. $str .' 插件" align="'. $align .'" />&ensp;';
	}

	// 显示电脑版标志
	public static function ImgPc(){
		return '<img src="images/imgPc.gif" title="电脑版" alt="电脑版" align="top" />&ensp;';
	}

	// 显示手机版标志
	public static function ImgWap(){
		return '<img src="images/imgWap.gif" title="手机版" alt="手机版" align="top" />&ensp;';
	}

	// 颜色选择器
	public static function ColorBox($mode,$valueId,$styleId){
		return '<img src="images/img_color.gif" style="cursor:pointer; margin:3px; vertical-align:top;" onclick=\'OT_OpenColor("'. $mode .'","'. $valueId .'","'. $styleId .'");\' alt="选择颜色" />';
	}

	// 明码/暗码 切换按钮
	public static function HiCodeBtn($idName){
		return '<input type="button" id="'. $idName .'Btn" value="明码" onclick=\'PwdTextBtn("'. $idName .'")\' />';
	}

}