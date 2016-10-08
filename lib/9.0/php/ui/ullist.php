<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
require_once("$LIB_PATH/php/ui/tablecolumn.php");
class ULList extends UIObject
{
	var $events;
	var $recordset;
	var $columnMap;
	var $columns;

	function ULList($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="";
		$this->tag="ul";
		$this->events = array();
		$this->columnMap=array();
		$this->columns=array();
		$this->addColumns("li");
		
		$this->setColumnClass("li",new Span($id));
	}

	
	function bindWith($rname,$tname,$tproperty='val')
	{
		$tname = strtoupper($tname);
		$tname= str_replace(" ","_",$tname);
		$temp=array();
		$temp['name']=strtoupper($rname);
		$tmep['property']=$tproperty;
		$this->columnMap[$tname]=$temp;
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$tname)
			{
				$this->columns[$i]->setPropertyMap($tproperty,$rname);
			}
		}
	}
	function setRecordset(Recordset $rec)
	{
		$this->recordset = $rec;
	}
	function addColumns()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();
		for($i=0;$i<$numargs;$i++)
		{
			
			$this->columns[$i]=new TableColumn($arg_list[$i],new Span($arg_list[$i])); 
		}
	}
	function setColumnClass($name,$class)
	{
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->setClass($class);
			}
		}
	}

	function createJsObject()
	{
		$js='';
		$obj = $this->id;
		$js.="var $obj = new Table('".$this->id."');
		$obj".".setRowCount(".$this->recordset->count().");";
		for($i=0;$i<count($this->columns);$i++)
		{
			$js.="$obj".".addColumn('".$this->id."_".$this->columns[$i]->getName()."');\n";
		}
		return $js;
	}
	function createInnerHTML()
	{
		$this->innerHTML='';
		if(!$this->recordset->isPopulated())
		{
			$c = 0;
			while($row = $this->recordset->getDBRow())
			{
				for($i=0;$i<count($this->columns);$i++)
				{
					$obj =  clone $this->columns[$i]->getClass();
					$obj->setId($this->id."_".$this->columns[$i]->getName()."_".$c);	
					$obj->setEcho(false);			
					for($x=0;$x<count($this->columns[$i]->propertyMap);$x++)
					{
						if($this->columns[$i]->propertyMap[$x]['property']=="val")
						{
							$obj->setValue($row[$this->columns[$i]->propertyMap[$x]['column']]);	
						}
						else if($this->columns[$i]->propertyMap[$x]['property']=="href")
						{
							$obj->setUrl($row[$this->columns[$i]->propertyMap[$x]['column']]);	
						}
						else if($this->columns[$j]->propertyMap[$x]['property']=="name")
						{
							$obj->setName($row[$this->columns[$j]->propertyMap[$x]['column']]);	
						}
						else
						{
							$obj->property .= ' '.$this->columns[$i]->propertyMap[$x]['property'].'="'.$row[$this->columns[$i]->propertyMap[$x]['column']].'" ';
						}
					}
					
					$this->innerHTML .= "<li>". $obj->rander() ."</li>\n";
					$c++;
				}

			}
		}
		else
		{
			
			if($this->recordset->count()>0)
			{
				for($i=0;$i<$this->recordset->count();$i++)
				{	

					
					
					$this->recordset->moveRecord($i);
					$row = $this->recordset->getCurrentRow();
					
					for($j=0;$j<count($this->columns);$j++)
					{
						$obj = clone $this->columns[$j]->getClass();//($this->columns[$j]->getName()."_".$i);
						
						//$obj = new $obj($this->columns[$j]->getName()."_".$i);
						$obj->setId($this->id."_".$this->columns[$j]->getName()."_".$i);
						$obj->setEcho(false);
						//print_r(($this->columns[$j]->propertyMap));
						for($x=0;$x<count($this->columns[$j]->propertyMap);$x++)
						{
							//print_r( $this->columns[$j]->propertyMap[$x]['property']);
		
							if($this->columns[$j]->propertyMap[$x]['property']=="val")
							{
								
								$obj->setValue($row[$this->columns[$j]->propertyMap[$x]['column']]);	
							}
							else if($this->columns[$j]->propertyMap[$x]['property']=="href")
							{
								$obj->setUrl($row[$this->columns[$j]->propertyMap[$x]['column']]);	
							}
							else if($this->columns[$j]->propertyMap[$x]['property']=="name")
							{
								$obj->setName($row[$this->columns[$j]->propertyMap[$x]['column']]);	
							}
							else
							{
								$obj->property .= ' '.$this->columns[$j]->propertyMap[$x]['property'].'="'.$row[$this->columns[$j]->propertyMap[$x]['column']].'" ';
							}
							
						}
						
						$this->innerHTML .= "<li>". $obj->rander() ."</li>\n";
					}

					
				}
			}
		}
	}

}
?>