<?php
class DataBox1_1
{
	private $data;
	private $enc;
	private $id;
	var $message;
	private $timestamp;
	var $str;
	private $version;
	function __construct($id)
	{
		$this->id = $id;
		$this->data = array();
		$this->enc = "base64";
		$this->timestamp = time();
		$this->version = "1.1";
	}
	function getDataset()
	{
		return $this->data;
	}
	function add($key,$val)
	{
		$this->data[$key]=$val;
	}
	function addFile($key,$filename)
	{
		if(file_exists($filename))
		{
			$f = new File($filename);
			
			$this->add($key,$f->read());
			return true;
		}
		else
		{
			return false;
		}
	}
	function getImage($name)
	{
		$str = $this->getObject($name);
		$img = new Image($name);
		$img->embedStream($str);
		return $img;
	}
	function isExist($key)
	{
		if(array_search($key,$this->getElementList())===false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	function getObject($key)
	{
		if($this->isExist($key))
		{
			return $this->data[$key];
		}
		else
		{
			return null;
		}
	}
	function get($key)
	{
		return $this->getObject($key);
	}
	function getString($key)
	{
		if(!$this->isExist($key))
		{
			return null;
		}
		if($this->enc=="base64")
		{
			return base64_encode( serialize($this->data[$key]));
		}
		else
		{
			return  serialize($this->data[$key])	;
		}
	}
	function getRecordset($key)
	{
		$r = new Recordset();
		$r->fromString($this->getString($key));
		return $r;
	}
	function __toString()
	{
		return $this->toString();
	}
	function getElementList()
	{
		return array_keys($this->data);
	}
	function toString()
	{
		$temp = array();
		$temp['id']=$this->id;
		$temp['version']=$this->version;
		$temp['data']=$this->data;
		$temp['time']=$this->timestamp;
		$temp['enc']=$this->enc;
		if($this->enc=="base64")
		{
			return strtoupper($this->id).":".base64_encode(serialize($temp));
		}
		else
		{
			return strtoupper($this->id).":".serialize($temp);
		}
	}
	function parse($str)
	{
		$str = trim($str);
		if(strpos($str,strtoupper($this->id).":")===false)
		{
			$this->message = "Invalid string";
			$this->str  =$str;
			return false;
		}
		
		if(strpos($str,strtoupper($this->id).":")<>0)
		{
			$this->message = "Invalid string. mismatch id";
			$this->str = $str;
			return false;
		}
		
		//echo strpos($str,strtoupper($this->id).":");
		$obj = null;
		if($this->enc=="base64")
		{
			$obj = unserialize(base64_decode(str_replace(strtoupper($this->id).":","",$str)));
		}
		else
		{
			$obj = unserialize(str_replace(strtoupper($this->id).":","",$str));
		}
		
		
		$this->id  = $obj['id'];
		$this->version = $obj['version'];
		$this->data = $obj['data'];
		$this->timestamp = $obj['time'];
		$this->enc = $obj['enc'];
		
		return true;
	}
	function toSession()
	{
		$_SESSION[$this->id]=$this->toString();
	}
	function fromSession()
	{
		if(isset($_SESSION[$this->id]))
		{
			return $this->parse($_SESSION[$this->id]);
		}
		else
		{
			return false;
		}
	}
	function toFile($file)
	{
		import("io");
		$file = new File($file);
		$file->write($this->toString());
	}
	function fromFile($file)
	{
		import("io");
		$file = new File($file);
		return $this->parse($file->read());
	}
	function write()
	{
		echo $this->toString();
	}
	function show()
	{
		print_r($this->data);
	}
	function toJsObject()
	{
		echo 'var '.$this->id.' = new DataBox("'.$this->id.'");
		'.$this->id.'.parse("'.$this->toString().'");';
	}
}
if($databoxVersion=="1.1")
{
class DataBox extends DataBox1_1
{
	function __construct($id)
	{
		parent::__construct($id);
	}
}
}
?>
