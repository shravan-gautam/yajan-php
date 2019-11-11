<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Meter extends UIObject
{
	var $events;
	var $min;
	var $max;
	var $showValue;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->type="";
		$this->tag="meter";
		$this->min="";
		$this->max="";
		$this->showValue=true;
		$this->events = array();
	}
	function showValue($val)
	{
		$this->showValue=false;
	}
	function setMin($m)
	{
		$this->min=$m;
	}
	function setMax($m)
	{
		$this->max=$m;
	}
	function rander($echo=true)
	{
		if($this->min!="")
		{
			$this->property .= ' min="'.$this->min.'"';
		}
		if($this->max!="")
		{
			$this->property .= ' max="'.$this->max.'"';
		}
		
		$r = parent::rander($echo);
		$sp = new Span($this->id."_valueSpan");
		$sp->setValue($this->value);
		$sp->addClass("valueCss");
		$sp->setValueFormat($this->valueFormat);
		$r.=$sp->rander($echo);
		return $r;
	}
}
?>
