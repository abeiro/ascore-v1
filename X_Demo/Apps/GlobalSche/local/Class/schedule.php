<?php
  
  function  getCronString() {
    
     return "{$this->minuto} {$this->hora} {$this->dom} {$this->mon} {$this->dow}";
    
  }
  function md5calc() {
    return $this->minuto.$this->hora.$this->dom.$this->mon.$this->dow;
    
  }
  function save()
    {
      global $prefix;
      debug("Info: Calling Extended save");
    
      if (($this->ID>1)&&($this->md5!=$this->md5calc())){
        $o=newObject("gtasklog");
        $res=$o->selectA("schedule_id=".$this->ID." AND inicio>".time());
        foreach ($res as $row) {
          $IDS[]=$row["ID"]; 
        }
        if ($IDS) {
          debug("Purgando programaciones","green");
          _query("DELETE FROM  {$prefix}_gtasklog WHERE estado='No Iniciada' AND ID IN (".implode(",",$IDS).")");
          
        }
             
      }
      $this->md5=$this->md5calc();
      $par = new Ente($this->name);
      $par = typecast($this, "Ente");
      //dataDump($par);
      return $par->save();
   }
  
  ?>