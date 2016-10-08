<?php
class Registration
{
	public $index;
	public $connection;
	var $registrationFile;
	function Registration()
	{
		global $YAJAN_DATA,$CONFIGURATION;
		/*
		$this->registrationFile="$YAJAN_DATA/db/registration";
		$this->dx = new DataBox("registration");
		
		if($this->dx->fromFile($this->registrationFile))
		{
			$this->index = $this->dx->getObject("index");
			$this->connection = $this->dx->getObject("connection");
		}
		else
		{
			$this->index = array();
			$this->connection = array();
		}
		*/
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
		$r->addColumns("id","driver","name","username","password","database","server","port","dbmode");
		for($i=0;$i<count($this->index);$i++)
		{
			
			if($PASSWORD_MASK!="")
			{
				$test[$i]['parm']['password']="$PASSWORD_MASK";
			}
			$obj = $test[$i]['parm'];
			$r->add($test[$i]["id"],$obj["driver"],$obj["name"],$obj["username"],$obj["password"],$obj["database"],$obj["server"],$obj["port"],$obj["dbmode"]);
		}	
		return $r;
	}
	function showConnectionIndex()
	{
		global $DEFAULT_DB_CONFIG,$PASSWORD_MASK;
		$r=$this->dbIndexToRecordset();
		$r->showCLITable();
	}
	function removeConnection($name)
	{
		
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
}
global $DB_REG;
$DB_REG = new Registration();

?>