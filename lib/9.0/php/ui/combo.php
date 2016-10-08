<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class ComboBox extends UIObject
{
	var $recordset;
	private $columnMap;
	function ComboBox($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="";
		$this->tag="select";
		$this->events = array();
		$this->recordset=new Recordset();
		$this->columnMap = array();
		$this->columnMap['name']='NAME';
		$this->columnMap['code']='CODE';
		$this->recordset->addColumns("code","name");
	}
	
	function bindValueWith($name)
	{
		$this->columnMap['code']=strtoupper($name);
	}
	function bindLableWith($name)
	{
		$this->columnMap['name']=strtoupper($name);
	}
	
	function add($name,$value='')
	{
		$this->recordset->add($value,$name);
	}

	function createInnerHTML()
	{
		$this->innerHTML='';
		if($this->tag=="select")
		{
			
			if(!$this->recordset->isPopulated())
			{
				while($row = $this->recordset->getDBRow())
				{
					
					$name = $row[$this->columnMap['name']];
					$val  = $row[$this->columnMap['code']];
					if($val=='')
					{
						$val=$name;
					}
					$selected="";
					if($this->value == $val)
					{
						$selected = ' selected="selected" ';
					}
					$this->innerHTML.='<option value="'.$val.'" '.$selected.' >'.$name.'</option>';
				}
			}
			else
			{
				
				if($this->recordset->count()>0)
				{
					for($i=0;$i<$this->recordset->count();$i++)
					{	
						$this->recordset->moveRecord($i);
						$name = $this->recordset->get($this->columnMap['name']);
						
						$val  = $this->recordset->get($this->columnMap['code']);
						if($val=='')
						{
							$val=$name;
						}
						$selected="";
						if($this->value == $val)
						{
							$selected = ' selected="selected" ';
						}
						$this->innerHTML.='<option value="'.$val.'" '.$selected.' >'.$name.'</option>';
					}
				}
			
			}
		}
	}
	
}
?>