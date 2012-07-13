<?php

require_once("coreg2.php");

setNavVars(array("void_framming"));
$u=newObject("bookmark",1);
$uid=BILO_uid();
$u->searchResults=$u->select("user_id=$uid",$offset,$sort);
listList($u,array(),"lbookmarks");




?>


