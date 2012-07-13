<?php


require_once("Memo.php");
require_once("security.php");

if (!BILO_isAdmin())
		$p->security_option="disabled";


$auth = newObject("user");
$uuids=$auth->listAll("username");

$gauth = newObject("group");
$guids=$gauth->listAll("groupname");


$external_data=array(
"gid"=>$guids,
"uid"=>$uuids
);

$p=newObject("data_object",$ID);

$p->inode=$inode;
if (empty($p->uid))
	$p->uid=BILO_uid();

if (empty($type))
	$type=$p->type;
else
	$p->type=$type;

if (!BILO_isAdmin())
	$p->security_option=disabled;
	
if (checkSecurity($p)) {
	formAction("action_save.php","footer","editForm");
	
	$p->boton0=gfxBotonAction("Guardar","getElementById('editForm').submit()",True);
	
	
		
	debug("type of edit element: $type ","red");
		
	
		if ($type=="archive")
			plantHTML($p,"add_form",$external_data);
		else if ($type=="folder")
			plantHTML($p,"add_folder",$external_data);
		
	formClose();
}
else {
	frameWrite("footer",_("Insufficient privileges."));
	require ('list.php');
}
//dataDump($_SESSION);
?>
