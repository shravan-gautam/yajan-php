<?php
class alter_registration
{
	function __construct($cmd)
	{
		global $CONFIGURATION,$CS,$AUTH;
/*
		if(!$AUTH->isMemberOff("dba"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
			
		if($cmd[2]=="add")
		{
			$this->add($cmd);
		}
		else if($cmd[2]=="modify")
		{
			$this->modify($cmd);
		}
		else if($cmd[2]=="remove")
		{
			$this->remove($cmd);
		}
		*/
		$CS->showError("alter registration is Depreciated. use alter connection command for this process.");
	}
	function add($cmd)
	{
		global $CONFIGURATION,$DB_REG,$CS;
		$CONFIGURATION->load();
		//print_r($student);
		if($cmd[3]=="db")
		{
			if($cmd[4]=="from" )
			{
				if($cmd[5]=="config")
				{
					$dbParm = $cmd[6];
					global $$dbParm;
					$parm = $$dbParm;
					$DB_REG->addConnection($parm['name']);
					$DB_REG->addDriver($parm);
					$DB_REG->save();
					$CS->showOk("Database ".$parm['name']." registration complete");
				}
			}
			else if($cmd[4]=="new")
			{
				$CS->showInfo("Enter connection name");
				$name = $CS->read();
				$CS->showInfo("Enter driver no [MYSQL/MYSQLI/MARIADB/ORACLE/CLOUDDB] ");
				$driver = $CS->read();
				$driver = strtolower($driver);
				$CS->showInfo("Database name ");
				$dbName = $CS->read();				
				$CS->showInfo("Database user name ");
				$dbUser = $CS->read();
				$CS->showInfo("Database password ");
				$dbPass = $CS->read();
				$CS->showInfo("Database server ");
				$dbServer = $CS->read();
				$CS->showInfo("Database port ");
				$dbPort = $CS->read();
				$CS->showInfo("Database mode [R/W/RW] ");
				$dbMode = $CS->read();
				if($name=="")
				{
					$CS->showError("Connection name is required");
					return;
				}
				if($driver=="")
				{
					$CS->showError("Connection driver is required");
					return;
				}
				if($dbUser=="")
				{
					$CS->showError("Database user name is required");
					return;
				}
				if($dbPass=="")
				{
					$CS->showError("Database password is required");
					return;
				}
				if($dbName=="")
				{
					$CS->showError("Database name is required");
					return;
				}
				if($dbPort=="")
				{
					$CS->showError("Database port is required");
					return;
				}
				if($dbServer=="")
				{
					$CS->showError("Database server is required");
					return;
				}

				$gurukulam = array();
				$gurukulam['name']=$name;
				$gurukulam['driver']=$driver;
				$gurukulam['username']=$dbUser;
				$gurukulam['password']=$dbPass;
				$gurukulam['database']=$dbName;
				$gurukulam['server']=$dbServer;
				$gurukulam['port']=$dbPort;
				$gurukulam['dbmode']=$dbMode;
				$DB_REG->addConnection($name);
				$DB_REG->addDriver($gurukulam);
				$DB_REG->save();
				$CS->showOk("Database ".$gurukulam['name']." registration complete");
			}
				//print_r($$dbParm);

		}
	}
	function modify($cmd)
	{
		global $CONFIGURATION,$DB_REG,$CS;
		if($cmd[3]=="db")
		{
			if(isset($cmd[6]) && $cmd[5]=="set")
			{
				$dbParm = $cmd[4];
				$p = $cmd[6];
				$p=explode("=",$p);
				//global $$dbParm;
				//$parm = $$dbParm;
				//$DB_REG->addConnection($parm['name']);
				//$DB_REG->addDriver($parm);
				$DB_REG->changeProperty($dbParm,$p[0],$p[1]);
				$DB_REG->save();
				$CS->showOk("Database ".$dbParm."/".$p[0]." modification complete");
				//print_r($$dbParm);
			}
		}
	}
	function remove()
	{
		global $CONFIGURATION,$DB_REG,$CS;
		//$CS->showErorr("Can not possible to remove database registration");
		
		if($cmd[3]=="db")
		{
			if(isset($cmd[4]))
			{
				$dbParm = $cmd[4];
				global $$dbParm;
				$parm = $$dbParm;
				$DB_REG->removeConnection($parm['name']);
				$DB_REG->save();
				$CS->showOk("Database ".$parm['name']." registration complete");
				//print_r($$dbParm);
			}
		}
		
	}
}
?>