<?php

if(isset($argv))
{
	for($i=1;$i<count($argv);$i++)
	{
		if($argv[$i]!="")
		{
			
			if(strpos($argv[$i],'=')>0)
			{
				$ix = explode("=",$argv[$i]);
				$ixx="__".$ix[0];
				$$ixx=$ix[1];
			}
			else
			{
				$cmd=$argv[$i];
			}
		}
	}
}
if(isset($__yajan_base))
{
	$YAJAN_DATA=$__yajan_base;
}
if(!is_dir($YAJAN_DATA))
{
	mkdir($YAJAN_DATA);
}

if(!function_exists("getmicrotime"))
{
	function getmicrotime() 
	{ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
}
$_PWD = getcwd();
@session_start();

//require_once("$_PWD/yajan/etc/yajan.conf.php");

global $SCRIPT_PATH,$URL_QUERY,$REQUEST_ID,$FREAMWORK_PATH,$YAJAN_DATA;
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
if(!isset($YAJAN_HOME))
{
	$LIB_PATH="$_PWD/$FREAMWORK_PATH/lib/$libVersion";
	require_once ("$LIB_PATH/php/system.php");
	$RELETIVE_YAJAN_HOME="";
	$YAJAN_PATH = "$FREAMWORK_PATH/lib/$libVersion";
	$ONLINE_LIB_PATH="$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion";
	$ONLINE_LIB_PATH = ltrim($ONLINE_LIB_PATH,"/");
}
else
{
	$LIB_PATH="$YAJAN_HOME/$FREAMWORK_PATH/lib/$libVersion";
	require_once ("$LIB_PATH/php/system.php");
	$RELETIVE_YAJAN_HOME = getRelativePath($_PWD,$YAJAN_HOME);
	$YAJAN_PATH = "$RELETIVE_YAJAN_HOME$FREAMWORK_PATH/lib/$libVersion";
	$ONLINE_LIB_PATH="$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion";
	$ONLINE_LIB_PATH = ltrim($ONLINE_LIB_PATH,"/");
}



$e = getmicrotime();
if(!function_exists("import"))
{
	function import($packege)
	{
		global $PACKEGES;
		$PACKEGES->import($packege);
	}
}
if(!isset($DEFAULT_IMPORT_PACKEG))
{
	$DEFAULT_IMPORT_PACKEG="";
}
$_0xp2323 = explode(",",$DEFAULT_IMPORT_PACKEG);

foreach($_0xp2323 as $_0xk2323)
{
	import($_0xk2323);
}


if(!isset($DEFAULT_DB_OBJECT))
{
	$DEFAULT_DB_OBJECT="db";
}
/*	Init of database	*/
global $$DEFAULT_DB_OBJECT,$DATABASE_AUTO_OPEN;
if(isset($__without_db)=="true")
{
	$DATABASE_AUTO_OPEN = false;
}

if($DATABASE_AUTO_OPEN)
{
	global $DB_REDOLOG_FILE;
	import("db");
	if(isset($DEFAULT_DB_CONFIG))
	{
		$$DEFAULT_DB_OBJECT = new Connection($DEFAULT_DB_CONFIG);
		//$$DEFAULT_DB_CONFIG = array();
		$$DEFAULT_DB_OBJECT->setRedoLog($DB_REDOLOG_FILE);
	}
}


?>