<?php

if ($_GET["humanBeing"]) {
    $user = newObject("user");
    $user->searchResults = $user->select("MD5(ID)='$hash'");
    $currentUser = current($user->searchResults);
    if ($currentUser->ID > 2) {
        if ($currentUser->activo == "No") {
            $_SESSION["__auth"]["username"] = $currentUser->username;
            $_SESSION["__auth"]["uid"] = $currentUser->ID;
            $pref = newObject("user_pref");
            $pref->getPrefByUser($currentUser->ID);
            $pref->setPrefs();
            $currentUser->activo = "Si";
            $currentUser->save();
            sleep(1);
            if (BILO_checkGroup("Alumnos"))
                header("Location: {$GLOBALS["SYS"]["ROOT"]}/Usuario/Ficha");
            else if (BILO_checkGroup("Profesores")) {
                require_once("Lib/lib_phpmailer.php");
                $mail = new PHPMailer();
                $mail->IsMail();
                $mail->AddAddress($currentUser->username);
                $mail->AddBCC($GLOBALS["SYS"]["admin_email"]);
                $mail->IsHTML(true);
                $mail->From = $GLOBALS["SYS"]["admin_email"];
                $mail->FromName = $GLOBALS["SYS"]["admin_realm"];
                $mail->Sender = $GLOBALS["SYS"]["admin_email"];
                $mail->Subject = $GLOBALS["SYS"]["SITENAME"] . " :: Gracias por suscribirse";
                $mail->Body = "Estimado usuario<br/><br/>Gracias por confiar en nosotros y registrarse en {$GLOBALS["SYS"]["SITENAME"]}.<br/><br/>
Como profesor, entre en el apartado 'Mi Perfil' y rellene sus datos de la manera más fidedigna posible. En primera instancia, revisaremos su
perfil y lo publicaremos lo más pronto posible. Note que si no completa su perfil en menos de 10 días, nos reservamos el derecho a borrarlo.<br/><br/>
Gracias por su confianza.
<br/><br/>
El equipo de {$GLOBALS["SYS"]["SITENAME"]}";

                $mail->Send();

                header("Location: {$GLOBALS["SYS"]["ROOT"]}/Usuario/Perfil");
            }
        } else {
            die("El usuario {$user->username} ya está activo");
        }
    }
    die("No se encontró hash.");
} else {

    header("Location: {$GLOBALS["SYS"]["ROOT"]}/Ok/Confirm/?hash={$_GET["hash"]}");
}
?>
