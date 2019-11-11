<?php
class Barcode
{
	var $val;
	var $font;
	var $size;
	var $showLable;
	function __construct($font="")
	{
		$this->font="class1";
		if($font!="")
		{
			$this->font=$font;
		}
		$this->size = "28";
		$this->showLable=false;
	}
	function setSize($s)
	{
		$this->size=$s;
	}
	function showLable($v=true)
	{
		$this->showLable = $v;
	}
	function show($val)
	{
		echo '<span class="barcode2" ><span style="font-size:'.$this->size.'px;">*'.$val.'*</span></span>';
		if($this->showLable==true)
		{
			echo '<br><span style="position:relative; top:-15px; background-color:#FFF; padding:1px;font-size:10px;">'.$val.'</span>';
		}
	}
}
?>