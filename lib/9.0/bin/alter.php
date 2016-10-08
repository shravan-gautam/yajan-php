<?php

class alter
{
	function alter($cmd)
	{
		global $LIB_PATH;
		$c = "alter_".$cmd[1];
		require_once "$LIB_PATH/bin/alter/$c.php";
		$obj = new $c($cmd);
	}
}
?>