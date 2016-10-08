<?php
class import
{
	var $scnFile;
	function showLineNumber($line)
	{
		//echo "\t".$line."\n";
		//$dbgt=debug_backtrace();
		//print_r($dbgt[2]);
		//echo $dbgt[1]['file']." on line ".$dbgt[1]['line']."\n";
	}
	function import($cmd)
	{
		global $AUTH,$CS;
		if($cmd[2]=="sql")
		{
			if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("operator"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
			
			$this->importSql($cmd);
		}
		else if($cmd[2]=="script")
		{
			if(!$AUTH->isMemberOff("dba") && !$AUTH->isMemberOff("operator"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}
				$this->importScript($cmd);
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
	}
	function config($cmd)
	{
		global $CONFIGURATION;
		if($cmd[2]=="from")
		{
			$filename="";
			if(isset($cmd[3]))
			{
				$filename=$cmd[3];
				
			}
			$CONFIGURATION->import($filename);
		}
	}
	function importSql($cmd)
	{
		global $DB_REG;
		$filename = $cmd[3];
		$db  = $cmd[5];
	}
	function importScript($cmd)
	{
		global $DB_REG;
		if(count($cmd)<=5)
		{
			$CS->error("Parameter is not complite.");
			return false;
		}
		$db  = $cmd[5];
		

		$index =$DB_REG->getProperty($cmd[5]);
		$db = new Connection($index["name"]);
		$db->scnLog(false);
		
		$filename = $cmd[3];
		$this->scnFile = new File($filename);
		$this->scnFile->open();
		$line = $this->scnFile->nextLine();

		$var = false;
		$lobVar=false;
		$lobTempVar = false;
		$parmArr= array();
		$lobArr=array();
		$lobTemp = array();
		$scnLine=0;
		while($line != null)
		{
			$scnLine++;
			
			$this->showLineNumber(__LINE__);
			$l = explode("::",$line);
			$cmd = base64_decode($l[3]);
			//echo $scnLine."=>[".$l[1]."][$cmd]"."\n";
			$obj = $l[2];
			
			if($l[1]=="VAR")
			{
				$this->showLineNumber(__LINE__);
				if($var==false)
				{
					$this->showLineNumber(__LINE__);
					$parmArr= array();
					$var=true;
				}
				$parmArr["$obj"]=base64_decode($cmd);
			}
			else
			{
				$this->showLineNumber(__LINE__);
				if($var==true)
				{
					$this->showLineNumber(__LINE__);
					$var=false;
				}
			}
			
			
			if($l[1]=="DESC")
			{
				$this->showLineNumber(__LINE__);
				if($lobVar==false)
				{
					$this->showLineNumber(__LINE__);
					$lobArr= array();
					$lobVar=true;
				}
				$c = explode("|",$cmd);
				$lobArr["$obj"]=array($obj,$c[1],$c[2]);
			}
			else
			{
				$this->showLineNumber(__LINE__);
				if($lobVar==true)
				{
					$this->showLineNumber(__LINE__);
					$lobVar=false;
				}
			}
			
			if($l[1]=="LOB")
			{
				$this->showLineNumber(__LINE__);
				if($lobTempVar==true)
				{
					//echo "Insert LOB Data ".$l[2]."\n";
					$this->showLineNumber(__LINE__);
					foreach($lobTemp as $k => $v)
					{
						$this->showLineNumber(__LINE__);
						if($k==$l[2])
						{
							$this->showLineNumber(__LINE__);
							//echo base64_decode($cmd)."\n\n";
							//print_r($v[3]);
							$v[3]->setValue(base64_decode($cmd));
							//print_r($v);
						}
						//$v[2]->setValue($v[1]);
					}
					
				}
			}
			else
			{
				$this->showLineNumber(__LINE__);
				if($lobTempVar==true)
				{
					$this->showLineNumber(__LINE__);
					//echo "Auto POST LOB Execution ture\n";
					$lobTemp=array();
					$lobTempVar=false;
					$db->autoExecute(true);
					
				}
			}
			//print_r($lobArr);

			if($l[1]=="YSQL")
			{
				$this->showLineNumber(__LINE__);
				$c = explode(" ",$cmd);
				if($c[0]=="autocommit")
				{
					$this->showLineNumber(__LINE__);
					if($c[1]=="true")
					{
						$this->showLineNumber(__LINE__);
						//echo "Auto Commit ture\n";
						$db->autoCommit(true);
						
					}
					else
					{
						$this->showLineNumber(__LINE__);
						//echo "Auto Commit false\n";
						$db->autoCommit(false);
						
					}
				}
				else if($c[0]=="autoexecute")
				{
					$this->showLineNumber(__LINE__);
					if($c[1]=="true")
					{
						$this->showLineNumber(__LINE__);
						//echo "Auto Execute ture\n";
						$db->autoExecute(true);
						
					}
					else
					{
						$this->showLineNumber(__LINE__);
						//echo "Auto Execute false\n";
						$db->autoExecute(true);
						
					}
				}
				else if($c[0]=="commit")
				{
					$this->showLineNumber(__LINE__);
					
					
					$db->commit();
					$lobArr=array();
					$lobVar=false;
					$lobTemp=array();
					$lobTempVar=false;
					//echo "Commit execute\n";
				}
			}
			else if($l[1]=="DML" || $l[1]=="DDL" || $l[1]=="DCL" || $l[1]=="PLSQL")
			{
				$this->showLineNumber(__LINE__);
				if($l[6]=="true")
				{
					$this->showLineNumber(__LINE__);
					//echo "execute sql statement\n";
					$db->execute($cmd);
				}
				else
				{
					$this->showLineNumber(__LINE__);
					foreach($lobArr as $k => $v)
					{
						$this->showLineNumber(__LINE__);
						if($v[0]!=""&& $v[1]!="")
						{
							$this->showLineNumber(__LINE__);
							if($v[2]=="112")
							{
								$this->showLineNumber(__LINE__);
								//echo "Get clob ".$v[0]."\n";
								$t = array($v[0],$v[1],$v[2],$db->getClob(':'.strtolower($v[0])));
								
							}
							else
							{
								$this->showLineNumber(__LINE__);
								//echo "Get blob ".$v[0]."\n";
								$t = array($v[0],$v[1],$v[2],$db->getBlob(':'.strtolower($v[0])));		
								
							}
							$lobTemp[$k]=$t;
						}
					}
					if(count($lobTemp)>0)
					{
						$this->showLineNumber(__LINE__);
						$lobTempVar=true;
					}
					//print_r($temp);
					//echo "Auto Execution false\n";
					$db->autoExecute(false);
					
					//print_r($parmArr);
					//echo "Parse ".$cmd."\n";
					$db->parse($cmd);
					foreach($parmArr as $k => $v)
					{
						$this->showLineNumber(__LINE__);
						//echo "Bind $k=$v\n";
						$db->bindVar(':'.$k,$v);
					}
					$parmArr = array();

					foreach($lobTemp as $k => $v)
					{
						$this->showLineNumber(__LINE__);
						//echo "Bind $k=".$v[0]."\n";
						//print_r($v[3]);
						$db->bindWith($v[3]);
					}
					
					//echo "execute statement\n";
					$db->execute();
					if($lobTempVar==false)
					{
						$this->showLineNumber(__LINE__);
						//echo "Auto SQL Execution ture\n";
						$db->autoExecute(true);
						
					}
				}
				
				if($l[5]=="true")
				{
					$this->showLineNumber(__LINE__);
					//echo "Commit execute\n";
					$db->commit();
					
				}
			}

			//echo ".";
			$line = $this->scnFile->nextLine();
		}

		$this->scnFile->close();
		$db->close();
	}
}
?>