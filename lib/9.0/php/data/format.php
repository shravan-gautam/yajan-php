<?php
class Format
{
	var $type;
	var $formatString;
	var $decimalDigits;
	var $numberSaprator;
	var $decimalSaprator;
	var $unit;
	var $unitPosition;
	var $unitAlign;
	public function Format($type)
	{
		$this->type = $type;
		$this->formatString = "";
		$this->unit="";
		$this->unitPosition="left";
		$this->unitAlign="left";
	}
	public function setUnit($unit)
	{
		$this->unit = $unit;
	}
	public function setFormatString($str)
	{
		$this->formatString = $str;
	}
	public function setDecimalDigits($d)
	{
		$this->decimalDigits = $d;
	}
	public function setNumberSeprator($s)
	{
		$this->numberSaprator = $s;
	}
	public function getFormatString()
	{
		return $this->formatString;
	}
	public function getFormatedValue($val)
	{
		return $val;
	}
}
?>