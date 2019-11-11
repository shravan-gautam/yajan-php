<?php
class DataCache
{
	var $target;
	var $id;
	var $cacheId;
	var $file;
	var $dx;
	var $life;
	var $info;
	var $log;
	var $cacheWrite;
	var $phpSession;
	var $totalRequest;
	var $cacheRequest;
	var $appRequest;
	var $writeRequest;
	var $clientType;
	function __construct($id="")
	{
		global $OUTPUT_CACHE_LOCATION;
		if(!is_dir($OUTPUT_CACHE_LOCATION))
		{
			exec("mkdir -p $OUTPUT_CACHE_LOCATION");
		}
		$this->totalRequest=0;
		$this->cacheRequest=0;
		$this->appRequest=0;
		$this->writeRequest=0;
		$this->life=1;
		$this->phpSession="";
		$this->cacheWrite=true;
		$this->target=$OUTPUT_CACHE_LOCATION;
		$this->clientType=$_SERVER["CLIENT_TYPE"];
		$this->id = $id;
		if(!is_dir("$this->target/$this->clientType"))
		{
			mkdir("$this->target/$this->clientType");
		}
		$this->file=new File("$this->target/$this->clientType/$this->id.cache");
		$this->dx = new DataBox1_2("cache");
		if($id!="")
		{
			$this->dx->fromFile("$this->target/$this->clientType/$this->id.dx");
		}
		
		$this->log = new Logfile("cache_change","var/log");
		if(file_exists($this->target."/trafic.stat"))
		{
			$data=file_get_contents($this->target."/trafic.stat");
			$data=explode("|",$data);
			$this->totalRequest=$data[0];
			$this->cacheRequest=$data[1];
			$this->appRequest=$data[2];
			$this->writeRequest=$data[3];
		}
		else
		{
			echo "no cache staticsfound.";
		}
		$this->totalRequest++;
	}
	function __destruct()
	{
		global $_PWD,$EXEC_MODE;
		//echo $this->totalRequest.".".$this->cacheRequest.".".$this->appRequest."\n";
		$this->appRequest=($this->totalRequest-$this->cacheRequest);
		if($EXEC_MODE!="CLI")
		{
			$fhandle = fopen("$_PWD/$this->target/trafic.stat","w");
			fwrite($fhandle,"$this->totalRequest|$this->cacheRequest|$this->appRequest|$this->writeRequest");
			fclose($fhandle);
		}
	}
	function getCache()
	{
		$list = new Path("$this->target/$this->clientType");
		$list->setExt("dx");
		$filelist=$list->getRecordset();
		$r = new Recordset();
		$r->addColumns("id","info","last_updated","expire","life","size","mdhash");
		
		for($i=0;$i<$filelist->count;$i++)
		{
			$id=$filelist->data[$i]["NAME"];
			$id=str_replace(".dx","",$id);
			$r1 = $this->getInfo($id);
			$d = $r1->data[0];
			
			$r->add($d["ID"],$d["INFO"],$d["LAST_UPDATED"],$d["EXPIRE"],$d["LIFE"],$d["SIZE"],$d["MDHASH"]);
			
		}
		//print_r($r->data);
		return $r;
	}
	function setInfo($info)
	{
		$this->info = $info;
	}
	function getInfo($id="")
	{
		$r = new Recordset();
		$r->addColumns("id","info","last_updated","expire","life","size","mdhash");
		if($id=="")
		{
			$id=$this->id;
			$dx=$this->dx;
		}
		else
		{
			$dx=new DataBox1_2("cache");
			
			$dx->fromFile("$this->target/$this->clientType/$id.dx");
			
		}
		if(file_exists("$this->target/$this->clientType/$id.dx"))
		{
			$r->add($id,$dx->getObject("info"),$dx->getObject("last_updated"),$dx->getObject("expire"),$dx->getObject("life"),filesize("$this->target/$this->clientType/$id.cache"),$dx->getObject("md5"));

		}
		return $r;		
	}
	function setPhpSession($session)
	{
		$this->phpSession=$session;
		
	}
	function echoHeader()
	{
		$ar= array("Date","Server","X-Powered-By","Set-Cookie","Expires","Cache-Control","Pragma","Vary","Transfer-Encoding");
		$header=$this->dx->getObject("respHeader");
		$header=explode("\n",$header);
		foreach($header as $head)
		{
			//header($head);
			//echo $head."\n";
			$h=explode(":",$head);
			if(array_search($h[0],$ar)===false)
			{
				header($head);
			}
		}
	}
	function loadUrl($url)
	{
		
		
		//$data = file_get_contents($url);
		import("net");
		$http = new HttpRequest($url);
		//$http->mathod("GET");
		$http->addHeader("X-YajanDataCache: no");
		if($this->phpSession!="")
		{
			$http->addHeader("X-YajanPhpSession: $this->phpSession");
		}
		$http->addHeader("X-YajanClientType: ".$_SERVER["CLIENT_TYPE"]);
		$http->responceHeader(true);
		$data = $http->send();
		$header=$http->getHeader();
		//echo $data;
		$this->setText($data,$header);
		
		//echo $this->id."\n";
	}
	function clear($id="")
	{
		if($id=="")
		{
			$id=$this->id;
		}
		if($this->isAvilable($id))
		{
			unlink($this->target."/$id.dx");
			unlink($this->target."/$id.cache");
			return true;
		}
		return false;
	}
	function cacheWrite($v)
	{
		$this->cacheWrite=$v;
	}
	function cleanup()
	{
		$r = $this->getCache();
		for($i=0;$i<$r->count;$i++)
		{
			if($r->data[0]["SIZE"]==0)
			{
				$this->clear($r->data[0]["ID"]);
			}
		}
	}
	function setText($text,$header="")
	{
		if($this->file->isExist())
		{
			$this->file->delete();
		}
		if($text!="")
		{
			$text = str_replace("makeAsCache();","",$text);
			$this->file->write($text);
			$mdhash = md5($text);
			
			$date2 = date("Y-m-d H:i:s");		
			$this->dx->add("id",$id);
			$this->dx->add("life",$this->life);
			$this->dx->add("last_updated",$date2);
			$this->dx->add("info",$this->info);
			$this->dx->add("md5",$mdhash);
			$this->dx->add("respHeader",$header);
			$minits = $this->life*1440;
			$date1 = date("Y-m-d H:i:s",strtotime("+$minits minutes"));
			
			
			$this->dx->add("expire",$date1);
			$this->dx->toFile("$this->target/$this->clientType/$this->id.dx");

			chmod("$this->target/$this->clientType/$this->id.dx",0777);
			chmod("$this->target/$this->clientType/$this->id.cache",0777);
			$this->log->write("$this->id|$mdhash");
			$this->writeRequest++;
		}
	}
	function isAvilable($id="")
	{
		if($id=="")
		{
			$file = $this->file;
			$id=$this->id;
		}
		else
		{
			$file=new File("$this->target/$this->clientType/$id.cache");
		}
		return $file->isExist();
	}
	function isOutdated()
	{
		 $date1 = strtotime($this->dx->getObject("expire"));
		 
         if($date1 < time())
		 {
			 return true;
		 }
		 return false; 
	}
	function read($signature=true)
	{
		global $OUTPUT_CACHE_SIGNATURE;
		$str="";
		if($signature && $OUTPUT_CACHE_SIGNATURE)
		{
			$str="\n".'<!-- [DataCache]> ID:'.$this->id.' < [DataCache] -->';
		}
		$this->cacheRequest++;
		return $this->file->read().$str;
	}
	
	function makeCache($url)
	{
		global $OUTPUT_CACHE_MODE;
		echo '
		<script type="text/javascript">
			function makeAsCache()
			{
				var html = document.documentElement.outerHTML;
				html =escape(html);
				ajax("'.$url.'", "url="+btoa(window.location)+"&data="+ html,function(resp)
				{
					if(resp!="")
					{
						console.debug(resp);
					}
				},true);
			}
		';
		if($this->cacheWrite && $OUTPUT_CACHE_MODE=="web")
		{
			echo '
			makeAsCache();		
			';
		}
		echo '
		</script>';
		
	}
}
?>
