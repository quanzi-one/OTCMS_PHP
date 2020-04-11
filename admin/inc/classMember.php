<?php

if(!defined('OT_ROOT')) {
	exit('Access Denied');
}



class Member{

	// 网站前缀标记
	protected $mSiteID;
	
	// 数据库对象
	protected $mDB;

	// 用户对象
	public $mMB;

	// 用户对象结果集数组
	public $mMbRow;

	// 参数数组
	protected $mSysArr;


	// 用户ID
	public $mUserID;

	// 用户名
	public $mUserName;

	// 用户密码
	public $mUserPwd;

	// 用户登录IP
	public $mUserIp;

	// 用户登录时间
	public $mUserTime;

	// 用户昵称
	public $mRealname;

	// 用户权限字符串
	public $mRightStr;


	// 构造函数
	public function __construct($siteID, $DBobj, $sysArr, $userArr){
		$this->mSiteID	= $siteID;
		$this->mDB		= $DBobj;
		$this->mSysArr	= $sysArr;
		$this->mUserID		= intval($userArr['userID']);
		$this->mUserName	= $userArr['userName'];
		$this->mUserPwd		= $userArr['userPwd'];
		$this->mUserTime	= $userArr['userTime'];
		$this->mUserIp		= $userArr['userIp'];
		$this->mRealname	= $userArr['userRealname'];
		$this->mRightStr	= $userArr['userRightStr'];
	}
 
  
	// 打开用户表，并返回用户操作对象
	function Open($sqlPath, $str, $rightLevel=10){	// 0~2检查session  2再检查用户组权限  5检查在线状态表
		if ($rightLevel <= 0 && strlen($this->mRightStr) > 2){
			if ($this->mUserID > 0 && strlen($this->mUserPwd) > 0){
				return false;
			}else{
				echo('
				<script language="javascript" type="text/javascript">
				alert("你还没有登录或是登录已经超时，请重新登录！");parent.document.location.href="admin_cl.php?nohrefStr=close";
				</script>
				');
			}

		}else{
			$this->mMB = $this->mDB->QueryParam('select MB_ID,MB_loginTime,MB_state,MB_itemNum,MB_groupID,MB_rightStr'. $sqlPath .' from '. OT_dbPref .'member where MB_ID=? and MB_userpwd=?', array($this->mUserID, $this->mUserPwd));	//一个帐号允许多人登录使用
				if ($this->mMbRow = $this->mMB->fetch()){
					if ($this->mSysArr['SA_loginMode'] == 1 && strtotime($this->mMbRow['MB_loginTime']) != strtotime($this->mUserTime)){
						die('
						<script language="javascript" type="text/javascript">
						alert("该账号登录时间不匹配！");parent.document.location.href="admin_cl.php?nohrefStr=close"
						</script>
						');
					}
					if ($this->mMbRow['MB_state'] == 10){
						die('
						<script language="javascript" type="text/javascript">
						alert("该账号已被冻结，如有疑问请联系管理员！");parent.document.location.href="admin_cl.php?nohrefStr=close"
						</script>
						');
					}
					if ($rightLevel >= 2 || strlen($this->mRightStr) <= 2){
						if ($this->mMbRow['MB_groupID'] == -1 && $this->mMbRow['MB_rightStr'] == 'admin'){
							$this->mRightStr = 'admin';

						}elseif ($this->mMbRow['MB_groupID'] == 1){
							$this->mRightStr = 'admin2';

						}else{
							$UGexe = $this->mDB->query('select MG_rightStr from '. OT_dbPref .'memberGroup where MG_ID='. $this->mMbRow['MB_groupID']);
							if (! $row2 = $UGexe->fetch()){
								die('
								<script language="javascript" type="text/javascript">
								alert("抱歉！找不到你所在的用户组！");parent.document.location.href="admin_cl.php?mudi=exit&nohrefStr=close";
								</script>
								');
							}
							$this->mRightStr = $row2['MG_rightStr'];
							if (strlen($this->mRightStr) < 3){
								die('
								<script language="javascript" type="text/javascript">
								alert("抱歉！您尚未授权，请与管理员联系！");parent.document.location.href="admin_cl.php?nohrefStr=close"
								</script>
								');
							}
							unset($UGexe);
							$this->mRightStr .= '[修改用户名或密码]';	//加入公用菜单


							$_SESSION[$this->mSiteID .'memberRight'] = $this->mRightStr;

							if ($rightLevel >= 5){
								$mgexe = $this->mDB->query('select MO_ID,MO_time,MO_computerCode from '. OT_dbPref .'memberOnline where MO_userID='. $this->mMbRow['MB_ID']);
									if (! $row3 = $mgexe->fetch()){
										die('
										<script language="javascript" type="text/javascript">
										alert("登录状态丢失！");parent.document.location.href="admin_cl.php?nohrefStr=close"
										</script>
										');
									}else{
										$this->mSysArr['SA_exitMinute'] = intval($this->mSysArr['SA_exitMinute']);
										if (TimeDate::Add('min',$this->mSysArr['SA_exitMinute'],$row3['MO_time']) < TimeDate::Get() && $this->mSysArr['SA_exitMinute'] > 0){
											die('
											<script language="javascript" type="text/javascript">
											alert("登录状态时间超时！");parent.document.location.href="admin_cl.php?nohrefStr=close"
											</script>
											');
										}
										$computerCode = Users::GetSignCode();
										if ($row3['MO_computerCode'] != $computerCode && $this->mSysArr['SA_loginMode'] == 1){
											die('
											<script language="javascript" type="text/javascript">
											alert("登录信息不匹配！");parent.document.location.href="admin_cl.php?nohrefStr=close"
											</script>
											');
										}
										$this->mDB->Update('memberOnline',array(
											'MO_time'	=> TimeDate::Get()
											),'MO_ID='. $row3['MO_ID']);
									}
								unset($mgexe);
							}
						}
					}
				}else{
					if ($str=='login'){
						die('
						<script language="javascript" type="text/javascript">
						alert("请先登录！");parent.document.location.href="index.php"
						</script>
						');
					}
				}
			return $this->mMbRow;
		}
	}


	//关闭用户表
	function Close(){
		$this->mMbRow = null;
		$this->mMB = null;
		$this->mDB = null;
	}

	function GetRightStr(){
		return $this->mRightStr;
	}


	// 判断是否有总管理员权限，有返回true，无返回false
	function IsAdminRight($mudi){
		switch($mudi){
			case 'alert':
				if ($this->mRightStr != 'admin'){
					JS::AlertEnd('您非管理员！');
				}
				break;

			case 'alertBack':
				if ($this->mRightStr != 'admin'){
					JS::AlertBackEnd('您非管理员！');
				}
				break;

			case 'jud':
				if ($this->mRightStr == 'admin'){
					return true;
				}else{
					return false;
				}
				break;

			default:
				JS::AlertEnd('目的不明确！');
				break;
		
		}
	}


	// 判断是否有主菜单权限
	function IsMenuRight($mudi, $str){
		if ($this->mRightStr == 'admin' || ($this->mRightStr == 'admin2' && is_numeric($str) == true) || strpos($this->mRightStr,'['. $str .']') !== false || $str == '修改用户名或密码'){
			if ($mudi == 'jud'){ return true; }
		}else{
			switch($mudi){
				case 'jud':
					return false;
					break;
				case 'alert':
					JS::AlertEnd('您无该权限！');
					break;
				case 'alertBack':
					JS::AlertBackEnd('您无该权限！');
					break;
				default:
					JS::AlertEnd('目的不明确！');
					break;
			}
		}
	}

	// 判断是否有子菜单权限
	function IsSecMenuRight($retMode,$fileID,$dataType){
		$dataTypeWhereStr = '';
		if ($fileID == 57){	// 当为动态页面（单一型）时
			$dataTypeWhereStr=' and MT_getDataID='. intval($dataType);
		}else{
			if ($dataType != ''){
				$dataTypeWhereStr=" and MT_getDataType='". Str::RegExp($dataType,'sql') ."'";
			}
		}

		$secexe = $this->mDB->query('select MT_ID from '. OT_dbPref .'menuTree where MT_fileID='. $fileID . $dataTypeWhereStr);
			if (! $row = $secexe->fetch()){
				switch($retMode){
					case 'jud':
						return false;
						break;
					case 'alert':
						JS::AlertEnd('无此菜单['. $fileID .']['. $dataType .']！');
						break;
					case 'alertBack':
						JS::AlertBackEnd('无此菜单['. $fileID .']['. $dataType .']！select MT_ID from '. OT_dbPref .'menuTree where MT_fileID='. $fileID . $dataTypeWhereStr);
						break;
					case 'alertClose':
						JS::AlertCloseEnd('无此菜单['. $fileID .']['. $dataType .']！');
						break;
					default:
						JS::AlertEnd('目的不明确！');
						break;
				}
			}else{
				$judMenuIdExist = false;
				$menuID = $row['MT_ID'];
				//$menuTreeID = $menuID;
				if (strpos($this->mRightStr,'['. $menuID .']') !== false){ $judMenuIdExist = true; }
				while ($row = $secexe->fetch()){
					if (strpos($this->mRightStr,'['. $row['MT_ID'] .']') !== false){ $judMenuIdExist = true; break; }
				}
			}
		unset($secexe);

		if ($this->mRightStr=='admin' || ($this->mRightStr=='admin2' && is_numeric($menuID)==true) || $judMenuIdExist==true || $menuID=='修改用户名或密码'){
			if ($retMode=='jud'){return true;}
		}else{
			switch($retMode){
				case 'jud':
					return false;
					break;
				case 'alert':
					JS::AlertEnd('您无该权限['. $fileID .']['. $dataType .']['. $menuID .']！');
					break;
				case 'alertBack':
					JS::AlertBackEnd('您无该权限['. $fileID .']['. $dataType .']['. $menuID .']！');
					break;
				case 'alertClose':
					JS::AlertCloseEnd('您无该权限['. $fileID .']['. $dataType .']！');
					break;
				default:
					JS::AlertEnd('目的不明确！');
					break;
			}
		}
	}

}