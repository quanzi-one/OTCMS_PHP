<?php
require(dirname(__FILE__) .'/check.php');


$webPathPart = '';
switch ($mudi){
	case 'message':
		Area::CheckIsOutSubmit('alertStr');	// 检测是否外部提交
		$userSysArr = Cache::PhpFile('userSys');
		message();
		break;

	case 'messageSend':
		MessageSend();
		break;

	case 'messageWrite':
		MessageWrite();
		break;

	case 'userVote':
		UserVote();
		break;

	case 'download':
		$userSysArr = Cache::PhpFile('userSys');
		download();
		break;

	case 'desktopUrl':
		DesktopUrl();
		break;

	default:
		die('err');
}

$DB->Close();





// 留言处理
// POST中messageType必填
function message(){
	global $DB,$systemArr,$userSysArr,$tplSysArr;

	$backURL	= Str::Filter(OT::PostStr('backURL'),'url');
	$dataType	= OT::PostRegExpStr('dataType','sql');
	$replyUserID= OT::PostInt('replyUserID');
	$verCode	= strtolower(OT::PostStr('verCode'));
	$rndMd5		= OT::PostStr('rndMd5');

	if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|message|')!==false){
		if ($systemArr['SYS_verCodeMode'] == 20){
			$geetest = new Geetest();
			if (! $geetest->IsTrue('web')){
				die('alert("验证码错误，请重新点击验证.");ResetVerCode();');
			}
		}else{
			if ($verCode=='' || $verCode != strtolower($_SESSION['VerCode'. $systemArr['SYS_verCodeMode']])){
				die('alert("验证码错误.");ResetVerCode();');
			}
			$_SESSION['VerCode'. $systemArr['SYS_verCodeMode']] = '';
		}
	}

	$messageNewTime = time();
	$messageOldTime = intval(@$_SESSION[OT_SiteID .'messageOldTime']);
	if ($messageNewTime-$messageOldTime < $tplSysArr['TS_messageSecond']){
		die('alert("连续留言需相隔'. $tplSysArr['TS_messageSecond'] .'秒.");');
	}else{
		$_SESSION[OT_SiteID .'messageOldTime'] = $messageNewTime;
	}

	$username = '';
	if ($tplSysArr['TS_isMessage'] == 0){
		die('alert("留言已关闭.");');
	}elseif ($tplSysArr['TS_isMessage'] == 10){
		$username = Users::Username();
		if ($username == ''){
			die('alert("需要会员身份才能留言.");');
		}

		$userRow = Users::Open('get',',UE_username,UE_state,UE_authStr','',$judUserErr);
		if ($judUserErr != ''){
			die('alert("需要会员身份才能留言.");');
		}
		if ($userRow['UE_state'] == 0){
			die('alert("您的状态还是未审核，暂时无法留言，请联系管理员.");');
		}
		// 检测用户邮箱、手机号是否需要强制验证
		AreaApp::UserTixing($userRow['UE_authStr'], $userSysArr, 1, 'str');
	}

	if ($username == ''){
		if (strpos($tplSysArr['TS_messageEvent'],'|noRevName|') !== false){
			$username = '游客';
		}else{
			$username = OT::PostRegExpStr('messUser','sql');
		}
	}
	$content	= OT::PostReplaceStr('messContent','html');
	
	if ($dataType=='' || $content==''){
		die('alert("表单信息填写不完整.");');
	}

	$contentLen = mb_strlen($content);
	if ($tplSysArr['TS_messageMaxLen']>0 && $contentLen>$tplSysArr['TS_messageMaxLen']){
		JS::AlertEnd('留言内容(已经'. $contentLen .'字符)超过系统限制的'. $tplSysArr['TS_messageMaxLen'] .'字符.');
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
		$row = $DB->GetRow('select MA_ID,MA_time,MA_ipCN,MA_num,MA_userID,MA_username,MA_content,MA_addiContent from '. OT_dbPref .'message where MA_ID='. $replyUserID);
		if ($row){
			$addiContent .= $row['MA_ID'] .'[|]'. $row['MA_userID'] .'[|]'. $row['MA_username'] .'[|]'. $row['MA_ipCN'] .'[|]'. $row['MA_content'] .'[|]'. $row['MA_time'] .'[|]'. $row['MA_num'];
			if (strlen($row['MA_addiContent']) > 10){
				$addiContent .= '[arr]'. $row['MA_addiContent'];
			}
		}
	}

	$record = array();
	$record['MA_type']			= 'web';
	$record['MA_time']			= TimeDate::Get();
	$record['MA_ip']			= $userIP;
	$record['MA_ipCN']			= $ipInfoArr['address'];
	$record['MA_num']			= intval($DB->GetOne('select max(MA_num) from '. OT_dbPref .'message'))+1;
	$record['MA_userID']		= $userID;
	$record['MA_username']		= $username;
	$record['MA_content']		= $content;
	$record['MA_contentMd5']	= md5($content);
	$record['MA_addiContent']	= $addiContent;
	$record['MA_state']			= $tplSysArr['TS_messageAudit'];

	$judResult = $DB->InsertParam('message',$record);
		if ($judResult){
			$alertResult = '成功';
			if ($tplSysArr['TS_messageAudit'] == 0){
				echo('alert("提交成功,待管理员审核通过后才会显示出来.");$id("messageForm").reset();ResetVerCode();');
			}else{
				echo('alert("提交成功.");$id("messageForm").reset();ResetVerCode();LoadMessageList();');
			}
		}else{
			$alertResult = '失败';
			echo('alert("提交失败,请刷新页面后再重新试下.");$id("messageForm").reset();ResetVerCode();');
		}
	
}



// 发送留言
function MessageSend(){
	global $DB,$systemArr,$tplSysArr;

	$dataType	= OT::GetRegExpStr('dataType','sql');

	$pageSize	= $tplSysArr['TS_messageNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select MA_ID,MA_time,MA_num,MA_userID,MA_username,MA_ipCN,MA_content,MA_addiContent,MA_isReply,MA_reply,MA_vote1,MA_vote2 from '. OT_dbPref .'message where MA_state=1 order by MA_time DESC',$pageSize,$page);
		if (! $showRow){
			echo('<br /><center class="font1_1">暂无留言</center>');
		}else{
			$recordCount=$DB->GetRowCount();
			$pageCount=ceil($recordCount/$pageSize);
			if ($page < 1 || $page > $pageCount){$page=1;}

			$number=1+($page-1)*$pageSize;
			$rowCount = count($showRow);

			echo('<ul>');
			for ($i=0; $i<$rowCount; $i++){
				if ($showRow[$i]['MA_userID']>0){ $replyName = '<span style="color:red;">会员：</span>'; }else{ $replyName = '留言者：'; }
				if (strlen($showRow[$i]['MA_ipCN'])>0 && strpos($tplSysArr['TS_messageEvent'],'|IP|')!==false){
					$ipCN = '&ensp;<span class="font1_2d">['. $showRow[$i]['MA_ipCN'] .']</span>';
				}else{
					$ipCN = '';
				}
				$addiContent = Area::GenTie($showRow[$i]['MA_ID'], $showRow[$i]['MA_addiContent'], $tplSysArr['TS_messageEvent'], 3);
				echo('
				<li>
					<div class="username">
						<div class="right font1_2d">
							<span class="font2_2" style="cursor:pointer;" onclick=\'ReplyUser("'. $showRow[$i]['MA_ID'] .'","'. $showRow[$i]['MA_num'] .'楼 '. $showRow[$i]['MA_username'] .'");\'>【回复】</span>&ensp;&ensp;
							<span style="color:#5ed317;cursor:pointer;" onclick="UserVote(\'message\','. $showRow[$i]['MA_ID'] .',1);"><img src="inc_img/userDing.gif" align="bottom" valign="top" alt="顶" title="顶" style="margin-right:2px;" /><span id="vote1_'. $showRow[$i]['MA_ID'] .'">'. $showRow[$i]['MA_vote1'] .'</span></span>&ensp;&ensp;
							<span style="color:#ff8056;cursor:pointer;" onclick="UserVote(\'message\','. $showRow[$i]['MA_ID'] .',2);"><img src="inc_img/userCai.gif" align="bottom" valign="top" alt="踩" title="踩" style="margin-right:2px;" /><span id="vote2_'. $showRow[$i]['MA_ID'] .'">'. $showRow[$i]['MA_vote2'] .'</span></span>&ensp;&ensp;
							'. TimeDate::Get('Y-m-d H:i',$showRow[$i]['MA_time']) .'
						</div>
						<b>'. $showRow[$i]['MA_num'] .'楼</b>&ensp;'. $replyName .''. $showRow[$i]['MA_username'] . $ipCN .'
					</div>
					<div class="note">'. Area::FaceSignToImg(Area::MessageEventDeal($showRow[$i]['MA_content'],$tplSysArr['TS_messageEvent']),'') .'</div>
					'. $addiContent .'
					');
					if ($showRow[$i]['MA_isReply'] == 1){
						echo('<div class="admin"><b>'. $tplSysArr['TS_messageAdminName'] .'：</b>'. Area::FaceSignToImg($showRow[$i]['MA_reply'],'') .'</div>');
					}
				echo('
				</li><div class="clr"></div>
				');
			}

			echo('
			</ul><div class="clr"></div>

			<table cellpadding="0" cellspacing="0" align="center" class="listNavBox" style="margin:0 auto; margin-top:8px;"><tr><td>
			'. Nav::Ajax('messageList',$pageCount,$pageSize,$recordCount) .'
			</td></tr></table>
			');
		}
	unset($showRow);
}



// 留言填写框
function MessageWrite(){
	global $DB,$webPathPart,$systemArr,$tplSysArr;
	$webPathPart	= Area::WppSign(OT::GetStr('webPathPart'));

	$username = Users::Username();
	if ($tplSysArr['TS_isMessage']==10 && $username==''){
		echo('
		<center style="font-size:14px;margin:30px 0 50px 0;">
			发表留言需要会员身份，请先 <a class="font2_1" href="users.php?mudi=login&force=1&isBack=1">[登录]</a>/<a class="font2_1" href="users.php?mudi=reg&force=1&isBack=1">[注册]</a>
		</center>
		');
	}else{
		$userIP	= Users::GetIp();
		if ($username == ''){ $replyName = '游客'; }else{ $replyName = $username; }
		$rndMd5 = md5(md5(OT_SiteID . session_id()) . OT_SiteID . $userIP);

		echo('
		<form id="messageForm" name="messageForm" method="post" action="deal.php?mudi=message" onsubmit="return CheckMessageForm();">
		<script language="javascript" type="text/javascript">document.write(\'<input type="hidden" name="backURL" value="\'+ document.location.href +\'" />\')</script>
		<input type="hidden" name="dataType" value="message" />
		<input type="hidden" id="rndMd5" name="rndMd5" value="'. $rndMd5 .'" />
		<input type="hidden" id="replyUserID" name="replyUserID" value="0" />
		<input type="hidden" id="messContentMaxLen" name="messContentMaxLen" value="'. $tplSysArr['TS_messageMaxLen'] .'" />
		<table id="messTable" align="center" style="margin:0 auto;">
		<tr>
			<td align="left">
				<a id="replyArea" name="replyArea"></a>
				<span style="font-weight:bold;" class="font2_2 pointer" onclick=\'FaceShow("faceInitBox","messContent");\'>[表情]</span>
				&ensp;&ensp;<span id="replyUserStr" style="color:blue;font-weight:bold;"></span>
				<div id="faceInitBox" style="width:98%;display:none;overflow:hidden;"></div>
			</td>
		</tr>
		<tr>
			<td>
				<textarea id="messContent" name="messContent"></textarea>
			</td>
		</tr>
		<tr>
			<td align="left">
				<div class="right"><span id="conMaxLenBox" class="font2_2"></span><input type="submit" value="" class="replyBtn button" /></div>
				<table cellpadding="0" cellspacing="0"><tr><td id="messUserBox">
				');
				if (Users::UserID()>0 && strlen($username)>0){
					echo('会员：'. $username .'<input type="hidden" id="messUser" name="messUser" value="'. $username .'" maxlength="25" />');
				}else{
					if (strpos($tplSysArr['TS_messageEvent'],'|noRevName|') !== false){
						echo('留言者：'. $replyName .'<input type="hidden" id="messUser" name="messUser" value="'. $replyName .'" />');
					}else{
						echo('留言者：<input type="text" id="messUser" name="messUser" value="'. $replyName .'" style="width:90px;" maxlength="25" />');
					}
				}
				echo('&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;');
				if (OT_OpenVerCode && strpos($systemArr['SYS_verCodeStr'],'|message|')!==false){
					echo('验证码：</td><td>'. Area::VerCode('messageForm') .'');
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



// 评论/留言踩顶处理
function UserVote(){
	global $DB,$infoSysArr;

	$type		= OT::GetStr('type');
	$dataID		= OT::GetInt('dataID');
	$selItem	= OT::GetInt('selItem',-1);
		if (! in_array($type, array('news','message'))){
			JS::AlertEnd('项目类型错误');
		}
		if ($dataID <= 0){
			JS::AlertEnd('项目ID错误');
		}
		if (! in_array($selItem, array(1,2))){
			JS::AlertEnd('项目值错误');
		}

	if (strpos(@$_SESSION[OT_SiteID . $type .'VoteList'],'['. $dataID .']') !== false){
		JS::AlertEnd('该用户已踩顶过，不要重复踩顶');
	}

	if ($type == 'news'){
		$DB->UpdateParam('infoMessage', array('IM_vote'. $selItem=>'IM_vote'. $selItem .'+1'), 'IM_ID='. $dataID);
	}else{
		$DB->UpdateParam('message', array('MA_vote'. $selItem=>'MA_vote'. $selItem .'+1'), 'MA_ID='. $dataID);
	}

	if (isset($_SESSION[OT_SiteID . $type .'VoteList'])){
		$_SESSION[OT_SiteID . $type .'VoteList'] .= '['. $dataID .']';
	}else{
		$_SESSION[OT_SiteID . $type .'VoteList'] = '['. $dataID .']';
	}

	JS::DiyEnd('$id("vote'. $selItem .'_'. $dataID .'").innerHTML = ToInt($id("vote'. $selItem .'_'. $dataID .'").innerHTML) + 1;');

}



// 下载专区
function download(){
	global $DB,$userSysArr;

	$dataID	= OT::GetInt('dataID');
	$point	= OT::GetInt('point',-1);

	$downexe = $DB->query('select IF_ID,IF_fileName,IF_file,IF_fileStr,IF_isRenameFile,IF_isUserFile from '. OT_dbPref .'info where IF_ID='. $dataID);
		if (! $row = $downexe->fetch()){
			JS::AlertCloseEnd('无该附件!');
		}else{
			if ($row['IF_file'] == '' && $row['IF_fileStr'] == ''){
				JS::AlertCloseEnd('无该附件!');
			}
			if ($row['IF_isUserFile'] == 1){
				$userID = Users::UserID();
				if ($userID == 0){
					JS::AlertHrefEnd('该附件仅限会员下载!', 'users.php?mudi=login&force=1&isBack=1');
				}else{
					$userRow = Users::Open('get',',UE_state,UE_authStr','',$judUserErr);
					if ((! $userRow) || $judUserErr != ''){
						JS::AlertHrefEnd('该附件仅限会员下载，请先登录！', 'users.php?mudi=login&force=1&isBack=1');
					}
					if ($userRow['UE_state'] == 0){
						JS::AlertCloseEnd('您当前状态是未审核，请先联系管理员给你审核通过，才能下载该附件。');
					}
					// 检测用户邮箱、手机号是否需要强制验证
					AreaApp::UserTixing($userRow['UE_authStr'], $userSysArr, 1);
				}
			}
			if ($point >= 0 && strlen($row['IF_fileStr']) > 0){
				$fileArr = explode('<arr>', $row['IF_fileStr'] .'<arr><arr><arr><arr><arr><arr><arr><arr>');
				list($fileUrl, $fileName) = explode('|', $fileArr[$point] .'||');
				if (strlen($fileUrl) == 0){
					JS::AlertCloseEnd('该附件不存在。');
				}
			}else{
				$fileUrl = $row['IF_file'];
				$fileName = $row['IF_fileName'];
			}

			if ($row['IF_isRenameFile'] == 1){
				if (strlen($fileName) > 0){
					$fileExt = File::GetExt($fileUrl);
					if (strlen($fileExt) > 10){ $fileExt = ''; }
					$fileName = $fileName .'.'. $fileExt;
				}else{
					$fileName = basename($fileUrl);
				}
				if (Is::AbsUrl($fileUrl)){
					// File::Download2($fileUrl, $fileName);
					header('Location:'. $fileUrl);
				}else{
					File::Download2(OT_ROOT . DownloadFileDir . $fileUrl, $fileName);
				}
			}else{
				if (Is::AbsUrl($fileUrl)){
					header('Location:'. $fileUrl);
				}else{
					header('Location:'. DownloadFileDir . $fileUrl);
				}
				exit;
			}
		}
	unset($downexe);
}



function DesktopUrl(){
	global $systemArr;

	$fileName = $systemArr['SYS_title'] .'.url';
	$ua = @$_SERVER['HTTP_USER_AGENT'];
	$fileNameEncode = urlencode($fileName);
	$fileNameEncode = str_replace('+', '%20', $fileNameEncode);

	header('Content-Type: application/octet-stream');
	if (preg_match('/MSIE/', $ua)){
		header('Content-Disposition: attachment; filename="'. $fileNameEncode .'"');
	}elseif (preg_match("/Firefox/", $ua)) {
		header('Content-Disposition: attachment; filename*="utf8\'\''. $fileName .'"');
	}else{
		header('Content-Disposition: attachment; filename="'. $fileName .'"');
	}


	//文件的类型 
	header('Content-type: application/octet-stream'); 
	//下载显示的名字 

	echo "[InternetShortcut]\n".
		 "URL=". GetUrl::CurrDir() ."\n".
		 "IDList=\n".
		 "[{000214A0-0000-0000-C000-000000000046}]\n".
		 "Prop3=19,2\n";

	exit(); 

}

?>