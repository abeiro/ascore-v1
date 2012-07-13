<?php

class wPhpExt_Grid {


	function __construct() {}
	
	public static function Render($dataModel,$object,$PageSize=5,$Titulo='') {

		
		
		include_once 'PhpExt/Ext.php';
		include_once 'PhpExt/Data/SimpleStore.php';
		include_once 'PhpExt/Data/ArrayReader.php';
		include_once 'PhpExt/Data/JsonReader.php';
		include_once 'PhpExt/Data/ScriptTagProxy.php';
		include_once 'PhpExt/Data/FieldConfigObject.php';
		include_once 'PhpExt/Data/StoreLoadOptions.php';
		include_once 'PhpExt/Data/HttpProxy.php';
		include_once 'PhpExt/Data/JsonStore.php';
		include_once 'PhpExt/Button.php';
		include_once 'PhpExt/Toolbar/PagingToolbar.php';
		include_once 'PhpExt/Grid/ColumnModel.php';
		include_once 'PhpExt/Grid/ColumnConfigObject.php';
		include_once 'PhpExt/Grid/GridPanel.php';
		include_once 'PhpExt/Window.php';
		include_once 'PhpExt/Form/FormPanel.php';
		include_once 'PhpExt/Ext.php';
		include_once 'PhpExt/Data/Store.php';
		include_once 'PhpExt/Data/ArrayReader.php';
		include_once 'PhpExt/Data/FieldConfigObject.php';
		include_once 'PhpExt/Grid/ColumnModel.php';
		include_once 'PhpExt/Grid/ColumnConfigObject.php';
		include_once 'PhpExt/Panel.php';
		include_once 'PhpExt/Grid/GridPanel.php';
		include_once 'PhpExt/Grid/RowSelectionModel.php';
		include_once 'PhpExt/Listener.php';
		include_once 'PhpExt/Config/ConfigObject.php';
		include_once 'PhpExt/Form/FormPanel.php';
		include_once 'PhpExt/Form/FieldSet.php';
		include_once 'PhpExt/Form/TextField.php';
		include_once 'PhpExt/QuickTips.php';
		include_once 'PhpExt/Layout/ColumnLayout.php';
		include_once 'PhpExt/Layout/ColumnLayoutData.php';
		include_once 'PhpExt/Layout/FitLayout.php';
		
		
			
		
		
		
		$reader = new PhpExt_Data_JsonReader();
		$reader->setRoot("topics")
       ->setTotalProperty("totalCount")
       ->setId("ID");
		
		foreach ($object->properties as $k=>$v) {
			$reader->addField(new PhpExt_Data_FieldConfigObject($k));
		}


		$store = new PhpExt_Data_Store();
		$store->setUrl('action_main.php')   
   			->setReader($reader)
   			->setBaseParams(array("limit"=>$PageSize));
   
				
		
		$colModel = new PhpExt_Grid_ColumnModel();
		foreach ($object->properties as $k=>$v) {
			if (strpos($k,"S_")!==0)
			$colModel->addColumn(PhpExt_Grid_ColumnConfigObject::createColumn($object->properties_desc[$k],$k,$k,null, null, null, true, true));
			
		}
		
		
		
		readfile(dirname(__FILE__)."/local/Tmpl/PhpExt.tmpl");
		
	
		
		$selModel = new PhpExt_Grid_RowSelectionModel();
		$selModel->setSingleSelect(true)
         ->attachListener("rowselect", 
			new PhpExt_Listener(PhpExt_Javascript::functionDef(null,
				"Ext.getCmp(\"idGrid\").getForm().loadRecord(rec);",
				array("sm","row","rec")))
			);



		
		// Grid
		$grid = new PhpExt_Grid_GridPanel("idGrid");
		$grid->setStore($store)
			->setColumnModel($colModel)
			->setStripeRows(true)
			 ->setWidth(500)
			 ->setHeight(350)
			->setTitle("Elementos");
		$grid->setSelectionModel($selModel);
		
		
		$paging = new PhpExt_Toolbar_PagingToolbar();
		$paging->setStore($store)
       ->setPageSize($PageSize)
       ->setDisplayInfo("Topics {0} - {1} of {2}")
       ->setEmptyMessage("No topics to display");	
		$grid->setBottomToolbar($paging);
		
		
		$window = new PhpExt_Window();
		$window->setTitle($Titulo)
       ->setWidth(600)
       ->setHeight(450)
       ->setMinWidth(300)
       ->setMinHeight(200)
       ->setPlain(true)
       ->setBodyStyle("padding:5px")
       ->setButtonAlign(PhpExt_Ext::HALIGN_CENTER);
$window->addButton(PhpExt_Button::createTextButton("Editar"));
$window->addButton(PhpExt_Button::createTextButton("Borrar"));
$window->addItem($grid); 



		// Ext.OnReady -----------------------

		echo PhpExt_Ext::onReady(
			null,
			null,
			$store->getJavascript(false, "store"),
			$store->load(new PhpExt_Data_StoreLoadOptions(array(
					"start"=>0,"limit"=>$PageSize))
			),
			$grid->getJavascript(false, "grid"),
			$window->getJavascript(false,"window"),
			$window->show()
		);

		echo "</script>";
	}
}
?>