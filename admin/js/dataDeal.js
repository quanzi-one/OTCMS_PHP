window.onload=function(){
	WindowHeight(0);
	LoadFiledData('def');
}

// 读取数据字段名
function LoadFiledData(str){
	refFiledOption = document.getElementById("refField").options;
	refFiledOption.length=0; 
	if ($id('refTable').value=="info"){
		refFiledOption.add(new Option("标题","IF_theme"));
		refFiledOption.add(new Option("来源","IF_source"));
		refFiledOption.add(new Option("作者","IF_writer"));
		refFiledOption.add(new Option("内容","IF_content"));
		refFiledOption.add(new Option("标签","IF_themeKey"));
		refFiledOption.add(new Option("摘要","IF_contentKey"));

	}else if ($id('refTable').value=="infoMessage"){
		refFiledOption.add(new Option("昵称","IM_username"));
		refFiledOption.add(new Option("内容","IM_content"));
	
	}else if ($id('refTable').value=="message"){
		refFiledOption.add(new Option("昵称","MA_username"));
		refFiledOption.add(new Option("内容","MA_content"));
	
	}else if ($id('refTable').value=="collItem"){
		refFiledOption.add(new Option("项目名称","CI_theme"));
	
	}else if ($id('refTable').value=="collResult"){
		refFiledOption.add(new Option("标题","CR_theme"));
		refFiledOption.add(new Option("来源","CR_source"));
		refFiledOption.add(new Option("作者","CR_writer"));
		refFiledOption.add(new Option("内容","CR_content"));
		refFiledOption.add(new Option("标签","CR_themeKey"));
	
	}
	if (str=="def" && $id('refFieldStr').value!=""){ $id('refField').value=$id('refFieldStr').value; }
}

// 数据处理查询表单
function CheckRefForm(str){
	$id('dealMode').value = str;
	if (str=="refer"){
		if ($id('refStr').value==""){ alert('请先输入要查询的关键词');$id('refStr').focus();return false; }
		$id('refForm').action="";
		$id('refForm').method="get";
	}else if (str=="replace"){
		if ($id('refStr').value==""){ alert('请先输入要查询的关键词');$id('refStr').focus();return false; }
		if ($id('repStr').value=="" && $id('repEmpty').checked==false){ alert('请先输入要替换关键词的内容');$id('repStr').focus();return false; }
		$id('refForm').action="";
		$id('refForm').method="get";
//		$id('refForm').action="dataDeal_deal.php?mudi=replaceStr";
//		$id('refForm').method="post";
	}
	$id('refForm').submit();
}

function CheckInfoForm(){
	$id('infoDealStr').innerHTML = "<img src='images/onload.gif' style='margin-right:5px;' />请稍等，正处理中...</center>";

	AjaxPostDeal("infoForm");
}