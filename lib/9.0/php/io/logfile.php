<?php

class Logfile
{
	var $file;
	var $echo=false;
	var $autoName=false;
	function __construct($logname,$path,$autoName=false)
	{
		
		$this->autoName = $autoName;
		if($this->autoName)
		{
			$filename = $path."/".$logname.date("d-m-Y").".log";
		}
		else
		{
			$filename = $path."/".$logname.".log";
		}
		$this->file = new File($filename);
	}
	function write($message,$type="INFO")
	{
		$message="$type : ".date("d-m-Y h:i:s").">".$message."\n";
		$this->file->append($message);
		if($this->echo==true){
			echo $message."<br>";
		}
	}
	
	function clear()
	{
		$this->file->clear();
	}
	
}
?>