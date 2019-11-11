<?php
function exception_handler ($exception) 
{
	global $EXCEPTION_LOG_FILE,$YAJAN_DATA,$EXCEPTION_LOG;
	if($EXCEPTION_LOG)
	{
		$EXCEPTION_LOG_FILE = "$YAJAN_DATA/log/exceptionDump.log";
		file_put_contents($EXCEPTION_LOG_FILE, $exception->__toString(), FILE_APPEND);
	}
}


set_exception_handler("exception_handler");
?>