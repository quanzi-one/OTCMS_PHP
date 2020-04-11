<?php

if(! defined('OT_ROOT')) {
	exit('Access Denied');
}


class AppBase{

	public static function Jud(){
		return false;
	}

	public static function InfoThemeB($themeStyle_b,$themeStyle_color,$tab='info'){

	}

	public static function InfoSaveContImg($isSaveContImg=0){

	}

	public static function InfoSaveImg($isSaveImg=0){

	}

	public static function InfoUserFile($IF_isUserFile){

	}

	public static function InfoSysTrBox2($IS_defTemplate){

	}

	public static function InfoTrBox1($IF_titleAddi, $IF_isTitle){

	}

	public static function InfoTrBox2($IF_template){

	}

	public static function InfoTrBox3($IF_addition){

	}

	public static function InfoTypeThemeB($themeStyle_b,$themeStyle_color){

	}

	public static function InfoTypeTrBox2($IT_template){

	}

	public static function InfoTypeTrBox3($IT_isRightMenu){

	}

	public static function InfoTypeTrBox4($IT_lookScore){

	}

	public static function InfoTypeTrBox5($IT_titleAddi,$IT_isTitle){

	}

	public static function InfoTypeStrBox1(){

	}

	public static function InfoWebThemeB($themeStyle_b,$themeStyle_color){

	}

	public static function InfoWebTrBox1($IW_titleAddi,$IW_isTitle){

	}

	public static function InfoWebTrBox2($IW_template){

	}

	public static function TaokeItemTrBox2($TI_template){

	}

	public static function TaokeGoodsTrBox2($TI_template){

	}

	public static function KeyWordAddi(){
		return '
			<input type="hidden" id="themeSize" name="themeSize" value="0" />
			<input type="hidden" id="themeColor" name="themeColor" value="" />
			<input type="hidden" id="themeB" name="themeB" value="" />
			<input type="hidden" id="themeU" name="themeU" value="" />
			';
	}

	public static function SysImagesTitle(){

	}
	
	public static function SysImagesWater($SI_isWatermark, $SI_watermarkPos, $SI_watermarkPadding, $SI_watermarkTrans, $SI_watermarkPath, $SI_watermarkFontContent, $SI_watermarkFontSize, $SI_watermarkFontColor){

	}

	public static function TplSysOptionBox1($TS_navMode){

	}

	public static function LeftMenuBox1($menuSelID){
	
	}

	public static function AdminMainMenuBox1(){

	}

	public static function UsersNewsBox1($isShow=1, $idName='content', $mode='pc'){

	}

	public static function UsersNewsBox2($isFile, $IF_isRenameFile, $IF_isUserFile, $IF_file, $IF_fileName, $IF_fileStr, $mode='pc'){

	}

	public static function LeftMenuNoteBox(){

	}

}

?>