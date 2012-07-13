<?php
  
  function AD_Login($user,$password,&$userdata) {
    
    global $SYS;
    
 
 
    $adServer=$SYS["AUTH"]["activedirectory"]["server"];
    $basedn=$SYS["AUTH"]["activedirectory"]["basedn"];
    $searchuserdn=$SYS["AUTH"]["activedirectory"]["searchdn"];
    $domain=$SYS["AUTH"]["activedirectory"]["domain"];
   
    $ldapconn = ldap_connect($adServer);
    if (!$ldapconn)
      return false;
    
    $ldapbind = ldap_bind($ldapconn, "$user@$domain",$password );
    
    if ($ldapbind) { 
      // We're inside!!
      $filter = "CN=$user";
      foreach ($SYS["AUTH"]["activedirectory"]["searchdn"] as $v) {
        $sr = ldap_search($ldapconn," $v,$basedn",$filter);
        if ($sr) {
          $info = ldap_get_entries($ldapconn,$sr);
          if ($info["count"]>0){
            $userdata["username"] = $info[0]["cn"][0];
            $guessNameArr1=explode("-",$info[0]["displayname"][0]);
            $guessNameArr=explode(" ",trim($guessNameArr1[0]));
             
            $userdata["apellidos"] = $guessNameArr[sizeof($guessNameArr)-2]." ".$guessNameArr[sizeof($guessNameArr)-1];
            $nombreArr=(array_shift(array_reverse($guessNameArr)));
            $userdata["nombre"]=str_replace($userdata["apellidos"],"",trim($guessNameArr1[0]));
            $userdata["email"] = $info[0]["mail"][0];
            
            return true;
          }
        }
      }
    }
    
    return false;
    
    
  }
  
  
  
  
  ?>