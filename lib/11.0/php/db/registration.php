<?php
class Registration
{
	public $index;
	public $connection;
	var $cmdOutput;
	var $registrationFile;
	function __construct()
	{
		global $YAJAN_DATA,$CONFIGURATION;
		$this->index = array();
		$this->connection = array();
		if(isset($CONFIGURATION->data['registration']))
		{
		$this->index = $CONFIGURATION->data['registration']['index'];
		$this->connection = $CONFIGURATION->data['registration']['connection'];
		}
	}
	function changeProperty($name,$key,$val)
	{
		$id = $this->getRegistrationId($name);
		$parm = $this->index[$id]['parm'];
		//print_r($parm);
		if(strtoupper($name)!="NAME")
		{
			$parm[$key]=$val;
			$this->index[$id]['parm']=$parm;
			//print_r($parm);
			return true;
		}
		else
		{
			return false;
		}
	}
	function isRegisterdId($id)
	{
		if($this->connection==null)
		{
			return -1;
		}
		if(isset($this->index[$id]))
		{
			return $id;
		}
		return -1;
	}
	function isRegisterd($name)
	{
		if($this->connection==null)
		{
			return -1;
		}
		$i = array_search($name,$this->connection);
		if($i===false)
		{
			return -1;
		}
		return $i;
	}
	function addConnection($name)
	{
		$id = $this->isRegisterd($name);
		if($id==-1)
		{
			$id = count($this->connection);
			$this->connection[$id]=$name;
		}
		return $id;
	}
	function showCurrentConfig()
	{
		global $DEFAULT_DB_CONFIG;
		$id = $this->getRegistrationId($DEFAULT_DB_CONFIG);
		$r=$this->dbIndexToRecordset();
		$r1= $r->extract("id",$id);
		$r1->showCLITable();
		//print_r($test);
	}
	function showConnectionList()
	{
		//print_r($this->connection);
		$r=$this->dbIndexToRecordset();
		$r->showCLITable();
	}
	function dbIndexToRecordset()
	{
		global $PASSWORD_MASK;
		$test = $this->index;
		$r= new Recordset();
		$r->addColumns("id","driver","name","username","password","database","server","port","dbmode","pwmask");
		for($i=0;$i<count($this->index);$i++)
		{
			

			$test[$i]['parm']['password']="*****";

			$obj = $test[$i]['parm'];
			$r->add($test[$i]["id"],$obj["driver"],$obj["name"],$obj["username"],$obj["password"],$obj["database"],$obj["server"],$obj["port"],$obj["dbmode"],$obj["pwmask"]);
		}	
		return $r;
	}
	function showConnectionProperty($id)
	{
		global $PASSWORD_MASK;
		//echo $PASSWORD_MASK;
		if($this->index[$id]["parm"]["pwmask"]!="")
		{
			$password=$this->index[$id]["parm"]["pwmask"];
		}
		else if($PASSWORD_MASK!="")
		{
			$password=$PASSWORD_MASK;
		}
		else
		{
			$password=$this->index[$id]["parm"]["password"];
		}
		$r = new Recordset();
		$r->addColumns("property","Description","value");
		$r->add("ID","Connection ID",$this->index[$id]["id"]);
		$r->add("DRIVER","Connection driver",$this->index[$id]["parm"]["driver"]);
		$r->add("NAME","Connection name",$this->index[$id]["parm"]["name"]);
		$r->add("USERNAME","Database login user name",$this->index[$id]["parm"]["username"]);
		$r->add("PASSWORD","Database login user password",$password);
		$r->add("DATABASE","Database name",$this->index[$id]["parm"]["database"]);
		$r->add("SERVER","Database server name or IP",$this->index[$id]["parm"]["server"]);
		$r->add("PORT","Database service port number",$this->index[$id]["parm"]["port"]);
		$r->add("DBMODE","Database opration mode",$this->index[$id]["parm"]["dbmode"]);
		$r->add("PWMASK","Password mask",$this->index[$id]["parm"]["pwmask"]);
		$r->showCLITable();
	}
	function showConnectionIndex()
	{
		global $DEFAULT_DB_CONFIG,$PASSWORD_MASK;
		$r=$this->dbIndexToRecordset();
		$r->showCLITable();
	}
	function getConnectionId($name)
	{

		for($i=0;$i<count($this->connection);$i++)
		{
			if($this->connection[$i]==$name)
			{
				return $i;
			}
		}
		return -1;
	}
	function removeAllConnection()
	{
		$this->index=array();
		$this->connection=array();

	}
	function removeConnection($name)
	{
		global $CS;
		
		if($this->connection==null)
		{
			$CS->showError("DB Connection empty");
			return -1;
		}
		
		$i = $this->getConnectionId($name);
		
		
		if($i===false)
		{
			echo $name;
			$CS->showError("Connection index not found");
			return -1;
		}
		$tmp  = array();
		for($ix = 0;$ix < count($this->index);$ix++)
		{
			if($ix!=$i)
			{
				$tmp[count($tmp)]=$this->index[$ix];
			}
		}
		$this->index=$tmp;
	}
	function getRegistrationId($name)
	{
		return $this->isRegisterd($name);
	}
	function getProperty($id)
	{
		return $this->index[$id]['parm'];
	}

	function addDriver($parm,$connectionId = -1)
	{
		$id = $this->isRegisterd($parm['name']);
		if($id==-1)
		{
			$id = count($this->connection);
		}
		
		if(!isset($parm['name']))
		{
			echo "Database registration failed";
			exit(0);
		}
		else
		{

			$name = $parm['name'];
			$info  =array();
			$info['parm']=$parm;
			$info['id']=$id;	
			$info['name']=$name;
			$info['connectionId']=$connectionId;
			$this->index[$id]=$info;
		}
	}
	
	function save()
	{
		global $CONFIGURATION;
		$CONFIGURATION->data['registration']=array("connection"=>$this->connection,"index"=>$this->index);
		$CONFIGURATION->write();
		//$this->dx->add("index",$this->index);
		//$this->dx->add("connection",$this->connection);
		//print_r($this->dx);
		//$this->dx->toFile($this->registrationFile);
	}
	function importSql($dbid,$sqlFilename)
	{
		global $CONFIGURATION,$CS;
		if($this->isRegisterdId($dbid)<0)
		{
			$CS->showError("Invalid or unregisterd database id.");
			return;
		}
		$this->cmdOutput="";
		$parm =  $this->index[$dbid]['parm'];
		
		if($parm['driver']=="mysql" || $parm['driver']=="mysqli")
		{
			
			$server = $parm['server'];
			$port = $parm['port'];
			$username = $parm['username'];
			$password = $parm['password'];
			$database = $parm['database'];
			
			$cmd = "exec 2>&1; mysql -h $server -P $port -u$username -p$password $database < $sqlFilename";
			
			exec($cmd,$this->cmdOutput);
			
			return true;
		}		
		$this->message="Database driver is not MYSQL comfortable.";
		return false;
	}
}
global $DB_REG;
$DB_REG = new Registration();

?>