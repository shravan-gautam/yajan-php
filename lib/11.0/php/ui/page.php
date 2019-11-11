<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Page extends UIObject
{
	var $events;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->tag="div";
		$this->events = array();
		$this->addClass("printPage");
	}
	function showBorder($v=true)
	{
		if($v==true)
		{
			$this->addCss("border","1px solid #000000");
		}
	}
}
?>