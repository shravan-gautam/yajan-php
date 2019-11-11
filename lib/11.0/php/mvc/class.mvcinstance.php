<?php
import("mvc");
class MVCInstance
{
    var $name;
    var $defaultApp;
    var $appName;
    var $home;
    var $app;
    var $defaultController;
    var $defaultMethod;
    var $userValidator;
    var $failureUrl;
    var $sessionId;
    var $urlSecurity;
    var $publicUrls;
    var $anonymousUrls;
    var $sso;
    var $urlPattern;
    function __construct($name)
    {
        $this->name = $name;
        $this->appName = "";
        $this->defaultApp="";
        $this->home="";
        $this->publicUrls = array();
        $this->anonymousUrls = array();
        $this->urlSecurity=false;
        $this->sessionId="";
        $this->failureUrl="";
        $this->userValidator=null;
        $this->sso = null;
        $this->urlPattern=array();
    }
    function addUrlPattern($pattern)
    {
        $this->urlPattern[count($this->urlPattern)]=trim($pattern,"/");
    }
    function addAnonymousUrl($class,$method)
    {
        $this->anonymousUrls[count($this->anonymousUrls)]=array("class"=>$class,"method"=>$method);
    }
    function addPublicUrl($class,$method)
    {
        $this->publicUrls[count($this->publicUrls)]=array("class"=>$class,"method"=>$method);
    }
    function setHome($home)
    {
        $this->home = $home;
    }
    function enableSsoSecurity($sso)
    {
        $this->sso = $sso;
    }
    function setDetailApplication($app)
    {
        $this->defaultApp = $app;
    }
    function setDefaultController($controllder)
    {
        $this->defaultController = $controllder;
    }
    function setDefaultMethod($method)
    {
        $this->defaultMethod = $method;
    }
    function setDefault($app,$controllder,$method)
    {
        $this->defaultApp = $app;
        $this->defaultController = $controllder;
        $this->defaultMethod = $method;
    }
    function enableUserSecurity($user,$failureUrl)
    {
        $this->userValidator = $user;
        $this->failureUrl = $failureUrl;
    }
    function enableSessionSecurity($sessionId)
    {
        $this->sessionId = $sessionId;
    }
    function enableUrlSecurity()
    {
        $this->urlSecurity = true;
    }
    function reWriteUrl($url)
    {
        $_SERVER["REDIRECT_URL"] = "/".$url;
        $_SERVER["REDIRECT_QUERY_STRING"] = $url;
        $_SERVER["QUERY_STRING"] = $url;
        $_SERVER["REQUEST_URI"] = "/".$url;
    }
    function checkStrPos($str1,$str2)
	{
		if(strpos($str1,$str2)!==false)
		{
			return strpos($str1,$str2);
		}
		return -1;
    }
    function parseUrlPattern($url)
	{
        
		$u = explode("?",$url);
		$u[0]=trim($u[0],"/");
		$url = explode("/",$u[0]);
        $out = array();
        //print_r($url);
		for($i=0;$i<count($this->urlPattern);$i++)
		{
			$pattern = $this->urlPattern[$i];
			$p = explode("?",$pattern);
			$p[0] = trim($p[0],"/");
            $pattern = explode("/",$p[0]);
            //echo count($pattern)."..".count($url)."<br>";
			if(count($pattern)==count($url))
			{
                
				for($j=0;$j<count($pattern);$j++)
				{
					$pn = str_replace("}","",str_replace("{","",$pattern[$j]));
                    $out[$pn]=$url[$j];
                    
                }
                
                if(!isset($out["APP_NAME"]))
                {
                    $out["APP_NAME"]=$this->defaultApp;
                }
                if(!isset($out["CONTROLLER"]))
                {
                    $out["CONTROLLER"]=$this->defaultController;
                }
                if(!isset($out["METHOD"]))
                {
                    $out["METHOD"]=$this->defaultMethod;
                }
                return $out;
			}
			
        }
        echo "No url pattern define or invalid pattern";
        exit(0);
	}
    function run()
    {
        global $app;
        
        $uri = $_SERVER["REQUEST_URI"];
        $self = $_SERVER["SCRIPT_NAME"];
        $saleBase = dirname($self);
        /*
        $this->home = $self;
		if($this->checkStrPos($uri, $self)==0)
		{
            
			$uri=substr($uri, strlen($self));	
		}
		else if($this->checkStrPos($uri, $saleBase)==0)
		{
			$uri=substr($uri, strlen($saleBase));	
        }
        */
        
        $out = $this->parseUrlPattern($uri);
        //print_r($out);
        //echo $uri;
        /*
        if($this->defaultApp!="")
        {
            $this->appName = $this->defaultApp;
            if($uri=="")
            {
                $u = array();
                $u[0]=$this->defaultApp;
                $u[1]=$this->defaultController;
                $u[2]=$this->defaultMethod;
                $ur = join("/",$u);
                $this->reWriteUrl($ur);
            }
            else
            {
                $uu = explode("/",$uri); 
                
                if(count($uu)==1)
                {
                    $uri = $uu[0]."/".$this->defaultController."/".$this->defaultMethod;
                }
                else if(count($uu)==2)
                {
                    $uri = $this->defaultApp."/".$uri;
                }
                $u = explode("/",$uri);             
                if(count($u)>0)
                {
                    $this->appName = $u[0];
                    
                    //array_shift($u);
                    //print_r($u);
                    
                    $ur = join("/",$u);
                    $this->reWriteUrl($ur);
                }
            }
        }
        else
        {
            
            if($uri!="")
            {
                $u = explode("/",$uri);  
                print_r($u);              
                if(count($u)>0)
                {
                    $this->appName = $u[0];
                    
                    array_shift($u);
                    //print_r($u);
                    
                    $ur = join("/",$u);
                    $this->reWriteUrl($ur);
                }
                else
                {
                    $u = array();
                    $u[0]=$this->defaultController;
                    $u[1]=$this->defaultMethod;
                    $ur = join("/",$u);
                    $this->reWriteUrl($ur);
                }
            }
            else
            {
                echo "Application name not define";
            }
        }
        //print_r($_SERVER["QUERY_STRING"]);
        */
        $this->appName = $out["APP_NAME"];
        
        if($this->appName!="")
        {
            if(!is_dir($this->name."/".$this->appName))
            {
                echo "Appication $this->appName is not found.";

                return;
            }
            $this->app = new MVCApplication($this->name,$this->appName);   
            $this->app->setHome($this->home);
            $this->app->setDefault($this->defaultController,$this->defaultMethod);
            if($this->urlSecurity)
            {
                $this->app->enableUrlSecurity();
            }
            if($this->userValidator!=null)
            {
                $this->app->enableUserSecurity($this->userValidator,$this->failureUrl);
            }
            if($this->sessionId!="")
            {
                $this->app->enableSessionSecurity($this->sessionId);
            }

            $this->instance = $this;
            $this->app->sso = $this->sso;
            $app = $this->app;
            for($i=0;$i<count($this->anonymousUrls);$i++)
            {
                $this->app->addAnonymousMethod($this->anonymousUrls[$i]["class"],$this->anonymousUrls[$i]["method"]);
            }
            for($i=0;$i<count($this->publicUrls);$i++)
            {
                $this->app->addAnonymousMethod($this->publicUrls[$i]["class"],$this->publicUrls[$i]["method"]);
            }
        //    $this->app->urlPattern = $this->urlPattern;
            //print_r($out);
            $this->app->run($out);

        }
        
    }
}
?>