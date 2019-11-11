<?php
class Model extends DBTable
{
	var $controller;
	var $db;
	function __construct($dbConnection=null)
	{
		global $db;
		
		if($dbConnection!=null)
		{
			$this->db=$dbConnection;
		}
		else
		{
			if(isset($db))
			{
				$this->db = $db;	
			}
		}
	}
	function setConnection(Connection $db)
	{
		$this->db = $db;
	}
	function initTable($tableName)
	{
		parent::__construct($tableName,$this->db);
		$this->initDb();
	}
	function query($columns="*",$query="",$autoExec=true)
	{
		if(gettype($columns)=="array")
		{
			$query = $columns;
			$columns="*";
		}
		return parent::query($columns,$query,$autoExec);
	}
	function execute($q)
	{
		return $this->db->execute($q);
	}
}
?>