<?php
class export
{
	function export($cmd)
	{
		global $CS,$AUTH;
		
		if($cmd[1]=="db_snap")
		{
			if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("operator"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
			
			$this->db_snap($cmd);
		}
		else if($cmd[1]=="sql")
		{
			if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("operator"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
			
			if($cmd[2]=="from" and $cmd[3]=="scn")
			{
				$this->sqlFromScn($cmd);
			}
		}
		else if($cmd[1]=="script")
		{
			
			if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("operator"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}

			if($cmd[2]=="from" and $cmd[3]=="scn")
			{
				$this->scriptFromScn($cmd);
			}
		}
		else if($cmd[1]=="config")
		{
			if(!$AUTH->isMemberOff("admin"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}

			$this->config($cmd);
		}
		$CS->showOK("Export complite");
	}
	function config($cmd)
	{
		global $CONFIGURATION;
		if($cmd[2]=="to")
		{
			$filename = "";
			if(isset($cmd[3]))
			{
				$filename = $cmd[3];
			}
			//echo $filename;
			$CONFIGURATION->export($filename);
		}
	}
	function scriptFromScn($cmd)
	{
		global $YAJAN_DATA,$CS;
		$db = $cmd[4];
		
		//echo $pType;
		if(!isset($cmd[5]))
		{
			$scn = new ScnReader($db);
			$scn->exportScnById(1,"");
		}
		else if($cmd[5]=="to")
		{
			$scn = new ScnReader($db);
			$scn->exportScnById(1,$cmd[6]);
		}
		else if($cmd[5]=="start")
		{
			$pType = $cmd[6];
			$filename="";
			if(isset($cmd[9]))
			{
				$filename=$cmd[9];
			}
			if($pType=="date")
			{
				$date = $cmd[7];
				$date = str_replace(" ",".",$date);
				$date = str_replace("/",".",$date);
				$date = str_replace("-",".",$date);
				$date = str_replace(":",".",$date);
				
				$scn = new ScnReader($db);
				if(isset($cmd[8]))
				{
					if($cmd[8]=="only")
					{
						$filename="";
						if($cmd[9]=="table")
						{
							$scn->tableName = $cmd[10];
						}
						else if($cmd[9]=="type")
						{
							$scn->type = $cmd[10];
						}
						if(isset($cmd[12]))
						{
							$filename = $cmd[12];
						}
					}
				}
				$scn->exportScnByDate($date,$filename);
			}
			else if($pType=="id")
			{
				$id = $cmd[7];

				$scn = new ScnReader($db);
				if(isset($cmd[8]))
				{
					if($cmd[8]=="only")
					{
						$filename="";
						if($cmd[9]=="table")
						{
							$scn->tableName = $cmd[10];
						}
						else if($cmd[9]=="type")
						{
							$scn->type = $cmd[10];
						}
						if(isset($cmd[12]))
						{
							$filename = $cmd[12];
						}
					}
				}				
				$scn->exportScnById($id,$filename);
			}
		}
		else if($cmd[5]=="incremental")
		{
			
			$filename="";
			if(isset($cmd[6])=="to")
			{
				if(isset($cmd[7]))
				{
					$filename=$cmd[7];
					$scn = new ScnReader($db);
					$incFile ="$YAJAN_DATA/tmp/$db.lastscn.exported";
					if(!file_exists($incFile))
					{
						$id=0;
					}
					else
					{
						$id=file_get_contents($incFile);
						$id++;
					}
					
					$lastId = $scn->exportScnById($id,$filename);
					file_put_contents($incFile,$lastId);
				}
			}
			else if(isset($cmd[6])=="only")
			{
				$CS->error("This feature is not avilable in incrimental stage");
			}
			else
			{
				$scn = new ScnReader($db);
				$incFile ="$YAJAN_DATA/tmp/$db.lastscn.exported";
				if(!file_exists($incFile))
				{
					$id=0;
				}
				else
				{
					$id=file_get_contents($incFile);
					$id++;
				}
				
				$lastId = $scn->exportScnById($id,"");
				file_put_contents($incFile,$lastId);
			}
			
		}
	}
	function sqlFromScn($cmd)
	{
		global $YAJAN_DATA;
		if(isset($cmd[4]))
		{
			$db = $cmd[4];
		}
		else
		{
			$db=0;
		}	
		
		//echo $pType;
		if(!isset($cmd[5]))
		{
			$scn = new ScnReader($db);
			$scn->exportSqlById(1,"");
			
		}
		else if($cmd[5]=="to")
		{
			$scn = new ScnReader($db);
			$scn->exportSqlById(1,$cmd[6]);
		}
		else if($cmd[5]=="start")
		{
			$pType = $cmd[6];
			$filename="";
			if(isset($cmd[9]))
			{
				$filename=$cmd[9];
			}
			if($pType=="date")
			{
				$date = $cmd[7];
				$date = str_replace(" ",".",$date);
				$date = str_replace("/",".",$date);
				$date = str_replace("-",".",$date);
				$date = str_replace(":",".",$date);
				
				$scn = new ScnReader($db);
				if(isset($cmd[8]))
				{
					if($cmd[8]=="only")
					{
						$filename="";
						if($cmd[9]=="table")
						{
							$scn->tableName = $cmd[10];
						}
						else if($cmd[9]=="type")
						{
							$scn->type = $cmd[10];
						}
						if(isset($cmd[12]))
						{
							$filename = $cmd[12];
						}
					}
				}
				$scn->exportSqlByDate($date,$filename);
			}
			else if($pType=="id")
			{
				$id = $cmd[7];

				$scn = new ScnReader($db);
				if(isset($cmd[8]))
				{
					if($cmd[8]=="only")
					{
						$filename="";
						if($cmd[9]=="table")
						{
							$scn->tableName = $cmd[10];
						}
						else if($cmd[9]=="type")
						{
							$scn->type = $cmd[10];
						}
						if(isset($cmd[12]))
						{
							$filename = $cmd[12];
						}
					}
				}				
				$scn->exportSqlById($id,$filename);
			}
		}
		else if($cmd[5]=="incremental")
		{
			$filename="";
			if(isset($cmd[6])=="to")
			{
				if(isset($cmd[7]))
				{
					$filename=$cmd[7];
					$scn = new ScnReader($db);
					$incFile ="$YAJAN_DATA/tmp/$db.lastscn.exported";
					if(!file_exists($incFile))
					{
						$id=0;
					}
					else
					{
						$id=file_get_contents($incFile);
						$id++;
					}
					
					$lastId = $scn->exportSqlById($id,$filename);
					file_put_contents($incFile,$lastId);
				}
			}
			else if(isset($cmd[6])=="only")
			{
				$CS->error("This feature is not avilable in incrimental stage");
			}
			else
			{
				$scn = new ScnReader($db);
				$incFile ="$YAJAN_DATA/tmp/$db.lastscn.exported";
				if(!file_exists($incFile))
				{
					$id=0;
				}
				else
				{
					$id=file_get_contents($incFile);
					$id++;
				}
				
				$lastId = $scn->exportSqlById($id,"");
				file_put_contents($incFile,$lastId);
			}
		}
	}
	function db_snap($cmd)
	{
		global $_PWD,$db,$CS,$YAJAN_DATA,$CONFIGURATION;
		//nclude "$_PWD/yajan/etc/yajan.conf.php";
		//print_r($db->adaptor->dbHandels[0]);
		$fromDb="";
		if(isset($cmd[2]))
		{
			if($cmd[2]=="from" && $cmd[3] != "")
			{
				$fromDb =  $cmd[3];
			}
		}
		$sec = new Encryption("shravan");
		if(!isset($CONFIGURATION->data['registration']['index']))
		{
			$CS->showError("Database registration not found.");
			return false;
		}
		$dbList = $CONFIGURATION->data['registration']['index'];
		
		for($dbNumber=0;$dbNumber<count($dbList);$dbNumber++)
		{
			if($fromDb!="")
			{
				$dbNumber=$fromDb;
			}
			$CS->showOK("export start for ".$dbList[$dbNumber]['name']);
			//$dbHendal = $db->adaptor->getDbHandel($dbNumber);
			$dbName = $dbList[$dbNumber]['parm']['name'];
			$driver = $dbList[$dbNumber]['parm']['driver'];
			$username = $dbList[$dbNumber]['parm']['username'];
			$password = $dbList[$dbNumber]['parm']['password'];
			$database = $dbList[$dbNumber]['parm']['database'];
			$server = $dbList[$dbNumber]['parm']['server'];
			$port = $dbList[$dbNumber]['parm']['port'];
			$backupDir = "$YAJAN_DATA/backup/$dbName";
			
			$infoDx = new DataBox("info");
			//$infoDx->add("scn",file_get_contents("$YAJAN_DATA/tmp/scn"));
			if(!is_dir($backupDir))
			{
				exec("mkdir -p $backupDir");
			}
			$dt = date('d.m.Y.H.i.s');
			$infoDx->add("date",$dt);
			if(file_exists("$YAJAN_DATA/scn/$dbNumber.scn"))
			{
				exec("mv $YAJAN_DATA/scn/$dbNumber.scn $YAJAN_DATA/scn/$dbNumber.$dt.scn");
			}
			$infoDx->add("driver",$driver);
			if($driver=="oracle")
			{
				$dx = new DataBox("backup");
				$username = strtoupper($username);
				//$cmd="exp $username/$password@$server:$port/$database full=Y file=var/scn/$i.dmp1 ";
				$objects = array("TABLE","VIEW","SYNONYM","FUNCTION","PROCEDURE","TRIGGER","INDEX","CONSTRAINT");
				$CS->showOK("exporting DDL...");
				for($i=0;$i<count($objects);$i++)
				{
					$objType = $objects[$i];
					$q="SELECT object_name, owner,object_type,DBMS_METADATA.GET_DDL(REPLACE(object_type, ' ', '_'), object_name, owner)
					FROM all_OBJECTS
					WHERE (OWNER = '$username')
					and object_type = '$objType'
					order by object_type, object_name";
					$r = $db->execute($q);
					$dx->add("$objType",$r);
					$CS->showOK("\t$objType ".$r->count." done");
				}

				$current  = file_get_contents("$YAJAN_DATA/db/current");
				$dt = date('d.m.Y.H.i.s');
				if(file_exists("$YAJAN_DATA/scn/$current.scn"))
				{
					exec("mv $YAJAN_DATA/scn/$current.scn $YAJAN_DATA/scn/$current.$dt.scn");
				}
				
				$q="select tname from tab where TABTYPE = 'TABLE' and tname not like 'BIN$%' order by tname";
				$r = $db->execute($q);
				$dx->add("tableList",$r);
				
				$dx->toFile("$backupDir/$dbNumber.ddl");
				$infoDx->add("ddlFile","$dbNumber.ddl");
				$CS->showOK("exporting data...");
				$dmlFile = array();
				for($i=0;$i<$r->count;$i++)
				{
					$tname = $r->data[$i]['TNAME'];
					$tdx = new DataBox1_2($tname); 
					$tdx->bigDataWrite("file","$backupDir/$tname.dml"); 
					$dmlFile[count($dmlFile)]="$tname.dml";
					$q="select * from $tname";
					
					$data = $db->execute($q,false);
					$row = $data->getDBRow(false);
					
					$ix=0;
					while($row!=false)
					{
						   $tdx->add("row".$ix,$row);
						   $ix++;
						   $row = $data->getDBRow(false);
					}
					$tdx->bigDataClose(); 
					$CS->showOK("\t$tname $ix rows export done");
				}
				$infoDx->add("dmlFile",$dmlFile);
				$CS->showOK("export complete");
			}
			else if($driver=="mysql")
			{
				if($server!="localhost")
				{
					$server = " -h $server ";
				}
				else
				{
					$server = "";
				}
				$cmd = "mysqldump -P $port -u$username -p$password $server $database > $backupDir/$dbName.sql";
				$infoDx->add("sqlFile","$dbName.sql");
				exec($cmd);
				
			}
			$infoDx->toFile("$backupDir/$dbName.backup");
			if($fromDb!="")
			{
				break;
			}
		}
		return true;
	}
}
?>