<?php
class MultiLevelDataset
{
	var $data;
	var $xml;
	var $root;
	function __construct()
	{
		$this->data = array();
		$this->xml = new DOMDocument( "1.0", "ISO-8859-15" );
		$this->root = $this->xml->createElement("root");
		$this->xml->appendChild($this->root);
	}
	
	function updateXml($node,$value,$level,$row)
	{
		
		$els = $node->getElementsByTagName('node');
		$match = false;
		foreach ($els as $el) 
		{
			if($el->getAttribute("__value")==$value)
			{
				$match=true;
				break;
			}
		}
		if($match)
		{
			return $el;
		}				
		else
		{
			$el = $this->xml->createElement("node");
			$el->setAttribute("__value",$value);
			$node->appendChild($el);
			foreach($row as $k =>$v)
			{
				$el->setAttribute($k,$v);
			}
			
			return $el;
		}
	}
	function add($row)
	{
		$index = 0;
		$el = $this->root;
		foreach($row as $k => $v)
		{
			$el = $this->updateXml($el,$v,$index,$row);
			$index++;
		}
	}
	function getXml()
	{
		return $this->xml->saveXml();
	}
	function Dom2Array($root) 
	{
		$array = array();
		if($root->hasAttributes() )
		{
			foreach($root->attributes as $attribute) 
			{
				if($attribute->name!="__value")
				{
					$array[$attribute->name] = $attribute->value;
				}
			}
		}
		if($root->nodeType == XML_TEXT_NODE || $root->nodeType == XML_CDATA_SECTION_NODE) 
		{
			$array[$root->nodeName]=$root->nodeValue;
			
		}
		elseif($root->nodeType == XML_ELEMENT_NODE)
		{
			if($root->hasChildNodes()) 
			{
				$children = $root->childNodes;
				for($i = 0; $i < $children->length; $i++) 
				{
					$child = $this->Dom2Array( $children->item($i) );
					if($root->nodeName=="root")
					{
						$array[] = $child;
					}
					else
					{
						$array['child'][] = $child;
					}
				}
			}
		}
		return $array;
	}
	function getArray()
	{
		return $this->Dom2Array($this->root);
	}
	function getJson()
	{
		$ar = $this->getArray();
		return json_encode($ar);
	}
	
}
?>