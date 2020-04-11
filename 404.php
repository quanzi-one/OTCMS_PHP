<?php
header('HTTP/1.0 404 Not Found');
header('Status: 404 Not Found');

testonehtftime
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
	<center><img id="errImg" src="inc_img/404err.jpg" title="404 Error：您所查找的页面不存在" alt="404 Error：您所查找的页面不存在" onerror="if (imgCalc<=5){ this.src=GetUpDir(); }else{ return false; }" /></center>
	<p class="link">
		<a id="homeUrl" href="<?php echo($webMainUrl); ?>">&#9666;返回网站首页</a>
		<a href="javascript:history.go(-2);">&#9666;返回上一页</a>
	</p>
	<dl class="texts">
        <dt style="margin-bottom:20px;">404 Error：您所查找的页面不存在,可能已被删除或您输错了网址，您可以访问下面导航栏目.</dt>
		<dd>
			<ul>
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
					<li><a href="'. $hrefStr .'"><strong>'. $row['IT_theme'] .'</strong></a></li>
					<div style="font-size:12px;">'. str_replace('*','',$row['IT_webDesc']) .'</div>
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
									<li style="margin-left:30px;list-style-type:circle;"><a href="'. $hrefStr .'"><strong>'. $row2['IT_theme'] .'</strong></a></li>
									<div style="font-size:12px;margin-left:30px;">'. str_replace('*','',$row2['IT_webDesc']) .'</div>
									');
								}while ($row2 = $menu2exe->fetch());
							}
						$menu2exe = null;
					}
				}
			unset($menuexe);
			?>
			</ul>
		</dd>
	</dl>
</body>
</html>