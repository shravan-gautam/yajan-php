<?php

/*

exec("mkdir var");
exec("chmod 777 ./var");
exec("mkdir var/tmp");
exec("chmod 777 ./var/tmp");
exec("mkdir var/log");
exec("chmod 777 ./var/log");
$pwd = getcwd() ;
exec("touch $pwd/.htpasswd");
$htaccess = '
AuthName "Secure Area"
AuthType Basic
AuthUserFile '.$pwd.'/.htpasswd
require valid-user
';



php yajan/console.php "alter config set AJAX_LISTNER=index.php"
php yajan/console.php "alter config set AJAX_LISTNER_STATUS=false"
php yajan/console.php "alter config set AUTO_POPULATE_RECORDSET=true"
php yajan/console.php "alter config set DATABASE_AUTO_OPEN=false"
php yajan/console.php "alter config set DB_REDOLOG_FILE=var/log/redo.log"
php yajan/console.php "alter config set DEFAULT_APPLICATION="
php yajan/console.php "alter config set DEFAULT_DB_CONFIG="
php yajan/console.php "alter config set DEFAULT_DB_OBJECT=db"
php yajan/console.php "alter config set DEFAULT_IMPORT_PACKEG=system,ui,db,cli"
php yajan/console.php "alter config set DEFAULT_MATHOD="
php yajan/console.php "alter config set DEFAULT_MODULE="
php yajan/console.php "alter config set EXCEPTION_LOG=false"
php yajan/console.php "alter config set EXCEPTION_LOG_FILE="
php yajan/console.php "alter config set FREAMWORK_PATH=yajan"
php yajan/console.php "alter config set JS_ELEMENT_MODE=dom"
php yajan/console.php "alter config set MESSAGE_BACKTRACE=false"
php yajan/console.php "alter config set MODULE_PATH=modules"
php yajan/console.php "alter config set SMTP_PASSWORD=1234"
php yajan/console.php "alter config set SMTP_PORT="
php yajan/console.php "alter config set SMTP_SERVER="
php yajan/console.php "alter config set SMTP_USER="
php yajan/console.php "alter config set YAJAN_DATA=/home/shravan/yajan_data/www/shravan"

*/
?>