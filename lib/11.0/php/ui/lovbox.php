<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class LovBox extends UIObject
{
	var $events;
	var $button;
	function __construct($id)
	{
		global $application,$module;
		parent::__construct();
		$this->id=$id;
		$this->type="hidden";
		$this->tag="input";
		$this->events = array();
		$this->button=new LovButton($id."_lovButton");
		$this->button->setUrl("/$application/$module/$id");
	}
	function rander($echo=true)
	{
		parent::rander($echo);
		//$b=new LovButton($id."_lovButton");
		
		//$e = $this->button->getEventParameter();
		//$e->addParameter("country");
		
		//$this->button->addJsEvent("onClick","onClickitemlistLovButton");
		$this->button->rander($echo); 
	}
}
?>