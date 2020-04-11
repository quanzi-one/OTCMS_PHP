<?php
require(dirname(__FILE__) .'/conobj.php');


switch ($mudi){
	case 'dashang':
		dashang();
		break;

	default :
		die('// 无');
}
aa

$DB->Close();





function dashang(){
	global $DB;

	$userID = OT::GetInt('userID');

	if ($userID > 0){
		$urow = $DB->GetRow('select UE_dashangImg1,UE_dashangImg2,UE_dashangImg3 from '. OT_dbPref .'users where UE_ID='. $userID);
		if ($urow){
			if (strlen($urow['UE_dashangImg1']) > 5){ $urow['UE_dashangImg1'] = Area::InfoImgUrl($urow['UE_dashangImg1'],UsersFileDir); }
			if (strlen($urow['UE_dashangImg2']) > 5){ $urow['UE_dashangImg2'] = Area::InfoImgUrl($urow['UE_dashangImg2'],UsersFileDir); }
			if (strlen($urow['UE_dashangImg3']) > 5){ $urow['UE_dashangImg3'] = Area::InfoImgUrl($urow['UE_dashangImg3'],UsersFileDir); }
			echo('
			var dsUserImg1 = "'. $urow['UE_dashangImg1'] .'";
			var dsUserImg2 = "'. $urow['UE_dashangImg2'] .'";
			var dsUserImg3 = "'. $urow['UE_dashangImg3'] .'";
			');
		}
	}
}
?>