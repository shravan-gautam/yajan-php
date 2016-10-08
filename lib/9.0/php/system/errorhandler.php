<?php
function exception_handler ($exception) 
{
	global $EXCEPTION_LOG_FILE,$EXCEPTION_LOG;
	if($EXCEPTION_LOG)
	{
		file_put_contents($EXCEPTION_LOG_FILE, $exception->__toString(), FILE_APPEND);
	}
}


set_exception_handler("exception_handler");
?>