<?php

require_once("$FREAMWORK_PATH/lib/$libVersion/bin/lib/include.php");
require_once("$FREAMWORK_PATH/lib/$libVersion/bin/repository/class.repository.php");

$AUTH = new ConsoleAuth();



if(isset($AUTH_ENABLE))
{
	if($AUTH_ENABLE==true)
	{
		$a="";
		if(isset($__auth))
		{
			$a=$__auth;
		}
		if($AUTH->login($a)==false)
		{
			$CS->showError("Exit without login");
			exit(0);		
		}
		
	}
}

global $_CONSOLE;
$_CONSOLE = new Console();

$microVersion = $INFO->getInfo("UPDATE_REVIEW_VERSION");
$confVersion = $CONFIGURATION->getVersionInfo("configPatchVersion");
if(isset($__cmd))
{
}
else
{
	$__cmd="";
	echo "\n";
	$CS->cout("Welcome ".$AUTH->userName." on instance ".$CONFIGURATION->instanceName."[".$CONFIGURATION->instanceId."] from $YAJAN_DATA\n");
	$CS->showOK("Version $libVersion, Core $microVersion, Configuration $confVersion.");
	$CS->showOK("With support of SQL, Form+, YCM, YRM Modules, EMService, MVC Structure");

	$rep = new YajanRepository();
	/*
	if($rep->info->getObject("instanceId")=="")
	{
		$CS->showError("Unregisterd instance ".$CONFIGURATION->instanceName."[".$CONFIGURATION->instanceId."]");
	}
	else
	{
		$CS->showOK("Registerd instance ".$CONFIGURATION->instanceName."[".$CONFIGURATION->instanceId."]");
	}
	*/
	echo "\n";
}
$_CONSOLE->run($__cmd);
if(isset($db))
{
	$db->close(false);
}
?>