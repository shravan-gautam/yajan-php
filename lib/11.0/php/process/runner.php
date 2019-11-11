<?php
include "yajan/include.php";
import("process");
$pid = $argv[1];

$thread = new ThreadRunner($pid);
$thread->run();

?>