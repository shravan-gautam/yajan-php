<?php
class Path
{
	var $fileList;
	var $dirName;
	var $rec;
	var $ext;
	var $recordset;
	function __construct($dirName)
	{	
		if(!is_dir($dirName))
		{
			//throw new Exception("Directory is not found");
		}
		$this->dirName=$dirName;
		$this->fileList=array();
		$this->rec=true;
		$this->ext="*";
	}
	function exists()
	{
		if(!is_dir($this->dirName))
		{
			return false;
		}
		else
		{
			return true;
		}
		
	}
	function formatBytes($bytes, $precision = 2) 
	{ 
		$units = array('B', 'KB', 'MB', 'GB', 'TB'); 
/*
		$bytes = max($bytes, 0); 
		$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
		$pow = min($pow, count($units) - 1); 

		// Uncomment one of the following alternatives
		// $bytes /= pow(1024, $pow);
		// $bytes /= (1 << (10 * $pow)); 

		return round($bytes, $precision) . ' ' . $units[$pow]; 
*/
	} 
	function getFilelist()
	{
		return $this->_getFileList($this->dirName);
	}
	private function _getRecordset($name)
	{
		if ($handle = opendir($name)) 
		{
			while ($file = readdir($handle)) 
			{
				if($file != "." && $file!="..")
				{
					
					$name = $this->dirName."/".$file;
					
					if(is_file($name))
					{
						$filetime = filemtime($name);
						if($this->ext!="*")
						{
							if(substr($file,strlen($file)-strlen($this->ext),strlen($file))==$this->ext)
							{
								$this->recordset->add($file,$name,filesize($name),"FILE",$filetime);
							}
						}
						else
						{
							$this->recordset->add($file,$name,filesize($name),"FILE",$filetime);
						}
					}
					else if(is_dir($name))
					{
						
						if($this->rec==true)
						{	
							$filetime = filemtime($name);
							$this->recordset->add($file,$name,filesize($name),"FOLDER",$filetime);
							$this->_getRecordset($name);
						}
					}
				}
				
			}
			closedir($handle);
		}
		else
		{
			throw new Exception("Directory is not found.");
		}
	}
	function getRelativePath($to)
	{
		// some compatibility fixes for Windows paths
		$from = $this->dirName;
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
	function getRecordset()
	{
		$this->recordset = new Recordset();
		$this->recordset->addColumns("NAME","PATH","SIZE","TYPE","TIME");
		$this->_getRecordset($this->dirName);
		return $this->recordset;
		/*
		if ($handle = opendir($this->dirName)) 
		{
			while ($file = readdir($handle)) 
			{
				if($file != "." && $file!="..")
				{
					
					$name = $this->dirName."/".$file;
					
					if(is_file($name))
					{
						$filetime = filemtime($name);
						if($this->ext!="*")
						{
							if(substr($file,strlen($file)-strlen($this->ext),strlen($file))==$this->ext)
							{
								$this->recordset->add($file,$name,filesize($name),"FILE",$filetime);
							}
						}
						else
						{
							$this->recordset->add($file,$name,filesize($name),"FILE",$filetime);
						}
					}
					else if(is_dir($name))
					{
						
						if($this->rec==true)
						{	
							$filetime = filemtime($name);
							$this->recordset->add($file,$name,filesize($name),"FOLDER",$filetime);
							$this->_getFileList($name);
						}
					}
				}
				
			}
			closedir($handle);
			
			return $this->recordset;
		}
		else
		{
			throw new Exception("Directory is not found.");
		}
		*/
	}
	function setRecursive($r)
	{
		$this->rec-$r;
	}
	function setExt($ext)
	{
		$this->ext=$ext;
	}
	private function _getFileList($dirPath)
	{
		
		if ($handle = opendir($dirPath)) 
		{
			$temp = array();
			$i=0;
			while ($file = readdir($handle)) 
			{
				if($file != "." && $file!="..")
				{
					$name = $dirPath."/".$file;
					$temp = array();
					$temp['name']=$file;
					$temp['path']=$name;
					$temp['size']=filesize($name);
					$temp['time']=filemtime($name);
					if(is_file($name))
					{
						
						$temp['type']='FILE';
						if($this->ext!="*")
						{
							if(substr($file,strlen($file)-strlen($this->ext),strlen($file))==$this->ext)
							{
								$this->fileList[count($this->fileList)]=$temp;
							}
						}
						else
						{
							$this->fileList[count($this->fileList)]=$temp;
						}
					}
					else if(is_dir($name))
					{
						$temp['type']='FOLDER';
						if($this->rec==true)
						{
							$this->_getFileList($name);
						}
					}
				}
			}
			closedir($handle);
			return $this->fileList;
		}
		else
		{
			throw new Exception("Directory is not found");
		}
	}
}

if(!class_exists("Directorys"))
{
	class Directorys extends Path
	{
		function __construct($filename)
		{
			parent::__construct($filename);
		}
	}
}
?>
