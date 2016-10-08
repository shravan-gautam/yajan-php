<?php
import("system");
import("security");
require_once("$LIB_PATH/php/data/recordsetcolumns.php");
class Recordset
{
	var $database;
	var $count;
	var $data;
	var $message;
	var $columns;
	var $countColumns;
	var $recordPosition;
	var $populationStatus;
	var $encription;
	var $encrptCode;
	var $distinctFilterStatus;
	var $distinctColumnIndex;
	var $priviusColumnReset;
	var $resource;
	function Recordset()
	{
		
		$numargs = func_num_args();
		$this->message = new Messages("DatabaseRecordset");
		$this->count=0;
		$this->recordPosition=0;
		$this->database=null;
		$this->populationStatus=false;
		$this->columns=array();
		$this->priviusColumnReset=true;
		$this->encrptCode="ASCI";
		$this->encription = new Encryption($this->encrptCode);
		$this->distinctFilterStatus=false;
		$this->distinctColumnIndex = array();
		$this->resource=null;
		if($numargs>0)
		{
			$this->database = func_get_arg(0);
			$this->database->q="";			
		}
		if($numargs>1)
		{
			$this->resource = func_get_arg(1);
		}
	}
	function reverce()
	{
		$this->data = array_reverse($this->data);
	}
	function setPriviusColumnReset($v)
	{
		$this->priviusColumnReset=$v;
	}
	function aggrigation($function,$field,$groupby)
	{
		$groupby = strtoupper($groupby);
		$field = strtoupper($field);
		$r = clone $this;
		$r->orderBy($groupby);
		$oldVal = "";
		$c = 1;
		
		$n = new Recordset();
		
		$n->addColumns($groupby,$function);
		for($i=0;$i<$r->count;$i++)
		{
			if($oldVal!=$r->data[$i][$groupby])
			{
				
				$oldVal =$r->data[$i][$groupby];
				if($i>1)
				{
					if($function=="count")
					{
						$n->add($oldVal,$c);
					}
				}
				$c=1;
			}
			else
			{
				$c++;
			}
		}
		return $n;
	}
	
	function clear()
	{
		$this->data = array();
		$this->count=0;
	}
	function append(Recordset $r)
	{
		$this->data = array_merge((array)$this->data, (array)$r->data);
		$this->count = count($this->data);
	}
	function createDuplicate($data=false)
	{
		$r = new Recordset();
		if($data)
		{
			$r->count=$this->count;
			$r->data = $this->data;
		}
		else{
			$r->count=0;
			$r->data=array();
		}
		$r->columns = $this->columns;
		$r->countColumns = $this->countColumns;
		$r->encription = $this->encription;
		$r->encrptCode = $this->encrptCode;
		$r->populationStatus = $this->populationStatus;
		$r->recordPosition = 0;
		$r->database = $this->database;
		return $r;
	}
	function isNumaric($col,$index="")
	{
		$col = strtoupper($col);
		if($index!="")
		{
			return is_numeric($this->data[$index][$col]);
		}
		else
		{
			for($i=0;$i<$this->count;$i++)
			{
				
				if(!is_numeric($this->data[$i][$col]) && $this->data[$i][$col]!="")
				{
					return false;
				}
			}
			return true;
		}
	}
	function extractColumns($cols)
	{
		$cols=strtoupper($cols);
		$r = new Recordset();
		
		$cols = explode(",",$cols);
		for($j=0;$j<count($cols);$j++)
		{
			$r->addColumns($cols[$j]);
		}
		for($i=0;$i<$this->count;$i++)
		{
			$ar = array();
			for($j=0;$j<count($cols);$j++)
			{
				$ar[$cols[$j]]=$this->data[$i][$cols[$j]];
			}
			$r->data[$i]=$ar;
		}
		$r->count=$this->count;
		$r->message=$this->message;
		$r->recordPosition=$this->recordPosition;
		$r->populationStatus=$this->populationStatus;
		$r->encription=$this->encription;
		$r->encrptCode=$this->encrptCode;
		return $r;
		
	}
	function extract($col,$val,$mode="==")
	{
		$col = strtoupper($col);
		$t = clone $this;
		
		if($t->populationStatus==true)
		{

			$temp = array();
			for($i=0;$i<$t->count;$i++)
			{
				if(isset($t->data[$i][$col]))
				{
					if($mode=="==")
					{
						if(gettype($val)=="array")
						{
							if(array_search($t->data[$i][$col],$val)!==false)
							{
								$temp[count($temp)]=$t->data[$i];
							}
						}
						else
						{
							if($t->data[$i][$col]==$val)
							{
								$temp[count($temp)]=$t->data[$i];
							}
						}
					}
					else if($mode=="<")
					{
						if($t->data[$i][$col]<$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}
					else if($mode=="<=")
					{
						if($t->data[$i][$col]<=$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}				
					else if($mode==">")
					{
						if($t->data[$i][$col]>$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}
					else if($mode==">=")
					{
						if($t->data[$i][$col]>=$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}				
					else if($mode=="!=")
					{
						if($t->data[$i][$col]!=$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}
					else if($mode=="start with")
					{
						$s = substr($t->data[$i][$col],0,strlen($val));
						if($s!=$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}
					else if($mode=="end with")
					{
						$s = substr($t->data[$i][$col],strlen($t->data[$i][$col])-strlen($val),strlen($val));
						if($s!=$val)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}				
					else if($mode=="content")
					{
						if(strpos($t->data[$i][$col],$val)!==false)
						{
							$temp[count($temp)]=$t->data[$i];
						}
					}
				}
			}
			$t->data = $temp;
			$t->count = count($temp);
		}
		return $t;
	}
	function getSum($col)
	{
		$col = strtoupper($col);
		if($this->isNumaric($col))
		{
			$sum = 0;
			for($i=0;$i<$this->count;$i++)
			{
				$sum+=$this->data[$i][$col];
			}
			return $sum;
		}
		else
		{
			return null;
		}
	}
	function getAverage($col)
	{
		$col = strtoupper($col);
		if($this->isNumaric($col))
		{
			$sum = 0;
			for($i=0;$i<$this->count;$i++)
			{
				$sum+=$this->data[$i][$col];
			}
			return ($sum/$this->count);
		}
		else
		{
			return null;
		}
	}	
	function getCsvColumn($column,$encoder="")
	{
		$column = strtoupper($column);
		$str = "";
		for($i=0;$i<$this->count;$i++)
		{
			$str.=$encoder.$this->data[$i][$column].$encoder.',';
		}
		//echo $str;
		$str = rtrim($str,",");
		return $str;
	}
	function getRow()
	{
		if($this->populationStatus==true)
		{
			return $this->data[$this->recordPosition];
		}
		else
		{
			$this->getDBRow();
		}
	}
	function setColumnFormat($name,$format)
	{
		foreach($this->columns as $col)
		{	
			
			if($col->getName()==strtoupper($name))
			{
				$col->setFormat($format);
			}
		}
	}
	function setColumnDistinctSelection($name,$value)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setDistinctSelection($value);
			}
		}
	}	
	function distinctSelection()
	{
		if($this->distinctFilterStatus==false)
		{
			$backColumn = null;
			foreach($this->columns as $col)
			{
				if($col->getDistinctSelection())
				{
					if($this->populationStatus)
					{
						$v = null;
						$cl = array();
						$count  = 1;
						$ffp= 0;
						
						for($i=0;$i<$this->count;$i++)
						{
							if($v==$this->data[$i][$col->getName()])
							{
								if($backColumn!=null && $this->priviusColumnReset)
								{
									$ncl = $this->distinctColumnIndex[$backColumn->getName()];
									if($this->data[$i][$backColumn->getName()]!='------"-----')
									{
										if($i!=0)
										{
											
											$cl[$ffp."::".$v]=$count;
											if($ffp<$i)
											{
												$ffp = $i;
											}
										}
										$v=$this->data[$i][$col->getName()];
										$count=1;
									}
									else
									{
										$this->data[$i][$col->getName()]='------"-----';
										$count++;									
									}
								}
								else
								{
									$this->data[$i][$col->getName()]='------"-----';
									$count++;
								}
							}
							else
							{
								if($i!=0)
								{
									
									$cl[$ffp."::".$v]=$count;
									if($ffp<$i)
									{
										$ffp = $i;
									}
								}
								$v=$this->data[$i][$col->getName()];
								$count=1;
							}
						}
						$cl[$ffp."::".$v]=$count;
						$this->distinctColumnIndex[$col->getName()] = $cl;
					}
				}
				$backColumn = $col;
			}
			$this->distinctFilterStatus = true;
		}
	}
	function setColumnEncription($name,$value)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setEncription($value);
			}
		}
	}
	function setColumnDefaultValue($name,$value)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setDefaultValue($value);
			}
		}
	}
	function setColumnDbName($name,$dbName)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setDbName($dbName);
			}
		}
	}
	function setColumnType($name,$type)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setType($type);
			}
		}
	}
	function setColumnSize($name,$size)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setSize($size);
			}
		}
	}
	function setColumnKey($name,$key)
	{
		foreach($this->columns as $col)
		{
			if($col->getName()==strtoupper($name))
			{
				$col->setKey($key);
			}
		}
	}
	function encrypt($format)
	{
		$this->encription->setFormat($this->encrptCode);
		if($this->populationStatus)
		{
			for($i=0;$i<$this->count;$i++)
			{
				foreach($this->data[$i] as $key => $val)
				{
					$ci = $this->findColumnIndex($key);
					$this->data[$i][$key] = $this->encription->crypt($val,$this->columns[$ci]->getEncription());
				}
			}
		}
		$this->encrptCode = $format;
	}
	function decrypt()
	{
		$this->encription->setFormat('ASCI');
		if($this->populationStatus)
		{
			for($i=0;$i<$this->count;$i++)
			{
				foreach($this->data[$i] as $key => $val)
				{
					
					$ci = $this->findColumnIndex($key);
					if($ci>-1)
					{
					$this->data[$i][$key] = $this->encription->crypt($val,$this->columns[$ci]->getEncription());
					}
				}
			}
		}
		$this->encrptCode = 'ASCI';
	}
	function isPopulated()
	{
		return $this->populationStatus;
	}
	function populateColumns()
	{
		if($this->database!=null)
		{
			if($this->database->resource!=null)
			{
				$this->countColumns = $this->database->getFieldsCount();
				$this->columns = $this->database->getColumns();
			}
		}
	}
	function populate()
	{
		if($this->database!=null)
		{
			if($this->database->resource!=null)
			{
				$this->count = $this->database->getDataset($this->data);
				$this->countColumns = $this->database->getFieldsCount();
				$this->columns = $this->database->getColumns();
				$this->populationStatus=true;
			}
			else
			{
				$this->message->add("Error","Database statment resource id is null");
			}
		}
	}
	function findColumnIndex($name)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				return $i;
			}
		}
		return -1;
	}
	function addColumns()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();

		
		for ($i = 0; $i < $numargs; $i++) 
		{
			$this->columns[count($this->columns)]=new RecordsetColumns(strtoupper($arg_list[$i]),"varchar2","50");
			for($j=0;$j<$this->count;$j++)
			{
				$this->data[$j][strtoupper($arg_list[$i])]='';
			}
		}
		$this->countColumns = count($this->columns);
	}
	function setColumnValue($name,$val)
	{
		for($j=0;$j<$this->count;$j++)
		{
			$this->data[$j][strtoupper($name)]=$val;
		}		
	}	
	function add()
	{
		$arg_list = func_get_args();
		if(gettype($arg_list[0])=="array")
		{
			
			if(count($arg_list[0])!=$this->countColumns)
			{
				$this->message->add("Error","Invalid array arguments");
				return false;
			}
			$t = $arg_list[0];
		}
		else
		{
			$numargs = func_num_args();
			if($numargs!=$this->countColumns)
			{
				$this->message->add("Error","Invalid arguments");
				return false;
			}
			$t=array();
			for ($i = 0; $i < $numargs; $i++) 
			{
				$t[$this->columns[$i]->getName()]=$arg_list[$i];
			}
		}
		
		$this->data[count($this->data)]=$t;
		$this->count++;
		$this->populationStatus=true;
	}
	function orderBy($name,$desc=false)
	{
		$name = strtoupper($name);
		$this->data = sortArray($this->data,$name,$desc);
	}
	function moveRecord($i)
	{
		$this->recordPosition=$i;
	}
	function getDBRow($cash=true)
	{
		
		if($this->database)
		{	
			if($cash)
			{
				$this->populationStatus=true;
				$this->count = count($this->data);
				$row=$this->database->getRow();
				if($row!=false)
				{
					$row=array_change_key_case($row,CASE_UPPER);
					$this->data[$this->count] = $row;
					return $this->data[$this->count];
				}
				else
				{
					return false;
				}
			}
			else
			{
				$row = $this->database->getRow();
				if($row!=false)
				{
					return array_change_key_case($row,CASE_UPPER);
				}
				else
				{
					return false;
				}
				
			}
		}
		else
		{
			return false;
		}
	}
	function getCsv()
	{
		$str = "";
		if($this->populationStatus==true)
		{
			for($i=0;$i<$this->count;$i++)
			{
				$r = $this->data[$i];
				foreach($r as $k => $v)
				{
					$str .= '"'.strip_tags($v).'",';
				}
			}
		}
		else
		{
			if($this->database)
			{
				while($row = $this->getDBRow())				
				{
					foreach($row as $k => $v)
					{
						$str .= '"'.strip_tags($v).'",';
					}
				}
			}
		}
		return $str;
	}
	
	function exportCSV($filename)
	{
		$file = new File($filename);
		$file->writeUTF();
		$col = array();
		
		for($i=0;$i<count($this->columns);$i++)
		{
			$col[$i]=$this->columns[$i]->getName();
		}
		$file->writeCSV(array($col));
		if($this->populationStatus==true)
		{
			$file->writeCSV($this->data);
		}
		else
		{
			if($this->database)
			{
				while($row = $this->getDBRow())				
				{
					$file->writeCSV(array($this->data[$this->count]));
				}
			}
		}
	}
	function htmlTable()
	{
		$str = '<table><tr>'."\n";
		foreach($this->columns as $col)
		{
			$str .= '<td>'.$col->getName().'</td>'."\n";
		}
		$str.='</tr>'."\n";
		if($this->populationStatus==true)
		{
			
			for($i=0;$i<$this->count;$i++)
			{
				$str .= '<tr>'."\n";
				foreach($this->columns as $col)
				{
					$str .= '<td>'.$this->data[$i][$col->getName()].'</td>'."\n";
				}
				$str .= '</tr>'."\n";
			}
			
		}
		else
		{
			if($this->database)
			{
				$i=0;
				while($row = $this->getDBRow())				
				{
					$str .= '<tr>'."\n";
					foreach($row as $key => $val)
					{
						$str .= '<td>'.$val.'</td>'."\n";
						
					}
					$str .= '</tr>'."\n";$i++;
				}
			}
		}
		$str .= '</table>';
		return $str;
	}
	function getJson()
	{
		return json_encode($this->data);
	}
	function toString()
	{
		return base64_encode(serialize($this));
	}
	function toFile($filename)
	{
		$file = new File($filename);
		$file->write($this->toString());
		return true;
	}
	
	function fromFile($filename)
	{
		$file = new File($filename);
		$this->fromString($file->read());
		return true;
	}
	function fromString($str)
	{
		$temp = unserialize(base64_decode($str));
		
		$this->count= $temp->count;;
		$this->data= $temp->data;
		$this->message= $temp->message;
		$this->columns= $temp->columns;
		$this->countColumns= $temp->countColumns;
		$this->recordPosition= $temp->recordPosition;
		$this->populationStatus= $temp->populationStatus;
		$this->encription= $temp->encription;
		$this->encrptCode= $temp->encrptCode;
		$this->distinctFilterStatus= $temp->distinctFilterStatus;
		$this->distinctColumnIndex= $temp->distinctColumnIndex;		

	}
	function getXml($unequeRowTag=false)
	{
		$str = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
		$str .= '<root>'."\n";
		$str .= '<columns>'."\n";
		$i=0;
		foreach($this->columns as $col)
		{
			$str .= '<col_'.$i.'>'.$col->getName().'</col_'.$i.'>'."\n";
			$i++;
		}
		$str .= '</columns>'."\n";
		$str .= '<count>'.$this->count.'</count>'."\n";
		$str .= '<rows>'."\n";
		if($this->populationStatus==true)
		{
			for($i=0;$i<$this->count;$i++)
			{
				if($unequeRowTag)
				{
					$str .= "\t".'<row_'.$i.'>'."\n";
				}
				else
				{
					$str .= "\t".'<row>'."\n";
				}
				foreach($this->columns as $col)
				{
					$str .= "\t\t".'<'.$col->getName().'>"'.strip_tags($this->data[$i][strtoupper($col->getName())]).'"</'.$col->getName().'>'."\n";
				}
				if($unequeRowTag)
				{
					$str .= "\t".'</row_'.$i.'>'."\n";
				}
				else
				{
					$str .= "\t".'</row>'."\n";
				}
			}
		}
		else
		{
			if($this->database)
			{
				$i=0;
				while($row = $this->getDBRow())				
				{
					if($unequeRowTag)
					{
						$str .= "\t".'<row_'.$i.'>'."\n";
					}
					else
					{
						$str .= "\t".'<row>'."\n";
					}
					foreach($row as $key => $val)
					{
						$str .= "\t\t".'<'.$key.'>'.$val.'</'.$key.'>'."\n";
						
					}
					if($unequeRowTag)
					{
						$str .= "\t".'</row_'.$i.'>'."\n";
					}
					else
					{
						$str .= "\t".'</row>'."\n";
					}
					$i++;
				}
			}
		}
		$str .= '</rows>'."\n";
		$str .= '</root>';
		return $str;
	}
	
	function exportXML($filename="",$unequeRowTag=false)
	{
		
		$file = new File($filename);
		$file->writeUTF();

		$file->append('<?phpxml version="1.0"?>'."\n");
		$file->append('<recordset>'."\n");
		foreach($this->columns as $col)
		{
			$file->append('<col>'.$col->getName().'</col>'."\n");
		}
		if($this->populationStatus==true)
		{
			for($i=0;$i<$this->count;$i++)
			{
				$file->append('<row>'."\n");
				if($unequeRowTag)
				{
					$file->append("\t".'<row_'.$i.'>'."\n");
				}
				else
				{
					$file->append("\t".'<row>'."\n");
				}
				foreach($this->columns as $col)
				{
					$file->append("\t\t".'<'.$col->getName().'>'.$this->data[$i][$col->getName()].'</'.$col->getName().'>'."\n");
				}
				if($unequeRowTag)
				{
					$file->append("\t".'</row_'.$i.'>'."\n");
				}
				else
				{
					$file->append("\t".'</row>'."\n");
				}
			}
		}
		else
		{
			if($this->database)
			{
				while($row = $this->getDBRow())				
				{
					//$file->writeCSV(array($this->data[$this->count]));
					if($unequeRowTag)
					{
						$file->append("\t".'<row_'.$i.'>'."\n");
					}
					else
					{
						$file->append("\t".'<row>'."\n");
					}
					foreach($row as $key => $val)
					{
						$file->append("\t\t".'<'.$key.'>'.$val.'</'.$key.'>'."\n");
					}
					if($unequeRowTag)
					{
						$file->append("\t".'</row_'.$i.'>'."\n");
					}
					else
					{
						$file->append("\t".'</row>'."\n");
					}
				}
			}
		}
		$file->append('</recordset>');
	}
	function getCurrentRow()
	{
		
		return $this->data[$this->recordPosition];
	}
	function get($name)
	{
		return $this->data[$this->recordPosition][$name];
	}
	function count()
	{
			return $this->count;	
	}
	function free()
	{
		if($this->database->resource!=null)
			$this->database->resultFree();
		else
		{
			$this->message->add("Error","Database statment resource id is null");
			return false;
		}
	}
	function getMessage()
	{
		return $this->message;
	}
	function createJsObject($name)
	{
		$obj = array();
		$obj['count'] = $this->count;
		$obj['data'] = $this->data;
		$obj['countColumns'] = $this->countColumns;
		$obj['encrptCode'] = $this->encrptCode;
		echo '<script type="text/javascript">';
		//echo "var $name = unserialize('".str_replace("'","\\'",serialize($obj))."');";
		echo "var $name = ".json_encode($obj);
		echo '</script>';

	}
	function findRow($col,$val)
	{
		$col = strtoupper($col);
		if($this->populationStatus==true)
		{
			for($i=0;$i<$this->count;$i++)
			{
				if($this->data[$i][$col]==$val)
				{
					return $this->data[$i];
				}
			}
		}
		return false;
	}
	function showCLITable()
	{
		$colSize=array();
		for($j=0;$j<count($this->columns);$j++)
		{
			$l = strlen($this->columns[$j]->getName());
			for($i=0;$i<$this->count;$i++)
			{
				if($l<strlen($this->data[$i][$this->columns[$j]->getName()]))
				{
					$l=strlen($this->data[$i][$this->columns[$j]->getName()]);
				}
				
			}
			$colSize[$j]=$l;
		}
		
		
		
		echo "|";
		for($j=0;$j<count($this->columns);$j++)
		{
			echo str_pad("", $colSize[$j], "=", STR_PAD_RIGHT); 
			if($j<count($this->columns)-1)
			{
				echo "=|=";
			}
		}
		echo "|\n";
		echo "|";
		for($j=0;$j<count($this->columns);$j++)
		{
			echo str_pad($this->columns[$j]->getName(), $colSize[$j], " ", STR_PAD_RIGHT); 
			if($j<count($this->columns)-1)
			{
				echo " | ";
			}
		}
		echo "|\n";
		echo "|";
		for($j=0;$j<count($this->columns);$j++)
		{
			echo str_pad("", $colSize[$j], "-", STR_PAD_RIGHT); 
			if($j<count($this->columns)-1)
			{
				echo "-|-";
			}
		}
		echo "|\n";
		for($i=0;$i<$this->count;$i++)
		{
			echo "|";
			for($j=0;$j<count($this->columns);$j++)
			{
				echo str_pad($this->data[$i][$this->columns[$j]->getName()], $colSize[$j], " ", STR_PAD_RIGHT); 
				if($j<count($this->columns)-1)
				{
					echo " | ";
				}
			}
			echo "|\n";
			
		}
		echo "|";
		for($j=0;$j<count($this->columns);$j++)
		{
			echo str_pad("", $colSize[$j], "=", STR_PAD_RIGHT); 
			if($j<count($this->columns)-1)
			{
				echo "=|=";
			}
		}
		echo "|\n";
	}
	
}
?>