<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}

require(OT_ROOT .'inc/classTemplateOTCMS.php');

class Template extends TemplateOTCMS{
	public $obj;
	public $isRegPlu		= false;	// 是否注册了自定义函数
	public $sysArr;
	public $tplSysArr;
	public $taokeSysArr;
	public $tplDir;

	public $webHost			= '';
	public $dbPathPart		= '';
	public $webPathPart		= '';
	public $jsPathPart		= '';
	public $webTitle		= '';
	public $webTitleAddi	= '';
	public $webKey			= '';
	public $webDesc			= '';
	public $closeWebContent	= '';
	public $metaTagStr		= '';

	public $isAppTaoke		= false;	// 淘宝客
	public $isAppDashang	= false;	// 打赏
	public $isJump			= true;		// 非当前路径是否跳转
	public $tplFileName		= '';
	public $webTypeName		= '';	// 页面类型
	public $webTypeName2	= '';	// 页面类型2
	public $webDataID		= 0;	// 页面ID
	public $urlMode			= '';	// 当前路径模式（动态、伪静态、纯静态）
	public $fileName		= '';	// 文件名
	public $pointStr		= '';	// 当前位置
	public $refType			= '';	// 列表页查询类型变量
	public $refContent		= '';	// 列表页查询内容变量
	public $markName		= '';	// 列表页查询标签变量
	public $areaName		= '';	// 区域名称
	public $typeID			= 0;	// 当前栏目ID
	public $itemLevel		= '';	// 当前栏目等级
	public $isRightMenu		= 0;	// 次页右侧菜单是否开启
	public $rightMenuID		= 0;	// 次页右侧菜单ID
	public $rightMenuName	= '';	// 次页右侧菜单名称
	public $rightNewNum		= 10;	// 次页右侧最新文章数量
	public $rightRecomNum	= 10;	// 次页右侧推荐文章数量
	public $rightHotNum		= 10;	// 次页右侧文章文章数量
	public $page			= 0;	// 页码
	public $queryStr		= '';	// 网址?后面的内容
	public $pcUrl			= '';	// 电脑版网址
	public $wapUrl			= '';	// 手机版网址

	public function __construct(){
		global $systemArr,$tplSysArr;
		// 加载初始化信息
		//require OT_ROOT .'cache/php/system.php';
		//require OT_ROOT .'cache/php/tplSys.php';
		$this->sysArr = $systemArr;
		$this->tplSysArr = $tplSysArr;
		$this->tplDir = 'template/'. $this->sysArr['SYS_templateDir'];

		if ($this->sysArr['SYS_isClose'] == 10){	// 网站关闭
			$closeWebContent = '
			<!DOCTYPE html>
			<html>
			<head>
				<title>网站暂时关闭中...</title>
			</head>
			<body>
				<table align="center" cellpadding="0" cellspacing="0"><tr><td align="left" style="font-size:14px;">'. $this->sysArr['SYS_closeNote'] .'</td></tr></table>
			</body>
			</html>
			';
		}

		// 初始化Smarty模板引擎
		require OT_ROOT .'smarty/Smarty.class.php';
		$this->obj = new Smarty;
		$this->obj->template_dir	= OT_ROOT . $this->tplDir;					// 模板目录
		$this->obj->addTemplateDir(OT_ROOT .'template/default/','def');			// 默认模板目录
		$this->obj->compile_dir		= OT_ROOT .'cache/smarty/templates_c/';		// 编译模板的目录
		$this->obj->config_dir		= OT_ROOT .'cache/smarty/configs/';			// 模板配置文件的目录
		$this->obj->cache_dir		= OT_ROOT .'cache/smarty/cache/';			// 缓存目录
		$this->obj->plugins_dir		= OT_ROOT .'cache/smarty/plugins/';			// 插件的目录

		$this->obj->debugging		= false;							// 调试变量，启动调试控制台
		$this->obj->debugging_ctrl	= true;							// 调试控制变量，是否允许以交替的方式启动调试设置
		$this->obj->debug_tpl		= OT_ROOT .'smarty/debug.tpl';	// 调试模板，用于调试控制台的模板文件名字

		$this->obj->compile_check	= true;		// 编译检查变量,为 false 即使文件被修改，也不会重新编译
		$this->obj->force_compile	= false;	// 强迫编译变量,不受$compile_check的限制

		$this->obj->caching			= 0;		// 是否开启缓存,-1强迫缓存永不过期；0缓存总是重新生成；1判断文件是否有被修改；2判断缓存生存时间是否超时
		$this->obj->cache_lifetime	= 90;		// 缓存生存时间(单位秒)

		$this->obj->use_sub_dirs	= true;					// 如果你的php环境不允许Smarty创建子目录,则设此变量为假.子目录非常有用,所以尽可能的使用他们. 
//		$this->obj->php_handling	= SMARTY_PHP_ALLOW;		// php处理模式，SMARTY_PHP_PASSTHRU 原样输出标记；SMARTY_PHP_QUOTE 作为html实体引用标记；SMARTY_PHP_REMOVE 从模板中移出标记；SMARTY_PHP_ALLOW 将作为php代码执行标记；
//		$this->obj->autoload_filters	= array('pre' => array('trim', 'stamp'),'output' => array('convert'));	// 自动加载过滤器变量,在数组中键是过滤器类型,值是过滤器名字所组成的数组

		$this->obj->left_delimiter	= '{otcms:';	// 自定义标记左分隔符
		$this->obj->right_delimiter	= '}';			// 自定义标记右分隔符

		$this->RegFunc();	// 注册自定义函数

		$this->Add('sysArr',	$this->sysArr);
		$this->Add('tplSysArr', $this->tplSysArr);

		if (AppTaobaoke::Jud()){
			if ($taokeSysFile = @include(OT_ROOT .'cache/php/taokeSys.php')){
				$taokeSysArr = unserialize($taokeSysFile);
			}else{
				$Cache = new Cache();
				$Cache->Php('taokeSys');
				die('
				<br /><br />
				<center>
					加载taokeSys配置文件失败，<a href="#" onclick="document.location.reload();">[点击重新刷新]</a>
				</center>
				');
			}

			$this->isAppTaoke = true;
			$this->taokeSysArr = $taokeSysArr;
			$this->Add('taokeSysArr', $this->taokeSysArr);
		}
		if (AppDashang::Jud()){
			$this->isAppDashang = true;
		}

	}


	// 解析页头
	public function WebTop(){
		global $GB_WebHost;

		if ($this->webTitleAddi == '*'){ $this->webTitleAddi = $this->sysArr['SYS_titleAddi']; }
		if (empty($this->webTitle)){ $this->webTitle = $this->sysArr['SYS_titleHome']; }
		if ($this->webKey == '*'){ $this->webKey = $this->sysArr['SYS_webKey']; }
		if ($this->webDesc == '*'){ $this->webDesc = $this->sysArr['SYS_webDesc']; }
		$webTitleStr = str_replace(array('{%网站标题%}','{%网站标题附加%}'), array($this->sysArr['SYS_title'],$this->sysArr['SYS_titleAddi']), $this->webTitle);

		$this->webHost = '';
		if ($this->webPathPart == '' && $this->sysArr['SYS_isUrl301'] <= 1){
			if ($this->sysArr['SYS_URL'] == ''){
				$webBaseURL	= GetUrl::Query();
				$htmlInstr	= strpos($webBaseURL,'.html');
				if ($htmlInstr>0 || OT::GetStr('mudi')=='homeHtml'){
					$endUrlPos	= strrpos($webBaseURL,'/')-1;
					$webBaseURL	= substr($webBaseURL,0,$endUrlPos);
				}
				$endUrlPos	= strrpos($webBaseURL,'/');
				$webBaseURL	= substr($webBaseURL,0,$endUrlPos);
				$this->webHost	= $webBaseURL;
			}else{
				$this->webHost	= $this->sysArr['SYS_URL'];
			}
			$this->metaTagStr .= '<base href="'. $this->webHost .'" />';
		}else{
			$this->webHost = $this->webPathPart;
		}
		$GB_WebHost = $this->webHost;

		$wapBtnStr	= '';
		$wapBtnUrl = '';
		$isWapBtn = 0;
		if ($this->sysArr['SYS_isWap'] == 1 && AppWap::Jud()){
			if (! empty($this->sysArr['SYS_wapUrl'])){
				$wapBtnUrl = $this->sysArr['SYS_wapUrl'];
				$wapJsStr = 'document.location.href="'. $this->webPathPart .'selWapPc.php?go=wap&goUrl='. urlencode($wapBtnUrl) .'";return false;';
			}else{
				$wapBtnUrl = $this->webPathPart .'wap/';
				$wapJsStr = '';
			}
			$isWapBtn = 1;
			$wapBtnStr = '<a href="'. $wapBtnUrl .'" target="_blank" onclick=\'SetCookie("wap_otcms","wap");'. $wapJsStr .'\'><img id="topWapBtn" src="'. $this->webPathPart .'inc_img/wap2.gif" alt="WAP手机版" /></a>';

			if (strlen($this->wapUrl) < 7){
				switch ($this->webTypeName){
					case 'home':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'];
						break;
					case 'list':
						$typeStr	= $pageStr = '';
						$listArr	= explode('_',substr($this->queryStr,5));

						if (is_numeric($listArr[0])){
							$this->typeID		= intval($listArr[0]);
							$this->webDataID	= $this->typeID;
						}else{
							$typeStr = $listArr[0];
							if (strpos($typeStr,'-') !== false){
								$typeStrArr	= explode('-',$typeStr);
								$typeStr	= $typeStrArr[0];
								if (count($typeStrArr) ==3 ){
									$this->refType		= $typeStrArr[1];
									$this->refContent	= Str::RegExp(urldecode($typeStrArr[2]),'sql+ ');
									$typeStr	= $typeStr .'-'. $this->refType .'-'. urlencode($this->refContent);
								}else{
									if (strpos('|user|topic|','|'. $typeStr .'|') !== false){
										$this->markName	= intval($typeStrArr[1]);
									}else{
										$this->markName	= Str::RegExp(urldecode($typeStrArr[1]),'sql');
									}
									$typeStr = $typeStr .'-'. urldecode($this->markName);
								}
							}
						}
						if (count($listArr) == 2){ $this->page = intval($listArr[1]); }
						if ($this->page>1){ $pageStr='_'. $this->page; }else{ $pageStr=''; }
						if ($typeStr != ''){
							$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'news/?list_'. $typeStr . $pageStr .'.html';
						}else{
							$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'news/?list_'. $this->webDataID . $pageStr .'.html';
						}
						break;
					case 'show':
						$queryArr			= explode('_',$this->queryStr);
						$this->webDataID	= intval($queryArr[0]);
						$this->wapUrl		= $this->sysArr['SYS_wapUrl'] .'news/?'. $this->webDataID .'.html';
						break;
					case 'web':
						$queryArr			= explode('_',substr($this->queryStr,4));
						$this->webDataID	= intval($queryArr[0]);
						$this->wapUrl		= $this->sysArr['SYS_wapUrl'] .'news/?web_'. $this->webDataID .'.html';
						break;
					case 'message':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'message.php';
						break;
					case 'users':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'users.php';
						$queryPart = $_SERVER['QUERY_STRING'];
						if (strlen($queryPart)>0){ $this->wapUrl .= '?'. $queryPart; }
						break;
					case 'bbsHome':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'message/';
						break;
					case 'bbsList': case 'bbsShow':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'message/';
						$queryPart = $_SERVER['QUERY_STRING'];
						if (strlen($queryPart)>0){ $this->wapUrl .= '?'. $queryPart; }
						break;
					case 'bbsWrite':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'message/posts.php';
						break;
					case 'gift':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'gift.php';
						$queryPart = $_SERVER['QUERY_STRING'];
						if (strlen($queryPart)>0){ $this->wapUrl .= '?'. $queryPart; }
						break;
					case 'form':
						$this->wapUrl = $this->sysArr['SYS_wapUrl'] .'form.php';
						$queryPart = $_SERVER['QUERY_STRING'];
						if (strlen($queryPart)>0){ $this->wapUrl .= '?'. $queryPart; }
						break;
				}
			}

			if ($this->sysArr['SYS_wapMetaTag']==1 && strlen($this->wapUrl)>0){
				$this->metaTagStr = ''.
					'<meta http-equiv="Cache-Control" content="no-transform" />'. PHP_EOL .
					'	<meta http-equiv="Cache-Control" content="no-siteapp" />'. PHP_EOL .
					'	<meta name="applicable-device" content="pc" />'. PHP_EOL .
					'	<meta http-equiv="mobile-agent" content="format=xhtml; url='. $this->wapUrl .'" />'. PHP_EOL .
					'	<meta http-equiv="mobile-agent" content="format=html5; url='. $this->wapUrl .'" />'. PHP_EOL .
					'	<meta http-equiv="mobile-agent" content="format=wml; url='. $this->wapUrl .'" />'. PHP_EOL .
					'	<link rel="alternate" media="only screen and(max-width: 640px)" href="'. $this->wapUrl .'" />'. PHP_EOL .
					$this->metaTagStr;
			}
		}

		$siteLogo = '';
		if (strlen($this->tplSysArr['TS_logo']) < 3){
			$logo = $this->webPathPart . $this->tplDir .'logo.png';
		}else{
			if (Is::AbsUrl($this->tplSysArr['TS_logo'])){
				$logo = $this->tplSysArr['TS_logo'];
			}else{
				$logo = $this->webPathPart . ImagesFileDir . $this->tplSysArr['TS_logo'];
			}
		}
		if (strlen($this->tplSysArr['TS_fullLogo']) < 3){
			$fullLogo = $this->webPathPart . $this->tplDir .'logoFull.png';
		}else{
			if (Is::AbsUrl($this->tplSysArr['TS_fullLogo'])){
				$fullLogo = $this->tplSysArr['TS_fullLogo'];
			}else{
				$fullLogo = $this->webPathPart . ImagesFileDir . $this->tplSysArr['TS_fullLogo'];
			}
		}
		$siteLogo = TplTop::GetLogoImg($logo,$fullLogo);

		$siteTopAdCode = '';
		if ($this->tplSysArr['TS_isTopAd'] == 1 || ($this->tplSysArr['TS_isTopAd'] == 10 && $this->webTypeName == 'home') ){
			$siteTopAdCode = $this->tplSysArr['TS_topAdCode'];
		}

		$GB_WebDomain = GetUrl::Main();

		$webBodyStyleStr = '';
		if ($this->isAppTaoke){
			if ($this->taokeSysArr['TS_isTopShop'] == 1){
				$webBodyStyleStr .= 'background-position:0px '. $this->taokeSysArr['TS_topShopH'] .'px;';
			}
		}

		$webBodyStr = '';
		if (strpos($this->sysArr['SYS_eventStr'],'|siteNoCopy|') !== false || (strpos($this->sysArr['SYS_eventStr'],'|siteNoCopyShow|') !== false && strpos('|show|web|',$this->webTypeName) !== false) ){
			$webBodyStr .= 'oncontextmenu="return false" ondragstart="return false" onselectstart ="return false" onselect="document.selection.empty()" oncopy="document.selection.empty()" onbeforecopy="return false"><style>*{ -moz-user-select:none; }</style';	//  onmouseup="document.selection.empty()"
		}

		$searchBaiduStr	= $searchAreaStr = '';
		$searchAreaArr	= explode('<arr>',$this->tplSysArr['TS_searchArea']);
		sort($searchAreaArr);
		foreach ($searchAreaArr as $key => $val){
			if (substr($val .' ',0,1) == '1'){
				$searchArr = explode('|',$val);
				if ($searchArr[2] == 'baidu'){
					$searchBaiduStr = '<input type="hidden" id="zhannei_domain" name="zhannei_domain" value="'. $searchArr[4] .'" />'.
									'<input type="hidden" id="zhannei_id" name="zhannei_id" value="'. $searchArr[5] .'" />';
				}elseif ($searchArr[2] == '360'){
					$searchBaiduStr = '<input type="hidden" id="zhannei_ie" name="zhannei_ie" value="utf8">'.
									'<input type="hidden" id="zhannei_src" name="zhannei_src" value="zz_'. $searchArr[4] .'">'.
									'<input type="hidden" id="zhannei_site" name="zhannei_site" value="'. $searchArr[4] .'">'.
									'<input type="hidden" id="zhannei_rg" name="zhannei_rg" value="1">';
				}
				$searchAreaStr .= '<option value="'. $searchArr[2] .'">'. $searchArr[3] .'</option>';
			}
		}

		$this->Add('siteCharset',		OT_Charset);			// 网站编码
		$this->Add('siteUrl',			$this->webHost);		// 网站网址
		$this->Add('siteName',			$webTitleStr);			// 网站标题
		$this->Add('siteKeywords',		$this->webKey);			// 网站关键词
		$this->Add('siteDescription',	$this->webDesc);		// 网站描述
		$this->Add('siteBaseTag',		$this->metaTagStr);		// meta标签
		$this->Add('siteDomain',		$GB_WebDomain);			// 网站域名
		$this->Add('siteVer',			OT_VERSION);			// 网站版本号
		$this->Add('webTypeName',		$this->webTypeName);	// 当前页面名称（如首页home，列表页list，内容页show）
		$this->Add('webDataID',			0);						// 当前页面记录ID
		$this->Add('webBodyStyleStr',	$webBodyStyleStr);		// body标签CSS样式
		$this->Add('webBodyStr',		$webBodyStr);			// body标签内容
		$this->Add('dbPathPart',		$this->dbPathPart);		// 数据库路径前缀
		$this->Add('webPathPart',		$this->webPathPart);	// 网页路径前缀
		$this->Add('jsPathPart',		$this->jsPathPart);		// JS路径前缀
		$this->Add('wapUrl',			$this->wapUrl);			// 手机版路径
		$this->Add('isWapBtn',			$isWapBtn);				// 是否显示手机按钮
		$this->Add('wapBtnUrl',			$wapBtnUrl);			// 手机版按钮网址
		$this->Add('tplDir',			$this->tplDir);			// 模板目录
		$this->Add('currTplDir',		$this->webPathPart . $this->tplDir);	// 当前模板路径
		$this->Add('siteLogo',			$siteLogo);				// 网站LOGO图
		$this->Add('wapBtnStr',			$wapBtnStr);			// WAP手机版按钮标签
		$this->Add('siteTopAdCode',		$siteTopAdCode);		// 页头文字广告代码
		$this->Add('searchAreaStr',		$searchAreaStr);		// 页头搜索栏
		$this->Add('searchHiddenStr',	$searchBaiduStr);		// 页头搜索栏隐藏信息
		$this->Add('areaTop1',			TplArea::AreaTop1());	// 顶部通用区域1
		$this->Add('areaTop2',			TplArea::AreaTop2());	// 顶部通用区域2
		$this->Add('areaTop3',			TplArea::AreaTop3());	// 顶部通用区域3
		$this->metaTagStr = '';
	}



	// 解析首页
	public function WebIndex(){
		$announMoreUrl = '';
		if ($this->tplSysArr['TS_isHomeAnnoun'] != 0){
			$announMoreUrl = Url::ListStr('announ');
		}

		$homeNewStyle = $homeRecomStyle = '';
		if ($this->tplSysArr['TS_homeNewBoxH'] > 0){ $homeNewStyle = 'height:'. $this->tplSysArr['TS_homeNewBoxH'] .'px;overflow:hidden;'; }
		if ($this->tplSysArr['TS_homeRecomBoxH'] > 0){ $homeRecomStyle = 'height:'. $this->tplSysArr['TS_homeRecomBoxH'] .'px;overflow:hidden;'; }
		
		$this->Add('homeNewStyle',		$homeNewStyle);		// 最新消息高度
		$this->Add('homeRecomStyle',	$homeRecomStyle);	// 精彩推荐高度
		$this->Add('announMoreUrl',		$announMoreUrl);	// 公告更多链接
	}


	
	// 解析列表页
	public function WebList(){
		global $DB;

		$typeStr		= '';
		$IT_theme2		= '';
		$IT_listInfo	= '';
		$IT_showMode	= 2;
		$IT_showNum		= 10;
		$listStr		= substr($this->queryStr,5);
		$listArr		= explode('_',$listStr);

		if (is_numeric($listArr[0])){
			$this->typeID = intval($listArr[0]);
		}else{
			$typeStr = $listArr[0];
			if (strpos($typeStr,'-') !== false){
				$typeStrArr	= explode('-',$typeStr);
				$typeStr	= $typeStrArr[0];
				if (count($typeStrArr) == 3){
					$this->refType		= $typeStrArr[1];
					$this->refContent	= Str::RegExp(urldecode($typeStrArr[2]),'sql+ ');
				}else{
					if (strpos('|user|topic|','|'. $typeStr .'|') !== false){
						$this->markName	= intval($typeStrArr[1]);
					}else{
						$this->markName	= Str::RegExp(urldecode($typeStrArr[1]),'sql');
					}
				}
			}
		}
		if (count($listArr) == 2){ $this->page = intval($listArr[1]); }

		switch ($typeStr){
			case 'announ':
				$this->webTitle	= str_replace('{%栏目名称%}',$this->sysArr['SYS_announName'],$this->sysArr['SYS_titleList']);
				$this->pointStr	= '&ensp;&gt;&ensp;'. $this->sysArr['SYS_announName'];
				$this->areaName	= $this->sysArr['SYS_announName'];
				$this->webKey	= $this->sysArr['SYS_announName'] .','. $this->sysArr['SYS_webKey'];
				$this->webDesc	= $this->sysArr['SYS_announName'] .'列表；'. $this->sysArr['SYS_webDesc'];
				$this->wapUrl	= Url::ListStr($typeStr, 0, $this->sysArr['SYS_wapUrl']);
				$IT_showMode	= $this->tplSysArr['TS_announListMode'];
				$IT_showNum		= $this->tplSysArr['TS_homeAnnounListNum'];
				break;

			case 'new':
				$this->webTitle	= str_replace('{%栏目名称%}','最新更新',$this->sysArr['SYS_titleList']);
				$this->pointStr	= '&ensp;&gt;&ensp;最新更新';
				$this->areaName	= '最新更新';
				$this->webKey	= '最新文章,'. $this->sysArr['SYS_webKey'];
				$this->webDesc	= $this->sysArr['SYS_webDesc'];
				$this->wapUrl	= Url::ListStr($typeStr, 0, $this->sysArr['SYS_wapUrl']);
				$IT_showMode	= $this->tplSysArr['TS_newListMode'];
				$IT_showNum		= $this->tplSysArr['TS_homeNewListNum'];
				break;

			case 'refer':
				$searchBadArr = explode('|', $this->tplSysArr['TS_searchBadStr']);
				foreach ($searchBadArr as $searchWord){
					if (strlen($searchWord) > 0){
						if (strpos($this->refContent,$searchWord) !== false){
							header('HTTP/1.0 404 Not Found');
							header('Status: 404 Not Found');
							JS::AlertHrefEnd('该搜索页不存在或已被屏蔽。',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
						}
					}
				}

				switch ($this->refType){
					case 'content':		$refTypeCN = '正文';	break;
					case 'source':		$refTypeCN = '来源';	break;
					case 'writer':		$refTypeCN = '作者';	break;
					default :			$refTypeCN = '标题';	break;
				}
				$this->webTitle	= str_replace(array('{%搜索类型%}', '{%搜索词%}'), array($refTypeCN, $this->refContent), $this->sysArr['SYS_titleSearch']);
				$this->pointStr	= '&ensp;&gt;&ensp;搜索结果('. $refTypeCN .')：'. $this->refContent .'';
				$this->areaName	= '搜索结果';
				$this->webKey	= $this->refContent .','. $refTypeCN .',搜索,'. $this->sysArr['SYS_webKey'];
				$this->webDesc	= '搜索'. $refTypeCN .'含有“'. $this->refContent .'”关键词的文章；'. $this->sysArr['SYS_webDesc'];
				$this->wapUrl	= Url::ListStr($typeStr .'-'. $this->refType .'-'. urlencode($this->refContent), 0, $this->sysArr['SYS_wapUrl']);
				$IT_showMode	= $this->tplSysArr['TS_searchListMode'];
				$IT_showNum		= $this->tplSysArr['TS_searchListNum'];
				break;

			case 'mark':
				$markBadArr = explode('|', $this->tplSysArr['TS_markBadStr']);
				foreach ($markBadArr as $markWord){
					if (strlen($markWord) > 0){
						if (strpos($this->markName,$markWord) !== false){
							header('HTTP/1.0 404 Not Found');
							header('Status: 404 Not Found');
							JS::AlertHrefEnd('该标签页不存在或已被屏蔽。',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
						}
					}
				}

				$this->webTitle	= str_replace('{%标签%}',$this->markName,$this->sysArr['SYS_titleMark']);
				$this->pointStr	= '&ensp;&gt;&ensp;标签：'. $this->markName .'';
				$this->areaName	= '标签匹配';
				$this->webKey	= $this->markName .','. $this->sysArr['SYS_webKey'];
				$this->webDesc	= '匹配含有标签“'. $this->markName .'”的文章；'. $this->sysArr['SYS_webDesc'];
				$this->wapUrl	= Url::ListStr($typeStr .'-'. urlencode($this->markName), 0, $this->sysArr['SYS_wapUrl']);
				$IT_showMode	= $this->tplSysArr['TS_markListMode'];
				$IT_showNum		= $this->tplSysArr['TS_markListNum'];

				if ($this->tplSysArr['TS_isMark'] == 2){
					$this->metaTagStr .= '<meta name="robots" content="noindex">';	// 附加不被搜索引擎收录meta标签
				}
				break;

			case 'user':
				$this->markName = intval($this->markName);
				$refUserexe=$DB->query('select UE_username from '. OT_dbPref .'users where UE_ID='. $this->markName);
				if (! $row = $refUserexe->fetch()){ $refUserName = ''; }else{ $refUserName = $row['UE_username']; }
				unset($refUserexe);
				if ($this->markName == 0){ $userMark = '会员文章列表'; }else{ $userMark = '会员：'. $refUserName .'_文章列表'; }
				$this->webTitle	= $userMark .'{%页码%}_{%网站标题%}';
				$this->pointStr	= '&ensp;&gt;&ensp;'. $userMark;
				$this->areaName	= '会员文章';
				$this->webKey	= $this->markName .','. $this->sysArr['SYS_webKey'];
				$this->webDesc	= $userMark .'；'. $this->sysArr['SYS_webDesc'];
				$this->wapUrl	= Url::ListStr($typeStr .'-'. $this->markName, 0, $this->sysArr['SYS_wapUrl']);
				$IT_showMode	= $this->tplSysArr['TS_userListMode'];
				$IT_showNum		= $this->tplSysArr['TS_userListNum'];
				break;

			case 'topic':
				$refTopicexe=$DB->query('select IW_theme,IW_isTitle,IW_titleAddi,IW_webKey,IW_webDesc,IW_template,IW_openMode,IW_listMode,IW_pageNum,IW_state from '. OT_dbPref .'infoWeb where IW_ID='. intval($this->markName));
				if (! $row = $refTopicexe->fetch()){
					header('HTTP/1.0 404 Not Found');
					header('Status: 404 Not Found');
					JS::AlertHrefEnd('专题不存在',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
				}else{
					if ($row['IW_isTitle'] == 1){
						$this->webTitle	= $row['IW_titleAddi'];
					}else{
						$this->webTitle	= str_replace(array('{%专题%}','{%专题附加%}'), array($row['IW_theme'], $row['IW_titleAddi']), $this->sysArr['SYS_titleTopic']);
					}
					$this->tplFileName	= $row['IW_template'];
					$this->pointStr		= '&ensp;&gt;&ensp;专题：'. $row['IW_theme'] .'';
					$this->areaName		= '专题：'. $row['IW_theme'] .'';
					$this->webKey		= $row['IW_webKey'];
					$this->webDesc		= $row['IW_webDesc'];
					$this->wapUrl		= Url::ListTypeID($typeStr, $this->markName, 0, $this->sysArr['SYS_wapUrl']);
					$IT_showMode		= $row['IW_listMode'];
					$IT_showNum			= $row['IW_pageNum'];
				}
				unset($refTopicexe);
				break;

			default :
				$this->webTitle	= str_replace('{%栏目名称%}','文章栏目',$this->sysArr['SYS_titleList']);
				$this->areaName	= '文章栏目';
				$typeStr		= $this->typeID;
				$this->pointStr	= '';
				$type1exe = $DB->query('select IT_ID,IT_level,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_fatID,IT_openMode,IT_listInfo,IT_subNewNum,IT_subRecomNum,IT_subHotNum,IT_showMode,IT_showNum,IT_isTitle,IT_titleAddi,IT_template,IT_webKey,IT_webDesc,IT_isRightMenu,IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $this->typeID);
				if (! $row = $type1exe->fetch()){
					header('HTTP/1.0 404 Not Found');
					header('Status: 404 Not Found');
					JS::AlertHrefEnd('栏目不存在',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
				}else{
					$this->tplFileName		= $row['IT_template'];
					$this->webTitleAddi		= $row['IT_titleAddi'];
					$this->webKey			= $row['IT_webKey'];
					$this->webDesc			= $row['IT_webDesc'];
					$this->wapUrl			= Url::ListID('', $row['IT_htmlName'], $this->typeID, 0, $this->sysArr['SYS_wapUrl']);
					$this->rightMenuID		= $row['IT_ID'];
					$this->rightMenuName	= $row['IT_theme'];
					$this->rightNewNum		= $row['IT_subNewNum'];
					$this->rightRecomNum	= $row['IT_subRecomNum'];
					$this->rightHotNum		= $row['IT_subHotNum'];
					$this->itemLevel		= $row['IT_level'];
					$this->isRightMenu		= $row['IT_isRightMenu'];
					$IT_theme				= $row['IT_theme'];
					$IT_listInfo			= $row['IT_listInfo'];
					$IT_showMode			= $row['IT_showMode'];
					$IT_showNum				= $row['IT_showNum'];
					$IT_htmlName			= $row['IT_htmlName'];
					$type1URL = Area::InfoTypeUrl(array(
						'IT_mode'		=> $row['IT_mode'],
						'IT_ID'			=> $row['IT_ID'],
						'IT_webID'		=> $row['IT_webID'],
						'IT_URL'		=> $row['IT_URL'],
						'IT_isEncUrl'	=> $row['IT_isEncUrl'],
						'IT_htmlName'	=> $row['IT_htmlName'],
						'mainUrl'		=> '',
						'webPathPart'	=> $this->webPathPart,
						));
					$this->pointStr .= '&ensp;&gt;&ensp;<a href="'. $type1URL .'" target="'. $row['IT_openMode'] .'">'. $row['IT_theme'] .'</a>';

					if ($row['IT_fatID']>0){
						$type2exe = $DB->query('select IT_ID,IT_level,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_titleAddi,IT_isRightMenu,IT_htmlName from '. OT_dbPref .'infoType where IT_ID='. $row['IT_fatID']);
						if	($row2 = $type2exe->fetch()){
							if ($this->webTitleAddi == ''){ $this->webTitleAddi = $row2['IT_titleAddi']; }
							if ($this->itemLevel == 0){ $this->itemLevel	= $row2['IT_level']; }
							$this->rightMenuID		= $row2['IT_ID'];
							$this->rightMenuName	= $row2['IT_theme'];
							$this->isRightMenu		= $row2['IT_isRightMenu'];
							$IT_theme2				= $this->sysArr['SYS_titleSign'] . $row2['IT_theme'];
							$type2URL = Area::InfoTypeUrl(array(
								'IT_mode'		=> $row2['IT_mode'],
								'IT_ID'			=> $row2['IT_ID'],
								'IT_webID'		=> $row2['IT_webID'],
								'IT_URL'		=> $row2['IT_URL'],
								'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
								'IT_htmlName'	=> $row2['IT_htmlName'],
								'mainUrl'		=> '',
								'webPathPart'	=> $this->webPathPart,
								));
							$this->pointStr = '&ensp;&gt;&ensp;<a href="'. $type2URL .'" target="'. $row2['IT_openMode'] .'">'. $row2['IT_theme'] .'</a>'. $this->pointStr;

							/* $hrefStr = '';
							if ($row2['IT_mode'] == 'web'){
								$hrefStr = Url::WebID($row2['IT_webID']);
							}elseif ($row2['IT_mode'] == 'topic'){
								$hrefStr = Url::ListTypeID('topic',$row2['IT_webID']);
							}elseif (substr($row2['IT_mode'],0,3) == 'url'){
								$hrefStr = Url::ListUrl($row2['IT_mode'],$row2['IT_URL'],$row2['IT_isEncUrl'],$row2['IT_ID'],$this->webPathPart);
								if (strlen($hrefStr) == 0){ $hrefStr='./'; }
							}
							if (strlen($hrefStr)>1){
								JS::HrefEnd($hrefStr);
							} */
						}
						unset($type2exe);

					}else{
						$hrefStr = '';
						if ($row['IT_mode'] == 'web'){
							$hrefStr = Url::WebID($row['IT_webID']);
						}elseif ($row['IT_mode'] == 'topic'){
							$hrefStr = Url::ListTypeID('topic',$row['IT_webID']);
						}elseif (substr($row['IT_mode'],0,3) == 'url'){
							$hrefStr = Url::ListUrl($row['IT_mode'],$row['IT_URL'],$row['IT_isEncUrl'],$row['IT_ID'],$this->webPathPart);
							if (strlen($hrefStr) == 0){ $hrefStr='./'; }
						}
						if (strlen($hrefStr)>1){
							JS::HrefEnd($hrefStr);
						}

					}
					if ($row['IT_isTitle'] == 1){
						$this->webTitle = $row['IT_titleAddi'];
					}else{
						$this->webTitle = str_replace('{%栏目名称%}',$IT_theme,$this->sysArr['SYS_titleList']);
					}
					$this->areaName = $row['IT_theme'];
				}
				unset($type1exe);
				break;
		}

		if ($this->page>1){ $pageTitle=' - 第'. $this->page .'页 '; }else{ $pageTitle=''; }
		$this->webTitle = str_replace(array('{%页码%}','{%父栏目名称%}','{%栏目名称附加%}'), array($pageTitle,$IT_theme2,$this->webTitleAddi), $this->webTitle);

		if ($this->sysArr['SYS_htmlUrlJump'] == 1 && $this->isJump){
			if (($this->sysArr['SYS_newsListUrlMode'] != $this->urlMode || strpos($_SERVER['SCRIPT_NAME'] .'?',$this->fileName) === false || ($this->sysArr['SYS_newsListUrlMode'] == 'static-3.x' && strpos($_SERVER['QUERY_STRING'],'&static') === false)) && strpos($_SERVER['QUERY_STRING'],'rnd=') === false){
				if (! (strpos('|refer|mark|user|topic|',$typeStr) !== false && (strpos('|dyn-2.x|html-2.x|static-3.x|',$this->sysArr['SYS_newsListUrlMode']) !== false || $this->sysArr['SYS_htmlUrlSel']>=2))){
					if ($typeStr == 'refer'){
						$retHref = Url::ListRefMark('refer',$this->refType,$this->refContent);
					}elseif ($typeStr == 'mark'){
						$retHref = Url::ListRefMark('mark',$this->markName,'');
					}elseif (is_numeric($typeStr)){
						$retHref = Url::ListID('',$IT_htmlName,$this->typeID);
					}else{
						$retHref = Url::ListStr($typeStr);
					}
					if (strlen($this->webPathPart)>=2){ $retHref=str_replace($this->webPathPart,$this->jsPathPart,$retHref); }
					// header('HTTP/1.1 301 Moved Permanently');
					header('Location: '. $retHref, true, 301);
					die();
				}
			}
		}

		$this->Add('itemID',		$this->typeID);		// 栏目ID
		$this->Add('itemType',		$typeStr);			// 栏目类别
		$this->Add('itemName',		$this->areaName);	// 栏目名称
		$this->Add('itemListInfo',	$IT_listInfo);		// 列表页文章信息是否显示
		$this->Add('itemMode',		$IT_showMode);		// 栏目模式
		$this->Add('itemNum',		$IT_showNum);		// 栏目数量
		$this->Add('itemTypeStr',	$typeStr);			// 栏目类别集
		$this->Add('itemLevel',		$this->itemLevel);	// 栏目等级
		$this->Add('webDataID',		$this->typeID);		// 当前页面记录ID
	}



	// 解析次页右侧
	public function WebSubRight(){
		if ($this->typeID == 0){ $rightItemID=''; }else{ $rightItemID=$this->typeID; }
		if ($this->typeID == -1){ $this->itemLevel = 1; }

		$this->Add('rightItemID',		$rightItemID);			// 右侧栏目ID
		$this->Add('isRightMenu',		$this->isRightMenu);	// 是否开启右侧导航菜单
		$this->Add('rightMenuItemID',	$this->rightMenuID);	// 右侧导航菜单ID
		$this->Add('rightMenuItemName',	$this->rightMenuName);	// 右侧导航菜单名称
		$this->Add('rightItemNewNum',	$this->rightNewNum);	// 右侧最新文章数
		$this->Add('rightItemRecomNum',	$this->rightRecomNum);	// 右侧推荐文章数
		$this->Add('rightItemHotNum',	$this->rightHotNum);	// 右侧热门文章数
		$this->Add('rightItemLevel',	$this->itemLevel);		// 右侧栏目等级
	}



	// 解析单篇页配置
	public function WebWeb(){
		global $DB;

		$webStr	= substr($this->queryStr,4);
		$webArr	= explode('_',$webStr);

		if (is_numeric($webArr[0])){ $dataID = intval($webArr[0]); }else{ $dataID = 0; }
		if (count($webArr) == 2){ $page = intval($webArr[1]); }else{ $page = 0; }

		$webexe=$DB->query('select * from '. OT_dbPref .'infoWeb where IW_ID='. $dataID);
			if (! $row = $webexe->fetch()){
				header('HTTP/1.0 404 Not Found');
				header('Status: 404 Not Found');
				JS::AlertHrefEnd('该记录不存在.',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
			}
			$this->tplFileName	= $row['IW_template'];
			$IW_titleAddi		= $row['IW_titleAddi'];
			$IW_webKey			= $row['IW_webKey'];
			$IW_webDesc			= $row['IW_webDesc'];
			$IW_theme			= $row['IW_theme'];
			$IW_content			= $row['IW_content'];
			if ($row['IW_isTitle'] == 1){
				$this->webTitle	= $row['IW_titleAddi'];
			}else{
				$this->webTitle	= str_replace(array('{%标题%}','{%标题附加%}'), array($IW_theme,$IW_titleAddi), $this->sysArr['SYS_titleWeb']);
			}
		unset($webexe);

		$this->pointStr		= '&ensp;&gt;&ensp;'. $IW_theme;
		$this->webDataID	= $dataID;
		$this->page			= $page;
		$this->webTitleAddi	= $IW_titleAddi;
		$this->webKey		= $IW_webKey;
		$this->webDesc		= $IW_webDesc;
		$this->areaName		= $IW_theme;
		$this->wapUrl		= Url::WebID($dataID, 0, $this->sysArr['SYS_wapUrl']);

		$IW_content	= Content::CloseTags(Area::AddImgAlt($IW_content,$IW_theme));
		if ($this->webPathPart != '../'){ $IW_content	= str_replace(InfoImgAdminDir,$this->webPathPart . InfoImgDir,$IW_content); }

		if ($this->sysArr['SYS_htmlUrlJump'] == 1 && $this->isJump){
			if (($this->sysArr['SYS_dynWebUrlMode'] != $this->urlMode || strpos($_SERVER['SCRIPT_NAME'] .'?',$this->fileName) === false || ($this->sysArr['SYS_dynWebUrlMode'] == 'static-3.x' && strpos($_SERVER['QUERY_STRING'],'&static') === false)) && strpos($_SERVER['QUERY_STRING'],'rnd=') === false){
				$retHref=Url::WebID($dataID);
				if (strlen($this->webPathPart)>=2){ $retHref=str_replace($this->webPathPart,$this->jsPathPart,$retHref); }
				// header('HTTP/1.1 301 Moved Permanently');
				header('Location: '. $retHref, true, 301);
				die();
			}
		}

		$this->Add('areaName',		$this->areaName);	// 单篇页标题
		$this->Add('webContent',	$IW_content);		// 单篇页内容
		$this->Add('webDataID',		$dataID);			// 当前页面记录ID
	}

	// 解析内容页配置
	public function WebNews(){
		global $DB,$infoSysArr;

		$showArr = explode('_',$this->queryStr);

		$dataID = intval($showArr[0]);
		if (count($showArr) == 2){ $page = intval($showArr[1]); }else{ $page=1; }
		$this->webDataID	= $dataID;
		$this->page			= $page;

		$infoexe = $DB->query('select IF_type,IF_isOri,IF_theme,IF_img,IF_URL,IF_isEncUrl,IF_time,IF_template,IF_type1ID,IF_type2ID,IF_type3ID,IF_writer,IF_source,IF_isTitle,IF_titleAddi,IF_themeKey,IF_themeKeyIdStr,IF_prevNewsId,IF_nextNewsId,IF_contentKey,IF_tabID,IF_content,IF_pageNum,IF_fileName,IF_file,IF_fileStr,IF_voteMode,IF_isMarkNews,IF_isReply,IF_topicID,IF_topAddiID,IF_addiID,IF_readNum,IF_replyNum,IF_isCheckUser,IF_userID,IF_isAudit,IF_state,IF_infoTypeDir,IF_datetimeDir,IF_isEnc,IF_mediaFile,IF_mediaEvent,IF_addition from '. OT_dbPref .'info where IF_ID='. $dataID);
			if (! $row = $infoexe->fetch()){
				header('HTTP/1.0 404 Not Found');
				header('Status: 404 Not Found');
				JS::AlertHrefEnd('搜索不到相关文章',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
			}
			if ($row['IF_state'] == 0){
				header('HTTP/1.0 404 Not Found');
				header('Status: 404 Not Found');
				JS::AlertHrefEnd('该文章已关闭',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
			}
			if (($row['IF_isAudit']==0 || $row['IF_isAudit']==2) && (empty($_SESSION[OT_SiteID .'userID']) || $_SESSION[OT_SiteID .'userID']!=$row['IF_userID']) && strlen($_SESSION[OT_SiteID .'memberUsername'])==0){
				header('HTTP/1.1 402 Payment Required');
				header('Status: 402 Payment Required');
				JS::AlertHrefEnd('该文章未审核，待审核通过后才能预览',$this->dbPathPart . $this->tplSysArr['TS_subWeb404']);
			}
			if ($row['IF_isAudit']==0 || $row['IF_isAudit']==2){ $judMakeCache='false'; }

			if ($row['IF_isTitle']==1){
				$webTitle	= $row['IF_titleAddi'];
			}else{
				$webTitle	= str_replace('{%文章标题%}',$row['IF_theme'],$this->sysArr['SYS_titleShow']);
			}
			$this->tplFileName	= $row['IF_template'];
			$this->pointStr		= '';
			$type1ID			= $row['IF_type1ID'];
			$type2ID			= $row['IF_type2ID'];
	//		$type3ID			= $row['IF_type3ID'];
			$this->rightMenuID	= $type1ID;
			$this->areaName		= '';
			$itemName = $itemName2 = '';
			if ($type1ID == -1){
				$this->areaName = $this->sysArr['SYS_announName'];
				$webTitle = str_replace('{%栏目名称%}',$this->sysArr['SYS_announName'],$webTitle);
				$this->pointStr = '&ensp;&gt;&ensp;<a href="'. Url::ListStr('announ',0) .'">'. $this->sysArr['SYS_announName'] .'</a>'. $this->pointStr;
			}else{
				if ($type2ID>0){
					$type2exe = $DB->query('select IT_ID,IT_level,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_htmlName,IT_subNewNum,IT_subRecomNum,IT_subHotNum from '. OT_dbPref .'infoType where IT_ID='. $type2ID);
						if ($row2 = $type2exe->fetch()){
							$this->rightNewNum = $row2['IT_subNewNum'];
							$this->rightRecomNum = $row2['IT_subRecomNum'];
							$this->rightHotNum = $row2['IT_subHotNum'];
							$this->itemLevel = $row2['IT_level'];
							$this->areaName = $row2['IT_theme'];
							$itemName = $this->areaName;
							$type2URL = Area::InfoTypeUrl(array(
								'IT_mode'		=> $row2['IT_mode'],
								'IT_ID'			=> $row2['IT_ID'],
								'IT_webID'		=> $row2['IT_webID'],
								'IT_URL'		=> $row2['IT_URL'],
								'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
								'IT_htmlName'	=> $row2['IT_htmlName'],
								'mainUrl'		=> '',
								'webPathPart'	=> $this->webPathPart,
								));
							$this->pointStr .= '&ensp;&gt;&ensp;<a href="'. $type2URL .'" target="'. $row2['IT_openMode'] .'">'. $row2['IT_theme'] .'</a>';
						}
					unset($type2exe);
				}
				if ($type1ID>0){
					$type1exe = $DB->query('select IT_ID,IT_level,IT_theme,IT_mode,IT_webID,IT_URL,IT_isEncUrl,IT_openMode,IT_isRightMenu,IT_htmlName,IT_subNewNum,IT_subRecomNum,IT_subHotNum from '. OT_dbPref .'infoType where IT_ID='. $type1ID);
						if ($row2 = $type1exe->fetch()){
							$this->rightMenuName	= $row2['IT_theme'];
							$this->isRightMenu		= $row2['IT_isRightMenu'];
							if ($this->areaName == ''){ $this->areaName=$row2['IT_theme']; }
//							if (strlen($itemName)>0){ $itemName .= $this->sysArr['SYS_titleSign'] . $row2['IT_theme']; }else{ $itemName=$row2['IT_theme']; }
							if ($type2ID>0){
								$itemName2	= $this->sysArr['SYS_titleSign'] . $row2['IT_theme'];
							}else{
								$this->rightNewNum = $row2['IT_subNewNum'];
								$this->rightRecomNum = $row2['IT_subRecomNum'];
								$this->rightHotNum = $row2['IT_subHotNum'];
								$itemName	= $row2['IT_theme'];
								$itemName2	= '';
							}
							if ($this->itemLevel==0){ $this->itemLevel = $row2['IT_level']; }
							$type1URL = Area::InfoTypeUrl(array(
								'IT_mode'		=> $row2['IT_mode'],
								'IT_ID'			=> $row2['IT_ID'],
								'IT_webID'		=> $row2['IT_webID'],
								'IT_URL'		=> $row2['IT_URL'],
								'IT_isEncUrl'	=> $row2['IT_isEncUrl'],
								'IT_htmlName'	=> $row2['IT_htmlName'],
								'mainUrl'		=> '',
								'webPathPart'	=> $this->webPathPart,
								));
							$this->pointStr = '&ensp;&gt;&ensp;<a href="'. $type1URL .'" target="'. $row2['IT_openMode'] .'">'. $row2['IT_theme'] .'</a>'. $this->pointStr;
						}
					unset($type1exe);
				}
				$webTitle = str_replace('{%栏目名称%}',$itemName,$webTitle);
				if ($this->areaName==''){ $this->areaName='文章栏目'; }
				if ($this->pointStr==''){ $this->pointStr='&ensp;'; }
			}

			$IF_type			= $row['IF_type'];
			$IF_isOri			= $row['IF_isOri'];
			$IF_theme			= $row['IF_theme'];
			$IF_img				= $row['IF_img'];
			$IF_writer			= $row['IF_writer'];
			$IF_source			= $row['IF_source'];
			$IF_time			= $row['IF_time'];
			$IF_titleAddi		= $row['IF_titleAddi'];
			$IF_themeKey		= $row['IF_themeKey'];
			$IF_themeKeyIdStr	= $row['IF_themeKeyIdStr'];
			$IF_prevNewsId		= $row['IF_prevNewsId'];
			$IF_nextNewsId		= $row['IF_nextNewsId'];
			$IF_contentKey		= $row['IF_contentKey'];
			if ($row['IF_tabID'] > 0){
				$IF_content		= Area::GetTabContent($row['IF_tabID'], $dataID);
			}else{
				$IF_content		= $row['IF_content'];
			}
			$IF_pageNum			= $row['IF_pageNum'];
			$IF_fileName		= $row['IF_fileName'];
			$IF_file			= $row['IF_file'];
			$IF_fileStr			= $row['IF_fileStr'];
			$IF_voteMode		= $row['IF_voteMode'];
			$IF_isMarkNews		= $row['IF_isMarkNews'];
			$IF_isReply			= $row['IF_isReply'];
			$IF_topicID			= $row['IF_topicID'];
			$IF_topAddiID		= $row['IF_topAddiID'];
			$IF_addiID			= $row['IF_addiID'];
			$IF_readNum			= $row['IF_readNum'];
			$IF_replyNum		= $row['IF_replyNum'];
			$IF_isCheckUser		= $row['IF_isCheckUser'];
			$IF_infoTypeDir		= $row['IF_infoTypeDir'];
			$IF_datetimeDir		= $row['IF_datetimeDir'];
			$IF_URL				= $row['IF_URL'];
			$IF_isEncUrl		= $row['IF_isEncUrl'];
			$IF_isEnc			= $row['IF_isEnc'];
			$IF_mediaFile		= $row['IF_mediaFile'];
			$IF_mediaEvent		= $row['IF_mediaEvent'];
			$IF_addition		= $row['IF_addition'];
			$IF_userID			= $row['IF_userID'];
		unset($infoexe);

		$pcUrlDir = GetUrl::CurrDir2($this->dbPathPart);

		$this->webKey	= $IF_themeKey;
		$this->webDesc	= $IF_contentKey;
		$this->pcUrl	= Url::NewsID($IF_infoTypeDir, $IF_datetimeDir, $dataID, 0, $pcUrlDir);
		$this->wapUrl	= Url::NewsID($IF_infoTypeDir, $IF_datetimeDir, $dataID, 0, $this->sysArr['SYS_wapUrl']);

		$content = '';
		if (strlen($IF_URL)>5){
			$content = '<script language="javascript" type="text/javascript">document.location.href="'. Url::NewsUrl($IF_URL,$IF_isEncUrl,$dataID,$this->webPathPart) .'";</script>';
		}else{
			if ($IF_isCheckUser==0 || ($IF_isCheckUser>0 && $IF_isEnc==1)){
				if ($IF_pageNum>0){
					$content	= Content::PageNum($IF_content,$IF_infoTypeDir,$IF_datetimeDir,$dataID,$IF_pageNum,$page,$this->webPathPart);
				}else{
					$content	= Content::PageSign($IF_content,$IF_infoTypeDir,$IF_datetimeDir,$dataID,$page,$this->webPathPart);
				}

				$wordNum = 0;
				$wordexe = $DB->query('select KW_theme,KW_themeStyle,KW_URL,KW_useNum from '. OT_dbPref .'keyWord where KW_isUse=1 order by KW_rank ASC');
					while ($row = $wordexe->fetch()){
						if (strpos($content,$row['KW_theme']) !== false){
							$wordNum ++;
							if ($row['KW_useNum']>0){
								$content = Str::ReplaceSkipMark($content,$row['KW_theme'],'<a href="'. $row['KW_URL'] .'" class="keyWord" style="'. $row['KW_themeStyle'] .'" target="_blank"><strong style="'. $row['KW_themeStyle'] .'">'. $row['KW_theme'] .'</strong></a>',$row['KW_useNum']);
							}else{
								$content = Str::ReplaceSkipMark($content,$row['KW_theme'],'<a href="'. $row['KW_URL'] .'" class="keyWord" style="'. $row['KW_themeStyle'] .'" target="_blank"><strong style="'. $row['KW_themeStyle'] .'">'. $row['KW_theme'] .'</strong></a>',-1);
							}
							if ($infoSysArr['IS_keyWordNum']>0 && $wordNum>=$infoSysArr['IS_keyWordNum']){ break; }
						}
					}
				unset($wordexe);

				if ($this->isAppTaoke){
					$content = AppTaobaokeDeal::WordContent($content);
				}

				$content = Content::CloseTags(Area::AddImgAlt($content,$IF_theme));
				if ($this->webPathPart != '../'){ $content = str_replace(InfoImgAdminDir,$this->webPathPart . InfoImgDir,$content); }

				if (! ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encMediaFile|')!==false)){
					if ($page < 2){
						$mediaCode = AppVideo::GetMediaCode($IF_mediaFile);
						if (strpos($IF_addition,'|topMediaFile|')!==false){
							$content = $mediaCode . $content;
						}else{
							$content .= $mediaCode;
						}
					}
				}

				$content .= '<div id="newsEncCont"></div>';

				if (! ($IF_isCheckUser>0 && $IF_isEnc==1 && strpos($IF_addition,'|encFile|')!==false)){
					$fileCode = Area::InfoFile($dataID, $IF_file, $IF_fileName, $IF_fileStr, $infoSysArr['IS_fileStyle'], $this->webPathPart);
					$content .= $fileCode;
				}
			}
		}

		if ($IF_topicID > 0){
			$topicexe = $DB->query('select IW_theme from '. OT_dbPref .'infoWeb where IW_ID='. $IF_topicID ." and IW_type='topic'");
			if ($row = $topicexe->fetch()){
				$content .= '<div class="topicBox">该文章所属专题：<a href="'. Url::ListTypeID('topic',$IF_topicID,0) .'" target="_blank" title="'. $row['IW_theme'] .'" class="href">'. $row['IW_theme'] .'</a></div>';
			}
			unset($topicexe);
		}

		$PrevNextWhereStr = '';
		if ($type2ID>0){
			$this->typeID = $type2ID;
			$PrevNextWhereStr = ' and IF_type2ID='. $this->typeID .'';
		}elseif ($type1ID>0 || $type1ID==-1){
			$this->typeID = $type1ID;
			$PrevNextWhereStr = ' and IF_type1ID='. $this->typeID .'';
		}

		if ($this->page>1){
			$IF_theme .= '('. $this->page .')';
			$pageTitle = ' - 第'. $this->page .'页 ';
		}else{
			$pageTitle = '';
		}
		$this->webTitle = str_replace(array('{%页码%}','{%父栏目名称%}','{%文章标题附加%}'), array($pageTitle,$itemName2,$IF_titleAddi), $webTitle);

		if ($this->sysArr['SYS_htmlUrlJump'] == 1 && $this->isJump){
			if (($this->sysArr['SYS_newsShowUrlMode'] != $this->urlMode || strpos($_SERVER['SCRIPT_NAME'] .'?',$this->fileName) === false || ($this->sysArr['SYS_newsShowUrlMode'] == 'static-3.x' && strpos($_SERVER['QUERY_STRING'],'&static') === false)) && strpos($_SERVER['QUERY_STRING'],'rnd=') === false){
				$retHref=Url::NewsID($IF_infoTypeDir,$IF_datetimeDir,$dataID);
				if (strlen($this->webPathPart)>=2){ $retHref=str_replace($this->webPathPart,$this->jsPathPart,$retHref); }
				// header('HTTP/1.1 301 Moved Permanently');
				header('Location: '. $retHref, true, 301);
				die();
			}
		}

		$prevNextCont = '';
		$prevWeb = '';
		$nextWeb = '';
		$prevId	= 0;
		$nextId	= 0;
		if ($infoSysArr['IS_prevAndNext']>0){
			$newWeb = '';
			$oldWeb = '';
			$nextPageWeb = '';
			$prevPageWeb = '';
			if ($infoSysArr['IS_isSavePrevNextId']==1 && $IF_prevNewsId>0){
				$checkexe = $DB->query('select IF_ID,IF_theme,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_ID='. $IF_prevNewsId .' and IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr .'');
			}else{
				$checkexe = $DB->query('select IF_ID,IF_theme,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. $PrevNextWhereStr .' and IF_time>='. $DB->ForTime(TimeDate::Add('s',1,$IF_time)) . OT_TimeInfoWhereStr .' order by IF_time ASC');
			}
				if (! $row = $checkexe->fetch()){
					$newWeb = '没有了';
				}else{
					$prevId = $row['IF_ID'];
					if (strlen($row['IF_URL']) > 0){
						$webURL = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$this->webPathPart);
					}else{
						$webURL = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
					}
					$newWeb = '<a href="'. $webURL .'">'. $row['IF_theme'] .'</a>';
					if ($infoSysArr['IS_prevNextArrow']==1){ $nextPageWeb = '<a class="pageNext" href="'. $webURL .'" title="'. $row['IF_theme'] .'"></a>'; }
				}
			$checkexe = null;

			if ($infoSysArr['IS_isSavePrevNextId']==1 && $IF_nextNewsId>0){
				$checkexe = $DB->query('select IF_ID,IF_theme,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_ID='. $IF_nextNewsId .' and IF_state=1 and IF_isAudit=1'. OT_TimeInfoWhereStr .'');
			}else{
				$checkexe = $DB->query('select IF_ID,IF_theme,IF_URL,IF_isEncUrl,IF_infoTypeDir,IF_datetimeDir from '. OT_dbPref .'info where IF_state=1 and IF_isAudit=1'. $PrevNextWhereStr .' and IF_time<='. $DB->ForTime(TimeDate::Add('s',-1,$IF_time)) .OT_TimeInfoWhereStr .' order by IF_time DESC');
			}
				if (! $row = $checkexe->fetch()){
					$oldWeb = '没有了';
				}else{
					$nextId = $row['IF_ID'];
					if (strlen($row['IF_URL']) > 0){
						$webURL = Url::NewsUrl($row['IF_URL'],$row['IF_isEncUrl'],$row['IF_ID'],$this->webPathPart);
					}else{
						$webURL = Url::NewsID($row['IF_infoTypeDir'],$row['IF_datetimeDir'],$row['IF_ID']);
					}
					$oldWeb = '<a href="'. $webURL .'">'. $row['IF_theme'] .'</a>';
					if ($infoSysArr['IS_prevNextArrow']==1){ $prevPageWeb = '<a class="pagePrev" href="'. $webURL .'" title="'. $row['IF_theme'] .'"></a>'; }
				}
			unset($checkexe);

			if ($infoSysArr['IS_prevAndNext']==10){
				$prevWeb = $newWeb;
				$nextWeb = $oldWeb;
			}else{
				$prevWeb = $oldWeb;
				$nextWeb = $newWeb;
			}

			if ($infoSysArr['IS_isSavePrevNextId']==1 && ($IF_prevNewsId!=$prevId || $IF_nextNewsId!=$nextId)){
				$DB->query('update '. OT_dbPref .'info set IF_prevNewsId='. $prevId .',IF_nextNewsId='. $nextId .' where IF_ID='. $dataID);
			}

			$prevNextCont = $prevPageWeb . $nextPageWeb;
		}

		if ($IF_isOri == 1){
			$this->metaTagStr .= ''.
				'	<meta property="og:type" content="article"/>'. PHP_EOL .
				'	<meta property="article:published_time" content="'. TimeDate::Get("Y-m-dTH:i:s",$IF_time) .'+08:00' .'" />'. PHP_EOL .
				'	<meta property="article:author" content="'. $IF_writer .'" />'. PHP_EOL .
				'	<meta property="article:published_first" content="'. $IF_writer .', '. $this->pcUrl .'" />'. PHP_EOL .
				'';	// 增加原创标签
		}

		$imgStr = '';
		if (strlen($IF_img)>3){
			if (Is::AbsUrl($IF_img)){
				$imgStr = $IF_img;
			}else{
				$imgStr = $pcUrlDir . InfoImgDir . $IF_img;
			}
		}
		if ($infoSysArr['IS_is360meta'] == 1){
			// 360智能摘要
			$this->metaTagStr .= ''.
				'	<meta property="og:type" content="news" />'. PHP_EOL .
				'	<meta property="og:title" content="'. $IF_theme .'" />'. PHP_EOL .
				'	<meta property="og:description" content="'. $IF_contentKey .'" />'. PHP_EOL .
				'	<meta property="og:image" content="'. $imgStr .'" />'. PHP_EOL .
				'	<meta property="og:url" content="'. $this->pcUrl .'" />'. PHP_EOL .
				'	<meta property="og:release_date" content="'. $IF_time .'" />'. PHP_EOL .
				'';
		}
		if ($infoSysArr['IS_isXiongzhang'] == 1 && AppMapBaidu::Jud()){
			// 熊掌号主页展现
			$this->metaTagStr .= ''.
				'	<script type="application/ld+json">'. PHP_EOL .
				'	{'. PHP_EOL .
				'		"@context": "https://ziyuan.baidu.com/contexts/cambrian.jsonld",'. PHP_EOL .
				'		"@id": "'. $this->pcUrl .'",'. PHP_EOL .
				'		"appid": "'. $infoSysArr['IS_xiongzhangId'] .'",'. PHP_EOL .
				'		"title": "'. $IF_theme .'",'. PHP_EOL .
				'		"images": ['. (strlen($imgStr)>0 ? '"'. $imgStr .'"' : '') .'],'. PHP_EOL .
				'		"pubDate": "'. TimeDate::Get('Y-m-d\TH:i:s',$IF_time) .'"'. PHP_EOL .
				'	}'. PHP_EOL .
				'</script>'. PHP_EOL .
				'';
		}

		$paraTag = '
			<input type="hidden" id="dataType" name="dataType" value="'. $IF_type .'" />
			<input type="hidden" id="isReply" name="isReply" value="'. $IF_isReply .'" />
			<input type="hidden" id="infoID" name="infoID" value="'. $dataID .'" />
			<input type="hidden" id="isUserCheck" name="isUserCheck" value="'. $IF_isCheckUser .'" />
			<input type="hidden" id="isEnc" name="isEnc" value="'. $IF_isEnc .'" />
			<input type="hidden" id="voteMode" name="voteMode" value="'. $IF_voteMode .'" />
			<input type="hidden" id="pageValue" name="pageValue" value="'. $this->page .'" />
			'. AppVideo::JsCode('pc', $IF_mediaFile, $IF_mediaEvent, $IF_img, $pcUrlDir);

		$areaNewsTop1 = TplArea::AreaNewsTop1($dataID, $IF_topAddiID);
		$areaNewsBottom1 = TplArea::AreaNewsBottom1($dataID, $IF_addiID, $IF_userID);

		if ($this->sysArr['SYS_newsShowUrlMode']=='html-2.x' || $this->sysArr['SYS_htmlCacheMin']>0){ $isNoReturn=0; }else{ $isNoReturn=1; }

		$this->Add('infoSysArr',		$infoSysArr);			// 文章参数设置
		$this->Add('areaName',			$this->areaName);		// 内容页名称
		$this->Add('paraTag',			$paraTag);				// 标题下附加信息
		$this->Add('IF_type',			$IF_type);				// [废弃 为了兼容]信息类别
		$this->Add('IF_isCheckUser',	$IF_isCheckUser);		// [废弃 为了兼容]是否开启限制会员查看
		$this->Add('pageValue',			$this->page);			// [废弃 为了兼容]文章页码
		$this->Add('IF_isReply',		$IF_isReply);			// 是否开启回复
		$this->Add('IF_ID',				$dataID);				// 文章ID
		$this->Add('IF_voteMode',		$IF_voteMode);			// 投票模式
		$this->Add('IF_theme',			$IF_theme);				// 文章标题
		$this->Add('IF_time',			$IF_time);				// 文章时间
		$this->Add('IF_writer',			$IF_writer);			// 文章作者
		$this->Add('IF_source',			$IF_source);			// 文章来源
		$this->Add('IF_readNum',		$IF_readNum);			// 文章阅读数
		$this->Add('IF_replyNum',		$IF_replyNum);			// 文章评论数
		$this->Add('IF_themeKey',		$IF_themeKey);			// 文章关键词/标签
		$this->Add('IF_themeKeyIdStr',	$IF_themeKeyIdStr);		// 文章关键词/标签ID集
		$this->Add('IF_isMarkNews',		$IF_isMarkNews);		// 是否开启相关文章
		$this->Add('isNoReturn',		$isNoReturn);			// 是否反馈信息(针对纯静态动态更新阅读数/评论数)
		$this->Add('prevWeb',			$prevWeb);				// 上一篇
		$this->Add('nextWeb',			$nextWeb);				// 下一篇
		$this->Add('IF_contentKey',		$IF_contentKey);		// 文章内容摘要
		$this->Add('IF_content',		$content);				// 文章内容
		$this->Add('webDataID',			$dataID);				// 当前页面记录ID
		$this->Add('currUrl',			$this->pcUrl);			// 当前页面网址
		$this->Add('areaNewsTop1',		$areaNewsTop1);			// 正文头通用区域
		$this->Add('areaNewsBottom1',	$areaNewsBottom1 . $prevNextCont);	// 正文尾通用区域
	}



	// 解析留言页配置
	public function WebMessage(){
		$this->pointStr	= '&ensp;&gt;&ensp;'. $this->areaName;

		$this->Add('areaName',	$this->areaName);
	}


	
	// 解析商品列表页
	public function GoodsList(){
		AppTaobaoke::TplGoodsList();
	}

	// 解析商品详细页
	public function GoodsDet(){
		AppTaobaoke::TplGoodsDet();
	}



	// 解析论坛页
	public function BbsHome(){
		AppBbsTpl::WebList();
	}

	// 解析论坛列表页
	public function BbsList(){
		AppBbsTpl::WebList();
	}

	// 解析论坛详细页
	public function BbsShow(){
		AppBbsTpl::WebShow();
	}

}

// 注册自定义函数
function DiyFunction($params){
	global $systemArr,$tplSysArr;

	switch ($params['action']){
		case 'NavMenu':			// 页头导航
			return TplTop::NavMenu($params['num']);
	
		case 'NavBanner':		// 页头banner
			return TplTop::NavBanner();
	
		case 'MarInfoBox':		// 页头滚动信息
			return TplTop::MarInfoBox($params['width'],$params['num']);
	
		case 'BottomMenu':		// 底部菜单
			return TplBottom::BottomMenu();

		case 'LogoBox':			// 友情链接列表
			return TplBottom::LogoBox();

		case 'FlashBox':		// 幻灯片
			return TplIndex::FlashBox($params['mode'], $params['width'], $params['height'], $params['num']);

		case 'FontItem':		// 文字文章
			return TplIndex::FontItem($params['type'], $params['level'], $params['num'], $params['isDate'], $params['isType']);

		case 'ImgItem':			// 图文文章
			return TplIndex::ImgItem($params['type'], $params['level'], $params['num']);

		case 'RecomList':		// 推荐文章
			return TplIndex::RecomList();

		case 'HotList':			// 热门文章
			return TplIndex::HotList();

		case 'DynWeb':			// 单篇页内容
			return TplIndex::DynWeb($params['webId'], $params['mode'], $params['num']);

		case 'Ads':				// 广告调用
			return TplIndex::Ads($params['num']);

		case 'NewList':			// 最新消息
			return TplIndex::NewList($params['num']);

		case 'MarImgBox':		// 滚动图片
			return TplIndex::MarImgBox($params['num']);

		case 'ItemList':		// 左两栏右热门文章+投票
			return TplIndex::ItemList($params['num']);

		case 'NewUsers':		// 最新会员排行
			return TplIndex::NewUsers($params['num']);

		case 'RankUsers':		// 会员积分排行
			return TplIndex::RankUsers($params['num']);

		case 'QiandaoRank':		// 会员签到排行
			return TplIndex::QiandaoRank($params['num']);

		case 'NewMessage':		// 最新留言
			return TplIndex::NewMessage($params['num']);

		case 'NewReply':		// 最新评论
			return TplIndex::NewReply($params['num']);

		case 'NewBbs':			// 最新论坛帖子
			return TplIndex::NewBbs($params['num']);

		case 'VoteBox':			// 投票
			return TplIndex::VoteBox();

		case 'ItemList3':		// 全3栏模式
			return TplIndex::ItemList3($params['num']);

		case 'NewsModeNum':	// 文章列表页多模式值
			return TplList::NewsModeNum($params['mode']);

		case 'NewsList':		// 文章列表页 内涵 NewsListMode 
			return TplList::NewsList($params['areaName'], $params['mode'], $params['num'], $params['typeStr'], $params['level']);

		case 'NewsListMode':	// 文章列表多模式显示
			return TplList::NewsListMode($params['listInfo'], $params['mode'], $params['num'], $params['typeStr'], $params['level']);

		case 'SubRightNavMenu':	// 次页右侧导航菜单
			return TplIndex::SubRightNavMenu($params['typeID']);

		case 'MarkStr':			// 标签信息
			return TplIndex::MarkStr($params['str']);

		case 'MarkNews':		// 相关文章列表
			return TplIndex::MarkNews($params['newsId'], $params['markStr'], $params['markIdStr'], $params['num']);

		case 'StatiNewsCount':	// 统计文章数量
			return TplIndex::StatiNewsCount($params['type']);

		case 'BannerImg':		// banner广告图调用
			return TplIndex::BannerImg($params['id'], $params['style'], $params['mode']);

		case 'Url_ListStr': case 'Url_NewsListStr':	// 列表页链接
			return Url::ListStr($params['type'], $params['page']);

		case 'Url_ListID':	// 列表页链接
			return Url::ListID('',$params['htmlName'],$params['id']);

		case 'Url_WebID':	// 单篇页链接
			return Url::WebID($params['id']);

		default :
			$nameArr = explode('_',$params['action']);
			if (count($nameArr) == 2){
				return call_user_func_array(array($nameArr[0],$nameArr[1]),array(@$params['paraStr'],true));
			}else{
				return '该自定义函数（'. $params['action'] .'）不存在';
			}
	}
}

?>