<?php
class UICSS
{
	var $css;
	function __construct()
	{
		$this->css = array();
	}
	function addCss($name,$value)
	{
		$this->css['value']=$value;
		$this->css['name']=$name;
	}
}
?>