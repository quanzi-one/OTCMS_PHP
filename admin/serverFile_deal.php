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

$MB->IsAdminRight('alertBack');


switch ($mudi){
	case 'upFileDel':
		UpFileDel();
		break;

	case 'upFileMoreDel':
		UpFileMoreDel();
		break;

	case 'upFileUseCheck':
		UpFileUseCheck();
		break;

	case 'upFileUseClear':
		UpFileUseClear();
		break;

	default:
		die('err');
}

$MB->Close();
$DB->Close();





// 单个删除
function UpFileDel(){
	global $DB;

	$dataID = OT::GetInt('dataID');

	$delrec=$DB->query('select UF_ID,UF_oss,UF_type,UF_name,UF_fileName,UF_otherImgStr from '. OT_dbPref .'upFile where UF_ID='. $dataID);
		if (! $row = $delrec->fetch()){
			JS::AlertEnd('不存在该记录');
		}

		if (in_array($row['UF_oss'],AreaApp::OssNameArr())){
			$fileName = AreaApp::OssFileName($row['UF_name'],$row['UF_fileName']);
			$ossArr = AreaApp::OssDel($row['UF_oss'], $fileName);
			if (! $ossArr['res']){
				JS::AlertEnd($row['UF_oss'] .' 文件“'. $fileName .'”删除失败。\n可能原因：'. $ossArr['note'] .'\n如果云存储上该文件已删除，请通过【批量删除】来删除该记录');
			}
		}else{
			$fileDir = StrInfo::FileAdminDir($row['UF_type']);
			File::Del($fileDir . $row['UF_name']);
			$otherImgArr = explode(',',$row['UF_otherImgStr']);
			for ($i=0; $i<count($otherImgArr); $i++){
				File::Del($fileDir . $otherImgArr[$i]);
			}
		}
	unset($delrec);

	$DB->query('delete from '. OT_dbPref .'upFile where UF_ID='. $dataID);

	die('
	<script language="javascript" type="text/javascript">
	parent.$id("data'. $dataID .'").style.display="none";
	</script>
	');
}



// 批量删除
function UpFileMoreDel(){
	global $DB;

	$backURL	= OT::PostStr('backURL');
	$selDataID	= OT::Post('selDataID');

	if (count($selDataID)<=0){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$whereStr='';
	for ($i=0; $i<count($selDataID); $i++){
		$whereStr .= ','. intval($selDataID[$i]);
	}
	if ($whereStr == ''){
		JS::AlertBackEnd('请先选择要删除的记录.');
	}

	$fileDir = StrInfo::FileAdminDir($row['UF_type']);
	$delrec=$DB->query('select UF_ID,UF_oss,UF_type,UF_name,UF_otherImgStr from '. OT_dbPref .'upFile where UF_ID in (0'. $whereStr .')');
		while ($row = $delrec->fetch()){
			if (in_array($row['UF_oss'],AreaApp::OssNameArr())){
				$ossJud = AreaApp::OssDel($row['UF_oss'], $row['UF_name']);
				/* if (! $ossJud){
					JS::AlertEnd($row['UF_oss'] .' 文件“'. $row['UF_name'] .'”删除失败');
				} */
			}else{
				File::Del($fileDir . $row['UF_name']);
				$otherImgArr = explode(',',$row['UF_otherImgStr']);
				for ($i=0; $i<count($otherImgArr); $i++){
					File::Del($fileDir . $row['UF_name']);
					File::Del($fileDir . $otherImgArr[$i]);
				}
			}
		}
	unset($delrec);

	$DB->query('delete from '. OT_dbPref .'upFile where UF_ID in (0'. $whereStr .')');

	JS::AlertHrefEnd('批量删除成功.',$backURL);
}



// 检测更新占用情况
function UpFileUseCheck(){
	global $DB;

	@ini_set('max_execution_time', 0);
	@set_time_limit(0); 
	ob_start();

	$currNum = 0;
	$total = $DB->GetOne('select count(UF_ID) from '. OT_dbPref .'upFile');
	$delrec=$DB->query('select UF_ID,UF_type,UF_name from '. OT_dbPref .'upFile order by UF_chkTime ASC');
		while ($row = $delrec->fetch()){
			$currNum ++;
			$useNum = 0;
			$useNote = '';
			switch ($row['UF_type']){
				case 'images':
					$retNum = intval($DB->GetOne('select count(BN_ID) from '. OT_dbPref .'banner where BN_img like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【图片广告】占用数：'. $retNum .'；';
						}
					$retNum = intval($DB->GetOne('select count(IM_ID) from '. OT_dbPref .'infoMove where IM_img like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【友情链接】占用数：'. $retNum .'；';
						}
					$retNum = 0;
					$dr = $DB->GetRow('select TS_logo,TS_fullLogo from '. OT_dbPref .'tplSys');
					if (strpos($dr['TS_logo'],$row['UF_name']) !== false){ $retNum ++; }
					if (strpos($dr['TS_fullLogo'],$row['UF_name']) !== false){ $retNum ++; }
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【模板参数设置】占用数：'. $retNum .'；';
						}
					if (AppDashang::Jud()){
						$retNum = 0;
						$dr = $DB->GetRow('select DS_img1,DS_img2,DS_img3 from '. OT_dbPref .'dashang');
						if (strpos($dr['DS_img1'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['DS_img2'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['DS_img3'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【图片广告】占用数：'. $retNum .'；';
							}
					}
					if (AppGift::Jud()){
						$retNum = intval($DB->GetOne('select count(GD_ID) from '. OT_dbPref .'giftData where GD_img like "%'. $row['UF_name'] .'%" or GD_content like "%'. $row['UF_name'] .'%"'));
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【积分商城】占用数：'. $retNum .'；';
							}
					}
					if (AreaApp::Jud(30)){	// 网址跳转插件 
						$retNum = intval($DB->GetOne('select count(GUB_ID) from '. OT_dbPref .'goUrlBrowser where GUB_img like "%'. $row['UF_name'] .'%"'));
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【网址跳转】占用数：'. $retNum .'；';
							}
					}
					if (AppWeixin::Jud()){
						$retNum = 0;
						$dr = $DB->GetRow('select WS_img from '. OT_dbPref .'weixinSys');
						if (strpos($dr['WS_img'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【公众号参数】占用数：'. $retNum .'；';
							}
					}
					if (AppBase::Jud()){
						$retNum = 0;
						$dr = $DB->GetRow('select SI_watermarkPath from '. OT_dbPref .'sysImages');
						if (strpos($dr['SI_watermarkPath'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【生成图片参数】占用数：'. $retNum .'；';
							}
					}
					if (AppNewsVerCode::Jud()){
						$retNum = 0;
						$dr = $DB->GetRow('select IS_newsVerCodeImg from '. OT_dbPref .'infoSys');
						if (strpos($dr['IS_newsVerCodeImg'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【文章参数设置-公众号二维码图】占用数：'. $retNum .'；';
							}
					}
					break;


				case 'download':
					$retNum = intval($DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_fileStr like "%'. $row['UF_name'] .'%" or IF_mediaFile like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【文章附件&音视频文件】占用数：'. $retNum .'；';
						}
					break;


				case 'product':
					if (AppTaobaoke::Jud()){
						$retNum = intval($DB->GetOne('select count(TG_ID) from '. OT_dbPref .'taokeGoods where TG_img like "%'. $row['UF_name'] .'%" or TG_content like "%'. $row['UF_name'] .'%"'));
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【淘客商品】占用数：'. $retNum .'；';
							}
					}
					break;


				case 'users':
					$retNum = intval($DB->GetOne('select count(UL_ID) from '. OT_dbPref .'userLevel where UL_img like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【会员等级】占用数：'. $retNum .'；';
						}
					if (AppDashang::Jud()){
						$retNum = intval($DB->GetOne('select count(US_ID) from '. OT_dbPref .'users where UE_dashangImg1 like "%'. $row['UF_name'] .'%" or UE_dashangImg2 like "%'. $row['UF_name'] .'%" or UE_dashangImg3 like "%'. $row['UF_name'] .'%"'));
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【会员打赏图】占用数：'. $retNum .'；';
							}
					}
					if (AppForm::Jud()){
						$retNum = intval($DB->GetOne('select count(FU_ID) from '. OT_dbPref .'formUsers where FU_data like "%'. $row['UF_name'] .'%"'));
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【多功能表单】占用数：'. $retNum .'；';
							}
					}
					break;


				default:	// 默认infoImg目录
					$retNum = intval($DB->GetOne('select count(BD_ID) from '. OT_dbPref .'bbsData where BD_content like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【论坛帖子】占用数：'. $retNum .'；';
						}
					$retNum = intval($DB->GetOne('select count(AD_ID) from '. OT_dbPref .'ad where AD_code like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【广告】占用数：'. $retNum .'；';
						}
					$retNum = intval($DB->GetOne('select count(IF_ID) from '. OT_dbPref .'info where IF_img like "%'. $row['UF_name'] .'%" or IF_content like "%'. $row['UF_name'] .'%" or IF_encContent like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【文章】占用数：'. $retNum .'；';
						}
					$retNum = intval($DB->GetOne('select count(IW_ID) from '. OT_dbPref .'infoWeb where IW_content like "%'. $row['UF_name'] .'%" or IW_contentWap like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【单篇页】占用数：'. $retNum .'；';
						}
					$retNum = intval($DB->GetOne('select count(TS_ID) from '. OT_dbPref .'tplSys where TS_copyright like "%'. $row['UF_name'] .'%"'));
						if ($retNum > 0){
							$useNum += $retNum;
							$useNote .= '【模板参数设置】占用数：'. $retNum .'；';
						}
					if (AppMoneyPay::Jud()){	// 在线充值基础包
						$retNum = 0;
						$dr = $DB->GetRow('select MS_userPayNote,MS_otherPayNote,MS_otherPayNoteWap,MS_webPayNote from '. OT_dbPref .'moneySys');
						if (strpos($dr['MS_userPayNote'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['MS_otherPayNote'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['MS_otherPayNoteWap'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['MS_webPayNote'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【财务参数设置】占用数：'. $retNum .'；';
							}
					}
					if (AppWap::Jud()){	// 手机版
						$retNum = 0;
						$dr = $DB->GetRow('select WAP_logo,WAP_flashDataStr from '. OT_dbPref .'wap');
						if (strpos($dr['WAP_logo'],$row['UF_name']) !== false){ $retNum ++; }
						if (strpos($dr['WAP_flashDataStr'],$row['UF_name']) !== false){ $retNum ++; }
							if ($retNum > 0){
								$useNum += $retNum;
								$useNote .= '【手机版设置】占用数：'. $retNum .'；';
							}
					}
					break;
			}

			$record = array();
			$record['UF_useNum'] = $useNum;
			$record['UF_chkTime'] = TimeDate::Get();
			$record['UF_chkNote'] = $useNote;
			$DB->UpdateParam('upFile',$record,'UF_ID='. $row['UF_ID']);

			echo('$id("chkInfo").innerHTML = "'. $currNum .'/'. $total .'";');
			ob_flush();
			flush();
		}
	unset($delrec);
}



// 清理占用数为0的图片
function UpFileUseClear(){
	global $DB;

	$delNum = 0;
	$delrec=$DB->query('select UF_ID,UF_oss,UF_type,UF_name,UF_otherImgStr from '. OT_dbPref .'upFile where UF_useNum<=0 and UF_chkTime>='. $DB->ForTime(TimeDate::Get('datetime',strtotime('-1 day'))) .' and UF_time<='. $DB->ForTime(TimeDate::Get('datetime',strtotime('-7 day'))));
		while ($row = $delrec->fetch()){
			$delNum ++;
			if (in_array($row['UF_oss'],AreaApp::OssNameArr())){
				$ossJud = AreaApp::OssDel($row['UF_oss'], $row['UF_name']);
				/* if (! $ossJud){
					JS::AlertEnd($row['UF_oss'] .' 文件“'. $row['UF_name'] .'”删除失败');
				} */
			}else{
				$fileDir = StrInfo::FileAdminDir($row['UF_type']);
				if (file_exists($fileDir . $row['UF_name'])==true){
					$otherImgArr = explode(',',$row['UF_otherImgStr']);
					for ($i=0; $i<count($otherImgArr); $i++){
						File::Del($fileDir . $row['UF_name']);
						File::Del($fileDir . $otherImgArr[$i]);
					}
				}
			}
			$DB->query('delete from '. OT_dbPref .'upFile where UF_ID='. $row['UF_ID']);
		}
	unset($delrec);

	echo('
	<script language="javascript" type="text/javascript">
	alert("共清理掉'. $delNum .'个图片/文件（仅限7天前发布的文件，以及最后占用检测时间是24小时内）.");document.location.href="serverFile.php?mudi=manage";
	</script>
	');
}

?>