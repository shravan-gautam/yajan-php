<?php
class restore
{
	function __construct($cmd)
	{
		global $AUTH,$CS;
		if($cmd[1]=="db_snap")
		{
			if(!$AUTH->isMemberOff("operator") && !$AUTH->isMemberOff("dba"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->db_snap($cmd);
		}
	}
	function db_snap($cmd)
	{
		global $CS,$DB_REG,$CONFIGURATION,$YAJAN_DATA,$AUTH;
		if(!$AUTH->isMemberOff("operator") && !$AUTH->isMemberOff("dba"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}			
		
		if(count($cmd)<4)
		{
			$CS->showError("insufficient parameter");
			return;
		}
		$from = $cmd[3];
		$to = $cmd[5];
		if($from=="")
		{
			$CS->showError("Source database id required");
			return;
		}
		if($to=="")
		{
			$CS->showError("Distination database id required");
			return;
		}
		$index = $CONFIGURATION->data['registration']['index'];


		$sp = $index[$from]['parm'];
		$dp = $index[$to]['parm'];
		$CS->showOK("Restoring database from '".$sp['name']."' to '".$dp['name']."'");
		$spName = $sp['name'];
		$path = "$YAJAN_DATA/backup/".$spName;
		$bFile = "$path/$spName.backup";
		$dx = new DataBox("info");
		$dx->fromFile($bFile);
		$driver = $sp['driver'];
		if(($sp['driver']=="mysql" || $sp['driver']=="mysqli") && ($dp['driver']=="mysql" || $dp['driver']=="mysqli"))
		{
			$sqlFile = "$path/".$dx->getObject("sqlFile");
			$server = $dp['server'];
			$port = $dp['port'];
			$username = $dp['username'];
			$password = $dp['password'];
			$database = $dp['database'];
			
			$cmd = "mysql -h $server -P $port -u$username -p$password $database < $sqlFile";
			exec($cmd,$out);
			if(count($out)>0)
			{
				print_r($out);
			}
			$CS->showOK("Restore complete");
		}
		
	}
}

?>