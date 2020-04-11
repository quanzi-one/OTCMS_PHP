<?php
header('HTTP/1.0 404 Not Found');
header('Status: 404 Not Found');

require(dirname(__FILE__) .'/conobj.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<title>404 Error：您所查找的页面不存在 - <?php echo($systemArr['SYS_title'] . $systemArr['SYS_titleAddi']); ?></title>
<style type="text/css">
body{margin:0;padding:0;font:14px/1.6 Arial,Sans-serif;}
a:link,a:visited{color:#007ab7;text-decoration:none;}
.link a{margin-right:1em;}
.link,.texts{width:580px;margin:0 auto 15px;color:#505050;}
.texts{line-height:2;}
.texts dd{margin:0;padding:0 0 0 15px;}
.texts ul{margin:0;padding:0;}
</style>

<script language='javascript' type='text/javascript'>
imgSrc = "inc_img/404err.jpg";
imgCalc=0;
imgPart="";
function GetUpDir(){
	imgPart += "../";
	imgCalc++;
	return imgPart + imgSrc;
}
</script>

</head>
<body>
<?php
$webMainUrl = GetUrl::CurrDir();
$webPathPart = '';
?>


	<style>
	body{ margin:0; padding:0; list-style:none; background:#e6e6e6;}
	.lanrenzhijia{ width:1003px;height:35px; line-height:35px; margin:0 auto; margin-top:20px; text-align:center;background:#636871;}
	.lanrenzhijia li a{ color:#fff; text-decoration:none; display:block; float:left; height:35px; line-height:35px; padding:0px 15px; font-size:14px;background:#636871;}
	.lanrenzhijia li a:hover{ background:#4b505a;}
	.lanrenzhijia li{float:left;position:relative; height:35px; line-height:35px;}
	.lanrenzhijia li .second{position:absolute;left:0;display:none;}
	</style>
	</head>
	<body>
	<div class="lanrenzhijia">
	<!-- <li>
	<a id="homeUrl" href="<%=webMainUrl%>">返回网站首页</a>
	<div class="second">
	</div>
	</li> -->
	<?php
	$menuexe = $DB->query('select IT_ID,IT_theme,IT_webDesc,IT_mode,IT_URL,IT_isEncUrl,IT_webID,IT_level,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_isMenu=1 order by IT_rank ASC');
		while ($row = $menuexe->fetch()){
			$hrefStr = Area::InfoTypeUrl(array(
				'IT_mode'		=> $row['IT_mode'],
				'IT_ID'			=> $row['IT_ID'],
				'IT_webID'		=> $row['IT_webID'],
				'IT_URL'		=> $row['IT_URL'],
				'IT_isEncUrl'	=> $row['IT_isEncUrl'],
				'IT_htmlName'	=> $row['IT_htmlName'],
				'mainUrl'		=> $webMainUrl,
				'webPathPart'	=> $webPathPart,
				));

			echo('
			<li>
				<a href="'. $hrefStr .'">'. $row['IT_theme'] .'</a>
				<div class="second">
				');
			if ($row['IT_level'] == 1){
				$menu2exe = $DB->query('select IT_ID,IT_theme,IT_webDesc,IT_mode,IT_URL,IT_isEncUrl,IT_webID,IT_htmlName from '. OT_dbPref .'infoType where IT_state=1 and IT_fatID='. $row['IT_ID'] .' and IT_isSubMenu=1 order by IT_rank ASC');
					if ($row2 = $menu2exe->fetch()){
						do {
							$hrefStr = Area::InfoTypeUrl(array(
								'IT_mode'		=> $row2['IT_mode'],
								'IT_ID'			=> $row2['IT_ID'],
								'IT_webID'		=> $row2['IT_webID'],
								'IT_URL'		=> $row2['IT_URL'],
								'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
								'IT_htmlName'	=> $row2['IT_htmlName'],
								'mainUrl'		=> $webMainUrl,
								'webPathPart'	=> $webPathPart,
								));
							echo('
							<a href="'. $hrefStr .'">'. $row2['IT_theme'] .'</a>
							');
						}while ($row2 = $menu2exe->fetch());
					}
				$menu2exe = null;
			}
				echo('
				</div>
			</li>
			');
		}
	unset($menuexe);
	?>

</div>
<script src="js/inc/jquery.min.js"></script>
<script>
$(function(){
	var lanrenzhijia = 0; // 默认值为0，二级菜单向下滑动显示；值为1，则二级菜单向上滑动显示
	if(lanrenzhijia ==0){
		$('.lanrenzhijia li').hover(function(){
			$('.second',this).css('top','30px').show();
		},function(){
			$('.second',this).hide();
		});
	}else if(lanrenzhijia ==1){
		$('.lanrenzhijia li').hover(function(){
			$('.second',this).css('bottom','30px').show();
		},function(){
			$('.second',this).hide();
		});
	}
});
</script>

<iframe allowtransparency='true' frameborder='0' width='100%' height='660' scrolling='no' src='<?php echo($webMainUrl); ?>404gy.html'></iframe>";
	
</body>
</html>