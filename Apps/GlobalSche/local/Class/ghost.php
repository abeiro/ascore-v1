<?php
  
  function save()
  {
    global $prefix;
    debug("Info: Calling Extended save");
    if (!($con = ssh2_connect($this->fqdn, 22))) {
      $this->ERROR="La conexión no se puede efecturar";
      return false;
    } else {
      if (!ssh2_auth_password($con, $this->usuario, $this->pass)) {
        $this->ERROR="Usuario/contraseña invalido ($this->usuario - contraseña de  ".strlen($this->pass)."  car.)";
        return false;     
      }
    }
    
    $par = new Ente($this->name);
    $par = typecast($this, "Ente");
    //dataDump($par);
    return $par->save();
  }
  
  function identificador() {
    
    return "{$this->usuario}@{$this ->fqdn}";
    
  }
  
  
  function makeConnection() {
    
    if (!($con = ssh2_connect($this->fqdn, 22))) {
      $this->ERROR="La conexión no se puede efecturar";
      return false;
    } else {
      if (!ssh2_auth_password($con, $this->usuario, $this->pass)) {
        $this->ERROR="Usuario/contraseña invalido ($this->usuario - contraseña de  ".strlen($this->pass)."  car.)";
        return false;     
      }
    }
    
    return $con;
  }
  ?>