<?php

$user=newObject("user");
if (!$user->resetPassword($hash))
	echo $user->ERROR;
else
	echo "Contraseña enviada";


?>