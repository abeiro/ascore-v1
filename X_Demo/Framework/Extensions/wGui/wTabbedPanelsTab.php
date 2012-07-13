<?php

class wTabbedPanelsTab extends wObject implements wRenderizable {

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

        echo "<ul class='TabbedPanelsTabGroup'>
			<li class='TabbedPanelsTab'>{$this->label}</li> 
			
		     </ul>";

        echo "<div class='TabbedPanelsContentGroup'>\n";
// 		foreach ($this->wChildren as $c) 
// 			echo "<div class='TabbedPanelsContent'>";$c->render();echo "</div>\n";
        echo "<div class='TabbedPanelsContent'>Es una prueba</div>\n";
        echo "</div>";


        echo "</div><!-- END OF TABBEDPANE -->
<script type='text/javascript'>
	var TabbedPanels1 = new Spry.Widget.TabbedPanels('TabbedPanels1');
</script>";

// <div class="TabbedPanelsContentGroup">
// 			<div class="TabbedPanelsContent">Tab 1 Content</div>
// 			<div class="TabbedPanelsContent">Tab 2 Content</div>
// 			<div class="TabbedPanelsContent">Tab 3 Content</div> 
// 			<div class="TabbedPanelsContent">Tab 4 Content</div> 
// 		</div>
    }

    /* Some default properties */

    function _setDefaults() {

        $this->setCSS("width", "100%");
    }

}

?>