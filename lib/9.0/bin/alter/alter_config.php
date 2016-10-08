<?php
class alter_config
{
	function alter_config($cmd)
	{
		global $CONFIGURATION,$CS,$AUTH;

		if(!$AUTH->isMemberOff("admin") && $AUTH->userName!="DEFAULT")
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		if($cmd[2]=="from")
		{
			if($cmd[3]=="file")
			{
				$this->loadConfigFile($cmd);
			}
		}
		else if($cmd[2]=="reset")
		{
			$this->reset($cmd);
		}
		else if($cmd[2]=="set")
		{
			$this->alterConfig($cmd);
		}
		else if($cmd[2]=="remove")
		{
			$this->removeConfig($cmd);
		}
		else if($cmd[2]=="make")
		{
			$this->make($cmd);
		}
		else
		{
			$CS->showError('invalid configuration command.');
		}
		
	}
	function reset($cmd)
	{
		global $YAJAN_DATA,$CONFIGURATION,$CS,$AUTH;
		exec("mkdir -p $YAJAN_DATA/tmp");
		exec("chmod 777 $YAJAN_DATA/tmp");
		exec("mkdir -p $YAJAN_DATA/log");
		exec("chmod 777 $YAJAN_DATA/log");
		exec("mkdir -p $YAJAN_DATA/db");
		exec("mkdir -p $YAJAN_DATA/scn");
		exec("chmod 777 $YAJAN_DATA/scn");
		exec("mkdir -p $YAJAN_DATA/backup");



		if(!file_exists("$YAJAN_DATA/db/current"))
		{
				file_put_contents("$YAJAN_DATA/db/current","0");
		}
		if(!file_exists("$YAJAN_DATA/db/mode"))
		{
				file_put_contents("$YAJAN_DATA/db/mode","all_db");
		}
		if(!file_exists("$YAJAN_DATA/db/scnlogmode"))
		{
				file_put_contents("$YAJAN_DATA/db/scnlogmode",'nolog');
		}
		$CONFIGURATION->clearConfig();
		$CONFIGURATION->write();
		$AUTH->addUser("default","default");
		$AUTH->addGroup("admin");
		$AUTH->addGroup("security");
		$AUTH->addGroup("dba");
		$AUTH->addGroup("developer");
		$AUTH->addGroup("operator");
		$AUTH->addUserGroup("default","admin");
		$AUTH->addUserGroup("default","security");
		$AUTH->addUserGroup("default","dba");
		$AUTH->addUserGroup("default","developer");
		$AUTH->addUserGroup("default","operator");
		$CS->showError('Config reset comlite');
	}
	function make($cmd)
	{
		global $CONFIGURATION,$CS,$YAJAN_DATA;
		if($cmd[3]=="duplicate")
		{
			if($cmd[4]=="as")
			{
				$filename = $cmd[5];
				copy($CONFIGURATION->getConfigFile(),$YAJAN_DATA."/config/$filename.conf");
				$CS->showOk("Duplicate copy of configuration created.");
			}
		}
		else if($cmd[3]=="php")
		{
			$phpFile = $cmd[4];
			if($cmd[5]=="of")
			{
				$name = $cmd[6];
				$CONFIGURATION->exportPhp($phpFile,$name);
				$CS->showOk("PHP Export complete.");
			}
		}
	}
	function removeConfig($cmd)
	{
		global $CONFIGURATION,$CS;
		$key = $cmd[3];
		$CONFIGURATION->removeKey($cmd[3]);
		$CONFIGURATION->write();
		$CS->showOK('Configuration altered.');
	}
	function loadConfigFile($cmd)
	{
		global $CONFIGURATION,$CS;
		$filename = $cmd[4];
		$safe=false;
		if(isset($cmd[5]))
		{
			if($cmd[5]=="safely")
			{
				$safe=true;
			}
		}
		if(file_exists($filename))
		{
			$fn = basename($filename);
			$data = file_get_contents($filename);
			$CONFIGURATION->set($fn,$data,"file",$safe);
			$CONFIGURATION->write();
			$CS->showOK('Configuration altered.');
		}

	}
function alterConfig($cmd)
	{
		global $CONFIGURATION,$CS;
		$str="";
		for($i=3;$i<count($cmd);$i++)
		{
			$str .= $cmd[$i]." ";
		}
		$str=trim($str);
		$c = explode("=",$str);
		
		$c[1]=urldecode($c[1]);
		$safe=false;
		if(isset($cmd[4]))
		{
			if($cmd[4]=="safely")
			{
				$safe=true;
			}
		}
		$CONFIGURATION->set($c[0],$c[1],"var",$safe);
		$CONFIGURATION->write();
		$CS->showOK('Configuration altered.');
	}
}
?>