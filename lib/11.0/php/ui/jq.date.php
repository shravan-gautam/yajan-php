<?php
class JQDate extends UIObject
{
	var $events;
	var $suggestions;
	var $format;
	var $source;
	var $minDate;
	var $maxDate;
	var $yearSelection;
	var $editable;
	var $showFormat;
	var $effect;
	var $yearRange;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->type="text";
		$this->tag="input";
		$this->events = array();
		$this->suggestions = array();
		$this->format = "dd/mm/yy";
		$this->source = "";
		$this->yearSelection=false;
		$this->editable=false;
		$this->showFormat = false;
		$this->effect="fadeIn";
		$this->yearRange="";
	}
	function setYearRange($range)
	{
		$this->yearRange = $range;
	}
	function addSuggestions()
	{
		//array_push($this->suggestions,func_get_args());
		$arg = func_get_args();
		for($i=0;$i<count($arg);$i++)
		{
			$this->suggestions[count($this->suggestions)]=$arg[$i];
		}
	}
	function setEffect($effect)
	{
		$this->effect = $effect;
	}
	function showFormat($val)
	{
		$this->showFormat = $val;
	}
	function yearSelection($val)
	{
		$this->yearSelection=$val;
	}
	function editable($val)
	{
		$this->editable = $val;
	}
	function addSource($name)
	{
		$this->source=$name;
	}
	function setMinDate($date)
	{
		$this->minDate = $date;
	}
	function setMaxDate($date)
	{
		$this->maxDate = $date;
	}
	function rander($echo=true)
	{
		
		$temp = $this->id;
		$this->id = $this->id;
		$str = parent::rander($echo);
		$this->id=$temp;
		$source="";
		if($this->source!="")
		{
			$source=',altField: "#'.$this->source.'"';
		}
		$min="";
		if($this->minDate!="")
		{
			if(strpos($this->minDate,"new Date")===false)
			{
				$min=',minDate:"'.$this->minDate.'"';
			}
			else
			{
				$min=',minDate:eval("'.$this->minDate.'")';
			}
		}
		$max="";
		if($this->maxDate!="")
		{
			if(strpos($this->maxDate,"new Date")===false)
			{
				$max=',maxDate:"'.$this->maxDate.'"';
			}
			else
			{
				$max=',maxDate:eval("'.$this->maxDate.'")';
			}
		}
		$yearSelection = "";
		if($this->yearSelection==true)
		{
			$yearSelection = ",changeYear:true";
		}
		$editable = "";
		if($this->editable==true)
		{
			$editable=",constrainInput: false";
		}
		$showFormat="";
		if($this->showFormat==true)
		{
			$showFormat=',appendText: "('.$this->format.')"';
		}
		$yearRange = "";
		if($this->yearRange !="")
		{
			$yearRange = ',yearRange: "'.$this->yearRange.'" ';
		}
		$script = ' <script type="text/javascript" language="javascript">
		
			function '.$this->id.'_make()
			{
				'.$this->id.' = $( "#'.$this->id.'" ).datepicker({showAnim: "'.$this->effect.'",dateFormat:"'.$this->format.'"'.$source.$min.$max.$yearSelection.$editable.$showFormat.$yearRange.'});
			}
			setTimeout("'.$this->id.'_make()",500);
			/**/
		
		</script>';
		if($this->echo==true)
		{
			echo $script;
		}

		return $str.$script;
	}
}
?>