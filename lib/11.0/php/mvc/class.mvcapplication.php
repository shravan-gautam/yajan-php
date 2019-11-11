<?php
class MVCApplication
{
	var $name;
	var $defaultController;
	var $defaultMethod;
	var $controller;
	var $method;
	var $uri;
	var $parm;
	var $errorTemplate;
	var $home;
	var $base;
	var $user;
	var $authUserFailedUrl;
	var $publicAccess;
	var $anonymousAccess;
	var $session;
	var $access;
	var $dir;
	var $userVarification;
	var $keyVarification;
	var $defaultAccessPrivate;
	var $instance;
	var $sso;
	var $urlPattern;
	function __construct($dir,$name)
	{
		$this->defaultAccessPrivate=false;
		$this->keyVarification=false;
		$this->anonymousAccess = array();
		$this->publicAccess=array();
		$this->name=$name;
		$this->home="";
		$this->dir = $dir;
		$this->userVarification=false;
		$this->base="$dir/$this->name";
		$this->session;
		$this->instance=null;
		$this->sso = null;
	}
	function enableUserSecurity($user,$url)
	{
		
		if(!method_exists($user,"isLogin"))
		{
			echo "Method isLogin not found in user object.";
			exit(0);
		}
		$this->setAuthUserObject($user,$url);
		$this->userVarification=true;
	}
	function enableSessionSecurity($session="")
	{
		if($session=="")
		{
			$session=$_SESSION["MVC_USER_SESSION"];
		}
		$this->setSession($session);
	}
	function enableUrlSecurity()
	{
		$this->keyVarification=true;
		$this->defaultAccessPrivate=false;
	}
	function addAnonymousMethod($className,$methodName)
	{
		$t = array();
		$t["className"]=$className;
		$t["methodName"]=$methodName;
		$this->anonymousAccess[count($this->anonymousAccess)]=$t;
	}
	function addPublicMethod($className,$methodName)
	{
		$t = array();
		$t["className"]=$className;
		$t["methodName"]=$methodName;
		$this->publicAccess[count($this->publicAccess)]=$t;
	}
	function isPublicAccess($className,$methodName)
	{
		for($i=0;$i<count($this->publicAccess);$i++)
		{
			if($this->publicAccess[$i]["className"]==$className && $this->publicAccess[$i]["methodName"]==$methodName)
			{
				return true;
			}
		}
		return false;
	}
	function isAnonymousAccess($className,$methodName)
	{
		for($i=0;$i<count($this->anonymousAccess);$i++)
		{
			if($this->anonymousAccess[$i]["className"]==$className && $this->anonymousAccess[$i]["methodName"]==$methodName)
			{
				return true;
			}
		}
		return false;
	}
	function setAuthUserObject($user,$failedUrl)
	{
		$this->user = $user;
		$this->authUserFailedUrl = $failedUrl;
	}
	function getJsHome($trim="")
	{
		$path = $_SERVER["SCRIPT_NAME"];
		$path = str_replace($trim,"",$path);
		return $path;
	}
	function setHome($path)
	{

		$this->home = $path;

	}
	function setDefault($controller,$method)
	{
		$this->defaultController=$controller;
		$this->defaultMethod=$method;
	}
	function setErrorTemplate($name)
	{
		$this->errorTemplate = $name;
	}
	function error($message)
	{
		if($this->errorTemplate!="")
		{
			include "$this->base/template/$this->errorTemplate.php";
		}
		else
		{
			echo $message;
		}
	}
	function setSession($session)
	{
		$this->session=$session;
	}
	function setResponceHeader($header)
	{
		header($header);
	}
	function _runController($controller,$methos)
	{
		$controllerFile = "$this->base/controller/$controller.php";
		//echo $controllerFile;
		//exit(0);
		if(file_exists($controllerFile))
		{
			require_once $controllerFile;
			$className = $this->controller;
			$method = $this->method;
			$obj = new $className();
			$obj->application = $this;
			if(method_exists($obj,$method))
			{
				$obj->$method();	
			}
			else
			{
				echo "Method "+$method+" is not found";
			}
		}
		else
		{
			echo "Controller ".$controller." not found";
		}
	}
	function loadController()
	{
		if($this->sso!=null)
		{
			if($sso->isValidAccess())
			{
				$type = $sso->getFunctionConfig("type");

				if($type=="")
				{
					$type="public";
				}
				if($type=="public")
				{
					$this->_runController($this->controller,$this->method);
				}
				else
				{
					if($sso->isValidUser())
					{
						$this->_runController($this->controller,$this->method);
					}
					else
					{
						echo "Invalid user";          
					}
				}
			}
			else
			{
				echo  "Invalid access.";
			}
			return;
		}
		$this->access = new AccessSecurity($this->name,$this->controller,$this->method,$this->defaultAccessPrivate);
		$this->access->setSession($this->session);
		if($this->keyVarification && $this->access->private && !$this->isPublicAccess($this->controller,$this->method))
		{
			if(!$this->access->isValid())
			{
				$m = $this->access->message;
				echo "Unauthrized access. $m";
				exit(0);
			}
		}

		$controllerFile = "$this->base/controller/$this->controller.php";
		//echo $controllerFile;
		//exit(0);
		if(file_exists($controllerFile))
		{
			require_once $controllerFile;
			$className = $this->controller;
			$method = $this->method;
			$obj = new $className();
			$obj->application = $this;
			//echo $className.".".$method;
			if(method_exists($obj,$method))
			{
				if($this->isAnonymousAccess($className,$method))
				{

					$obj->$method();
				}
				else
				{
					//print_r($user);
					if($this->userVarification)
					{
						if(!$this->user->isLogin())
						{
							header("Location: ".$this->authUserFailedUrl);
						}
						else
						{
							$obj->$method();	
						}
						//$obj->$method();	
					}
					else
					{
						$obj->$method();	
					}					
				}
				return true;
			}
			else
			{
				$this->error("method $method not found");
				return false;
			}
		}
		else
		{
			$this->error("controller $this->controller not found");
			return false;
		}
	}
	function initParm()
	{
		$p = explode("&",$this->parm);
		$a = array();
		for($i=0;$i<count($p);$i++)
		{
			$c = explode("=",$p[$i]);
			$a[$c[0]]=$c[1];
		}
		$this->parm = $a;
	}
	function initContenor($uri)
	{
		$uri = trim($uri,"/");
		if($uri=="")
		{
			$uri=$this->defaultController."/".$this->defaultMethod;
		}
		
		$u = explode("/",$uri);
		
		if(is_dir("$this->dir/".$u[0]))
		{
			$this->name = $u[0];
			$this->base="$this->dir/$this->name";
			array_shift($u);
		}
		$uri=join($u,"/");
		return $uri;
	}
	function checkStrPos($str1,$str2)
	{
		if(strpos($str1,$str2)!==false)
		{
			return strpos($str1,$str2);
		}
		return -1;
	}
	
	function run($parm)
	{
		$uri = $_SERVER["REQUEST_URI"];
		$uri = explode("?",$uri);
		$this->parm = $uri[1];
		$this->initParm();
		/*
		$uri = $_SERVER["REQUEST_URI"];
		$self = $_SERVER['SCRIPT_NAME'];
		
		$saleBase = dirname($self);
		
		$this->home = $self;
		if($this->checkStrPos($uri, $self)==0)
		{
			$uri=substr($uri, strlen($self));	
		}
		else if($this->checkStrPos($uri, $saleBase)==0)
		{
			$uri=substr($uri, strlen($saleBase));	
		}
		echo $uri;
		*/
		/*
		$uri = explode("?",$uri);
		$this->parm = $uri[1];
		$this->initParm();
		$uri = $this->initContenor($uri[0]);
		$uri = trim($uri,"/");
		
		if($uri!="")
		{
			$rq = explode("/",$uri);
			if(count($rq)==1)
			{
				$rq[1]=$this->defaultMethod;
			}
		}
		else
		{
			$rq=array();
			$rq[0]=$this->defaultController;
			$rq[1]=$this->defaultMethod;
		}
		*/
		$rq = array();
		foreach ($parm as $key => $value)
		{
			$rq[count($rq)]=$value;
		}
		$this->uri = $rq;
		$this->controller = $parm["CONTROLLER"];
		$this->method = $parm["METHOD"];
		
		if($this->controller!="" && $this->method!="")
		{
			$this->loadController();
		}
		
	}
}
?>