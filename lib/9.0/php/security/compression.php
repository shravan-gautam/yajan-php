<?php
//ini_set('memory_limit', '-1');
class Compression
{
	var $level;
	function Compression()
	{
		$this->level = 9;
	}
	function compress($string)
	{
		if($this->level>0)
		{
			return gzencode($string,$this->level);
			//return gzdeflate($string,9);
		}
		else
		{
			return $string;
		}
	}
	function uncompress($string)
	{
		if($this->level>0)
		{
			$v = @gzinflate(substr($string,10,-8)); 
			
			//$v = @gzinflate($string,9);
			if($v!==false)
			{
				return $v;
			}
			else
			{
				return "Error in uncompression or decription";
			}
		}
		else
		{
			return $string;
		}
	}
}
?>