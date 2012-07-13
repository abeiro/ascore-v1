function dev_go() {
	current=new String(parent.fbody.location.href);
	pos=current.lastIndexOf("/");
	go=new String(current.slice(0,pos+1));
	alert(go);
	/*go="dev.php";
	parent.fbody.location.href=go;*/
}

function help_go() {
	current=new String(parent.body.location.href);
	pos=current.lastIndexOf("/");
	go=new String(current.slice(0,pos+1));
	go=go+"help.php";
	parent.body.location.href=go;
}

function unLoading() {
	doc=parent.statusbar.document;
	doc.getElementById('semaforo').style.backgroundColor='red';
	doc.getElementById('semaforo').src='Data/Img/Icons/think.gif';
}


function endLoad() {
	doc=parent.statusbar.document;
	doc.getElementById('semaforo').style.backgroundColor='green';
	doc.getElementById('semaforo').src='Data/Img/Icons/ok.gif';
}

function setProgress(val) {
	doc=parent.statusbar.document;
	if (val>98)
		val=98;
	doc.getElementById('progress').style.paddingLeft=val+'px';
	if (val!=0)
		doc.getElementById('progress').style.backgroundColor='#2A81B7';
	else
		doc.getElementById('progress').style.backgroundColor='#F6F6EE';
}
	


/*must_r=false;
try {a=parent.statusbar.document;}
	catch (e) {must_r=true;}
try {b=a.getElementById('semaforo');}
	catch (e) {must_r=true;}

	
if (must_r==true) {
	current=new String(location.href);
	p=current.lastIndexOf("void_framming");
        
	if (p==-1) {
		pos=current.lastIndexOf("?");
		if (pos>0)
			location.href=location.href+"&rearrangeme=yes";
		else
			location.href=location.href+"?rearrangeme=yes";
	}
	
}
*/


 
