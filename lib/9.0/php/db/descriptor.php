<?php
class Descriptor
{
	var $name;
	var $obj;
	var $db;
	var $type;
	var $dbType;
	var $scnMgmt;
	function Descriptor($scn=null)
	{
		if($scn!=null)
		{
			$this->scnMgmt=$scn;
		}
	}
	function setValue($val)
	{
		$this->scnMgmt->write($this->db->dbId,$this->dbType."|".$this->name."|".base64_encode($val),"LOB");
		return $this->obj->save($val);
	}
	function write($val)
	{
		$this->scnMgmt->write($this->db->dbId,$this->dbType."|".$this->name."|".base64_encode($val),"LOB");
		return $this->obj->write($val);
	}
	function loadFile($filename)
	{
		$this->scnMgmt->write($this->db->dbId,$this->dbType."|".$this->name."|".base64_encode(file_get_contents($filename)),"LOB");
		return $this->obj->savefile($filename);
	}
}
?>