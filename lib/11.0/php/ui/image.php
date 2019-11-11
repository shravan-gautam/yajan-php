<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class Image extends UIObject
{
	var $events;
	var $width;
	var $height;
	var $embedStatus;
	function __construct($id,$value='')
	{
		parent::__construct();
		$this->id=$id;
		$this->type="";
		$this->tag="img";
		$this->value=$value;
		$this->w="";
		$this->h="";
		$this->embedStatus = false;
		$this->events = array();
	}
	function setWidth($w)
	{
		$this->width=$w;
	}
	function setHeight($h)
	{
		$this->height=$h;
	}
	function setSize($size='')
	{
		
		$this->width=func_get_arg(0);
		$this->height=func_get_arg(1);
	}
	function setEmbedStatus($val)
	{
		$this->embedStatus=$val;
	}
	function embedFile($filename)
	{
		$data = file_get_contents($filename);
		$this->embedStream($data);
		
	}
	function embedStream($data)
	{
		$data = base64_encode($data);
		$this->jsValueUpdation=false;
		$this->valueType = "bin";
		
		//$this->setValue("data:image/jpeg;base64,".$data);
		$this->property .= ' src="data:image/jpeg;base64,'.$data.'" ';
	}
	function rander($echo=true)
	{
		$w='';
		if($this->width!="")
		{
			$w = ' width="'.$this->width.'" ';
		}
		$h="";
		if($this->height!="")
		{
			$h = ' width="'.$this->height.'" ';
		}
		$this->property .= " $w $h ";
		return parent::rander($echo);
	}
	function setValue($val,$jsObjectValue=true)
	{
		if(!$this->embedStatus)
		{
			parent::setValue($val,$jsObjectValue);
		}
		else
		{
			$this->embedStream($val);
		}
	}
}
?>