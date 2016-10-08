<?php
require_once("$FREAMWORK_PATH/lib/$libVersion/bin/lib/include.php");

$AUTH = new ConsoleAuth();



if(isset($AUTH_ENABLE))
{
	if($AUTH_ENABLE==true)
	{
		/*
		if(isset($argv[2]))
		{
			$a = $argv[2];
		}
		else
		{
			$a="";
		}
		*/
		$a="";
		if(isset($__auth))
		{
			$a=$__auth;
		}
		/*
		$loginOption = getopt('l:');
		if(isset($loginOption['l']))
		{
			$a = $loginOption['l'];
		}
		else
		{
			$a="";
		}
		*/
		
		if($AUTH->login($a)==false)
		{
			$CS->showError("Exit without login");
			exit(0);
		
		}
		
	}
}

global $_CONSOLE;
$_CONSOLE = new Console();


if(isset($__cmd))
{
}
else
{
	$__cmd="";
	$CS->showOK("Welcome ".$AUTH->userName);
}
$_CONSOLE->run($__cmd);
if(isset($db))
{
	$db->close(false);
}
?>