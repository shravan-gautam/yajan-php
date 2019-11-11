<?php
import("cli");
$YOGINIDIR = "var";
$CS = new CLI();
$GLOBAL = array();
$INFO = new YajanInfo();

$GLOABL['prompt']="Yajan";
class Console
{
	var $cmd="";
	var $objectType="";
	var $objectName="";
	var $objectPath = "";
	var $object = null;
	var $parentObject = null;
	function __construct()
	{
		global $GLOABL,$YOGINIDIR;
		if(!is_dir($YOGINIDIR))
		{
			mkdir($YOGINIDIR);
		}
	}
	
	function run($cmd="")
	{
		global $LIB_PATH,$GLOABL,$CS;
		
		$noPrompt = true;
		if($cmd!="")
		{
			$noPrompt=false;
		}
		$oldCmd = "";
		$cmdText = "";
		while($cmd!="exit")
		{
			if($cmd!="")
			{
				if($cmd=="/")
				{
					$cmd = $oldCmd;
					$cmdText = $oldCmd;
					$CS->showOK("$oldCmd");
				}
				
				$cmd = explode(" ",$cmd);
				$c = $cmd[0];
				
				if(file_exists("$LIB_PATH/bin/$c.php"))
				{
					require_once "$LIB_PATH/bin/$c.php";
					$obj = new $c($cmd);
				}
				else
				{
					$CS->showError("$c is not a command");
				}
			}
			if($noPrompt)
			{
				$oldCmd = $cmdText;
				echo $GLOABL['prompt'].":> ";
				$cmdText = $CS->read();
				$cmdText = rtrim($cmdText,";");
				if($cmdText=="/")
				{
					$cmdText = $cmdText;
				}
				$cmd = $cmdText;
				
			}
			else
			{
				return;
			}
		}
	}
}

?>