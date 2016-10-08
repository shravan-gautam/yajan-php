<?php
class show
{
	function show($cmd)
	{
		global $CS,$YAJAN_DATA;
		$f = $cmd[1];
		if(method_exists($this,$f))
		{
			echo "\n";
			$this->$f($cmd);
			echo "\n";
		}
		else
		{
			$CS->showError("invalid command $f.");
		}
	}
	function db($cmd)
	{
		global $DB_REG,$YAJAN_DATA,$AUTH,$CS;
		if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("developer"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}

		if($cmd[2]=="default")
		{
			$DB_REG->showCurrentConfig();
		}
		else if($cmd[2]=="connection")
		{
			$DB_REG->showConnectionList();
			
		}
		else if($cmd[2]=="index")
		{
			$DB_REG->showConnectionIndex();
		}
		else if($cmd[2]=="sync_mode")
		{
			echo "Current synchronization mode is : ".file_get_contents("var/db/mode")."\n";
		}

	}
	
	function scn($cmd)
	{
		global $YAJAN_DATA,$CS,$AUTH;
		if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("developer"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		if(!isset($cmd[2]))
		{
			$cmd[2]="lastline";
		}
		if($cmd[2]=="current")
		{
			if(isset($cmd[4]))
			{
				$db = $cmd[4];
			}
			else
			{
				$db=0;
			}
			echo "Current sequence chain number is : ".(file_get_contents("$YAJAN_DATA/scn/$db.scncounter"))."\n";
		}
		else if($cmd[2]=="logmode")
		{
			echo "Current scn log mode is : ".file_get_contents("$YAJAN_DATA/db/scnlogmode")."\n";
		}
		else if($cmd[2]=="lastline")
		{
			$current = file_get_contents("$YAJAN_DATA/db/current");
			
			$n = 5;
			if(isset($cmd[3]))
			{
				$n=$cmd[3];
			}
			$db = "";
			if($n=="from")
			{
				$n=5;
				if(isset($cmd[4]))
				{
					$db = $cmd[4];
				}
				else
				{
					$db=0;
				}
			}
			else
			{
				if(isset($cmd[5]))
				{
					$db = $cmd[5];
				}
				else
				{
					$db=0;
				}
			}
			$filename = "$YAJAN_DATA/scn/$db.scn";
			if(file_exists($filename))
			{
				exec("tail -n $n $filename",$out);
				for($i=0;$i<count($out);$i++)
				{
					$line = $out[$i];
					
					$line = explode("::",$line);
					$line[3]=base64_decode($line[3]);
					print_r($line);
				}
			}
			else
			{
				$CS->showError("$db.scn file is not exist");
			}
		}
	}
	function config($cmd)
	{
		global $CONFIGURATION,$YAJAN_DATA,$AUTH;
		global $CS;

		if(!$AUTH->isMemberOff("developer")  && !$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}

		if(!isset($cmd[2]))
		{
			$cmd[2]="var";
		}
		if($cmd[2]=="all")
		{
			print_r($CONFIGURATION->data['config']);
		}
		else if($cmd[2]=="var")
		{
			ksort($CONFIGURATION->data['config']);

			$CS->showInfo("Boot configuration detail of ".$CONFIGURATION->getConfigName()."\n");
			foreach($CONFIGURATION->data['config'] as $k => $v)
			{
				if($v['type']=="var")
				{
					echo str_pad($k,"30"," ")."= ".str_pad(base64_decode($v["value"]),"50"," ")."\n";
				}
			}
			echo "\n\n";
		}
		else if($cmd[2]=="desc")
		{
			global $CS;
			$varName = $cmd[3];
			foreach($CONFIGURATION->data['config'] as $k => $v)
			{
				if($k==$varName)
				{
					$CS->showInfo("\nValue of $k\n\n");
					echo base64_decode($v['value']);
				}
			}
		}
	}
	function user()
	{
		global $AUTH,$PASSWORD_MASK,$AUTH,$CS;
		if(!$AUTH->isMemberOff("security"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}

		$test = $AUTH->data['user'];
		for($i=0;$i<count($test);$i++)
		{
			if($PASSWORD_MASK!="")
			{
				$test[$i][1]="$PASSWORD_MASK";
			}
		}
		print_r($test);
	}
	function group($cmd)
	{
		global $AUTH,$CS;
		if(!$AUTH->isMemberOff("security"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		
		if(!isset($cmd[2]))
		{
			print_r($AUTH->data['group']);
		}
		else
		{
			echo "\nUser ".$cmd[3]." is member of follwing groups.\n";
			$data = array();
			for($i=0;$i<count($AUTH->data['user_group']);$i++)
			{
				if($AUTH->data['user_group'][$i][0]==strtoupper($cmd[3]))
				{
					$data[count($data)]=$AUTH->data['user_group'][$i][1];
				}
			}
			print_r($data);
		}
	}
	function usermap()
	{
		global $AUTH,$CS;
		if(!$AUTH->isMemberOff("security"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		
		print_r($AUTH->data['user_group']);
	}	
	function info()
	{
		global $AUTH,$CS;
		if(!$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
		
		$info = new YajanInfo();
		$info->showInfo();
	}
	function patch()
	{
		global $AUTH,$CS;
		if(!$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
			
		$info = new YajanInfo();
		$info->showPatch();
	}
	function update()
	{
		global $AUTH,$CS;
		if(!$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
			
		$info = new YajanInfo();
		$info->showUpdate();
	}
}

?>