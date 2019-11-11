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
	function __construct($id="")
	{
		$this->life=1;
		$this->cacheWrite=true;
		$this->target="var/cache/datacache";
		$this->id = $id;
		$this->file=new File($this->target."/$this->id.cache");
		$this->dx = new DataBox1_2("cache");
		$this->dx->fromFile($this->target."/$this->id.dx");
		$this->log = new Logfile("cache_change","var/log");
	}
	function getCache()
	{
		$list = new Path($this->target);
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
			
			$dx->fromFile("$this->target/$id.dx");
			
		}
		if(file_exists("$this->target/$id.dx"))
		{
			$r->add($id,$dx->getObject("info"),$dx->getObject("last_updated"),$dx->getObject("expire"),$dx->getObject("life"),filesize($this->target."/$id.cache"),$dx->getObject("md5"));

		}
		return $r;		
	}
	function loadUrl($url)
	{
		$data = file_get_contents($url);
		$this->setText($data);
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
	function setText($text)
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
			$minits = $this->life*1440;
			$date1 = date("Y-m-d H:i:s",strtotime("+$minits minutes"));
			
			
			$this->dx->add("expire",$date1);
			$this->dx->toFile($this->target."/$this->id.dx");
			chmod($this->target."/$this->id.dx",0777);
			chmod($this->target."/$this->id.cache",0777);
			$this->log->write("$this->id|$mdhash");
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
			$file=new File($this->target."/$id.cache");
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
		$str="";
		if($signature)
		{
			$str="\n".'<!-- [DataCache]> ID:'.$this->id.' < [DataCache] -->';
		}
		return $this->file->read().$str;
	}
	
	function makeCache($url)
	{
		if($this->cacheWrite)
		{
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
			makeAsCache();
		</script>';
		}
	}
}
?>