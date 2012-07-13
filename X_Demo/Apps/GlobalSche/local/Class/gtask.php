<?php
  

  function  getCronString() {
    
   $p=newObject("schedule",$this->schedule_id);
   return $p->getCronString();
    
  }
  
  
?>