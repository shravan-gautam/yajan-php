<?php
class ThreadRunner
{
	var $pid;
	var $threadData;
	var $arg;
	var $phpFile;
	var $pidFile;
	function __construct($pid)
	{
		$this->pid = $pid;
		$this->pidFile = "/tmp/$pid";
		$this->threadData = new DataBox($this->pid);
		$this->threadData->fromFile($this->pidFile);
		$this->arg = $this->threadData->getObject("arg");
		$this->phpFile = $this->threadData->getObject("phpFile");
	}
	function setValue($key,$value)
	{
		$this->threadData->add($key,$value);
		$this->threadData->toFile($this->pidFile);
	}
	function getValue($key)
	{
		$this->threadData->fromFile($this->pidFile);
		return $this->threadData->getObject($key);
	}
	function run()
	{	
		
		for($i=0;$i<count($this->arg);$i++)
		{
			$v = "arg$i";
			$$v = $this->arg[$i];
		}
		if($this->getValue("status")=="start")
		{
			$this->setValue("status","running");
			include  $this->phpFile;
			$this->setValue("status","complete");
		}
	}
}
?>