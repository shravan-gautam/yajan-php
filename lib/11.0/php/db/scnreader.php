<?php
class ScnReader
{
	var $filename;
	var $scnPath;
	var $db;
	var $scnFile;
	var $tableName;
	var $type;
	function __construct($db)
	{
		global $YAJAN_DATA;
		$this->tableName="";
		$this->type="";
		$this->db = $db;
		$this->scnPath = "$YAJAN_DATA/scn/$db.scn";
		$this->scnFile = new File($this->scnPath);
	}
	function exportScnByDate($date,$filename="")
	{
		$this->scnFile->open();
		$timeCount = $this->getScnTimeCount($date);
		$line = $this->scnFile->nextLine();
		$out = null;
		if($filename!="")
		{
			$out = new File($filename);
			$out->open();
			$out->writeUTF();
		}
		while($line != null)
		{
			$rowOk = true;
			$l = explode("::",$line);
			$rc = $this->getScnTimeCount($l[4]);

			if($rc<$timeCount)
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->type!="" && $l[1]!=strtoupper($this->type))
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->tableName!="" && $l[2]!=strtoupper($this->tableName))
			{
				$rowOk=false;
			}			
			if($rowOk==true)
			{
				if($out!=null)
				{
					$out->append($line);
				}
				else
				{
					echo $line;
				}	
			}			
			$line = $this->scnFile->nextLine();
		}
		if($out!=null)
		{
			$out->close();
		}
		$this->scnFile->close();
	}

	function exportSqlByDate($date,$filename="")
	{
		$this->scnFile->open();
		$timeCount = $this->getScnTimeCount($date);
		$line = $this->scnFile->nextLine();
		$out = null;
		if($filename!="")
		{
			$out = new File($filename);
			$out->open();
			$out->writeUTF();
		}
		while($line != null)
		{
			$rowOk = true;
			$l = explode("::",$line);
			$rc = $this->getScnTimeCount($l[4]);
			if($l[1]=="LOB" || $l[1]=="VAR" || $l[1] =="YSQL" || $l[1] =="DESC")
			{
				$rowOk=false;
			}
			if($rc<$timeCount)
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->type!="" && $l[1]!=strtoupper($this->type))
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->tableName!="" && $l[2]!=strtoupper($this->tableName))
			{
				$rowOk=false;
			}			
			if($rowOk==true)
			{
				$sql = base64_decode($l[3]);
				if($out!=null)
				{
					$out->append($sql.";\n");
				}
				else
				{
					echo $sql."\n";
				}	
			}			
			$line = $this->scnFile->nextLine();
		}
		if($out!=null)
		{
			$out->close();
		}
		$this->scnFile->close();
	}
	function exportScnById($id,$filename="")
	{
		$this->scnFile->open();
		$line = $this->scnFile->nextLine();
		$out = null;
		if($filename!="")
		{
			if(file_exists($filename))
			{
				exec("rm $filename");
			}
			$out = new File($filename);
			
			$out->open();
			
			$out->writeUTF();
		}
		$scnId=0;
		while($line != null)
		{
			$rowOk = true;
			$l = explode("::",$line);
			$scnId=$l[0];


			if($scnId<$id)
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->type!="" && $l[1]!=strtoupper($this->type))
			{
				$rowOk=false;
			}			
			if($rowOk == true && $this->tableName!="" && $l[2]!=strtoupper($this->tableName))
			{
				$rowOk=false;
			}			
			if($rowOk==true)
			{
				$sql = base64_decode($l[3]);
				if($out!=null)
				{
					$out->append($line);
				}
				else
				{
					echo $line;
				}	
			}			
			$line = $this->scnFile->nextLine();
		}
		if($out!=null)
		{
			$out->close();
		}
		$this->scnFile->close();
		return $scnId;
	}	
	function exportSqlById($id,$filename="")
	{
		$this->scnFile->open();
		$line = $this->scnFile->nextLine();
		$out = null;
		if($filename!="")
		{
			if(file_exists($filename))
			{
				exec("rm $filename");
			}
			$out = new File($filename);
			
			$out->open();
			
			$out->writeUTF();
		}
		$scnId=0;
		while($line != null)
		{
			$rowOk = true;
			$l = explode("::",$line);
			$scnId=$l[0];
			if($l[1]=="LOB" || $l[1]=="VAR" || $l[1] =="YSQL" || $l[1] =="DESC")
			{
				$rowOk=false;
			}
			if($scnId<$id)
			{
				$rowOk=false;
			}
			if($rowOk == true && $this->type!="" && $l[1]!=strtoupper($this->type))
			{
				$rowOk=false;
			}			
			if($rowOk == true && $this->tableName!="" && $l[2]!=strtoupper($this->tableName))
			{
				$rowOk=false;
			}			
			if($rowOk==true)
			{
				$sql = base64_decode($l[3]);
				if($out!=null)
				{
					$out->append($sql.";\n");
				}
				else
				{
					echo $sql."\n";
				}	
			}			
			$line = $this->scnFile->nextLine();
		}
		if($out!=null)
		{
			$out->close();
		}
		$this->scnFile->close();
		return $scnId;
	}
	
	function getScnTimeCount($date)
	{
		$date = explode(".",$date);
		$d=$date[0];
		$m=$date[1];
		$y=$date[2];
		$h=$date[3];
		$i=$date[4];
		$s=$date[5];
		return strtotime("$m/$d/$y $h:$i:$s");
	}
	function setScnPointer($date)
	{
		
		
	}
}
?>