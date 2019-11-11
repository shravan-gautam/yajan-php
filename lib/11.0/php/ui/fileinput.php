<?php
class FileInput extends UIObject
{
	var $events;
	var $multiple;
	var $intactive;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->name = $id."[]";
		$this->type="file";
		$this->tag="input";
		$this->events = array();
		$this->multiple=false;
		$this->intactive=false;
	}
	function intractive($val)
	{
		$this->intactive = $val;
	}
	function multiple($val=true)
	{
		$this->multiple=$val;
	}
	function rander($echo=true)
	{
		if($this->multiple==true)
		{
			$this->property .= " multiple ";
		}
		//$this->property .= ' height: 0px;width: 0px; overflow:hidden; ';
		//parent::rander();
		global $UI;
		$class1= $UI->getCss("input");
		$class2= $UI->getCss("button");
		if($this->intactive==true)
		{
		echo '
		<input class="'.$class1.'" readonly="readonly" style="width:'.$this->size.';float:left" name="'.$this->id.'_filename" onclick="selectFile'.$this->id.'()" type="text" id="'.$this->id.'_filename" placeholder="Selected Filename" /><input class="'.$class2.'" type="button" onclick="selectFile'.$this->id.'()" value="Browse" name="'.$this->id.'_browse" style="float:right" />
		<input  '.$this->property.' id="'.$this->id.'" name="'.$this->id.'[]" onchange="onChange'.$this->id.'(this)" type="file" style="height: 0px;width: 0px; overflow:hidden;display:none;" />
		<script type="text/javascript" language="javascript">
		function selectFile'.$this->id.'()
		{
			document.getElementById("'.$this->id.'").click();
		}
		function onChange'.$this->id.'(obj)
		{
			$("#'.$this->id.'_filename").val(obj.value);
		}</script>';
		}
		else
		{
			
			parent::rander($echo);
		}
	}
}
?>