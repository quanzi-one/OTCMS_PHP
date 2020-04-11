$(function (){
	try {
		$('.editorBox label span').css({"position":"relative"})
		$('.editorBox label span img').css({"display":"none","position":"absolute","left":"0px","top":"20px"})
		$('.editorBox label').mouseover(function (){
			$(this).find('img').css({"display":""})
			$(this).hover(function (){
				
			},function (){
				$(this).find('img').css({"display":"none"})
			});
		});
	}catch (e) { }

	WindowHeight(0);
});


function CheckEditorMode(){
	var editorVal
	for (var i=0; i<$name('editorMode').length; i++){
		if ($name('editorMode')[i].checked){
			editorVal = $name('editorMode')[i].value;
		}
	}
	AjaxGetDeal("readDeal.php?mudi=checkEditorMode&editorMode="+ editorVal);
}


// 检查表单
function CheckForm(){

}