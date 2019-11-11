<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class TokenInput extends UIObject
{
	var $events;
	var $suggestions;
	var $theam;
	var $dataUrl;
	var $tokenLimit;
	function __construct($id)
	{
		parent::__construct();
		$this->id=$id;
		$this->type="text";
		$this->tag="input";
		$this->events = array();
		$this->suggestions = array();
		$this->theam="";
		$this->dataUrl="";
		$this->tokenLimit=0;
	}
	function setTokenLimit($limit)
	{
		$this->tokenLimit=$limit;
	}
	function setUrl($u)
	{
		$this->dataUrl = $u;
	}
	function facebookStyle($v)
	{
		if($v)
		{
			$this->theam="facebook";
		}
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
	function findSuggestionName($id)
	{
		for($i=0;$i<count($this->suggestions);$i++)
		{
			$v = explode("=",$this->suggestions[$i]);
			if($v[0]==$id)
			{
				return $v[1];
			}
		}
		for($i=0;$i<$this->recordset->count;$i++)
		{
			if($this->recordset->data[$i]['CODE']==$id)
			{
				return $this->recordset->data[$i]['NAME'];
			}
		}
		return null;
	}
	function rander($echo=true)
	{
		
		$str = parent::rander($echo);

		$tag='';
		if(count($this->suggestions)>0)
		{
			
			for($i=0;$i<count($this->suggestions);$i++)
			{
				$v = explode("=",$this->suggestions[$i]);
				$tag.="{id:'".$v[0]."',name:'".$v[1]."'}";
				if($i<count($this->suggestions)-1)
				{
					$tag.=",";
				}
			}
		}
		if($this->recordset->count>0)
		{
			for($i=0;$i<$this->recordset->count;$i++)
			{
				$tag.="{id:'".$this->recordset->data[$i]['CODE']."',name:'".$this->recordset->data[$i]['NAME']."'}";
				if($i<$this->recordset->count-1)
				{
					$tag.=",";
				}
			}
		}
		if($this->theam!="")
		{
			$this->theam = 'theme: "facebook"';
		}
		$data="";
		if($this->dataUrl!="")
		{
			$data = "\"$this->dataUrl\"";
		}
		else if($tag!="")
		{
			$data = "[$tag]";
		}
		$val = "";
		if($this->value!="")
		{
			$v = explode(",",$this->value);
			for($i=0;$i<count($v);$i++)
			{
				$vName = $this->findSuggestionName($v[$i]);
				$val .= '{id: "'.$v[$i].'", name: "'.$vName.'"},';
			}
		}
		$val = rtrim($val,",");
		if($val!="")
		{
			$val = "prePopulate: [$val],";
		}
		$tokenLimit="";
		if($this->tokenLimit>0)
		{
			$tokenLimit = " tokenLimit:$this->tokenLimit,";
		}
		if($this->echo==true)
		{
			$s = ' <script type="text/javascript">
			
				'.$this->id.'_jqObject = $("#'.$this->id.'").tokenInput('.$data.',{'.$this->theam.$val.$tokenLimit.'});
			
			</script>';
			echo $s;
		}
		$str.=$s;

		
		return $str;
	}
}
?>
