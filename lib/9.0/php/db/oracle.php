<?php
class OracleDatabase extends DBDriver
{
	function __construct($parm,$id)
	{
		parent::DBDriver();
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
		$str = '//'.$parm['server'].'/'.$parm['database'];
		$str ='(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST='.$parm['server'].')(PORT='.$parm['port'].'))(CONNECT_DATA=(SERVER=DEDICATED)(SERVICE_NAME = '.$parm['database'].')))';
		if(isset($parm['charset']))
		{
			$this->link = oci_connect($parm['username'], $parm['password'],$str,$parm['charset']);
		}
		else
		{
			$this->link = oci_connect($parm['username'], $parm['password'],$str );
		}
		
		if(isset($parm['showError']))
		{
			$this->showError = $parm['showError'];
		}
		$this->queryErrorStatus = false;
		$this->autoExecute = true;
		if (!$this->link) 
		{
			$e = oci_error();
			$this->dbError($e,'exit');
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
		return oci_bind_by_name(
			$this->resource,
			$var,
			$val,
			-1);
	}
	function bindWith($obj)
	{
		oci_bind_by_name(
			$this->resource,
			$obj->name, 
			$obj->obj,
			-1,
			$obj->dbType);
	}
	function getDescriptor($name,$type,$dbType)
	{
		$obj = new Descriptor($this->scnMgmt);
		$obj->name = $name;
		$obj->obj = oci_new_descriptor($this->link,$type);
		$obj->type = $type;
		$obj->dbType = $dbType;
		$obj->db = $this;
		$this->scnMgmt->write($this,$name."|".$type."|".$dbType,"DESC");
		//$this->bindWith($obj);
		return $obj;
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
		$this->resource = oci_parse($this->link, $q);
		
		$this->startTimecount();
		if (!$this->resource)
		{
			$e = oci_error($this->link);
			$this->dbError($e);
			$this->queryErrorStatus=2;
			return null;
		}
		return $this->resource;
		
	}
	
	function execute()
	{
		$this->result=false;
		if($this->resource)
		{
			if($this->commitMode==true)
			{
				
				$this->result = oci_execute($this->resource, OCI_COMMIT_ON_SUCCESS);
			}
			else
			{
				$this->result = oci_execute($this->resource, OCI_NO_AUTO_COMMIT);
			}
			if ($this->result==false) 
			{
				$this->queryErrorStatus=2;
				$e = oci_error($this->resource);
				oci_rollback($this->link); 
				$this->dbError($e);
			}
			$this->endTimecount();
			$this->addQuerycount();
			$this->queryTrace();
		}
		if(!$this->resource) return false;
		return $this->resource;
	}
	function safe_query($q,$type,$mode='',$buff='')
	{
		
		$this->parse($q,$type,$mode='',$buff='');
		return $this->execute();
	}
	function commit()
	{
		$this->scnMgmt->write($this,"commit","YSQL");
		return oci_commit($this->link);
		//$this->resultFree();
	}
	function rollback()
	{
		$this->scnMgmt->write($this,"rollback","YSQL");
		return oci_rollback($this->link);
	}
	function getRow()
	{
		return oci_fetch_assoc($this->resource);
	}
	function getDataset(&$dataset)
	{
		$dataset = array();
		$i=0;
		$this->startTimecount();
		while ($row = oci_fetch_assoc($this->resource)) 
		{
			
			$dataset[count($dataset)]=array_change_key_case($row,CASE_UPPER);
			$i++;
		} 
		$this->endTimecount();
		return $i;
	}
	function getFieldsCount()
	{
		return oci_num_fields($this->resource);
	}
	function getField($i)
	{
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
	function resultFree()
	{
		if($this->resource)
		{
			oci_free_statement($this->resource); 
		}
	}
	function closeDatabase()
	{
		oci_close($this->link); 
	}
	function dbError($e,$exit='')
	{
		
		$this->messageObject->add('error',$e['message'],$this->q);
		
		if($this->showError)
		{
			print htmlentities($e['message']." on query \"".$this->q."\"");
		}
		if($exit!='')
		{
			exit;
		}
		
	}
}
?>