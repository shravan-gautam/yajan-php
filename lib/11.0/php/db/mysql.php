<?php
class MysqlDatabase extends DBDriver
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
		$this->link = @mysql_connect($parm['server'], $parm['username'], $parm['password']);
		if (!$this->link)
		{
			$this->dbError();
		}
		if(!@mysql_select_db($parm['database'],$this->link))
		{
			$this->dbError();
		}
		$this->autoExecute = true;
		if($this->commitMode==true)
		{
			
		}
		$this->queryTrace = null;
		if($DB_QUERY_TRACE_DUMP && isset($DB_QUERY_TRACE_FILE) && $DB_QUERY_TRACE_FILE!="")
		{
			$this->queryTrace = $DB_QUERY_TRACE_FILE;
		}
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
		$method = (!$unbuf) ? 'mysql_query' : 'mysql_unbuffered_query';
		if (!$q) return false;
		
		$this->startTimecount();
		$this->resource = $method($q,$this->link);
		
		if ($this->resource === false)
		{
			$this->queryErrorStatus=2;
			echo mysql_error() ;
			return null;
			///trigger_error(mysql_error() . n . $q, E_USER_ERROR);
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
		return mysql_fetch_assoc($this->resource);
	}
	function getDataset(&$data)
	{
		$data = array();
		$i=0;
		$this->startTimecount();
		while ($row = mysql_fetch_assoc($this->resource)) 
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
		return mysql_num_fields($this->resource);
	}
	function getField($i)
	{
		return new RecordsetColumns(mysql_field_name($this->resource,$i),
									mysql_field_type($this->resource,$i),
									mysql_field_len($this->resource,$i));
	}
	function getColumns()
	{
		$t=array();
		
		for($i=0;$i<$this->getFieldsCount();$i++)
		{
			$t[count($t)]=$this->getField($i);
		}
		return $t;
	}
	function autoCommit($val)
	{
		$this->commitMode=$val;
		//mysqli_autocommit($con,FALSE);
	}
	function commit()
	{
	}
	function rollback()
	{
	}
	function resultFree()
	{
		mysql_free_result($this->resource); 
	}
	function closeDatabase()
	{
		mysql_close($this->link); 
	}
	function dbError()
	{
		header('Status: 503 Service Unavailable');
		$this->messageObject->add('error',mysql_error(),$this->query);
		if($this->showError)
		{
		die("Error : ".mysql_error());
		}
		else
		{
			die("");
		}
	}
}
?>
