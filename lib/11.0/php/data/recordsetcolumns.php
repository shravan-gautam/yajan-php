<?php
class RecordsetColumns
{
	var $name;
	var $type;
	var $size;
	var $format;
	var $defaultValue;
	var $key;
	var $dbName;
	var $distinctSelection;
	var $attribute;
	var $encription;
	function __construct($name,$type='',$size='')
	{
		$this->name=strtoupper($name);
		$this->type=strtoupper($type);
		$this->attribute=array();
		$this->size=$size;
		$this->format="";
		$this->defaultValue="";
		$this->key="";
		$this->dbName = $name;
		$this->distinctSelection = false;
		$this->encription = 'ASCI';
	}
	function addAttribute($key,$val)
	{
		$this->attribute[$key]=$val;
	}
	function getAttribute($key)
	{
		return $this->attribute[$key];
	}
	function getDistinctSelection()
	{
		return $this->distinctSelection;
	}
	function setDistinctSelection($val) // true / false
	{
		$this->distinctSelection = $val;
	}
	function setDbName($name)
	{
		$this->dbName = strtoupper($name);
	}
	function setKey($key)
	{
		$this->key = strtoupper($key);
	}
	function getKey()
	{
		return $this->key;
	}
	function getType()
	{
		return $this->type;
	}
	function getFormat()
	{
		return $this->format;
	}
	function getDefaultValue()
	{
		return $this->defaultValue;
	}
	function getSize()
	{
		return $this->size;
	}
	function getDbName()
	{
		return $this->dbName;
	}
	function setType($type)
	{
		$this->type=strtoupper($type);
	}
	function setSize($size)
	{
		$this->size=$size;
	}
	function setFormat($format)
	{
		$this->format = strtoupper($format);
	}
	function setDefaultValue($value)
	{
		$this->defaultValue = $value;
	}
	function setEncription($encCode)
	{
		$this->encription = $encCode;
	}
	function getEncription()
	{
		return $this->encription;
	}
	function getName()
	{
		return $this->name;
	}
}
?>