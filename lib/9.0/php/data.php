<?php
	global $databoxVersion,$COOKIES;
	if(!isset($databoxVersion))
	{
		$databoxVersion="1.1";
	}
	require_once("$LIB_PATH/php/data/text.php");
	require_once("$LIB_PATH/php/data/htmltext.php");
	require_once("$LIB_PATH/php/data/databox1.1.php");
	require_once("$LIB_PATH/php/data/databox1.2.php");
	require_once("$LIB_PATH/php/data/recordsetcolumns.php");
	require_once("$LIB_PATH/php/data/recordset.php");
	require_once("$LIB_PATH/php/data/format.php");
	require_once("$LIB_PATH/php/data/numberFormat.php");
	require_once("$LIB_PATH/php/data/moneyFormat.php");
	require_once("$LIB_PATH/php/data/cookies.php");

	$COOKIES = new Cookies();
?>