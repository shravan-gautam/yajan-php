<?php
class Tar
{
	var $filename;
	var $src= array();
	var $dst;
	var $zip;
	var $destnation;
	var $message;
	function __construct($filename)
	{
		$this->filename =$filename;
		$this->zip = false;
	}
	function zip($v)
	{
		$this->zip = $v;
	}
	function add($source)
	{
		$this->src[count($this->src)]=$source;
	}
	function loadFiles($srcpath)
	{
       $this->src[count($this->src)]=$srcpath;
	}
	function systemExec($cmd)
	{
		$status=0;
		$res = array();
		exec($cmd,$res,$status);
		if($status==0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getDestination()
	{
		return $this->destnation;
	}
	function getMessage()
	{
		return $this->message;
	}
	function make()
	{
		$cwd = getcwd();
		$tempFile = "/tmp/".rand(00000000,9999999999999999);
		if(!mkdir($tempFile))
		{
			return false;
		}
		$tempFile.="/";
		for($i=0;$i<count($this->src);$i++)
		{
			echo "cp -r ".$this->src[$i]." ".$tempFile;
			if($this->systemExec("cp -r ".$this->src[$i]." ".$tempFile)==false)
			{
				$this->message="error in copying files.";
				return false;
			}
		}
		chdir($tempFile);
		$attr="-cf";
		$this->destnation = "/tmp/".$this->filename;
		if($this->zip)
		{
			$attr = "-czf";
		}
		if($this->systemExec("tar $attr ".$this->destnation." .")==false)
		{
			return false;
		}
		chdir($cwd);
		if($this->clean())
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	function clean()
	{
		if(file_exists($this->destnation))
		{
			if($this->systemExec("rm -r ".$this->destnation)==false)
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return false;
		}
	}
	/*
	function setTarget($dst="/home/ramanujam/www/abc/")
	{
		$this->dst=$dst;
	}
	
	function doCopy()
	{
		 for($i=0;$i<count($this->src);$i++)
		 {
			shell_exec("cp -r ".$this->src[$i]." ".$this->dst);
		 }
	} 
	function makeArchiveDirectory($zipName)
	{
	  shell_exec("tar cJf ".$zipName.".tar.xz ".$this->dst);
	}
	*/
}
?>