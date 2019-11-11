<?php
class UIEvents
{
	var $name;
	var $callback;
	var $type;
	var $class;
	var $parm;
	var $classMathod;
	var $application;
	var $url;
	function __construct($name,$callback,$type)
	{
		$this->name=$name;
		$callback = str_replace("()","",$callback);
		$this->callback=$callback;
		$this->type = $type;
		$this->parm=array();
		$this->application="";
	}
	function setApplication($application)
	{
		$this->application;
	}
	function setClass($class,$mathod)
	{
		$this->class=$class;
		$this->classMathod=$mathod;
	}
	function setUrl($url)
	{
		$this->url = $url;
	}
	function addParameter($name,$varname="",$value="")
	{
		if($varname=="" && $this->type!="js")
		{
			$varname=$name;
		}
		$temp = array();
		$temp['name']=$name;
		$temp['varname']=$varname;
		$temp['value']=$value;
		$this->parm[count($this->parm)]=$temp;
	}
}
?>