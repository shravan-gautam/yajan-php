<?php
class DataBox1_2
{
	private $data;
	var $enc;
	private $id;
	var $message;
	private $timestamp;
	var $str;
	private $version;
	var $salt;
	var $saltIntration;
	var $base64;
	var $compressionLevel;
	private $bigData;
	private $bidDataDestinationType;
	private $bidDataDestination;
	private $bDtPointer;
	private $keyList;
	private $binDataTmp;
	private $bDtRowIndex;
	private $bDtRowPointer;
	function DataBox1_2($id,$salt='')
	{
		
		$this->id = $id;
		$this->bDtRowIndex=0;
		$this->bDtRowPointer=0;
		$this->data = array();
		$this->enc = "asci";
		$this->timestamp = time();
		$this->version = "1.2";
		$this->salt = $salt;
		$this->saltIntigration = true;
		$this->base64 = true;
		$this->compressionLevel = 9;
		$this->bigData=false;
		$this->bidDataDestinationType="";
		$this->bidDataDestination="";
		$this->bDtPointer=null;
		$this->keyList = array();
		$this->binDataTmp = null;
	}

	function bigDataWrite($type,$dest="")
	{
		$this->bigData =true;
		$this->bidDataDestinationType=$type;
		$this->bidDataDestination=$dest;
		if($this->bidDataDestinationType=="file")
		{
			$this->bDtPointer = new File($this->bidDataDestination);
			$this->bDtPointer->write("");
		}
	}
	function bigDataRead($type,$data)
	{
		$this->bigData =true;
		if($type=="stream")
		{
			$this->binDataTmp = "/tmp/".uniqid(true);
			$this->bDtPointer = new File($this->binDataTmp);
			$this->write($data);
		}
		else if($type=="file")
		{
			$this->bDtPointer = new File($data);
		}
		if($this->parse($this->bDtPointer->readLastLine()))
		{
			
			
		}
	}
	function secure($v)
	{
		if($v)
		{
			$this->saltIntigration = false;
			$this->base64 = false;
		}
		else
		{
			$this->saltIntigration = true;
			$this->base64 = true;
		}
	}
	function getImage($name)
	{
		$str = $this->getObject($name);
		$img = new Image($name);
		$img->embedStream($str);
		return $img;
	}
	function add($key,$val)
	{
		
		if(!$this->bigData)
		{
			$this->data[$key]=$val;
			$type = gettype($val);
			if($type=="string")
			{
				$size = strlen($val);
			}
			else
			{
				$size = 0;
			}
		}
		else
		{	
			$type = gettype($val);
			$t = array();
			$t[$key]=$val;
			$d = $this->dataProcessor($t)."\n";
			$size = strlen($d);
			if($this->bidDataDestinationType=="file")
			{
				$this->bDtPointer->append($d);
			}
			else if($this->bidDataDestinationType=="stream")
			{
				echo $d;
			}
			unset($t);
			unset($d);
		}
		$this->keyList[count($this->keyList)]=array("key"=>$key,"size"=>$size,"type"=>$type);
		
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
	function getObject($key="")
	{
		if($this->bigData ==true)
		{
			if($key!="")
			{
				$p=0;
				$i=0;
				for($i=0;$i<count($this->keyList)-1;$i++)
				{
					if($this->keyList[$i]['key']==$key)
					{
						break;
					}
					else
					{
						$p+=$this->keyList[$i]['size'];
					}
				}
				$str = $this->bDtPointer->readLineByPointer($p);
				$obj = $this->dataDeProcessor($str);
				if(isset($obj[$key]))
				{
					return $obj[$key];
				}
				else
				{
					return false;
				}
			}
			else
			{
				//$this->keyList[$i]['size'];
				if($this->bDtRowIndex>=count($this->keyList))
				{
					return false;
				}
				$str = $this->bDtPointer->readLineByPointer($this->bDtRowPointer);
				$obj = $this->dataDeProcessor($str);
				if(!$obj)
				{
					return false;
				}
				
				$this->bDtRowPointer+=$this->keyList[$this->bDtRowIndex]['size'];
				$this->bDtRowIndex+=1;
				$x = array_keys($obj);
				return $obj[$x[0]];
				
			}
		}
		else
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
			return  serialize($this->data[$key]);
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

	function dataProcessor($temp)
	{
		
		$c = new Compression();
		$c->level = $this->compressionLevel;
		$str = "";
		
		if($this->enc=="base64")
		{
			$str = $c->compress(base64_encode(serialize($temp)));
		}
		else if($this->enc=="enc1")
		{
			$e = new Encryption($this->salt);
			if($this->saltIntigration)
			{
				$e->saltIntigration = true;
			}
			//echo $e->encrypt($c->compress(serialize($temp)));
			$str = $e->encrypt($c->compress(serialize($temp)));
		}
		else
		{
			$str = $c->compress(serialize($temp));
		}
		
		unset($temp);
		if($this->base64)
		{
			$str = base64_encode($str);
		}
		return strtoupper($this->id).":".$str;
		
	}
	function toString($data=true)
	{
		if(!$this->bigData)
		{
			$temp = array();
			$temp['id']=$this->id;
			$temp['version']=$this->version;
			$temp['data']=$this->data;
			$temp['time']=$this->timestamp;
			$temp['enc']=$this->enc;
			$temp['keylist']=$this->keyList;
			return $this->dataProcessor($temp);
		}
		else
		{
			return null;
		}
	}
	function bigDataClose()
	{
		$temp = array();
		$temp['id']=$this->id;
		$temp['version']=$this->version;
		$temp['time']=$this->timestamp;
		$temp['data']=$this->data;
		$temp['enc']=$this->enc;
		$temp['keyList']=$this->keyList;
		$d = $this->dataProcessor($temp);
		if($this->bidDataDestinationType=="file")
		{
			$this->bDtPointer->append($d);
		}
		else if($this->bidDataDestinationType=="stream")
		{
			echo $d;
		}
	}
	function dataDeProcessor($str)
	{
		
		if(strpos($str,strtoupper($this->id).":")===false)
		{
			$this->message = "Invalid string";
			$this->str  =$str;
			return false;
		}
		if(strpos($str,strtoupper($this->id).":")<>0)
		{
			$this->message = "Invalid string";
			$this->str = $str;
			return false;
		}
		
		$str = str_replace(strtoupper($this->id).":","",$str);
		if($this->base64)
		{
			$str = base64_decode($str);
		}
		$obj = null;
		$c = new Compression();
		$c->level = $this->compressionLevel;
		
		if($this->enc=="base64")
		{
			$obj = unserialize(base64_decode($c->uncompress($str)));
		}
		else if($this->enc == "enc1")
		{
			
			$e = new Encryption($this->salt);
			$e->saltIntigration = $this->saltIntigration;
			
			$obj = unserialize($c->uncompress($e->decrypt($str)));
		}
		else
		{
			$obj = unserialize($c->uncompress($str));
		}
		return $obj;
	}
	function parse($str)
	{
		$obj = $this->dataDeProcessor($str);
		
		if($obj==false)
		{
			return false;
		}
		$this->id  = $obj['id'];
		$this->version = $obj['version'];
		$this->data = $obj['data'];
		$this->timestamp = $obj['time'];
		$this->enc = $obj['enc'];
		if(isset($obj['keyList']))
		{
			$this->keyList = $obj['keyList'];
		}
		
		return true;
	}
	function toSession()
	{
		$_SESSION[$this->id]=$this->toString();
	}
	function toCookie($time=0,$path="/",$domain="",$secure=false)
	{
		setcookie($this->id,$this->toString(),$time,$path,$domain,$secure);
	}
	function fromSession()
	{
		return $this->parse($_SESSION[$this->id]);
	}
	function toFile($file)
	{
		import("io");
		$file = new File($file);
		$file->write($this->toString());
	}
	function toCookieFile()
	{
		$f = new CookieFile($this->id);
		$f->write($this->toString());
	}
	function fromCookieFile()
	{
		$f = new CookieFile($this->id);
		return $this->parse($f->read());
	}
	function fromFile($file)
	{
		import("io");
		
		$f = new File($file);
		return $this->parse($f->read());
	}
	function fromString($str)
	{
		return $this->parse($str);
	}
	function fromCookie()
	{
		
		if(isset($_COOKIE[$this->id]))
		{
			return $this->parse($_COOKIE[$this->id]);
		}
		else
		{
			return false;
		}
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
		$this->base64=true;
		$this->compressionLevel=0;
		$this->saltIntration=false;
		$this->enc = "base64";
		echo '<script type="text/javascript">
		var '.$this->id.' = new DataBox("'.$this->id.'");
		'.$this->id.'.enc="'.$this->enc.'";
		'.$this->id.'.salt="'.$this->salt.'";
		'.$this->id.'.base64="'.$this->base64.'";
		'.$this->id.'.saltIntration="'.$this->saltIntration.'";
		'.$this->id.'.parse("'.$this->toString().'");
		</script>';
	}
}
if($databoxVersion=="1.2")
{
class DataBox extends DataBox1_2
{
	function DataBox($id,$salt='')
	{
		parent::DataBox1_2($id,$salt);
	}
}
}
?>