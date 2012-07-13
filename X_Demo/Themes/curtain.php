<?php
require_once("coreg2.php");

if ($_SESSION["GLOBAL"]["curtain_effect"]=='No') {
?>
	document.getElementById("cortina").style.display="none";		
<?php
die();	
}
?>
onload=progressLoad;
		
var _ct_i=10;
var _ct_j=0;

function setOpacity(value) {
		testObj=document.getElementById("cortina");
		testObj.style.opacity = value/10;
		testObj.style.KhtmlOpacity = value/10;
		testObj.style.filter = 'alpha(opacity=' + value*10 + ')';
}
function opaShow() {
	
	testObj=document.getElementById("fakedbody");
	testObj.style.display="none";
	testObj=document.getElementById("footer");
	testObj.style.display="none";
	document.body.style.display="none";
	document.body.style.display="";
	
	/*window.scroll(0,0);
	for (i=10000;i>0;i--) {
		window.scroll(i,0);    
		
	}*/
			
	return false;
}

function progressLoad() {
	if (_ct_j==0) {
			
			setTimeout("progressLoad()", 1000);
			_ct_j=1;
			return true;
	}
	
	if (_ct_i>-1) {
		
		setOpacity(_ct_i);   
		_ct_i--;
		setTimeout("progressLoad()", 5);
		
	}
	else {
		testObj=document.getElementById("cortina");
		testObj.style.display="none";
	}
				
	return true;
}

function progressunLoad() {
	
	testObj=document.getElementById("cortina");
	testObj.style.display="";
	for (x=-1;x<11;x++) {
		setOpacity(x);   
		
	}
	
				
	return true;
}
document.getElementById("cortina").style.display="";
