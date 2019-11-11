<?php
require_once("$LIB_PATH/php/data/format.php");
class EmailFormat  extends Format
{
	function __construct()
	{
		parent::__construct("number");
	}
	function getFormatedValue($email)
	{
		if($this->hint)
		{

			$s1 = explode("@",$email);
			$val = $s1[0];
			$s = $val[0].str_repeat("*", strlen($val)-3).substr($val,strlen($val)-2,strlen($val)-1)."@".$s1[1];
		}
		else
		{
			$s = $email;
		}
		return $s;
	}
	function showAsHint($val)
	{
		$this->hint = $val;
	}
}
?>