<?php
class Cookies
{
	function __construct()
	{
		global $_COOKIE;

	}
	function get($name)
	{
		global $_COOKIE;
		if($this->isExist($name))
		{
			return $_COOKIE[$name];
		}
		else
		{
			return false;
		}
	}
	function isExist($name)
	{
		global $_COOKIE;

		if(isset($_COOKIE[$name]))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function set($name,$value,$time="0",$path="/")
	{
		setcookie($name,$value,$time,$path);
	}
}
?>