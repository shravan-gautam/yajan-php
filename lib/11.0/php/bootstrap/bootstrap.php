<?php

class BootstrapObject 
{
	var $type;
	var $size;
	var $tag;
	var $css;
	public function __construct()
	{
		$this->type ="default";
	}
	function size($size)
	{
		$this->size = $size;
	}
	function type($type)
	{
		$this->type = $type;
	}
	function css($css)
	{
		$this->css = $css;
	}
	function getCss($tag)
	{
		global $BOOTSTRAP_UI_CSS;
		$this->tag = $tag;
		$css = "";
		if(!isset($BOOTSTRAP_UI_CSS)||$BOOTSTRAP_UI_CSS==false)
		{
			return $css;
		}
		if($this->tag == "input")
		{

			$css = "form-control";
			if($this->type!="")
			{
				$css .= ' form-control-'.$this->type;
			}
			if($this->size!="")
			{
				$css .= ' form-control-'.$this->size;
			}
		}
		else if($this->tag == "select")
		{

			$css = "form-control";
			if($this->type!="")
			{
				$css .= ' form-control-'.$this->type;
			}
			if($this->size!="")
			{
				$css .= ' form-control-'.$this->size;
			}
		}
		else if($this->tag == "textarea")
		{

			$css = "form-control";
			if($this->type!="")
			{
				$css .= ' form-control-'.$this->type;
			}
			if($this->size!="")
			{
				$css .= ' form-control-'.$this->size;
			}
		}
		else if($this->tag == "button")
		{
			$css = "btn";
			
			if($this->type!="")
			{
				$css .= ' btn-'.$this->type;
			}
			if($this->size!="")
			{
				$css .= ' btn-'.$this->size;
			}
		}
		$css .= " ".$this->css;
		return $css;
	}
}
class Bootstrap extends UIModule
{

	public function __construct()
	{
		parent::__construct();
		$this->module = "bootstrap";
		$this->cssObject = new BootstrapObject();
	}

}
global $BOOTSTRAP_CSS;
$UI = new Bootstrap();


?>
