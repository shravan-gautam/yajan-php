<?php
class DBTable extends Recordset
{
	var $table;
	var $db;
	var $newId;
	var $query;
	function DBTable($name,$database)
	{
		parent::Recordset();
		$this->table = $name;
		$this->db=$database;
		$this->newId = null;
	}
	function setTable($name)
	{
		$this->table=$name;
	}
	function getColumns()
	{
		if($this->db->getDriver()=="mysql")
		{
			$q="select * from $this->table limit 1,1";
		}
		else if($this->db->getDriver()=="oracle")
		{
			$q="select * from $this->table where rownum < 2";
		}
		$r = $this->db->execute($q);
		
		return $r->columns;
	}
	function getMax($column,$init="1")
	{
		$sql="select nvl(max($column),0) as maxval from $this->table";
		$find=$this->db->execute($sql);
		$maxVal=$find->data[0]['MAXVAL'];
		if(!$maxVal)
		{
			$maxVal=$init;
		}
		return $maxVal;
	}
	function importRecordset($r)
	{
		$this->count = $r->count;
		$this->data = $r->data;
		$this->columns = $r->columns;
		$this->countColumns = $r->countColumns;
		$this->recordPosition = 0;
		$this->populationStatus = true;
		$this->encription = $r->encription;
		$this->encrptCode = $r->encrptCode;
		
	}
	function getNextVal($column,$init=0)
	{
		$val = $this->getMax($column,$init);
		return ($val+1);
	}
	private function makeInsertQuery()
	{
		$col="";
		$value="";
		foreach($this->columns as $val)
		{
			$n = $val->getDbName();
			if($n!="")
			{
				$col.=$n.",";
				
				if($val->getType()=="DATE")
				{
					
					if($val->getFormat()!="")
					{
						$value.="to_date('".$this->data[$this->recordPosition][strtoupper($val->getName())]."','".$val->getFormat()."'),";
					}
					else
					{
						$value.="'".$this->data[$this->recordPosition][strtoupper($val->getName())]."',";
					}
				}
				else
				{
					$value.="'".$this->data[$this->recordPosition][strtoupper($val->getName())]."',";
				}
			}
		}
		$col = rtrim($col,",");
		$value = rtrim($value,",");
		$q="insert into ".$this->table."($col) values($value)";
		return $q;
	}
	function getNewId()
	{
		$obj = null;
		
		foreach($this->columns as $col)
		{
			if($col->getKey()=="PRIMERY")
			{
				$obj=$col;
			}
		}
		if($obj!=null)
		{
			if($obj->getType()!="NUMBER")
			{
				$this->message->add("Error","Primery key column is not number format.");
				return false;
			}
			$name = $obj->getName();

			if(strtoupper($this->db->parameter['driver'])==strtoupper("ORACLE"))
			{
				$maxFun="nvl(max($name),0)+1 as total";
			}
			else
			{
				$maxFun="ifnull(max($name),0)+1 as total";
			}
			$q="select $maxFun from ".$this->table;
			$r = $this->db->execute($q);
			if($r)
			{
				$r = $r->getRow();
				$this->newId = $r['TOTAL'];
				$this->data[$this->recordPosition][strtoupper($name)]=$this->newId;
			}
			else
			{
				$this->message->add("Error","Query error during getting new id.");
				return false;
			}
			return true;
		}
		else
		{
			$this->message->add("Error","No primery key found.");
			return false;
		}
	}
	
	function query($query="")
	{
		if($query!="")
		{
			$this->query=$query;
		}
		if($this->query=="")
		{
			$from = "";
			foreach($this->columns as $col)
			{
				$from.=$col->getName().",";
			}
			$from = rtrim($from,",");
			$q="select $from from ".$this->table;

		}
		else
		{
			$q=$this->query;
		}
		return $this->db->execute($q);
	}
	function countColumnValue($name)
	{
		$q="select count(*) as total from ".$this->table." where $name = '".$this->data[$this->recordPosition][strtoupper($name)]."'";
		$t=$this->db->execute($q);
		return $t->data[0]['TOTAL'];
	}
	function createJsObject($name)
	{
		$str = serialize($this);
		echo '<script type="text/javascript">var '.$name.' = new Recordset();'.$name.'.load('."'$str'".'); </script>';
	}
}
?>