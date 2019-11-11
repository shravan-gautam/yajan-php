<?php
class APIAccessKey
{
	var $keyList;
	var $key;
	function __construct($key)
	{
		$this->key = $key;
		$this->keyList=array();
		$this->filename="key.list";
	}
	function add($key)
	{
		//$this->keyList[count($this->keyList)]=$key;
	}
	function getSalt()
	{
		$cmd = "grep '$this->key=' $this->filename";
		exec($cmd,$output);
		$o = explode("=",$output[0]);
		return $o[1];
	}
	function generateHash($data)
	{
		$data = $data.$this->getSalt();
		return md5($data);
	}
}
?>