<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class TextBox extends UIObject
{
	var $events;
	var $suggestions;
	function TextBox($id)
	{
		parent::UIObject();
		$this->id=$id;
		$this->type="text";
		$this->tag="input";
		$this->events = array();
		$this->suggestions = array();
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
	function rander($echo=true)
	{
		$str = parent::rander($echo);
		if(count($this->suggestions)>0)
		{
			$tag='';
			for($i=0;$i<count($this->suggestions);$i++)
			{
				$tag.="'".$this->suggestions[$i]."'";
				if($i<count($this->suggestions)-1)
				{
					$tag.=",";
				}
			}
			
			if($this->echo==true)
			{
				$s = ' <script>
				$(function() 
				{
					var availableTags = ['.$tag.' ];
					$( "#'.$this->id.'" ).autocomplete(
					{
						source: availableTags
					});
				});
				</script>';
				echo $s;
			}
			$str.=$s;

		}
		return $str;
	}
}
?>