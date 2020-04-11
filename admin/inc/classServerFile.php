<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}


class ServerFile{

	// 添加指定服务器文件的占用数
	public static function UseAdd($fileName){
		global $DB;
		if ($fileName == ''){ return false; }

		$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr($fileName));
	}


	// 减少指定服务器文件的占用数
	public static function UseCut($fileName){
		global $DB;
		if ($fileName == ''){ return false; }

		$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr($fileName));
	}


	// 批量增加指定服务器文件的占用数
	public static function UseAddMore($fileArr){
		global $DB;

		$whereStr = '';
		if (is_array($fileArr)==false){
			$fileArr = explode('|',$fileArr);
		}

		foreach ($fileArr as $value){
			if ($value != ''){
				$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr($value));
			}
		}
	/*
		foreach ($fileArr as $value){
			if ($value != ''){
				$whereStr .= ' or UF_name='. $DB->ForStr($value);
			}
		}

		$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr('') . $whereStr);
	*/
	}


	// 批量减少指定服务器文件的占用数
	public static function UseCutMore($fileArr){
		global $DB;

		$whereStr = '';
		if (is_array($fileArr)==false){
			$fileArr = explode('|',$fileArr);
		}

		foreach ($fileArr as $value){
			if ($value != ''){
				$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr($value));
			}
		}
	/*
		foreach ($fileArr as $value){
			if ($value != ''){
				$whereStr .= ' or UF_name='. $DB->ForStr($value);
			}
		}

		$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr('') . $whereStr);
	*/
	}



	// 编辑器文件对比
	public static function Editor($oldFileStr,$newFileStr,$editorContent){
		global $DB;

		$returnOldArr = $returnNewArr = array();
		$returnNewStr = '|';
		$oldFileArr = explode('|',$oldFileStr);
		$newFileArr = explode('|',$newFileStr);

		$whereStrCut = $whereStrAdd = '';
		foreach ($newFileArr as $value){
			if ($value != ''){
				// 检测已被删图片
				if (strstr($editorContent,$value)==false){
					$returnNewArr[] = $value;
					$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr($value));
	//				$whereStrCut .= ' or UF_name='. $DB->ForStr($value);
				}else{
					$returnNewStr .= $value .'|';
				}
				// 检测新增图片
				if (in_array($value,$oldFileArr)==false){
					$returnNewArr[] = $value;
					$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr($value));
	//				$whereStrAdd .= ' or UF_name='. $DB->ForStr($value);
				}
			}
		}
	/*
		foreach ($oldFileArr as $value){
			if ($value != ''){
				if (strstr($editorContent,$value)==false){
					$returnOldArr[] = $value;
					$whereStrCut .= ' or UF_name='. $DB->ForStr($value);
				}
				if (in_array($value,$newFileArr)==false){
					$returnOldArr[] = $value;
					$whereStrCut .= ' or UF_name='. $DB->ForStr($value);
				}
			}
		}
		if ($whereStrCut != ''){
			$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr('') . $whereStrCut);
		}
		if ($whereStrAdd != ''){
			$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr('') . $whereStrAdd);
		}
	*/
		$returnArr = array();
		$returnArr['oldArr'] = $returnOldArr;
		$returnArr['newArr'] = $returnNewArr;
		$returnArr['newStr'] = $returnNewStr;
		return $returnArr;
	}


	// 检测编辑器存在的文件
	public static function EditorImgStr($newFileStr,$editorContent){
		$returnNewStr = '|';
		$newFileArr = explode('|',$newFileStr);

		foreach ($newFileArr as $value){
			if ($value != ''){
				// 检测已被删图片
				if (strstr($editorContent,$value)==true){
					$returnNewStr .= $value .'|';
				}
			}
		}
		return $returnNewStr;
	}



	// 编辑器文件对比
	public static function MoreImg($oldFileStr,$newFileStr){
		global $DB;

		$oldFileArr = explode('|',$oldFileStr);
		$newFileArr = explode('|',$newFileStr);

		foreach ($newFileArr as $value){
			if ($value != ''){
				if (in_array($value,$oldFileArr)==false){
					$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum+1 where UF_name='. $DB->ForStr($value));
				}
			}
		}
		foreach ($oldFileArr as $value){
			if ($value != ''){
				if (in_array($value,$newFileArr)==false){
					$DB->query('update '. OT_dbPref .'upFile set UF_useNum=UF_useNum-1 where UF_name='. $DB->ForStr($value));
				}
			}
		}
	}

}

?>