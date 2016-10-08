<?php
class SystemHost
{
	var $host;
	var $url;
	var $referer;
	function SystemHost()
	{
		$this->host = $_SERVER;
		$this->referer="";
		$this->url;
		$this->initRaffer();
	}
	function setPhpIni($key,$val)
	{
		ini_set($key,$val);
	}
	function getPhpIni($key)
	{
		return ini_get($key);
	}
	function setPhpErrorReporting($val)
	{
		$str = 'error_reporting('.$val.');';
		eval($str);
	}
	function initRaffer()
	{
		if(isset($_SESSION["currentPageUrl"]))
		{
			$_SESSION["referer"]=$_SESSION["currentPageUrl"];
			$this->referer=$_SESSION["referer"];
		}
		$_SESSION["currentPageUrl"]=$this->current_page_url();
		$this->url=$_SESSION["currentPageUrl"];
	}
	function current_page_url()
	{
		$page_url   = 'http';
		if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		{
			$page_url .= 's';
		}
		if(isset($_SERVER['SERVER_NAME']) && isset($_SERVER['REQUEST_URI']))
		{
			return $page_url.'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		}
		else
		{
			return "";
		}
	}

}
$SYSTEM = new SystemHost();
?>