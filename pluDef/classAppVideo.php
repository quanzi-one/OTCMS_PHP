<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppVideo{

	public static function Jud(){
		return false;
	}

	public static function InfoTrBox1($IF_mediaFile, $IF_mediaEvent, $IF_addition=''){

	}

	public static function InfoTrItem1($IF_addition){

	}

	public static function AppSysTrBox1($AS_videoPcWidth, $AS_videoPcHeight, $AS_videoWapWidth, $AS_videoWapHeight, $AS_audioPcWidth, $AS_audioPcHeight, $AS_audioWapWidth, $AS_audioWapHeight){
		return '
			<tr>
				<td align="right">视频音乐播放器：</td>
				<td>您尚未购买该插件</td>
			</tr>
			';
	}

	public static function GetMediaCode($IF_mediaFile, $pcUrl='', $mode=''){

	}

	public static function GetJsCode($mode, $IF_mediaFile, $IF_mediaEvent='', $IF_img='', $currDir='', $videoArr=array(), $audioArr=array()){

	}

	public static function JsCode($mode, $IF_mediaFile, $IF_mediaEvent='', $IF_img='', $currDir='', $videoArr=array(), $audioArr=array()){

	}
}

?>