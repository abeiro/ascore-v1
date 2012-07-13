<html>
<head>

<?php 

/**
* Simple Example
*
* This file demonstrates how to turn a simple Text Area into an AreaEdit component
* and how to do a javascript submit.
*
* This file was originally written by James Sleeman of Gogo Code and comes from the
* Xinha project.
*/
?>

  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Simple Example of AreaEdit</title>
  <link rel="stylesheet" href="examples.css" />

  <script type="text/javascript">

    // You must set _editor_url to the URL (including trailing slash) where
    // where AreaEdit is installed, it's highly recommended to use an absolute URL
    //  eg: _editor_url = "/path/to/areaedit/";
    // You may try a relative URL if you wish]
    //  eg: _editor_url = "../";

    // in this example we do a little regular expression to find the absolute path.

    _editor_url  = document.location.href.replace(/examples\/simple_example\.php.*/, '')
    _editor_lang = "en";      // And the language we need to use in the editor.

  </script>

  <!--  load in debug trace message class -->

  <script type="text/javascript" src="../ddt/ddt.js"></script>

  <script type="text/javascript">

  // create a global debug object to track everything that happens
  // during startup.

  var startupDDT = new DDT( "startup" );

  // uncomment the following if you would like to trace out the 
  // startup functions. This only works in the debugging version
  // of AreaEdit, not the runtime.
  //
  // startupDDT._ddtOn();

  </script>
  
  <!-- Load up the actual editor core -->
  <script type="text/javascript" src="../htmlarea.js"></script>

  <script type="text/javascript">

    areaedit_editors = null;
    areaedit_init    = null;
    areaedit_config  = null;
    areaedit_plugins = null;

	startupDDT._ddt( "simple_example.html", "71", "Setting up areaedit_init()" );

	// --------------------------------------------------------------------------------

	/**
	* sample initialization function 
	*
	* this is called from the body onload handler below. It sets up the configurations
	* and builds the editor.
	*/

	areaedit_init = function()
		{

		startupDDT._ddt( "simple_example.html", "76", "areaedit_init called from window.onload handler for simple_example.php" );

      /** STEP 1 ***************************************************************
       * First, what are the plugins you will be using in the editors on this
       * page.  List all the plugins you will need, even if not all the editors
       * will use all the plugins.
       ************************************************************************/

		// a minmal list of plugins.

      areaedit_plugins_minimal = 
      [
       'ContextMenu',
       'Linker',
		 'ImageManager'
      ];

      // This loads the plugins. We're using a very minimal list
		// here.
		//
		// loadPlugins causes the plugin .js files (in this case content-menu.js,
		// linker.js and image-manager.js) in the "background". The second parameter
		// here is a callback that gets invoked while we're waiting for things to load,
		// which in this case just causes us to loop back to here. Once everything 
		// is loaded loadPlugins() returns true and we can continue on.

	   startupDDT._ddt( "simple_example.html", "92", "calling HTMLArea.loadplugins()" );

      if ( !HTMLArea.loadPlugins( areaedit_plugins_minimal, areaedit_init)) 
			{
			return;
			}

      /** STEP 2 ***************************************************************
       * Now, what are the names of the textareas you will be turning into
       * editors? For this example we're only loading 1 editor.
       ************************************************************************/

      areaedit_editors = 
      [
        'TextArea1'
      ];

      /** STEP 3 ***************************************************************
       * We create a default configuration to be used by all the editors.
       * If you wish to configure some of the editors differently this will be
       * done in step 4.
       *
       * If you want to modify the default config you might do something like this.
       *
       *   areaedit_config = new HTMLArea.Config();
       *   areaedit_config.width  = 640;
       *   areaedit_config.height = 420;
       *
       *************************************************************************/

  	    startupDDT._ddt( "simple_example.html", "119", "calling HTMLArea.Config()" );

       areaedit_config = new HTMLArea.Config();

      /** STEP 3 ***************************************************************
       * We first create editors for the textareas.
       *
       * You can do this in two ways, either
       *
       *   areaedit_editors   = HTMLArea.makeEditors(areaedit_editors, areaedit_config, areaedit_plugins);
       *
       * if you want all the editor objects to use the same set of plugins, OR;
       *
       *   areaedit_editors = HTMLArea.makeEditors(areaedit_editors, areaedit_config);
       *   areaedit_editors['myTextArea'].registerPlugins(['Stylist','FullScreen']);
       *   areaedit_editors['anotherOne'].registerPlugins(['CSS','SuperClean']);
       *
       * if you want to use a different set of plugins for one or more of the
       * editors.
       ************************************************************************/

      startupDDT._ddt( "simple_example.html", "140", "calling HTMLArea.makeEditors()" );

      areaedit_editors   = HTMLArea.makeEditors(areaedit_editors, areaedit_config, areaedit_plugins_minimal);

      /** STEP 4 ***************************************************************
       * If you want to change the configuration variables of any of the
       * editors,  this is the place to do that, for example you might want to
       * change the width and height of one of the editors, like this...
       *
       *   areaedit_editors.myTextArea.config.width  = 640;
       *   areaedit_editors.myTextArea.config.height = 480;
       *
       ************************************************************************/

       areaedit_editors.TextArea1.config.width  = 700;
       areaedit_editors.TextArea1.config.height = 350;

      /** STEP 5 ***************************************************************
       * Finally we "start" the editors, this turns the textareas into
       * AreaEdit editors.
       ************************************************************************/

      startupDDT._ddt( "simple_example.html", "160", "calling HTMLArea.startEditors()" );

      HTMLArea.startEditors(areaedit_editors);

    	}  // end of areaedit_init()

	/**
	* javascript submit handler.
	*
	* this shows how to create a javascript submit 
	* button that works with the htmleditor.
	*/

	submitHandler = function(formname) 
		{
	   var form = document.getElementById(formname);

		// in order for the submit to work both of these methods have to be
		// called.

	   form.onsubmit(); 
		form.submit();
		}

  </script>
</head>

<body onload="areaedit_init()">

	<?php

	// handle the form was submitted case.

	if ( @$_POST["TextArea1"] != NULL )
		{

		// the user submitted the form. For now we'll just display it bracketed 
		// by a couple of hr's.

		print( "<b>Content submitted was:</b><br>\n" );
		print( $_POST["TextArea1"] );

		}

	?>

	<hr>
  <h1>AreaEdit Simple Example</h1>

  <p>This file demonstrates a simple integration of the AreaEdit editor with a minimal
  set of plugins. Enter some content, press submit and the content will be displayed
  above.</p>
  <br>

  <a href="../index.html">Back to Index</a>

  <br>
  <hr>
  <br>

  <form id="editors_here" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">

    <textarea id="TextArea1" name="TextArea1" rows="10" cols="80" style="width:100%">
	 This is the content of TextArea1 from areaedit/examples/simple_example.php.
	 </textarea>

	<br>

	<?php

	// the submitHandler() function is defined in the top section and shows how to 
	// submit a form with an AreaEdit component in it from javascript.

	?>
	<input type="button" value="submit" onclick="javascript:submitHandler('editors_here');">
  </form>

<br>
<br>
<a href="../index.html">Back to Index</a>
<br>
</body>
</html>
