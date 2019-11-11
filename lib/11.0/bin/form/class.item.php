<?php
class FormBuilderItem
{
	var $datablock;
	var $name;
	var $path;
	var $property;
	var $phpPrifix;
	function __construct($datablock,$name)
	{
		
		$this->datablock = $datablock;
		$this->phpPrifix = $this->datablock->phpPrifix;
		$this->name = $name;
		$this->path = $this->datablock->path."/items/$name";
		include "$this->path/property.php";
		$this->property = $colProperty;
		
		
	}
	function getProperty($name)
	{
		return $this->property[$name];
	}
	function getSqlName()
	{
		return $this->getProperty("dbName");
	}
	function getSqlValue()
	{
		$dbType = $this->getProperty("dbType");
		$formatMask = $this->getProperty("formatMask");
		$v = "";
		$name = $this->getPhpName();

		if($dbType=="DATE")
		{
			$v = "TO_DATE('$name','$formatMask')";
		}
		else
		{
			$v = "'$name'";
		}
		return $v;
	}
	function getPhpVariable()
	{
		
	}
	function getObjectPhp()
	{
		$p = '$obj = new TextBox("'.str_replace("_","",$this->name).'");';
		return $p;
	}
	function getPhpMemberName()
	{
		return '$'.$this->phpPrifix.$this->name;
	}
	function getPhpName()
	{
		return '$this->'.$this->phpPrifix.$this->name;
	}
}
?>