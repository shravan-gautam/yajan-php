<?php
class ScnMgmt
{
	var $file;
	var $scnPath;
	var $mode;
	var $autoCommit;
	var $autoExecute;
	var $scnLog;
	function ScnMgmt()
	{
		global $YAJAN_DATA;
		$this->scnPath = "$YAJAN_DATA/scn";
		$this->mode = file_get_contents("$YAJAN_DATA/db/scnlogmode");
		if(!is_dir("$YAJAN_DATA/tmp"))
		{
			mkdir("$YAJAN_DATA/tmp");
		}
		if(!is_dir($this->scnPath))
		{
			mkdir($this->scnPath);
		}
		//$this->file = new File("$YAJAN_DATA/tmp/scn");
		$this->autoCommit = true;
		$this->autoExecute = true;
		$this->scnLog = true;
	}
	function scnLog($v)
	{
		$this->scnLog=$v;
	}
	/*
	function next()
	{
		if($this->mode=="log")
		{
			$v = base64_decode($this->file->read());
			if($v=="")
			{
				$v=0;
			}
			$v=$v+1;

			$this->file->write(base64_encode($v));
		}
	}
	*/
	function write($dbObject,$q,$type)
	{
		global $REQUEST_ID,$DB_REG;
		if($type!="DQL" && $this->mode=="log" && $this->scnLog==true)
		{
			
			if($type=="VAR")
			{
				$object = explode("|",$q);
				$q=$object[1];
				$object = $object[0];
				$object = str_replace(":","",$object);
				$object = strtoupper($object);
			}
			else if($type=="LOB")
			{
				$object = explode("|",$q);
				$q=$object[2];
				$object = $object[1];
				$object = str_replace(":","",$object);
				$object = strtoupper($object);
			}
			else if($type=="DESC")
			{
				$object = explode("|",$q);
				
				$object = $object[0];
				$object = str_replace(":","",$object);
				$object = strtoupper($object);
			}
		
			else if($type=="YSQL" || $type == "DCL" || $type == "PLSQL" || $type=="OTHER")
			{
				$object="SQL";
			}
			else
			{
				
					$object = explode(" ",$q);
					$c = strtoupper($object[0]);
					if($c=="CREATE" || $c=="ALERT" || $c=="DROP")
					{
						$object = $object[2];
					}
					else if($c=="INSERT")
					{
						$object = $object[2];
					}
					else if($c=="UPDATE")
					{
						$object = $object[1];
					}
					else if($c == "DECLARE" || $c=="BEGIN")
					{
						$object = "PLSQL";
					}
					else if($c=="DELETE")
					{
						if(strtoupper($object[1])!="FROM")
						{
							$object = $object[1];
						}
						else
						{
							$object = $object[2];
						}
					}
					
					$object = explode("(",$object);
					$object = strtoupper($object[0]);
					$object = str_replace("\r","",$object);
					$object = str_replace("\n","",$object);
					$object = str_replace("\t","",$object);
					$object = trim($object);
				
				
			}
			
			if($this->autoExecute)
			{
				$autoExecute="true";
			}
			else
			{
				$autoExecute="false";
			}
			if($this->autoCommit)
			{
				$autoCommit="true";
			}
			{
				$autoCommit="false";
			}
			$dbNumber = $dbObject->dbId;
			$dbNumber = $DB_REG->getRegistrationId($dbObject->parm['name']);
			$f = new File($this->scnPath."/$dbNumber.scn");
			$dt = date("d.m.Y.H.i.s");
			$f->append($this->scnNumber($dbNumber)."::$type::$object::".base64_encode($q)."::".$dt."::".$autoCommit."::".$autoExecute."::$REQUEST_ID\n");
		}
	}
	function scnNumber($dbNumber)
	{	
		global $libVersion,$YAJAN_DATA;
		$mTime = getmicrotime();
		$mTime = str_replace(".","",$mTime);
		//exec("bash yajan/lib/$libVersion/bin/scncounter.sh $dbNumber $YAJAN_DATA",$out);
		//return $out[0];
		return $mTime;
	}
	function close()
	{
		
	}
}
?>