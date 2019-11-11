<?php
class Controller
{
	var $application;
	function __construct()
	{

	}
	function loadModel($name)
	{
		$appBase = $this->application->base;
		$modelFile = "$appBase/model/$name.php";
		if(file_exists($modelFile))
		{
			require_once $modelFile;
			$obj = new $name();
			$obj->controller = $this;
			return $obj;
		}
		else
		{
			$this->application->error("model $name not find");
			return null;
		}
	}
	function loadTemplate($name,$view,$data=array())
	{
		$appBase = $this->application->base;
		$filename = "$appBase/template/$name.php";
		if(file_exists($filename))
		{
			//require_once $modelFile;
			include $filename;
		}
		else
		{
			$this->application->error("template $name not find");
			return null;
		}
	}
	function loadView($name,$data=array())
	{
		$appBase = $this->application->base;
		$filename = "$appBase/view/$name.php";

		if(file_exists($filename))
		{
			//require_once $modelFile;
			if(gettype($data)=="array")
			{
				foreach ($data as $key => $value) {
					$$key = $value;
				}
			}
			include $filename;
		}
		else
		{
			$this->application->error("view $name not find");
			return null;
		}
	}
}
?>