<?php
  
  class GSControlPanel extends wPane {
    
    var $MainClass="";
    var $tasks=null;
    var $dGrid=null;
    var $dForm =null;
    var $tabPane=null;
    var $hierarchyClass=null;
    var $grids=null;
    var $aForms=array();
    var $SQL_CONDS=array();
    var $statusBox=null;
    
    /* 
    Constructor and first tab
    */
    
    function __construct($parent=null,$name,$class) {
      
      
      global $SYS;
      
      parent::__construct($name,$parent);
      
      $this->MainClass=$class;
      $this->tasks=newObject($this->MainClass);
      $this->setElementStyle("float","none");
      $this->setElementStyle("width","100%");
      
      $F=new wPane("{$class}gspane",$this);
      $F->visibility="visible";
      $F->setElementStyle("float","none");
      $F->setElementStyle("width","100%");

      
      $this->statusBox=new wMutableLabel("statusBox",$F,"-----");
      $this->statusBox->setCSS("text-align","left");
      $this->statusBox->setCSS("width","600px");
      $this->statusBox->setCSS("padding-top","10px");
      $this->statusBox->setCSS("font-weight","bold");
      $this->statusBox->setCSS("float","none");
      
      
      
      $this->tabPane=new wTabbedPane("name",$F);
      
      $LayOut=new wLayoutTable("$name",$this->tabPane);
      //$LayOut->setCSS("width","100%");
      $LayOut->setHorizontal();
      $LayOut->fixedSizes=array("","100%");
      /* Grid */
      
      $grid=new wGrid("grid{$class}{$this->id}",$LayOut);
      $grid->DataURL="?oDataRequest=".get_class($this)."&instance={$this->MainClass}&desktop_id={$GLOBALS["desktop_id"]}";
      $grid->setWidth(595);
      $this->addListener("noevent","updateURIS",$this);
      
      //$grid->actionOnSelectID="xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$this->MainClass}')";
      
      $grid->actionOnSelectID="xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$this->MainClass}');xajax_GSControlPanel.updateURIS('$class',value)";
      
      /* Form */
      
      $form=new wForm("form{$class}{$this->id}",$LayOut);
      $form->setCSS("margin-left","5px");
      $form->setCSS("width","100%");
      /* Data */    
      
      $grid->setDataModelFromCore($this->tasks);
      $form->setDataModelFromCore($this->tasks);
      
      $form->createDefaultButtons();
      
      $form->doAfterSave("aj_ReloadGrid");
      $form->doAfterDelete("aj_GridDelete");
      
      $this->dGrid=&$grid;
      $this->dForm=&$form;
      $this->aForms[sizeof($this->aForms)]=$form;
      $this->grids["$class"]=$grid;
      
    }
    
    
    /* 
    
    DATA GRID REQUEST
    
    */
    
    function aj_RequestData($class) {
      global $SYS;
      $a=newObject($class);
      $calculatedOffset=floor(($_POST["page"]-1)*$SYS["DEFAULTROWS"]);
      if (isset($this->hierarchyClass["$class"])) {
        if ($_GET["driver"])
          $sqlcond=$this->hierarchyClass["$class"]."=".$_GET["driver"];
        else
          $sqlcond="1=2";
      }
      else
        $sqlcond="1=1";
      
      if ($this->SQL_CONDS["$class"])
        $sqlcond.=" AND {$this->SQL_CONDS["$class"]}";
      if ($_POST["sortColumn"])
        $sortDriver=str_replace("grid_","",$_POST["sortColumn"])." {$_POST["ascDescFlg"]}";
      else
        $sortDriver=$this->SQL_SORT["$class"];
      debug("Grid sorting request: $sortDriver","green");
      $a->seachResults=$a->select($sqlcond,$calculatedOffset,$sortDriver);
      
      
      
      /* Nasty hook */
      if (isset($this->formatGridData)) {
        $func=$this->formatGridData;
        $func($a->seachResults);
      }
      
      wGrid::prepareGridData($a->seachResults,$a->totalPages,$_POST["page"]);
    }
    
    
    /*
    RELOAD GRID WRAPPER
    */
    
    public function aj_ReloadGrid($ajaxresponse,$objectsaved,$jid) {
      
      
      $gridOfsavedObject=str_replace("form","grid",$jid);
      debug(__FILE__." I'm alive, $gridOfsavedObject {$objectsaved->coreObject->ID} $jid","yellow");
      
      /* If adding a new element, must updated related select boxes in child tabs */
      if ($this->MainClass==$objectsaved->coreObject->name) {
        
        $no=$objectsaved->coreObject;
        if ($no->__isNew) {
          foreach ($this->aForms as $cform) {
            $formClass=$cform->coreObject->name;
            $fieldToUpdate=$this->hierarchyClass["$formClass"];
            debug("Adding a new element, $fieldToUpdate","yellow");
            if ($fieldToUpdate) {
              $label=reset($no->properties);
              $code="appendOptionLast($('{$cform->id}.$fieldToUpdate'),'$label',{$no->ID});";
              $ajaxresponse->script("$code");
            }
          }
        }
      }
      
      
      $ajaxresponse->script("tableGrid_{$gridOfsavedObject}.refresh()");
      
    }
    
    
    /*
    RELOAD GRID WRAPPER ON DELETE
    */
    public function aj_GridDelete($ajaxresponse,$objectsaved,$fjid) {
      
      debug(__FILE__.__FUNCTION__."I'm alive $fjid {$gridOfsavedObject->id} ","yellow");
      $gridOfsavedObject=$this->grids[$objectsaved->coreObject->name];
      $ajaxresponse->script("tableGrid_{$gridOfsavedObject->id}.refresh()");
      //$ajaxresponse->script("$('{$fjid}').reset()");
      //$this->afterRequestNewForm(&$ajaxresponse,$objectsaved,$objectsaved->id);
    }
    
    /*
    ADD A TABS
    */
    
    function addTab($name,$class,$relationWithParentField=null) {
      
      global $SYS;
      
      $instancedObject=newObject("$class");
      
      $LayOut=new wLayoutTable("$name",$this->tabPane);
      $LayOut->setHorizontal();
      $LayOut->fixedSizes=array("","");
      /* Grid */
      
      $grid=new wGrid("grid{$class}{$this->id}",$LayOut);
      $grid->DataURL="?oDataRequest=".get_class($this)."&instance={$class}&desktop_id={$GLOBALS["desktop_id"]}";
      $grid->setWidth(595);
      
      $grid->actionOnSelectID="xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$class}')";
      
      /* Form */
      
      $form=new wForm("form{$class}{$this->id}",$LayOut);
      $form->setCSS("margin","5px");
      
      /* Data */    
      
      $grid->setDataModelFromCore($instancedObject);
      
      /* VNH */
      
      $form->setDataModelFromCore($instancedObject);
      
      
      $form->createDefaultButtons();
      $form->setCSS("width","95%");
      $form->doAfterSave("aj_ReloadGrid");
      $form->doAfterDelete("aj_GridDelete");
      
      if ($relationWithParentField) {
        $this->hierarchyClass["$class"]="$relationWithParentField";
        $this->grids["$class"]=$grid;
        $label=new wHidden("{$this->MainClass}_driver",$this,false);
        //$form->components["$relationWithParentField"]->setReadOnly();
      }
      
      $this->aForms[sizeof($this->aForms)]=$form;
    }
    
    /*
    UPDATE GRID URIS  AFTER SELECT AN ELEMENT
    */
    function updateURIS($classn,$value) {
      debug("$classn,$value","yellow");
      $objResponse = new xajaxResponse();
      //
      foreach ($this->grids as $classname=>$ogrid) {
        if ($classn!=$classname)
          $objResponse->script('
            tableModel_'.$ogrid->id.'.url="'.$ogrid->DataURL.'&driver='.$value.'";
            tableGrid_'.$ogrid->id.'.url="'.$ogrid->DataURL.'&driver='.$value.'";
            ');
        
      }
      
      return $objResponse ;
      
    }
    
    /*
    ACTIONS TO DO AFTER SELECT AN ITEM
    */
    public function afterrequestloadFromId($ajaxresponse,$object,$jid) {
      debug("I'm still alive {$object->name}","green");
      $class=$object->coreObject->name;
      foreach ($this->hierarchyClass as $name=>$field) {
        if ($class==$this->MainClass) 
          $ajaxresponse->assign("{$this->MainClass}_driver","value",$object->coreObject->ID);      
        else
          $ajaxresponse->script("\$(\"faked_$jid.$field\").value=\$(\"$jid.$field\").value");
      }
    }
    
    /*
    ACTIONS TO DO AFTER SET NEW FORM
    */
    public function afterRequestNewForm($ajaxresponse,$object,$jid) {
      debug("I'm still alive {$object->name} $jid","green");
      foreach ($this->hierarchyClass as $classname=>$fieldname)
               if ($object->coreObject->name==$classname) {
                 $ajaxresponse->script('$("'.$jid.'.'.$fieldname.'").value=$("'."{$this->MainClass}_driver".'").value');
                 $ajaxresponse->script("setSelectReadonly('$jid.$fieldname')");
                 // $ajaxresponse->script('alert($("'."{$this->MainClass}_driver".'").value)');
               }
      
    }
    
  }
    
    
    ?>
