<?php
class FormBuilderForm
{
	var $name;
	var $property;
	var $path;
	var $datablocks;
	var $views;
	function FormBuilderForm($name)
	{
		global $FORM_BUILDER_VAR;
		
		$this->name=$name;
		$this->path= "$FORM_BUILDER_VAR/$name";
		include "$this->path/property.php";
		$this->property= $formProperty;
		
		$path = $this->path."/datablock/";
		$p  = new Path($path);
		$list = $p->getRecordset();
		$this->datablocks=array();
		for($i=0;$i<$list->count;$i++)
		{
			
			$this->datablocks[$i]=$this->getDatablock($list->data[$i]["NAME"]);
		}

		$path = $this->path."/view/";
		$p  = new Path($path);
		$list = $p->getRecordset();
		$this->views=array();
		for($i=0;$i<$list->count;$i++)
		{
			$this->views[$i]=$this->getView($list->data[$i]["NAME"]);
		}	

	}
	function getRuntimePath()
	{
		$app= $this->getProperty("application");
		$name=$this->name;
		$path="/$app/$name";
		return $path;
	}
	function getAddModulePath()
	{
		$runtimePath=$this->getRuntimePath();
		if($this->getProperty("urlMode")=="module")
		{
			return $runtimePath."/add";
		}
		else
		{
			return $runtimePath."/add.php";
		}
	}
	function getEditModulePath()
	{
		$runtimePath=$this->getRuntimePath();
		if($this->getProperty("urlMode")=="module")
		{
			return $runtimePath."/edit";
		}
		else
		{
			return $runtimePath."/edit.php";
		}
	}
	function getRemoveModulePath()
	{
		$runtimePath=$this->getRuntimePath();
		if($this->getProperty("urlMode")=="module")
		{
			return $runtimePath."/remove";
		}
		else
		{
			return $runtimePath."/remove.php";
		}
	}
	function getListModulePath()
	{
		$runtimePath=$this->getRuntimePath();
		if($this->getProperty("urlMode")=="module")
		{
			return $runtimePath."/list";
		}
		else
		{
			return $runtimePath."/list.php";
		}
	}
	function getInitModulePath()
	{
		$runtimePath=$this->getRuntimePath();
		if($this->getProperty("urlMode")=="module")
		{
			return $runtimePath."/init";
		}
		else
		{
			return $runtimePath."/init.php";
		}
	}	
	function getProperty($name)
	{
		return $this->property[$name];
	}
	function getDatablocks()
	{
		return $this->datablocks;
	}
	function getDatablock($name)
	{
		
		$o = new FormBuilderDatablock($this,$name);
		return $o;
	}
	function getView($name)
	{
		$o = new FormBuilderView($this,$name);
		return $o;
	}
	function writeFile($filename,$text)
	{
		file_put_contents("$this->path/output/$filename",$text);
	}
	function install($module)
	{
		global $CS;
		$app=$this->getProperty("application");
		$modulePath = "$module/$app/$this->name";
		if(!is_dir("$module/$app"))
		{
			mkdir("$module/$app");
		}
		if(!is_dir("$module/$app/$this->name"))
		{
			mkdir("$module/$app/$this->name");
		}
		exec("cp -n  $this->path/output/* $module/$app/$this->name/");
		$CS->showInfo("module install");
	}
	function getViewHtml()
	{
		$html = "";
		for($i=0;$i<count($this->views);$i++)
		{
			$view = $this->views[$i];
			
			$html .= '<div>'.$view->getHtml().'</div>';
		}
		return $html;
	}
}
?>