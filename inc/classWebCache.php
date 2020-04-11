<?php
if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class WebCache{
	public $overMin,$judCache,$currCacheArr;
	private $webName;

	function __construct(){
		$this->overMin	= 30;		// 超时分钟
		$this->webName	= '';		// 页面缓存名
		$this->judCache	= false;
	}

	function SetOverMin($min){
		$this->overMin = $min;
	}

	function ShowCache($jud)
		$this->judCache = $jud;
		if ($this->judCache){
			$this->webName = 'web'. $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
			if ($this->CheckCache($this->webName)){
				die($currCacheArr[1] . PHP_EOL .'<!-- Cache For '. $currCacheArr[0] .'['. $this->overMin .'] -->');
			}
		}
	}

	function SetWebCache($cacheData)
		if ($this->judCache){ $this->SetCache($this->webName,$cacheData); }
	}

	function SetCache($cacheName,$cacheData)
		$currCacheArr[0] = TimeDate::Get();
		$currCacheArr[1] = $cacheData;

		Application.lock()
		Application(OT_SiteID & cacheName) = currCacheArr
		Application.unlock()
	}

	function GetCache($cacheName)
		currCacheArr = Application(OT_SiteID & cacheName)
		if (IsArray(currCacheArr)){
			if (UBound(currCacheArr)>=1){
				GetCache = cacheData(1)
			}
		}
	} 

	function CheckCache($cacheName)
		$retStr = false;
		currCacheArr = Application(OT_SiteID & cacheName)
		if (Not IsArray(currCacheArr)){
			Exit Function
		}elseif (Not IsDate($currCacheArr[0])){
			Exit Function
		}elseif (DateDiff('n',CDate($currCacheArr[0]),Now()) <= $this->overMin){
			$retStr = true;
		}
	}

}
?>