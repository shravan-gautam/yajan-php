<?php
class Thread
{
	private $pid;
	private $phpFilename;
	private $phpClass;
	private $phpFunction;
	private $dx;
	private $pidFile;
	private $output;
	private $cmd;
	function __construct($pid="")
	{
		if($pid=="")
		{
			$this->pid = uniqid("yajan_",true);
		}
		else
		{
			$this->pid = $pid;
		}
		$this->pidFile = "/tmp/".$this->pid;
		$this->dx = new DataBox($this->pid);
		$this->refreash();
	}
	function start()
	{
		global $libVersion;
		
		$this->dx->add("arg",func_get_args());
		$this->dx->add("pid",$this->pid);
		$this->dx->add("phpFile",$this->phpFilename);
		$this->dx->add("phpClass",$this->phpClass);
		$this->dx->add("phpFunction",$this->phpFunction);
		$this->dx->add("status","start");
		$this->dx->toFile($this->pidFile);
		//$this->cmd = "nohup exec 2>&1 &&  php yajan/lib/$libVersion/php/process/runner.php $this->pid > /tmp/$this->pid.output &";
		$this->cmd  = "nohup php yajan/lib/$libVersion/php/process/runner.php $this->pid > /tmp/$this->pid.output 2>&1 &";
		exec($this->cmd,$this->output);
		return $this->pid;
	}
	function getPid()
	{
		return $this->pid;
	}
	function stop()
	{
		$this->dx->add("status","stop");
		$this->dx->toFile($this->pidFile);
	}
	function refreash()
	{
		$this->dx->fromFile($this->pidFile);
	}
	function getValue($key)
	{
		return ($this->dx->getObject("$key"));
	}
	function getCmd()
	{
		return $this->cmd;
	}
	function getOutput()
	{
		return file_get_contents("/tmp/$this->pid.output");
	}
	function getStatus()
	{
	}
	function setExecuter($filename,$class="",$function="")
	{
		$this->phpFilename = $filename;
		$this->phpClass = $class;
		$this->phpFunction = $function;
	}
}
?>