<?php

function save($force=false)
    {
        if ($this->parent_id>1) {
            $counter=$this->listAll("user_id",false,"parent_id={$this->parent_id} AND ID<>{$this->ID} and activo='Si'");
            $o=newObject("post",$this->parent_id);
            $o->responses=sizeof($counter)+1;
            $o->save();
                    
        }
        
        $par = new Ente($this->name);
        $par = typecast($this, "Ente");
        $result=$par->save($force);
        $this->ERROR=$par->ERROR;
        return $result;
    }
    
?> 
