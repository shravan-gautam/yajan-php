<?php
require_once("$LIB_PATH/php/data/recordsetcolumns.php");
class ColumnCollection
{
	var $collection;
	function ColumnCollection()
	{
		$this->collection = array();
	}
	function add(RecordsetColumns $col)
	{
		$this->collection[count($this->collection)]=$col;
	}
	function count()
	{
		return count($this->collection);
	}
	function getColumn($i)
	{
		return $this->collection[$i];
	}
}

?>