<?php
class Uploader
{
	var $file;
	var $name;
	var $size;
	var $type;
	var $tempname;
	var $error;
	var $ext;
	var $message;
	var $destination;
	var $autoName;
	var $targate;
	var $allowExts;
	var $sizeLimit;
	var $fileIndex;
	var $count;
	var $overwrite;
	var $nullFile;
	function __construct($file)
	{

		if(!isset($file['name']))
		{
			$this->nullFile=true;
			return ;
		}
		$this->file=$file;
		$this->name = $file['name'];
		$this->ext = array();
		if(gettype($this->name)=="string")
		{
			
			if($this->name=="")
			{
				$this->nullFile=true;
				return ;
			}
			$this->count = 1;
			if(pathinfo($file['name'], PATHINFO_EXTENSION)!="")
			{
				$this->ext[count($this->ext)] = (pathinfo($file['name'], PATHINFO_EXTENSION));	
				//$this->ext[count($this->ext)] = mime_content_type($file["tmp_name"]);	
			}
			else
			{
				if($file["error"]==0)
				{
					$s =  mime_content_type($file["tmp_name"]);
					$s = explode("/",$s);				
					$this->ext[count($this->ext)] =$s[1];					
				}
			}
		}
		else
		{
			
			for($i=0;$i<count($this->name);$i++)
			{
				if($this->name[$i]=="")
				{
					$this->nullFile=true;
					return ;
				}
			}
			$this->count = count($this->name);
			for($i=0;$i<$this->count;$i++)
			{

				if( (pathinfo($file['name'][$i], PATHINFO_EXTENSION))!="")
				{
					$this->ext[count($this->ext)] = (pathinfo($file['name'][$i], PATHINFO_EXTENSION));	
				}
				else
				{
					$s =  mime_content_type($file["tmp_name"][$i]);
					$s = explode("/",$s);
					$this->ext[count($this->ext)] = $s[1];
				}
			}			
		}

		$this->nullFile=false;
		$this->size = $file['size'];
		$this->type = $file['type'];
		$this->tempname = $file['tmp_name'];
		$this->error = $file['error'];
		$this->autoName=false;
		$this->message="";
		$this->targate=array();
		$this->dastination=array();
		$this->allowExts="";
		$this->sizeLimit=0;
		$this->fileIndex=0;
		
		
		$this->overwrite=false;


		

	}
	function getTempname()
	{
		print_r($this->tempname);
		return $this->tempname[$this->fileIndex];
	}

	function allow()
	{
		$args = func_get_args();
		//$args = array_map("strtolower",$args);
		$this->allowExts = $args;

	}
	function sizeLimit($limit)
	{
		$this->sizeLimit =$limit;
	}
	function getSize()
	{
		return $this->size[$this->fileIndex];
	}
	function getType()
	{
		return $this->type[$this->fileIndex];
	}
	function getName()
	{
		return $this->name[$this->fileIndex];
	}
	function getExt()
	{
		return $this->ext[$this->fileIndex];
	}
	function getCountFiles($targate)
	{
		
		$targate = str_replace(".".$this->ext[$this->fileIndex],"*",$targate);
		$cmd = "ls $targate | wc -l";
		
		return exec($cmd);
	}
	function getDestination()
	{
		
		if(count($this->destination)==1)
		{
			return $this->destination[0];
		}
		else
		{
			return $this->destination;
		}
	}
	function getRecordset()
	{
		$r = new Recordset();
		$r->addColumns("name","path","extension","size","dir");
		for($i=0;$i<count($this->destination);$i++)
		{
			$name = $this->destination[$i];
			$path = $this->targate[$i];
			$dir = dirname ($path);

			//$ext = pathinfo($path, PATHINFO_EXTENSION);
			$ext = mime_content_type($path);
			$ext = explode("/",$ext);
			$ext = $ext[1];
			$size = filesize($path);
			$r->add($name,$path,$ext,$size,$dir);
		}
		return $r;
	}
	function getDestinationPath()
	{
		if(count($this->targate)==1)
		{
			return $this->targate[0];
		}
		else
		{
			return $this->targate;
		}
	}
	function getMessage()
	{
		return $this->message;
	}
	function autoName($val)
	{
		$this->autoName=$val;
		
	}
	function overwrite($val)
	{
		$this->overwrite=$val;
	}
	function isNull()
	{
		return $this->nullFile;
	}
	function upload($targate,$filename="")
	{	
		
		if($this->nullFile==true)
		{
			$this->message = "Null files";
			return false;
		}
		//print_r($this->file);
		
		if(gettype($this->file["error"][0])=="array")
		{
			for($i=0;$i<count($this->file["error"]);$i++)
			{
				if($this->file["error"][$i]!=0)
				{
					$this->message = "File not uploaded properly. ";
					return false;
				}
			}
		}
		else if(gettype($this->file["error"])=="integer")
		{
			if($this->file["error"]!=0)
			{
				$this->message = "File not uploaded properly. ";
				return false;
			}
		}
		if(is_string($this->name) && $this->name=="")
		{
			$this->message = "name is null";
			$this->destination[$this->fileIndex]="";
			$this->targate[$this->fileIndex]="";
			return false;
		}
		
		for($i=0;$i<$this->count;$i++)
		{
			if($this->error[$i]!=0)
			{
				$this->message= "Error code : ".$this->error[$i];
				return false;
			}
		}
		if(!is_writable($targate))
		{
			$this->message= "$targate is not writable";
			return false;
		}

		if($this->allowExts!="")
		{
			for($i=0;$i<$this->count;$i++)
			{
				if(gettype($this->allowExts)=="array")
				{
					
					if(!in_array($this->ext[$i],$this->allowExts))
					{
						$this->message=".".$this->ext[$i]." file is not allowed to upload.";
						return false;
					}
				}
				else if(gettype($this->allowExts)=="string")
				{
					if($this->ext[$i]!=$this->allowExts)
					{
						$this->message="This file type is not allowed to upload.";
						return false;
					}
				}
			}
		}
		for($i=0;$i<$this->count;$i++)
		{
			if($this->sizeLimit>0 && $this->sizeLimit<$this->size[$i])
			{
				$this->message="File size is over to allow uploading size.";
				return false;
			}
		}
	
		for($i=0;$i<$this->count;$i++)
		{
			$this->fileIndex=$i;
			
			if(!$this->_upload($targate,$filename))
			{
				return false;
			}
		}
		return true;
		
	}
	private function _upload($targate,$filename="")
	{
		$targate = rtrim($targate,"/");
		if($filename=="")
		{
			if(gettype($this->name)=="string")
			{
				$filename = $this->name;
			}
			else
			{
				$filename = $this->name[$this->fileIndex];
			}
			$filename = str_replace(" ","_",$filename);
			$filename = str_replace("(","_",$filename);
			$filename = str_replace(")","_",$filename);
		}
		
		if($this->autoName==true)
		{
			
			$count = $this->getCountFiles($targate."/".$filename);

			if($count>0)
			{
				$filename = str_replace(".","_".$count.".",$filename);
			}
		}
		else
		{
			if(file_exists($targate."/".$filename))
			{
				if($this->overwrite==false)
				{
					$this->message="Destination is allrady exist";
					return false;
				}
			}
		}
		
		$tDir = $targate;
		$targate = $targate."/".$filename;
		
		$this->destination[$this->fileIndex]=$filename;
		$this->targate[$this->fileIndex]=$targate;
		if(!file_exists($tDir))
		{
			$this->message= "Destination '$tDir' directory not exist";
			return false;
		}
		if(!is_writable($tDir))
		{
			$this->message= "Destination '$tDir' directory not writeable";
			return false;
		}		
		if(gettype($this->tempname)=="string")
		{
			$tmpName = $this->tempname;
		}
		else
		{
			$tmpName = $this->tempname[$this->fileIndex];
		}
		
		if(move_uploaded_file($tmpName,$targate))
		{
			return true;
		}
		else
		{
			$this->message= "Error in uploading";
			return false;
		}
	}
}
?>
