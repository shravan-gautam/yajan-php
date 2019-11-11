<?php
class YajanCacheManager
{
	function __construct($cmd)
	{
		global $CS,$DEFAULT_DB_CONFIG,$FORM_BUILDER_VAR,$OUTPUT_CACHE_LOCATION;
		$this->cmd = $cmd;
		$this->version = "1.1";
		$CS->setFGColor("yellow");
		$CS->cout("\n\nWelcome Yajan Cache Manager $this->version\n\n");	
		
		if(!isset($OUTPUT_CACHE_LOCATION))
		{
			$CS->showError("OUTPUT_CACHE_LOCATION is not define. please set in config");
			$this->status=false;
		}
		else
		{
			$this->status=true;
		}
		
	}
	function run()
	{
		global $CS,$DB_REG,$__run;
		if(count($this->cmd)<4)
		{
			$db =0;
		}
		else
		{
			$db =$this->cmd[3];
		}

		$sp = $DB_REG->getProperty($db);
		$this->db = new Connection($sp['name']);
		$cmd = "";
		$this->dbName = $sp['name'];
		$this->dbDriver = $sp['driver'];
		$this->prompt = "$this->dbName";
		while($cmd!="exit")
		{
			if(isset($__run))
			{
				$cmd=$__run;
				echo "executing \"$cmd\"\n";
			}
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="show")
			{
				$this->show($cmd);
			}
			else if($cmd[0]=="cleanup")
			{
				$this->cleanup($cmd);
			}
			else if($cmd[0]=="clear")
			{
				$this->clear($cmd);
			}
			if(isset($__run))
			{
				break;
			}
			echo "YCM: > ";
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}
	function show($cmd)
	{
		global $OUTPUT_CACHE_LOCATION;
		if($cmd[1]=="cache")
		{
			if($cmd[2]=="header")
			{
				if($cmd[3]=="all")
				{
					$dc = new DataCache();
					$r=$dc->getCache();
					$r->showCLITable();
				}	
				else
				{
					$dc = new DataCache($cmd[3]);
					$r=$dc->getInfo();
					$r->showCLITable();					
				}
			}
		}
		else if($cmd[1]=="info")
		{
			$r = new Recordset();
			$r->addColumns("Type","description","value");
			exec("du $OUTPUT_CACHE_LOCATION -sh | awk '{print $1}'",$output);
			
			$r->add("Disk","Total cache size",$output[0]);
			$output=array();
			exec("ls $OUTPUT_CACHE_LOCATION/*.dx | wc -l",$output);
			
			$r->add("Disk","Total cached urls",$output[0]);
			
			$data = file_get_contents("$OUTPUT_CACHE_LOCATION/trafic.stat");
			$data=explode("|",$data);
			$r->add("Statistic","Total request receive by cache manager",$data[0]);
			$r->add("Statistic","Total request read by cache",$data[1]);
			$r->add("Statistic","Total request write by cache",$data[3]);
			$r->add("Statistic","Total request forwered to application",$data[2]);
			
			$r->showCLITable();
			
			
		}
	}
	function cleanup($cmd)
	{
		$dc = new DataCache();
		$r=$dc->getCache();
		$count=0;
		for($i=0;$i<$r->count;$i++)
		{
			if($r->data[$i]["SIZE"]==0)
			{
				$dc->clear($r->data[0]["ID"]);
				$count++;
			}
		}
		echo "cleanup complite. $count rows change\n";

	}
	function clear($cmd)
	{
		global $OUTPUT_CACHE_LOCATION;
		if($cmd[1]=="statistic")
		{
			unlink("$OUTPUT_CACHE_LOCATION/trafic.stat");
			echo "Cache statistic clear.";
		}
	}
}
?>