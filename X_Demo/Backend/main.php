<?php

/*********************** 
Extended URI featuring

MODULE
APP
ACTION
************************/


require_once("coreg2.php");	
set_include_dir(dirname(__FILE__)."/local/Tmpl/-");

require_once("Bilo/API_exports.php");
set_include_dir(dirname(__FILE__)."/Bilo/-");
require_once("Lib/lib_session.php");

//setLimitRows(20);
$up=newobject("user_pref");
$up->getPrefByUser(BILO_uid());
$up->setPrefs();

if ((!$isLoginScreen)&&(!$SYS["GLOBAL"]["void_login"])) {
		if (BILO_isLogged()==false) {
			PlantHTML(array("location"=>$SYS["ROOT"]."/Login/login.php"),"redirect");
			die();
		} else echo '';/*
		else if ((BILO_isOperator())||(BILO_isAdmin())) {
			echo '';	
		}
		else {
			PlantHTML(array("location"=>$SYS["ROOT"]."/Login/login.php"),"opener");
			die();
		}
*/
	}

/*if ((!BILO_isAdmin())&&(!BILO_isOperator()))
	 die(_("Sin privilegios"));*/
	
$EURI=explode("/",$petition);

/* 

Creaci�n de men�s din�mico

*/


if (is_dir(dirname(__FILE__)."/../Apps/$APP/$ACTION")) {

	$ACTION.="/{$EURI[3]}";

}

if (is_file(dirname(__FILE__)."/../Apps/$APP/$ACTION")) {
	if (strpos($ACTION,"action_")!==false)
		include(dirname(__FILE__)."/../Apps/$APP/$ACTION");
	else if (($SYS["GLOBAL"]["DEV_MODE"])&&(strpos($ACTION,"dev.php"))) {
		die("dev");
		include(dirname(__FILE__)."/../Apps/$APP/$ACTION");
	
	}
	else {
		if ($print_mode)
			plantHTML(array("PATH"=>$SYS["ROOT"]),"f_menu");
		else
			plantHTML(array("PATH"=>$SYS["ROOT"]),"f_menu_curtain");
		include(dirname(__FILE__)."/../Apps/$APP/$ACTION");
		plantHTML(array("PATH"=>$SYS["ROOT"]),"footer");
	}
}
else if (empty($APP)){
	
	$metadata=array("page"=>"empty.php");
	/* Build menu entry system */
	foreach($SYS["APPS"] as $v=>$k) {
		if (!is_file($SYS["BASE"]."/Apps/".$k."/admin_menu_entry.php"))
			continue;
		require($SYS["BASE"]."/Apps/".$k."/admin_menu_entry.php");
		if (isset($menu_entry[0])) {
				foreach($menu_entry as $menuvalue) 
					if ($menuvalue["active"]) {
						$metadata["menu_entrys"].="\n\t<li><span class=\"dir\">{$menuvalue["label"]}</span>";
						$metadata["menu_entrys"].=build_submenus($menuvalue["items"],$k);
						$metadata["menu_entrys"].="\n\t<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>\n";
					}
				}else {
					if ($menu_entry["active"]) {
						$metadata["menu_entrys"].="\n\t<li><span class=\"dir\">{$menu_entry["label"]}</span>";
						$metadata["menu_entrys"].=build_submenus($menu_entry["items"],$k);
						$metadata["menu_entrys"].="\n\t<!--[if lte IE 6]></td></tr></table></a><![endif]-->
</li>\n";
					}
					
				}
			
	unset($menu_entry);
	}
	
	
	$metadata["total_menu_items"]=$TMI;
	$metadata["ROOT"]=$SYS["ROOT"];
	set_include_dir(dirname(__FILE__)."/local/Tmpl/-");
	plantHTML($metadata,"mainview");
}
else {
 	echo "Not found $petition";
	header("HTTP/1.0 404 Not Found");
}


function build_submenus($data,$app) {
	global $SYS;
	
	if (empty($data))
		return "";
	$res="\t<ul>";
	foreach($data as $item) {
		if ($item[3])
					$icon='<img src="'.$SYS["ROOT"]."/Apps/".$app."/local/Img/".$item[3].' " align="left" style="margin-top:0px;margin-left:1px;margin-right:5px" border="0"/>';
		else
			$icon='<img src="'.$SYS["ROOT"].'/Backend/local/Img/menu_none.gif" align="left" style="margin-top:4px;margin-left:1px;margin-right:5px" border="0"/>';
		
		if (!is_array($item[0]))
			if (empty($item["0"]))
				$res.="\n\t<li style=\"cursor:arrow\"><span style=\"height:2px\"><a ></a>\n";
			else
				$res.="\n\t<li><a href=\"{$item["0"]}\" target=\"{$item["1"]}\">$icon {$item["2"]}</a></li>\n";
		else {
			$res.="\n\t<li><span class=\"dir\">$icon {$item["2"]} </span>".build_submenus($item["0"],$app);
			
		}
	}
	$res.="\n\t</ul>\n";
	return $res;
}

?>
