<?php
require_once("parm.php");
if(isset($_SERVER["HTTP_X_REAL_IP"]))
{
$_SERVER["REMOTE_ADDR"]=$_SERVER["HTTP_X_REAL_IP"];
}
if(isset($_SERVER["HTTP_X_YAJANCLIENTTYPE"]))
{
	if($_SERVER["HTTP_X_YAJANCLIENTTYPE"]=="MOBILE")
	{
		$_SERVER["HTTP_USER_AGENT"]="Mozilla/5.0 (Linux; Android 5.0; Lenovo K50a40 Build/LRX21M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36";
	}
}
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
				global $ixx;
				
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
$CALL_SEQUANCE_TIME=time().uniqid();
//require_once("$_PWD/yajan/etc/yajan.conf.php");

global $SCRIPT_PATH,$URL_QUERY,$REQUEST_ID,$FREAMWORK_PATH,$YAJAN_DATA,$LOG_PATH;
$FILENAME = basename($_SERVER['SCRIPT_FILENAME']);
$SCRIPT_PATH = str_replace("/$FILENAME","",$_SERVER['SCRIPT_NAME']);

$REQUEST_ID = uniqid(true);
$EXEC_MODE="WEB";
if(!isset($LOG_PATH))
{
	$LOG_PATH="$YAJAN_DATA/log";
	if(!is_dir($LOG_PATH))
	{
		mkdir($LOG_PATH);
	}
}

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
$_SERVER["EXEC_MODE"]=$EXEC_MODE;
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




if(!isset($DEFAULT_IMPORT_PACKEG))
{
	$DEFAULT_IMPORT_PACKEG="";
}
$_0xp2323 = explode(",",$DEFAULT_IMPORT_PACKEG);
global $NO_CACHE,$CACHE_ID,$CACHE_DATA_SOURCE,$OUTPUT_CACHE,$_CACHE_MANAGER;

if(isset($NO_CACHE) && $NO_CACHE==true)
{
	$OUTPUT_CACHE=false;
}
if(isset($OUTPUT_CACHE) && $OUTPUT_CACHE==true)
{
	import("io");
	import("data");
	if(!isset($CACHE_ID))
	{
		$CACHE_ID=md5("http://".$_SERVER["HTTP_HOST"]."".$_SERVER["REQUEST_URI"]);
		$CACHE_DATA_SOURCE = "http://".$_SERVER["HTTP_HOST"]."".$_SERVER["REQUEST_URI"];
	}
	$_CACHE_MANAGER = new DataCache($CACHE_ID);
	if(!isset($_SERVER["HTTP_X_YAJANDATACACHE"]) || $_SERVER["HTTP_X_YAJANDATACACHE"]!="no" )
	{
		if($_CACHE_MANAGER->isAvilable() && !$_CACHE_MANAGER->isOutdated())
		{
			$_CACHE_MANAGER->echoHeader();
			echo $_CACHE_MANAGER->read();
			exit(0);
		}
	}
}


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

global $BOOT_LOG_FILE,$CALL_SEQUANCE_TIME,$_GET,$_POST,$BOOT_LOG;
		
if(isset($BOOT_LOG) && $BOOT_LOG=true)
{
	
	$BOOT_LOG_FILE = "$LOG_PATH/boot.log";
//	chmod($BOOT_LOG_FILE,0777);
	$bootLog = new File($BOOT_LOG_FILE);
	$m = "_".$_SERVER["REQUEST_METHOD"];
	$l = strlen(serialize($$m));
	$bootLog->append(date("d/m/Y h:i:s A")."|".$CALL_SEQUANCE_TIME."|".$_SERVER["REMOTE_ADDR"]."|".$_SERVER["REQUEST_METHOD"]."|".$_SERVER["REQUEST_URI"]."|".$l."\n");
	if($EXEC_MODE=="CLI")
	{
		chmod($BOOT_LOG_FILE,0777);
	}
}
?>
