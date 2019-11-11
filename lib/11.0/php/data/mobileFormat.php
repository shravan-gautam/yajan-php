<?php
require_once("$LIB_PATH/php/data/format.php");
class MobileFormat  extends Format
{
	function __construct()
	{

		parent::__construct("number");
	}
	function getFormatedValue($val)
	{

		$val = parent::getFormatedValue($val);
		if($this->hint)
		{
			if(strlen($val)>3)
			{
				$s = substr($val,0,2).str_repeat("*", strlen($val)-4).substr($val,strlen($val)-2,strlen($val)-1);
			}
			else
			{
				$s = $val[0]."*".$val[strlen($val)-1];
			}
		}
		else
		{
			$s = $val;
		}
		return $s;
	}
	function showAsHint($val)
	{
		$this->hint = $val;
	}
}
?>
