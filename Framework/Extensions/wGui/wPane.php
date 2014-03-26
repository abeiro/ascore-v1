<?php

class wPane extends wObject implements wRenderizable {

    var $name = "";
    var $type = 0;
    var $visibility = "visible";
    var $elementStyle = array();
    private $counter = 0;

    function __construct($name=null, &$parent) {
        parent::__construct($name, $parent);
        $this->name = $name;

        define("PANE.VERTICAL", 0);
        define("PANE.HORIZONTAL", 1);
        $this->setElementStyle("float", "left");
    }

    function render() {
        parent::render();
        if (sizeof($this->wChildren))
            $partSize = 100 / (sizeof($this->wChildren));
        else
            $partSize = 100;

        echo "<div id='{$this->id}' style='visibility:{$this->visibility};{$this->cssStyle}'>\n";
        foreach ($this->wChildren as $k => $c) {
            echo "<!-- START OF PANE PART-->\n<div style='{$this->elementStyleCSS}'>";
            $c->render();
            echo "</div>\n<!-- END OF PANE PART-->\n";
        }

        echo "</div><!-- END OF PANE -->";
    }

    function setVertical() {
        $this->type = PANE . VERTICAL;
    }

    function setHorizontal() {
        $this->type = PANE . HORIZONTAL;
    }

    function setElementStyle($p, $v) {
        $this->elementStyle[$p] = "$v";
        $this->elementStyleCSS = "";
        foreach ($this->elementStyle as $i => $j)
            $this->elementStyleCSS.="$i:$j;";
    }

    function _setDefaults() {
        /* Some default properties */
// 		$this->setCSS("border","1px solid gray");
        $this->setCSS("overflow", "hidden");
    }

}

class wSimplePane extends wObject implements wRenderizable {
   
	function render() {
        parent::render();
    
        echo "<div id='{$this->id}' style='{$this->cssStyle}'>\n";
        foreach ($this->wChildren as $k => $c) 
            $c->render();
        echo "</div><!-- END OF PANE -->";
    }

}
?>
