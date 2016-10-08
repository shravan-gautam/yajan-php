<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Button extends UIObject
{
	var $events;
	function Button($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="";
		$this->tag="button";
		$this->events = array();
	}
	function setType($type)
	{
		$this->type = $type;
		if($type=="submit")
		{
			$this->tag = "input";
		}
	}
	function rander($echo=true)
	{
		$r = parent::rander($echo);
		return $r;
	}
}
?>
