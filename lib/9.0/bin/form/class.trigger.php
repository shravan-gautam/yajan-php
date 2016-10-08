<?php
class FormBuilderTrigger
{
	var $datablock;
	var $name;
	var $property;
	var $path;
	var $data;
	function FormBuilderTrigger($datablock,$name)
	{
		$this->datablock = $datablock;
		$this->name = $name;
		$this->path = $this->datablock->path."/trigger";
		$this->data = file_get_contents($this->path."/$name");
	}
	function getData()
	{
		return $this->data;
	}
}
?>