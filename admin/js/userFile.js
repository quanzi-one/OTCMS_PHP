window.onload=function(){WindowHeight(150);}


// 检测按钮 自动清理已不存在的文件
function CheckClearButton(){
	if (confirm("你确定要进行自动清理已不存在的文件？\n\n由于大部分板块都有删除记录并连同附带的图片/文件一起删除的功能，\n所以会造成部分文件已删除了，但该上传记录却还在的现象.")==true){
		document.location.href="userFile_deal.php?mudi=clear&backURL="+ encodeURIComponent(document.location.href);
	}
}
