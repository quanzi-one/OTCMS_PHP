<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class UsersNews{

	public static function Jud(){
		global $userSysArr;
		if ($userSysArr['US_isNews'] == 1){ return true; }else{ return false; }
	}

	// 用户菜单
	public static function UminiMenu(){
		if (! self::Jud()){ return ''; }

		return '<div id="uebox_news" class="left" style="padding-left:8px;"><a href="./usersCenter.php?mudi=addNews" class="font2_1" >[投稿]</a></div>';
	}

	// 会员中心信息统计栏
	public static function UcInfo($userID){
		if (! self::Jud()){ return ''; }

		global $DB;
		$whereTime = TimeDate::Add('d',-30,TimeDate::Get());
		$infoAddNum = $infoAuditNum = 0;
		$infoAddNum = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_userID='. $userID .' and IF_time>='. $DB->ForTime($whereTime));
		if ($infoAddNum > 0){
			$infoAuditNum = $DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_userID='. $userID .' and IF_isAudit=1 and IF_time>='. $DB->ForTime($whereTime));
		}

		return '
			<div class="item">
				<div class="serNum">0'. (++ UsersCenter::$infoNum) .'</div>
				<div class="cont">
					文章
					<p class="number">最近30天内发表了<span class="num1">'. $infoAddNum .'</span>篇，审核通过<span class="num2">'. $infoAuditNum .'</span>篇</p>
				</div>
				<div class="func"><a href="usersCenter.php?mudi=newsManage">管理</a></div>
			</div>
			<div class="clr"></div>
			';
	}

	public static function UcMenu(){
		if (! self::Jud()){ return ''; }

		return '
			<li><a href="usersCenter.php?mudi=addNews">发布文章</a></li>
			<li><a href="usersCenter.php?mudi=newsManage">文章管理</a></li>
			';
	}

	// 添加/修改文章
	public static function AddOrRev(){
		global $DB,$mudi,$userSysArr,$userRow,$systemArr,$webPathPart;

		$infoSysArr = Cache::PhpFile('infoSys');

		$UE_ID			= $userRow['UE_ID'];
		$UE_username	= $userRow['UE_username'];

		$dataID = OT::GetInt('dataID');

		if (! self::Jud()){
			return '<br /><br /><center>文章功能已关闭，有问题请联系管理员</center>';
		}

		$retStr = '';
		if ($mudi == 'revNews'){
			if ($userSysArr['US_isNewsRev'] == 0){
				return '<br /><br /><center>不允许修改文章，有问题请联系管理员</center>';
			}
			
			$revexe = $DB->query('select IF_ID,IF_theme,IF_isOri,IF_source,IF_writer,IF_typeStr,IF_tabID,IF_content,IF_upImgStr,IF_pageNum,IF_themeKey,IF_contentKey,IF_mediaFile,IF_file,IF_fileName,IF_fileStr,IF_isRenameFile,IF_isUserFile,IF_isNew,IF_isHomeThumb,IF_isThumb,IF_isFlash,IF_isImg,IF_isMarquee,IF_isRecom,IF_isTop,IF_img,IF_voteMode,IF_isReply,IF_isCheckUser,IF_score1,IF_score2,IF_score3,IF_cutScore1,IF_cutScore2,IF_cutScore3,IF_isEnc,IF_encContent,IF_addition,IF_topicID,IF_topAddiID,IF_addiID from '. OT_dbPref .'info where IF_ID='. $dataID);
				if (! $row = $revexe->fetch()){
					return '<br /><br /><center style="font-size:14px;">获取不到指定的记录.</center>';
				}
				$IF_ID			= $row['IF_ID'];
				$IF_theme		= $row['IF_theme'];
				$IF_isOri		= $row['IF_isOri'];
				$IF_source		= $row['IF_source'];
				$IF_writer		= $row['IF_writer'];
				$IF_typeStr		= $row['IF_typeStr'];
				if ($row['IF_tabID'] > 0){
					$IF_content	= Area::GetTabContent($row['IF_tabID'], $dataID);
				}else{
					$IF_content	= $row['IF_content'];
				}
					$beforeURL = GetUrl::CurrDir();
					$imgUrl = $beforeURL . InfoImgDir;
					$IF_content	= str_replace(InfoImgAdminDir,$imgUrl,$IF_content);
				$IF_upImgStr	= $row['IF_upImgStr'];
				$IF_pageNum		= $row['IF_pageNum'];
				$IF_themeKey	= $row['IF_themeKey'];
				$IF_contentKey	= $row['IF_contentKey'];
				$IF_mediaFile	= $row['IF_mediaFile'];
				$IF_file		= $row['IF_file'];
				$IF_fileName	= $row['IF_fileName'];
				$IF_fileStr		= $row['IF_fileStr'];
					if (strlen($IF_fileStr) == 0 && strlen($IF_file) > 0){
						$IF_fileStr = $IF_file .'|'. $IF_fileName .'|';
						$IF_fileName = '';
						$IF_file = '';
					}
				$IF_isRenameFile= $row['IF_isRenameFile'];
				$IF_isUserFile	= $row['IF_isUserFile'];
				$IF_isNew		= $row['IF_isNew'];
				$IF_isHomeThumb	= $row['IF_isHomeThumb'];
				$IF_isThumb		= $row['IF_isThumb'];
				$IF_isFlash		= $row['IF_isFlash'];
				$IF_isImg		= $row['IF_isImg'];
				$IF_isMarquee	= $row['IF_isMarquee'];
				$IF_isRecom		= $row['IF_isRecom'];
				$IF_isTop		= $row['IF_isTop'];
				$IF_img			= $row['IF_img'];
				$IF_voteMode	= $row['IF_voteMode'];
				$IF_isReply		= $row['IF_isReply'];
				$IF_topicID		= $row['IF_topicID'];
				$IF_topAddiID	= $row['IF_topAddiID'];
				$IF_addiID		= $row['IF_addiID'];
				$IF_isCheckUser	= $row['IF_isCheckUser'];
				$IF_score1		= $row['IF_score1'];
				$IF_score2		= $row['IF_score2'];
				$IF_score3		= $row['IF_score3'];
				$IF_cutScore1	= $row['IF_cutScore1'];
				$IF_cutScore2	= $row['IF_cutScore2'];
				$IF_cutScore3	= $row['IF_cutScore3'];
				$IF_isEnc		= $row['IF_isEnc'];
				$IF_encContent	= $row['IF_encContent'];
				$IF_addition	= $row['IF_addition'];
			unset($revexe);

			$ug = new UserGroup($UE_ID);
			$infoScore1	= $ug->row['UG_infoScore1'];
			$infoScore2	= $ug->row['UG_infoScore2'];
			$infoScore3	= $ug->row['UG_infoScore3'];
			if (strpos($ug->row['UG_event'],'|禁止投稿|') !== false){
				return '<br /><br /><center>您所在用户组禁止投稿，如有问题请联系管理员</center>';
			}

			$mudiCN = '修 改';
		}else{
			if ($userSysArr['US_isNewsAdd'] == 0){
				return '<br /><br /><center>文章发表已关闭，如有问题请联系管理员</center>';
			}

			$ug = new UserGroup($UE_ID);
			$infoScore1	= $ug->row['UG_infoScore1'];
			$infoScore2	= $ug->row['UG_infoScore2'];
			$infoScore3	= $ug->row['UG_infoScore3'];
			if (strpos($ug->row['UG_event'],'|禁止投稿|') !== false){
				return '<br /><br /><center>您所在用户组禁止投稿，如有问题请联系管理员</center>';
			}

			// 总投稿数限制
			$retArr = $ug->InfoTotalNumArr();
				if ($retArr['res']){
					if (strlen($retArr['note']) > 0){
						$retStr .= '<div style="color:blue;font-size:16px;padding-bottom:6px;">'. $retArr['note'] .'</div>';
					}
				}else{
					return '<center style="padding-top:25px;font-size:16px;">'. $retArr['note'] .'</center>';
				}
			
			// 每日投稿限制
			$retArr = $ug->InfoDayNumArr();
				if ($retArr['res']){
					if (strlen($retArr['note']) > 0){
						$retStr .= '<div style="color:blue;font-size:16px;padding-bottom:6px;">'. $retArr['note'] .'</div>';
					}
				}else{
					return '<center style="padding-top:25px;font-size:16px;">'. $retArr['note'] .'</center>';
				}
			
			$IF_ID			= 0;
			$IF_theme		= '';
			$IF_isOri		= 0;
			$IF_source		= $userSysArr['US_newsSource'];
			$IF_writer		= $userSysArr['US_newsWriter'];
				if (strpos($IF_writer,'{username}') !== false){
					$IF_writer		= str_replace('{username}', $UE_username, $IF_writer);
				}elseif (strpos($IF_writer,'{会员用户名}') !== false){
					$IF_writer		= str_replace('{会员用户名}', $UE_username, $IF_writer);
				}elseif (strpos($IF_writer,'{会员用户名部分隐藏}') !== false){
					$IF_writer		= str_replace('{会员用户名部分隐藏}', Str::PartHide($UE_username), $IF_writer);
				}
				if (strpos($IF_writer,'{会员昵称}') !== false){
					$IF_writer		= str_replace('{会员昵称}', $DB->GetOne('select UE_realname from '. OT_dbPref .'users where UE_ID='. $UE_ID), $IF_writer);
				}
			$IF_typeStr		= '';
			$IF_content		= '';
			$IF_upImgStr	= '';
			$IF_pageNum		= '';
			$IF_themeKey	= '';
			$IF_contentKey	= '';
			$IF_mediaFile	= '';
			$IF_file		= '';
			$IF_fileName	= '';
			$IF_fileStr		= '';
			$IF_isRenameFile= 0;
			$IF_isUserFile	= 0;
			$IF_isNew		= 1;
			$IF_isHomeThumb	= 0;
			$IF_isThumb		= 0;
			$IF_isFlash		= 0;
			$IF_isImg		= 0;
			$IF_isMarquee	= 0;
			$IF_isRecom		= 0;
			$IF_isTop		= 0;
			$IF_img			= '';
			$IF_voteMode	= 1;
			$IF_isReply		= 1;
			$IF_topicID		= 0;
			$IF_topAddiID	= 0;
			$IF_addiID		= 0;
			$IF_isCheckUser	= 0;
			$IF_score1		= '';
			$IF_score2		= '';
			$IF_score3		= '';
			$IF_cutScore1	= '';
			$IF_cutScore2	= '';
			$IF_cutScore3	= '';
			$IF_isEnc		= 0;
			$IF_encContent	= '';
			$IF_addition	= '';

			$mudiCN = '发 表';
		}

		if ($userSysArr['US_isRevSource'] == 1){
			$sourceStr = '<input type="text" id="source" name="source" class="text" value="'. Str::MoreReplace($IF_source,'input') .'" style="width:500px;" />';
		}else{
			$sourceStr = $IF_source .'<input type="hidden" id="source" name="source" value="'. Str::MoreReplace($IF_source,'input') .'" />';
		}
		if ($userSysArr['US_isRevWriter'] == 1){
			$writerStr = '<input type="text" id="writer" name="writer" class="text" value="'. Str::MoreReplace($IF_writer,'input') .'" style="width:500px;" />';
		}else{
			$writerStr = $IF_writer .'<input type="hidden" id="writer" name="writer" value="'. Str::MoreReplace($IF_writer,'input') .'" />';
		}

		if (strlen(Str::RegExp($userSysArr['US_addNewsAnnoun'],'html')) >= 3){
			$retStr .= '<div class="announ">'. $userSysArr['US_addNewsAnnoun'] .'</div>';
		}

		$retStr .= '
		<script language="javascript" type="text/javascript" src="js/usersNews.js?v='. OT_VERSION .'"></script>
		<script language="javascript" type="text/javascript" id="kindeditorJs" src="tools/kindeditor4/kindeditor-all-min.js?v='. OT_VERSION .'"></script>

		<div style="color:red;padding-top:10px;">带 * 号为必填项，其他项可选。</div>
		<form id="dealForm" name="dealForm" method="post" action="usersNews_deal.php?mudi=deal" onsubmit="return CheckNewsForm();" class="form">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<input type="hidden" id="dataID" name="dataID" value="'. $IF_ID .'" />
		<input type="hidden" id="isScore1" name="isScore1" value="'. $userSysArr['US_isScore1'] .'" />
		<input type="hidden" id="isScore2" name="isScore2" value="'. $userSysArr['US_isScore2'] .'" />
		<input type="hidden" id="isScore3" name="isScore3" value="'. $userSysArr['US_isScore3'] .'" />
		<input type="hidden" id="score1Name" name="score1Name" value="'. $userSysArr['US_score1Name'] .'" />
		<input type="hidden" id="score2Name" name="score2Name" value="'. $userSysArr['US_score2Name'] .'" />
		<input type="hidden" id="score3Name" name="score3Name" value="'. $userSysArr['US_score3Name'] .'" />
		<input type="hidden" id="infoScore1" name="infoScore1" value="'. $infoScore1 .'" />
		<input type="hidden" id="infoScore2" name="infoScore2" value="'. $infoScore2 .'" />
		<input type="hidden" id="infoScore3" name="infoScore3" value="'. $infoScore3 .'" />
		<table cellpadding="0" cellspacing="0" class="revInfoBox" style="width:900px;">
		<tr><td style="width:100px;"></td><td></td></tr>
		<tr>
			<td align="right"><span class="font2_2">*</span>&ensp;标题：</td>
			<td>
				<input type="text" id="theme" name="theme" class="text" value="'. Str::MoreReplace($IF_theme,'input') .'" style="width:500px;" />
				&ensp;<label title="原创打钩，会根据百度原创保护 星火计划2.0 在页头增加原创标签"><input type="checkbox" name="isOri" value="1" '. Is::Checked($IF_isOri,1) .' />原创</label>
				&ensp;&ensp;<a href="javascript:void(0);" onclick="CheckRepeatTheme();return false;" style="text-decoration:underline;">检测重复标题</a>
			</td>
		</tr>
		<tr>
			<td align="right">来源：</td>
			<td>'. $sourceStr .'</td>
		</tr>
		<tr>
			<td align="right">作者：</td>
			<td>'. $writerStr .'</td>
		</tr>
		<tr>
			<td align="right"><span class="font2_2">*</span>&ensp;栏目：</td>
			<td>
				<select id="typeStr" name="typeStr">
				<option value=""></option>
				';
				$typeNum = 0;
				$typeexe = $DB->query('select * from '. OT_dbPref .'infoType where IT_state=1 and IT_level=1 and IT_isUser=1 order by IT_rank ASC');
				if (! $row = $typeexe->fetch()){
					$retStr .= '
					<option value="">无栏目，需到后台 常规设置-栏目管理 开启[用户投稿]</option>
					';
				}else{
					do {
						$typeNum ++;
						$type2exe = $DB->query('select * from '. OT_dbPref .'infoType where IT_state=1 and IT_level=2 and IT_fatID='. $row['IT_ID'] .' and IT_isUser=1 order by IT_rank ASC');
						if (! $row2 = $type2exe->fetch()){
							$retStr .= '<option value=",'. $row['IT_ID'] .'," '. Is::InstrSelected($IF_typeStr,','. $row['IT_ID'] .',') .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>';
						}else{
							$retStr .= '<optgroup label="'. $typeNum .'、'. $row['IT_theme'] .'" style="font-weight:normal;"></optgroup>';
							do{
								$retStr .= '<option value=",'. $row['IT_ID'] .','. $row2['IT_ID'] .'," '. Is::InstrSelected($IF_typeStr,','. $row2['IT_ID'] .',') .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
							}while ($row2 = $type2exe->fetch());
						}
						$type2exe=null;
					}while ($row = $typeexe->fetch());
				}
				unset($typeexe);

				$retStr .= '
				</select>&ensp;
				<span class="font2_2">如果有二级栏目,一级栏目不能选择</span><span onclick=\'if($id("bugBox").style.display==""){$id("bugBox").style.display="none";}else{$id("bugBox").style.display="";}\'>&ensp;</span><label id="bugBox" style="display:none;"><input type="checkbox" id="bugMode" name="bugMode" value="1" />调试模式</label>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:6px;"><span class="font2_2">*</span>&ensp;内容：</td>
			<td>
				<textarea id="content" name="content" cols="40" rows="4" style="width:680px;height:380px;" class="text" onclick=\'LoadEditor("content",300);\' title="点击开启编辑器模式">'. $IF_content .'</textarea>
				<div>
					<input type="button" onclick=\'InsertStrToEditor("content", "[OT_page]");\' value="插入分页符" />
					'. AppBase::UsersNewsBox1($userSysArr['US_isNewsUpImg'], 'content') .'
					<input type="hidden" id="infoFileDir" name="infoFileDir" value="'. InfoImgDir .'" />
					<input type="hidden" id="upImgStr" name="upImgStr" value="'. $IF_upImgStr .'" />
				</div>
			</td>
		</tr>
		<tr>
			<td align="right">自动分页字数：</td>
			<td>
				<input type="text" id="pageNum" name="pageNum" size="50" style="width:50px;" value="'. $IF_pageNum .'" />
				&ensp;<span class="font2_2">页数=内容总字数÷自动分页字数；如果在内容中加入了手动分页符或不想分页,请填写0或留空</span>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">关键词(标签)：</td>
			<td>
				<input type="text" id="themeKey" name="themeKey" class="text" value="'. Str::MoreReplace($IF_themeKey,'input') .'" style="width:500px;" />
				&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("fc");return false;\' style="text-decoration:underline;">分词获取</a>
				<!-- &ensp;&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("dz");return false;\' style="text-decoration:underline;">网络获取</a> -->
				&ensp;&ensp;<a href="javascript:void(0);" onclick=\'GetKeyWord("");return false;\' style="text-decoration:underline;">本地获取</a>
				<br /><span class="font2_2">多个关键词用空格、竖杆“|”或逗号“,”隔开</span>
				&ensp;<span id="onloadThemeKey" class="font3_2"></span>
			</td>
		</tr>
		<tr>
			<td align="right" valign="top" style="padding-top:8px;">内容摘要：</td>
			<td>
				<textarea id="contentKey" name="contentKey" cols="40" rows="4" style="width:500px;height:60px;" class="text">'. Str::MoreReplace($IF_contentKey,'input') .'</textarea>
				&ensp;<a href="javascript:void(0);" onclick="ToContentKey();return false;" style="text-decoration:underline;">自动获取</a>
				<br /><span class="font2_2">内容摘要为空则自动获取</span>
			</td>
		</tr>
		'. AppBase::UsersNewsBox2($userSysArr['US_isNewsUpFile'], $IF_isRenameFile, $IF_isUserFile, $IF_file, $IF_fileName, $IF_fileStr) .'
		';
		$addiStr = '';
		if (strpos($userSysArr['US_newsAddiStr'],'|new|') !== false){
			$addiStr .= '<label title="出现在前台最新消息处"><input type="checkbox" id="isNew" name="isNew" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isNew,1) .' />最新消息</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|homeThumb|') !== false){
			$addiStr .= '<label title="首页栏目显示图片文章需要此属性，同时所属栏目【在首页显示图片文章】需要开启"><input type="checkbox" id="isHomeThumb" name="isHomeThumb" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isHomeThumb,1) .' />首页缩略图</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|thumb|') !== false){
			$addiStr .= '<label title="列表页中显示"><input type="checkbox" id="isThumb" name="isThumb" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isThumb,1) .' />缩略图</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|flash|') !== false){
			$addiStr .= '<label title="首页左上角幻灯片"><input type="checkbox" id="isFlash" name="isFlash" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isFlash,1) .' />幻灯片</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|img|') !== false){
			$addiStr .= '<label title="首页中部滚动图片"><input type="checkbox" id="isImg" name="isImg" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isImg,1) .' />滚动图片</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|marquee|') !== false){
			$addiStr .= '<label title="页头滚动信息"><input type="checkbox" id="isMarquee" name="isMarquee" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isMarquee,1) .' />滚动信息</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|recom|') !== false){
			$addiStr .= '<label title="出现在精彩推荐/本类推荐里"><input type="checkbox" id="isRecom" name="isRecom" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isRecom,1) .' />推荐</label>&ensp;&ensp;';
		}
		if (strpos($userSysArr['US_newsAddiStr'],'|top|') !== false){
			$addiStr .= '<label title="出现在最新消息第一条及列表页前几条"><input type="checkbox" id="isTop" name="isTop" onclick="CheckAddition()" value="1" '. Is::Checked($IF_isTop,1) .' />置顶</label>&ensp;&ensp;';
		}
		if (strlen($addiStr) > 0){
			$addiStr = '
				<tr>
					<td align="right">文章属性：</td>
					<td>'. $addiStr .'</td>
				</tr>
				';
		}

		$retStr .= $addiStr .'
		<tr id="imgBox" style="display:none;">
			<td align="right">缩略图/图片：</td>
			<td>
				<input type="text" id="img" name="img" size="50" style="width:320px;" value="'. $IF_img .'" />
				<span style="position:relative;"><span style="position:absolute; left:-200px; top:22px;"><img id="imgView" src="" width="100" style="display:none;" onerror=\'if (this.value!="1"){this.value="1";this.src="'. $webPathPart .'inc_img/noPic.gif";}\' /></span></span>
				<input type="button" onclick=\'OT_OpenUpImg("input","img","info","&proType=info&proID='. $dataID .'")\' value="上传图片" />
				&ensp;<a href="javascript:void(0);" onclick="GetEditorImg();return false;" class="font1_2" style="text-decoration:underline;">从编辑器中获取</a>
				<div id="editorImgBox"></div>
			</td>
		</tr>
		';
		if ($userSysArr['US_isNewsVote'] == 99){
			$retStr .= '
			<tr>
				<td align="right">投票方式：</td>
				<td>
					<label><input type="radio" name="voteMode" value="0" '. Is::Checked($IF_voteMode,0) .' />关闭</label>&ensp;&ensp;
					<label><input type="radio" name="voteMode" value="1" '. Is::Checked($IF_voteMode,1) .' />心情</label>&ensp;&ensp;
					<label><input type="radio" name="voteMode" value="2" '. Is::Checked($IF_voteMode,2) .' />顶踩</label>&ensp;&ensp;
					<label><input type="radio" name="voteMode" value="11" '. Is::Checked($IF_voteMode,11) .' />百度喜欢按钮</label>&ensp;&ensp;
				</td>
			</tr>
			';
		}
		if ($userSysArr['US_isReply'] == 99){
			$retStr .= '
			<tr>
				<td align="right">评论区：</td>
				<td>
					<label><input type="radio" name="isReply" value="1" '. Is::Checked($IF_isReply,1) .' />显示</label>&ensp;&ensp;
					<label><input type="radio" name="isReply" value="0" '. Is::Checked($IF_isReply,0) .' />隐藏</label>&ensp;&ensp;
				</td>
			</tr>
			';
		}
		if ($userSysArr['US_topicID'] == -1){
			$retStr .= AppTopic::UcNewsTrBox1($IF_topicID);
		}
		if ($userSysArr['US_topAddiID'] == -1 || $userSysArr['US_addiID'] == -1){
			$topAddiOptionStr = $addiOptionStr = '';
			$addiexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='news' order by IW_rank ASC");
			while ($row = $addiexe->fetch()){
				$topAddiOptionStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IF_topAddiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
				$addiOptionStr .= '<option value="'. $row['IW_ID'] .'" '. Is::Selected($IF_addiID,$row['IW_ID']) .'>'. $row['IW_theme'] .'</option>';
			}
			unset($addiexe);

			if ($userSysArr['US_topAddiID'] == -1){
				$retStr .= '
				<tr>
					<td align="right">头附加内容：</td>
					<td>
						<select id="topAddiID" name="topAddiID">
						<option value="0">无</option>
						'. $topAddiOptionStr .'
						</select>
					</td>
				</tr>
				';
			}
			if ($userSysArr['US_addiID'] == -1){
				$retStr .= '
				<tr>
					<td align="right">尾附加内容：</td>
					<td>
						<select id="addiID" name="addiID">
						<option value="0">无</option>
						'. $addiOptionStr .'
						</select>
					</td>
				</tr>
				';
			}
		}
		if ($userSysArr['US_isCheckUser'] == 1){
			if ($userSysArr['US_isScore1'] == 1 && $infoScore1 > 0){ $score1Style = ''; }else{ $score1Style = 'display:none;'; }
			if ($userSysArr['US_isScore2'] == 1 && $infoScore2 > 0){ $score2Style = ''; }else{ $score2Style = 'display:none;'; }
			if ($userSysArr['US_isScore3'] == 1 && $infoScore3 > 0){ $score3Style = ''; }else{ $score3Style = 'display:none;'; }
			$retStr .= '
			<tr>
				<td align="right">付费阅读：</td>
				<td align="left">
					<label><input type="radio" id="isCheckUser1" name="isCheckUser" value="1" '. Is::Checked($IF_isCheckUser,1) .' onclick="CheckIsCheckUser()" />开启</label>&ensp;&ensp;
					<label><input type="radio" id="isCheckUser0" name="isCheckUser" value="0" '. Is::Checked($IF_isCheckUser,0) .' onclick="CheckIsCheckUser()" />关闭</label>&ensp;&ensp;
				</td>
			</tr>
			<tr>
				<td align="right"></td>
				<td align="left">
					<table id="checkUserBox" align="left" cellpadding="0" cellspacing="0" summary="" class="padd3" style="display:none;">
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td align="right" style="width:120px;"></td>
						<td align="center" style="width:55px;'. $score1Style .'">'. $userSysArr['US_score1Name'] .'</td>
						<td align="center" style="width:55px;'. $score2Style .'">'. $userSysArr['US_score2Name'] .'</td>
						<td align="center" style="width:55px;'. $score3Style .'">'. $userSysArr['US_score3Name'] .'</td>
						<td></td>
					</tr>
					<tr>
						<td align="right"></td>
						<td align="center" style="'. $score1Style .'">≤'. $infoScore1 .'</td>
						<td align="center" style="'. $score2Style .'">≤'. $infoScore2 .'</td>
						<td align="center" style="'. $score3Style .'">≤'. $infoScore3 .'</td>
						<td></td>
					</tr>
					<tr>
						<td align="right">限制阅读积分：</td>
						<td align="center" style="'. $score1Style .'"><input type="text" id="score1" name="score1" value="'. $IF_score1 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td align="center" style="'. $score2Style .'"><input type="text" id="score2" name="score2" value="'. $IF_score2 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td align="center" style="'. $score3Style .'"><input type="text" id="score3" name="score3" value="'. $IF_score3 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td class="font2_2">（用户积分达到才能阅读，不要求直接填0）</td>
					</tr>
					<tr>
						<td align="right">付费阅读积分：</td>
						<td align="center" style="'. $score1Style .'"><input type="text" id="cutScore1" name="cutScore1" value="'. $IF_cutScore1 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td align="center" style="'. $score2Style .'"><input type="text" id="cutScore2" name="cutScore2" value="'. $IF_cutScore2 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td align="center" style="'. $score3Style .'"><input type="text" id="cutScore3" name="cutScore3" value="'. $IF_cutScore3 .'" size="3" style="width:42px;" onkeyUp="this.value=FiltInt(this.value)" /></td>
						<td class="font2_2">（用户扣积分才能阅读）</td>
					</tr>
					'. AppNewsEnc::UcInfoTrBox1($IF_isEnc, $IF_encContent, $IF_addition) .'
					</table>
				</td>
			</tr>
			';
		}
		if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|news|')!==false){
			$retStr .= '
			<tr>
				<td align="right">验证码：</td>
				<td>'. Area::VerCode('dealForm') .'</td>
			</tr>
			';
		}
		$retStr .= '
		</table>
		<center style="padding-right:100px;">
			<input type="submit" value=" '. $mudiCN .' " class="btn subBtn" />
			<span id="loadingStr"></span>
		</center>
		</form>

		<script language="javascript" type="text/javascript">
		LoadEditor("content",300);
		CheckFile();
		CheckAddition();
		CheckIsCheckUser();
		try {
			$("#img").mouseover(function() {

				}).hover(function() { 
					$("#imgView").css({"display":""});
					$id("imgView").src=$id("infoFileDir").value + $id("img").value;
				}, function(){
					$("#imgView").css({"display":"none"});
			});

		}catch (e) {}
		</script>
		';

		return $retStr;
	}



	// 文章管理
	public static function Manage(){
		global $DB,$mudi,$userRow,$userSysArr,$webPathPart;

		if (! self::Jud()){
			return '<br /><br /><center>文章功能已关闭，有问题请联系管理员</center>';
		}

		$UE_ID		= $userRow['UE_ID'];

		$refTypeStr	= OT::GetRegExpStr('refTypeStr','sql+,');
	//	$refState	= OT::GetInt('refState',-1);
		$refTheme	= OT::GetRegExpStr('refTheme','sql');
		$refSource	= OT::GetRegExpStr('refSource','sql');
		$refWriter	= OT::GetRegExpStr('refWriter','sql');

		$SQLstr='select IF_ID,IF_theme,IF_time,IF_infoTypeDir,IF_datetimeDir,IF_readNum,IF_replyNum,IF_isAudit,IF_isGetScore,IF_auditNote from '. OT_dbPref .'info where IF_userID='. $UE_ID .'';

		if ($refTypeStr != ''){ $SQLstr .= " and IF_typeStr like '%". $DB->ForStr($refTypeStr,false) ."%'"; }
	//	if ($refState > -1){ $SQLstr .= " and IF_state=". $refState; }
		if ($refTheme != ''){ $SQLstr .= " and IF_theme like '%". $DB->ForStr($refTheme,false) ."%'"; }
		if ($refSource != ''){ $SQLstr .= " and IF_source like '%". $DB->ForStr($refSource,false) ."%'"; }
		if ($refWriter != ''){ $SQLstr .= " and IF_writer like '%". $DB->ForStr($refWriter,false) ."%'"; }

		$newsDelScoreArr = Area::UserScore('newsDel');

		$retStr = '
		<script language="javascript" type="text/javascript" src="js/usersNews.js?v='. OT_VERSION .'"></script>

		<input type="hidden" id="isScore1" name="isScore1" value="'. $userSysArr['US_isScore1'] .'" />
		<input type="hidden" id="isScore2" name="isScore2" value="'. $userSysArr['US_isScore2'] .'" />
		<input type="hidden" id="isScore3" name="isScore3" value="'. $userSysArr['US_isScore3'] .'" />
		<input type="hidden" id="score1Name" name="score1Name" value="'. $userSysArr['US_score1Name'] .'" />
		<input type="hidden" id="score2Name" name="score2Name" value="'. $userSysArr['US_score2Name'] .'" />
		<input type="hidden" id="score3Name" name="score3Name" value="'. $userSysArr['US_score3Name'] .'" />
		<input type="hidden" id="delScore1" name="delScore1" value="'. $newsDelScoreArr['US_score1'] .'" />
		<input type="hidden" id="delScore2" name="delScore2" value="'. $newsDelScoreArr['US_score2'] .'" />
		<input type="hidden" id="delScore3" name="delScore3" value="'. $newsDelScoreArr['US_score3'] .'" />

		<div style="padding:5px 5px 15px 5px;">
			<form id="refForm" name="refForm" method="get" action="" onsubmit="return CheckRefNewsForm()">
			<input type="hidden" name="mudi" value="'. $mudi .'" />
			栏目：<select id="refTypeStr" name="refTypeStr" style="width:150px;">
				<option value=""></option>
				';
				$typeNum = 0;
				$typeexe = $DB->query('select * from '. OT_dbPref .'infoType where IT_state=1 and IT_level=1 and IT_isUser=1 order by IT_rank ASC');
					while ($row = $typeexe->fetch()){
						$retStr .= '<option value=",'. $row['IT_ID'] .'," '. Is::InstrSelected($refTypeStr,','. $row['IT_ID'] .',') .'>'. $typeNum .'、'. $row['IT_theme'] .'</option>';

						$typeNum ++;
						$type2exe = $DB->query('select * from '. OT_dbPref .'infoType where IT_state=1 and IT_level=2 and IT_fatID='. $row['IT_ID'] .' and IT_isUser=1 order by IT_rank ASC');
							while ($row2 = $type2exe->fetch()){
								$retStr .= '<option value=",'. $row['IT_ID'] .','. $row2['IT_ID'] .'," '. Is::InstrSelected($refTypeStr,','. $row2['IT_ID'] .',') .'>&ensp;&ensp;&ensp;┣&ensp;'. $row2['IT_theme'] .'</option>';
							}
						$type2exe = null;
					}
				unset($typeexe);

				$retStr .= '
				</select>&ensp;&ensp;&ensp;
			标题：<input type="text" id="refTheme" name="refTheme" style="width:120px;" value="'. $refTheme .'" />&ensp;&ensp;&ensp;
			来源：<input type="text" id="refSource" name="refSource" style="width:70px;" value="'. $refSource .'" />&ensp;&ensp;&ensp;
			作者：<input type="text" id="refWriter" name="refWriter" style="width:70px;" value="'. $refWriter .'" />&ensp;&ensp;&ensp;
			<input type="submit" value="查 询" class="btn subBtn2" />&ensp;&ensp;
			<input type="button" value="重 置" onclick=\'document.location.href="?mudi=newsManage";\' class="btn defBtn2" />
			</form>
		</div>

		<table cellpadding="0" cellspacing="0" border="0" class="tabList1">
		<thead>
		<tr>
			<td width="6%" align="center">编号</td>
			<td width="44%" align="center">标题</td>
			<td width="7%" align="center">阅读量</td>
			<td width="7%" align="center">评论数</td>
			<td width="8%" align="center">状态</td>
			<td width="14%" align="center">发布时间</td>
			<td width="14%" align="center">操作</td>
		</tr>
		</thead>
		';
		$pageSize	= $userRow['UE_pageNum'];	// 每页条数
		$page		= OT::GetInt('page');
		$showRow=$DB->GetLimit($SQLstr .' order by IF_time DESC',$pageSize,$page);
		if (! $showRow){
			$retStr .= '</table><center class="font1_1 padd8">暂无内容</center>';
			return $retStr;
		}else{
			$recordCount=$DB->GetRowCount();
			$pageCount=ceil($recordCount/$pageSize);
			if ($page < 1 || $page > $pageCount){$page=1;}

			$retStr .= '<tbody class="tabBody">';
			$number=1+($page-1)*$pageSize;
			$rowCount = count($showRow);
			for ($i=0; $i<$rowCount; $i++){
				if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }
				$newsBtn = $auditNote = '';
				if ($showRow[$i]['IF_isAudit'] == 1){
					$auditCN = '<span style="color:green;">已审核</span>';
					$newsBtn = '<a style="margin-right:8px;" href="'. Url::NewsID($showRow[$i]['IF_infoTypeDir'],$showRow[$i]['IF_datetimeDir'],$showRow[$i]['IF_ID']) .'" target="_blank">查看</a>';
				}elseif ($showRow[$i]['IF_isAudit'] == 2){
					$auditCN = '<span style="color:red;">被拒绝</span>';
					$auditNote = '<div style="padding-top:3px;color:red;">被拒绝原因：'. $showRow[$i]['IF_auditNote'] .'</div>';
				}else{
					$auditCN = '<span style="color:#000;">待审核</span>';
				}
				$retStr .= '
				<tr id="data'. $showRow[$i]['IF_ID'] .'" '. $bgcolor .'>
					<td align="center">'. $number .'</td>
					<td align="left" style="word-break:break-all;padding:5px;">'. $showRow[$i]['IF_theme'] . $auditNote .'</td>
					<td align="center" >'. $showRow[$i]['IF_readNum'] .'</td>
					<td align="center" >'. $showRow[$i]['IF_replyNum'] .'</td>
					<td align="center">'. $auditCN .'</td>
					<td align="center">'. TimeDate::Get('datetime3',$showRow[$i]['IF_time']) .'</td>
					<td align="center">
						'. $newsBtn .
						'<a href="usersCenter.php?mudi=revNews&dataID='. $showRow[$i]['IF_ID'] .'">修改</a>'.
						'<a style="margin-left:8px;" href="javascript:void(0);" onclick="DelNews('. $showRow[$i]['IF_ID'] .','. $showRow[$i]['IF_isGetScore'] .');return false;" class="font2_1">删除</a>
					</td>
				</tr>
				';	// $webPathPart .'news/?'. $showRow[$i]['IF_ID'] .'.html&rnd=user'. $UE_ID
				$number ++;
			}
		}
		unset($showRow);
		$retStr .= '
		</tbody>
		</table>

		<table align="center" style="margin-top:2px;"><tr><td>
		'. Nav::Show('',$pageCount,$pageSize,$recordCount,'img','pageNum','get') .'
		</td></tr></table>
		';

		return $retStr;
	}

}
?>