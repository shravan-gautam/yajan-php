<?php
class DBDriver
{
	var $resource;
	public $user;
	public $pass;
	public $db;
	public $server;
	public $port;
	public $link;
	public $start;
	public $timecount;
	public $queryCount;
	public $commitMode;
	public $dbMode;
	public $messageObject;
	public $message;
	public $queryErrorStatus;
	public $autoExecute;
	public $queryTrace;
	public $showError;
	public $scnMgmt;
	public $parm;
	public $queryCost;
	var $type;
	var $q;
	var $dbId;
	function __construct()
	{
		
	}
	function scnLog($v)
	{
		$this->scnLog = $v;
	}
	function isAvilable()
	{
		if (!$this->link)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function autoCommit($val)
	{
		if($val)
		{
			$v="true";
		}
		else
		{
			$v="false";
		}
		$this->scnMgmt->autoCommit=$val;
		$this->scnMgmt->write($this,"autocommit $v","YSQL");
		$this->commitMode=$val;
		return true;
	}

	function autoExecute($val)
	{
		$this->scnMgmt->autoExecute=$val;
		$this->autoExecute = $val;
	}
	function isQueryComplite()
	{
		if($this->queryErrorStatus==2)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function scnUpdate()
	{
		return $this->scnMgmt->write($this,$this->q,$this->type);
	}
	function queryTrace()
	{
		global $CALL_SEQUANCE_TIME;
		if($this->queryTrace!=null)
		{
			$file = new File($this->queryTrace);
			$b = debug_backtrace();
			$j = json_encode($b);
			$file->append(date("d/m/Y h:i:s A")."|".$CALL_SEQUANCE_TIME."|".$this->queryCost."|".$b[4]["file"].":".$b[4]["line"]."|".$this->q."\n");
		}
	}
	function showError($v)
	{
		$this->showError = $v;
	}
	function getMessage()
	{
		return $this->messageObject;
	}
	function getmicrotime() 
	{ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	function startTimecount()
	{
		$this->queryCost=0;
		$this->start=$this->getmicrotime();
	}
	function addQuerycount()
	{
		$this->queryCount = $this->queryCount+1;
	}
	function endTimecount()
	{
		$this->timecount = $this->timecount+ ($this->getmicrotime() - $this->start);
		$this->queryCost = ($this->getmicrotime() - $this->start);
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
		if(strpos($this->dbMode,"w")===false)
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