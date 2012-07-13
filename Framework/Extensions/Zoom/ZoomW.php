<?php
/* Special module for Zooming Data */
/* Variables

$module: Module class belongs to
$class: Class to be data-listed
$show:	Prpertie to be shown
$method:Metohd to execute, selectAll by default
$name: Name of input to be modified
*/

$SYS["PROJECT"]=($SYS["PROJECT"])?$SYS["PROJECT"]:"$module";
$void_framming=true;
require_once("coreg2.php");
require_once($SYS["BASE"]."/Apps/".$module."/".$module.".php");
$u=newObject("$class");
if (empty($method))
	$u->searchResults=$u->selectAll($offset,$sort);
else {
	$method_x=explode("(",$method);
	$method_n=$method_x[0];
	$method_xx=explode(")",$method_x[1]);
	$method_xxx=explode(")",$method_xx[0]);
	$method_p=implode("','",$method_xxx);
	
	$u->searchResults=eval('
$u=newObject("'.$class.'");
return $u->'.$method_n.'('.$method_p.');
');
	debug("Especial method called ".'
$u=newObject("'.$class.'");
return $u->'.$method_n.'('.$method_p.');
',"cyan");
} 
setNavVars(array("module","class","show","method","name"));
$SYS["FROOT"]=$SYS["ROOT"]."../../../";
$template="
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<!--HEAD-->
<html>
<head>
<title>Zoom $class </title>
<LINK href=\"{$SYS["FROOT"]}/Themes/Estilos/global.css\" rel=\"stylesheet\" type=\"text/css\">
</head>

<script type=\"text/javascript\" language=\"JavaScript1.3\">

function frameClose(){
	window.close();
}
function selItem(id,val){
	opener.document.getElementById(\"Target$class$name\").value=id;
	opener.document.getElementById(\"wTarget$class$name\").value=val;
	s=new String(val);
	
	opener.document.getElementById(\"wTarget$class$name\").size=s.length+5;
	frameClose();
}

function noaction(){
	return;
}
function wresize() {
	s=document.getElementById('mainView$class$name').clientHeight+10;
	w=parent.document.documentElement.clientWidth;
	ww=document.getElementById('mainView$class$name').clientWidth;
	if (window.scrollbars==1) {
		parent.document.getElementById(\"wFrame$class$name\").style.width=500+\"px\";
		
	}
	
	if (parent.document.body.scrollTop<=0)
		h=parent.document.documentElement.scrollTop+(parent.document.documentElement.clientHeight/2-s/2);
	else
		h=parent.document.body.scrollTop+(parent.document.documentElement.clientHeight/2-s/2);
	
	
	y=h;
	x=((w/2)-(ww/2));
	if (y<25)
		y=25;
	parent.document.getElementById(\"wFrame$class$name\").style.left=x+\"px\";
	parent.document.getElementById(\"wFrame$class$name\").style.height=s+\"px\";
	parent.document.getElementById(\"wFrame$class$name\").style.top=y+\"px\";
	
	/* Repaint */
	
}
</script>
<style>
td.dynamic_class_autotemplate0 {text-align:left;vertical-align:top;background-color:#EEEEF6}
td.dynamic_class_autotemplate1 {text-align:left;vertical-align:top;background-color:white}
td.dynamic_class_autotemplate2 {text-align:left;vertical-align:top;background-color:#F7F7F7}
td.dynamic_class_autotemplate3 {text-align:left;vertical-align:top;background-color:white}
</style>
<body bgcolor=\"#FFFFFF\" style=\"padding:0px;margin:0px;\">
<table width=\"100%\" id=\"mainView$class$name\">
<tr>
	<td align=\"center\" style=\"background-color:#4B85B8;color:white\" colspan=\"2\" height=\"16\">
	<b>Seleccione datos: ".$class."-".$show."</b>
	</td>
</tr>
<tr>
<td valign=\"top\">
	<table width=\"100%\" >
	
	<tr>
	<td bgcolor=\"white\"><a href=\"<!-- N:navvars -->&sort=ID\">ID</a></td>
	<td bgcolor=\"white\" align=\"center\"><a href=\"<!-- N:navvars -->&sort=".$show."\">
	<!-- K:_(Ordenar) --></a></td>
	<td bgcolor=\"white\"  align=\"center\">seleccionar</td>
	</tr>
	<!--SET-->
	<tr>
		<td class=\"<!-- dynamic_class -->\">
			<!-- D:ID -->
		</td>
		<td class=\"<!-- dynamic_class -->\" width=\"100%\" nowrap>
			<!-- D:".$show." -->
		</td>
		<td class=\"<!-- dynamic_class -->\" style=\"text-align:center\">
			<a 
	style=\"cursor:pointer;background-color:white;border:1px solid gray\"
	href=\"javascript:selItem('<!-- D:ID -->','<!-- D:".$show." -->');\">&nbsp;+&nbsp;</a>
		</td>
	</tr>
	<!--END-->
	</table>
	</td>
	<td width=\"1\" height=\"200\"></td>
</tr>
<tr>
	<td colspan=\"2\">
	<table  width=\"100%\" cellspacing=\"0\" border=\"0\" cellpadding=\"4\" align=\"center\" style=\"border:solid 1px gray\">
	<tr><td align=\"left\">
	<a href=\"<!-- N:prevpage -->\">Página anterior</a>
	</td>
	<td align=\"center\"><!-- D:Pager -->
	</td><td align=\"right\">
	<a href=\"<!-- N:nextpage -->\">Página siguiente</a>
	</td>
	</tr>
	</table>
	</td>
</tr>
</table>

";
		
listList($u,array(),$template);		

?>
</body>