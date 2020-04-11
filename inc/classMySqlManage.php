<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class MySqlManage {
	private $db;				// 数据库连接对象
	private $host;				// 数据库连接IP
	private $dbName;			// 所用数据库
	private $tabArr = array();	// 数据库表集
	private $bakDir;			// 数据库备份文件夹
	public $bakType;			// 数据库备份文件夹
	
	private $ds = "\n";			// 换行符
	public $sqlStr = '';		// 存储SQL的变量
	public $sqlEnd = ';';		// 每条sql语句的结尾符

	/**
	 * 初始化
	 *
	 * @param string $host
	 * @param string $port
	 * @param string $username
	 * @param string $userpwd
	 * @param string $dbName
	 * @param string $charset
	 */
	function __construct($host = 'localhost', $port = '3306', $username = 'root', $userpwd = '', $dbName = 'test', $charset = 'utf8') {
		$this->host = $host;
		$this->dbName = $dbName;
		ini_set('memory_limit','3072M');
		@ini_set('max_execution_time', 0);
		@set_time_limit(0); //无时间限制
		@ob_end_flush();

		// 连接数据库
		$dsn = 'mysql:host='. $host .';port='. $port .';dbname='. $dbName;
		$this->db = new PdoDb( array('type'=>'mysql', 'dsn'=>$dsn, 'user'=>$username, 'pwd'=>$userpwd, 'FETCH'=>'NUM') );	// , 'BUFFERED'=>'QUERY'

		$this->GetTableArr();
	}

	// 获取数据库表数组
	function GetTableArr() {
		$this->tabArr	= array();
		/* $tabPrefLen = strlen(OT_dbPref);
		$tabexe = $this->db->query("select TABLE_NAME from information_schema.tables where TABLE_SCHEMA='". $this->dbName ."'");
		while ($row = $tabexe->fetch()){
			$tabName = $row['TABLE_NAME'];
			if (strcasecmp(substr($tabName,0,$tabPrefLen), OT_dbPref) == 0){
				$this->tabArr[] = $tabName;
			}
		}
		unset($tabexe); */
		$tabexe = $this->db->query("SHOW TABLES");
		while ($row = $tabexe->fetch()){
			$this->tabArr[] = $row[0];
		}
		unset($tabexe);
		
		return $this->tabArr;
	}

	// 检查数据库表数组
	function CheckTableArr($tableArr) {
		$errStr = '';
		foreach ($tableArr as $val){
			if ( ! in_array($val,$this->tabArr) ){
				$errStr .= '['. $val .']';
			}
		}
		if (strlen($errStr) > 0){
			$this->ErrMsg('表 '. $errStr .' 不存在，请检查！');
			exit( 0 );
		}
	}

	// 获取数据库表字段数量
	function GetFieldNum($tabName) {
		$fieldexe = $this->db->query("select COLUMN_NAME from information_schema.COLUMNS where TABLE_SCHEMA='". $this->dbName ."' and TABLE_NAME='". $tabName ."' order by ORDINAL_POSITION ASC");
		return count($fieldexe->fetchAll());
	}

	// 输出错误信息
	private function ErrMsg($msg,$err=false){
		$err = $err ? '<span class="err">ERROR:</span>' : '' ;
		echo '<p class="dbDebug">'. $err . $msg .'</p>';
		flush();

	}

	/*
	 * ------------------------------------------数据库备份start----------------------------------------------------------
	 */

	/**
	 * 数据库备份
	 * 参数：备份哪个表(可选),备份目录(可选，默认为backup),分卷大小(可选,默认2000，即2M)
	 *
	 * @param $string $tableArr
	 * @param $string $dir
	 * @param int $size
	 */
	function backup($tableArr = array(), $dir = './', $size=5120) {
		$dir = $dir ? $dir : './';
		// 创建目录
		if (! is_dir( $dir )) {
			@mkdir( $dir, 0777, true ) or die('创建文件夹失败');
		}
		$size = $size ? $size : 1024*5;
		$sql = '';
		$filePath = '';
		$fileSize = 0;
		$fileNum = 0;
		if ( is_array($tableArr) && count($tableArr)>0 ){
			$bakTabArr = $tableArr;
			$this->CheckTableArr($bakTabArr);
		}else{
			$this->bakType = 'all';
			$bakTabArr = $this->tabArr;
		}

		$this->ErrMsg('正在备份');
		// 备份全部表
		if ( count($bakTabArr) > 0) {
			$this->ErrMsg('读取数据库结构成功！');
		} else {
			$this->ErrMsg('读取数据库结构失败！');
			exit( 0 );
		}
		// 插入dump信息
		$sql .= $this->SqlTopInfo();
		// 文件名前面部分
		$filename = 'OT'. date('ymdHis') .'_'. $this->bakType;
		// 第几分卷
		$p = 1;
		// 循环所有表
		foreach ( $bakTabArr as $tablename ) {
			// 获取表结构
			$sql .= $this->CreateTable( $tablename );
			$data = $this->db->query('select * from '. $tablename );
			$num_fields = $this->GetFieldNum( $tablename );

			// 循环每条记录
			while ( $record = $data->fetch() ) {
				// 单条记录
				$sql .= $this->InsertRecord( $tablename, $num_fields, $record );
				// 如果大于分卷大小，则写入文件
				$sqlLen = strlen( $sql );
				if ($sqlLen >= $size * 1024) {
					$file = $filename .'_part'. $p .'.sql';
					if (strlen($filePath) == 0){ $filePath = $file; }
					$fileSize += $sqlLen;
					$fileNum ++;
					// 写入文件
					if ($this->WriteFile( $sql, $file, $dir )) {
						$this->ErrMsg('-卷-<b>'. $p .'</b>-数据备份完成 [ <span class="imp">'. $dir . $file .'</span> ]（大小：'. File::SizeUnit($sqlLen) .'，内存：'. File::SizeUnit(memory_get_usage()) .'）');
					} else {
						$this->ErrMsg('卷-<b>'. $p .'</b>-备份失败!',true);
						return false;
					}
					// 下一个分卷
					$p ++;
					// 重置$sql变量为空，重新计算该变量大小
					unset($sql);
					$sql = '';
				}
			}

			unset($num_fields, $data);
		}
		// sql大小不够分卷大小
		if ($sql != '') {
			$filename .= '_part'. $p .'.sql';
			if (strlen($filePath) == 0){ $filePath = $filename; }
			$sqlLen = strlen( $sql );
			$fileSize += $sqlLen;
			$fileNum ++;
			if ($this->WriteFile( $sql, $filename, $dir )) {
				$this->ErrMsg('-卷-<b>'. $p .'</b>-数据备份完成 [ <span class="imp">'.$dir.$filename.'</span> ]（大小：'. File::SizeUnit($sqlLen) .'，内存：'. File::SizeUnit(memory_get_usage()) .'）');
			} else {
				$this->ErrMsg('卷-<b>'. $p .'</b>-备份失败',true);
				return false;
			}
		}
		$this->ErrMsg('恭喜您! <span class="imp">备份成功</span>');

		return array('type'=>$this->bakType, 'name'=>$filePath, 'size'=>$fileSize, 'num'=>$fileNum);
	}

	/**
	 * 插入数据库备份基础信息
	 * @return string
	 */
	private function SqlTopInfo() {
		$this->sqlStr = '';
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- Created by 网钛CMS PHP版, Power By 网钛科技 '. $this->ds;
		$this->sqlStr .= '-- 程序官网：http://otcms.com '. $this->ds;
		$this->sqlStr .= '-- 生成日期: '. date('Y-m-d H:i:s') . $this->ds;
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- 程序版本：V'. OT_VERSION .' '. OT_UPDATETIME . $this->ds;
		$this->sqlStr .= '-- 数据库版本：V'. OT_DBVER .' '. OT_DBTIME . $this->ds;
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- 主机: '. $this->host . $this->ds;
		$this->sqlStr .= '-- MySQL版本: '. $this->db->GetOne('select VERSION()') . $this->ds;
		$this->sqlStr .= '-- PHP 版本: '. phpversion() . $this->ds;
		$this->sqlStr .= $this->ds;
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- 数据库: `'. $this->dbName .'`'. $this->ds;
		$this->sqlStr .= '--'. $this->ds . $this->ds;
		$this->sqlStr .= '-- -------------------------------------------------------';
		$this->sqlStr .= $this->ds . $this->ds;
		return $this->sqlStr;
	}

	/**
	 * 插入表结构
	 *
	 * @param unknown_type $table
	 * @return string
	 */
	private function CreateTable($table) {
		$this->sqlStr = '';
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- 表的结构'. $table . $this->ds;
		$this->sqlStr .= '--'. $this->ds . $this->ds;

		// 如果存在则删除表
		$this->sqlStr .= 'DROP TABLE IF EXISTS `'. $table .'`'. $this->sqlEnd . $this->ds;

		// 获取详细表信息
		$row = $this->db->GetRow('SHOW CREATE TABLE `'. $table .'`');
			$this->sqlStr .= $row [1] . $this->sqlEnd . $this->ds;
		unset($row);

		// 加上
		$this->sqlStr .= $this->ds;
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= '-- 转存表中的数据 '. $table . $this->ds;
		$this->sqlStr .= '--'. $this->ds;
		$this->sqlStr .= $this->ds;
		return $this->sqlStr;
	}

	/**
	 * 插入单条记录
	 *
	 * @param string $table
	 * @param int $num_fields
	 * @param array $record
	 * @return string
	 */
	private function InsertRecord($table, $num_fields, $record) {
		// sql字段逗号分割
		$this->sqlStr = '';
		$comma = '';
		$this->sqlStr .= 'INSERT INTO `'. $table .'` VALUES(';
		// 循环每个子段下面的内容
		for($i = 0; $i < $num_fields; $i ++) {
			// $this->sqlStr .= ($comma . $this->db->ForStr( $record[$i] ) );
			$this->sqlStr .= ($comma ."'". $this->db->mysqlEscape( $record[$i] ) ."'");
			$comma = ',';
		}
		unset($comma);
		$this->sqlStr .= ');'. $this->ds;
		return $this->sqlStr;
	}

	/**
	 * 写入文件
	 *
	 * @param string $sql
	 * @param string $filename
	 * @param string $dir
	 * @return boolean
	 */
	private function WriteFile($sql, $filename, $dir) {
		$dir = $dir ? $dir : './backup/';
		// 创建目录
		if (! is_dir( $dir )) {
			mkdir( $dir, 0777, true );
		}
		$re = true;
		if (! @$fp = fopen( $dir . $filename, 'w+' )) {
			$re = false;
			$this->ErrMsg('打开sql文件失败！',true);
		}
		if (! @fwrite( $fp, $sql )) {
			$re = false;
			$this->ErrMsg('写入sql文件失败，请文件是否可写',true);
		}
		if (! @fclose( $fp )) {
			$re = false;
			$this->ErrMsg('关闭sql文件失败！',true);
		}
		return $re;
	}

	/*
	 *
	 * -------------------------------上：数据库导出-----------分割线----------下：数据库导入--------------------------------
	 */

	/**
	 * 导入备份数据
	 * 说明：分卷文件格式20120516211738_all_part1.sql
	 * 参数：文件路径(必填)
	 *
	 * @param string $sqlfile
	 */
	function restore($sqlfile) {
		// 检测文件是否存在
		if (! file_exists( $sqlfile )) {
			$this->ErrMsg('sql文件不存在！请检查',true);
			exit();
		}
		$this->lock( $this->dbName );
		// 获取数据库存储位置
		$sqlpath = pathinfo( $sqlfile );
		$this->bakDir = $sqlpath ['dirname'];
		// 检测是否包含分卷，将类似20120516211738_all_part1.sql从_part分开,有则说明有分卷
		$volume = explode('_part', $sqlfile );
		$volume_path = $volume [0];
		$this->ErrMsg('请勿刷新及关闭浏览器以防止程序被中止，如有不慎！将导致数据库结构受损');
		$this->ErrMsg('正在导入备份数据，请稍等！');
		if (empty( $volume [1] )) {
			$this->ErrMsg('正在导入sql：<span class="imp">'. $sqlfile .'</span>');
			// 没有分卷
			if ($this->ImportSqlFile( $sqlfile )) {
				$this->ErrMsg('数据库导入成功！');
			} else {
				 $this->ErrMsg('数据库导入失败！',true);
				exit();
			}
		} else {
			// 存在分卷，则获取当前是第几分卷，循环执行余下分卷
			$volume_id = explode('.sq', $volume [1] );
			// 当前分卷为$volume_id
			$volume_id = intval( $volume_id [0] );
			while ( $volume_id ) {
				$tmpfile = $volume_path .'_part'. $volume_id .'.sql';
				// 存在其他分卷，继续执行
				if (file_exists( $tmpfile )) {
					// 执行导入方法
					$this->msg .= '正在导入分卷 '. $volume_id .' ：<span style="color:#f00;">'. $tmpfile .'</span><br />';
					if ($this->ImportSqlFile( $tmpfile )) {

					} else {
						$volume_id = $volume_id ? $volume_id :1;
						exit('导入分卷：<span style="color:#f00;">'. $tmpfile .'</span>失败！可能是数据库结构已损坏！请尝试从分卷1开始导入');
					}
				} else {
					$this->msg .= '此分卷备份全部导入成功！<br />';
					return;
				}
				$volume_id ++;
			}
		}if (empty( $volume [1] )) {
			$this->ErrMsg('正在导入sql：<span class="imp">'. $sqlfile .'</span>');
			// 没有分卷
			if ($this->ImportSqlFile( $sqlfile )) {
				$this->ErrMsg('数据库导入成功！');
			} else {
				 $this->ErrMsg('数据库导入失败！',true);
				exit();
			}
		} else {
			// 存在分卷，则获取当前是第几分卷，循环执行余下分卷
			$volume_id = explode('.sq', $volume [1] );
			// 当前分卷为$volume_id
			$volume_id = intval( $volume_id [0] );
			while ( $volume_id ) {
				$tmpfile = $volume_path .'_part'. $volume_id .'.sql';
				// 存在其他分卷，继续执行
				if (file_exists( $tmpfile )) {
					// 执行导入方法
					$this->msg .= '正在导入分卷 '. $volume_id .' ：<span style="color:#f00;">'. $tmpfile .'</span><br />';
					if ($this->ImportSqlFile( $tmpfile )) {

					} else {
						$volume_id = $volume_id ? $volume_id :1;
						exit('导入分卷：<span style="color:#f00;">'. $tmpfile .'</span>失败！可能是数据库结构已损坏！请尝试从分卷1开始导入');
					}
				} else {
					$this->msg .= '此分卷备份全部导入成功！<br />';
					return;
				}
				$volume_id ++;
			}
		}
	}

	/**
	 * 将sql导入到数据库（普通导入）
	 *
	 * @param string $sqlfile
	 * @return boolean
	 */
	private function ImportSqlFile($sqlfile) {
		// sql文件包含的sql语句数组
		$sqls = array();
		$f = fopen( $sqlfile, 'rb' );
		// 创建表缓冲变量
		$create_table = '';
		while ( ! feof( $f ) ) {
			// 读取每一行sql
			$line = fgets( $f );
			// 这一步为了将创建表合成完整的sql语句
			// 如果结尾没有包含';'(即为一个完整的sql语句，这里是插入语句)，并且不包含'ENGINE='(即创建表的最后一句)
			if (! preg_match('/;/', $line ) || preg_match('/ENGINE=/', $line )) {
				// 将本次sql语句与创建表sql连接存起来
				$create_table .= $line;
				// 如果包含了创建表的最后一句
				if (preg_match('/ENGINE=/', $create_table)) {
					//执行sql语句创建表
					$this->InsertSql($create_table);
					// 清空当前，准备下一个表的创建
					$create_table = '';
				}
				// 跳过本次
				continue;
			}
			//执行sql语句
			$this->InsertSql($line);
		}
		fclose( $f );
		return true;
	}

	//插入单条sql语句
	private function InsertSql($sql){
		if (! $this->db->query( trim( $sql ) )) {
			$this->msg .= mysql_error();
			return false;
		}
	}

	/*
	 * -------------------------------数据库导入end---------------------------------
	 */

	// 关闭数据库连接
	private function close() {
		unset( $this->db );
	}

	// 锁定数据库，以免备份或导入时出错
	private function lock($tablename, $op = 'WRITE') {
		if ($this->db->query('lock tables '. $tablename .' '. $op ))
			return true;
		else
			return false;
	}

	// 解锁
	private function unlock() {
		if ( $this->db->query('unlock tables') )
			return true;
		else
			return false;
	}
	
	// 析构
	function __destruct() {
		if($this->db){
			$this->db->query('unlock tables', $this->db );
			unset( $this->db );
		}
	}

}
	
// $db = new MySqlManage('localhost','端口号','账号','密码','库名');
// $db->backup('','','');
?>