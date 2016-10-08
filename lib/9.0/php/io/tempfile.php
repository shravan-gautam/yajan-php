<?php

class Tempfile
{
	var $file;
	function __construct()
	{
		$filename = md5(time());
		$this->file = new File($filename);
	}
	function read()
	{
		return $this->file->read();
	}
	function write($string)
	{
		$this->file->write($string);
	}
	function delete()
	{
		$this->file->delete();
	}
}
?>