<?php
class YajanRepository
{
	var $sourceList;
	var $info;
	var $installedSnapFile;
	function __construct()
	{
		global $CS,$DEFAULT_DB_CONFIG,$YAJAN_DATA,$FORM_BUILDER_VAR,$OUTPUT_CACHE_LOCATION,$CONFIGURATION;
		
		$this->version = "1.1";

		if(!is_dir($YAJAN_DATA."/repository"))
		{
			mkdir($YAJAN_DATA."/repository");
			mkdir($YAJAN_DATA."/repository/data");
		}
		$this->sourceList=new Recordset();
		$this->sourceList->addColumns("id","name","description","path","included","excluded","branch");
		$this->sourceList->fromFile($YAJAN_DATA."/repository/sourcelist.conf");
		$this->info = new DataBox1_2("reposiroty");
		$this->info->fromFile($YAJAN_DATA."/repository/info.dx");


		$this->installedSnapFile=$YAJAN_DATA."/repository/inSnap.conf";
		if(!file_exists($this->installedSnapFile))
		{
			exec("touch $this->installedSnapFile");
		}
	}
	function write()
	{
		global $YAJAN_DATA;
		$this->info->toFile($YAJAN_DATA."/repository/info.dx");
	}
	function addSource()
	{
		global $CS;
		$CS->setFGColor("yellow");
		$CS->cout("Add new source directory\n");
		
		$CS->showInfo("Name of source directory");
		$name = $CS->read();
		$CS->showInfo("Description of source directory");
		$description = $CS->read();
		$CS->showInfo("Path of source directory");
		$path = $CS->read();
		$CS->showInfo("Included extantion [*.*]");
		$included = $CS->read();
		$CS->showInfo("Excluded extantion ");
		$excluded = $CS->read();
		$update=false;
		for($i=0;$i<$this->sourceList->count;$i++)
		{
			$this->sourceList->moveRecord($i);
			if($this->sourceList->get("name")==$name)
			{
				$this->sourceList->set("description",$description);
				$this->sourceList->set("path",$path);
				$this->sourceList->set("included",$included);
				$this->sourceList->set("excluded",$excluded);
				$this->sourceList->set("branch","master");
				$update=true;
			}
		}
		if(!$update)
		{
			$uid= uniqid("S",true);
			$this->sourceList->add($uid,$name,$description,$path,$included,$excluded,"master");
		}
		$this->sourceList->toFile();
	}
	function alter($cmd)
	{
		global $YAJAN_DATA,$CS;
		
		if($cmd[1]=="source")
		{
			if($cmd[3]=="set")
			{
				//alter source <name> set <parm>=<value>
				$name = $cmd[2];
				$parm = $cmd[4];
				$parm=explode("=",$parm);
				$value=$parm[1];
				$parm=$parm[0];
			
				$this->sourceList=new Recordset();
				$this->sourceList->fromFile($YAJAN_DATA."/repository/sourcelist.conf");
				for($i=0;$i<$this->sourceList->count;$i++)
				{
					if($this->sourceList->data[$i]["NAME"]==$name)
					{
						$this->sourceList->data[$i][strtoupper($parm)]=$value;
						break;
					}					
				}
				$this->sourceList->toFile();
				$CS->showInfo("Source $name alter complite");
			}
		}
	}
	function drop($cmd)
	{
		global $YAJAN_DATA,$CS;
		
		if($cmd[1]=="source")
		{
			
				//alter source <name> set <parm>=<value>
				$name = $cmd[2];
				
			
				$this->sourceList=new Recordset();
				$this->sourceList->fromFile($YAJAN_DATA."/repository/sourcelist.conf");
				$index= -1;
				for($i=0;$i<$this->sourceList->count;$i++)
				{
					if($this->sourceList->data[$i]["NAME"]==$name)
					{
						$index = $i;
						break;
					}
				}
				$id = $this->sourceList->data[$index]["ID"];
				exec("rm -r $YAJAN_DATA/repository/data/$id");
				unset($this->sourceList->data[$index]);
				$this->sourceList->data = array_values($this->sourceList->data);
				//print_r($this->sourceList->data);

				$this->sourceList->toFile();
				$CS->showInfo("Remove source $name");
			
		}
	}
	function run()
	{
		global $CS,$DB_REG,$CONFIGURATION,$__run;
		$CS->setFGColor("yellow");
		$CS->cout("\n\nYajan Repository Manager $this->version\n");			
		if($this->info->getObject("instanceId")=="")
		{
			$CS->showError("Unregister instance ".$CONFIGURATION->instanceName."[".$CONFIGURATION->instanceId."]\n\n");	
		}
		else
		{
			$CS->showOK("Registerd instance ".$CONFIGURATION->instanceName."[".$CONFIGURATION->instanceId."]\n\n");	
		}
		
		if(count($this->cmd)<4)
		{
			$db =0;
		}
		else
		{
			$db =$this->cmd[3];
		}
		
		$sp = $DB_REG->getProperty($db);
		$cmd = "";
		$this->prompt = "$this->dbName";
		while($cmd!="exit")
		{
			if(isset($__run))
			{
				echo "execute \"$__run\"\n";
				$cmd=$__run;
			}
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="show")
			{
				$this->show($cmd);
			}
			else if($cmd[0]=="add")
			{
				$this->add($cmd);
			}
			else if($cmd[0]=="alter")
			{
				$this->alter($cmd);
			}
			else if($cmd[0]=="drop")
			{
				$this->drop($cmd);
			}
			else if($cmd[0]=="checkout")
			{
				$this->checkout($cmd);
			}
			else if($cmd[0]=="register")
			{
				$this->register($cmd);
			}
			else if($cmd[0]=="commit")
			{
				$this->commit($cmd);
			
			}
			else if($cmd[0]=="sync")
			{
				$this->sync($cmd);
			}
			else if($cmd[0]=="restore")
			{
				$this->restore($cmd);
			}
			else if($cmd[0]=="help")
			{
				$this->help($cmd);
			}
			if(isset($__run))
			{
				break;
			}
			echo "YRM: > ";
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}
	function restore($cmd)
	{
		global $YAJAN_DATA,$CS;
		$source="";
		
		if(isset($cmd[2]))
		{
			$source = $cmd[2];
		}
		
		$full=false;
		if(isset($cmd[3]) && $cmd[3]=="full")
		{
			$full=true;
		}

		
		$r = new Recordset();
		$r->addColumns("snap_id","source","branch","source_name");
		for($k=0;$k<$this->sourceList->count;$k++)
		{
			$this->sourceList->moveRecord($k);
			$sourceBranch = $this->sourceList->get("branch");
			$id = $this->sourceList->get("id");

			$baseDir = $YAJAN_DATA."/repository/data/$id";
			if(is_dir($baseDir))
			{
				$snapshotList = "$baseDir/snapshot.list";
				$out=array();
				exec("cat $snapshotList",$out);

				
				for($i=0;$i<count($out);$i++)
				{
					$d = explode("|",$out[$i]);
					if($source=="" ||($source!="" && $d[3]==$source))
					{
						
						if($sourceBranch == $d[8])
						{
							$r->add($d[0],$d[4],$d[8],$d[3]);
							
						}
						
					}
				}
			}
		}		
		
		for($i=0;$i<$r->count;$i++)
		{
			$source=$r->data[$i]["SOURCE"];
			$branch=$r->data[$i]["BRANCH"];
			$snapId=$r->data[$i]["SNAP_ID"];
			$c=array();
			
			exec("grep \"$snapId\" $this->installedSnapFile | wc -l",$c);
			if($full==true)
			{
				$c[0]=0;
				
			}	
			
			if($c[0]==0)
			{
				$baseFile = "$YAJAN_DATA/repository/data/$source/$snapId.tar.gz";
				$cmd = "tar --no-overwrite-dir -zxf \"$baseFile\"";
				$out=array();
				exec($cmd,$out);
				exec("echo \"".$snapId."\" >> $this->installedSnapFile");
				$CS->showOK("Restoring snapshot [$snapId]");
			}
		}
		$CS->showOK("Restore complite.");
	}
	function checkout($cmd)
	{
		global $YAJAN_DATA,$CS;
		
		
		$source="";
		if(count($cmd)==3)
		{
			$source=$cmd[2];
		}
		$CS->showInfo("Snapshot building...");
		
		if(!is_dir($YAJAN_DATA."/repository/data"))
		{
			mkdir($YAJAN_DATA."/repository/data");
		}
		
		for($i=0;$i<$this->sourceList->count;$i++)
		{
			$this->sourceList->moveRecord($i);
			$name = $this->sourceList->get("name");
			$id = $this->sourceList->get("id");
			$path = $this->sourceList->get("path");
			$branch = $this->sourceList->get("branch");

			
			$baseDir = $YAJAN_DATA."/repository/data/$id";
			if(!is_dir($baseDir))
			{
				mkdir($baseDir);
			}
			exec("touch $baseDir/snapshot.list");
			$snid = uniqid("SNP",true);
			$snapshotFile = "$baseDir/$id.snap";
			$archiveFile= "$baseDir/$snid.tar.gz";
			if($source=="" || ($source!="" && $name == $source))
			{
				if(is_dir($path))
				{
					$CS->showInfo("Build snapshot [$snid] for $name [$id]");
					$cmd="tar --listed-incremental $snapshotFile -zcf $archiveFile $path";
					//echo $cmd;
					exec($cmd);
					$time = time();
					$size = filesize($archiveFile);
					$dt = date("r");
					exec("echo \"$snid|$dt|$time|$name|$id|$snapshotFile|$archiveFile|$path|$branch|$size\" >>  $baseDir/snapshot.list");
					exec("echo \"".$snid."\" >> $this->installedSnapFile");
				}
			}
		}
		$CS->setFGColor("yellow");
		$CS->cout("Commit complite\n");	
	}	
	function commit($cmd)
	{
		global $YAJAN_SERVER,$YAJAN_DATA,$CONFIGURATION,$CS;
		import("net");
		$CS->setFGColor("green");
		
		$CS->showOK("Connecting");
		for($k=0;$k<$this->sourceList->count;$k++)
		{
			$this->sourceList->moveRecord($k);
			$id = $this->sourceList->get("id");
			$hr = new HttpRequest("$YAJAN_SERVER/repository.php");
			$sec = md5($this->info->get("secret"));
			$hr->addParameter("get","commitsnap");
			$hr->addParameter("id",$this->info->get("instanceId"));
			$hr->addParameter("secret",$sec);
			$hr->addParameter("source",$this->sourceList->get("id"));
			$hr->addParameter("branch",$this->sourceList->get("branch"));
			$hr->addFile("snaplist","$YAJAN_DATA/repository/data/$id/snapshot.list");
			$hr->addFile("snapfile","$YAJAN_DATA/repository/data/$id/$id.snap");
			$hr->addFile("sourcelist",$YAJAN_DATA."/repository/sourcelist.conf");
			$resp = $hr->send();
			$hr->close();
			if($resp=="OK")
			{
				$path = new Path("$YAJAN_DATA/repository/data/$id");
				$path->setExt("tar.gz");
				$filelist = $path->getRecordset();
				
				
				$str = file_get_contents("$YAJAN_SERVER/repository.php?get=commitfilelist&id=".$this->info->get("instanceId")."&branch=".$this->sourceList->get("branch")."&secret=".$sec."&source=".$this->sourceList->get("id"));
				
				$onlineFile = new Recordset();
				$onlineFile->fromString($str);

				for($i=0;$i<$filelist->count;$i++)
				{
					
					$filelist->moveRecord($i);
					$exist=false;
					for($j=0;$j<$onlineFile->count;$j++)
					{
						$onlineFile->moveRecord($j);
						if($onlineFile->get("name")==$filelist->get("name"))
						{
							$exist=true;
							break;
						}
					}
					if(!$exist)
					{
						$CS->cout("\tUploading snapshot ".$filelist->get("name")."\t");
						$hr1 = new HttpRequest("$YAJAN_SERVER/repository.php");
						
						$hr1->addParameter("get","commitsnapdata");
						$hr1->addParameter("id",$this->info->get("instanceId"));
						$sec = md5($this->info->get("secret"));
						$hr1->addParameter("secret",$sec);
						$hr1->addParameter("branch",$this->sourceList->get("branch"));
						$hr1->addParameter("filename",$filelist->get("name"));
						$hr1->addParameter("source",$this->sourceList->get("id"));
						
						$hr1->addFile("snapfiledata",$filelist->get("path"));
						$resp = $hr1->send();
						
						$hr1->close();
						if($resp=="OK")
						{
							$CS->cout("DONE\n");
						}
						else
						{
							$CS->showError($resp);
						}

					}
				}
			}
			else
			{
				echo $resp;
			}
		}
		$CS->setFGColor("yellow");
		$CS->cout("Commit complite\n");	
		
	}
	function register($cmd)
	{
		global $YAJAN_SERVER,$CONFIGURATION,$CS,$YAJAN_DATA;
		if($cmd[1]=="online")
		{
			$data = file_get_contents("$YAJAN_SERVER/repository.php?get=register&id=".$CONFIGURATION->instanceId."&name=".$CONFIGURATION->instanceName);
			
			if($data!="error")
			{

				$dx = new DataBox1_2("responce");
				if($dx->parse($data))
				{
					$secret = $dx->getObject("secret");
					$this->info->add("secret",$secret);
					$this->info->add("instanceId",$CONFIGURATION->instanceId);
					
					file_put_contents("$YAJAN_DATA/repository/sourcelist.conf",$dx->getObject("sourcelist"));
					$this->write();
					$this->sourceList->fromFile("$YAJAN_DATA/repository/sourcelist.conf");
					$CS->showOK("Registration complite.");
				}
				else
				{
					$CS->showError($data);
				}
			}
		}
	}
	function sync($cmd)
	{
		global $YAJAN_SERVER,$CONFIGURATION,$CS,$YAJAN_DATA;
		if($cmd[1]=="local")
		{
			$snap = new Recordset();
			$snap->addColumns("snap_id","sourceId","time");
			$CS->showOK("Get configration...");
			for($i=0;$i<$this->sourceList->count;$i++)
			{
				$sourceId = $this->sourceList->data[$i]["ID"];
				
				if(!is_dir("$YAJAN_DATA/repository/data"))
				{
					mkdir("$YAJAN_DATA/repository/data");
				}
				if(!is_dir("$YAJAN_DATA/repository/data/$sourceId"))
				{
					mkdir("$YAJAN_DATA/repository/data/$sourceId");
				}
				$d = file_get_contents("$YAJAN_SERVER/repository.php?get=getsnapshotlist&id=".$CONFIGURATION->instanceId."&source=".$sourceId."&branch=master");
				file_put_contents("$YAJAN_DATA/repository/data/$sourceId/snapshot.list",$d);
				
				$d = file_get_contents("$YAJAN_SERVER/repository.php?get=getsnapshotfile&id=".$CONFIGURATION->instanceId."&source=".$sourceId."&branch=master");
				file_put_contents("$YAJAN_DATA/repository/data/$sourceId/$sourceId.snmp",$d);
				
				
				
				$snapshotList = "$YAJAN_DATA/repository/data/$sourceId/snapshot.list";
				$out=array();
				exec("cat $snapshotList",$out);
			
				
				for($j=0;$j<count($out);$j++)
				{
					$d = explode("|",$out[$j]);
					
					$snap->add($d[0],"$sourceId",$d[2]);
					
				}
			}
			$CS->showOK("Get shapshot data...");
			for($i=0;$i<$snap->count;$i++)
			{
				$snapId=$snap->data[$i]["SNAP_ID"];
				$sourceId = $snap->data[$i]["SOURCEID"];
				if(!file_exists("$YAJAN_DATA/repository/data/$sourceId/$snapId.tar.gz"))
				{
					$w = fopen("$YAJAN_DATA/repository/data/$sourceId/$snapId.tar.gz", 'w');
					$fromFile="$YAJAN_SERVER/repository.php?get=getsnapshotdata&id=".$CONFIGURATION->instanceId."&source=".$sourceId."&branch=master&snapId=$snapId";
					$f = fopen($fromFile, 'r');
					while(!feof($f)){
						
						fwrite($w,fgets($f, 1024));
						
					}
					fclose($f);
					fclose($w);
					$CS->showOK("\t\tGet snapshot [$snapId] data complite. ");
				}
			}
			$CS->showOK("Registration successfult");
		}
	}
	function add($cmd)
	{
		if($cmd[1]=="source")
		{
			$this->addSource($cmd);
		}
	}
	function show($cmd)
	{
		global $CS, $YAJAN_DATA;
		
		
		if($cmd[1]=="source")
		{
			//unset($this->sourceList->data[10]);
			//$this->sourceList->data = array_values($this->sourceList->data);
			//$this->sourceList->count=10;
			//$this->sourceList->toFile();
			$CS->cout("\nRepository source list\n\n");
			$this->sourceList->showCLITable();
			//print_r($this->sourceList->data);
		}
		else if($cmd[1]=="snapshot")
		{
			$source="";
			if(count($cmd)==4)
			{
				$source=$cmd[3];
			}
				$r = new Recordset();
				$r->addColumns("name","branch","snap_id","on_date","time","size");
			for($k=0;$k<$this->sourceList->count;$k++)
			{
				$this->sourceList->moveRecord($k);
				$id = $this->sourceList->get("id");
				
				$baseDir = $YAJAN_DATA."/repository/data/$id";
				if(is_dir($baseDir))
				{
					$snapshotList = "$baseDir/snapshot.list";
					$out=array();
					exec("cat $snapshotList",$out);
				
					
					for($i=0;$i<count($out);$i++)
					{
						$d = explode("|",$out[$i]);
						if($source=="" ||($source!="" && $d[3]==$source))
						{
							$r->add($d[3],$d[8],$d[0],$d[1],$d[2],$d[9]);
						}
					}
				}
			}
			$r->showCLITable();
		}
	}

	function help($cmd)
	{
		global $CS;
		$r=new Recordset();
		$r->addColumns("command","description","syntext");
		if(count($cmd)==1)
		{
			$CS->cout("Base command of yajan repository manager\n");
			$r->add("show","Getting information that store in repository.","show <prperty name>");
			$r->add("add","Add new reposiroty object into server","add <object name>");
			$r->add("checkout","Checkout on source and make new snapshot in local reposiroty","checkdate");
			$r->add("commit","Commit change in local reposiroty to reomte","commit");
			$r->add("sync","Retrive remote change into local reposiroty","sync local");
			$r->add("restore","Restore local reposiroty snapshot","add resotre");
			
		}
		else if($cmd[1]=="show")
		{
			$CS->cout("SHOW command of yajan repository manager\n");
			$r->add("source","Show source directiry that added into repository","show source");
		}
		else if($cmd[1]=="add")
		{
			$CS->cout("ADD command of yajan repository manager\n");
			$r->add("source","Add news source directiry into repository","add source");
		}
		if($r->count>0)
		{
			$r->orderBy("name");
			$r->showCLITable();
		}
	}
}
?>