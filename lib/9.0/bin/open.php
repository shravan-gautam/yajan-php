<?php
class Open
{
	
	function Open($cmd)
	{
		global $AUTH,$CS;
		
		if($cmd[1]=='sql')
		{
			if(!$AUTH->isMemberOff("developer") && !$AUTH->isMemberOff("dba"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->openSql($cmd);
		}
		else if($cmd[1]=="form+")
		{
			if(!$AUTH->isMemberOff("developer"))
			{
				$CS->showError("insufficient privileges.");
				return;
			}			
			
			$this->openFormBuilder($cmd);
		}
	}
	function openSql($cmd)
	{
		global $CS,$DB_REG,$DEFAULT_DB_CONFIG;
		$CS->setFGColor("yellow");
			
		if(count($cmd)==4)
		{
			$db =$cmd[3];
		}
		else
		{
			$db = $DB_REG->getRegistrationId($DEFAULT_DB_CONFIG);
		}
		if($DB_REG->isRegisterdId($db)==-1)
		{
			$CS->showError("Invalid Database ID");
			return;
		}
		$dbId = $db;
		$sp = $DB_REG->getProperty($db);
		/*
		$dx = new DataBox("registration");
		$filename = "var/db/registration";
		$dx->fromFile($filename);
		$index = $dx->getObject("index");
		$sp = $index[$db]['parm'];
		*/
		$dbName = $sp['name'];
		$db = new Connection($sp);
		$CS->cout("Connected to DB ID $dbId($dbName).\n");
		if(!$db->autoCommit(false))
		{
			$CS->cout("NOTE!! Auto commit is on.\n");	
		}
		$sql = "";
		$colSize=30;
		$oldSql = "";
		while($sql!="exit")
		{
			if($sql!="" and $sql != "exit")
			{
				if($sql=="/")
				{
					$sql = $oldSql;
				}
				$s = explode(" ",$sql);
				if(strtoupper($s[0])=="SET")
				{
					if(strtoupper($s[1])=="LIN")
					{
						$colSize = $s[2];
					}
				}
				else if(strtoupper($s[0])=="DESC")
				{
					$obj = $s[1];
					if($sp['driver']=="oracle")
					{
						$q="select * from $obj where rownum < 1";
					}
					else
					{
						$q="select * from $obj limit 1,1";
					}
					$r = $db->execute($q);
					if($r->count>0)
					{
						echo "\n";
						echo "-".str_pad('',10,'-');
						echo str_pad('',$colSize*3,'-')."--\n";
						echo str_pad("SR",10,' ');
						echo str_pad("COLUMN",$colSize,' ');
						echo str_pad("TYPE",$colSize,' ');
						echo str_pad("SIZE",$colSize,' ');
						echo "\n";
						echo "-".str_pad('',10,'-');
						echo str_pad('',$colSize*3,'-')."--\n";
						for($i=0;$i<$r->countColumns;$i++)
						{
							echo str_pad($i,10,' ');
							echo str_pad($r->columns[$i]->getName(),$colSize,' ');
							echo str_pad($r->columns[$i]->getType(),$colSize,' ');
							echo str_pad($r->columns[$i]->getSize(),$colSize,' ');
							echo "\n";
						}
						echo "-".str_pad('',10,'-');
						echo str_pad('',$colSize*3,'-')."--\n";
					}
					else
					{
						echo "$obj is not found.\n";
					}
					$oldSql = $sql;
				}
				else
				{
					$r = $db->execute($sql);
					
					
					if(gettype($r)=="object")
					{
						echo "\n";
						echo "-".str_pad('',$colSize*$r->countColumns,'-')."--\n";
						for($i=0;$i<$r->countColumns;$i++)
						{
							echo str_pad($r->columns[$i]->getName(),$colSize,' ');
						}
						echo "\n";
						echo "-".str_pad('',$colSize*$r->countColumns,'-')."--\n";
						for($i=0;$i<$r->count;$i++)
						{
							foreach($r->data[$i] as $k => $v)
							{
								if(strlen($v)>$colSize)
								{
									echo str_pad(substr($v,0,$colSize-3)."...",$colSize,' ');
								}
								else
								{
									echo str_pad($v,$colSize,' ');
								}
							}
							echo "\n";
						}
						echo "=".str_pad('',$colSize*$r->countColumns,'=')."==\n";
						echo "\n".$r->count." rows return\n\n";
					}
					else
					{
						echo "Opration complite\n";
					}
					$oldSql = $sql;
				}
			}
			
			echo "SQL: [".$sp['name']."] > ";
			$sql = $CS->read();
			$sql = rtrim($sql,";");
		}
		
	}
	function openFormBuilder($cmd)
	{
		global $CS;
		require_once "form/formBuilder.php";
		$fc = new FormBuilder($cmd);
		$fc->run();
		$CS->setFGColor("yellow");
		$CS->cout("\n\nThanks.\n\n");	
		
	}
}
?>