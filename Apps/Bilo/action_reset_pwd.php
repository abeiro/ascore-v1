<?php
require_once("Bilo.php");
HTML("action_header");
$user=newObject("user",$ID);
$user->preResetPassword($user->username);

?>