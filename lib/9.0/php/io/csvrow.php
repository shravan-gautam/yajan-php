<?php
class CSVRow
{
	var $rowData;
	var $terminator;
	function CSVRow()
	{
		$this->rowData = array();
		$this->terminator = ",";
	}
	function parse($str)
	{
		$this->rowData = explode($this->terminator,$str);
	}
	function setRowData(array $data)
	{
		$this->rowData = $data;
	}
	function getColumnCount()
	{
		return count($rowData);
	}
	function replaceAll($from,$to)
	{
		for($i=0;$i<count($this->rowData);$i++)
		{
			$this->rowData[$i]=str_replace($from,$to,$this->rowData[$i]);
		}
	}
	function get($index)
	{
		return $this->rowData[$index];
	}
	function set($index,$val)
	{
		return $this->rowData[$index]=$val;
	}
}
?>