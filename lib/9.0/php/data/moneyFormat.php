<?php
require_once("$LIB_PATH/php/data/format.php");
class MoneyFormat extends Format
{
	var $reverceValue;
	var $nigativeSymbol;
	var $absoluteSign;
	public function MoneyFormat()
	{
		global $CURRENCY_MODE;
		if(!isset($CURRENCY_MODE))
		{
			$CURRENCY_MODE="IN";
		}
		parent::Format("number");
		$this->decimalDigits = 2;
		$this->numberSaprator = "";
		$this->decimalSaprator = ".";
		$this->numberSaprator = ",";
		$this->reverceValue=false;
		$this->nigativeSymbol=false;
		$this->absoluteSign=false;
		$this->formatMode=$CURRENCY_MODE;
	}
	public function formatMode($mode) // mode=EN/IN
	{
		$this->formatMode=$mode;
	}
	public function reverceValue($v=true)
	{
		$this->reverceValue = $v;
	}
	function nigativeSymbol($v=true)
	{
		$this->nigativeSymbol = $v;
	}
	function absoluteSign($v=true)
	{
		$this->absoluteSign=$v;
	}
	function moneyFormatIndia($num)
	{
		$ng=false;
		if($num<0)
		{
			$num=0-$num;
			$ng=true;
		}
		$explrestunits = "" ;
		$num=preg_replace('/,+/', '', $num);
		$words = explode(".", $num);
		$des="00";
		if(count($words)<=2)
		{
			$num=$words[0];
			if(count($words)>=2){$des=$words[1];}
			if(strlen($des)<2){$des="$des"."0";}else{$des=substr($des,0,2);}
			
		}
		if(strlen($num)>3)
		{
			$lastthree = substr($num, strlen($num)-3, strlen($num));
			$restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
			$restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
			$expunit = str_split($restunits, 2);
			for($i=0; $i<sizeof($expunit); $i++)
			{
				// creates each of the 2's group and adds a comma to the end
				if($i==0)
				{
					$explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
				}else{
					$explrestunits .= $expunit[$i].",";
				}
			}
			$thecash = $explrestunits.$lastthree;
		} else 
		{
			$thecash = $num;
		}
		if($ng)
		{
			$thecash="-$thecash";
		}
		return "$thecash.$des"; // writes the final format where $currency is the currency symbol.

	}	
	public function formatCurrency($val)
	{
		if($this->formatMode=="IN")
		{
			return $this->moneyFormatIndia($val);
		}
		else if($this->formatMode=="EN")
		{
			return number_format($val, $this->decimalDigits, $this->decimalSaprator, $this->numberSaprator);
		}
	}
	public function getFormatedValue($val)
	{
		
		
		if($this->type=="number")
		{
			
			if($this->reverceValue)
			{
				$val = 0-$val;
			}
			$out = $val;
			$out = $this->formatCurrency($val);
			if($this->unit!="")
			{
				if($this->unitPosition=="left")
				{
					$out = $this->unit." ".$out;
				}
				else
				{
					$out = $out." ".$this->unit;
				}
				
			}
			
			$out = trim($out);
			if($this->nigativeSymbol)
			{
				if($val<0)
				{
					$out = str_replace("-","",$out);
					$out = "($out)";
				}
			}
			if($this->absoluteSign)
			{
				$out = str_replace("-","",$out);
			}
			return $out;
		}
		
	}
}
?>