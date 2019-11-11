<?php
class CloudDB
{
	var $resource;
	var $q;
	public $link;
	public $start;
	public $timecount;
	public $queryCount;
	public $commitMode;
	public $dbMode;
	var $messageObject;	
	public $message;
	public $queryErrorStatus;
	public $autoExecute;
	public $queryTrace;
	public $showError;
	public $scnMgmt;
	public $parm;
	public $result;
	var $type;
	var $dbId;
	
	function __construct($parm,$id)
	{
		global $DB_QUERY_TRACE_FILE,$DB_QUERY_TRACE_DUMP,$SHOW_DB_ERROR;
		$this->messageObject = new Messages("OracleDatabase");
		$this->dbMode=$parm['dbmode'];
		$this->link=null;
		
		$this->parm = $parm;
		$sec = new Encryption("shravan");
		$this->parm['password']=$sec->encrypt($this->parm['password']);
		$this->commitMode=true;
		$this->start=0;
		$this->timecount=0;
		$this->queryCount=0;
		$this->showError=$SHOW_DB_ERROR;
		$this->dbId = $id;
		$this->server = $parm['server'].":".$parm['port']."/cloud/db/sql";

	}
	function scnLog($v)
	{
		$this->scnLog = $v;
	}
	function isAvilable()
	{

	}
	function autoExecute($val)
	{
		return true;
	}
	function isQueryComplite()
	{

	}
	function scnUpdate()
	{
				return $this->scnMgmt->write($this,$this->q,$this->type);
	}
	function bindVar($var,$val)
	{

	}
	function bindWith($obj)
	{

	}
	function getDescriptor($name,$type,$dbType)
	{

	}
	function parse($q,$type,$mode='',$buff='')
	{
		
		$this->queryErrorStatus=1;
		$this->resource=null;
		$this->q = $q;
		
		$this->type = $type;
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
		
		$this->dx = new DataBox1_2("request","shravan");
		$this->dx->add("key",$this->parm['username']);
		$this->dx->add("ip",$_SERVER['SERVER_ADDR']);
		$this->dx->add("sql",$this->q);
		$this->dx->add("type",$this->type);		
		return $this->dx;
	}
	function queryTrace()
	{

	}
	
	function execute()
	{
		
		$hr = new HttpRequest($this->server);
		$hr->sslVarification(false);
		$hr->addParameter("request",$this->dx->toString());
		$this->str = $hr->send();
		//echo $this->str;
		$resp = new DataBox1_2("response","shravan");
		if($resp->parse($this->str))
		{
			if($resp->getObject("status")=="ok")
			{
				if($resp->getObject("output")=="object")
				{
					$this->resource = new Recordset();
					$str = $resp->getObject("response");
					$this->resource->fromString($str);
					return $this->resource;
				}
				else
				{
					return $resp->getObject("response");
				}
				
			}
			else
			{
				$this->message = $resp->getObject("text");
				return false;
			}
		}
		else
		{
			$this->message="invalid responce.";
			return false;
		}
	}
	function safe_query($q,$type,$mode='',$buff='')
	{

	}
	function autoCommit($val)
	{

	}
	function commit()
	{
		return true;
	}
	function rollback()
	{
		return true;
	}
	function getRow()
	{
	}
	function getDataset(&$dataset)
	{
		
	}
	function getFieldsCount()
	{
		//return oci_num_fields($this->resource);
	}
	function getField($i)
	{
		/*
		if(oci_num_fields($this->resource)!=false)
		{
			
			if($i<oci_num_fields($this->resource))
			{
		return new RecordsetColumns(oci_field_name($this->resource,$i+1),oci_field_type($this->resource,$i+1),oci_field_size($this->resource,$i+1));
			}
			else
			{
				return null;
			}
		}
		else
		{
			return null;
		}
		*/						
	}
	function getColumns()
	{
		/*
		$t=array();
		
		for($i=0;$i<$this->getFieldsCount();$i++)
		{
			$t[count($t)]=$this->getField($i);
		}
		return $t;
		*/
	}
	function showError($v)
	{
		//$this->showError = $v;
	}
	function resultFree()
	{
		/*
		if($this->resource)
		{
			oci_free_statement($this->resource); 
		}
		*/
	}
	function closeDatabase()
	{
		//oci_close($this->link); 
	}
	function getMessage()
	{	
		//return $this->messageObject;
	}
	function dbError($e,$exit='')
	{
		/*
		$this->messageObject->add('error',$e['message'],$this->q);
		
		if($this->showError)
		{
			print htmlentities($e['message']." on query \"".$this->q."\"");
		}
		if($exit!='')
		{
			exit;
		}
		*/
	}
	function getmicrotime() 
	{ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	function startTimecount()
	{
		//$this->start=$this->getmicrotime();
	}
	function addQuerycount()
	{
		$this->queryCount = $this->queryCount+1;
	}
	function endTimecount()
	{
		$this->timecount = $this->timecount+ ($this->getmicrotime() - $this->start);
	}
	function isReadable()
	{
		if(strpos($this->dbMode,"r")===false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function isWritable()
	{
		if(strpos($this->dbMode,"w")===false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function isEditable()
	{
		if(strpos($this->dbMode,"e")===false)
		{
			return false;
		}
		else
		{
			return true;
		}

	}
}
?>