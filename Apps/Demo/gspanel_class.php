<?php


/* 
GSControlPanel Class.

This implements a wGrid<->wFrom pair with hierarchy.

This class is not part of the current wGui API _yet_


*/

class GSControlPanel extends wPane {

    var $MainClass = "";
    var $tasks = null;
    var $dGrid = null;
    var $dForm = null;
    var $tabPane = null;
    var $hierarchyClass = null;
    var $grids = null;
    var $aForms = array();
    var $SQL_CONDS = array();
    var $statusBox = null;
    var $mainPane = null;
    var $realParent = null;
    /*
      Constructor and first tab
     */

    function __construct($parent = null, $name, $class) {


        global $SYS;

        parent::__construct($name, $parent);
		$this->realParent=$parent;
        $this->MainClass = $class;
        $this->tasks = newObject($this->MainClass);
        $this->setElementStyle("float", "none");
        $this->setElementStyle("width", "100%");


        $this->MainToolbar();

        $this->mainPane = new wPane("{$class}gspane", $this);
        $this->mainPane->visibility = "visible";
        $this->mainPane->setElementStyle("float", "none");
        $this->mainPane->setElementStyle("width", "100%");


        $this->statusBox = new wMutableLabel("statusBox", $this->toolBar , "-----");
        $this->statusBox->setCSS("text-align", "left");
        $this->statusBox->setCSS("width", "300px");
        $this->statusBox->setCSS("padding-left", "50px");
        $this->statusBox->setCSS("font-weight", "bold");
        $this->statusBox->setCSS("float", "none");



        $this->tabPane = new wTabbedPane("name", $this->mainPane);

        $LayOut = new wLayoutTable("$name", $this->tabPane);
        //$LayOut->setCSS("width","100%");
        $LayOut->setHorizontal();
        $LayOut->fixedSizes = array("", "100%");
        /* Grid */

        $grid = new wGrid("grid{$class}{$this->id}", $LayOut);
        $grid->DataURL = "?oDataRequest=" . get_class($this) . "&instance={$this->MainClass}&desktop_id={$GLOBALS["desktop_id"]}";
        $grid->setWidth(595);
        $this->addListener("noevent", "updateURIS", $this);

        //$grid->actionOnSelectID="xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$this->MainClass}')";

        $grid->actionOnSelectID = "xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$this->MainClass}');xajax_GSControlPanel.updateURIS('$class',value)";

        /* Form */

        $form = new wForm("form{$class}{$this->id}", $LayOut);
        $form->setCSS("margin-left", "5px");
        $form->setCSS("width", "100%");
        /* Data */

        $grid->setDataModelFromCore($this->tasks);
        $form->setDataModelFromCore($this->tasks);

        $form->createDefaultButtons();

        $form->doAfterSave("aj_ReloadGrid");
        $form->doAfterDelete("aj_GridDelete");

        $this->dGrid = &$grid;
        $this->dForm = &$form;
        $this->aForms[sizeof($this->aForms)] = $form;
        $this->grids["$class"] = $grid;
    }

    function render() {
        parent::render();
        
           

        
    }
    function MainToolbar() {

        $this->searchForm = new wForm("searchForm", $this);
        $obj = newObject($this->MainClass);
        $this->searchForm->setDataModelFromCore($obj);
        $this->searchForm->setStyle("
    display:none;
    position:absolute;
    top:50px;
    right:5px;
    width:800px;
    min-height:500px;
    z-index:10;
    background-color:white;
    color:black;
    padding:10px;
     -webkit-box-shadow: 0 0 10px rgb(0,0,0);  
    -moz-box-shadow: 0 0 10px rgb(0,0,0);  
    box-shadow: 0 0 10px rgb(0,0,0); 
    -moz-border-radius: 3px 3px 3px 3px;
    border-radius: 3px 3px 3px 3px;
    ");
        $this->searchForm->LineByLine = false;
        $searchButtonPanel = new wLayoutTable("", $this->searchForm);
        $doSearchButton = new wButton("doSearchButton", $searchButtonPanel);
        $doSearchButton->label = "Buscar";
        $doSearchButton->addListener("onclick", "doSearch", $this);
        $doSearchButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->searchForm->id);
        $doSearchButton->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->MainClass);

        $doCancelButton = new wButton("doCancelButton", $searchButtonPanel);
        $doCancelButton->label = "Cancelar";
        $doCancelButton->addListener("onclick", "activateSearchWindow", $this);
        $doCancelButton->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->searchForm->id . '").style.display');

        $doResetButton = new wButton("doResetButton", $searchButtonPanel);
        $doResetButton->label = "Limpiar";
        $doResetButton->addListener("onclick", "resetSearchForm", $this);

        $this->toolBar = new wLayout("toolBar", $this);
        $this->toolBar->setFree();
        $this->toolBar->setCSS("padding-left", "10px");

        $filter = new wInput("filterName", $this->toolBar);
        $filter->addListener("onblur", "changeNameFilter", $this);
        $filter->Listener["onblur"]->addParameter(XAJAX_JS_VALUE, '$("' . $filter->id . '").value');
        $filter->legend = "Filtro rapido: ";
        $filter->setSelectedValue($this->filters["SQL_CONDSI"][$this->MainClass]);

        $searchButton = new wImage("SearchButton", $this->toolBar);
        $searchButton->label = "Buscar";
        $searchButton->src = $GLOBALS["SYS"]["ROOT"] . "/Apps/AstDip/local/Img/search.png";
        $searchButton->addListener("onclick", "activateSearchWindow", $this);
        $searchButton->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $this->searchForm->id . '").style.display');


        $printButton = new wImage("PrintButton", $this->toolBar);
        $printButton->label = "Imprimir";
        $printButton->src = $GLOBALS["SYS"]["ROOT"] . "/Apps/AstDip/local/Img/printer.png";
        $printButton->addListener("onclick", "doPrint", $this);

		$exportButton = new wImage("ExportButton", $this->toolBar);
        $exportButton->label = "Exportar";
        $exportButton->src = $GLOBALS["SYS"]["ROOT"] . "/Apps/AstDip/local/Img/export.png";
        $exportButton->addListener("onclick", "doPrint", $this);
		$exportButton->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, 'csv');
    }

    function buildMultiqueryFromArray($arraydata, $class) {
        $target = newObject($class);
        $dquery = " (1=1 ";
        $fields = ($target->properties_type);
        foreach ($target->properties as $name => $type) {
            if ((isset($arraydata[$name])) && (!empty($arraydata[$name]))) {
                if (strpos($target->properties_type["$name"], "ref") === 0)
                    if ($arraydata["$name"] < 2)
                        continue;
                if (strpos($target->properties_type["$name"], "list") === 0)
                    if ($arraydata["$name"] == "-")
                        continue;
                if (strpos($target->properties_type["$name"], "boolean") === 0) {
                    if (isset($arraydata["$name"]))
                        $dquery.=" AND `$name`='Si' ";
                    continue;
                }

                if (strpos($target->properties_type["$name"], "string") === 0)
                    $dquery.=" AND `$name` ILIKE '%{$arraydata[$name]}%'";
                else
                    $dquery.=" AND `$name` = '{$arraydata[$name]}'";
            }
        }
        $dquery.=")";

        return $dquery;
    }

    function activateSearchWindow($event, $id, $onoff) {
        $objResponse = new xajaxResponse();
        $objResponse->append("searchForm", "class", "wWindowCss");
        if ($onoff == "block")
            $objResponse->assign("searchForm", "style.display", "none");
        else
            $objResponse->assign("searchForm", "style.display", "block");
        return $objResponse;
    }

    function doSearch($event, $id, $data, $class) {
        $objResponse = new xajaxResponse();
        $filter = $this->buildMultiqueryFromArray($data, $class);
        $this->SQL_CONDS["$class"]["searchForm"]=$filter;
		$this->updateParentCache();

        $objResponse->script("tableGrid_{$this->dGrid->id}_restart()");
        //$objResponse->assign("searchForm","style.display","none");
        return $objResponse;
    }

    function resetSearchForm($event, $id) {
        $objResponse = new xajaxResponse();
        $objResponse->script('$("searchForm").reset()');
        return $objResponse;
    }

    function changeNameFilter($event, $id, $filter) {
		$o=newObject($this->MainClass);
		foreach ($o->properties_type as $p=>$t)
			if (strpos($t,"string")===0)
				break;
        $objResponse = new xajaxResponse();
        $this->SQL_CONDS[$this->MainClass]["filter"] = "  $p IliKE '%$filter%' ";
        //$this->filters["SQL_CONDSI"][$this->MainClass]["filter"] = array($p,$filter);
		//debug("DOM parent: ".$this->realParent->getMyClassName."\n".print_r(get_class_methods($this->realParent),true),"green");

		
		$this->updateParentCache();
		
        $objResponse->script("tableGrid_{$this->dGrid->id}_restart()");

        return $objResponse;
    }
    function updateParentCache() {
		$cParent = &$this->wParent;
        while ($cParent) {
            debug(__FILE__ . "Calling parent: " . get_class($cParent), "white");
            if (method_exists($cParent, "updateCache")) {
                debug("Parent component method exists updateCache: " . get_class($cParent), "blue");
                call_user_func(array($cParent, "updateCache"));
                break;
            }
            else
                $cParent = &$cParent->wParent;
        }
	}

    function doPrint($event, $id,$csv=false) {
        $objResponse = new xajaxResponse();
        $class=$this->MainClass;
        $a=  newObject($this->MainClass);
        
        $sqlcond = $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["lastsql"];
        $calculatedOffset = $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["calculatedOffset"];
        $sortDriver = $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["sortDriver"];
        
        setLimitRows(1500);
        $a->seachResults = $a->select($sqlcond, 0, $sortDriver);
		$result[0][] = "ID";
        foreach ($a->properties_desc as $nombreCampo => $tipoCampo) 
            if (strpos($nombreCampo,"S_")===0)
				continue;
			else
				$result[0][] = $tipoCampo;

        foreach ($a->seachResults as $linea => $row) {
            $linea++;
            $result[$linea][] = $row->ID;

            foreach ($row->properties_type as $nombreCampo => $tipoCampo) {
				if (strpos($nombreCampo,"S_")===0)
					continue;
			
                if (strpos($tipoCampo, "ref") === 0) {
                    $refData = explode(":", $tipoCampo);
                    if (sizeof($refData) >= 3) {
                        $result[$linea][] = ($row->get_ext($refData[1], $row->$nombreCampo, $refData[2]));
                    }
                    else
                        $result[$linea][] = ($row->$nombreCampo);
                }
                else if (strpos($tipoCampo, "date") !== false) {
                    if ($row->$nombreCampo > 10000) {
                        $format = substr($tipoCampo, strpos($tipoCampo, ":") + 1);
                        $result[$linea][] = (strftime(($format) ? $format : "%d/%m/%Y", $row->$nombreCampo));
                    }
                    else
                        $result[$linea][] = "";
                }
                else {
                    $result[$linea][] = ($row->$nombreCampo);
                }
            }
        }
     $data=json_encode($result);
	 if ($csv===false)
		$objResponse->script("test=$data;jsPrint(test)");
	 else {
		
		$objResponse->script("test=$data;jsCsv(test)");
		}
     return $objResponse;
    }

    /*

      DATA GRID REQUEST

     */

    function aj_RequestData($class, $uselast = false) {
        global $SYS;

        if (!$uselast) {
            $a = newObject($class);
            $calculatedOffset = floor(($_POST["page"] - 1) * $SYS["DEFAULTROWS"]);
            if (isset($this->hierarchyClass["$class"])) {
                if ($_GET["driver"])
                    $sqlcond = $this->hierarchyClass["$class"] . "=" . $_GET["driver"];
                else
                    $sqlcond = "1=2";
            }
            else
                $sqlcond = "1=1";

            if ($this->SQL_CONDS["$class"])
					$sqlcond.=" AND ".implode(" AND ",$this->SQL_CONDS["$class"]);
            if ($_POST["sortColumn"])
                $sortDriver = str_replace("grid_", "", $_POST["sortColumn"]) . " {$_POST["ascDescFlg"]}";
            else
                $sortDriver = $this->SQL_SORT["$class"];
            debug("Grid sorting request: $sortDriver", "green");
        } else {
            $sqlcond = $_SESSION["cache"]["gspanel"]["$class"]["lastsql"];
            $calculatedOffset = $_SESSION["cache"]["gspanel"]["$class"]["calculatedOffset"];
            $sortDriver = $_SESSION["cache"]["gspanel"]["$class"]["sortDriver"];
        }

        $a->seachResults = $a->select($sqlcond, $calculatedOffset, $sortDriver);
		
        $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["lastsql"] = $sqlcond;
        $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["calculatedOffset"] = $calculatedOffset;
        $_SESSION["cache"][$GLOBALS["desktop_id"]]["$class"]["sortDriver"] = $sortDriver;

        /* Nasty hook */
        if (isset($this->formatGridData)) {
            $func = $this->formatGridData;
            $func($a->seachResults);
        }

        wGrid::prepareGridData($a->seachResults, $a->totalPages, $_POST["page"]);
    }

    /*
      RELOAD GRID WRAPPER
     */

    public function aj_ReloadGrid($ajaxresponse, $objectsaved, $jid) {


        $gridOfsavedObject = str_replace("form", "grid", $jid);
        debug(__FILE__ . " I'm alive, $gridOfsavedObject {$objectsaved->coreObject->ID} $jid", "yellow");

        /* If adding a new element, must updated related select boxes in child tabs */
        if ($this->MainClass == $objectsaved->coreObject->name) {

            $no = $objectsaved->coreObject;
            if ($no->__isNew) {
                foreach ($this->aForms as $cform) {
                    $formClass = $cform->coreObject->name;
                    $fieldToUpdate = $this->hierarchyClass["$formClass"];
                    debug("Adding a new element, $fieldToUpdate", "yellow");
                    if ($fieldToUpdate) {
                        $label = reset($no->properties);
                        $code = "appendOptionLast($('{$cform->id}.$fieldToUpdate'),'$label',{$no->ID});";
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

    public function aj_GridDelete($ajaxresponse, $objectsaved, $fjid) {

        debug(__FILE__ . __FUNCTION__ . "I'm alive $fjid {$gridOfsavedObject->id} ", "yellow");
        $gridOfsavedObject = $this->grids[$objectsaved->coreObject->name];
        $ajaxresponse->script("tableGrid_{$gridOfsavedObject->id}.refresh()");
        //$ajaxresponse->script("$('{$fjid}').reset()");
        //$this->afterRequestNewForm(&$ajaxresponse,$objectsaved,$objectsaved->id);
    }

    /*
      ADD A TABS
     */

    function addTab($name, $class, $relationWithParentField = null) {

        global $SYS;

        $instancedObject = newObject("$class");

        $LayOut = new wLayoutTable("$name", $this->tabPane);
        $LayOut->setHorizontal();
        $LayOut->fixedSizes = array("", "");
        /* Grid */

        $grid = new wGrid("grid{$class}{$this->id}", $LayOut);
        $grid->DataURL = "?oDataRequest=" . get_class($this) . "&instance={$class}&desktop_id={$GLOBALS["desktop_id"]}";
        $grid->setWidth(595);

        $grid->actionOnSelectID = "xajax_wForm.requestloadFromId(value,'form{$class}{$this->id}','{$class}')";

        /* Form */

        $form = new wForm("form{$class}{$this->id}", $LayOut);
        $form->setCSS("margin", "5px");

        /* Data */

        $grid->setDataModelFromCore($instancedObject);

        /* VNH */

        $form->setDataModelFromCore($instancedObject);


        $form->createDefaultButtons();
        $form->setCSS("width", "95%");
        $form->doAfterSave("aj_ReloadGrid");
        $form->doAfterDelete("aj_GridDelete");

        if ($relationWithParentField) {
            $this->hierarchyClass["$class"] = "$relationWithParentField";
            $this->grids["$class"] = $grid;
            $label = new wHidden("{$this->MainClass}_driver", $this, false);
            //$form->components["$relationWithParentField"]->setReadOnly();
        }

        $this->aForms[sizeof($this->aForms)] = $form;
    }

    /*
      UPDATE GRID URIS  AFTER SELECT AN ELEMENT
     */

    function updateURIS($classn, $value) {
        debug("$classn,$value", "yellow");
        $objResponse = new xajaxResponse();
        //
        foreach ($this->grids as $classname => $ogrid) {
            if ($classn != $classname)
                $objResponse->script('
            tableModel_' . $ogrid->id . '.url="' . $ogrid->DataURL . '&driver=' . $value . '";
            tableGrid_' . $ogrid->id . '.url="' . $ogrid->DataURL . '&driver=' . $value . '";
            ');
        }

        return $objResponse;
    }

    /*
      ACTIONS TO DO AFTER SELECT AN ITEM
     */

    public function afterrequestloadFromId($ajaxresponse, $object, $jid) {
        debug("I'm still alive {$object->name}", "green");
        $class = $object->coreObject->name;
        foreach ($this->hierarchyClass as $name => $field) {
            if ($class == $this->MainClass)
                $ajaxresponse->assign("{$this->MainClass}_driver", "value", $object->coreObject->ID);
            else
                $ajaxresponse->script("\$(\"faked_$jid.$field\").value=\$(\"$jid.$field\").value");
        }
    }

    /*
      ACTIONS TO DO AFTER SET NEW FORM
     */

    public function afterRequestNewForm($ajaxresponse, $object, $jid) {
        debug("I'm still alive {$object->name} $jid", "green");
        foreach ($this->hierarchyClass as $classname => $fieldname)
            if ($object->coreObject->name == $classname) {
                $ajaxresponse->script('$("' . $jid . '.' . $fieldname . '").value=$("' . "{$this->MainClass}_driver" . '").value');
                $ajaxresponse->script("setSelectReadonly('$jid.$fieldname')");
                // $ajaxresponse->script('alert($("'."{$this->MainClass}_driver".'").value)');
            }
    }

}

?>
