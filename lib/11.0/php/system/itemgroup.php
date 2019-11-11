<?php
class ItemGroup
{
	var $items;
	function __construct()
	{
		$this->items = array();
	}
	function add($name,$val='',$target='')
	{
		$temp = array();
		$temp['name']=$name;
		$temp['value']=$val;
		$temp['items']=null;
		$temp['target']=$target;
		$this->items[count($this->items)]=$temp;
	}
	function addGroup($name,$target='')
	{
		$temp = array();
		$temp['name']=$name;
		$temp['items']=new ItemGroup();
		$temp['value']="#";
		$temp['target']=$target;
		$this->items[count($this->items)]=$temp;
		return $temp['items'];
	}
	function count()
	{
		return count($this->items);
	}
	function get($i)
	{
		return $this->items[$i];
	}
}
?>