<?php

	import("data");
	import("io");

			require_once("$LIB_PATH/php/db/scnmgmt.php");
			require_once("$LIB_PATH/php/db/dbdriver.php");
			require_once("$LIB_PATH/php/db/databaseadaptor.php");
			require_once("$LIB_PATH/php/db/mysql.php");
			require_once("$LIB_PATH/php/db/mysqli.php");
			require_once("$LIB_PATH/php/db/oracle.php");
			require_once("$LIB_PATH/php/db/sqlite.php");
			require_once("$LIB_PATH/php/db/cloudDB.php");
			require_once("$LIB_PATH/php/db/connection.php");
			require_once("$LIB_PATH/php/db/dbtable.php");
			require_once("$LIB_PATH/php/db/descriptor.php");
			require_once("$LIB_PATH/php/db/scnreader.php");
			require_once("$LIB_PATH/php/db/postgre.php");
	if(isset($YAJAN_DATA))
	{
		if(is_dir($YAJAN_DATA."/db")==true)
		{
		}	
	}
?>
