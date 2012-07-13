<?php
/* Special module for Zooming Data */
/* Variables

$module: Module class belongs to
$class: Class to be data-listed
$show:	Prpertie to be shown
$method:Metohd to execute, selectAll by default
$name: Name of input to be modified
*/



function tMdP($current,&$res,$link="",$max=0) {
	global $class,$offset,$sort,$cclass,$ID;
	
	if ($max>4)
		return true;
		
	$p=newObject("$class",$current,$max++);
	$res=" <a  href=\"$link&$cclass=0\" style=\"text-decoration:none;\">Inicio</a>";
	
		
	if ($p->$cclass<2) {
		$res.="-&#062;<a href=\"$link&$cclass={$p->ID}\" style=\"text-decoration:none;\">{$p->nombre}</a>";
		return true;
	}
	else {
		
		tMdP($p->$cclass,&$res,$link,$max);
		$res.="-&#062;<a href=\"$link&$cclass={$p->ID}\" style=\"text-decoration:none;\">{$p->nombre}</a>";
	}
	
	return true;
}

$SYS["PROJECT"]=($SYS["PROJECT"])?$SYS["PROJECT"]:"$module";

require_once("coreg2.php");
require_once($SYS["DOCROOT"]."/../Apps/".$module."/".$module.".php");

$u=newObject("$class");
$dynCat=(isset($$cclass))?$$cclass:0;
setNavVars(array("module","class","show","method","name","cclass","$cclass","avsearch"));	
if($dynCat>0)
{
	$c=newObject($class,$dynCat);
	tMdP(&$c->ID,&$u->MDP,$SYS["ROOT"]."Framework/Extensions/Tree/Tree.php?module=".$module."&".$cclass."=".$dynCat."&class=".$class."&cclass=$cclass"."&show=".$show."&name=".$name."&method=".$method."");
	
}

if (empty($method))

	if (empty($avsearch))
		$u->searchResults=$u->select("$cclass=$dynCat",$offset,$sort);
	else {
		$fields=($u->properties);
		foreach ($fields as $k=>$v)  {
			$xmultiquery[]="$k LIKE '%$avsearch%' ";	
		}
		$multiquery=implode(" OR ",$xmultiquery);
		$u->searchResults=$u->select("$multiquery",$offset,$sort);

	}

	
else {
	echo "method :$method:";
	$method_x=explode("(",$method);
	$method_n=$method_x[0];
	$method_xx=explode(")",$method_x[1]);
	$method_xxx=explode(")",$method_xx[0]);
	$method_p=implode("','",$method_xxx);
	
	if (!empty($avsearch)) {
		debug("Especial method called ".'
		$u=newObject("'.$class.'");
		return $u->'.$method_n.'AVSEARCH("'.$avsearch.'",'.$method_p.');
		',"cyan");
		if ($method_p)
			$method_pe=",".$method_p;
			
		$u->searchResults=eval('
		$u=newObject("'.$class.'");
		if (method_exists($u,"'.$method_n.'AVSEARCH"))
			return $u->'.$method_n.'AVSEARCH("'.$avsearch.'"'.$method_pe.');
		else
			return $u->'.$method_n.'('.$method_p.');
			
		');

	}
	else {
	
		$u->searchResults=eval('
	$u=newObject("'.$class.'");
	return $u->'.$method_n.'('.$method_p.');
	');

	}


	debug("Especial method called ".'
$u=newObject("'.$class.'");
return $u->'.$method_n.'('.$method_p.');
',"cyan");
}


$className=$class;
$showName=$u->properties_desc["$show"];

$template="
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<!--HEAD-->
<html>
<head>
<title>Zoom $class </title>
<LINK href=\"{$SYS["ROOT"]}/Themes/Estilos/global.css\" rel=\"stylesheet\" type=\"text/css\">
</head>

<script type=\"text/javascript\" language=\"JavaScript1.3\">

function frameClose(){
	parent.document.getElementById(\"wFrame$class$name\").style.display=\"none\";
}
function selItem(id,val){
	parent.document.getElementById(\"Target$class$name\").value=id;
	parent.document.getElementById(\"wTarget$class$name\").value=val;
	s=new String(val);
	
	parent.document.getElementById(\"wTarget$class$name\").size=(s.length)+5;
	frameClose();
}

function noaction(){
	return;
}
function refresh(){
	location.href=location.href+'&reload';
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
	<b>$showName ($className).</b>
	</td>
</tr>
<tr>
<td valign=\"top\">
	<table width=\"100%\" >
	<tr>
	<td colspan=\"3\">Categoria:&nbsp;<!-- D:MDP --></td>
</tr>
	<tr>
	
	<td bgcolor=\"white\" align=\"center\"><a href=\"<!-- N:navvars -->&sort=".$show."\">
	<!-- K:_(Ordenar) --></a></td>
	<td bgcolor=\"white\"  align=\"center\">seleccionar</td>
	</tr>
	<!--SET-->
	<tr>
		
		<td class=\"<!-- dynamic_class -->\" width=\"100%\" nowrap>
		<!-- I:subtree -->
		<a 
	style=\"cursor:pointer;background-color:white;border:1px solid gray\"
	href=\"<!-- D:ROOT -->/Framework/Extensions/Tree/Tree.php?module=".$module."&class=".$class."&show=".$show."&current=".$current."&name=".$name."&method=".$cmethod."&".$cclass."=<!-- D:ID -->&cclass=".$cclass."\" id=\"wFrame".$class.$name."\" width=\"400\" name=\"wFrame".$class.$name."\"  height=\"300\"  border=\"0\" style=\"display:none;position:absolute;\" \">&nbsp;+&nbsp;</a>
		<!-- I:subtree -->
		<!-- L:".$show." -->
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
<td>
<form action=\"\" method=\"POST\">
buscar: <INPUT type=\"text\" name=\"avsearch\" value=\"$avsearch\">
<INPUT type=\"submit\">
</form>
</td>
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


//$u->searchResults=$u->subTree();
if (!function_exists("HasChildren")) {

	function HasChildren($ID) {
		global $class,$offset,$sort,$cclass;
		$ux=newObject("$class");
		$ux->select("$cclass=$ID",$offset,$sort);
		return $ux->nRes;
		
	}

}
$ed=array(
	"subtree"=>'code#return HasChildren($object->ID);');

listList($u,$ed,$template);		

?>
<script type="text/javascript" language="JavaScript1.3">
wresize();
</script>
<a  style="cursor:pointer;position:absolute;right:3px;top:2px;float : right;" href="javascript:frameClose()">
<img src="<?php echo $SYS["ROOT"]."/Framework/Extensions/Zoom/local/Images/close.png"?>" alt="Cerrar" width="16" height="16" align="middle" border="0" title="Centrar"></a>
<a  style="cursor:pointer;position:absolute;left:3px;top:2px;float : right;" href="javascript:wresize()">
<img src="<?php echo $SYS["ROOT"]."/Framework/Extensions/Zoom/local/Images/center.png"?>" alt="Centrar" width="16" height="16" align="middle" border="0" title="Centrar"></a>

<a  style="cursor:pointer;position:absolute;left:19px;top:2px;float : right;" href="javascript:refresh()">
<img src="<?php echo $SYS["ROOT"]."/Framework/Extensions/Zoom/local/Images/refresh.png"?>" alt="Actualizar" width="16" height="16" align="middle" title="Actualizar" border="0"></a>
</body>