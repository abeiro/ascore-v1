<?php

$user = newObject("user");
$user->searchResults = $user->select("MD5(CONCAT(ID,password,S_Date_M))='{$_GET["hash"]}'");
$currentUser = current($user->searchResults);
if ($currentUser->ID < 2) {
    die("Hash inválido");
}

if ($_GET["humanBeing"]) {
    if ($currentUser->ID > 2) {
        $_SESSION["__auth"]["username"] = $currentUser->username;
        $_SESSION["__auth"]["uid"] = $currentUser->ID;
        header("Location: {$GLOBALS["SYS"]["ROOT"]}/Ok/Cuenta");
    }
    die("No se encontró hash.");
} else {

    header("Location: {$GLOBALS["SYS"]["ROOT"]}/Ok/ConfirmP/?hash={$_GET["hash"]}");
}
?>
