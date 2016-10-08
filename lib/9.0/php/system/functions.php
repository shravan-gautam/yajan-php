<?php
function register_globals($order = 'gpr')
{
    // define a subroutine
    if(!function_exists('register_global_array'))
    {
        function register_global_array(array $superglobal)
        {
            foreach($superglobal as $varname => $value)
            {
                global $$varname;
				$value = str_replace("--","",$value);
                $$varname = $value;
            }
        }
    }
   
    $order = explode("\r\n", trim(chunk_split($order, 1)));
    foreach($order as $k)
    {
        switch(strtolower($k))
        {
            case 'e':    register_global_array($_ENV);        break;
            case 'g':    register_global_array($_GET);        break;
            case 'p':    register_global_array($_POST);        break;
            case 'c':    register_global_array($_COOKIE);    break;
            case 's':    register_global_array($_SERVER);    break;
			case 'r':    register_global_array($_REQUEST);    break;
        }
    }
	crossDomainEnable();
}
function getRelativePath($from, $to)
{
    // some compatibility fixes for Windows paths
    $from = is_dir($from) ? rtrim($from, '\/') . '/' : $from;
    $to   = is_dir($to)   ? rtrim($to, '\/') . '/'   : $to;
    $from = str_replace('\\', '/', $from);
    $to   = str_replace('\\', '/', $to);

    $from     = explode('/', $from);
    $to       = explode('/', $to);
    $relPath  = $to;

    foreach($from as $depth => $dir) {
        // find first non-matching dir
        if($dir === $to[$depth]) {
            // ignore this directory
            array_shift($relPath);
        } else {
            // get number of remaining dirs to $from
            $remaining = count($from) - $depth;
            if($remaining > 1) {
                // add traversals up to first matching dir
                $padLength = (count($relPath) + $remaining - 1) * -1;
                $relPath = array_pad($relPath, $padLength, '..');
                break;
            } else {
                $relPath[0] = './' . $relPath[0];
            }
        }
    }
    return implode('/', $relPath);
}
function isAvilableUnixPackeg($packeg)
{
	$cmd = "dpkg --get-selections | grep 'install' | grep '$packeg'";
	$output = exec($cmd);
	if(strpos($cmd,$packeg)==false)
	{
		return false;
	}
	else
	{
		return true;
	}
}
function variable_name(&$var)
{
	$ret = '';
	$tmp = $var;
	$var = md5(uniqid(rand(), TRUE));

	$key = array_keys($GLOBALS);
	foreach ( $key as $k )
	if ( $GLOBALS[$k] === $var )
	{
	$ret = $k;
	break;
	}

	$var = $tmp;
	return $ret;
}
function crossDomainEnable()
{
	global $CROSS_DOMAIN_STATUS;
	if(isset($CROSS_DOMAIN_STATUS))
	{
		if($CROSS_DOMAIN_STATUS)
		{
			header("Access-Control-Allow-Origin: *");
			header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
			header("Access-Control-Max-Age: 1000");
			header("Access-Control-Allow-Headers: x-requested-with, Content-Type, origin, authorization, accept, client-security-token");
			
		}
	}
}

?>