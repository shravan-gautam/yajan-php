<?php
class Url
{
	var $url;
	var $node;
	function __construct($url)
	{
		$this->url = $url;
		$this->node = explode("/",$this->url);
	}
	function getProtocol()
	{
		return trim($this->node[0],':');
	}
	function getDomain()
	{
		$s = $this->node[2];
		$s=explode(":",$s);
		return $s[0];
	}
	function getPort()
	{
		$s = $this->node[3];
		$s=explode(":",$s);
		if(count($s)>1)
		{
			return $s[1];
		
		}
		$protocol=$this->getProtocol();
		
		if($protocol=="http")
		{
			return 80;
		}
		else if($protocol=="https")
		{
			return 443;
		}
		return "";		
	}
	function getQuery()
	{
		$k=explode("?",$this->url);
		if(count($k)>0)
		{
			return $k[1];
		}
		return "";
	}
	function getQueryParm($parm)
	{
		$parmList=array();
		$query=$this->getQuery();
		$query=explode("&",$query);
		foreach($query as $p)
		{
			$p=explode("=",$p);
			if($p[0]==$parm)
			{
				if(count($p)>0)
				{
					return $p[1];
				}
				return "";
			}
		}
		
		return "";
		
	}
	function getHtml()
	{
		return new Html($this->url);
	}
}
?>