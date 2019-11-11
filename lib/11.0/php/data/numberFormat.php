<?php
require_once("$LIB_PATH/php/data/format.php");
class NumberFormat extends Format
{
	public function __construct()
	{
		parent::__construct("number");
		$this->decimalDigits = 2;
		$this->numberSaprator = "";
		$this->decimalSaprator = ".";
	}
	public function getFormatedValue($val)
	{
		$val = parent::getFormatedValue($val);
		if(!is_numeric($val))
		{
			return $val;
		}
		if($this->type=="number")
		{
			return number_format($val, $this->decimalDigits, $this->decimalSaprator, $this->numberSaprator);
		}
	}
}
?>
