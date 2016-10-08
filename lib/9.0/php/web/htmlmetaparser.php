<?php
class HtmlMetaParser
{
	var $metaInfo;
	var $metaGroup;
	var $html;
	function HtmlMetaParser($type)
	{
		$this->metaGroup=$type;
		$this->metaInfo = array();
	}
	function loadUrl($url)
	{
		import("web");
		$this->html = new Html($url);
		
	}
	function init()
	{
		$node_list = $this->html->getElementsByTagName("meta");
		
		$this->metaInfo=array();
		foreach($node_list as $node) 
		{
		  
		  //$attrib = $node->getAttribute("name");
		  $property = $node->getAttribute("property");
		  $value = $node->getAttribute("content");
		  $attrib = explode(":",$property);
		  
		  if($attrib[0]==$this->metaGroup && count($attrib)>1)
		  {
			$this->metaInfo[count($this->metaInfo)]=array("name"=>$attrib[1],"value"=>$value);
		  }
		}
		
	}
	function getValue($name)
	{
		
		foreach($this->metaInfo as $meta)
		{
			if($meta["name"]==$name)
			{
				return $meta["value"];
			}
		}
		return "";
	}
}
?>