<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class TextArea extends UIObject
{
	var $events;
	var $cols;
	var $rows;
	function TextArea($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="text";
		$this->tag="textarea";
		$this->events = array();
		$this->cols=30;
		$this->rows=4;
	}
	function rander($echo=true)
	{
		$this->property=' cols="'.$this->cols.'" rows="'.$this->rows.'" ';
		parent::rander($echo);
	}
	function setColumns($col)
	{
		$this->cols=$col;
	}
	function setRows($row)
	{
		$this->rows=$row;
	}
}
?>