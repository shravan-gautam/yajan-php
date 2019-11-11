<?php
class StringFormat extends Format
{
	function __construct()
	{
		parent::__construct("string");
	}
	function getType($str)
	{
		$p1 = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/';
		$p2="/^[1-9][0-9]*$/"; 
		$cType="";
		$output = array();
		if (preg_match($p1, $str)) 
		{
			$cType = "EMAIL";
		}
		else if(preg_match($p2, $str))
		{
			$cType = "MOBILE";
		}	
		return $cType;		
	}
	function getFormatedValue($str)
	{
		$str = parent::getFormatedValue($str);
		return $str;
	}
}
?>