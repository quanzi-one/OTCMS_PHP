$(function (){
	try {
		ThumbMode();
		WaterMode();
	}catch (e) {}
	try {
		$("#watermarkPath").mouseover(function() {

			}).hover(function() { 
				$('#imgView').css({"display":""});
				if (IsAbsUrl($id('watermarkPath').value)){
					$id('imgView').src=$id('watermarkPath').value;
				}else{
					$id('imgView').src=$id('imgAdminDir').value + $id('watermarkPath').value;
				}
			}, function(){
				$('#imgView').css({"display":"none"});
		});
	}catch (e) {}

	WindowHeight(0);
});


// 检测表单
function CheckForm(){
	
}


//设置标题字体颜色
function SetThemeColor(color){
  $id('watermarkFontColor').value = color;
  $id('watermarkFontColor').style.color = color;
}

//水印模式选择
function ThumbMode(){
	if ($id("isThumb1").checked){
		$id("thumb").style.display = "";
	}else{
		$id("thumb").style.display = "none";
	}
	WindowHeight(20);
}

//水印模式选择
function WaterMode(){
	if ($id("isWatermarkFont").checked){
		$id("waterFont").style.display = "";
	}else{
		$id("waterFont").style.display = "none";
	}
	if ($id("isWatermarkImg").checked){
		$id("waterImg").style.display = "";
	}else{
		$id("waterImg").style.display = "none";
	}
	if ($id("isWatermarkFont").checked || $id("isWatermarkImg").checked){
		$id("waterPoint").style.display = "";
	}else{
		$id("waterPoint").style.display = "none";
	}
	WindowHeight(20);
	if ($id("isWatermarkFont").checked){
		SetThemeColor($id('watermarkFontColor').value);
	}
}
