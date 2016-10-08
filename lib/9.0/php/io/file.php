<?php
ini_set("auto_detect_line_endings", true);
class File
{
	var $file;
	var $filename;
	var $pointer;
	var $buffur;
	var $mode;
	function File($filename)
	{
		$this->mode="instant";
		$this->filename=$filename;
		$this->pointer=0;
		$this->buffur = 4096;
		$this->fileExists = true;
		if(!file_exists($filename))
		{
			$this->fileExists = false;
		}
	}
	function open($attr="r")
	{
		if(!$this->fileExists)
		{
			return false;
		}
		
		$this->file = fopen($this->filename,$attr);
		$this->mode="continew";
	}
	function close()
	{
		if($this->mode=="continew" && $this->file)
		{
			fclose($this->file);
		}
	}
	function seek($position)
	{
		$this->pointer = $position;
	}
	function writeUTF()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		if($this->mode=="instant")
		{
			$this->file = fopen($this->filename,"w");
		}
		fwrite($this->file, pack("CCC",0xef,0xbb,0xbf));
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
	}
	function size()
	{
		return filesize($this->filename);
	}
	function read()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		if($this->mode=="instant")
		{
			$this->file = fopen($this->filename,"r");
		}
		if(filesize($this->filename)>0)
		{
			$val = fread($this->file,filesize($this->filename));
		}
		else
		{
			$val = null;
		}
		if($this->mode=="instant")
		{
			fclose($this->file);
		}	
		return $val;
	}
	function write($string)
	{
		if($this->filename!="")
		{
			if($this->mode=="instant")
			{
				$this->file = fopen($this->filename,"w");
			}
			if($this->file)
			{
				fwrite($this->file,$string,strlen($string));
				if($this->mode=="instant")
				{
					fclose($this->file);
				}
			}
			
		}
	}
	function append($string)
	{

		if(file_exists($this->filename))
		{
			if($this->mode=="instant")
			{
				$this->file = fopen($this->filename,"a");
			}
			fwrite($this->file,$string,strlen($string));
			if($this->mode=="instant")
			{
				fclose($this->file);
			}
		}
		else
		{
			$this->write($string);
		}
	}
	function clear()
	{
		$this->write("");
	}
	function writeCSV($data)
	{
		if($this->mode=="instant")
		{
			$this->file = fopen($this->filename, 'a');
		}
		if(gettype($data)=="array")
		{
			foreach ($data as $fields) 
			{
				if(gettype($data)=="array")
				fputcsv($this->file, $fields);
			}
		}
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
	}
	function countLine()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		if($this->mode=="instant")
		{
			$this->file= fopen($this->filename, 'rb');
		}
		$lines = 0;
		
		while (!feof($this->file)) 
		{
			$lines += substr_count(fread($this->file, 8192), "\n");
		}
		
		$lines++;
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
		return $lines;
	}
	function readLastLine()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		$line = '';
		if($this->mode=="instant")
		{
			$this->file= fopen($this->filename, 'r');
		}
		$cursor = -1;

		fseek($this->file, $cursor, SEEK_END);
		$char = fgetc($this->file);
		while ($char === "\n" || $char === "\r") {
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($this->file);
		}
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			$line = $char . $line;
			fseek($this->file, $cursor--, SEEK_END);
			$char = fgetc($this->file);
		}
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
		return $line;
	}
	function nextLine()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		if($this->mode=="instant")
		{
			$this->open("r");
		}
		
		return  fgets($this->file);
		//print_r("AS".$line);
	}
	function toUnixFormat()
	{
		$u = uniqid();
		exec("tr -d '\15\32' < '".$this->filename."' > '".$this->filename.".$u'");
		exec("mv '".$this->filename.".$u' '".$this->filename."'");
	}
	function readLineByNumber($n)
	{
		if(!$this->fileExists)
		{
			return false;
		}
		if($this->mode=="instant")
		{
			$this->file = fopen($this->filename, 'rb');
		}
		$lineNo = 0;
		while (($line = fgets($this->file)) !== false) 
		{
			$n++;
			if($lineNo==$n)
			{
				if($this->mode=="instant")
				{
					fclose($this->file);
				}
				return $line;
			}
		}
		
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
		return false;
	}
	function readLineByPointer($p)
	{
		if(!$this->fileExists)
		{
			return false;
		}
		$this->seek($p);
		if($this->mode=="instant")
		{
			$this->file = fopen($this->filename, 'rb');
		}
		fseek($this->file,$this->pointer);
		$line = fgets($this->file, $this->buffur);
		if($this->mode=="instant")
		{
			fclose($this->file);
		}
		return $line;
	}
	function delete()
	{
		if(!$this->fileExists)
		{
			return false;
		}
		unlink($this->filename);
	}
	
}
if(!class_exists("Files"))
{
	class Files extends File
	{
		function Files($filename)
		{
			parent::File($filename);
		}
	}
}
?>