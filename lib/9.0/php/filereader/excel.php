<?php

$excel_readers = array(
    'Excel5' , 
    'Excel2003XML' , 
    'Excel2007'
);
require_once( "PHPExcel.php");

class MSExcel
{
	var $filename;
	var $reader;
	var $excel;
	function MSExcel($filename="")
	{
		$this->excel=null;
		$this->filename=$filename;
		$this->reader = PHPExcel_IOFactory::createReader('Excel5');
		$this->reader->setReadDataOnly(true);
		$this->open($this->filename);
	}
	function open($filename)
	{
		$this->filename=$filename;
		if($this->filename!="" && file_exists($this->filename))
		{
			$this->excel = $this->reader->load($this->filename);
			return true;
		}
		else
		{
			$this->message="File is not exist or invalid.";
			return false;
		}
	}
	function toCSV($outputFilename)
	{
		
		if($outputFilename!="")
		{
			if($this->excel != null)
			{
				$writer = PHPExcel_IOFactory::createWriter($this->excel, 'CSV');
				$writer->save($outputFilename);
				
				return true;
			}
			else
			{
				$this->message="excel file is not opend.";
				return false;
			}
		}
		else
		{
			$this->message="output file name is null";
			return false;
		}
	}
}

?>