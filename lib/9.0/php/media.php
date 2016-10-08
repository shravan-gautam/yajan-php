<?php
if(isAvilableUnixPackeg("php5-gd"))
{
	require_once("$LIB_PATH/php/media/imageeditor.php");
	require_once("$LIB_PATH/php/media/barcode.php");
}
else
{
	echo "GD Liberary is requred for media packeg";
}
?>