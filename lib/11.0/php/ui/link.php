<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Link extends UIObject
{
	var $events;
	var $link;
	var $target;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->type="";
		$this->tag="a";
		$this->raget="";
		$this->setUrl("javascript:");
		$this->events = array();
	}
	function setTarget($t)
	{
		$this->target = $t;
	}
	function rander($echo=true)
	{
		
		$this->property .=' href="'.$this->link.'" ';
		if($this->target!="")
		{
			$this->property .=' href="'.$this->link.'" target="'.$this->target.'"';
		}
		$this->innerHTML = $this->value;
		return parent::rander($echo);
	}
}
?>