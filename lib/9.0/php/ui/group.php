<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Group extends UIObject
{
	var $events;
	var $element;
	function Group($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="text";
		$this->tag="input";
		$this->events = array();
		$this->element=array();
	}
	function add($el)
	{
		$this->element[count($this->element)]=$el;
	}
	function rander($echo=true)
	{
		echo '<script type="text/javascript" language="javascript">'.$this->id.' = new Group();</script>';
		for($i=0;$i<count($this->element);$i++)
		{
			$this->element[$i]->setName($this->id);
			$this->element[$i]->rander($echo);
			echo '<script type="text/javascript" language="javascript">'.$this->id.'.add('.$this->element[$i]->getId().');</script>';
		}

	}
}
?>