/* 【【【【face.js】】】】 */
var faceStartNum=1; faceEndNum=30;

function FaceInit(faceId,inputId){
	var faceStr = "";
	for (var i=faceStartNum; i<=faceEndNum; i++){
		faceStr += "<img src='"+ webPathPart +"inc_img/face_def/"+ i + ".gif' border='0' style='margin:1px;' class='pointer' onclick=\"FocusAddText('"+ inputId +"','[face:"+ i + "]');\" alt='[face:"+ i + "]' />";
	}
	$id(faceId).innerHTML = faceStr;
	
}

function FaceShow(faceId,inputId){
	if ($id(faceId).innerHTML==""){
		FaceInit(faceId,inputId);
		$id(faceId).style.display="";
	}else{
		if ($id(faceId).style.display==""){
			$id(faceId).style.display="none";
		}else{
			$id(faceId).style.display="";
		}
	}
}

function FaceSignToImg(innerId){
	innerStr = $id(innerId).innerHTML;
	var reg,stringObj,newStr; 

	for (var i=faceStartNum; i<=faceEndNum; i++){
		reg=new RegExp("[face:"+ i +"]","g"); //创建正则RegExp对象
		newstr=innerStr.replace(reg,"<img src='"+ webPathPart +"inc_img/face_def/"+ i + ".gif' border='0' />"); 
	}
	$id(innerId).innerHTML=newstr;
}





/* 【【【【inc/smoothscroll.js】】】】 */

Scroller = {
	// control the speed of the scroller.
	// dont change it here directly, please use Scroller.speed=50;
	speed:10,

	// returns the Y position of the div
	gy: function (d) {
		gy = d.offsetTop
		if (d.offsetParent) while (d = d.offsetParent) gy += d.offsetTop
		return gy
	},

	// returns the current scroll position
	scrollTop: function (){
		body=document.body
	    d=document.documentElement
	    if (body && body.scrollTop) return body.scrollTop
	    if (d && d.scrollTop) return d.scrollTop
	    if (window.pageYOffset) return window.pageYOffset
	    return 0
	},

	// attach an event for an element
	// (element, type, function)
	add: function(event, body, d) {
	    if (event.addEventListener) return event.addEventListener(body, d,false)
	    if (event.attachEvent) return event.attachEvent('on'+body, d)
	},

	// kill an event of an element
	end: function(e){
		if (window.event) {
			window.event.cancelBubble = true
			window.event.returnValue = false
      		return;
    	}
	    if (e.preventDefault && e.stopPropagation) {
	      e.preventDefault()
	      e.stopPropagation()
	    }
	},
	
	// move the scroll bar to the particular div.
	scroll: function(d){
		i = window.innerHeight || document.documentElement.clientHeight;
		h=document.body.scrollHeight;
		a = Scroller.scrollTop()
		if(d>a)
			if(h-d>i)
				a+=Math.ceil((d-a)/Scroller.speed)
			else
				a+=Math.ceil((d-a-(h-d))/Scroller.speed)
		else
			a = a+(d-a)/Scroller.speed;
		window.scrollTo(0,a)
	  	if(a==d || Scroller.offsetTop==a)clearInterval(Scroller.interval)
	  	Scroller.offsetTop=a
	},
	// initializer that adds the renderer to the onload function of the window
	init: function(){
		Scroller.add(window,'load', Scroller.render)
	},

	// this method extracts all the anchors and validates then as # and attaches the events.
	render: function(){
		a = document.getElementsByTagName('a');
		Scroller.end(this);
		window.onscroll
		for (i=0;i<a.length;i++) {
			l = a[i];
			if(l.href && l.href.indexOf('#') != -1 && ((l.pathname==location.pathname) || ('/'+l.pathname==location.pathname)) ){
				if (l.href.indexOf('#scroll_') != -1){
					Scroller.add(l,'click',Scroller.end)
					l.onclick = function(){
						Scroller.end(this);
						l=this.hash.substr(1);
						a = document.getElementsByTagName('a');
						for (i=0;i<a.length;i++) {
							if(a[i].name == l){
								clearInterval(Scroller.interval);
								Scroller.interval=setInterval('Scroller.scroll('+Scroller.gy(a[i])+')',10);
							}
						}
					}
				}
			}
		}
	}
}
// invoke the initializer of the scroller
Scroller.init();
