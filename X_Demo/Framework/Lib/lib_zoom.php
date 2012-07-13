<?php
if (!class_exists("CoreWidget")) {
	class CoreWidget {
		var $wName;
	}
}
	 
/* 

Class ADvance Select 

*/
class avSelect extends CoreWidget {
	
	var $cWname;
	
	
	/*
	 
	 avSelectPrint($name,$module,$class,$show,$current,$method='')
	
	returns an invisible iframe linking to Zoom Data Modules.
	Compatbile CSS whith most Browsers.
	Parameters are:
	
	name: 		name of input
	module: 	module where to look data
	class: 		class data source
	show: 		field to be shown
	current:	current value (ID)
	method:		if data has to be mined by this method (defaults to selectAll())
		
	*/

	function avSelectPrint($name,$module,$class,$show,$current,$method=''){
	
		global $SYS;
		
		if ($_SESSION["GLOBAL"]["zoom"]=="popup") {
			return $this->avSelectWPrint($name,$module,$class,$show,$current,$method);
		}
		
		$cdata=newObject("$class",$current);
		$dsize=strlen($cdata->$show)*1.5+5;
		$cmethod=urlencode($method);
		$res=  
		"
		
		<input type=\"hidden\" size=\"11\" maxlength=\"11\" name=\"$name\" id=\"Target$class$name\" value=\"$current\">
		<input type=\"text\" size=\"$dsize\" name=\"_t_$name\" id=\"wTarget$class$name\" value=\"".$cdata->$show."\" readonly>
		<span onclick=\"a=document.getElementById('wFrame$class$name');a.style.display=(a.style.display=='none')?'':'none';wFrame$class$name.wresize()\"
		style=\"cursor:pointer;background-color:white;border:1px solid gray\">&nbsp;+&nbsp;</span>
		
		<iframe src=\"{$SYS["ROOT"]}/Framework/Extensions/Zoom/Zoom.php?module=$module&class=$class&show=$show&current=$current&name=$name&method=$cmethod\" id=\"wFrame$class$name\" width=\"400\" name=\"wFrame$class$name\"  height=\"300\"  border=\"0\" style=\"display:none;position:absolute;\" ></iframe>";
		return $res;
	}
	
	/*
	 
	 avSelectPrint($name,$module,$class,$show,$current,$method='')
	
	returns window.open linking to Zoom Data Modules.
	Compatbile CSS whith most Browsers.
	Parameters are:
	
	name: 		name of input
	module: 	module where to look data
	class: 		class data source
	show: 		field to be shown
	current:	current value (ID)
	method:		if data has to be mined by this method (defaults to selectAll())
		
	*/
	function avSelectWPrint($name,$module,$class,$show,$current,$method=''){
	
		global $SYS;
		$cdata=newObject("$class",$current);
		$dsize=strlen($cdata->$show)*1.5+5;
		
		return  
		"
		
		<input type=\"hidden\" size=\"11\" maxlength=\"11\" name=\"$name\" id=\"Target$class$name\" value=\"$current\">
		<input type=\"text\" size=\"$dsize\" name=\"_t_$name\" id=\"wTarget$class$name\" value=\"".$cdata->$show."\" readonly>
		<span onclick='window.open(\"{$SYS["ROOT"]}/Framework/Extensions/Zoom/ZoomW.php?module=$module&class=$class&show=$show&current=$current&name=$name&method=$method\",\"wFrame$class$name\",\"width=400,height=300,toolbar=0,scrollbar=0\")'
		style=\"cursor:pointer;background-color:white;border:1px solid gray\">&nbsp;+&nbsp;</span>
		";
		
	}
	
	/*
	 
	show($name,$module,$class,$show,$current,$method='')
	
	alias of avSelectPrint
	
	returns an invisible iframe linking to Zoom Data Modules.
	Compatible CSS whith most Browsers.
	Parameters are:
	
	name: 		name of input
	module: 	module where to look data
	class: 		class data source
	show: 		field to be shown
	current:	current value (ID)
	method:		if data has to be mined by this method (defaults to selectAll())
		
	*/
	
	function show($name,$module,$class,$show,$current,$method=''){
		return avSelectPrint($name,$module,$class,$show,$current,$method);
	}
	
}
?>
