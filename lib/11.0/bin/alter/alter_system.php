<?php
class alter_system
{
	function __construct($cmd)
	{
		global $AUTH,$CS;

		
		if($cmd[2]=="scnlog")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			$this->scnlog_switch($cmd);
		}
		else if($cmd[2]=="sync_mode")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->sync_mode($cmd);
		}
		else if($cmd[2]=="default")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->defaultSystem($cmd);
		}
		else if($cmd[2]=="add")
		{
			$this->add($cmd);
		}
		else if($cmd[2]=="change")
		{
			
			$this->change($cmd);
		}
		else if($cmd[2]=="remove")
		{
			$this->remove($cmd);
		}
		else if($cmd[2]=="install")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->install($cmd);
		}
		else if($cmd[2]=="set")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->set($cmd);
		}
	}
	function set($cmd)
	{
		global $CONFIGURATION,$CS;
		$c = $cmd[3];
		$c=explode("=",$c);
		if($c[0]=="instance_id" && isset($c[1]))
		{
			$CONFIGURATION->instanceId= $c[1];
			$CONFIGURATION->write();
			$CS->showOK("System instance ID has change successfully");
		}
		else if($c[0]=="instance_name" && isset($c[1]))
		{
			$CONFIGURATION->instanceName= $c[1];
			$CONFIGURATION->write();
			$CS->showOK("System instance NAME has change successfully");
		}
			

	}
	function install($cmd)
	{
		global $libVersion,$CS,$FREAMWORK_PATH,$YAJAN_SERVER,$_CONSOLE,$CONFIGURATION;		
		$info = new YajanInfo();
		/*
		if($cmd[3]=="update")
		{
			if(!is_dir("$FREAMWORK_PATH/lib/$libVersion/data"))
			{
				mkdir("$FREAMWORK_PATH/lib/$libVersion/data");
			}
			import("net");
			$CS->showInfo("Connecting to server...");
			$info = new YajanInfo();
			$cv = $info->getInfo("update_review_version");
			$cnfV = $CONFIGURATION->getVersionInfo("configPatchVersion");
			$CS->showInfo("Current Update Review Version is ".$info->getInfo("update_review_version"));
			$avReview= file_get_contents($YAJAN_SERVER."/release.php?version=$libVersion&get=latestVersion");
			$avReview = str_replace("\n","",$avReview);
			$CS->showInfo("Available Update Review Version is ".$avReview);
			$data = file_get_contents($YAJAN_SERVER."/release.php?get=dirlist&version=$libVersion");
			$data = explode("\n",$data);
			$count = 0;
			for($i=0;$i<count($data);$i++)
			{
					$dirname = $data[$i];
					$dirname = str_replace("./","$FREAMWORK_PATH/",$dirname);
					if($dirname!="")
					{
							if(!is_dir($dirname))
							{
									mkdir($dirname);
									echo "Creating directory $dirname done.\n";
									$count++;
							}
					}
			}

			$data = file_get_contents($YAJAN_SERVER."/release.php?version=$libVersion");
			$data = explode("\n",$data);
			
			for($i=0;$i<count($data);$i++)
			{
				$mdHash = substr($data[$i],0,32);
				$filename = trim(substr($data[$i],33,strlen($data[$i])));
				if($filename!="")
				{
					$filename = str_replace("./","$FREAMWORK_PATH/",$filename);
					$fname = basename($filename);
					$dname = str_replace("/$fname","",$filename);
					if(file_exists("$filename"))
					{
						
						$md = md5_file("$filename");
						//echo "$md.$filename\n";
						if($md!=$mdHash && $filename != "$FREAMWORK_PATH/lib/$libVersion/data/info.conf")
						{
							$filedata = file_get_contents($YAJAN_SERVER."/release.php?hash=$mdHash&version=$libVersion");
							if($mdHash!="cd5568dd73758946b8fb74fdd3c7cda9")
							{
								file_put_contents("$filename",$filedata);
								$CS->showInfo("Update $filename");
							}
							$count++;
						}
					}
					else
					{
						if(!is_dir($dname))
						{
							mkdir($dname);
						}
						$filedata = file_get_contents($YAJAN_SERVER."/release.php?hash=$mdHash&version=$libVersion");
						file_put_contents("$filename",$filedata);
						$CS->showInfo("Create $filename");
						
						$count++;
					}
				}
			}
			$data = file_get_contents($YAJAN_SERVER."/release.php?version=$libVersion&get=patchlist&cv=$cnfV");
			$data = explode("\n",$data);
			for($i=0;$i<count($data);$i++)
			{
				$data[$i] = str_replace("\n","",$data[$i]);
				if($data[$i]!="")
				{
					$pv = $data[$i];
					$pl = file_get_contents($YAJAN_SERVER."/release.php?version=$libVersion&get=patch&pv=$pv");
					$desc = file_get_contents($YAJAN_SERVER."/release.php?version=$libVersion&get=releaseDesc&pv=$pv");
					if($desc!="")
					{
						$desc = base64_decode($desc);
						$CS->showWarnning("$pv");
						echo "\n$desc\n";
					}
					else
					{
						$CS->showWarnning("$pv\n");
					}
					$pl = base64_decode($pl);
					$pl = explode("\n",$pl);
					for($j=0;$j<count($pl);$j++)
					{
						$p = str_replace("\n","",$pl[$j]);
						if($p!="")
						{
							$_CONSOLE->run($p);
							$count++;
						}
					}
					$info->addPatch($pv);
				}
			}
			if($count==0)
			{
				$CS->showInfo("No update found.");
			}
			else
			{
				$CS->showInfo("$count update found.");
			}
			$CONFIGURATION->setVersionInfo("configPatchVersion",$avReview);
			$info->addInfo("update_review_version",$avReview);
			$info->addInfo("LIB_VERSION",$libVersion);
			$CONFIGURATION->write();
			$CS->showOk("Checking new update complete.");
			
		}
		else */
		if($cmd[3]=="plugin")
		{
			global $CS;
			if(count($cmd)<6)
			{
				$CS->showError("insufficiant parameter.");
				$CS->showInfo("alter system install plugin <name> <version>");
			}
			$CS->showInfo("Pluggin installtation start...");
			$pl = new Plugins();
			$pl->install($cmd[4],$cmd[5],$cmd);
		}
	}
	function remove($cmd)
	{
		global $AUTH,$CS;
		if($cmd[3]=="user")
		{
			if(!$AUTH->isMemberOff("security"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			

			$user = $cmd[4];
			if(isset($cmd[5]))
			{
				if($cmd[5]=="from")
				{
					$group = $cmd[6];
					if($AUTH->removeUserGroup($user,$group))
					{
						$CS->showOk("$user user remove from group $group");
					}
					else
					{
						$CS->showError("Error in user removing in group $group");
					}
				}
			}
			else 
			{
				if($AUTH->removeUser($user))
				{
					$CS->showOk("$user user remove");
				}
				else
				{
					$CS->showError("Error in user remove");
				}
			}
		}
		
	}
	function change($cmd)
	{
		global $AUTH,$CS;
		if($cmd[3]=="user")
		{
			if(!$AUTH->isMemberOff("security"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
			
			$user = $cmd[4];
			if($cmd[5]=="password")
			{
				
				$pass = "";
				if(isset($cmd[6]))
				{
					$pass = $cmd[6];
				}
				
				if($AUTH->changeUserPassword($user,$pass))
				{
					
					$CS->showOk("$user user's password has been changed successfuly");
				}
				else
				{
					$CS->showError("Error in user creation");
				}
			}
		}
	}
	function add($cmd)
	{
		global $AUTH,$CS;
		if($cmd[3]=="user")
		{
			
			if(!$AUTH->isMemberOff("security"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}

			$user = $cmd[4];
			
			if($cmd[5]=="in")
			{
				if(isset($cmd[6]))
				{
					$group = $cmd[6];
					if($AUTH->addUserGroup($user,$group))
					{
						$CS->showOk("$user user added in group $group");
					}
					else
					{
						$CS->showError("Error in user adding in group $group");
						
					}
				}
				else
				{
					$CS->showError("Invalid group name");
				}
				
			}
			else if($cmd[5]=="password")
			{
				$pass = "";
				if(isset($cmd[6]))
				{
					$pass = $cmd[6];
				}
				if($AUTH->addUser($user,$pass))
				{
					$CS->showOk("$user user added ");
				}
				else
				{
					$CS->showError("Error in user creation");
					
				}
			}
			
			
		}
		else if($cmd[3]=="group")
		{
			if(!$AUTH->isMemberOff("security"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
			
			$group = $cmd[4];
			$AUTH->addGroup($group);
			$CS->showOk("group add successfully.");
		}
	}
	function defaultSystem($cmd)
	{
		global $CS,$CONFIGURATION,$AUTH;
		if(!$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		
		if($cmd[3]=="config")
		{
			$CS->showWarnning('WARNNING!!!  you change to yajan default boot configuration file. [Y/N]');
			$o = $CS->read();
			if($o=="Y")
			{
				if($cmd[4]=="reset")
				{
					$CONFIGURATION->restoreDefaultConfig();
					$CS->showOK("Restore default boot configuration file");
				}
				else
				{
					if($CONFIGURATION->changeDefaultConfig($cmd[4]))
					{
						$CS->showOK("Default boot configuration file change to ".$cmd[4]);
					}
					else
					{
						$CS->showOK("Boot configuration ".$cmd[4]." is not exist ");
					}
				}
				
			}
			else
			{
				
			}
		}
	}
	function scnlog_switch($cmd)
	{
		global $CS,$YAJAN_DATA,$AUTH;
		
		if($cmd[3]=="switch")
		{
			$current  = file_get_contents("$YAJAN_DATA/db/current");
			$dt = date('d.m.Y.H.i.s');
			exec("mv $YAJAN_DATA/scn/$current.scn $YAJAN_DATA/scn/$current.$dt.scn");
			$CS->showOK('System altered.');
		}
		else if(isset($cmd[4]) && $cmd[4]=="switch")
		{
			$current  = $cmd[3];
			$dt = date('d.m.Y.H.i.s');
			exec("mv $YAJAN_DATA/scn/$current.scn $YAJAN_DATA/scn/$current.$dt.scn");
			$CS->showOK('System altered.');
		}
		else if($cmd[3]=="nolog")
		{
			file_put_contents("$YAJAN_DATA/db/scnlogmode","nolog");
			$CS->showOK('System altered.');
		}
		else if($cmd[3]=="log")
		{
			file_put_contents("$YAJAN_DATA/db/scnlogmode","log");
			$CS->showOK('System altered.');
		}
	}
	function sync_mode($cmd)
	{
		global $CS;
		$val = $cmd[3];
		$pv = array("all_db","current");
		if(array_search($val,$pv)!==false)
		{
			file_put_contents("var/db/mode",$val);
			$CS->showOK('System altered.');
		}
		else
		{
			$CS->showError("Invalid status");
		}
	}
}
?>