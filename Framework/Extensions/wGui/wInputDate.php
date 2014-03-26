<?php

class wInputDate extends wInput implements wRenderizable {

    var $name = "";
    var $value = "";
    var $maxlenght = "";
    var $ifFormat = "%d/%m/%Y";
    var $time = null;

    function __construct($name=null, &$parent, $addPrefix=true) {
        parent::__construct($name, $parent, $addPrefix);
        //
        $this->setFormat("%d/%m/%Y");

        $this->addListener("onchange", "ValidateDate", $this);
        $this->Listener["onchange"]->addParameter(XAJAX_INPUT_VALUE, $this->id);
    }

    function render() {

        global $SYS;
        
        /* if (!$this->value)
          $this->setTime(time()); */

        foreach ($this->Listener as $k => $v) {
            if (!is_array($v))
                $eventCode.=" $k='" . $v->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            else {
                $eventCode.=" $k='" . $v[$this->ListenerAux[$k]]->getScript($SYS["ROOT"] . "/Framework/Extensions/xajax") . "'";
            }
        }
        /* JSCalendar includes */
        echo "<input type='text' name='{$this->name}' $eventCode id='{$this->id}' value='{$this->value}' style='{$this->cssStyle}' maxlength='{$this->maxlenght}' onkeyup='this.value=this.value.replace(\"-\",\"/\")' >\n<br/>";

        if ($GLOBALS["jscalendaralreadyincluded"] == false) {
            echo "
          <style type='text/css'>@import url({$SYS["ROOT"]}/Framework/Extensions/wGui/jscalendar/calendar-blue.css);</style>
          <script type='text/javascript' src='{$SYS["ROOT"]}/Framework/Extensions/wGui/jscalendar/calendar.js'></script>
          <script type='text/javascript' src='{$SYS["ROOT"]}/Framework/Extensions/wGui/jscalendar/lang/calendar-es-utf8.js'></script>
          <script type='text/javascript' src='{$SYS["ROOT"]}/Framework/Extensions/wGui/jscalendar/calendar-setup.js'></script>";
            $GLOBALS["jscalendaralreadyincluded"] = true;
        }
        echo "
        <script type='text/javascript'>
        Calendar.setup(
        {
        inputField  : '{$this->id}',         // ID of the input field
        ifFormat    : '{$this->ifFormat}',    // the date format
        button      : '{$this->id}b',       // ID of the button
        showsTime   : " . (strpos($this->ifFormat, "%H") ? "true" : "false") . "
        }
        );
        </script>";
    }

    function ValidateDate($id, $event, $myDate) {
        global $xajax;

		$myDate=strtr($myDate,array("-"=>"/"));

        $objResponse = new xajaxResponse();
        if ($this->ifFormat == "%d/%m/%Y") {
            if (text_to_int($myDate) == 3600) {
                $objResponse->script("alert('Fecha inválida -$myDate-')");
                $objResponse->assign($id, "value", int_to_text(time()));
            } else
                $objResponse->assign($id, "value", int_to_text(text_to_int($myDate)));
        }
        else if (date_parse_from_format($this->ifFormat, $myDate))
            $objResponse->assign($id, "value", $myDate);
        else {
            $objResponse->script("alert('Fecha inválida -$myDate-')");
            $objResponse->assign($id, "value", strftime($this->ifFormat, time()));
        }

        return $objResponse;
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "150px");
        $this->setCSS("float", "left");
        $this->setCSS("background-image", "url({$GLOBALS["SYS"]["ROOT"]}/Framework/Extensions/wGui/jscalendar/img/date.png)");
        $this->setCSS("background-position", "top right");
        $this->setCSS("background-repeat", "no-repeat");
    }

    function setSelectedValue($data) {
        $this->value = $data;
    }

    function setTime($time) {
        $this->value = strftime($this->ifFormat, $time);
    }

    function setFormat($format) {
        $this->ifFormat = $format;
    }

}

?>
