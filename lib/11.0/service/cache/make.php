<?php
//echo "cache make.\n";
if(!isset($argv[3]))
{
	echo "cache source not define\n";
	exit(0);
}

	import("web");
	import("io");
	
	
	$CACHE_DATA_SOURCE = base64_decode($argv[2]);
	$sessionId=$argv[3];
	//$CACHE_DATA_SOURCE =$argv[2];
	$CACHE_ID=md5($CACHE_DATA_SOURCE);
	$_CACHE_MANAGER = new DataCache($CACHE_ID);
	$_CACHE_MANAGER->setInfo($CACHE_DATA_SOURCE);
	
	$_CACHE_MANAGER->setPhpSession($sessionId);
	$_CACHE_MANAGER->loadUrl($CACHE_DATA_SOURCE);
	echo "cacehe complite $CACHE_DATA_SOURCE \n";
?>