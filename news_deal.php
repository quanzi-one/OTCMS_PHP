<?php
require(dirname(__FILE__) .'/check.php');

$infoSysArr = Cache::PhpFile('infoSys');


switch ($mudi){
	case 'vote':
		Vote();
		break;

	case 'message':
		$userSysArr = Cache::PhpFile('userSys');
		Message();
		break;

	case 'messageSend':
		MessageSend();
		break;

	case 'messageWrite':
		$webPathPart = '';
		MessageWrite();
		break;

	case 'contentSend':
		$userSysArr = Cache::PhpFile('userSys');
		ContentSend();
		break;

	default:
		die('err');
}

$DB->Close();





// 投票处理
function Vote(){
	global $DB,$infoSysArr;

	$dataID		= OT::GetInt('dataID');
	$selItem	= OT::GetInt('selItem',-1);
	$webPathPart= Area::WppSign(OT::GetStr('webPathPart'));

	$voteexe = $DB->query('select IF_voteMode,IF_voteStr from '. OT_dbPref .'info where IF_ID='. $dataID);
		if (! $row = $voteexe->fetch()){
			die('投票信息读取失败');
		}
		$IF_voteMode	= $row['IF_voteMode'];
		$IF_voteStr		= $row['IF_voteStr'];
	unset($voteexe);

	if ($selItem>-1){
		$voteNewTime = time();
		$voteOldTime = intval(@$_SESSION[OT_SiteID .'voteOldTime']);
		if ($voteNewTime-$voteOldTime < $infoSysArr['IS_newsVoteSecond']){
			JS::AlertEnd('连续投票需相隔'. $infoSysArr['IS_newsVoteSecond'] .'秒.');
		}else{
			$_SESSION[OT_SiteID .'voteOldTime'] = $voteNewTime;
		}
	}

	if ($IF_voteMode>0){
		$voteItemArr	= array(49,49,49,49,49,49,49,49);
		$voteItem2Arr	= array(0,0,0,0,0,0,0,0);
		$voteArr		= explode(',',$IF_voteStr);
		if ($selItem>-1){
			for ($i=0; $i<count($voteArr); $i++){
				if ($selItem == $i){
					$voteArr[$i] ++;
				}
			}
			$newVoteStr = implode(',',$voteArr);
			$DB->query("update ". OT_dbPref ."info set IF_voteStr='". $newVoteStr ."' where IF_ID=". $dataID);
			if (isset($_SESSION[OT_SiteID .'newsVoteList'])){
				$_SESSION[OT_SiteID .'newsVoteList'] .= '['. $dataID .']';
			}else{
				$_SESSION[OT_SiteID .'newsVoteList'] = '['. $dataID .']';
			}
		}
		$voteCount	= 0;
		for ($i=0; $i<count($voteArr); $i++){
			$voteCount += $voteArr[$i];
		}
		if ($voteCount>0){
			for ($i=0; $i<count($voteArr); $i++){
				if ($voteArr[$i]>0){
					$voteItem2Arr[$i] = OT::NumFormat($voteArr[$i]/$voteCount,2)*100;
					$voteItemArr[$i] = 49 - OT::NumFormat($voteArr[$i]/$voteCount,2)*49;
				}
			}
		}

		if ($IF_voteMode == 1){
			echo('
			请选择您看到这篇文章的心情：已有<span class="font2_2">'. $voteCount .'</span>人表态<br />
			<table cellpadding="0" cellspacing="0" align="center" style="margin:0 auto;"><tr><td>
			<ul>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(0)">
					<tr><td align="center">'. $voteArr[0] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[0] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/1.gif" /></td></tr>
					<tr><td align="center">支持</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(1)">
					<tr><td align="center">'. $voteArr[1] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[1] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/2.gif" /></td></tr>
					<tr><td align="center">感动</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(2)">
					<tr><td align="center">'. $voteArr[2] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[2] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/3.gif" /></td></tr>
					<tr><td align="center">惊讶</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(3)">
					<tr><td align="center">'. $voteArr[3] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[3] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/4.gif" /></td></tr>
					<tr><td align="center">同情</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(4)">
					<tr><td align="center">'. $voteArr[4] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[4] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/5.gif" /></td></tr>
					<tr><td align="center">流汗</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(5)">
					<tr><td align="center">'. $voteArr[5] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[5] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/6.gif" /></td></tr>
					<tr><td align="center">鄙视</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(6)">
					<tr><td align="center">'. $voteArr[6] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[6] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/7.gif" /></td></tr>
					<tr><td align="center">愤怒</td></tr>
					</table>
				</li>
				<li>
					<table cellpadding="0" cellspacing="0" onclick="VoteDeal(7)">
					<tr><td align="center">'. $voteArr[7] .'</td></tr>
					<tr><td align="center"><div class="boxBorder"><div class="boxBlank" style="height:'. $voteItemArr[7] .'px;"></div></div></td></tr>
					<tr><td align="center"><img src="'. $webPathPart .'inc_img/mood/8.gif" /></td></tr>
					<tr><td align="center">难过</td></tr>
					</table>
				</li>
			</ul><div class="clr"></div>
			</td></tr></table>
			');
		}elseif ($IF_voteMode == 2){
			echo('
			请选择您看到这篇文章的表态：已有<span class="font2_2">'. $voteCount .'</span>人参与<br /><br />
			<div class="upDown">
				<div class="up" onclick="VoteDeal(0)">
					<div class="upa">顶一下</div><div class="upb">(<span id="upVoteNum">'. $voteArr[0] .'</span>)</div><div class="clr"></div>
					<div class="upc"><div class="upd" style="width:'. $voteItem2Arr[0] .'px;"></div></div><div class="upe"><span id="upVotePer">'. $voteItem2Arr[0] .'</span>%</div>
				</div>
				<div class="down" onclick="VoteDeal(1)">
					<div class="downa">踩一下</div><div class="downb">(<span id="downVoteNum">'. $voteArr[1] .'</span>)</div><div class="clr"></div>
					<div class="downc"><div class="downd" style="width:'. $voteItem2Arr[1] .'px;"></div></div><div class="downe"><span id="downVotePer">'. $voteItem2Arr[1] .'</span>%</div>
				</div>
			</div><div class="clr"></div>
			');
		}
		echo('
		<script language="javascript" type="text/javascript">
		try {
			setTimeout("VoteStyle()",500);
		}catch (e) {}
		');
		if (strpos(@$_SESSION[OT_SiteID .'newsVoteList'],'['. $dataID .']') !== false){
			echo('
			try {
				isUseVote = true;
			}catch (e) {}
			');
		}
		echo('
		</script>
		');
	}
	
}



// 评论处理
function Message(){
	global $DB,$systemArr,$userSysArr,$tplSysArr,$infoSysArr;

	$backURL	= Str::Filter(OT::PostStr('backURL'),'url');
	$infoID		= OT::PostInt('infoID');
	$isReply	= OT::PostInt('isReply');
	$replyUserID= OT::PostInt('replyUserID');
	$verCode	= strtolower(OT::PostStr('verCode'));
	$rndMd5		= OT::PostStr('rndMd5');

	if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|newsReply|')!==false){
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				die('alert("验证码错误，请重新验证.");ResetVerCode();');
			}
		}else{
			if ($verCode=='' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				die('alert("验证码错误.");ResetVerCode();');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']]='';
		}
	}

	$replyNewTime = time();
	$replyOldTime = intval(@$_SESSION[OT_SiteID .'replyOldTime']);
	if ($replyNewTime-$replyOldTime < $infoSysArr['IS_newsReplySecond']){
		die('alert("连续评论需相隔'. $infoSysArr['IS_newsReplySecond'] .'秒.");ResetVerCode();');
	}else{
		$_SESSION[OT_SiteID .'replyOldTime'] = $replyNewTime;
	}

	$username = '';
	if ($infoSysArr['IS_isNewsReply']==0 || $isReply==0){
		die('alert("评论已关闭.");');
	}elseif ($infoSysArr['IS_isNewsReply']==10 || $isReply==10){
		$username = Users::Username();
		if ($username == ''){
			die('alert("需要会员身份才能发表评论.");');
		}

		$userRow = Users::Open('get',',UE_username,UE_state,UE_authStr','',$judUserErr);
		if ($judUserErr != ''){
			die('alert("需要会员身份才能发表评论.");');
		}
		if ($userRow['UE_state'] == 0){
			die('alert("您的状态未审核，暂时无法发表评论，请联系管理员.");');
		}
		// 检测用户邮箱、手机号是否需要强制验证
		AreaApp::UserTixing($userRow['UE_authStr'], $userSysArr, 1, 'str');
	}

	if ($username == ''){
		if (strpos($tplSysArr['TS_messageEvent'],'|noRevName|') !== false){
			$username = '游客';
		}else{
			$username = OT::PostRegExpStr('replyUser','sql');
		}
	}
	$content	= OT::PostReplaceStr('replyContent','html');
	
	if ($infoID==0 || $content==''){
		die('alert("表单信息填写不完整.");ResetVerCode();');
	}

	$contentLen = mb_strlen($content);
	if ($infoSysArr['IS_newsReplyMaxLen']>0 && $contentLen>$infoSysArr['IS_newsReplyMaxLen']){
		JS::AlertEnd('评论内容(已经'. $contentLen .'字符)超过系统限制的'. $infoSysArr['IS_newsReplyMaxLen'] .'字符.');
	}

	$userIP	= Users::GetIp();
	$resultMd5 = md5(md5(OT_SiteID . session_id()) . OT_SiteID . $userIP);
	if ($tplSysArr['TS_messageRndMd5'] > 0){
		if ($rndMd5 != $resultMd5){
			if ($tplSysArr['TS_messageRndMd5'] == 2){
				die('alert("用户跟随信息验证失败，已重新更新，请再点提交下.");$id("rndMd5").value = "'. $resultMd5 .'";ResetVerCode();');
			}else{
				die('alert("用户跟随信息验证失败，请重新刷新页面后再操作.");ResetVerCode();');
			}
		}
	}

	$badWordArr = explode('|',$tplSysArr['TS_messageBadWord']);
	foreach ($badWordArr as $str){
		if ($str != ''){
			$alertStr='内容中含禁止关键词（'. $str .'）';
			if (strpos($content,$str) !== false){
				die('alert("'. $alertStr .'");');
			}
		}
	}

	$userID		= Users::UserID();
	if ($userID>0){ $username = Users::Username(); }
	$ipInfoArr = OT::GetIpInfoArr($userIP, OT::IpHidden($userIP));

	$addiContent = '';
	if ($replyUserID > 0){
		$row = $DB->GetRow('select IM_ID,IM_time,IM_ipCN,IM_num,IM_userID,IM_username,IM_content,IM_addiContent from '. OT_dbPref .'infoMessage where IM_ID='. $replyUserID .' and IM_infoID='. $infoID);
		if ($row){
			$addiContent .= $row['IM_ID'] .'[|]'. $row['IM_userID'] .'[|]'. $row['IM_username'] .'[|]'. $row['IM_ipCN'] .'[|]'. $row['IM_content'] .'[|]'. $row['IM_time'] .'[|]'. $row['IM_num'];
			if (strlen($row['IM_addiContent']) > 10){
				$addiContent .= '[arr]'. $row['IM_addiContent'];
			}
		}
	}

	$record = array();
	$record['IM_type']			= 'web';
	$record['IM_infoID']		= $infoID;
	$record['IM_time']			= TimeDate::Get();
	$record['IM_ip']			= $userIP;
	$record['IM_ipCN']			= $ipInfoArr['address'];
	$record['IM_num']			= intval($DB->GetOne('select max(IM_num) from '. OT_dbPref .'infoMessage where IM_infoID='. $infoID .''))+1;
	$record['IM_userID']		= $userID;
	$record['IM_username']		= $username;
	$record['IM_content']		= $content;
	$record['IM_contentMd5']	= md5($content);
	$record['IM_addiContent']	= $addiContent;
	$record['IM_state']			= $infoSysArr['IS_newsReplyAudit'];

	$judResult = $DB->InsertParam('infoMessage',$record);
		if ($judResult){
			$alertResult = '成功';
			$DB->query('update '. OT_dbPref .'info set IF_replyNum=IF_replyNum+1 where IF_ID='. $infoID);
			if ($infoSysArr['IS_newsReplyAudit'] == 0){
				echo('alert("提交成功,待管理员审核通过后才会显示出来.");$id("replyForm").reset();ResetVerCode();');
			}else{
				echo('alert("提交成功.");$id("replyForm").reset();ResetVerCode();LoadReplyList('. $infoID .');');
			}
		}else{
			$alertResult = '失败';
			echo('alert("提交失败,请刷新页面后再重新提交.");$id("replyForm").reset();ResetVerCode();');
		}
	
}



// 发送评论
function MessageSend(){
	global $DB,$systemArr,$infoSysArr,$tplSysArr;

	$dataID			= OT::GetInt('dataID');
	$webPathPart	= Area::WppSign(OT::GetStr('webPathPart'));

	$pageSize	= $infoSysArr['IS_newsReplyNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select IM_ID,IM_time,IM_num,IM_userID,IM_username,IM_ipCN,IM_content,IM_addiContent,IM_isReply,IM_reply,IM_vote1,IM_vote2 from '. OT_dbPref .'infoMessage where IM_infoID='. $dataID .' and IM_state=1 order by IM_time DESC',$pageSize,$page);
		if (! $showRow){
			echo('<br /><center class="font1_1">暂无评论</center>');
		}else{
			$recordCount=$DB->GetRowCount();
			$pageCount=ceil($recordCount/$pageSize);
			if ($page < 1 || $page > $pageCount){$page=1;}

			$number=1+($page-1)*$pageSize;
			$rowCount = count($showRow);

			echo('<ul>');
			for ($i=0; $i<$rowCount; $i++){
				if ($showRow[$i]['IM_userID']>0){ $replyName='<span style="color:red;">会员：</span>'; }else{ $replyName='评论者：'; }
				if (strlen($showRow[$i]['IM_ipCN'])>0 && strpos($tplSysArr['TS_messageEvent'],'|IP|')!==false){
					$ipCN = '&ensp;<span class="font1_2d">['. $showRow[$i]['IM_ipCN'] .']</span>';
				}else{
					$ipCN = '';
				}
				$addiContent = Area::GenTie($showRow[$i]['IM_ID'], $showRow[$i]['IM_addiContent'], $tplSysArr['TS_messageEvent'], 3, $webPathPart);
				echo('
				<li>
					<div class="username">
						<div class="right font1_2d">
							<span class="font2_2" style="cursor:pointer;" onclick=\'ReplyUser("'. $showRow[$i]['IM_ID'] .'","'. $showRow[$i]['IM_num'] .'楼 '. $showRow[$i]['IM_username'] .'");\'>【回复】</span>&ensp;&ensp;
							<span style="color:#5ed317;cursor:pointer;" onclick="UserVote(\'news\','. $showRow[$i]['IM_ID'] .',1);"><img src="'. $webPathPart .'inc_img/userDing.gif" align="bottom" valign="top" alt="顶" title="顶" style="margin-right:2px;" /><span id="vote1_'. $showRow[$i]['IM_ID'] .'">'. $showRow[$i]['IM_vote1'] .'</span></span>&ensp;&ensp;
							<span style="color:#ff8056;cursor:pointer;" onclick="UserVote(\'news\','. $showRow[$i]['IM_ID'] .',2);"><img src="'. $webPathPart .'inc_img/userCai.gif" align="bottom" valign="top" alt="踩" title="踩" style="margin-right:2px;" /><span id="vote2_'. $showRow[$i]['IM_ID'] .'">'. $showRow[$i]['IM_vote2'] .'</span></span>&ensp;&ensp;
							'. TimeDate::Get('Y-m-d H:i',$showRow[$i]['IM_time']) .'
						</div>
						<img src="'. $webPathPart .'inc_img/user.gif" /><b>'. $showRow[$i]['IM_num'] .'楼</b>&ensp;'. $replyName .''. $showRow[$i]['IM_username'] . $ipCN .'
					</div>
					<div class="note">'. Area::FaceSignToImg(Area::MessageEventDeal($showRow[$i]['IM_content'],$tplSysArr['TS_messageEvent']),$webPathPart) .'</div>
					'. $addiContent .'
					');
					if ($showRow[$i]['IM_isReply'] == 1){
						echo('<div class="admin"><b>'. $infoSysArr['IS_newsReplyName'] .'：</b>'. Area::FaceSignToImg($showRow[$i]['IM_reply'],$webPathPart) .'</div>');
					}
				echo('
				</li><div class="clr"></div>
				');
			}

			echo('
			</ul><div class="clr"></div>

			<table cellpadding="0" cellspacing="0" align="center" class="listNavBox" style="margin:0 auto; margin-top:8px;"><tr><td>
			'. Nav::Ajax('replyList',$pageCount,$pageSize,$recordCount) .'
			</td></tr></center>
			');
		}
	unset($showRow);
}



// 发送评论填写框
function MessageWrite(){
	global $DB,$systemArr,$infoSysArr,$tplSysArr,$webPathPart;

	$dataID			= OT::GetInt('dataID');
	$isReply		= OT::GetInt('isReply');
	$webPathPart	= Area::WppSign(OT::GetStr('webPathPart'));

	$infoexe=$DB->query('select IF_ID,IF_isReply from '. OT_dbPref .'info where IF_ID='. $dataID);
		if ($row = $infoexe->fetch()){
			$IF_isReply		= $row['IF_isReply'];
		}else{
			$IF_isReply		= $isReply;
		}
	unset($infoexe);
	
	$username = Users::Username();
	if (($infoSysArr['IS_isNewsReply']==10 || $IF_isReply==10) && $username==''){
		echo('
		<center style="font-size:14px;margin:30px 0 50px 0;">
			发表评论，请先 <a class="font2_1" href="'. $webPathPart .'users.php?mudi=login&force=1&isBack=1">[登录]</a> / <a class="font2_1" href="'. $webPathPart .'users.php?mudi=reg&force=1&isBack=1">[注册]</a>
		</center>
		');
	}else{
		$userIP	= Users::GetIp();
		if ($username == ''){ $replyName = '游客'; }else{ $replyName = $username; }
		$rndMd5 = md5(md5(OT_SiteID . session_id()) . OT_SiteID . $userIP);

		echo('
		<form id="replyForm" name="replyForm" method="post" action="'. $webPathPart .'news_deal.php?mudi=message" onsubmit="return CheckReplyForm();">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<input type="hidden" id="rndMd5" name="rndMd5" value="'. $rndMd5 .'" />
		<input type="hidden" name="infoID" value="'. $dataID .'" />
		<input type="hidden" name="isReply" value="'. $IF_isReply .'" />
		<input type="hidden" id="replyUserID" name="replyUserID" value="0" />
		<input type="hidden" id="replyContentMaxLen" name="replyContentMaxLen" value="'. $infoSysArr['IS_newsReplyMaxLen'] .'" />
		<table id="replyTable" align="center" style="margin:0 auto;">
		<tr>
			<td align="left">
				<a id="replyArea" name="replyArea"></a>
				<span style="font-weight:bold;" class="font2_2 pointer" onclick=\'FaceShow("faceInitBox","replyContent");\'>[表情]</span>
				&ensp;&ensp;<span id="replyUserStr" style="color:blue;font-weight:bold;"></span>
				<div id="faceInitBox" style="width:98%;display:none;overflow:hidden;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<textarea id="replyContent" name="replyContent"></textarea>
			</td>
		</tr>
		<tr>
			<td align="left">
				<div class="right"><span id="conMaxLenBox" class="font2_2"></span><input type="submit" value="" class="replyBtn button" /></div>
				<table cellpadding="0" cellspacing="0"><tr><td id="replyUserBox">
				');
				if (Users::UserID()>0 && strlen($username)>0){
					echo('会员：'. $username .'<input type="hidden" id="replyUser" name="replyUser" value="'. $username .'" maxlength="25" />');
				}else{
					if (strpos($tplSysArr['TS_messageEvent'],'|noRevName|') !== false){
						echo('评论者：'. $replyName .'<input type="hidden" id="replyUser" name="replyUser" value="'. $replyName .'" />');
					}else{
						echo('评论者：<input type="text" id="replyUser" name="replyUser" value="'. $replyName .'" style="width:90px;" maxlength="25" />');
					}
				}
				echo('&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;');
				if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|newsReply|')!==false){
					echo('验证码：</td><td>'. Area::VerCode('replyForm','verCode','100px') .'');
				}
				echo('
				</td></tr></table>
			</td>
		</tr>
		</table>
		</form>
		');
	}
}



// 发送文章内容
function ContentSend(){
	global $DB,$infoSysArr,$userSysArr;

	$dataID			= OT::GetInt('dataID');
	$page			= OT::GetInt('page');
	$isCut			= OT::GetStr('isCut');
	$webPathPart	= Area::WppSign(OT::GetStr('webPathPart'));
	$outputID		= 'newsContent';

	$infoexe = $DB->query('select IF_theme,IF_tabID,IF_content,IF_pageNum,IF_userID,IF_isCheckUser,IF_userGroupList,IF_userLevel,IF_score1,IF_score2,IF_score3,IF_cutScore1,IF_cutScore2,IF_cutScore3,IF_scoreUserList,IF_file,IF_fileName,IF_fileStr,IF_state,IF_infoTypeDir,IF_datetimeDir,IF_isEnc,IF_encContent,IF_mediaFile,IF_addition from '. OT_dbPref .'info where IF_ID='. $dataID);
		if (! $row = $infoexe->fetch()){
			die('<br /><center>记录不存在</center>');
		}
		if ($row['IF_state'] == 0){
			die('<br /><center>该记录已关闭</center>');
		}

		$IF_theme			= $row['IF_theme'];
		if ($row['IF_tabID'] > 0){
			$IF_content		= Area::GetTabContent($row['IF_tabID'], $dataID);
		}else{
			$IF_content		= $row['IF_content'];
		}
		$IF_pageNum			= $row['IF_pageNum'];
		$IF_userID			= $row['IF_userID'];
		$IF_isCheckUser		= $row['IF_isCheckUser'];
		$IF_userGroupList	= $row['IF_userGroupList'];
		$IF_userLevel		= $row['IF_userLevel'];
		$IF_score1			= $row['IF_score1'];
		$IF_score2			= $row['IF_score2'];
		$IF_score3			= $row['IF_score3'];
		$IF_cutScore1		= $row['IF_cutScore1'];
		$IF_cutScore2		= $row['IF_cutScore2'];
		$IF_cutScore3		= $row['IF_cutScore3'];
		$IF_scoreUserList	= $row['IF_scoreUserList'];
		$IF_file			= $row['IF_file'];
		$IF_fileName		= $row['IF_fileName'];
		$IF_fileStr			= $row['IF_fileStr'];
		$IF_infoTypeDir		= $row['IF_infoTypeDir'];
		$IF_datetimeDir		= $row['IF_datetimeDir'];
		$IF_isEnc			= $row['IF_isEnc'];
		$IF_encContent		= $row['IF_encContent'];
		$IF_mediaFile		= $row['IF_mediaFile'];
		$IF_addition		= $row['IF_addition'];
	unset($infoexe);


	if ($IF_isCheckUser > 0){

		if ($IF_isCheckUser == 2 && AppNewsVerCode::Jud()){
			AppNewsVerCode::NewsShow($webPathPart);
		}else{
			$judRead = 1;
			$readStr = '';
			$userReadStr = '';
			$judCut = 1;
			$cutStr = '';
			$userCutStr = '';
			$cutUserSql = '';
			if ($IF_score1 > 0){
				$readStr .= '&ensp;&ensp;'. $userSysArr['US_score1Name'] .'>='. $IF_score1;
			}
			if ($userSysArr['US_isScore2'] == 1 && $IF_score2 > 0){
				$readStr .= '&ensp;&ensp;'. $userSysArr['US_score2Name'] .'>='. $IF_score2;
			}
			if ($userSysArr['US_isScore3'] == 1 && $IF_score3 > 0){
				$readStr .= '&ensp;&ensp;'. $userSysArr['US_score3Name'] .'>='. $IF_score3;
			}
			if ($IF_cutScore1 > 0){
				$cutStr .= '&ensp;&ensp;'.  $userSysArr['US_score1Name'] .':'. $IF_cutScore1;
			}
			if ($userSysArr['US_isScore2'] == 1 && $IF_cutScore2 > 0){
				$cutStr .= '&ensp;&ensp;'. $userSysArr['US_score2Name'] .':'. $IF_cutScore2;
			}
			if ($userSysArr['US_isScore3'] == 1 && $IF_cutScore3 > 0){
				$cutStr .= '&ensp;&ensp;'. $userSysArr['US_score3Name'] .':'. $IF_cutScore3;
			}
			if (strlen($readStr) > 0){ $readStr = '<br />需要会员'. $readStr; }

			$userRow = Users::Open('get',',UE_username,UE_score1,UE_score2,UE_score3,UE_state,UE_groupID,UE_isGroupTime,UE_groupTime','',$judUserErr);
				if ((! $userRow) || $judUserErr != ''){
					die('
					<br />
					<div class="hiddenContent">
					<center style="font-size:14px;">
						需要登录后才能阅读.
						'. $judUserErr .' <a class="font2_1" href="'. $webPathPart .'users.php?mudi=login&force=1&isBack=1">[登录]</a> / <a class="font2_1" href="'. $webPathPart .'users.php?mudi=reg&force=1&isBack=1">[注册]</a> '. $readStr .'
					</center>
					</div>
					');
				}
				if ($userRow['UE_state'] == 0){
					die('
					<br />
					<div class="hiddenContent">
					<center style="font-size:14px;">
						您当前状态 未审核，审核通过后才可阅读。'. $readStr .'
					</center>
					</div>
					');
				}
				$groupID = UserGroup::CurrId($userRow['UE_groupID'], $userRow['UE_isGroupTime'], $userRow['UE_groupTime']);
				if (strlen($IF_userGroupList) > 1 && strpos($IF_userGroupList,'['. $groupID .']') === false){
					$alertStr = '';
					if (AppUserGroup::Jud()){
						$alertStr .= '您可以 ';
						if (AppMoneyAlipay::Jud() || AppMoneyWeixin::Jud()){
							$alertStr .= '<a href="'. $webPathPart .'usersCenter.php?mudi=onlinePay&isBack=1" target="_blank" style="font-weight:bold;text-decoration:underline;color:red;">在线充值</a> 然后 ';
						}
						$alertStr .= '<a href="'. $webPathPart .'usersCenter.php?mudi=userAndGroup" target="_blank" style="font-weight:bold;text-decoration:underline;color:red;">会员组开通/更换</a> 更高级用户组';
					}
					die('
					<br />
					<div class="hiddenContent">
					<center style="font-size:14px;">
						您当前所在的用户组【'. UserGroup::CurrName($groupID) .'】无权限阅读。'. $readStr .'
						<div style="color:#000;">'. $alertStr .'</div>
					</center>
					</div>
					');
				}

			$UE_ID			= $userRow['UE_ID'];
			$UE_username	= $userRow['UE_username'];
			$UE_score1		= $userRow['UE_score1'];
			$UE_score2		= $userRow['UE_score2'];
			$UE_score3		= $userRow['UE_score3'];

			
	//		if ($userSysArr['US_isScore1'] == 1){
				if ($UE_score1 < $IF_score1){
					$userReadStr .= '&ensp;&ensp;'. $userSysArr['US_score1Name'] .'：'. $UE_score1 .'';
					$judRead = 0;
				}
				if ($UE_score1 < $IF_cutScore1){
					$userCutStr .= '&ensp;&ensp;'. $userSysArr['US_score1Name'] .'：'. $UE_score1 .'';
					$judCut = 0;
				}
				$cutUserSql .= 'update '. OT_dbPref .'users set UE_score1=UE_score1-'. $IF_cutScore1 .'';
	//		}
			if ($userSysArr['US_isScore2'] == 1){
				if ($UE_score2 < $IF_score2){
					$userReadStr .= '&ensp;&ensp;'. $userSysArr['US_score2Name'] .'：'. $UE_score2 .'';
					$judRead = 0;
				}
				if ($UE_score2 < $IF_cutScore2){
					$userCutStr .= '&ensp;&ensp;'. $userSysArr['US_score2Name'] .'：'. $UE_score2 .'';
					$judCut = 0;
				}
				$cutUserSql .= ',UE_score2=UE_score2-'. $IF_cutScore2 .'';
			}
			if ($userSysArr['US_isScore3'] == 1){
				if ($UE_score3 < $IF_score3){
					$userReadStr .= '&ensp;&ensp;'. $userSysArr['US_score3Name'] .'：'. $UE_score3 .'';
					$judRead = 0;
				}
				if ($UE_score3 < $IF_cutScore3){
					$userCutStr .= '&ensp;&ensp;'. $userSysArr['US_score3Name'] .'：'. $UE_score3 .'';
					$judCut = 0;
				}
				$cutUserSql .= ',UE_score3=UE_score3-'. $IF_cutScore3 .'';
			}
			if (AppMoneyAlipay::Jud() || AppMoneyWeixin::Jud()){
				$payAlert = '，请先<a href="'. $webPathPart .'usersCenter.php?mudi=onlinePay&isBack=1" target="_blank" style="font-weight:bold;text-decoration:underline;color:red;">在线充值</a>';
			}else{
				$payAlert = '';
			}
			if (strlen($userReadStr)>0){ $userReadStr = '<div style="color:#000;">当前会员'. $userReadStr . $payAlert .'</div>'; }
			if (strlen($userCutStr)>0){ $userCutStr = '<div style="color:#000;">当前会员'. $userCutStr . $payAlert .'</div>'; }
			if ($judRead == 0){
				die('
				<br />
				<div class="hiddenContent">
				<table align="center" style="margin:0 auto;"><tr><td class="font2_1">阅读权限不足。'. $readStr . $userReadStr .'</td></tr></table>
				</div>
				');
			}

			if (strpos($IF_scoreUserList,'['. $UE_ID .']') !== false){ $judCut=2; }

			if ($isCut == 'true'){
				if ($judCut == 0){
					die('
					<br />
					<div class="hiddenContent">
					<table align="center" style="margin:0 auto;"><tr><td class="font2_1">积分不足，无法阅读。<br />阅读需扣积分'. $cutStr . $userCutStr .'</td></tr></table>
					</div>
					');
				}elseif ($judCut == 1){
					$cutUserSql .= ' where UE_ID='. $UE_ID;
					$DB->query($cutUserSql);
					$DB->query('update '. OT_dbPref .'info set IF_scoreUserList='. $DB->ForStr($IF_scoreUserList .'['. $UE_ID .']') .' where IF_ID='. $dataID);
					$judCut=2;

					if (AppUserScore::IsAdd($IF_cutScore1, $IF_cutScore2, $IF_cutScore3)){
						$scoreArr = array();
						$scoreArr['UM_userID']		= $UE_ID;
						$scoreArr['UM_username']	= $UE_username;
						$scoreArr['UM_type']		= 'read';
						$scoreArr['UM_dataID']		= $dataID;
						$scoreArr['UM_score1']		= $IF_cutScore1*(-1);
						$scoreArr['UM_score2']		= $IF_cutScore2*(-1);
						$scoreArr['UM_score3']		= $IF_cutScore3*(-1);
						$scoreArr['UM_remScore1']	= $UE_score1 - $IF_cutScore1;
						$scoreArr['UM_remScore2']	= $UE_score2 - $IF_cutScore2;
						$scoreArr['UM_remScore3']	= $UE_score3 - $IF_cutScore3;
						$scoreArr['UM_note']		= '阅读收费文章“'. $IF_theme .'”';
						AppUserScore::AddData($scoreArr);
					}
					AppNewsGain::AddGainScore($dataID, $IF_theme, $UE_username, $IF_userID, $IF_cutScore1, $IF_cutScore2, $IF_cutScore3);

				}
			}

			if ($judCut == 0){
				die('
				<br />
				<div class="hiddenContent">
				<table align="center" style="margin:0 auto;"><tr><td class="font2_1">阅读需扣积分'. $cutStr . $userCutStr .'</td></tr></table>
				</div>
				');
			}elseif ($judCut==1 && ($IF_cutScore1!=0 || $IF_cutScore2!=0 || $IF_cutScore3!=0)){
				die('
				<br />
				<div class="hiddenContent">
				<table align="center" style="margin:0 auto;"><tr><td class="font2_1">阅读需扣积分'. $cutStr . $userCutStr .'<br />您确定阅读吗？<input type="button" value="确定阅读" onclick="CutScoreBtn()" /></td></tr></table>
				</div>
				');
			}
		}

		if ($IF_isEnc == 1){
			$content = Content::CloseTags(Area::AddImgAlt($IF_encContent,$IF_theme));
			if ($webPathPart!='../'){ $content = str_replace(InfoImgAdminDir,$webPathPart . InfoImgDir,$content); }

			if ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encMediaFile|')!==false){
				if ($page < 2){
					$mediaCode = AppVideo::GetMediaCode($IF_mediaFile, $webPathPart, 'ajax');
					if (strpos($IF_addition,'|topMediaFile|')!==false){
						$content = $mediaCode . $content;
					}else{
						$content .= $mediaCode;
					}
				}
			}
			if ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encFile|')!==false){
				$fileCode = Area::InfoFile($dataID, $IF_file, $IF_fileName, $IF_fileStr, $infoSysArr['IS_fileStyle'], $webPathPart);
				$content .= $fileCode;
			}

			echo($content);

		}else{
			if ($IF_pageNum>0){
				$content	= Content::PageNum($IF_content,$IF_infoTypeDir,$IF_datetimeDir,$dataID,$IF_pageNum,$outputID,$webPathPart);
			}else{
				$content	= Content::PageSign($IF_content,$IF_infoTypeDir,$IF_datetimeDir,$dataID,$outputID,$webPathPart);
			}
			$content = Content::CloseTags(Area::AddImgAlt($content,$IF_theme));
			if ($webPathPart!='../'){ $content = str_replace(InfoImgAdminDir,$webPathPart . InfoImgDir,$content); }

			$wordexe = $DB->query('select * from '. OT_dbPref .'keyWord where KW_isUse=1 order by KW_rank ASC');
				while ($row = $wordexe->fetch()){
					if ($row['KW_useNum']>0){
						$content = Str::ReplaceSkipMark($content,$row['KW_theme'],'<a href="'. $row['KW_URL'] .'" class="keyWord" target="_blank"><strong>'. $row['KW_theme'] .'</strong></a>',$row['KW_useNum']);
					}else{
						$content = Str::ReplaceSkipMark($content,$row['KW_theme'],'<a href="'. $row['KW_URL'] .'" class="keyWord" target="_blank"><strong>'. $row['KW_theme'] .'</strong></a>',-1);
					}
				}
			unset($wordexe);

			if (! ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encMediaFile|')!==false)){
				if ($page < 2){
					$mediaCode = AppVideo::GetMediaCode($IF_mediaFile, $webPathPart, 'ajax');
					if (strpos($IF_addition,'|topMediaFile|')!==false){
						$content = $mediaCode . $content;
					}else{
						$content .= $mediaCode;
					}
				}
			}
			if (! ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encFile|')!==false)){
				$fileCode = Area::InfoFile($dataID, $IF_file, $IF_fileName, $IF_fileStr, $infoSysArr['IS_fileStyle'],  $webPathPart);
				$content .= $fileCode;
			}

			echo($content);
		}

	}

}
?>