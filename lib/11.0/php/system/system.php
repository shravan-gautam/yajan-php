<?php
class SystemHost
{
	var $host;
	var $url;
	var $referer;
	var $startTime;
	var $endTime;
	var $exicTime;
	function __destruct()
	{
		global $OUTPUT_CACHE,$NO_CACHE,$_CACHE_MANAGER,$CACHE_DATA_SOURCE,$FREAMWORK_PATH,$_COOKIE,$OUTPUT_CACHE_MODE,$ACCESS_LOG,$YAJAN_DATA,$CALL_SEQUANCE_TIME,$db;
		if(isset($OUTPUT_CACHE) && $OUTPUT_CACHE==true && $NO_CACHE==false)
		{
			$url = base64_encode($CACHE_DATA_SOURCE);
			$_PWD = getcwd();
			$pwd = $_SERVER["DOCUMENT_ROOT"];
			$sessionId = $_COOKIE["PHPSESSID"];
			if($OUTPUT_CACHE_MODE=="yajan")
			{
				exec('cd "'.$pwd.'"; php yajan/exec.php service/cache/make '.$url.' '.$sessionId.' > /dev/null &',$output);
			}
		}
		//ob_start();
		//$this->endTime = system('date +%s.%N');
		///ob_clean();
		$this->endTime = microtime(true);
		$this->exicTime = $this->endTime - $this->startTime;
		if(isset($ACCESS_LOG) && $ACCESS_LOG ==true)
		{
			//echo getcwd();
			//print_r($_SERVER);
			if(isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]))
			{
				chdir($_SERVER["CONTEXT_DOCUMENT_ROOT"]);
			}
			
			$accessLog = new Logfile("access","$YAJAN_DATA/log");
			if(isset($db))
			{
				$m4 = $this->exicTime - $db->database->timecount;
				$m3 = $db->database->timecount;
				
				$accessLog->write("$CALL_SEQUANCE_TIME|".$_SERVER["REMOTE_ADDR"]."|$this->exicTime|$db->queryCount|$m3|$m4|".$_SERVER["REQUEST_URI"]);
			}
			else
			{
				$accessLog->write("$CALL_SEQUANCE_TIME|".$_SERVER["REMOTE_ADDR"]."|$this->exicTime|".$_SERVER["REQUEST_URI"]);
			}
		}
	}
	
	function __construct()
	{
		
		$this->host = $_SERVER;
		$this->referer="";
		$this->url;
		$this->initRaffer();
		//ob_start();
		//$this->startTime = system('date +%s.%N');
		//ob_clean();
		$this->startTime = microtime(true);
		if(isset($_SERVER["HTTP_X_YAJANPHPSESSION"]))
		{
			session_id($_SERVER["HTTP_X_YAJANPHPSESSION"]);
			$_COOKIE["PHPSESSID"]=$_SERVER["HTTP_X_YAJANPHPSESSION"];
		}
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