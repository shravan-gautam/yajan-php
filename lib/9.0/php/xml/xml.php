<?php
class Xml
{
	var $xml;
	var $document;
	function Xml()
	{
		$this->document = new XMLDocument();
	}
	function fromFile($filename)
	{
		$this->xml = file_get_contents($filename);
	}
	
	function toArray($xml="")
	{
		if($xml=="")
		{
			$xml=$this->xml;
		}
		return $this->document->toArray($xml);
	}
	function toXml($array)
	{
		return $this->document->toXML($array);
	}
	function toRecordset($xml="")
	{
		if($xml=="")
		{
			$xml=$this->xml;
		}
		$r = new Recordset();
		$ar = $this->toArray($xml);
		
		//$tableName = ($ar['object_name'])."_";
		foreach($ar['columns'] as $key => $val)
		{
			//$val = str_replace($tableName,"",$val);
			$r->addColumns($val);
		}
		$c =0;
		if(isset($ar['rows']) && gettype($ar['rows'])=="array")
		{
		foreach($ar['rows'] as $key => $val)
		{
			$temp = array();
			foreach($val as $k => $v)
			{
				//$k = str_replace(strtoupper($tableName),"",$k);
				if($v[0]=="\"")
				{
					$v=substr($v,1,strlen($v));
				}
				if($v[strlen($v)-1]=="\"")
				{
					$v=substr($v,0,strlen($v)-1);
				}
				$v = urldecode($v);
				$temp[$k]=$v;
			}
			$r->data[count($r->data)]=$temp;
			$c++;
		}		
		}
		$r->count=$c;
		$r->populationStatus=true;
		return $r;
	}
}


?>
