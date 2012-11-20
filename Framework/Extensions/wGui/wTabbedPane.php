<?php

class wTabbedPane extends wObject implements wRenderizable {

    var $content;
    var $name = "";
    var $label = "";

    function setHTML($HTML) {
        $this->content = $HTML;
    }

    function render() {
        global $SYS;
        parent::render();
        echo "<div class='TabbedPanels' id='{$this->id}'>";
        $counter = 0;
        foreach ($this->wChildren as $k => $c) {
            echo "<ul class='TabbedPanelsTabGroup'>
			<li onclick='TabPanShow(\"{$this->id}_{$c->id}\")' id='{$this->id}_{$c->id}_selector'class='TabbedPanelsTab'>{$c->name}</li> 
		     </ul>";
        }
        echo "<div id='{$this->id}_parent_node'>";
        foreach ($this->wChildren as $k => $c) {
            $display = ($counter == 0) ? "block" : "none";
            echo "<!--- START OF TAB PANEL CHILDREN {$this->id}_{$c->id} -->\n";
            echo "<div id='{$this->id}_{$c->id}' style='display:$display'  class='TabbedPanelsContentGroup'>\n";
            $c->render();
            echo "</div>";
            echo "<!--- END OF TAB PANEL CHILDREN -->\n";
            $counter++;
        }
        echo "</div>";
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "100%");
    }

}

?>