<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class StrArr{

	// 新闻类别数组option化
	public static function OptionArray($strArr,$fatID,$selID,$headPart=''){
		$retStr = '';
		if (isset($strArr[$fatID]) && is_array($strArr[$fatID])){
			foreach ($strArr[$fatID] as $valArr){
				if (is_array($valArr)){
					$retStr .= '<option value="'. $valArr['id'] .'" '. Is::Selected($valArr['id'],$selID) .'>'. $headPart .'&ensp;'. $valArr['theme'] .'</option>';
					$retStr .= self::OptionArray($strArr,$valArr['id'],$selID,'│&ensp;'. $headPart);
				}else{
					break;
				}
			}
		}
		return $retStr;
	}


	// 新闻类别父类别集数组option化
	public static function OptionFatStrArray($strArr,$fatID,$fatStr,$selID,$headPart=''){
		$retStr = '';
		if (isset($strArr[$fatID]) && is_array($strArr[$fatID])){
			foreach ($strArr[$fatID] as $valArr){
				if (is_array($valArr)){
					$retStr .= '<option value="'. $fatStr . $valArr['id'] .'" '. Is::Selected($fatStr . $valArr['id'],$selID) .'>'. $headPart .'&ensp;'. $valArr['theme'] .'</option>';
					$retStr .= self::OptionFatStrArray($strArr,$valArr['id'],$fatStr . $valArr['id'] .',',$selID,'│&ensp;'. $headPart);
				}else{
					break;
				}
			}
		}
		return $retStr;
	}

	// 新闻类别ID数组option化
	public static function OptionIdArray($strArr,$fatID,$selID,$headPart=''){
		$retStr = '';
		if (isset($strArr[$fatID]) && is_array($strArr[$fatID])){
			foreach ($strArr[$fatID] as $valArr){
				if (is_array($valArr)){
					$retStr .= '<option value="'. $valArr['id'] .'" '. Is::Selected($valArr['id'],$selID) .'>'. $headPart .'&ensp;'. $valArr['theme'] .'</option>';
					$retStr .= self::OptionIdArray($strArr,$valArr['id'],$selID,'│&ensp;'. $headPart);
				}else{
					break;
				}
			}
		}
		return $retStr;
	}



	// 获取数组值
	public static function GetArrValue($valArr,$valId){
		foreach ($valArr as $oneArr){
			if (isset($oneArr[$valId]) && is_array($oneArr[$valId])){
				return $oneArr[$valId];
			}
		}
	}

	// 通过新闻类别ID值获取所有父ID、名称数组
	public static function GetArrFatArr($valArr,$valId){
		$fatArr = array();
		foreach ($valArr as $key => $oneArr){
			if (isset($oneArr[$valId]) && is_array($oneArr[$valId])){
				$fatArr[] = array('id'=>$key,'theme'=>$oneArr[$valId]['theme']);
				if ($key>0){ $fatArr = array_merge($fatArr,self::GetArrFatArr($valArr,$key,false)); }
			}
		}
		return $fatArr;
	}

	// 通过新闻类别ID值获取所有父ID数组
	public static function GetArrFatArrId($valArr,$valId){
		$fatArr = array();
		foreach ($valArr as $key => $oneArr){
			if (isset($oneArr[$valId]) && is_array($oneArr[$valId])){
				if ($key>0){
					$fatArr = array_merge($fatArr,self::GetArrFatArrId($valArr,$key));
					$fatArr[] = $key;
				}
			}
		}
		return $fatArr;
	}

	// 通过新闻类别ID值获取所有父名称数组
	public static function GetArrFatArrTitle($valArr,$valId){
		$fatArr = array();
		foreach ($valArr as $key => $oneArr){
			if (isset($oneArr[$valId]) && is_array($oneArr[$valId])){
				if ($key>0){ $fatArr = array_merge($fatArr,self::GetArrFatArrTitle($valArr,$key)); }
				$fatArr[] = $oneArr[$valId]['theme'];
			}
		}
		return $fatArr;
	}


	// 通过新闻类别ID值获取所有子ID数组
	public static function ArrTypeChildId($valArr,$valId){
		$childArr = array();
		if (isset($valArr[$valId]) && is_array($valArr[$valId])){
			foreach ($valArr[$valId] as $key => $oneArr){
				$childArr = array_merge($childArr,self::ArrTypeChildId($valArr,$key));
				$childArr[] = $key;
			}
		}
		return $childArr;
	}

}

?>