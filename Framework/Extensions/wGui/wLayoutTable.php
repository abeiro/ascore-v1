<?php

class wLayoutTable extends wObject implements wRenderizable {

    var $name = "";
    var $type = 0;
    var $fixedSizes = array();
    var $free = false;
    private $counter = 0;

    function __construct($name = null, &$parent) {
        parent::__construct($name, $parent);
        $this->name = $name;

        define("LAYOUT.VERTICAL", 0);
        define("LAYOUT.HORIZONTAL", 1);
        define("LAYOUT.EQUALSIZE", 3);
    }

    function render() {
        parent::render();
        echo "<table id='{$this->id}' style='{$this->cssStyle}'>\n";

        if (sizeof($this->wChildren))
            $partSize = 100 / (sizeof($this->wChildren));
        else
            $partSize = 100;

        $counter = 0;
        $partSize.="%";

        if ($this->type == 0)
            echo "<tr>";
        foreach ($this->wChildren as $k => $c) {

            /* if ($k==(sizeof($this->wChildren)-1))
              $partSize="99%"; */

            if ($this->type == 0) {
                if (sizeof($this->fixedSizes) == sizeof($this->wChildren))
                    $partSize = $this->fixedSizes[$counter];
                if ($this->free)
                    $partSize = "";
                echo "<!-- START OF LAYOUT PART-->\n<td valign='top' align='left' style='align:left;{$this->childCssStyle}' width='$partSize'>";
                $c->render();
                echo "</td>\n<!-- END OF LAYOUT PART-->\n";
            }
            if ($this->type == 1) {
                if (sizeof($this->fixedSizes) == sizeof($this->wChildren))
                    $partSize = $this->fixedSizes[$counter];
                if ($this->free)
                    $partSize = "";
                echo "<tr><td valign='top'  align='left' style='' width='$partSize'>\n\t";
                $c->render();
                echo "</td></tr>\n<!-- END OF LAYOUT PART-->\n";
            }

            $counter++;
        }
        if ($this->type == 0)
            echo "</tr>";
        echo "</table><!-- END OF LAYOUT -->";
    }

    function setVertical() {
        $this->type = LAYOUT . VERTICAL;
    }

    function setHorizontal() {
        $this->type = LAYOUT . HORIZONTAL;
    }

    function _setDefaults() {
        /* Some default properties */
        //$this->setCSS("border","1px solid gray");
        $this->setCSS("overflow", "hidden");
        $this->setCSS("margin", "0px");
        $this->setCSS("padding", "0px");
        $this->setCSS("width", "100%");
    }

    function setFree($free = true) {
        $this->free = $free;
    }

}

?>
