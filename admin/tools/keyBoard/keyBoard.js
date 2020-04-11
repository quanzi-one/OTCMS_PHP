//window.onload=function(){ password1=null; initCalc(); }

var CapsLockValue=0;
var check;
self.onError=null;
currentX = currentY = 0;  
whichIt = null;           
lastScrollX = 0; lastScrollY = 0;
NS = (document.layers) ? 1 : 0;
IE = (document.all) ? 1: 0;


function checkFocus(x,y) { 
	stalkerx = document.softkeyboard.pageX;
	stalkery = document.softkeyboard.pageY;
	stalkerwidth = document.softkeyboard.clip.width;
	stalkerheight = document.softkeyboard.clip.height;
	if( (x > stalkerx && x < (stalkerx+stalkerwidth)) && (y > stalkery && y < (stalkery+stalkerheight))) return true;
	else return false;
}


function grabIt(e) {
	check = false;
	if(IE) {
		whichIt = event.srcElement;
		while (whichIt.id.indexOf("softkeyboard") == -1) {
			whichIt = whichIt.parentElement;
			if (whichIt == null) { return true; }
		}
		whichIt.style.pixelLeft = whichIt.offsetLeft;
		whichIt.style.pixelTop = whichIt.offsetTop;
		currentX = (event.clientX + document.body.scrollLeft);
		currentY = (event.clientY + document.body.scrollTop); 	
	} else { 
		window.captureEvents(Event.MOUSEMOVE);
		if(checkFocus (e.pageX,e.pageY)) { 
			whichIt = document.softkeyboard;
			StalkerTouchedX = e.pageX-document.softkeyboard.pageX;
			StalkerTouchedY = e.pageY-document.softkeyboard.pageY;
		}
	}
	return true;
}



function moveIt(e) {
	if (whichIt == null) { return false; }
	if(IE) {
		newX = (event.clientX + document.body.scrollLeft);
		newY = (event.clientY + document.body.scrollTop);
		distanceX = (newX - currentX);    distanceY = (newY - currentY);
		currentX = newX;    currentY = newY;
		whichIt.style.pixelLeft += distanceX;
		whichIt.style.pixelTop += distanceY;
		if(whichIt.style.pixelTop < document.body.scrollTop) whichIt.style.pixelTop = document.body.scrollTop;
		if(whichIt.style.pixelLeft < document.body.scrollLeft) whichIt.style.pixelLeft = document.body.scrollLeft;
		if(whichIt.style.pixelLeft > document.body.offsetWidth - document.body.scrollLeft - whichIt.style.pixelWidth - 20) whichIt.style.pixelLeft = document.body.offsetWidth - whichIt.style.pixelWidth - 20;
		if(whichIt.style.pixelTop > document.body.offsetHeight + document.body.scrollTop - whichIt.style.pixelHeight - 5) whichIt.style.pixelTop = document.body.offsetHeight + document.body.scrollTop - whichIt.style.pixelHeight - 5;
		event.returnValue = false;
	} else { 
		whichIt.moveTo(e.pageX-StalkerTouchedX,e.pageY-StalkerTouchedY);
		if(whichIt.left < 0+self.pageXOffset) whichIt.left = 0+self.pageXOffset;
		if(whichIt.top < 0+self.pageYOffset) whichIt.top = 0+self.pageYOffset;
		if( (whichIt.left + whichIt.clip.width) >= (window.innerWidth+self.pageXOffset-17)) whichIt.left = ((window.innerWidth+self.pageXOffset)-whichIt.clip.width)-17;
		if( (whichIt.top + whichIt.clip.height) >= (window.innerHeight+self.pageYOffset-17)) whichIt.top = ((window.innerHeight+self.pageYOffset)-whichIt.clip.height)-17;
		return false;
	}
	return false;
}



function dropIt() {
	whichIt = null;
	if(NS) window.releaseEvents (Event.MOUSEMOVE);
	return true;
}



if(NS) {
	window.captureEvents(Event.MOUSEUP|Event.MOUSEDOWN);
	window.onmousedown = grabIt;
	window.onmousemove = moveIt;
	window.onmouseup = dropIt;
}

if(IE) {
	document.onmousedown = grabIt;
	document.onmousemove = moveIt;
	document.onmouseup = dropIt;
}


document.write(""+
"<style>\n"+
".btnCss {width:25px;}\n"+
"A.C:link {COLOR: #ffffff; TEXT-DECORATION: none}\n"+
"A.C:visited {color:#ffffff; text-decoration:none;}\n"+
"A.C:hover {color:#ff0000;}\n"+
"A.C:active {color:#ff0000;}\n"+
"</style>\n"+

"<DIV align=center id=\"softkeyboard\" name=\"softkeyboard\" style=\"position:absolute; left:0px; top:0px; width:500px; z-index:180;display:none\">"+
	"<table id=\"CalcTable\" width=\"\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"\">"+
	"<FORM id=Calc name=Calc action=\"\" method=post autocomplete=\"off\">"+
	"<tr>"+
		"<td title=\"为了保证安全,建议使用密码输入器输入密码!\" align=\"right\" valign=\"middle\" bgcolor=\"\" style=\"cursor: default;height:30px;\">"+
			"<input type='hidden' value=\"\" name=password>"+
			"<input type='hidden' value=ok name=action2>&nbsp"+
			"<font style=\"font-size:13px;\"></font>"+
			"&nbsp;&nbsp;密　码　输　入　器&nbsp&nbsp&nbsp&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp&nbsp;&nbsp&nbsp;"+
			"<input style=\"width:100px;height:20px;background-color:#54BAF1;\" type='button' value=\"使用键盘输入\" bgtype=\"1\" onclick=\"password1.readOnly=0;password1.focus();softkeyboard.style.display='none';\"><!-- password1.value=''; -->"+
			"<span style=\"width:2px;\"></span>"+
		"</td>"+
	"</tr>"+
	"<tr align=\"center\">"+
		"<td align=\"center\" bgcolor=\"#FFFFFF\">"+
			"<table align=\"center\" width=\"%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\">"+
				"<tr align=\"left\" valign=\"middle\">"+
					"<td><input type='button' value=\" ~ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ! \" class='btnCss'></td>"+
					"<td><input type='button'  value=\" @ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" # \" class='btnCss'></td>"+
					"<td><input type='button' value=\" $ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" % \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ^ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" & \" class='btnCss'></td>"+
					"<td><input type='button' value=\" * \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ( \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ) \" class='btnCss'></td>"+
					"<td><input type='button' value=\" _ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" + \" class='btnCss'></td>"+
					"<td><input type='button' value=\" | \" class='btnCss'></td>"+
					"<td colspan=\"1\" rowspan=\"2\"><input name=\"button10\" type='button' value=\" 退格\" onclick=\"setpassvalue();\"  onDblClick=\"setpassvalue();\" style=\"width:100px;height:44px\"></td>"+
				"</tr>"+
				"<tr align=\"left\" valign=\"middle\">"+
					"<td><input type='button' value=\" ` \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 1 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 2 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 3 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 4 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 5 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 6 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 7 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 8 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" 9 \" class='btnCss'></td>"+
					"<td><input name=\"button6\" type='button' value=\" 0 \" class='btnCss'></td>"+
					"<td><input type='button' value=\" - \" class='btnCss'></td>"+
					"<td><input type='button' value=\" = \" class='btnCss'></td>"+
					"<td><input type='button' value=\" \\ \" class='btnCss'></td>"+
					"<td></td>"+
				"</tr>"+
				"<tr align=\"left\" valign=\"middle\">"+
					"<td><input type='button' value=\" q \" class='btnCss'></td>"+
					"<td><input type='button' value=\" w \" class='btnCss'></td>"+
					"<td><input type='button' value=\" e \" class='btnCss'></td>"+
					"<td><input type='button' value=\" r \" class='btnCss'></td>"+
					"<td><input type='button' value=\" t \" class='btnCss'></td>"+
					"<td><input type='button' value=\" y \" class='btnCss'></td>"+
					"<td><input type='button' value=\" u \" class='btnCss'></td>"+
					"<td><input type='button' value=\" i \" class='btnCss'></td>"+
					"<td><input type='button' value=\" o \" class='btnCss'></td>"+
					"<td><input name=\"button8\" type='button' value=\" p \" class='btnCss'></td>"+
					"<td><input name=\"button9\" type='button' value=\" { \" class='btnCss'></td>"+
					"<td><input type='button' value=\" } \" class='btnCss'></td>"+
					"<td><input type='button' value=\" [ \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ] \" class='btnCss'></td>"+
					"<td><input name=\"button9\" type='button' onClick=\"capsLockText();setCapsLock();\" onDblClick=\"capsLockText();setCapsLock();\" value=\"切换大/小写\" style=\"width:100px;\"></td>"+
				"</tr>"+
				"<tr align=\"left\" valign=\"middle\">"+
					"<td><input type='button' value=\" a \" class='btnCss'></td>"+
					"<td><input type='button' value=\" s \" class='btnCss'></td>"+
					"<td><input type='button' value=\" d \" class='btnCss'></td>"+
					"<td><input type='button' value=\" f \" class='btnCss'></td>"+
					"<td><input type='button' value=\" g \" class='btnCss'></td>"+
					"<td><input type='button' value=\" h \" class='btnCss'></td>"+
					"<td><input type='button' value=\" j \" class='btnCss'></td>"+
					"<td><input name=\"button3\" type='button' value=\" k \" class='btnCss'></td>"+
					"<td><input name=\"button4\" type='button' value=\" l \" class='btnCss'></td>"+
					"<td><input name=\"button5\" type='button' value=\" : \" class='btnCss'></td>"+
					"<td><input name=\"button7\" type='button' value=\" &quot; \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ; \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ' \" class='btnCss'></td>"+
					"<td rowspan=\"2\" colspan=\"2\"><input name=\"button12\" type='button' onclick=\"OverInput();\" value=\"   确定  \" style=\"width:126px;height:44px;\"></td>"+
				"</tr>"+
				"<tr align=\"left\" valign=\"middle\">"+
					"<td><input name=\"button2\" type='button' value=\" z \" class='btnCss'></td>"+
					"<td><input type='button' value=\" x \" class='btnCss'></td>"+
					"<td><input type='button' value=\" c \" class='btnCss'></td>"+
					"<td><input type='button' value=\" v \" class='btnCss'></td>"+
					"<td><input type='button' value=\" b \" class='btnCss'></td>"+
					"<td><input type='button' value=\" n \" class='btnCss'></td>"+
					"<td><input type='button' value=\" m \" class='btnCss'></td>"+
					"<td><input type='button' value=\" &lt; \" class='btnCss'></td>"+
					"<td><input type='button' value=\" &gt; \" class='btnCss'></td>"+
					"<td><input type='button' value=\" ? \" class='btnCss'></td>"+
					"<td><input type='button' value=\" , \" class='btnCss'></td>"+
					"<td><input type='button' value=\" . \" class='btnCss'></td>"+
					"<td><input type='button' value=\" / \" class='btnCss'></td>"+
				"</tr>"+
			"</table>"+
		"</td>"+
	"</FORM>"+
	"</tr></table>"+
	"</DIV>")



function addValue(newValue){
	if (CapsLockValue==0){
		var str=Calc.password.value;
		if(str.length<password1.maxLength){Calc.password.value += newValue;}			
		if(str.length<=password1.maxLength){password1.value=Calc.password.value;}
	}else{
		var str=Calc.password.value;
		if(str.length<password1.maxLength){Calc.password.value += newValue.toUpperCase();}
		if(str.length<=password1.maxLength){password1.value=Calc.password.value;}
	}
}


function setpassvalue(){
	var longnum=Calc.password.value.length;
	var num;
	num=Calc.password.value.substr(0,longnum-1);
	Calc.password.value=num;
	var str=Calc.password.value;
	password1.value=Calc.password.value;
}


function OverInput(){
	var str=Calc.password.value;
	password1.value=Calc.password.value;
	softkeyboard.style.display="none";
	Calc.password.value="";
	password1.readOnly=0;
	password1.focus();
}


function showkeyboard(){
	if(event.y+140)softkeyboard.style.top=event.y+document.body.scrollTop+15;
	if((event.x-250)>0){softkeyboard.style.left=event.x-250;}
	else{softkeyboard.style.left=0;}
	softkeyboard.style.display="block";
	password1.readOnly=1;
	password1.blur();
}


function setCapsLock(){
	if (CapsLockValue==0){
		CapsLockValue=1;
	}else{
		CapsLockValue=0;
	}
}


function setCalcborder(){
	CalcTable.style.border="1px solid #0090FD"
}



function setHead(){
	CalcTable.cells[0].style.backgroundColor="#7EDEFF"	
}


function setCalcButtonBg(){
	for(var i=0;i<Calc.elements.length;i++){
		if(Calc.elements[i].type=="button"&&Calc.elements[i].bgtype!="1"){
			Calc.elements[i].style.borderTopWidth= 0
			Calc.elements[i].style.borderRightWidth= 2
			Calc.elements[i].style.borderBottomWidth= 2
			Calc.elements[i].style.borderLeftWidth= 0
			Calc.elements[i].style.borderTopStyle= "none";
			Calc.elements[i].style.borderRightStyle= "solid";
			Calc.elements[i].style.borderBottomStyle= "solid";
			Calc.elements[i].style.borderLeftStyle= "none";
			Calc.elements[i].style.borderTopColor= "#118ACC";
			Calc.elements[i].style.borderRightColor= "#118ACC";
			Calc.elements[i].style.borderBottomColor= "#118ACC";
			Calc.elements[i].style.borderLeftColor= "#118ACC";
			Calc.elements[i].style.backgroundColor="#ADDEF8";
			var str1=Calc.elements[i].value;
			str1=str1.trim();
			var thisButtonValue=Calc.elements[i].value;
			thisButtonValue=thisButtonValue.trim();
			if(thisButtonValue.length==1){
				Calc.elements[i].onclick=function(){var thisButtonValue=this.value;thisButtonValue=thisButtonValue.trim();addValue(thisButtonValue);}
				Calc.elements[i].ondblclick=function(){var thisButtonValue=this.value;thisButtonValue=thisButtonValue.trim();addValue(thisButtonValue);}
			}
		}
	}
}



function initCalc(){
	setCalcborder();
	setHead();
	setCalcButtonBg();
}


String.prototype.trim = function(){return this.replace(/(^\s*)|(\s*$)/g, "");}

var capsLockFlag;
capsLockFlag=true;



function capsLockText(){
	if(capsLockFlag){
		for(var i=0;i<Calc.elements.length;i++){
		var char=Calc.elements[i].value;
		var char=char.trim();
			if(Calc.elements[i].type=="button"&&char>="a"&&char<="z"&&char.length==1){
				Calc.elements[i].value=" "+String.fromCharCode(char.charCodeAt(0)-32)+" ";
			}
		}
	}else{
		for(var i=0;i<Calc.elements.length;i++){
			var char=Calc.elements[i].value;
			var char=char.trim()
			if(Calc.elements[i].type=="button"&&char>="A"&&char<="Z"&&char.length==1){
				Calc.elements[i].value=" "+String.fromCharCode(char.charCodeAt(0)+32)+" ";
			}
		}
	}
	capsLockFlag=!capsLockFlag;
}