<?php
class CookieFile
{
	var $id;
	var $path;
	var $cookieId;
	var $cookieVal;
	var $age;
	var $filepath;
	function CookieFile($id,$path="/tmp/cookieFile",$age=1)
	{
		exec("mkdir -p $path");
		$this->path = $path;
		$this->id = $id;
		$this->age = $age;
		$c= new Cookies();
		if($c->isExist($this->id))
		{
			
			$this->cookieVal = $c->get($this->id);
			$enc = new Encryption($salt="");
			$this->filepath = $enc->decrypt($this->cookieVal);
		}
	}
	function write($data,$utf=false)
	{
		$c = new Cookies();
		if(!$c->isExist($this->id))
		{
			$this->filepath = $this->path."/".uniqid("cookieFile_",true);
			
		}
		$this->cookieId = $this->id;
		$enc = new Encryption($salt="");
		$this->cookieVal = $enc->encrypt($this->filepath);

		$c->set($this->cookieId,$this->cookieVal,time()+($this->age*86400));
		$file=new File($this->filepath);
		if($utf)
		{
			$file->writeUTF();
			$file->append($data);
		}
		else
		{
			$file->write($data);
		}
		
		return true;
	}
	function read()
	{
		if(file_exists($this->filepath))
		{
		$file=new File($this->filepath);
		return $file->read();
		}
		else
		{
		return null;
		}
	}
}
?>