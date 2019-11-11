<?php
class TCP
{
	var $destination;
	function __construct($destination="")
	{
		$this->destination = $destination;
	}
	function ping($destination="")
	{
		if($destination!="")
		{
			$this->destination = $destination;
		}
		$d = $this->destination;
		$cmd = "ping -c 1 $d | grep icmp_req | wc -l";
		
		$out = array();
		exec($cmd,$out);
		if($out[0]=="0")
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
?>