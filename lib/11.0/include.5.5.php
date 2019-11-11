<?php
@session_start();
$libVersion = "5.5";
$bootstrapVersion = "3";
$databoxVersion = "1.1";
$_PWD = getcwd() ;

require_once("$_PWD/yajan/etc/yajan.conf.php");

global $SCRIPT_PATH,$URL_QUERY,$REQUEST_ID,$FREAMWORK_PATH;
$FILENAME = basename($_SERVER['SCRIPT_FILENAME']);
$SCRIPT_PATH = str_replace("/$FILENAME","",$_SERVER['SCRIPT_NAME']);

$REQUEST_ID = uniqid(true);
$EXEC_MODE="";
if(php_sapi_name() == "cli") 
{
	$EXEC_MODE="CLI";
} 
else 
{
    $QUERY_STRING = $_SERVER['QUERY_STRING'];
	$EXEC_MODE="WEB";
	$sqlInjectionWord = array('update','union','delete','insert','alter','create','drop','--');
	for($i=0;$i<count($sqlInjectionWord);$i++)
	{
		$w = $sqlInjectionWord[$i];
		if(strpos(strtolower($QUERY_STRING),strtolower($w))!==false)
		{
			echo 'Critical error.';
			exit(0);
		}
	}
	
}

$SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];

$LIB_PATH="$_PWD/$FREAMWORK_PATH/lib/$libVersion";
$YAJAN_PATH = "$FREAMWORK_PATH/lib/$libVersion";
$ONLINE_LIB_PATH="$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion";
$ONLINE_LIB_PATH = ltrim($ONLINE_LIB_PATH,"/");
include("$LIB_PATH/php/system.php");

function import($packege)
{
	global $PACKEGES;
	$PACKEGES->import($packege);
}

$_0xp2323 = explode(",",$DEFAULT_IMPORT_PACKEG);

foreach($_0xp2323 as $_0xk2323)
{
	import($_0xk2323);
}

import("system");

/*	Init of database	*/
global $$DEFAULT_DB_OBJECT;
if($DATABASE_AUTO_OPEN)
{
	global $DB_REDOLOG_FILE;
	import("db");
	$$DEFAULT_DB_OBJECT = new Connection($$DEFAULT_DB_CONFIG);
	$$DEFAULT_DB_CONFIG = array();
	$$DEFAULT_DB_OBJECT->setRedoLog($DB_REDOLOG_FILE);
}

?>