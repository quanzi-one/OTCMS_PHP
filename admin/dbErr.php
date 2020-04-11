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
<script language="javascript" type="text/javascript" src="js/inc/list.js?v='. OT_VERSION .'"></script>
<script language="javascript" type="text/javascript" src="js/share.js?v='. OT_VERSION .'"></script>
');


$dataTypeCN = '数据库错误日志'; 


switch ($mudi){
	case 'manage':
		$MB->IsAdminRight('alertBack');
		manage();
		break;

	default:
		die('err');
}

$skin->WebBottom();

$MB->Close();
$DB->Close();





function manage(){
	global $DB,$MB,$skin,$mudi,$dataType,$dataTypeCN,$pageCount,$recordCount;

	$skin->TableTop2('share_list.gif','',$dataTypeCN .'列表');
	$skin->TableItemTitle('4%,22%,66%,9%','序号,错误信息,SQL语句,发生时间');

	$pageSize	= $MB->mMbRow['MB_itemNum'];		//每页条数
	$page		= OT::GetInt('page');
	$showRow=$DB->GetLimit('select * from '. OT_dbPref .'dbErr order by DE_ID DESC',$pageSize,$page);
	if ($showRow){
		$recordCount=$DB->GetRowCount();
		$pageCount=ceil($recordCount/$pageSize);
		if ($page < 1 || $page > $pageCount){$page=1;}

		echo('
		<tbody class="tabBody padd5td">
		');
		$number=1+($page-1)*$pageSize;
		$rowCount = count($showRow);
		for ($i=0; $i<$rowCount; $i++){
			if ($i % 2 == 1){ $bgcolor='class="tabColorTr"'; }else{ $bgcolor=''; }

			echo('
			<tr '. $bgcolor .'>
				<td align="center">'. $number .'</td>
				<td align="left" style="word-break:break-all;">'. $showRow[$i]['DE_note'] .'<br /></td>
				<td align="left" style="word-break:break-all;">'. $showRow[$i]['DE_content'] .'<br /></td>
				<td align="center">'. $showRow[$i]['DE_time'] .'<br /></td>
			</tr>
			');
			$number ++;
		}

		echo('
		</tbody>
		<tr class="tabColorB padd5td">
			<td align="left" colspan="20">
				<input type="button" value="1天前记录删除" onclick=\'if(confirm("你确定要删除1天前记录？")==true){document.location.href="dbErr_deal.php?mudi=monthDel&day=1&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="3天前记录删除" onclick=\'if(confirm("你确定要删除3天前记录？")==true){document.location.href="dbErr_deal.php?mudi=monthDel&day=3&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="7天前记录删除" onclick=\'if(confirm("你确定要删除1周前记录？")==true){document.location.href="dbErr_deal.php?mudi=monthDel&day=7&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="14天前记录删除" onclick=\'if(confirm("你确定要删除2周前记录？")==true){document.location.href="dbErr_deal.php?mudi=monthDel&day=14&backURL="+ encodeURIComponent(document.location.href)}\' />
				<input type="button" value="30天前记录删除" onclick=\'if(confirm("你确定要删除一个月前记录？")==true){document.location.href="dbErr_deal.php?mudi=monthDel&day=30&backURL="+ encodeURIComponent(document.location.href)}\' />
			</td>
		</tr>
		');
	}
	unset($showRow);

	$skin->TableBottom2($pageCount, $pageSize, $recordCount);

}

?>