<?php
require(dirname(__FILE__) .'/check.php');
$skin->CheckIframe();


/* **** 版权所有 ****

 网钛CMS(OTCMS)
 程序开发：网钛科技
 官方网站：http://otcms.com
 联系ＱＱ：877873666
 联系邮箱：877873666@qq.com

 **** 版权所有 **** */


//打开用户表，并检测用户是否登录
$MB->Open('','login');

$skin->WebTop();


echo('
<script language="javascript" type="text/javascript" src="js/infoSys.js?v='. OT_VERSION .'"></script>
');


switch ($mudi){
	case 'manage':
		$MB->IsSecMenuRight('alertBack',194,$dataType);
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$sysAdminArr;

	$revexe=$DB->query('select * from '. OT_dbPref .'infoSys');
	if ($row = $revexe->fetch()){
		$IS_addition			= $row['IS_addition'];
		$IS_defIsAudit			= $row['IS_defIsAudit'];
		$IS_defIsNew			= $row['IS_defIsNew'];
		$IS_defIsHomeThumb		= $row['IS_defIsHomeThumb'];
		$IS_defIsThumb			= $row['IS_defIsThumb'];
		$IS_defIsImg			= $row['IS_defIsImg'];
		$IS_defIsFlash			= $row['IS_defIsFlash'];
		$IS_defIsMarquee		= $row['IS_defIsMarquee'];
		$IS_defIsRecom			= $row['IS_defIsRecom'];
		$IS_defIsTop			= $row['IS_defIsTop'];
		$IS_defMarkNews			= $row['IS_defMarkNews'];
		$IS_defVoteMode			= $row['IS_defVoteMode'];
		$IS_defVoteStr			= $row['IS_defVoteStr'];
		$IS_defIsReply			= $row['IS_defIsReply'];
		$IS_defTopicID			= $row['IS_defTopicID'];
		$IS_defTopAddiID		= $row['IS_defTopAddiID'];
		$IS_defAddiID			= $row['IS_defAddiID'];
		$IS_defIsCheckUser		= $row['IS_defIsCheckUser'];
		$IS_defUserGroupList	= $row['IS_defUserGroupList'];
		$IS_defUserLevel		= $row['IS_defUserLevel'];
		$IS_defScore1			= $row['IS_defScore1'];
		$IS_defScore2			= $row['IS_defScore2'];
		$IS_defScore3			= $row['IS_defScore3'];
		$IS_defCutScore1		= $row['IS_defCutScore1'];
		$IS_defCutScore2		= $row['IS_defCutScore2'];
		$IS_defCutScore3		= $row['IS_defCutScore3'];
		$IS_defReadNum1			= $row['IS_defReadNum1'];
		$IS_defReadNum2			= $row['IS_defReadNum2'];
		$IS_defTemplate			= $row['IS_defTemplate'];
		$IS_defTemplateWap		= $row['IS_defTemplateWap'];
		$IS_defIsSitemap		= $row['IS_defIsSitemap'];
		$IS_defIsXiongzhang		= $row['IS_defIsXiongzhang'];
		$IS_defBdPing			= $row['IS_defBdPing'];
		$IS_tabNum				= $row['IS_tabNum'];
		$IS_tabID				= $row['IS_tabID'];
		$IS_tabCurrNum			= $row['IS_tabCurrNum'];
		$IS_tabMaxNum			= $row['IS_tabMaxNum'];
		$IS_tabCheckTime		= $row['IS_tabCheckTime'];
		$IS_tabCheckMin			= $row['IS_tabCheckMin'];
		$IS_moreArea			= $row['IS_moreArea'];
		$IS_prevNextArrow		= $row['IS_prevNextArrow'];
		$IS_prevAndNext			= $row['IS_prevAndNext'];
		$IS_isSavePrevNextId	= $row['IS_isSavePrevNextId'];
		$IS_is360meta			= $row['IS_is360meta'];
		$IS_isXiongzhang		= $row['IS_isXiongzhang'];
		$IS_xiongzhangId		= $row['IS_xiongzhangId'];
		$IS_isContentKey		= $row['IS_isContentKey'];
		$IS_isTime				= $row['IS_isTime'];
		$IS_isWriter			= $row['IS_isWriter'];
		$IS_isSource			= $row['IS_isSource'];
		$IS_isReadNum			= $row['IS_isReadNum'];
		$IS_isReplyNum			= $row['IS_isReplyNum'];
		$IS_isWapQrcode			= $row['IS_isWapQrcode'];
		$IS_keyWordNum			= $row['IS_keyWordNum'];
		$IS_isNewsVote			= $row['IS_isNewsVote'];
		$IS_newsVoteSecond		= $row['IS_newsVoteSecond'];
		$IS_readNum1			= $row['IS_readNum1'];
		$IS_readNum2			= $row['IS_readNum2'];
		$IS_oneReadNum			= $row['IS_oneReadNum'];
		$IS_isMarkNews			= $row['IS_isMarkNews'];
		$IS_isSaveMarkNewsId	= $row['IS_isSaveMarkNewsId'];
		$IS_isNewsReply			= $row['IS_isNewsReply'];
		$IS_newsReplyMode		= $row['IS_newsReplyMode'];
		$IS_changyanId1			= $row['IS_changyanId1'];
		$IS_changyanId2			= $row['IS_changyanId2'];
		$IS_changyanIsDashang	= $row['IS_changyanIsDashang'];
		$IS_changyanIsFace		= $row['IS_changyanIsFace'];
		$IS_newsReplyNum		= $row['IS_newsReplyNum'];
		$IS_newsReplySecond		= $row['IS_newsReplySecond'];
		$IS_newsReplyAudit		= $row['IS_newsReplyAudit'];
		$IS_newsReplyMaxLen		= $row['IS_newsReplyMaxLen'];
		$IS_newsReplyName		= $row['IS_newsReplyName'];
		$IS_isShareNews			= $row['IS_isShareNews'];
		$IS_shareNewsCode		= $row['IS_shareNewsCode'];
		$IS_newsVoteCode		= $row['IS_newsVoteCode'];
		$IS_isNoCollPage		= $row['IS_isNoCollPage'];
		$IS_isGoPage			= $row['IS_isGoPage'];
		$IS_eventStr			= $row['IS_eventStr'];
		$IS_copyAddiStr			= $row['IS_copyAddiStr'];
		$IS_fileStyle			= $row['IS_fileStyle'];
		if (AppNewsVerCode::Jud()){
			$IS_newsVerCodeAnswer	= $row['IS_newsVerCodeAnswer'];
			$IS_newsVerCodeImg		= $row['IS_newsVerCodeImg'];
			$IS_newsVerCodeNote		= $row['IS_newsVerCodeNote'];
		}else{
			$IS_newsVerCodeAnswer	= '';
			$IS_newsVerCodeImg		= '';
			$IS_newsVerCodeNote		= '';
		}

	}
	unset($revexe);

	$topAddiStr = $addiStr = '';
	$addiexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='news' order by IW_rank ASC");
	while ($row = $addiexe->fetch()){
		$topAddiStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IS_defTopAddiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
		$addiStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IS_defAddiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
	}
	unset($addiexe);

	echo('
	<form id="dealForm" name="dealForm" method="post" action="infoSys_deal.php?mudi=deal" onsubmit="return CheckForm()">
	<input type="hidden" id="dataType" name="dataType" value="'. $dataType .'" />
	<input type="hidden" id="dataTypeCN" name="dataTypeCN" value="'. $dataTypeCN .'" />
	<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>

	<div class="tabMenu">
	<ul>
	   <li rel="tabBase" class="selected">后台新增文章默认项</li>
	   <li rel="tabNewsWeb">前台文章内容页设置</li>
	   <li id="buyBox" rel="tabBuy" style="display:none;">商业版专属</li>
	</ul>
	</div>

	<div class="tabMenuArea">
		<table id="tabBase" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr style="display:'. (AppBase::Jud() ? 'none' : '') .';">
			<td align="right">附加功能：</td>
			<td>
				<label><input type="checkbox" name="addition[]" value="|themeAppr|" '. Is::InstrChecked($IS_addition,'|themeAppr|') .' />标题伪原创<span style="color:red;">(不建议打钩)</span></label>&ensp;
				<label><input type="checkbox" name="addition[]" value="|contentAppr|" '. Is::InstrChecked($IS_addition,'|contentAppr|') .' />内容伪原创<span style="color:red;">(不建议打钩)</span></label>&ensp;
				<label><input type="checkbox" name="addition[]" value="|makeHtml|" '. Is::InstrChecked($IS_addition,'|makeHtml|') .' />辅助外部采集生成静态页<span style="color:red;">(没用外部采集器不要打钩)</span></label>&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">编辑器：</td>
			<td>
				<label><input type="checkbox" name="addition[]" value="|isSaveContImg|" '. Is::InstrChecked($IS_addition,'|isSaveContImg|') .' />保存内容中的图片到本地'. Skin::PluSign('需要商业版基础包','bottom') .'</label>&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">文章属性：</td>
			<td>
				<label><input type="checkbox" id="defIsAudit" name="defIsAudit" value="1" '. Is::Checked($IS_defIsAudit,1) .' />已审核</label>&ensp;
				<label><input type="checkbox" id="defIsNew" name="defIsNew" value="1" '. Is::Checked($IS_defIsNew,1) .' />最新消息</label>&ensp;
				<label><input type="checkbox" id="defIsHomeThumb" name="defIsHomeThumb" value="1" '. Is::Checked($IS_defIsHomeThumb,1) .' />首页缩略图</label>&ensp;
				<label><input type="checkbox" id="defIsThumb" name="defIsThumb" value="1" '. Is::Checked($IS_defIsThumb,1) .' />缩略图</label>&ensp;
				<label><input type="checkbox" id="defIsFlash" name="defIsFlash" value="1" '. Is::Checked($IS_defIsFlash,1) .' />幻灯片</label>&ensp;
				<label><input type="checkbox" id="defIsImg" name="defIsImg" value="1" '. Is::Checked($IS_defIsImg,1) .' />滚动图片</label>&ensp;
				<label><input type="checkbox" id="defIsMarquee" name="defIsMarquee" value="1" '. Is::Checked($IS_defIsMarquee,1) .' />滚动信息</label>&ensp;
				<label><input type="checkbox" id="defIsRecom" name="defIsRecom" value="1" '. Is::Checked($IS_defIsRecom,1) .' />推荐</label>&ensp;
				<label><input type="checkbox" id="defIsTop" name="defIsTop" value="1" '. Is::Checked($IS_defIsTop,1) .' />置顶</label>
			</td>
		</tr>
		<tr>
			<td align="right">相关文章：</td>
			<td>
				<label><input type="radio" name="defMarkNews" value="1" '. Is::Checked($IS_defMarkNews,1) .' />开启<label>&ensp;&ensp;&ensp;&ensp;
				<label><input type="radio" name="defMarkNews" value="0" '. Is::Checked($IS_defMarkNews,0) .' />关闭<label>&ensp;&ensp;&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">投票方式：</td>
			<td>
				<label><input type="radio" name="defVoteMode" value="0" '. Is::Checked($IS_defVoteMode,0) .' />关闭</label>&ensp;&ensp;
				<label><input type="radio" name="defVoteMode" value="1" '. Is::Checked($IS_defVoteMode,1) .' />心情</label>&ensp;&ensp;
				<label><input type="radio" name="defVoteMode" value="2" '. Is::Checked($IS_defVoteMode,2) .' />顶踩</label>&ensp;&ensp;
				<label><input type="radio" name="defVoteMode" value="11" '. Is::Checked($IS_defVoteMode,11) .' />百度喜欢按钮</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">评论区：</td>
			<td>
				<label><input type="radio" name="defIsReply" value="1" '. Is::Checked($IS_defIsReply,1) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="defIsReply" value="10" '. Is::Checked($IS_defIsReply,10) .' />仅限会员</label>&ensp;&ensp;
				<label><input type="radio" name="defIsReply" value="0" '. Is::Checked($IS_defIsReply,0) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		'. AppTopic::InfoSysTrBox1($IS_defTopicID) .'
		<tr>
			<td align="right">正文头附加内容：</td>
			<td>
				<select id="defTopAddiID" name="defTopAddiID">
				<option value="0">无</option>
				'. $topAddiStr .'
				</select>
			</td>
		</tr>
		<tr>
			<td align="right">正文尾附加内容：</td>
			<td>
				<select id="defAddiID" name="defAddiID">
				<option value="0">无</option>
				'. $addiStr .'
				</select>
			</td>
		</tr>
		'. AppMapBaidu::InfoSysTrBox1($IS_defIsSitemap, $IS_defIsXiongzhang, $IS_defBdPing) .'
		<tr>
			<td align="right">阅读量范围：</td>
			<td align="left">
				<input type="text" id="defReadNum1" name="defReadNum1" size="50" style="width:50px;" value="'. $IS_defReadNum1 .'" />
				-
				<input type="text" id="defReadNum2" name="defReadNum2" size="50" style="width:50px;" value="'. $IS_defReadNum2 .'" />
				&ensp;&ensp;'. $skin->TishiBox('新增文章时，阅读量随机选择范围内，如要固定值，范围值2个都填一样。') .'
			</td>
		</tr>
		<tr>
			<td align="right">文章内容库：</td>
			<td align="left">
				<select id="tabID" name="tabID">
				<option value="0">自身表</option>
				');
				$addTabID = $maxNewsNum = 0;
				$tabArr = $DB->GetTabArr('xiao');
				for ($i=1; $i<=$IS_tabNum; $i++){
					if (in_array(strtolower(OT_dbPref) .'infocontent'. $i, $tabArr)){
						$maxNewsNum = $DB->GetOne('select count(IC_ID) from '. OT_dbPref .'infoContent'. $i);
						echo('<option value="'. $i .'" '. Is::Selected($IS_tabID,$i) .'>内容表'. $i .'（'. $maxNewsNum .'篇）</option>');
						$addTabID = $i;
					}
				}
				$addTabID ++;
				echo('
				</select>&ensp;'. ($maxNewsNum > 5000 ? '<input type="button" value="创建内容表'. $addTabID .'" onclick="CreateTab('. $addTabID .')" />' : '') .'
			</td>
		</tr>
		<tr>
			<td align="right">内容库单表限制：</td>
			<td align="left">
				<input type="text" id="tabMaxNum" name="tabMaxNum" size="50" style="width:80px;" value="'. $IS_tabMaxNum .'" /> 篇
				<span style="color:red;">（取值1000~50000，该功能需要 自动操作 - 定时检查 - 文章内容库分表 打钩才会生效，下2个同样）</span>
			</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">内容库分表检查时间：</td>
			<td align="left">'. $IS_tabCheckTime .'</td>
		</tr>
		<tr>
			<td align="right" class="font1_2d">内容库分表检查间隔：</td>
			<td align="left">
				<input type="text" id="tabCheckMin" name="tabCheckMin" size="50" style="width:50px;" value="'. $IS_tabCheckMin .'" />分钟
				<input type="button" value="关闭" onclick=\'$id("tabCheckMin").value="0";\' />
				<input type="button" value="1小时" onclick=\'$id("tabCheckMin").value="60";\' />
				<input type="button" value="3小时" onclick=\'$id("tabCheckMin").value="180";\' />
				<input type="button" value="5小时" onclick=\'$id("tabCheckMin").value="300";\' />
				<input type="button" value="12小时" onclick=\'$id("tabCheckMin").value="720";\' />
				<input type="button" value="24小时" onclick=\'$id("tabCheckMin").value="1440";\' />
			</td>
		</tr>
		'. AppBase::InfoSysTrBox2($IS_defTemplate) .'
		'. AppWap::InfoSysTrBox2($IS_defTemplateWap) .'
		<tr>
			<td align="right" valign="top" style="padding-top:6px;">移入[更多选项]选项卡：</td>
			<td align="left">
				<ul>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|titleAddi|" '. Is::InstrChecked($IS_moreArea,'|titleAddi|') .' />文章标题附加</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|source|" '. Is::InstrChecked($IS_moreArea,'|source|') .' />来源</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|writer|" '. Is::InstrChecked($IS_moreArea,'|writer|') .' />作者</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|template|" '. Is::InstrChecked($IS_moreArea,'|template|') .' />模板文件'. Skin::PluSign('商业版基础包/手机版','bottom') .'</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|webURL|" '. Is::InstrChecked($IS_moreArea,'|webURL|') .' />外部链接</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|file|" '. Is::InstrChecked($IS_moreArea,'|file|') .' />附件文件</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|topAddiID|" '. Is::InstrChecked($IS_moreArea,'|topAddiID|') .' />正文头附加内容</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|addiID|" '. Is::InstrChecked($IS_moreArea,'|addiID|') .' />正文尾附加内容</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|voteMode|" '. Is::InstrChecked($IS_moreArea,'|voteMode|') .' />投票方式</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|isMarkNews|" '. Is::InstrChecked($IS_moreArea,'|isMarkNews|') .' />相关文章</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|isReply|" '. Is::InstrChecked($IS_moreArea,'|isReply|') .' />评论区</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|topicID|" '. Is::InstrChecked($IS_moreArea,'|topicID|') .' />专题'. Skin::PluSign('专题','bottom') .'</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|bdPing|" '. Is::InstrChecked($IS_moreArea,'|bdPing|') .' />百度推送&熊掌号'. Skin::PluSign('百度熊掌号和主动推送','bottom') .'</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|addition|" '. Is::InstrChecked($IS_moreArea,'|addition|') .' />附加功能</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|readNum|" '. Is::InstrChecked($IS_moreArea,'|readNum|') .' />阅读量</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|state|" '. Is::InstrChecked($IS_moreArea,'|state|') .' />状态</label></li>
					<li style="float:left;width:135px;"><label><input type="checkbox" name="moreArea[]" value="|isCheckUser|" '. Is::InstrChecked($IS_moreArea,'|isCheckUser|') .' />限制阅读</label></li>
				</ul>
			</td>
		</tr>
		<tr style="display:none;">
			<td colspan="2" class="font2_2" style="color:red;padding-top:20px;">提醒：仅对后台文章管理-新增文章有效</td>
		</tr>
		</table>


		<table id="tabNewsWeb" style="display:none;" cellpadding="0" cellspacing="0" summary="" class="padd3">
		<tr><td style="width:150px;"></td><td></td></tr>
		<tr>
			<td align="right">360智能摘要：</td>
			<td>
				<label><input type="radio" name="is360meta" value="1" '. Is::Checked($IS_is360meta,1) .' />显示<label>&ensp;&ensp;
				<label><input type="radio" name="is360meta" value="0" '. Is::Checked($IS_is360meta,0) .' />关闭<label>&ensp;&ensp;
			</td>
		</tr>
		'. AppMapBaidu::InfoSysTrBox2($IS_isXiongzhang, $IS_xiongzhangId) .'
		<tr>
			<td align="right">文章摘要：</td>
			<td>
				<label><input type="radio" name="isContentKey" value="1" '. Is::Checked($IS_isContentKey,1) .' />显示<label>&ensp;&ensp;
				<label><input type="radio" name="isContentKey" value="0" '. Is::Checked($IS_isContentKey,0) .' />关闭<label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">发布时间：</td>
			<td align="left">
				<label><input type="radio" name="isTime" value="1" '. Is::Checked($IS_isTime,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="isTime" value="0" '. Is::Checked($IS_isTime,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">作者：</td>
			<td align="left">
				<label><input type="radio" name="isWriter" value="1" '. Is::Checked($IS_isWriter,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="isWriter" value="0" '. Is::Checked($IS_isWriter,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">来源：</td>
			<td align="left">
				<label><input type="radio" name="isSource" value="1" '. Is::Checked($IS_isSource,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="isSource" value="0" '. Is::Checked($IS_isSource,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">阅读量：</td>
			<td align="left">
				<label><input type="radio" name="isReadNum" value="1" '. Is::Checked($IS_isReadNum,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="isReadNum" value="0" '. Is::Checked($IS_isReadNum,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">评论量：</td>
			<td align="left">
				<label><input type="radio" name="isReplyNum" value="1" '. Is::Checked($IS_isReplyNum,1) .' />显示</label>&ensp;&ensp;
				<label><input type="radio" name="isReplyNum" value="0" '. Is::Checked($IS_isReplyNum,0) .' />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">附件样式：</td>
			<td align="left" class="fileStyleClass">
				<span class="fileStyleImg"><img src="temp/infoFile.jpg" style="display:none;" /></span>
				<label><input type="radio" name="fileStyle" value="0" '. Is::Checked($IS_fileStyle,0) .' />默认(红色)</label>&ensp;&ensp;
				<label><input type="radio" name="fileStyle" value="1" '. Is::Checked($IS_fileStyle,1) .' />蓝白色</label>&ensp;&ensp;
				<label><input type="radio" name="fileStyle" value="2" '. Is::Checked($IS_fileStyle,2) .' />深蓝色</label>&ensp;&ensp;
			</td>
		</tr>
		'. AppWap::InfoSysBox1($IS_isWapQrcode) .'
		'. AppNewsVerCode::InfoSysTrBox1($IS_newsVerCodeAnswer, $IS_newsVerCodeImg, $IS_newsVerCodeNote) .'
		<tr>
			<td align="right">阅读量一次增加范围：</td>
			<td align="left">
				<input type="text" id="readNum1" name="readNum1" size="50" style="width:50px;" value="'. $IS_readNum1 .'" />
				-
				<input type="text" id="readNum2" name="readNum2" size="50" style="width:50px;" value="'. $IS_readNum2 .'" />
				&ensp;&ensp;'. $skin->TishiBox('为部分用户制造网站很多人访问的假象，如只想增加1,两个都填1。') .'
			</td>
		</tr>
		<tr>
			<td align="right">多次刷新只算一次阅读量：</td>
			<td align="left">
				<label><input type="radio" name="oneReadNum" value="1" '. Is::Checked($IS_oneReadNum,1) .' />启用</label>&ensp;&ensp;
				<label><input type="radio" name="oneReadNum" value="0" '. Is::Checked($IS_oneReadNum,0) .' />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">内容页投票：</td>
			<td align="left">
				<label><input type="radio" id="isNewsVote1" name="isNewsVote" value="1" '. Is::Checked($IS_isNewsVote,1) .' onclick="CheckNewsVote();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isNewsVote0" name="isNewsVote" value="0" '. Is::Checked($IS_isNewsVote,0) .' onclick="CheckNewsVote();" />隐藏</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="newsVoteBox" style="display:none;">
		<tr>
			<td align="right" class="font1_2d">文章连续投票间隔秒数：</td>
			<td align="left"><input type="text" id="newsVoteSecond" name="newsVoteSecond" size="50" style="width:30px;" value="'. $IS_newsVoteSecond .'" /> 秒</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">内容页“百度喜欢”代码：</td>
			<td>
				<textarea id="newsVoteCode" name="newsVoteCode" style="width:500px; height:100px;">'. $IS_newsVoteCode .'</textarea>
				<div class="font3_2" style="padding-bottom:5px;"><span class="font1_2d pointer" onclick=\'InputDefShare("newsVoteCode");\'>[默认]</span>&ensp;“百度喜欢后分享”申请地址 <a href="http://share.baidu.com/code/likeshare" target="_blank" class="font2_2">http://share.baidu.com/code/likeshare</a></div>
			</td>
		</tr>
		</tbody>
		<tr>
			<td align="right">上/下一篇：</td>
			<td>
				<label><input type="radio" id="prevAndNext0" name="prevAndNext" value="0" '. Is::Checked($IS_prevAndNext,0) .' onclick="CheckPrevAndNext();" />关闭<label>&ensp;&ensp;
				<label><input type="radio" name="prevAndNext" value="10" '. Is::Checked($IS_prevAndNext,10) .' onclick="CheckPrevAndNext();" />新/旧<label>&ensp;&ensp;
				<label><input type="radio" name="prevAndNext" value="20" '. Is::Checked($IS_prevAndNext,20) .' onclick="CheckPrevAndNext();" />旧/新<label>&ensp;&ensp;
			</td>
		</tr>
		<tr>
			<td align="right">相关文章：</td>
			<td>
				<label><input type="radio" id="isMarkNews1" name="isMarkNews" value="1" '. Is::Checked($IS_isMarkNews,1) .' onclick="CheckMarkNews();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isMarkNews0" name="isMarkNews" value="0" '. Is::Checked($IS_isMarkNews,0) .' onclick="CheckMarkNews();" />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tr id="markNewsBox">
			<td align="right" class="font1_2d">自动保存相关文章信息：</td>
			<td>
				<label><input type="radio" name="isSaveMarkNewsId" value="1" '. Is::Checked($IS_isSaveMarkNewsId,1) .' />开启<label>&ensp;&ensp;
				<label><input type="radio" name="isSaveMarkNewsId" value="0" '. Is::Checked($IS_isSaveMarkNewsId,0) .' />关闭<label>&ensp;&ensp;
				<input type="button" value="清空信息" onclick="ClearSaveMarkNewsId();" />
			</td>
		</tr>
		<tr>
			<td align="right">评论区：</td>
			<td>
				<label><input type="radio" id="isNewsReply1" name="isNewsReply" value="1" '. Is::Checked($IS_isNewsReply,1) .' onclick="CheckNewsReply();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isNewsReply10" name="isNewsReply" value="10" '. Is::Checked($IS_isNewsReply,10) .' onclick="CheckNewsReply();" />仅限会员</label>&ensp;&ensp;
				<label><input type="radio" id="isNewsReply0" name="isNewsReply" value="0" '. Is::Checked($IS_isNewsReply,0) .' onclick="CheckNewsReply();" />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tbody id="newsReplyBox" style="display:none;">
		<tr class="newsReplyMode0Class">
			<td align="right" class="font1_2d">文章评论每页显示数量：</td>
			<td align="left"><input type="text" id="newsReplyNum" name="newsReplyNum" size="50" style="width:30px;" value="'. $IS_newsReplyNum .'" /></td>
		</tr>
		<tr class="newsReplyMode0Class">
			<td align="right" class="font1_2d">文章连续评论间隔秒数：</td>
			<td align="left"><input type="text" id="newsReplySecond" name="newsReplySecond" size="50" style="width:30px;" value="'. $IS_newsReplySecond .'" /> 秒</td>
		</tr>
		<tr class="newsReplyMode0Class">
			<td align="right" class="font1_2d">文章评论审核：</td>
			<td>
				<label><input type="radio" name="newsReplyAudit" value="0" '. Is::Checked($IS_newsReplyAudit,0) .' />开启</label>&ensp;&ensp;
				<label><input type="radio" name="newsReplyAudit" value="1" '. Is::Checked($IS_newsReplyAudit,1) .' />关闭</label>&ensp;&ensp;
			</td>
		</tr>
		<tr class="newsReplyMode0Class">
			<td align="right" class="font1_2d">文章评论最大字数：</td>
			<td align="left"><input type="text" id="newsReplyMaxLen" name="newsReplyMaxLen" size="50" style="width:30px;" value="'. $IS_newsReplyMaxLen .'" /></td>
		</tr>
		<tr class="newsReplyMode0Class">
			<td align="right" class="font1_2d">文章评论回复称呼：</td>
			<td><input type="text" id="newsReplyName" name="newsReplyName" size="50" style="width:100px;" value="'. $IS_newsReplyName .'" /></td>
		</tr>
		</tbody>
		<tr>
			<td align="right">内容页“分享到”代码：</td>
			<td>
				<label><input type="radio" id="isShareNews1" name="isShareNews" value="1" '. Is::Checked($IS_isShareNews,1) .' onclick="CheckShareNews();" />启用</label>&ensp;&ensp;
				<label><input type="radio" id="isShareNews0" name="isShareNews" value="0" '. Is::Checked($IS_isShareNews,0) .' onclick="CheckShareNews();" />禁用</label>&ensp;&ensp;
			</td>
		</tr>
		<tr id="shareNewsBox" style="display:none;">
			<td align="right" valign="top" style="padding-top:6px;" class="font1_2d">内容页“分享到”代码：</td>
			<td>
				<textarea id="shareNewsCode" name="shareNewsCode" style="width:500px; height:100px;">'. $IS_shareNewsCode .'</textarea>
				<div class="font3_2" style="padding-bottom:5px;"><span class="font1_2d pointer" onclick=\'InputDefShare("shareNewsCode");\'>[默认]</span>&ensp;“百度分享”申请地址 <a href="http://share.baidu.com/" target="_blank" class="font2_2">http://share.baidu.com/</a></div>
			</td>
		</tr>
		</table>
		');

		if (! AreaApp::Jud(4)){
			$skin->PaySoftBox('tabBuy','您尚未购买商业版基础包插件，无法使用该功能。');
			echo('<input type="hidden" id="authState" name="authState" value="false" />');

		}elseif ($sysAdminArr['SA_isLan'] == 1 && $sysAdminArr['SA_sendUrlMode'] == 0){
			$skin->PaySoftBox('tabBuy',$skin->LanPaySoft());
			echo('<input type="hidden" id="authState" name="authState" value="false" />');

		}else{
			$beforeURL	= GetUrl::CurrDir(1);

			$paraArr = array(
				'IS_keyWordNum'			=> $IS_keyWordNum ,
				'IS_prevNextArrow'		=> $IS_prevNextArrow ,
				'IS_isSavePrevNextId'	=> $IS_isSavePrevNextId ,
				'IS_newsReplyMode'		=> $IS_newsReplyMode ,
				'IS_changyanId1'		=> $IS_changyanId1 ,
				'IS_changyanId2'		=> $IS_changyanId2 ,
				'IS_changyanIsDashang'	=> $IS_changyanIsDashang ,
				'IS_changyanIsFace'		=> $IS_changyanIsFace ,
				'IS_eventStr'			=> $IS_eventStr ,
				'IS_copyAddiStr'		=> $IS_copyAddiStr ,
				'IS_isNoCollPage'		=> $IS_isNoCollPage ,
				'IS_isGoPage'			=> $IS_isGoPage ,
				'beforeURL'				=> $beforeURL ,
				'judAppChangyan'		=> AppChangyan::Jud() ? 1 : 0 ,
				'judApp41'				=> AppChangyan::Jud() ? 1 : 0 ,
				);

			$getWebHtml = OTauthWeb('infoSys', 'infoSys_V3.00.php', $paraArr);
			if (strpos($getWebHtml,'(OTCMS)') === false){
				$authAlertStr='未知原因';
				if (strpos($getWebHtml,'<!-- noRemote -->') !== false){
					$authAlertStr='无法访问外网';
				}elseif (strpos($getWebHtml,'<!-- noUse -->') !== false){
					$authAlertStr='授权禁用';
				}else{
				
				}
				$getWebHtml = ''.
					$skin->PaySoftBox('tabBuy','因'. $authAlertStr .'而无法使用',false,true) .
					'<input type="hidden" id="authState" name="authState" value="false" />';
			}else{
				echo('
				<script language="javascript" type="text/javascript">
				$id("buyBox").style.display = "";
				</script>
				');
			}
			echo($getWebHtml);
		}

		echo('
		<div class="tabMenuSubmit"><input type="submit" class="btnBg" value="保 存" /></div>
	</div>

	</form>
	');

}

?>