<?php
class Packeges
{
	private $p;
	var $echo;
	function __construct()
	{
		$this->p = array();
		$this->echo = false;
	}
	function message($msg)
	{
		if($this->echo)
		{
			echo $msg."<br>\n";
		}
	}
	function import($packege)
	{
		global $_PWD,$FREAMWORK_PATH,$libVersion,$LIB_PATH,$CSS,$JS,$UI;
		if(!$this->isImported($packege))
		{
			if(file_exists("$LIB_PATH/php/$packege.php"))
			{
				include "$LIB_PATH/php/$packege.php";
				
				$this->p[count($this->p)]=$packege;
				$this->message("$packege imported.");
			}
			else
			{
				$this->message("$packege packege file not found.");
			}
		}
	}
	function isImported($packege)
	{
		if(array_search($packege,$this->p)!==false)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
}
?>