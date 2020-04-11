<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}



class Ftp{
	private $host = '';		// 远程服务器地址
	private $user = '';		// ftp用户名
	private $pwd = '';		// ftp密码
	private $port = 21;		// ftp登录端口
	private $isPasv = true;	// ftp被动模式
	protected $error = '';	// 最后失败时的错误信息
	protected $conn;		// ftp登录资源


	public function __construct($config=array())
	{
		empty($config) or $this->initialize($config);
	}

	// 初始化数据
	public function initialize($config){
		$this->host = $config['host'];
		$this->user = $config['user'];
		$this->pwd  = $config['pwd'];
		$this->port = empty($config['port']) ? 21 : $config['port'];
		$this->isPasv = isset($config['isPasv']) ? $config['isPasv'] : true;
	}

	// 连接及登录ftp
	public function Connect($config=array()){
		empty($config) or $this->initialize($config);
		if (($this->conn = @ftp_connect($this->host,$this->port)) == false){
			$this->error = '主机（'. $this->host .':'. $this->port .'）连接失败';
			return false;
		}
		if ( ! $this->Login()){
			$this->error = '服务器（'. $this->host .':'. $this->port .'）登录失败';
			return false;
		}
		if ($this->isPasv){
			ftp_pasv($this->conn, true);
		}
		return true;
	}

	// 登录Ftp服务器
	private function Login(){
		return @ftp_login($this->conn,$this->user,$this->pwd);
	}

	// 关闭ftp连接
	// @return bool
	public function Close(){
		return $this->conn ? @ftp_close($this->conn_id) : false;
	}

	// 上传文件到ftp服务器
	// $localFile 本地文件路径；$serverFile 服务器文件地址；$permissions 文件夹权限；$mode 上传模式(ascii和binary其中之一)
	public function UpFile($localFile='',$serverFile='',$mode='auto',$permissions=NULL){
		if ( ! file_exists($localFile)){
			$this->error = "本地文件不存在";
			return false;
		}
		if ($mode == 'auto'){
			$ext = $this->GetExt($localFile);
			$mode = $this->SetType($ext);
		}
		// 创建文件夹
		$this->CreateDir($serverFile);
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
		$result = ftp_put($this->conn,$serverFile,$localFile,$mode);//同步上传
		if ($result === false){
			$this->error = "文件上传失败";
			return false;
		}
		return true;
	}

	// 从ftp服务器下载文件到本地
	// $localFile 本地文件地址；$serverFile 远程文件地址；$mode 上传模式(ascii和binary其中之一)
	public function DownFile($localFile='',$serverFile='',$mode='auto'){
		if ($mode == 'auto'){
			$ext = $this->GetExt($serverFile);
			$mode = $this->SetType($ext);
		}
		$mode = ($mode == 'ascii') ? FTP_ASCII : FTP_BINARY;
		$result = @ftp_get($this->conn, $localFile, $serverFile, $mode);
		if ($result === false){
			return false;
		}
		return true;
	}

	// 删除ftp服务器端文件
	// $serverFile 文件地址
	public function DelFile($serverFile){
		$result = @ftp_delete($this->conn,$serverFile);
		if ($result === false){
			return false;
		}
		return true;
	}

	// ftp创建多级目录
	// $serverFile 要上传的远程图片地址
	private function CreateDir($serverFile,$permissions=null){
		$serverDir = dirname($serverFile);
		$path_arr = explode('/',$serverDir); // 取目录数组
		//$file_name = array_pop($path_arr); // 弹出文件名
		$path_div = count($path_arr); // 取层数
		foreach($path_arr as $val) // 创建目录
		{
			if(@ftp_chdir($this->conn,$val) == false)
			{
				$tmp = @ftp_mkdir($this->conn,$val);//此处创建目录时不用使用绝对路径(不要使用:2018-02-20/ceshi/ceshi2，这种路径)，因为下面ftp_chdir已经已经把目录切换成当前目录
				if($tmp == false)
				{
					echo '目录创建失败，请检查权限及路径是否正确！';
					exit;
				}
				if ($permissions !== NULL){
					//修改目录权限
					$this->SetRight($val,$permissions);
				}
				@ftp_chdir($this->conn,$val);
			}
		}

		for($i=0;$i<$path_div;$i++) // 回退到根,因为上面的目录切换导致当前目录不在根目录
		{
			@ftp_cdup($this->conn);
		}
	}

	// 递归删除ftp端目录
	// $serverDir ftp目录地址
	public function DelDir($serverDir){
		$list = $this->GetFileList($serverDir);
		if ( ! empty($list)){
			$count = count($list);
			for ($i=0;$i<$count;$i++){
				if ( ! preg_match('#\.#',$list[$i]) && !@ftp_delete($this->conn,$list[$i])){
					// 这是一个目录，递归删除
					$this->DelDir($list[$i]);
				}else{
					$this->DelFile($list[$i]);
				}
			}
		}
		if (@ftp_rmdir($this->conn,$serverDir) === false){
			return false;
		}
		return true;
	}

	// 更改 FTP 服务器上的文件或目录名
	// $oldFile 旧文件/文件夹名；$newFile 新文件/文件夹名
	public function MoveFile($oldFile='',$newFile=''){
		$result = @ftp_rename($this->conn,$oldFile,$newFile);
		if ($result === false){
			$this->error = "移动失败";
			return false;
		}
		return true;
	}

	// 列出ftp指定目录
	// $serverPath
	public function GetFileList($serverPath=''){
		$contents = @ftp_nlist($this->conn, $serverPath);
		return $contents;
	}

	// 获取文件的后缀名
	// $localFile
	private function GetExt($localFile=''){
		return (($dot = strrpos($localFile,'.'))==false) ? 'txt' : substr($localFile,$dot+1);
	}

	// 获取上传错误信息
	public function GetErr(){
		return $this->error;
	}

	// 根据文件后缀获取上传编码
	// $ext
	private function SetType($ext=''){
		// 如果传输的文件是文本文件，可以使用ASCII模式，如果不是文本文件，最好使用BINARY模式传输。
		return in_array($ext, ['txt', 'text', 'php', 'phps', 'php4', 'js', 'css', 'htm', 'html', 'phtml', 'shtml', 'log', 'xml'], true) ? 'ascii' : 'binary';
	}

	// 修改目录权限
	// $path 目录路径；$mode 权限值
	private function SetRight($path,$mode=0755){
		if (false == @ftp_chmod($this->conn,$path,$mode)){
			return false;
		}
		return true;
	}
}

	/*
	使用示例
	$config = [
		'host'=>'192.168.0.1',
		'user'=>'ftpuser',
		'pwd'=>'123456'
	];
	$ftp = new Ftp($config);
	$result = $ftp->Connect();
	if ( ! $result){
		echo $ftp->GetErr();
	}

	$localFile = '1.mp4';
	$serverFile = date('Y-m').'/1.mp4';

	// 上传文件
	if ($ftp->UpFile($localFile,$serverFile)){
		echo "上传成功";
	}else{
		echo "上传失败";
	}

	// 删除文件
	if ($ftp->DelFile($serverFile)){
		echo "删除成功";
	}else{
		echo "删除失败";
	}

	// 删除整个目录
	$serverPath='2018-09-19';
	if ($ftp->DelDir($serverPath)){
		echo "目录删除成功";
	}else{
		echo "目录删除失败";
	}

	// 下载文件
	$localFile2 = 'video5.mp4';
	$serverFile2='video3.mp4';
	if ($ftp->DownFile($localFile2,$serverFile2)){
		echo "下载成功";
	}else{
		echo "下载失败";
	}

	// 移动文件|重命名文件
	$localFile3 = 'video3.mp4';
	$serverFile3='shangchuan3/video3.mp4';
	if ($ftp->MoveFile($localFile3,$serverFile3)){
		echo "移动成功";
	}else{
		echo "移动失败";
	}
	$ftp->Close();
	//p($result);

	function p($data=''){
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
	*/

?>