<?php
	require_once("$LIB_PATH/bin/lib/lib.php");
	require_once("$LIB_PATH/php/system/packeges.php");
	require_once("$LIB_PATH/php/system/errorhandler.php");
	require_once("$LIB_PATH/php/system/messages.php");
	require_once("$LIB_PATH/php/system/array.php");
	require_once("$LIB_PATH/php/system/itemgroup.php");
	require_once("$LIB_PATH/php/system/time.php");
	require_once("$LIB_PATH/php/system/client.php");
	
	
	require_once("$LIB_PATH/php/system/functions.php");
	$PACKEGES = new Packeges();
	$CLIENT = new Client();
	require_once("$LIB_PATH/php/system/system.php");
	require_once("$LIB_PATH/php/system/configuration.php");
	register_globals('gpr');
?>