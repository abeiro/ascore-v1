<?php
    /* Functions in this file */
    /**************************/

    // check_empty($post)
    // donothing()
    // listGroups()
    // listGroupsCodes()
    // listGroupsIndex()
    // listGroupsNames()
    // listUsersInGroupName($name)
    // load($id)
    // NameSurname()
    // preResetPassword($username)
    // resetPassword($hash)
    // save()
    // selectShopUser($ID)
    // setGroupCode($arr_code)
    // userExist($user)
?>
<?php
    /* Extensión de la clase inventarios_muestrarios */
    function donothing()
    {
        return True;
    }
    function save()
    {
        debug("Info: Calling Extended save");
        unset($this->properties["fullname"]);
        if ($this->password == md5(""))
        {
            $this->ERROR = _("password vacio");
            return FALSE;
        }
        if (($this->userExist($this->username)) && ($this->ID < 2))
        {
            $this->ERROR = _("Usuario existente");
            return FALSE;
        }
        //dataDump($this);
        $par = new Ente($this->name);
        $par = typecast($this, "Ente");
        //dataDump($par);
        return $par->save();
    }
    function listGroups()
    {
        $g = newObject("group");
        setLimitRows(32);
        $a = ($g->select("(code&{$this->grupos}=code) AND active='Si'"));
        resetLimitRows();
        return $a;
    }
    function listGroupsNames()
    {
        $g = newObject("group");
        setLimitRows(32);
        $a = ($g->select("(code&{$this->grupos}=code) AND active='Si'"));
        resetLimitRows();
        foreach ($a as $k => $v)
        $res[] = $v->groupname;
        return implode(",", $res);
    }
    function listGroupsIndex()
    {
        $g = newObject("group");
        setLimitRows(32);
        $a = ($g->select("(code&{$this->grupos}=code) AND active='Si'"));
        resetLimitRows();
        foreach ($a as $k => $v)
        $res[$v->ID] = $v->groupname;
        return $res;
    }
    function listGroupsCodes()
    {
        $g = newObject("group");
        setLimitRows(32);
        $a = ($g->select("(code&{$this->grupos}=code) AND active='Si'"));
        resetLimitRows();
        foreach ($a as $k => $v)
        $res[$v->ID] = $v->code;
        return $res;
    }
    function getUserByName($codename = 'username')
    {
        $res = $this->select("username='$codename'");
        if (!empty($res))
        {
            $data = current($res);
            $this->setAll($data->properties);
	    return true;
        }
        else
            return false;
    }
    function setGroupCode($arr_code)
    {
        $code = 0;
        foreach ($arr_code as $k => $v)
        {
            $g = newObject("group", $v);
            $code = $code|$g->code;
            debug($g->code, "red");
        }
        return $code;
    }
    function checkPassword($user = '', $password = '')
    {
        global $AUTH;
        $u = newObject("user");
        $res = $u->select("username='$user'");
        if ($u->nRes > 0)
        {
            $user = current($res);
            if ($user->password == md5($password))
                return True;
            else
                $AUTH["error"] = _("Contraseña errónea");
        }
        else
            $AUTH["error"] = _("usuario desconocido");
        return False;
    }
    function GetIdFromName($name = '')
    {
        $u = newObject("user");
        $res = $u->select("username='$name'");
        if ($u->nRes > 0)
        {
            $user = current($res);
            $this->setAll($user->properties);
            return True;
            ;
        }
        else
            return False;
    }
    function inGroup($name = '')
    {
        $g = newObject("group");
        setLimitRows(32);
        $a = ($g->select("(code&{$this->grupos}=code) AND active='Si' and groupname='$name'"));
        resetLimitRows();
        if (sizeof($a) > 0)
            return True;
        else
            return False;
    }
    function selectUser($q, $offset = 0, $sort = "ID")
    {
        global $prefix, $SYS;
        $All = array();
        if ((empty($sort)))
            $sort = "ID";
        if ((empty($offset)) || ($offset < 0))
            $offset = 0;
        $q = "SELECT SQL_CALC_FOUND_ROWS *,CONCAT(nombre,' ',apellidos) as fullname from {$prefix}_".$this->name." WHERE $q AND ID>1";
        $q .= " ORDER BY $sort LIMIT $offset,".$SYS["DEFAULTROWS"];
        $bdres = _query($q);
        /*$rawres=fetch_array($bdres);
         $this->ID=$rawres["ID"];
         $this->properties=array_slice($rawres,1);*/
        for ($i = 0, $aff_rows = _affected_rows(); $i < $aff_rows; $i++)
        {
            $rawres = _fetch_array($bdres);
            //$p=array_slice($rawres,1);
            $All[$i] = $this->_clone($rawres);
        }
        $this->nRes = _affected_rows();
        if ($this->nRes < $SYS["DEFAULTROWS"])
            $this->nextP = $offset;
        else
            $this->nextP = $offset+$this->nRes;
        $this->prevP = $offset-$SYS["DEFAULTROWS"];
        $bdres = _query("SELECT FOUND_ROWS()");
        $aux = _fetch_array($bdres);
        $this->totalPages = $aux["FOUND_ROWS()"];
        return $All;
    }
    function listUsersInGroupName($name)
    {
        global $offset, $sort;
        $g = newObject("group");
        $g->getGroupByName($name);
        return ($this->selectUser("(grupos&{$g->code}={$g->code})", $offset, $sort));
    }
    function listUsersInGroup($id, $off = -1, $s = -1)
    {
        global $offset, $sort;
        $off = ($off == -1)?$offset:
        $off;
        $s = ($s == -1)?$sort:
        $s;
        $g = newObject("group", $id);
        return ($this->selectUser("(grupos&{$g->code}={$g->code})", $off, $s));
    }
    function load($id)
    {
        global $prefix;
        if ($id == 0)
            return array();
        $q = "SELECT *,CONCAT(nombre,' ',apellidos) as fullname from {$prefix}_".$this->name." WHERE ID=$id";
        $bdres = _query($q);
        $rawres = _fetch_array($bdres);
        if ($rawres === False)
            return False;
        $this->ID = $rawres["ID"];
        $this->properties = array_slice($rawres, 1);
        $this->_normalize();
        return True;
    }
    function userExist($user)
    {
        global $prefix;
        $q = "SELECT ID FROM {$prefix}_".$this->name." WHERE username='$user'";
        $bdres = _query($q);
        $this->nRes = _affected_rows();
        if ($this->nRes > 0)
            return True;
        else
            return False;
    }
    function check_empty($post)
    {
        $empty = false;
        if (empty($post["username"]))
        $empty = true;
        else
            $this->nombre = $post["username"];
        if (empty($post["password"]))
        $empty = true;
        else
            $this->nombre = $post["password"];
        if (empty($post["nombre"]))
        $empty = true;
        else
            $this->nombre = $post["nombre"];
        if (empty($post["apellidos"]))
        $empty = true;
        else
            $this->apellidos = $post["apellidos"];
        if (empty($post["direccion"]))
        $empty = true;
        else
            $this->apellidos = $post["direccion"];
        if (empty($post["localidad"]))
        $empty = true;
        else
            $this->apellidos = $post["localidad"];
        if (empty($post["p_state"]))
        $empty = true;
        else
            $this->apellidos = $post["p_state"];
        if (empty($post["zip"]))
        $empty = true;
        else
            $this->apellidos = $post["zip"];
        if (empty($post["pais"]))
        $empty = true;
        else
            $this->apellidos = $post["pais"];
        if (empty($post["telefono"]))
        $empty = true;
        else
            $this->telefono = $post["telefono"];
        if (empty($post["email"]))
        $empty = true;
        else
            $this->email = $post["email"];
        return $empty;
    }
    function NameSurname()
    {
        return $this->nombre." ".$this->apellidos;
    }
    function selectShopUser($ID)
    {
        $t = newObject("tienda");
        $t->searchResults = $t->select("id_user=$ID");
        return $t->searchResults[0]->ID;
    }
    function mailExist($email = '', $ID = 1)
    {
        global $prefix;
        $q = "SELECT ID FROM {$prefix}_".$this->name." WHERE email='$email' AND ID <> $ID";
        $bdres = _query($q);
        $this->nRes = _affected_rows();
        if ($this->nRes > 0)
            return True;
        else
            return False;
    }
    function preResetPassword($username,$URL='') {
        global $SYS, $SECRETKEY;
        if (empty($URL))
		$URL=$SYS["ROOT"]."/Apps/Bilo/public_action_reset_password.php?";
	$user = newObject("user");
        if ($user->getUserByName($username))
        {
            $action = newObject("reset_psw");
            $action->user_id = $user->ID;
            $texthash = "$SECRETKEY".time();
            $action->hash = md5($texthash);
            $action_id = $action->save();
            $link = "Hemos recibido una solicitud para el cambio de contraseña para el usuario <b>{$user->username}</b>, si desea realizar este cambio, haga click en el siguiente enlace:<br><br><a href=\"{$URL}hash={$action->hash}\">{$URL}hash={$action->hash}</a><br><br>
                Si su cliente de correo no permite el acceso a enlaces, copie y pegue esta URL en el navegador:<br><br>{$URL}hash={$action->hash}";
            require_once("Lib/lib_phpmailer.php");
            $mail = new PHPMailer();
            $mail->AddAddress($user->email);
            $mail->IsHTML(true);
            $mail->From = $SYS["admin_email"];
            $mail->FromName = $SYS["admin_realm"];
            $mail->Attached = $mail->AddAttachment($path, $name = "", $encoding = "base64",
                $type = "application/octet-stream");
            $mail->Subject = $SYS["SITENAME"]."::Solicitud de confirmación de cambio de contraseña";
            $mail->Body = $link;
            if (!$mail->Send())
                echo $mail->ErrorInfo();
            else
                echo _("Solicitud enviada");
        }
	else
		echo _('No existe el usuario');
    }
    function resetPassword($hash)
    {
        global $SYS;
        if ($hash != "")
        {
            $action = newObject("reset_psw");
            $action->searchResults = $action->select("hash='$hash' AND completed='No'", $offset, $sort);
            if ($action->nRes > 0)
            {
                $temp = $action->searchResults[0];
                $user = newObject("user", $temp->user_id);
                $exp_reg = "[^A-Z0-9]";
                $longitud = 5;
                $pass = substr(eregi_replace($exp_reg, "", md5(rand())) . eregi_replace($exp_reg, "", md5(rand())) . eregi_replace($exp_reg, "", md5(rand())),
                    0, $longitud);
                $user->password = md5($pass);
                //dataDump($user);
                if ($user->save())
                {
                    $temp->completed = 'Si';
                    $temp->save();
                    $link = "Se ha generado una nueva contraseña para su usuario, a continuación le informamos de sus nuevos datos para conectarse:<br><br>
                        Usuario: $user->username<br><br>
                        Contraseña: $pass";
                    require_once("Lib/lib_phpmailer.php");
                    $mail = new PHPMailer();
                    $mail->AddAddress($user->email);
                    $mail->IsHTML(true);
                    $mail->From = $SYS["admin_email"];
                    $mail->FromName = $SYS["admin_realm"];
                    $mail->Attached = $mail->AddAttachment($path, $name = "", $encoding = "base64",
                        $type = "application/octet-stream");
                    $mail->Subject = $SYS["SITENAME"]._("::Nueva contraseña de usuario");
                    $mail->Body = $link;
                    if (!$mail->Send()) {
                        $this->ERROR=$mail->ErrorInfo();
			return false;
			}
                    else
                        return true;
                }
            }
            else {
                $this->ERROR=_("No se encuentra la solicitud");
		return false;
		}
        }
  	$this->ERROR=_("Hash invalido");
	return false;
    }
?>
