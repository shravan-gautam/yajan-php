<?php
class FormBuilderDatablock
{
	var $form;
	var $name;
	var $path;
	var $property;
	var $phpPrifix;
	var $items;
	var $triggers;
	function FormBuilderDatablock($form,$name)
	{
		
		$this->phpPrifix = "";
		$this->form = $form;
		$this->name = $name;
		$p = $this->form->path;
		
		$this->path = "$p/datablock/$this->name";
		if(file_exists("$this->path/property.php"))
		{
			include "$this->path/property.php";
			$this->property = $datablockProperty;
		}
		else
		{
			//print_r(debug_backtrace());
		}
		$this->items= $this->_getItems();
		$this->triggers = $this->_getTriggers();
	}
	function getProperty($name)
	{
		return $this->property[$name];
	}
	function getPhpClassMemberVars()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			$v = $l->getPhpMemberName();
			$str.= '	var '."$v;\n";
		}
		return $str;
	}
	function getPhpClassVarsStr()
	{
		
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			$v = $l->getPhpName();
			$str.= '	var '."$v;\n";
		}
		return $str;
	}
	
	function getPhpClassVarsInitValStr()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			$v = $l->getPhpName();
			$initVal = $l->getProperty("initValue");
			$str.= '		'."$v='';\n";
		}
		return $str;
	}
	function getClassFunctionParm()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			$v = $l->name;
			$str .= '$'.$v.",";
		}
		$str = rtrim($str,",");
		return $str;
	}
	function getClassFunctionPrimeryParm()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			if($l->getProperty("primary")==true)
			{
				$v = $l->name;
				$str .= '$'.$v.",";				
			}
		}
		$str = rtrim($str,",");
		return $str;
	}
	function getPouplatedValuSet()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			$v = $l->name;
			$str .= '			$this->'.$v.'=$r->data[0]["'.$v.'"];'."\n";				
		}
		$str = rtrim($str,",");
		return $str;
	}
	function getClassFunctionParmInit($primery=false)
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$l = $this->items[$i];
			if($primery== true)
			{
				if($l->getProperty("primary")==true)
				{
					$v = $l->name;
					$str .= '		$this->'.$v."=$".$v.";\n";
				}
			}
			else
			{
				$v = $l->name;
				$str .= '		$this->'.$v."=$".$v.";\n";
			}
		}
		$str = rtrim($str,",");
		return $str;
	}	
	
	
	function getPhpTriggerString()
	{
		$str = "";
		for($i=0;$i<count($this->triggers);$i++)
		{
			$l = $this->triggers[$i];
			$v = $l->data;
			$v = str_replace("<?php","",$v);
			$v = str_replace("?>","",$v);
			
			$str.= $v;
		}
		return $str;
	}
	function getItems()
	{
		return $this->items;
	}
	function _getItems()
	{
		$path = $this->path."/items";
		$p  = new Path($path);
		$list = $p->getRecordset();
		$ar = array();
		$p = new FormBuilderItem($this,$list->data[0]["NAME"]);
		for($i=0;$i<$list->count;$i++)
		{
			$ar[$i] = new FormBuilderItem($this,$list->data[$i]["NAME"]);
		}
		return $ar;
	}

	function getInsertString()
	{
		$c1 = $this->getItemDBColumns();
		$c2 = $this->getItemVariables();
		$table = $this->getProperty("tableName");
		$q="insert into $table($c1) values($c2)";
		return $q;
	}
	function getUpdateString()
	{
		$str ="update ".$this->getProperty("tableName")." set ";
		$str2 = "";
		for($i=0;$i<count($this->items);$i++)
		{
			
			$item = $this->items[$i];
			if($item->getProperty("primary")==false)
			{
				
				$str .= $item->getProperty("dbName")."=".$item->getSqlValue().", ";
			}
			else
			{
				$str2 .= $item->getProperty("dbName").'='.$item->getSqlValue()." AND ";
			}
		}
		$str = rtrim($str,", ");
		$str2 = rtrim($str2,"AND ");
		$str .= " where 1=1 and $str2";
		return $str;
	}
	function getDeleteString()
	{
		$str ="delete from ".$this->getProperty("tableName");
		$str2 = " ";
		for($i=0;$i<count($this->items);$i++)
		{
			$item = $this->items[$i];
			if($item->getProperty("primary")==true)
			{
				$str2 .= $item->getProperty("dbName").'='.$item->getSqlValue()." AND ";
			}
		}
		$str = rtrim($str,",");
		$str2 = rtrim($str2,"AND ");
		$str .= " where 1=1 and $str2";
		return $str;
	}
	function getSelectString()
	{
		$str ="select * from ".$this->getProperty("tableName");
		$str2 = " ";
		for($i=0;$i<count($this->items);$i++)
		{
			$item = $this->items[$i];
			if($item->getProperty("primary")==true)
			{
				$str2 .= $item->getProperty("dbName").'='.$item->getSqlValue()." AND ";
			}
		}
		$str = rtrim($str,",");
		$str2 = rtrim($str2,"AND ");
		$str .= " where 1=1 and $str2";
		return $str;
	}
	function getItemVariables()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$str .= $this->items[$i]->getSqlValue().',';
		}
		$str= rtrim($str,',');
		return $str;
	}
	function getItemColumns()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$str .= $this->items[$i]->getPhpName().',';
		}
		$str= rtrim($str,',');
		$str = str_replace('$this->',"",$str);
		echo $str;
		return $str;
	}
	function getItemDBColumns()
	{
		$str = "";
		for($i=0;$i<count($this->items);$i++)
		{
			$str .= $this->items[$i]->getSqlName().',';
		}
		$str= rtrim($str,',');


		return $str;
	}
	function _getTriggers()
	{
		$path = $this->path."/trigger";
		$p  = new Path($path);
		$p->setExt("php");
		$list = $p->getRecordset();
		$ar = array();
		for($i=0;$i<$list->count;$i++)
		{
			$ar[$i] = new FormBuilderTrigger($this,$list->data[$i]["NAME"]);
		}
		return $ar;
	}
	
}
?>