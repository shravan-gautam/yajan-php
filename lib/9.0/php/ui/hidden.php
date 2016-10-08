<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class HiddenItem extends UIObject
{
	var $events;
	function HiddenItem($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="hidden";
		$this->tag="input";
		$this->events = array();
	}
}
?>