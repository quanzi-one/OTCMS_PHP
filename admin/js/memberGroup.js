window.onload=function(){ WindowHeight(0); }


// 检测表单
function CheckForm(){
	if ($id("theme").value == ""){ alert("用户组名称不能为空");$id("theme").focus();return false; }
}



/* *** 权限设置 *** */
function OnclickUG(dataID){
	document.location.href = "memberGroup.php?mudi=setRight&dataID="+ dataID;
}

//隐藏、显示菜单(ImgType:1.上下连接图；2.上连接图)
function TurnMenu(IDname,ImgType){
	if ($id(IDname).style.display == ""){
		$id(IDname).style.display = "none"
		if (ImgType == "2"){
			$id(IDname + "Img").src = "images/userRight/dz2_1.gif";
		}else{
			$id(IDname + "Img").src = "images/userRight/dz1_1.gif";
		}
	}else{
		$id(IDname).style.display = ""
		if (ImgType == "2"){
			$id(IDname + "Img").src = "images/userRight/dz2_2.gif";
		}else{
			$id(IDname + "Img").src = "images/userRight/dz1_2.gif";
		}
	}
	WindowHeight(0);
}

//全选、不选
function CheckBoxAll(str){
	if ($id("allRight").checked){
		if ($id('userRightStr').value != "admin"){
			for (var i=0;i<ULform.elements.length;i++){
				if (ULform.elements[i].value!="用户管理" && ULform.elements[i].value!="用户组维护" && ULform.elements[i].value!="用户维护" && ULform.elements[i].value!="权限设置"){
					ULform.elements[i].checked = true;
				}
			};
		}else{
			for (var i=0;i<ULform.elements.length;i++){ ULform.elements[i].checked = true; };
		}
	}else{
		for (var i=0;i<ULform.elements.length;i++){ ULform.elements[i].checked = false; };
	}
}

//复选框互动
function OnclickCheckBox(IDname,num){
	if ($name(IDname)[num].checked == true){ CKD=true; }else{ CKD=false; }

	for (i=0; i<$name(IDname +"_"+ num).length; i++){
		$name(IDname +"_"+ num)[i].checked = CKD

		for (i2=0; i2<$name(IDname +"_"+ num +"_"+ i).length; i2++){
			$name(IDname +"_"+ num +"_"+ i)[i2].checked = CKD

			for (i3=0; i3<$name(IDname +"_"+ num +"_"+ i +"_"+ i2).length; i3++){
				$name(IDname +"_"+ num +"_"+ i +"_"+ i2)[i3].checked = CKD
			}

		}

	}

	CheckCheckBox(IDname)
}

//检测复选框
function CheckCheckBox(str){
	var CBcount=0
	var strTop=str.substring(0,str.lastIndexOf("_"))
	var strBottom=str.substring(str.lastIndexOf("_")+1,str.length)
	for (CBi=0; CBi<4; CBi++){
//		alert(strTop +"|"+ strBottom +"|"+ CBcount)
		CBcount=0
		if (strTop.length >= 6){
			for (i=0; i<$name(strTop +"_"+ strBottom).length; i++){
				if ($name(strTop +"_"+ strBottom)[i].checked == true){CBcount += 1}
			}
			if (CBcount == 0){
				$name(strTop)[strBottom].checked = false;
			}else{
				$name(strTop)[strBottom].checked = true;
			}
		}

		strBottom=strTop.substring(strTop.lastIndexOf("_")+1,strTop.length)
		strTop=strTop.substring(0,strTop.lastIndexOf("_"))
	}
}

//检测表单
function CheckRightForm(){
	var CFcount=0;CFstr="|"
	ULform.rightStr.value="";	//先清空
	for (i=0;i<ULform.elements.length;i++){
		if (ULform.elements[i].checked == true){
			CFcount += 1;
			CFstr += ULform.elements[i].value +"|";
		}
	}
	if (CFcount == 0){
		alert("不允许用户组无任何权限");return false;
	}else{
		ULform.rightStr.value = CFstr;
	}
}

//确定重置
function CheckReset(){
	if (confirm("您确定重置？") == false){return false}
}




//菜单列表隐藏、显示
function JudMenu(num){
	if (num == 2){ return ""; }else{ return "none"; }
}

//showMenu选中与否
function JudShowMenu(num){
	if (num == 2){ return "checked"; }else{ return ""; }
}



function CheckShowMenu(num){
	if ($name("selShowMenu")[num].checked == true){openSM=""}else{openSM="none"}
	if (num == 0){
		menuSM=BImenu.split(",")
		for (i=0; i<menuSM.length; i++){
			if (document.getElementById("menu"+ i).style.display != openSM){
				if (i+1 == menuSM.length){imgSM=2}else{imgSM=1}
				TurnMenu("menu"+ i,imgSM)
			}
		}
	}

	if (num == 1){
		menuSM=BImenu.split(",")
		for (i=0; i<menuSM.length; i++){
			menuSM2=eval("BImenu"+ i).split(",")
			for (i2=0; i2<menuSM2.length; i2++){
					try{
				if (document.getElementById("menu"+ i +"_"+ i2).style.display != openSM){
					if (i2+1 == menuSM2.length){imgSM=2}else{imgSM=1}
					TurnMenu("menu"+ i +"_"+ i2,imgSM)
				}
					}catch(e){}
			}
		}
	}
}
