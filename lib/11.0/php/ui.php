<?php
	
	import("data");
	import("io");
	require_once("$LIB_PATH/php/ui/jslib.php");
	require_once("$LIB_PATH/php/ui/csslib.php");
	require_once("$LIB_PATH/php/ui/uimodule.php");
	global $JS,$CSS,$UI;
	$JS = new UIJSLIB();
	$CSS = new UICSSLIB();
	$UI = new UIModule();
	
	require_once("$LIB_PATH/php/ui/object.php");
	require_once("$LIB_PATH/php/ui/event.php");
	require_once("$LIB_PATH/php/ui/css.php");
	
	
	require_once("$LIB_PATH/php/ui/textbox.php");
	require_once("$LIB_PATH/php/ui/button.php");
	require_once("$LIB_PATH/php/ui/combo.php");
	require_once("$LIB_PATH/php/ui/list.php");
	require_once("$LIB_PATH/php/ui/hidden.php");
	require_once("$LIB_PATH/php/ui/textarea.php");
	require_once("$LIB_PATH/php/ui/group.php");
	require_once("$LIB_PATH/php/ui/checkbox.php");
	require_once("$LIB_PATH/php/ui/radio.php");
	require_once("$LIB_PATH/php/ui/image.php");
	require_once("$LIB_PATH/php/ui/tablecolumn.php");
	require_once("$LIB_PATH/php/ui/table.php");
	require_once("$LIB_PATH/php/ui/span.php");
	require_once("$LIB_PATH/php/ui/link.php");
	require_once("$LIB_PATH/php/ui/form.php");
	require_once("$LIB_PATH/php/ui/ullist.php");
	require_once("$LIB_PATH/php/ui/fileinput.php");
	require_once("$LIB_PATH/php/ui/richedit.php");
	require_once("$LIB_PATH/php/ui/contextmenu.php");
	
	require_once("$LIB_PATH/php/ui/pagecontainer.php");
	
	require_once("$LIB_PATH/php/ui/jq.tab.php");
	require_once("$LIB_PATH/php/ui/jq.accordion.php");
	require_once("$LIB_PATH/php/ui/jq.date.php");
	require_once("$LIB_PATH/php/ui/jq.menu.php");
	require_once("$LIB_PATH/php/ui/jq.menu2.php");
	require_once("$LIB_PATH/php/ui/jq.dialog.php");
	require_once("$LIB_PATH/php/ui/jq.chart.php");
	
	
	
	
	require_once("$LIB_PATH/php/ui/tokeninput.php");
	require_once("$LIB_PATH/php/ui/meter.php");
	require_once("$LIB_PATH/php/ui/datebox.php");
	require_once("$LIB_PATH/php/ui/datetimebox.php");
	require_once("$LIB_PATH/php/ui/searchform.php");
	require_once("$LIB_PATH/php/ui/listofvalue.php");
	require_once("$LIB_PATH/php/ui/lovButton.php");
	require_once("$LIB_PATH/php/ui/lovbox.php");
	require_once("$LIB_PATH/php/ui/addButton.php");
	
?>