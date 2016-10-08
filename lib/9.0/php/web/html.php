<?php
import("data");
class Html
{
	var $url;
	var $dom;
	var $xPath;
	var $metaInfo;
	function Html($url="")
	{
		$this->dom = new DOMDocument();
		$this->url = $url;
		if($this->url!="")
		{
			@$this->dom->loadHTMLFile($this->url);
			$this->xPath = new DOMXpath($this->dom);
			$this->initMeta();
		}
	}
	function getDomain()
	{
		$u = explode("/",$this->url);
		$str = "$u[0]/$u[1]/$u[2]";
		return $str;
	}
	function refresh()
	{
		@$this->dom->loadHTMLFile($this->url);
		$this->xPath = new DOMXpath($this->dom);
		$this->initMeta();
	}
	function loadUrl($url)
	{
		$this->url = $url;
		
		@$this->dom->loadHTMLFile($this->url);
		$this->xPath = new DOMXpath($this->dom);
		$this->initMeta();
	//	$string = file_get_contents($this->url);
	//	$this->parse($string);
	}
	function parse($string)
	{
		//$string = mb_convert_encoding($string, 'utf-8', mb_detect_encoding($string));
		// if you have not escaped entities use
		//$string = mb_convert_encoding($string, 'html-entities', 'utf-8'); 
		$this->dom->loadHTML(mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8'));
		$this->xPath = new DOMXpath($this->dom);
		$this->initMeta();
	}
	function initMeta()
	{
		$node_list = $this->dom->getElementsByTagName("meta");
		$this->metaInfo=array();
		foreach($node_list as $node) 
		{
		  $attrib = $node->getAttribute("name");
		  $value = $node->getAttribute("content");
		  
		 
		  $this->metaInfo[count($this->metaInfo)]=array("name"=>$attrib,"value"=>$value);
		  
		}
	}
	function getMeta($key)
	{
		$key=strtoupper($key);
		foreach($this->metaInfo as $meta)
		{
			if(strtoupper($meta["name"])==$key)
			{
				return $meta["value"];
			}
		}
		return "";
	}
	function getMetaObject($type)
	{
		$p= new HtmlMetaParser($type);
		//$p->loadUrl($this->url);
		$p->html = $this->dom;
		$p->init();
		return $p;
	}
	function toString()
	{
		return new Text($this->dom->saveHTML());
	}
	function getDom()
	{
		return $this->dom;
	}
	function xQuery($query)
	{
		return $this->xPath->query($query);
	}
	function getElementsByTagName($tag)
	{
		return $this->dom->getElementsByTagName($tag);
	}
	function getElementById($id)
	{
		return $this->dom->getElementById($id);
	}
	
	function show()
	{
		print_r($this->dom);
	}
}
?>