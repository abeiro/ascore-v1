<?php

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

    /*
      Constructor and first tab
     */

    function __construct($parent = null, $name, $class) {


        global $SYS;

        parent::__construct($name, $parent);

        $this->MainClass = $class;
        $this->tasks = newObject($this->MainClass);
        $this->setElementStyle("float", "none");
        $this->setElementStyle("width", "100%");


        $this->MainToolbar();

        $this->mainPane = new wPane("{$class}gspane", $this);
        $this->mainPane->visibility = "visible";
        $this->mainPane->setElementStyle("float", "none");
        $this->mainPane->setElementStyle("width", "100%");


        $this->statusBox = new wMutableLabel("statusBox", $this->mainPane, "-----");
        $this->statusBox->setCSS("text-align", "left");
        $this->statusBox->setCSS("width", "600px");
        $this->statusBox->setCSS("padding-top", "10px");
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
        echo <<<EOFSCRIPT
<script>
function jsonDisplay(parent, data){
	
	/**
	 * table 을 만들어 반환함.
	 * @param {Element} parent
	 * @return {Element}
	 */
    function createTable(parent){
        var table = document.createElement("table");
        table.border = "1";
        return parent.appendChild(table);
    }
    
	/**
	 * array 타입의 데이타를 가지고 row를 생성.
	 * @param {Element} table
	 * @param {Array} array
	 * @return {void}
	 */
    function tableByArray(table, array){
        if (table._rowCount == null) {
            table._rowCount = 0;
        }
        for (var i = 0, len = array.length; i < len; i++) {
            var row = table.insertRow(table._rowCount++);
            tableByObject(row, array[i]);
        }
    }
    
	/**
	 * object 타입의 데이타를 가지고 table이나 cell을 생성.
	 * object 의 멤버중 object는 새로운 table을 만들고,
	 * object 가 아닌 멤버들은 cell로 만든다.
	 * @param {Element} tr
	 * @param {Object} obj
	 * @return {void}
	 */
    function tableByObject(tr, obj){
        if (tr._cellCount == null) {
            tr._cellCount = 0;
        }
        for (var name in obj) {
            var item = obj[name];
            var cell = tr.insertCell(tr._cellCount++);
            cell.style.verticalAlign = "top";
            tableHeaderCreate(cell.offsetParent, name);
            if (typeof item == "object") {
                if (item.constructor == Array) {
                    tableByArray(createTable(cell), item);
                }
                else {
                    tableByObject(createTable(cell).insertRow(0), item);
                }
            }
            else {
                cell.innerHTML += item;
            }
        }
    }
    
	/**
	 * 테이블의 헤더를 만든다. (thead와 비슷.)
	 * @param {Element} table
	 * @param {string} name
	 * @return {void}
	 */
    function tableHeaderCreate(table, name){
        if (table._rowCount == null) {
            table._rowCount = 0;
        }
        if (table._headerNames == null) {
            table._headerNames = [];
        }
        var i = 0, hasName;
        while (hasName = table._headerNames[i++]) {
            if (hasName == name) {
                return;
            }
        }
        table._headerNames.push(name);
        if (table._headerTr == null) {
            table._headerTr = table.insertRow(0);
            table._rowCount++;
        }
        if (table._headerTr._cellCount == null) {
            table._headerTr._cellCount = 0;
        }
        var cell = table._headerTr.insertCell(table._headerTr._cellCount++);
        cell.innerHTML = name;
    }
    
    tableByObject(createTable(parent).insertRow(0), data);
}

function j2t(div, jsonObj) {
				div.innerHTML = "";
				jsonDisplay(div, jsonObj);
				
			}
</script>
           
EOFSCRIPT;
        
    }
    function MainToolbar() {

        $searchForm = new wForm("searchForm", $this);
        $obj = newObject($this->MainClass);
        $searchForm->setDataModelFromCore($obj);
        $searchForm->setStyle("
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
        $searchForm->LineByLine = true;
        $searchButtonPanel = new wLayoutTable("", $searchForm);
        $doSearchButton = new wButton("doSearchButton", $searchButtonPanel);
        $doSearchButton->label = "Buscar";
        $doSearchButton->addListener("onclick", "doSearch", $this);
        $doSearchButton->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $searchForm->id);
        $doSearchButton->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->MainClass);

        $doCancelButton = new wButton("doCancelButton", $searchButtonPanel);
        $doCancelButton->label = "Cancelar";
        $doCancelButton->addListener("onclick", "activateSearchWindow", $this);
        $doCancelButton->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $searchForm->id . '").style.display');

        $doResetButton = new wButton("doResetButton", $searchButtonPanel);
        $doResetButton->label = "Limpiar";
        $doResetButton->addListener("onclick", "resetSearchForm", $this);

        $toolBar = new wLayout("toolBar", $this);
        $toolBar->setFree();
        $toolBar->setCSS("padding-left", "10px");

        $filter = new wInput("filterName", $toolBar);
        $filter->addListener("onblur", "changeNameFilter", $this);
        $filter->Listener["onblur"]->addParameter(XAJAX_JS_VALUE, '$("' . $filter->id . '").value');
        $filter->legend = "Filtro rapido: ";
        $filter->setSelectedValue($_SESSION["SQL_CONDSI"]["gtask"]);

        $searchButton = new wImage("SearchButton", $toolBar);
        $searchButton->label = "Buscar";
        $searchButton->src = $GLOBALS["SYS"]["ROOT"] . "/Apps/GlobalSche/local/Img/search.png";
        $searchButton->addListener("onclick", "activateSearchWindow", $this);
        $searchButton->Listener["onclick"]->addParameter(XAJAX_JS_VALUE, '$("' . $searchForm->id . '").style.display');


        $printButton = new wImage("PrintButton", $toolBar);
        $printButton->label = "Imprimir";
        $printButton->src = $GLOBALS["SYS"]["ROOT"] . "/Apps/GlobalSche/local/Img/printer.png";
        $printButton->addListener("onclick", "doPrint", $this);
    }

    function buildMultiqueryFromArray($arraydata, $class) {
        $target = newObject($class);
        $dquery = "AND (1=1 ";
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
                    $dquery.=" AND `$name` LIKE '{$arraydata[$name]}'";
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

        $_SESSION["SQL_CONDS"]["gtask"] = "  titulo liKE '%{$_SESSION["SQL_CONDSI"]["gtask"]}%' ";
        $_SESSION["SQL_CONDS"]["gtask"].=$filter;

        session_write_close();
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
        $objResponse = new xajaxResponse();
        $_SESSION["SQL_CONDS"][$this->MainClass] = "  titulo liKE '%$filter%' ";
        $_SESSION["SQL_CONDSI"][$this->MainClass] = $filter;
        session_write_close();
        $objResponse->script("tableGrid_{$this->dGrid->id}_restart()");

        return $objResponse;
    }
    
    function doPrint($event, $id) {
        $objResponse = new xajaxResponse();
        $class=$this->MainClass;
        $a=  newObject($this->MainClass);
        
        $sqlcond = $_SESSION["cache"]["gspanel"]["$class"]["lastsql"];
        $calculatedOffset = $_SESSION["cache"]["gspanel"]["$class"]["calculatedOffset"];
        $sortDriver = $_SESSION["cache"]["gspanel"]["$class"]["sortDriver"];
        
        setLimitRows(1500);
        $a->seachResults = $a->select($sqlcond, 0, $sortDriver);
        foreach ($a->properties_desc as $nombreCampo => $tipoCampo) 
            $result[0][] = $tipoCampo;
        foreach ($a->seachResults as $linea => $row) {
            $linea++;
            $result[$linea][] = $row->ID;

            foreach ($row->properties_type as $nombreCampo => $tipoCampo) {

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
     $data=json_encode(array("Ext"=>$result[0]));
     $objResponse->script("j2t($('mainLayout'),$data)");
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
                $sqlcond.=" AND {$this->SQL_CONDS["$class"]}";
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

        $_SESSION["cache"]["gspanel"]["$class"]["lastsql"] = $sqlcond;
        $_SESSION["cache"]["gspanel"]["$class"]["calculatedOffset"] = $calculatedOffset;
        $_SESSION["cache"]["gspanel"]["$class"]["sortDriver"] = $sortDriver;

        /* Nasty hook */
        if (isset($this->formatGridData)) {
	    if (is_array($this->formatGridData)) {
	      if (method_exists($this->formatGridData["class"],$this->formatGridData["method"])) {
		$class=$this->formatGridData["class"];
		$method=$this->formatGridData["method"];
		$class::$method($a->seachResults);
	      }
	    } else if (function_exists($this->formatGridData)) {
	      $func = $this->formatGridData;
	      $func($a->seachResults);
	  }
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
