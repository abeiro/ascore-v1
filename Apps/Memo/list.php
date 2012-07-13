<?php

require_once("Memo.php");
require_once("security.php");

$dir=newObject("data_object");

if (!isset($inode))
	$inode=0;
else 
	$inode=$inode+0;
	
if (!isset($sort))
	$sort="type DESC";

$aux=newObject("data_object",$inode);	
$aux2=newObject("data_object",0);	
$mdptext="";
$aux->mdp(&$mdptext);
$dir->path=$mdptext;
$dir->current_inode=$inode;
	
if (checkReadSecurity($aux))
	{
	$dir->searchResults=$dir->select("inode=$inode",$offset,$sort);

	$safe_list=array();
	
	do {
		$ele=current($dir->searchResults);
		if (checkReadSecurity($ele))
			$safe_list[]=$ele;
	} while (next($dir->searchResults));
	
	$dir->searchResults=$safe_list;
	if ((sizeof($safe_list)<1)||($safe_list[0]==false)) {
		unset($dir->searchResults);
		$dir->searchResults=array();;
	}
		
	
	formAction("","","listForm");
	$SYS["inode"]=$inode;
	plantHTML($SYS,"navigator_top");
	include_once("mime_icons.php");
	
	
	listList($dir,array(
		"mime_image"=>'code#return img_icon($object->mime);',
		"usuario"=>'xref#user|uid|username',
		"grupo"=>'xref#group|gid|groupname'
		
		)
		
	,"list");
	HTML("navigator_bottom",True);
	
	formClose();
}
else
{
	$notification->push(_("Insufficient privileges."),'horde.error');
	if ($inode!=$oldinode)
		$inode=$oldinode;
	else
		$inode=0;
		
	require ('list.php');

}




