<?php
class JSONObjectCllection
{
	var $list;
	function __construct()
	{

	}
	function getItem($index)
	{
		$o = new JSONObject();
		$o->obj = $this->list[$index];
		return $o;
	}
	function count()
	{
		return count($this->list);
	}
}

class JSONObject
{
	var $string;
	var $obj;

	function __construct($data="")
	{
		$this->string = $data;
		if($this->string!="")
		{
			$this->fromString($this->string);
		}
	}
	function fromString($string)
	{
		$this->string = $string;
		$this->obj = json_decode($this->string);
	}
	function getProperty($name)
	{
		$val = $this->obj->{$name};
		$type = gettype($val);
		if($type=="array")
		{
			$l = new JSONObjectCllection();
			$l->list = $val;
			return $l;
		}
		else if($type=="string")
		{
			return $val;
		}
		else if($type=="object")
		{
			$o = new JSONObject();
			$o->obj = $val;
			return $o;
		}
	}
	function getChiled()
	{
		$type = gettype($this->obj);
		if($type=="array")
		{
			$l = new JSONObjectCllection();
			$l->list=$this->obj;
			return $l;
		}
		return null;
	}
	function getKeylist()
	{
		

	}
}
?>