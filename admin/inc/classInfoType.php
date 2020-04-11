<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class InfoType{

	// 数据库对象
	public $mTypeArr;
	public $mTypeFatArr;
	public $mTopicArr;

	public $noStr;


	// 构造函数
	public function __construct($showArr = array('topic'=>true, 'infoType'=>true)){
		global $DB;

		$this->noStr	= '[已不存在]';

		$this->mTypeArr = array();
		$this->mTypeFatArr = array();
		if (! empty($showArr['infoType'])){
			$typecnexe=$DB->query('select IT_ID,IT_theme,IT_level,IT_fatID from '. OT_dbPref .'infoType order by IT_ID DESC');
			while ($row = $typecnexe->fetch()){
				$this->mTypeArr[$row['IT_ID']] = $row['IT_theme'];
				$this->mTypeFatArr[$row['IT_fatID']][$row['IT_ID']] = array(
					'id'	=> $row['IT_ID'],
					'theme'	=> $row['IT_theme']
					);
			}
			unset($typecnexe);
		}

		if (! empty($showArr['topic'])){
			$topiccnexe=$DB->query("select IW_ID,IW_theme from ". OT_dbPref ."infoWeb where IW_type='topic' order by IW_ID DESC");
			while ($row = $topiccnexe->fetch()){
				$this->mTopicArr[$row['IW_ID']] = $row['IW_theme'];
			}
			unset($topiccnexe);
		}
	}



	// 属性项处理
	function AddiCN($dataID,$str){
		$addiStr = '';
		$addiNum = 0;
		$addiArr = array(array('|audit|','已审'), array('|img|','滚图'), array('|thumb|','缩图'), array('|recom|','推荐'), array('|marquee|','滚信'), array('|flash|','幻灯'), array('|top|','置顶'));
		$addiCount = count($addiArr);
		for ($i=0; $i<$addiCount; $i++){
			$addiNum ++;
			if (strpos($str,$addiArr[$i][0]) !== false){
				$addiStr .= ItemSwitch2('info',$dataID,$addiArr[$i][1],1,'addition',$addiArr[$i][0],$addiNum);
			}else{
				$addiStr .= ItemSwitch2('info',$dataID,$addiArr[$i][1],0,'addition',$addiArr[$i][0],$addiNum);
			}
		}
		if ($addiStr==''){ $addiStr = '<br />'; }

		return $addiStr;
	}


	// 属性项处理
	function AddiBtn($dataID,$isAudit,$isNew,$isHomeThumb,$isThumb,$isImg,$isFlash,$isMarquee,$isRecom,$isTop){
		return ''.
			Adm::SwitchAddi('info',$dataID,'已审',$isAudit,'isAudit','') .
			Adm::SwitchAddi('info',$dataID,'最新',$isNew,'isNew','') .
			Adm::SwitchAddi('info',$dataID,'首图',$isHomeThumb,'isHomeThumb','') .
			Adm::SwitchAddi('info',$dataID,'缩图',$isThumb,'isThumb','') .
			Adm::SwitchAddi('info',$dataID,'滚图',$isImg,'isImg','') .
			Adm::SwitchAddi('info',$dataID,'幻灯',$isFlash,'isFlash','') .
			Adm::SwitchAddi('info',$dataID,'滚信',$isMarquee,'isMarquee','') .
			Adm::SwitchAddi('info',$dataID,'推荐',$isRecom,'isRecom','') .
			Adm::SwitchAddi('info',$dataID,'置顶',$isTop,'isTop','') .
			'';
	}


	// 类别
	function TypeStrCN($str){
		global $systemArr;

		if ($str == 'announ'){
			return $systemArr['SYS_announName'];
		}else{
			$newStr = '';
			$selStr = 0;
			$selStr2= 0;
			$newArr = explode(',',''. $str);
			$newCount = count($newArr);
			for ($i=0; $i<$newCount; $i++){
				$newArr[$i] = intval($newArr[$i]);
				if ($newArr[$i] > 0){
					$selStr ++;
					if ($selStr > 1){
						$selStr2 ++;
						$newStr .= '<br />'. str_repeat('　', $selStr2) .'┣&ensp;'. $this->TypeCN($newArr[$i]) .'';
					}else{
						$newStr .= $this->TypeCN($newArr[$i]) .'';
					}
				}
			}
			return $newStr;
		}
	}


	function TypeCN($id){
		if (! isset($this->mTypeArr[$id]) ){
			return $this->noStr;
		}else{
			return $this->mTypeArr[$id];
		}
	}


	function TopicCN($id){
		if (! isset($this->mTopicArr[$id]) ){
			return $this->noStr;
		}else{
			return $this->mTopicArr[$id];
		}
	}


}
?>