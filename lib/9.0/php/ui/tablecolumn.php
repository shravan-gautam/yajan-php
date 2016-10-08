<?php
class TableColumn
{
	var $name;
	var $randerClass;
	var $propertyMap;
	var $header_title;
	var $visible;
	var $css;
	var $valueFormat;
	var $encription;
	var $cssClass;
	function TableColumn($name,$className=null)
	{
		if($className==null)
		{
			$className = new Span("");
		}
		$this->name = strtoupper($name);
		$this->randerClass = $className;
		$this->propertyMap =array();
		$this->addProperty('val','');
		$this->header_title = strtoupper($name);
		$this->visible = true;
		$this->css = array();
		$this->cssClass = null;
		$this->valueFormat = null;
		$this->encription = "ASCI";
	}
	function setValueFormat($format)
	{
		
		$this->valueFormat = $format;
		if($this->randerClass!=null)
		{
			if($this->valueFormat!=null)
			{
				$this->randerClass->setValueFormat($this->valueFormat);
			}
		}
	}
	function addCss($k,$v)
	{
		$this->css[$k]=$v;
	}
	function addCssClass($val)
	{
		$this->cssClass=$val;
	}
	function getCssClass()
	{
		return $this->cssClass;
	}
	function getCss()
	{
		return $this->css;
	}
	function getStyleString()
	{
		$str="";
		foreach($this->css as $k => $v)
		{
			$str.="$k:$v;";
		}
		return $str;
	}
	function isVisible()
	{
		return $this->visible;
	}
	function setVisibility($v)
	{
		$this->visible = $v;
	}
	function getHeaderTitle()
	{
		return $this->header_title;
	}
	function setHeaderTitle($title)
	{
		$this->header_title = $title;
	}
	function getName()
	{
		return $this->name;
	}
	function removePropertyMap()
	{
		$this->propertyMap =array();
	}
	
	function getClass()
	{
		return $this->randerClass;
	}
	function addProperty($property,$column)
	{
		$temp = array();
		$temp['property']=$property;
		$temp['column']=strtoupper($column);
		
		$this->propertyMap[count($this->propertyMap)]=$temp;
	}
	function bindWith($column,$property='val')
	{
		$this->setPropertyMap($property,$column);
	}
	function setPropertyMap($property,$column)
	{
		$v = false;
		
		for($i=0;$i<count($this->propertyMap);$i++)
		{
			
			if($this->propertyMap[$i]['property']==$property)
			{
				$this->propertyMap[$i]['column']=strtoupper($column);
				$v = true;
			}
		}
		
		if($v==false)
		{
			$this->addProperty($property,$column);
		}
	}
	function setClass($class)
	{
		$this->randerClass=clone $class;
		if($this->valueFormat!=null)
		{
			$this->randerClass->setValueFormat($this->valueFormat);
		}
	}
	function setEncryption($code)
	{
		$this->encription = $code;
	}
	function setEncription($code)
	{
		$this->encription = $code;
	}
	function getEncryption()
	{
		return $this->encription;
	}
	function getEncription()
	{
		return $this->encription;
	}

}
?>