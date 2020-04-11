<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class PdoDb{
	public		$mDB;			// PDO对象
	protected	$mRes;			// PDOStatement对象
	public		$mErr;			// 错误信息
	protected	$mSql;			// 最后的SQL语句
	public		$mRowCount = -1;// limit函数附带统计没限制前记录数
	protected	$mDbType;		// 连接数据库类型 mysql/sqlite
	public		$mDbCharset;	// 连接数据库编码 gbk/utf8
	public		$mDbPref;		// 数据库表前缀
	public		$mDbName;		// 数据库名
 
	// 构造函数
	public function __construct($configArr){
		global $dbName;
		// $configArr('type'=>数据库类型, 'charset'=>编码, 'dsn'=>连接驱动, 'user'=>用户名, 'pwd'=>密码, 'option'=>其他选项)
		$this->mDbType = $configArr['type'];
		$this->mDbCharset = (empty($configArr['charset']) || in_array($configArr['charset'],array('gbk','utf8mb4'))==false) ? 'utf8' : 'gbk';
		$this->mDbPref = empty($configArr['pref']) ? OT_dbPref : $configArr['pref'];
		$this->mDbName = empty($configArr['dbName']) ? $dbName : $configArr['dbName'];

		$dsnAddi = '';
		if ($this->mDbType == 'mysql'){ $dsnAddi = ';charset='. $this->mDbCharset; }

		try{
			if (empty($configArr['user'])){ $configArr['user']=null; }
			if (empty($configArr['pwd'])){ $configArr['pwd']=null; }
			if (empty($configArr['option'])){ $configArr['option']=null; }
			$this->mDB = new PDO($configArr['dsn'] . $dsnAddi, $configArr['user'], $configArr['pwd'], $configArr['option']);
		} catch (PDOException $e) {
			if (empty($configArr['dbErr'])){
				$this->errMsg($e);
			}else{
				die($configArr['dbErr']);
			}
		}
		
		/*
		if (! empty($this->mDB->errorCode())){
			print_r($this->mDB->errorInfo());
			exit;
		}*/

		switch ($this->mDbType){
			case 'mysql':
				$this->mDB->query('SET NAMES '. $this->mDbCharset);
				$this->mDB->query('SET character_set_connection='. $this->mDbCharset .', character_set_results='. $this->mDbCharset .', character_set_client=binary;');
				break;
/*
			case 'sqlite':
				break;
		
			case 'access':
				break;

			default :
				die('不支持该类型数据库（'. $this->mDbType .'）');
				break;
*/
		}

		/*
		设置默认的提取模式
		PDO::FETCH_ASSOC：返回一个索引为结果集列名的数组
		PDO::FETCH_BOTH（默认）：返回一个索引为结果集列名和以0开始的列号的数组
		PDO::FETCH_BOUND：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
		PDO::FETCH_CLASS：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
		PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
		PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
		PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组
		PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象
		*/
		if (isset($configArr['FETCH'])){
			if ($configArr['FETCH'] == 'BOTH'){
				$this->mDB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_BOTH);
			}elseif ($configArr['FETCH'] == 'NUM'){
				$this->mDB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
			}else{
				$this->mDB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			}
		}else{
			$this->mDB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		if (isset($configArr['BUFFERED']) && $configArr['BUFFERED'] == 'QUERY'){
			$this->mDB->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		}

		/*
		强制列名为指定的大小写
		PDO::CASE_LOWER：强制列名小写。
		PDO::CASE_NATURAL：保留数据库驱动返回的列名。
		PDO::CASE_UPPER：强制列名大写。
		*/
		$this->mDB->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);

		/*
		错误报告
		PDO::ERRMODE_SILENT： 仅设置错误代码。
		PDO::ERRMODE_WARNING: 引发 E_WARNING 错误
		PDO::ERRMODE_EXCEPTION: 抛出 exceptions 异常。
		*/
		$this->mDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);

		// 设置编码
		// $this->mDB->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'set names '. $this->mDbCharset);

		// 是否使用PHP本地模拟prepare
		$this->mDB->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	}
 
	// 判断是否连接
 	public function IsConn($tabName=null){
		switch ($this->mDbType){
			case 'mysql':
				$chkexe = $this->mDB->query('show tables'); // sqlite_master
				if ($chkexe){
					return true;
				}else{
					return false;
				}
				break;

			case 'sqlite':
				$chkexe = $this->mDB->query('select * from sqlite_sequence limit 1'); // sqlite_master
				if ($chkexe){
					return true;
				}else{
					return false;
				}
				break;

			case 'access':
				if ($tabName){
					$chkexe = $this->mDB->query('select top 1 * from '. $tabName);
				}else{
					$chkexe = $this->mDB->query('select top 1 * from OT_info');
				}
				if ($chkexe){
					return true;
				}else{
					return false;
				}
				break;

			case 'mssql':
				if ($tabName){
					$chkexe = $this->mDB->query('select top 1 * from '. $tabName);
				}else{
					$chkexe = $this->mDB->query('select top 1 * from '. $this->mDbPref .'info'); // sqlite_master
				}
				if ($chkexe){
					return true;
				}else{
					return false;
				}
				break;

			default :
				die('不支持该类型数据库（'. $this->mDbType .'）');
				break;
		}
	}
  
	// 释放对象
	public function CloseQuery(){
		$this->mRes = null;
	}
  
	// 断开连接
	public function Close(){
		$this->mDB = null;
		$this->mRes = null;
	}
  
	// 设置表名前缀
	public function SetTabPref($str){
		$this->mDbPref = $str;
	}
  
	// 错误信息
	public function DbErr($str='',$sqlStr='',$dataArr=array()){
		$errArr = $this->mDB->errorInfo();
		if (@$errArr[2] == 'database is locked'){ $refBtn = '<input type="button" value="刷新" onclick="document.location.reload();" />'; }else{ $refBtn = ''; }
		$errStr = '数据库错误码：'. @$errArr[0] .'，错误码：'. @$errArr[1] .'，详细错误：'. @$errArr[2] .'。'. $refBtn;

		if (! empty($dataArr)){
			foreach ($dataArr as $val){
				$sqlStr = $this->ReplaceLimit('?', $this->ForStr(str_replace('?','[wenhao]',$val)), $sqlStr, 1);
			}
		}
		$sqlStr = str_replace('[wenhao]','?',$sqlStr);
		$this->AddDbErr($str .'操作失败：'. $errStr, $sqlStr);
		
		die($str .'操作失败.'. $errStr);
	}
  
	// 错误信息
	public function AddDbErr($note, $content){
		// die($note .'|'. htmlspecialchars($content)); 
		$this->InsertParam('dbErr',array('DE_time'=>date('Y-m-d H:i:s'), 'DE_ip'=>$this->GetIp(), 'DE_note'=>$note, 'DE_content'=>htmlspecialchars($content)));
	}


	// 执行sql，返回新加入的id
	public function exec($sql, $mode=''){
		try{
			$res = $this->mDB->exec($sql);
			if ($mode == 'die' && $this->mDB->errorCode() != '00000'){ 
				$this->mErr = $this->mDB->errorInfo();
				print_r($this->mErr); die('['. $sql .']'); // 
			} 
		} catch (PDOException $e) {  
			$this->errMsg($e);
		}
		if ($res) {
			$this->mSql = $sql;
			return $this->lastInsertId();
		}
	}
 
	// 查询sql，返回对象
	public function query($sql, $mode=''){
		$this->mRes = null;
		try{
			$res = $this->mDB->query($sql);
			if ($mode == 'die' && $this->mDB->errorCode() != '00000'){ 
				$this->mErr = $this->mDB->errorInfo();
				print_r($this->mErr); die('['. $sql .']'); // 
			} 
		} catch (PDOException $e) {  
			$this->errMsg($e);
		}
		if ($res) {
			$this->mRes = $res;
			$this->mSql = $sql;
			return $this->mRes;
		}
	}
 
/*
	// 获取结果集中下一行指定列的值
	public function fetchColumn($num=0){
		return $this->mRes->fetchColumn($num);
	}
 
	// 序列化一次数据
	public function fetch($option=PDO::FETCH_ASSOC){
		if (empty($option)){ $option=PDO::FETCH_ASSOC; }
		return $this->mRes->fetch($option);
	}
 
	// 序列化所有数据
	public function fetchAll($option=PDO::FETCH_ASSOC){
		if (empty($option)){ $option=PDO::FETCH_ASSOC; }
		return $this->mRes->fetchAll($option);
	}
 
	// 最后添加的id
	public function lastInsertId(){
		return $this->mDB->lastInsertId();
	}

	// 影响的行数（执行DELETE、 INSERT、或 UPDATE 语句受影响的行数）
	public function rowCount(){
		return $this->mRes->rowCount();
	}
 */ 

	// 预备语句
	public function prepare($sql){
		$this->mRes = null;
		try{
			$res = $this->mDB->prepare($sql);
		} catch (PDOException $e) {  
			$this->errMsg($e);
		}
		if ($res) {
			$this->mRes = $res;
			$this->mSql = $sql;
			return $this;
		}
	}
 
	// 绑定数据
	public function bindArray($arr){
		foreach ($arr as $k => $v) {
			if (is_array($v)) {
				//array的有效结构 array('value'=>xxx,'type'=>PDO::PARAM_XXX)
				$this->mRes->bindValue($k + 1, $v['value'], $v['type']);
			} else {
				$this->mRes->bindValue($k + 1, $v, PDO::PARAM_STR);
			}
		}
		return $this;
	}
 
	// 执行预备语句，返回结果 true/false（需要配合prepare函数使用）
	public function execute(){
		try{
			$res = $this->mRes->execute();
		} catch (PDOException $e) {  
			$this->errMsg($e);
		}
		if ($res) {
			return true;
		}
	}
 

	/*
	获取指定位置指定数量的记录，并以数组形式返回
	$sqlStr：所要执行的SQL语句
	$pageSize：每页显示数据的条数
	$currPage：当前显示的页数
	*/
	function GetLimit($sqlStr,$pageSize=20,$currPage=1){ //定义方法
		if(intval($currPage)<=0){
			$this->mCurrPage = 1;
		}else{
			$this->mCurrPage = $currPage;
		}

		$this->mPageSize	= $pageSize;
		$this->mSql			= $sqlStr;

		$startNum	= ($this->mCurrPage-1)*$this->mPageSize;
		if ($pageSize == 0 && $currPage == 0){
			$sqlLimitStr = $sqlStr;
		}else{
			$sqlLimitStr = $sqlStr ." limit $startNum, $this->mPageSize";
		}
		$result		= $this->mDB->prepare($sqlLimitStr);
		try{
			$result->execute();				// 执行查询语句，并返回结果集
			$rowArr = $result->fetchAll();	// 获取结果集里所有数据
		}catch (Exception $e){
			die($sqlLimitStr);
		}

		if(count($rowArr)==0 || $rowArr==false){
			if ($this->mCurrPage > 1){
				return $this->GetLimit($sqlStr,$pageSize,1);
			}else{
				return false;
			}
		}else{
			return $rowArr;
		}
	}

	// 根据SQL语句统计记录数；返回值-1不是select语句，-2采用count语句统计失败，-3直接统计失败
	function GetRowCount(){
		if (strtolower(substr($this->mSql,0,6)) == 'select'){
			try{
				$fieldStr = $this->GetSignStr(strtolower($this->mSql),'select','from');
				$newSqlStr = str_ireplace($fieldStr, ' Count(1) ', $this->mSql);
				$rs = $this->mDB->query($newSqlStr);
				$count = $rs->fetchColumn();
			}catch (Exception $e){
				$count=-2;
				$rs = $this->mDB->query($this->mSql);
				$count = count($rs->fetchAll());
			}
			if (! is_numeric($count)){ $count=-3; }
		}else{
			$count = -1;
		}
		$this->mRowCount = $count;
		return $count;
	}

	// 获取一个数据的结果集
	// 2种模式：1、一个参数直接输入SQL语句；2、三个参数 表名、字段、条件
	function GetOne($sql, $field='', $where=''){
		if (strlen($field) > 0){
			$sql = 'select '. $field .' from '. OT_dbPref . $sql;
			if (strlen($where) > 0){ $sql .= ' where '. $where; }
		}
		$rs = $this->mDB->query($sql);
		if (! $rs){
			$this->DbErr('GetOne',$sql);
		}
		return $rs->fetchColumn();
	}

	// 获取一行数据的结果集
	function GetRow($sql, $option=null){
		$rs = $this->mDB->query($sql);
		if ($option == null){
			return $rs->fetch();
		}else{
			return $rs->fetch($option);
		}
	}

	// 获取所有数据的结果集
	function GetAll($sql, $option=null){
		$rs = $this->mDB->query($sql);
		if ($option == null){
			return $rs->fetchAll();
		}else{
			return $rs->fetchAll($option);
		}
	}

 
	// 查询数据库中的数据
	// tableName：表名, whereStr：搜索条件，mode：事件
	function Select($tableName, $fieldStr='*', $whereStr='', $mode=''){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$sqlStr = 'select '. $fieldStr .' from '. $tableName . (strlen($whereStr)>0 ? ' where '. $whereStr : '');

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die($sqlStr);
		}else{
			return $this->query($sqlStr);
		}
	}

	// 往数据库中插入数据
	// tableName：表名, cols：数据数组(字段名=>内容), autoDeal：是否自动处理true/false, mode：事件
	function Insert($tableName, $cols=array(), $mode='', $autoDeal=true){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$count = 0;
		if (count($cols) <= 0){return false;}

		$fields = '(';
		$values = ' values(';
		foreach ($cols as $key => $value){
			if ($count != 0){
				$fields .= ',';
				$values .= ',';
			}
			if ($autoDeal){
				/*
				if (! is_numeric($value)){
					if (substr($value,0,strlen($key)) == $key){
						if (! is_numeric(substr($value,strlen($key)))){
							$value = $this->ForStr($value);
						}
					}else{
						$value = $this->ForStr($value);
					}
				}
				*/
				if (! is_numeric($value)){ $value = $this->ForStr($value); }
			}
			$fields .= $key;
			$values .= $value;
			$count ++;
		}
		$fields .= ')';
		$values .= ')';

		$sqlStr = 'Insert into '. $tableName . $fields . $values;

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die(htmlspecialchars($sqlStr));
		}else{
			$retRes = $this->query($sqlStr);
			if (! $retRes){
				$this->DbErr('Insert',$sqlStr);
			}
			return $retRes;
		}
	}

	// 更新数据库中的数据
	// tableName：表名, cols：数据数组(字段名=>内容), whereStr：搜索条件，autoDeal：是否自动处理true/false, mode：事件
	function Update($tableName, $cols=array(), $whereStr, $mode='', $autoDeal=true){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$count = 0;
		if (count($cols) <= 0){return false;}

		$fields = '';
		foreach ($cols as $key => $value){
			if ($count != 0){$fields .= ',';}
			if ($autoDeal){
				if (! is_numeric($value)){
					// 要排除字段累加数值情况被当做字符串（如GT_num=GT_num+1）
					if (substr($value,0,strlen($key)) == $key){
						if (! is_numeric(substr($value,strlen($key)))){
							$value = $this->ForStr($value);
						}
					}else{
						$value = $this->ForStr($value);
					}
				}
				/*
				if (! is_numeric($value)){ $value = $this->ForStr($value); }
				*/
			}
			$fields .= $key;
			$fields .= '=';
			$fields .= $value;
			$count ++;
		}

		$sqlStr = 'Update '. $tableName .' Set '. $fields .' where '. $whereStr;

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die(htmlspecialchars($sqlStr));
		}else{
			$retRes = $this->query($sqlStr);
			if (! $retRes){
				$this->DbErr('Update',$sqlStr);
			}
			return $retRes;
		}
	}

	// 删除数据库中的数据
	// tableName：表名, whereStr：搜索条件，mode：事件
	function Delete($tableName, $whereStr='1=1', $mode=''){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$sqlStr = 'Delete from '. $tableName .' where '. $whereStr;

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die($sqlStr);
		}else{
			$retRes = $this->query($sqlStr);
			if (! $retRes){
				$this->DbErr('Delete',$sqlStr);
			}
			return $retRes;
		}
	}

	// 批量执行SQL语句
	// sqlArr：一个元素一条SQL语句
	function RunMore($sqlArr=array()){
		$successNum = 0;
		$errSqlStr = '';
		foreach ($sqlArr as $sqlValue){
			$isOK = $this->query($sqlValue);
			if ($isOK){
				$successNum ++;
			}else{
				$errSqlStr .= '['. $sqlValue .']';
			}
		}

		return array('total'=>count($sqlArr),'success'=>$successNum,'errSqlStr'=>$errSqlStr);
	//	die('共'. count($sqlArr) .'条，成功'. $successNum .'条。');
	}

	// 以Param方式查询/删除数据
	// QueryParam('select MB_ID from '. $this->mDbPref .'member where MB_ID=? and MB_userpwd=?', array($UserID, $UserPwd))
	function QueryParam($sqlStr, $dataArr=array()){
		$parObj = $this->mDB->prepare($sqlStr);
		$parObj->execute($dataArr);
		return $parObj;
	}

	// 以Param方式往数据库中插入数据
	function InsertParam($tableName, $cols=array(), $mode=''){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$count = 0;
		if (count($cols) <= 0){return false;}

		$fields = '(';
		$values = ' values(';
		$dataArr = array();
		foreach ($cols as $key => $value){
			if ($count != 0){
				$fields .= ',';
				$values .= ',';
			}
			$fields .= $key;
			$values .= '?';
			$dataArr[] = $value;
			//$values .= ':'. $key;
			//$dataArr[':'. $key] = $value;
			$count ++;
		}
		$fields .= ')';
		$values .= ')';

		$sqlStr = 'Insert into '. $tableName . $fields . $values;

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die(htmlspecialchars($sqlStr));
		}else{
			try {
				$parObj = $this->mDB->prepare($sqlStr);
				$retRes = $parObj->execute($dataArr);
				if (! $retRes){
					if ($tableName != $this->mDbPref . 'dbErr'){
						// echo('结果：'. $sqlStr); var_dump($retRes); print_r($this->mDB->errorInfo()); var_dump($dataArr);
						$this->DbErr('InsertParam',$sqlStr,$dataArr);
					}
				}
			} catch (PDOException $e) {
				die('错误信息：'. $e->getMessage());
			}
			return $retRes;
		}
	}

	// 以Param方式更新数据库中的数据
	function UpdateParam($tableName, $cols=array(), $whereStr, $mode=''){
		if (strcasecmp(substr($tableName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tableName = $this->mDbPref . $tableName; }
		$count = 0;
		if (count($cols) <= 0){ return false; }

		$fields = '';
		$dataArr = array();
		foreach ($cols as $key => $value){
			if ($count != 0){$fields .= ',';}
			$fields .= $key;
			$fields .= '=';

			if (! is_numeric($value)){
				// 要排除字段累加数值情况被当做字符串（如GT_num=GT_num+1）
				if (substr($value,0,strlen($key)) == $key){
					if (! is_numeric(substr($value,strlen($key)))){
						$fields .= '?';
						$dataArr[] = $value;
					}else{
						$fields .= $value;
					}
				}else{
					$fields .= '?';
					$dataArr[] = $value;
				}
			}else{
				$fields .= '?';
				$dataArr[] = $value;
			}
			$count ++;
		}

		$sqlStr = 'Update '. $tableName .' Set '. $fields .' where '. $whereStr;

		if ($mode=='get'){
			return $sqlStr;
		}else if ($mode=='die'){
			die($this->Update($tableName, $cols, $whereStr, 'die'));
			// die(htmlspecialchars($sqlStr));
		}else{
			$parObj = $this->mDB->prepare($sqlStr);
			$retRes = $parObj->execute($dataArr);
			if (! $retRes){
				// $sqlStr = $this->Update($tableName, $cols, $whereStr, 'get');
				$this->DbErr('UpdateParam',$sqlStr,$dataArr);
			}
			return $retRes;
		}
	}



	// 获取数据库表名集数组
	function GetTabArr($size='', $tabName=''){
		$retArr = array();
		if ($this->mDbType == 'sqlite'){
			if (strlen($tabName) > 0){ $whereStr = ' and name like "%'. $tabName .'%"'; }else{ $whereStr = ''; }
			$tabexe = $this->query("select name from sqlite_master where type='table'". $whereStr);
			while ($row = $tabexe->fetch()){
				if ($size == 'xiao'){
					$retArr[] = strtolower($row['name']);
				}elseif ($size == 'da'){
					$retArr[] = strtoupper($row['name']);
				}else{
					$retArr[] = $row['name'];
				}
			}
		}else{
			if (strlen($tabName) > 0){ $whereStr = ' and TABLE_NAME like "%'. $tabName .'%"'; }else{ $whereStr = ''; }
			$tabexe = $this->query("select TABLE_NAME from information_schema.tables where TABLE_SCHEMA='". $this->mDbName ."'". $whereStr);
			while ($row = $tabexe->fetch()){
				if ($size == 'xiao'){
					$retArr[] = strtolower($row['TABLE_NAME']);
				}elseif ($size == 'da'){
					$retArr[] = strtoupper($row['TABLE_NAME']);
				}else{
					$retArr[] = $row['TABLE_NAME'];
				}
			}
		}
		return $retArr;
	}



	// 判断表是否存在
	function IsTab($tabName){
		if (strcasecmp(substr($tabName,0,strlen($this->mDbPref)), $this->mDbPref) != 0){ $tabName = $this->mDbPref . $tabName; }
		if ($this->mDbType == 'sqlite'){
			$tabexe = $this->query("select name from sqlite_master where type='table' and name='". $tabName ."'");
		}else{
			$tabexe = $this->query("select TABLE_NAME from information_schema.tables where TABLE_SCHEMA='". $this->mDbName ."' and TABLE_NAME='". $tabName ."'");
		}
		if ($row = $tabexe->fetch()){
			return true;
		}else{
			return false;
		}
	}



	// sql语句_时间
	function ForTime($str){
		return "'". $str ."'";
	}

	// sql语句_字符串
	function ForStr($str,$judSign=true){
		/*
		if (OT_Database=='sqlite'){
			if(function_exists('sqlite_escape_string')) {
				$newStr = sqlite_escape_string($str);
			}else{
				$newStr = $this->sqliteEscape($str);
			}
		}else{
			if(function_exists('mysql_escape_string')) {
				$newStr = mysql_escape_string($str);
			}else{
				$newStr = $this->mysqlEscape($str);
			}
		}
		if ($judSign){ $newStr = "'". $newStr ."'"; }
		*/
 
		$newStr = $this->mDB->quote($str,PDO::PARAM_STR);
		if (! $judSign){ $newStr = substr($newStr, 1, -1); }
		return $newStr;
	}

	function sqliteEscape($str){  
		$str = str_replace(array('/',"'",'[',']','%','&','_','(',')'), array("//","''","/[","/]","/%","/&","/_","/(","/)"), $str);  
		return $str;  
	}  

	function mysqlEscape($str){  
		$str = str_replace(array("\x00","\\","'","\"","\x1a"), array("\\x00","\\\\","\'","\\\"","\\x1a"), $str);  
		return $str;  
	}  

	// sql语句_获取时间
	function SqlGetTime(){
		if (OT_Database=='sqlite'){
			return "(datetime('now', 'localtime'))";
		/* }elseif (OT_Database=='access'){
			return 'now()'; */
		}else{
			return 'getdate()';
		}
	}

	// sql语句_获取随机
	function SqlGetRand(){
		if (OT_Database=='sqlite'){
			return 'ORDER BY RANDOM()';
		}else{
			return 'ORDER BY RAND()';
		}
	}



	// 开启事务，返回结果 true/false
	public function beginTransaction(){
		return $this->mDB->beginTransaction();
	}
 
	// 执行事务，返回结果 true/false
	public function commitTransaction(){
		return $this->mDB->commit();
	}
 
	// 回滚事务，返回结果 true/false
	public function rollbackTransaction(){
		return $this->mDB->rollBack();
	}
 

	// 获得用户IP地址
	public function GetIp(){
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
			$userIP = getenv('HTTP_CLIENT_IP');
		}elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
			$userIP = getenv('HTTP_X_FORWARDED_FOR');
		}elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
			$userIP = $_SERVER['REMOTE_ADDR'];
		}
		/*
		}elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
			$userIP = getenv('REMOTE_ADDR');
		*/
		$userIP = addslashes($userIP);
		if ($userIP == '::1'){
			$userIP = '127.0.0.1';
		}else{
			@preg_match("/[\d\.]{7,15}/", $userIP, $userIpArr);
			$userIP = $userIpArr[0] ? $userIpArr[0] : 'unknown';
			unset($userIpArr);
		}
		return $userIP;
	}


	// 抛出错误
	public function errMsg($e){
		die('
		数据库链接出错（错误码：mysql'. (extension_loaded('pdo_mysql') ? 1 : 0) .''. PHP_VERSION .'sqlite'. (extension_loaded('pdo_sqlite') ? 1 : 0) .'）！<br />
		错误信息:'. iconv("GBK", "UTF-8//IGNORE", $e->getMessage()) .'；<br />
		错误代码:'. $e->getCode() .'；<br />
		文件:'. $e->getFile() .'；<br />
		行号:'. $e->getLine() .'；<br />
		');
		// Trace:'. $e->getTraceAsString() .'
	}
  
	// 获取PDO支持的数据库，返回结果 array
	public static function getSupportDriver(){
		return PDO::getAvailableDrivers();
	}

	// 获取数据库的版本信息，返回结果 array
	public function getDriverVersion(){
		$name = $this->mDB->getAttribute(PDO::ATTR_DRIVER_NAME);
		return array($name=>$this->mDB->getAttribute(PDO::ATTR_CLIENT_VERSION));
	}

	// 截取字符串
	// contentStr：要截取的字符串；startCode：开始字符串；endCode：结束字符串；incStart：是否包含startCode；incEnd：是否包含endCode
	function GetSignStr($contentStr,$startCode,$endCode,$incStart=false,$incEnd=false){
		if (empty($contentStr)==true || empty($startCode)==true || empty($endCode)==true){
			return '';
		}
		$contentTemp='';
		$Start=-1;
		$Over=-1;
		$contentTemp=strtolower($contentStr);
		$startCode=strtolower($startCode);
		$endCode=strtolower($endCode);
		$Start = strpos($contentTemp, $startCode);
		if (! is_numeric($Start)){
			return '';
		}else{
			if ($incStart==false){
				$Start += strlen($startCode);
			}
		}
		$Over=$Start + strpos(substr($contentTemp,$Start),$endCode);
		if ($Over<=0 || $Over<=$Start){
			return '';
		}else{
			if ($incEnd==true){
				$Over += strlen($endCode);
			}
		}

		return substr($contentStr,$Start,$Over-$Start);
	}

	function ReplaceLimit($search, $replace, $subject, $limit=-1){
		if(is_array($search)){
			foreach($search as $k=>$v){
				$search[$k] = '`'. preg_quote($search[$k], '`'). '`';
			}
		}else{
			$search = '`'. preg_quote($search, '`'). '`';
		}
		return preg_replace($search, $replace, $subject, $limit);
	}

}