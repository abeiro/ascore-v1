<?php

require_once("Bilo.php");
if ($_SESSION["__auth"]["backto"]) {
    $user=newObject("user",$_GET["ID"]);
    $_SESSION["__auth"]["backto"]="";
    $_SESSION["__auth"]["username"]=$user->username;
    $_SESSION["__auth"]["uid"]=$user->ID;
}



session_commit();

?>
<script>
    window.close()
</script>