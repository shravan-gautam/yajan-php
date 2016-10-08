<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class CheckBox extends UIObject
{
	var $events;
	var $checked;
	function CheckBox($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="checkbox";
		$this->tag="input";
		$this->events = array();
		$this->cols=30;
		$this->rows=4;
		$this->checked=false;
	}
	function setChecked($val)
	{
		$this->checked=$val;
	}
	function rander($idMode=true)
	{
		if($this->checked)
		{
			$this->property = ' checked="checked" ';
		}
		else
		{
			$this->property = ' ';
		}
		return parent::rander($idMode);
	}
}
?>