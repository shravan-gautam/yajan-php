<?php
class get
{
	function __construct($cmd)
	{
		global $CS,$YAJAN_DATA;
		$f = $cmd[1];
		if(method_exists($this,$f))
		{
			echo "\n";
			$this->$f($cmd);
			echo "\n";
		}
		else
		{
			$CS->showError("invalid command $f.");
		}
	}
	function search($cmd)
	{
		//global $CS;
		if($cmd[2]=="plugins")
		{
			//$CS->showInfo("Getting plugins list");			
			$plugins = new Plugins();
			$plugins->getList();
		}
	}
}

?>