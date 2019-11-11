<?php
/*
class MySqliteDb extends SQLite3
{
	var $connect_errno;
    function __construct($filename)
    {
        $this->open($filename);
        $this->connect_errno=false;
    }
    function autocommit($v)
    {

    }
    function ping()
    {
    	return true;
    }
    function query($q)
    {
    	return $this->exec($q);
    }
}
*/
class SqliteDatabaseDriver extends DBDriver
{
	
	function __construct($parm,$id)
	{
		parent::__construct();
		global $DB_QUERY_TRACE_FILE,$DB_QUERY_TRACE_DUMP,$SHOW_DB_ERROR;
		$this->messageObject = new Messages("DatabaseAdaptor");
		$this->dbMode=$parm['dbmode'];
		$this->link=null;
		$this->commitMode=true;
		
		$this->start=0;
		$this->timecount=0;
		$this->parm = $parm;
		$sec = new Encryption("shravan");
		$this->parm['password']=$sec->encrypt($this->parm['password']);
		$this->queryCount=0;
		$this->queryErrorStatus=false;
		$this->showError = $SHOW_DB_ERROR;
		$this->dbId = $id;
		if(isset($parm['showError']))
		{
			$this->showError = $parm['showError'];
		}
		$dir = dirname($parm["database"]);
		if(!is_dir($dir))
		{
			exec("mkdir -p $dir");
		}

		$this->link = new SQLite3($parm["database"]);



		$this->autoExecute = true;
		if($this->commitMode==true)
		{
			//$this->link->autocommit(TRUE);
		}
		$this->queryTrace = null;
		if($DB_QUERY_TRACE_DUMP && isset($DB_QUERY_TRACE_FILE) && $DB_QUERY_TRACE_FILE!="")
		{
			$this->queryTrace = $DB_QUERY_TRACE_FILE;
		}
	}
	function isAvilable()
	{
		//return $this->link->ping();
		return true;
	}

	function bindVar($var,$val)
	{
		$this->scnMgmt->write($this,$var."|".base64_encode($val),"VAR");
	}
	function bindWith($obj)
	{
		
	}
	function getDescriptor($name,$type,$dbType)
	{
		$obj = new Descriptor($this->scnMgmt);
		$this->scnMgmt->write($this,$name."|".$type."|".$dbType,"DESC");
		return $obj;
	}
	function parse($q,$type,$mode='',$unbuf='')
	{


		$this->queryErrorStatus=1;
		$this->q = $q;
		$this->type = $type;
		$this->resource=null;
		if($type=="DQL" && !$this->isReadable())
		{
			return null;
		}
		if($type=="DML" && !$this->isWritable())
		{
			return null;
		}
		if($type=="DDL" && !$this->isEditable())
		{
			return null;
		}

		//echo $type;
		$this->startTimecount();

		if($type=="DQL")
		{
			$this->resource = $this->link->query($this->q);	
		}
		else
		{
			$this->resource = $this->link->exec($this->q);	
		}

		$this->endTimecount();
		$this->queryTrace();
		if(!$this->resource)
		{
			$this->queryErrorStatus;
			return false;
		}
		return $this->resource;
	}
	function execute()
	{
		return $this->resource;
	}
	function safe_query($q='',$type,$debug='',$unbuf='')
	{
		$this->parse($q,$type,$mode,$unbuf);
		return $this->execute();
	}
	function getRow()
	{
		return $this->resource->fetch_assoc();
	}
	function getDataset(&$data)
	{
		$data = array();
		$i=0;
		$this->startTimecount();

		while ($row = $this->resource->fetchArray(SQLITE3_ASSOC)) 
		{
			$data[count($data)]=array_change_key_case($row,CASE_UPPER);
			$i++;
		}

		$this->endTimecount();
		$this->addQuerycount();
		return $i;
	}
	function getFieldsCount()
	{
		return $this->resource->numColumns();
	}
	function getField($i)
	{
		$n =  $this->resource->columnName($i);

		return new RecordsetColumns($n,"VARCHAR","100");
		
	}
	function getColumns()
	{
		$t=array();
		$r = $this->resource->numColumns();

		for ($i=0; $i < $r; $i++) { 
			$n =  $this->resource->columnName($i);
			$t[count($t)] = new RecordsetColumns($n,"VARCHAR","100");
		}
			
		return $t;
	}
	function getCommitStatus()
	{
		return true;
	}
	function autoCommit($val)
	{
		$this->commitMode=$val;
		//return $this->link->autocommit($val);
		//$this->link->autocommit($val);
	}
	function commit()
	{
		//return $this->link->commit();
		//return $this->link->commit();
		return true;
	}
	function rollback()
	{
		//return $this->link->rollback();
		//return $this->link->rollback();
		return true;
	}
	function resultFree()
	{
		$this->resource->free();
	}
	function closeDatabase()
	{
		$this->link->close();
	}
	function dbError()
	{
		header('Status: 503 Service Unavailable');
		$this->messageObject->add('error',$this->link->connect_error,$this->q);
		if($this->showError)
		{
			die("Error : ".$this->link->connect_error);
		}
		else
		{
			die("");
		}
	}
}
?>