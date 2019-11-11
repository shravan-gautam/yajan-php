<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class RadioButton extends UIObject
{
	var $events;
	var $checked;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->type="radio";
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
	function rander($echo=true)
	{
		if($this->checked)
		{
			$this->property = ' checked="checked" ';
		}
		else
		{
			$this->property = ' ';
		}
		return parent::rander($echo);
	}
}
?>