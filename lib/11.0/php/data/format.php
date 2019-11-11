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
	var $hint=false;
	var $valueSequance;
	public function __construct($type)
	{
		$this->type = $type;
		$this->formatString = "";
		$this->unit="";
		$this->unitPosition="left";
		$this->unitAlign="left";
		$this->valueSequance="";
	}
	function setValueSequance($seq)
	{
		$this->valueSequance = $seq;
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

		return $this->reArrangeValue($val);
	}
	public function reArrangeValue($val)
	{
		if($val!="")
		{
			//echo $val."\n";
			if($this->valueSequance!="")
			{
				$str = "";
				$i1=0;
				$i2=0;
				$l1 = strlen($val);
				$l2 = strlen($this->valueSequance);
				$rp = floor($l1/$l2);
				str_repeat($this->valueSequance, $rp);


				$of1 = $l1%$l2;
				while($i1<$l1)
				{
					$i2=$i1%$l2;
					$cl = floor($i1/$l2-1)+1;
					$p1 = ($this->valueSequance[$i2])+($l2*$cl);
					//echo $cl.".".$i1.".".$p1.".".($l1-$i1).".";
					if($i1> $l1-$of1-1)
					{
						$str.=$val[$i1];
						//echo $val[$i1]."*";
					}
					else
					{
						$str.=$val[$p1];
						//echo $val[$p1];
					}
					//echo "\n";

					$i1++;
				}
				$val = $str;
			}
		}
		return $val;
	}
}
?>
