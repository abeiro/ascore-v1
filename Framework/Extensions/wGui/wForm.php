<?php

class wForm extends wObject implements wRenderizable {

    var $name = "";
    var $coreObject = null;
    var $afterSaveMethod = null;
    var $buttonPane = null;
    var $components = array();
    var $LineByLine=false;
    function __construct($name=null, &$parent) {
        global $xajax;

        parent::__construct($name, $parent);
        $this->name = $name;
        $lfr = $xajax->register(XAJAX_FUNCTION, "requestloadFromId");
        $lfr->addParameter(XAJAX_INPUT_VALUE, $this->id);
        $lfr->addParameter(XAJAX_INPUT_VALUE, $this->id);
    }

    function createDefaultButtons() {

        $this->buttonPane = new wPane("Panel de Botones", $this);
        $this->buttonPane->setCSS("padding", "5px");
        $this->buttonPane->setCSS("text-alignment", "center");

        $b1 = new wButton("Guardar", $this->buttonPane, "Guardar");
        $b2 = new wButton("Eliminar", $this->buttonPane, "Eliminar");
        $b3 = new wButton("Nuevo", $this->buttonPane, "Nuevo");

        $b1->addListener("onclick", "requestSaveForm", $this);
        $b1->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->id);
        $b1->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->coreObject->name);
        $b1->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->id);


        $b2->addListener("onclick", "requestDeleteForm", $this);
        $b2->Listener["onclick"]->addParameter(XAJAX_FORM_VALUES, $this->id);
        $b2->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->coreObject->name);
        $b2->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->id);


        $b3->addListener("onclick", "requestNewForm", $this);
        $b3->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->id);
        $b3->Listener["onclick"]->addParameter(XAJAX_QUOTED_VALUE, $this->coreObject->name);
    }

    function render() {
        parent::render();
        echo "<form name='{$this->name}' id='{$this->id}' action='#' style='{$this->cssStyle}'>\n";
        //     $cssWidth="width:25%";

        foreach ($this->wChildren as $c) {
            if ($this->LineByLine) {
                echo "<div style='width:100%;padding:2px;break:all'>";
                $c->render();
                   echo "</div>";
            } else {
                $count++;
                if ($count % 2 == 0) {
                    echo "<div style='float:left;width:73%;padding:2px'>";
                    $c->render();
                    echo '</div>';
                } else {
                    echo "<div style='float:left;width:25%;min-width:175px;border:1px;padding:2px'>";
                    $c->render();
                    echo '</div>';
                }
            }
        }
        echo "<br clear=\"both\" />";
        //$this->buttonPane->render();
        echo "</form>";
    }

    function setDataModelFromCore($object) {

        $this->coreObject = $object;
        $object->createFormFromEntity();
        $xmldata = $object->_arrayForm;

        foreach ($xmldata["form"]["elements"] as $k1 => $v1) {
            switch ($v1["type"]) {

                /* TEXT MODE */
                case "text":
                    switch ($v1["format"]) {
                        case "string":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new WInput($k1, $this);
                            $options = substr($object->properties_type[$k1], strpos($object->properties_type[$k1], ":") + 1);
                            $inputs["$k1"]->setCSS("width", ($options * 2.5) . "px");
                            break;

                        case "date":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new wInputDate($k1, $this);
                            if (strlen($object->properties_type[$k1]) > 5)
                                $inputs["$k1"]->setFormat(substr($object->properties_type[$k1], strpos($object->properties_type[$k1], ":") + 1));

                            if ($object->$k1 > 3600)
                                $inputs["$k1"]->setTime($object->$k1);

                            break;

                        case "password":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new wPassword($k1, $this);
                            break;
                        case "int":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new WInput($k1, $this);
                            break;


                        case "float":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new WInput($k1, $this);

                            break;

                        case "money":
                            $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                            $inputs["$k1"] = new WInput($k1, $this);
                            break;
                        default:
                            break;
                    }
                    break;

                /* TEXTAREA  MODE */

                case "textarea":
                    $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                    $inputs["$k1"] = new wTextArea($k1, $this);
                    $inputs["$k1"]->setCSS("width", "100%");
                    $inputs["$k1"]->setCSS("height", "50px");


                    break;

                /* SELECT  MODE */
                case "select":
                    $labels["$k1"] = new wLabel("-", $this, $v1["label"]);

                    switch ($v1["format"]) {
                        case "list":
                            $inputs["$k1"] = new wListBox($k1, $this);
                            $options = substr($object->properties_type[$k1], strpos($object->properties_type[$k1], ":") + 1);
                            $ops = explode("|", $options);
                            $o = null;
                            if ($object->properties_properties[$k1]["mandatory"])
                                $o["-"] = "-";
                            foreach ($ops as $minikey => $minival)
                                $o["$minival"] = $minival;
                            $inputs["$k1"]->setSelectedIndex($object->$k1);
                            $inputs["$k1"]->setDataModel($o);

                            /* if(isset($v1["attributes"]))
                              foreach($v1["attributes"] as $k3=>$v3)
                              $q.="$k3=\"$v3\" ";
                              $q.=">"; */

                            break;

                        case "ref":
                            $inputs["$k1"] = new wListBox($k1, $this);
                            $references = $object->get_references($k1);
                            $inputs["$k1"]->setSelectedIndex($object->$k1);
                            $inputs["$k1"]->setDataModel($references);


                            break;

                        default:
                            if (isset($v1["attributes"]["multiple"])) {
                                $q.="\n\t<td>{$v1["label"]}<select name=\"" . $k1 . "[]\" id=\"" . $k1 . "\" ";
                            } else
                                $q.="\n\t<td>{$v1["label"]}<select name=\"" . $k1 . "\" id=\"" . $k1 . "\" ";
                            if (isset($v1["attributes"]))
                                foreach ($v1["attributes"] as $k3 => $v3)
                                    $q.="$k3=\"$v3\" ";
                            $q.="><!-- X:" . $k1 . " --></select>";
                            break;
                    }
                    break;

                case "checkbox":
                    $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                    $inputs["$k1"] = new wCheckBox($k1, $this);
                    break;

                case "hidden":
                    $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                    $inputs["$k1"] = new wHidden($k1, $this);
                    break;

                case "image":
                    $labels["$k1"] = new wLabel("-", $this, $v1["label"]);
                    $inputs["$k1"] = new WInput($k1, $this);
                    break;

                default:
                    break;
            }
        }
        $inputs["ID"] = new wHidden("ID", $this);
        $inputs["ID"]->setSelectedValue($object->ID);
        $this->components = $inputs;
        //print_r($this);
    }

    function _setDefaults() {
        /* Some default properties */
    }

    /*
      REQUEST LOAD
     */

    public function requestloadFromId($id, $fjid, $class) {
        $this->coreObject = newObject("$class", $id);
        $objResponse = new xajaxResponse();
        foreach ($this->coreObject->properties as $k => $v) {
            if (strpos($this->coreObject->properties_type["$k"], "boolean") === 0)
                $objResponse->script("$(\"$fjid.$k\").checked=" . wForm::dataFormater($v, $this->coreObject->properties_type[$k]));
            else
                $objResponse->assign("$fjid.$k", "value", wForm::dataFormater($v, $this->coreObject->properties_type[$k]));
        }
        $objResponse->assign("$fjid.ID", "value", $id);

        $MethodtoCall = "afterrequestloadFromId";
        $cParent = &$this->wParent;
        while ($cParent) {
            debug(__FILE__ . " Calling parent: " . get_class($cParent), "white");
            if (method_exists($cParent, $MethodtoCall)) {
                debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
                call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $fjid);
                break;
            }
            else
                $cParent = &$cParent->wParent;
        }

        return $objResponse;
    }

    /*
      REQUEST SAVE
     */

    public function requestSaveForm($id, $fjid, $data, $classname, $jsorig) {
        $this->coreObject = newObject("$classname", $id);
        $this->coreObject->setAll($data);
        $objResponse = new xajaxResponse();
        if ($id < 2)
            $this->coreObject->__isNew = true;

        $this->coreObject->ID = $this->coreObject->save();
        if ($this->coreObject->ID < 2) {
            $objResponse->script("alert('{$this->coreObject->ERROR}')");
        }

        
		debug("Calling parent: " . print_r($this->afterSaveMethod,true), "white");
		if (is_array($this->afterSaveMethod)) {
			foreach ($this->afterSaveMethod as $singleMethod) {
				$MethodtoCall = $singleMethod;
				$cParent = &$this->wParent;
				while ($cParent) {
					debug("Calling parent: $singleMethod " . get_class($cParent), "white");
					if (method_exists($cParent, $MethodtoCall)) {
						debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
						call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $jsorig);
						break;
					}
					else
						$cParent = &$cParent->wParent;
				}
			}
		} else {
			$MethodtoCall = $this->afterSaveMethod;
			$cParent = &$this->wParent;
			while ($cParent) {
				debug("Calling parent: " . get_class($cParent), "white");
				if (method_exists($cParent, $MethodtoCall)) {
					debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
					call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $jsorig);
					break;
				}
				else
					$cParent = &$cParent->wParent;
			}
		}

        $MethodtoCall = $this->afterSaveMethod;
        $cParent = &$this->wParent;
        while ($cParent) {
            debug("Calling parent: " . get_class($cParent), "white");
            if (method_exists($cParent, $MethodtoCall)) {
                debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
                call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $jsorig);
                break;
            }
            else
                $cParent = &$cParent->wParent;
        }

        if ($this->coreObject->__isNew) {
        //xajax_wForm.requestNewForm("Nuevo", "onclick", "formgtasklogRegistros", "gtasklog")
            $objResponse->script("xajax_wForm.requestloadFromId({$this->coreObject->ID},'{$this->id}','$classname')");
        }
        return $objResponse;
    }

    public function requestDeleteForm($id, $fjid, $data, $classname, $jsorig) {
        $this->coreObject = newObject("$classname", $id);
        $this->coreObject->load($data["ID"]);
        $objResponse = new xajaxResponse();

        if (!$this->coreObject->delete()) {
            $objResponse->script("alert('{$this->ERROR}')");
        }
        $MethodtoCall = $this->afterDeleteMethod;
        $cParent = &$this->wParent;
        while ($cParent) {
            debug("Calling parent: " . get_class($cParent), "white");
            if (method_exists($cParent, $MethodtoCall)) {
                debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
                call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $jsorig);
                break;
            }
            else
                $cParent = &$cParent->wParent;
        }
        $objResponse->script("$('{$this->id}').reset()");
        return $objResponse;
    }

    public function requestNewForm($id, $fjid, $data, $classname) {
        $objResponse = $this->requestloadFromId(1, $data, $classname, $fjid);
        $MethodtoCall = "afterRequestNewForm";
        $cParent = &$this->wParent;
        while ($cParent) {
            debug(__FILE__ . "Calling parent: " . get_class($cParent), "white");
            if (method_exists($cParent, $MethodtoCall)) {
                debug("Parent component mehtod exsists $MethodtoCall: " . get_class($cParent), "blue");
                call_user_func(array($cParent, $MethodtoCall), $objResponse, $this, $data, $fjid);
                break;
            }
            else
                $cParent = &$cParent->wParent;
        }

        return $objResponse;
    }

    public static function dataFormater($value, $type) {
        debug(__FILE__ . " " . __FUNCTION__ . " $value,$type", "red");
        if (strpos($type, "date") !== false) {
            /* Date */
            if ($value > 10000) {
                $format = substr($type, strpos($type, ":") + 1);
                return utf8_encode(strftime(($format) ? $format : "%d/%m/%Y", $value));
            } else
                return "";
        } else if (strpos($type, "boolean") === 0) {
            /* Date */
            if ($value == "Si")
                return "true";
            else
                return "false";
        }
        return $value;
    }

    public function doAfterSave($method) {

        $this->afterSaveMethod[] = $method;
    }

    public function doAfterDelete($method) {

        $this->afterDeleteMethod = $method;
    }

}

?>
