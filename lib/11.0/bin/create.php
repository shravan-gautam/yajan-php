<?php
class create
{
    function __construct($cmd)
	{
		global $CS,$YAJAN_DATA;
		$f = $cmd[1];
		if(method_exists($this,$f))
		{
			echo "\n";
			$this->$f($cmd);
			echo "\n";
		}
		else
		{
			$CS->showError("invalid command $f.");
		}
    }
    function instance($cmd)
    {
        global $CS;
        $instanceName = $cmd[2];
        if(!is_dir($instanceName))
        {
            mkdir($instanceName);
            $CS->showInfo("Instance created.");
        }
    }
    function application($cmd)
    {
        global $CS;
        //create application <test> in <apps> with default <controler>/<method>
        $appName = $cmd[2];
        if(!isset($cmd[4]))
        {
            $CS->showError("insufficiant parameter");
            $CS->showInfo("syntext : create application <applicationName> in <instanceName>");
            $CS->showInfo("syntext : create application <applicationName> in <instanceName> with default <controlerName>/<methodName>");
            $CS->showInfo("syntext : create application <applicationName> in <instanceName> with default <controlerName>/<methodName> on <homePath>");
            return;
        }
        
        $instanceName = $cmd[4];
        $homePath="";
        if(isset($cmd[9]))
        {
            $homePath = $cmd[9];
        }
        if(!is_dir($instanceName))
        {
            $CS->showError("Instance is not created");
            return;
        }
        $appPath = "$instanceName/$appName";
        if(!is_dir("$instanceName/$appName"))
        {
            
            mkdir($appPath);
            mkdir("$appPath/controller");
            mkdir("$appPath/model");
            mkdir("$appPath/template");
            mkdir("$appPath/view");
            $php = '<?php
            import("mvc");
            
            $app = new MVCApplication("'.$instanceName.'","'.$appName.'");
            $app->setHome("'.$homePath.'");
            
            // $app->enableUserSecurity($user,"<failure url>"); // $user is object of User classs
            // $app->enableSessionSecurity("<sessionid>"); // for session security
            // $app->enableUrlSecurity(); // for url hash security
            
            
            // $app->addPublicMethod("home","init"); // bypass url from url security
            // $app->addAnonymousMethod("home","init"); // bypass url from user security
            
                        
            ?>';
            file_put_contents("$instanceName/app.$appName.mvc.php",$php);            
           
            $CS->showInfo("mvc app $appName created.");
        }

        if(isset($cmd[7]))
        {
            $uri = $cmd[7];
            $u = explode("/",$uri);
            $controlderName = $u[0];
            $methodName = $u[1];

            $php = '<?php
            $app->setDefault("'.$controlderName.'","'.$methodName.'");
            ?>';
            $file = new File("$instanceName/app.$appName.mvc.php");
            $file->append($php);


            $php = '<?php
class '.$controlderName.' extends Controller
{
    function __construct()
    {
        parent::__construct();
    }	
    function '.$methodName.'()
    {
        $this->loadView("'.$controlderName.'/'.$methodName.'");
    }
}
?>';
            file_put_contents("$appPath/controller/$controlderName.php",$php);


            $html = '<html>
    <head>
        <title>Yajan MVC Application '.$appName.'</title>
    </head>
    <body>
        <center>Welcome MVC Application '.$appName.'</center>
    </body>
</html>';
            if(!is_dir("$appPath/view/$controlderName"))
            {
                mkdir("$appPath/view/$controlderName");
            }
            file_put_contents("$appPath/view/$controlderName/$methodName.php",$html);
            $CS->showInfo("default controler/method created.");
        }
    }
}
?>