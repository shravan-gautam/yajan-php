<?php
class Csv
{
	var $filename;
	var $determinator;
	var $file;
	var $fp;
	function Csv($filename="")
	{
		
		$this->determinator=",";
		$this->filename=$filename;
		if($filename!="")
		{
			$this->fp = fopen($this->filename,"r");
			$this->file = new File("$filename");
			//$this->file->toUnixFormat();
			$this->file->open();
		}		
	}
	function setDeterminator($d)
	{
		$this->determinator=$d;
	}
	function toRecordset($file="")
	{
		$row=1;
		if($file!="")
		{
			$this->filename=$file;
		}
		$objCSV=fopen($this->filename,"r");
		$rs=new Recordset();
       	while (($objArr = fgetcsv($objCSV, 3000, $this->determinator)) !==false)
		{ 
			$num = count($objArr);
			if($row==1)
			{
				for($i=0;$i<$num;$i++)
				{
					$rs->addColumns("Column_".($i+1));
				}
			}
			$row++;
			$t = array();
			for ($c=0; $c < $num; $c++) 
			{
				$t[strtoupper("column_".($c+1))]=$objArr[$c];
				
			}
			$rs->add($t);
        }
        fclose($objCSV);
		return $rs;
	}
	function getRowCount()
	{
		return $this->file->countLine();
		
	}
	function getRow()
	{
		$str = $this->file->nextLine();
		
		$str = fgetcsv($this->fp, 3000, $this->determinator);
		//$str = fgets($this->fp);
		//echo $str;
		//print_r($str);
		if($str!==false)
		{
			$r = new CSVRow();
			//$r->parse($str);
			$r->setRowData($str);
			return $r;
		}
		else
		{
			return false;
		}
	}
}
?>