<?php
class alter_connection
{
	function __construct($cmd)
	{
		global $CONFIGURATION,$CS,$AUTH;

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
		else if($cmd[2]=="drop")
		{
			$this->remove($cmd);
		}
	}
	function add($cmd)
	{
		global $CONFIGURATION,$DB_REG,$CS;
		$CONFIGURATION->load();
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
					$CS->showOk("Database connection ".$parm['name']." registration complete");
				}
			}
			else if($cmd[4]=="new")
			{
				$CS->showInfo("Enter connection name");
				$name = $CS->read();
				$CS->showInfo("Enter driver no [MYSQL/MYSQLI/MARIADB/ORACLE/POSTGRE/CLOUDDB] ");
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
				$gurukulam['pwmask']="";
				$DB_REG->addConnection($name);
				$DB_REG->addDriver($gurukulam);
				$DB_REG->save();
				$CS->showOk("Database connection ".$gurukulam['name']." registration complete");
			}
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
				$DB_REG->changeProperty($dbParm,$p[0],$p[1]);
				$DB_REG->save();
				$CS->showOk("Database connection ".$dbParm."/".$p[0]." modification complete");
			}
		}
	}
	function remove($cmd)
	{
		global $CONFIGURATION,$DB_REG,$CS;
		if($cmd[3]=="db")
		{
			if(isset($cmd[4]))
			{
				$dbParm = $cmd[4];
				if($dbParm=="all")
				{
					$DB_REG->removeAllConnection();
					$DB_REG->save();
					$CS->showOk("All Database connection removed");
				}
				else
				{
					$DB_REG->removeConnection($dbParm);
					$CS->showOk("Database connection ".$parm['name']." removed complete");
				}
			}
		}
		
	}
}
?>