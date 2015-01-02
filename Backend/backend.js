function fReload() {

	try {
		eval(currentView+".removeDesktop('parent."+currentView+".location.reload()')");
	} catch (idontcare) {}

	
  
}

function fPrint() {

  eval(currentView+".print()");

  
}
function fBack() {

 try {
		eval(currentView+".removeDesktop()");
	} catch (idontcare) {}

	try {
		eval(currentView+".history.go(-1)");
	} catch (idontcare) {}
  
}


function dev_go(url) {
	try {
		eval(currentView+".removeDesktop()");
	} catch (idontcare) {}

	try {
		eval(currentView+".location.href='"+url+"'");
	} catch (idontcare) {}
	
}
function getBodyLocation() {

	return fbody.location.href;
	
}
function setBodyLocation(url) {

	fbody.location.href=url;
	
}

function SizeH() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  //window.alert( 'Width = ' + myWidth );
  //window.alert( 'Height = ' + myHeight );
  return myHeight;
}

function SizeW() {
  var myWidth = 0, myHeight = 0;
  if( typeof( window.innerWidth ) == 'number' ) {
    //Non-IE
    myWidth = window.innerWidth;
    myHeight = window.innerHeight;
  } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
    //IE 6+ in 'standards compliant mode'
    myWidth = document.documentElement.clientWidth;
    myHeight = document.documentElement.clientHeight;
  } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
    //IE 4 compatible
    myWidth = document.body.clientWidth;
    myHeight = document.body.clientHeight;
  }
  //window.alert( 'Width = ' + myWidth );
  //window.alert( 'Height = ' + myHeight );
  return myWidth;
}

function wResizeAll() {
		var s;
		var r;
		
		
		
		s=SizeH()-22-22-34;
		
		try{
			f=document.getElementById("fbody").style.height=s+"px";
		}
		catch(e){console.log("wResizeAll 1:"+s+' '+e)};
		
		r=SizeW()-5;
		try{
			f=document.getElementById("statusbar").style.width=r+"px";
		    f=document.getElementById("fbody").style.width=r+"px";
			f=document.getElementById("footer").style.width=r+"px";
		
		}
		catch(e){console.log("wResizeAll 2 : "+r+' '+e)};
		window.onresize=wResizeAll;
 	}

function wResizeFrame(fr) {
		var s;
		var r;
		
		s=SizeH()-22-22-34;
		
		try{
			frams=document.getElementsByTagName("iframe");
			for (i=0;i<frams.length;i++) { 
				if (frams[i].id.indexOf("fbody")==0) {
					frams[i].style.height=s+"px";
				}
			}
			//f=document.getElementById(fr).style.height=s+"px";
		}
		catch(e){
			//alert(s+' '+e)
		};
		
		r=SizeW()-5;
		try{
			f=document.getElementById("statusbar").style.width=r+"px";
		    f=document.getElementById(fr).style.width=r+"px";
			f=document.getElementById("footer").style.width=r+"px";
		
		}
		catch(e){console.log(r+' '+e)};
		window.onresize=wResizeAll;
 	}	

var _opa_i=0;
var sw=0;

function setOpacity(elemento,value) {
		testObj=document.getElementById(elemento);
		testObj.style.opacity = value/10;
		testObj.style.KhtmlOpacity = value/10;
		testObj.style.filter = 'alpha(opacity=' + value*10 + ')';
}
function opaShow(elemento) {
	
	//if ((!sw)||(sw==0)) {
		if (_opa_i<=10) {
			setOpacity(elemento,_opa_i);
			_opa_i++;
			setTimeout("opaShow('"+elemento+"')", 25);
		}
		else
			_opa_i=0;
		
}

function openWindow(link,sourcelink) {
	
	sourcelinkF=sourcelink.replace(/ /g,'_');
	frams=document.getElementsByTagName("iframe");
	
	for (i=0;i<frams.length;i++) { 
		if (frams[i].id.indexOf("fbody")==0) {
			frams[i].style.display="none";
		}
	}
	a=document.getElementById("fbody");
	already=document.getElementById(sourcelinkF);
	if (already==null) {
		b=a.cloneNode(true);
		b.id=sourcelinkF;
		b.name=sourcelinkF;
		document.body.appendChild(b);
		b.src=link;
		b.style.display="block";
		wResizeFrame(sourcelinkF);
		putWindowTab(sourcelinkF,link);
		
	} else {
		b=already;
		b.style.display="block";
		if (b.src.indexOf(link)==false)
			b.src=link;
		else {
			try {
				eval(sourcelinkF+".showDesktop()");
			} catch (idontcare) {};
		}
		wResizeFrame(sourcelinkF);
		
	}

	renderWindowTab(sourcelinkF);
}

function putWindowTab(elemento,dlink) {
	sourcelinkF=elemento.replace(/ /g,'_');
	if (WindowTab[sourcelinkF]==undefined)
		WindowTab[sourcelinkF]=dlink;
}

function removeWindowTab(elemento) {
	sourcelinkF=elemento.replace(/ /g,'_');
	if (WindowTab[sourcelinkF]!=undefined)
		WindowTab[sourcelinkF]=undefined;
}

function closeWindow(elemento) {
	sourcelinkF=elemento.replace(/ /g,'_');
	if (WindowTab[sourcelinkF]!=undefined) {
		try {
			sf=document.getElementById(sourcelinkF);
			eval(sourcelinkF+".removeDesktop()");
		} catch (idontcare) {};

	}

	if (WindowTab[sourcelinkF]!=undefined) {
		WindowTab[sourcelinkF]=undefined;
		sf=document.getElementById(sourcelinkF);
		sf.parentNode.removeChild(sf);

	}

	for (i in WindowTab) 
		if (WindowTab[i]!=undefined) {
			b=document.getElementById(i);
			b.style.display="block";
			renderWindowTab(i);
			wResizeFrame(i);
			return;
		}
			

	/* No more left */
	i="fbody";
	b=document.getElementById(i);
	b.style.display="block";
	wResizeFrame("fbody");
	renderWindowTab("fbody");
	
}


function renderWindowTab(currentTab) {
	sourcelinkF=currentTab.replace(/ /g,'_');
	var buffer="";
    currentView=(sourcelinkF);
	for (i in WindowTab) {
		if (WindowTab[i]!=undefined) {
			label=i.replace("fbody","").replace(/_/g,' ');
			
			if ((sourcelinkF!=undefined) && (sourcelinkF==i))
				buffer+="<div class='windowListTab windowListTabActive'><a  href=\"javascript:openWindow('"+WindowTab[i]+"','"+i+"')\">"+label+"</a> <a class='windowCloseLink' href=\"javascript:closeWindow('"+i+"')\">x</a></div>";
			else
				buffer+="<div class='windowListTab'><a class='windowListTab' href=\"javascript:openWindow('"+WindowTab[i]+"','"+i+"')\">"+label+"</a> <a class='windowCloseLink'  href=\"javascript:closeWindow('"+i+"')\">x</a></div>";
		}
	document.getElementById("windowlist").innerHTML=buffer;
	}
}

var WindowTab=new Array();
var currentView=null;