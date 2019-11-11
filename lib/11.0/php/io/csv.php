<?php
class Csv
{
	var $filename;
	var $determinator;
	var $file;
	var $fp;
	var $header=false;
	var $firstRow=false;
	function __construct($filename="")
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
	function firstRowAsHeader()
	{
		$this->firstRow=true;
	}
	function header($v=true)
	{
		$this->header=$v;
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
		$cmd = "sed -i '1s/^\xEF\xBB\xBF//' $this->filename";
		exec($cmd);

		$objCSV=fopen($this->filename,"r");

		$rs=new Recordset();
		$headerClose=false;
		$colList = array();
		

		
		function __getRow(&$handle,$length,$deliminator)
		{
			$data = fgetcsv($handle, $length, $deliminator);
			if($data==false)
			{
				return false;
			}
			return $data;			
		}
       	while (($objArr = __getRow($objCSV,3000,$this->determinator)) !==false)
		{ 
			
			$num = count($objArr);
			if($this->header==true)
			{
				if($headerClose==false)
				{
					if($objArr[0]=="Start entering data below this line")
					{
						$headerClose=true;
						
					}
					else
					{
						
						if($objArr[0]=="DocType:")
						{
							$colLable = __getRow($objCSV,3000,$this->determinator);
							$colName = __getRow($objCSV,3000,$this->determinator);
							$colMandetry = __getRow($objCSV,3000,$this->determinator);
							$colType = __getRow($objCSV,3000,$this->determinator);
							$colFormat = __getRow($objCSV,3000,$this->determinator);
							$colSize = __getRow($objCSV,3000,$this->determinator);
							$colInfo = __getRow($objCSV,3000,$this->determinator);

							for($ixx = 1;$ixx<count($colName);$ixx++)
							{
								$col = new RecordsetColumns($colName[$ixx],$colType[$ixx],$colSize[$ixx]);
								$col->format = $colFormat[$ixx];
								$rs->columns[count($rs->columns)]=$col;
							}
							$rs->countColumns = count($rs->columns);
							//print_r($rs->columns);
							//

						}
					}
				}
			}
			else if($this->firstRow==true)
			{
				if($headerClose==false)
				{
					$colList = $objArr;
					for($i=0;$i<$num;$i++)
					{
						$rs->addColumns($objArr[$i]);
					}
					$objArr = __getRow($objCSV,3000,$this->determinator);
					$num = count($objArr);
					$headerClose=true;
				}
			}
			else
			{
				if($row==1)
				{
					for($i=0;$i<$num;$i++)
					{
						$rs->addColumns("Column_".($i+1));
					}
				}
			}
			
			


			if($this->header==true)
			{
				if($headerClose==true && $objArr[0]!="Start entering data below this line")
				{
					
					$row++;
					$t = array();
					
					for ($c=1; $c < $num; $c++) 
					{
						$t[strtoupper($colName[$c])]=$objArr[$c];
					}
					
					$rs->add($t);
				}
			}
			else if($this->firstRow==true )
			{
				$row++;
				$t = array();
				for($i=0;$i<$num;$i++)
				{
					//$rs->addColumns($objArr[$i]);
					$t[$colList[$i]]=$objArr[$i];
				}
				$rs->add($t);
			}
			else
			{
				$row++;
				$t = array();
				for ($c=0; $c < $num; $c++) 
				{
					$t[strtoupper("column_".($c+1))]=$objArr[$c];
				}
				$rs->add($t);
			}
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