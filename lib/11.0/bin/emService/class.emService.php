<?php
class EMServiceConsole
{
	function __construct($cmd)
	{
		global $CS,$DEFAULT_DB_CONFIG,$FORM_BUILDER_VAR,$OUTPUT_CACHE_LOCATION;
		$this->cmd = $cmd;
		$this->version = "1.1";
		$CS->setFGColor("yellow");
		$CS->cout("\n\nError Management Console $this->version\n\n");	
		
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
		while($cmd!="exit")
		{
			if(isset($__run))
			{
				$cmd=$__run;
				echo "executing \"$cmd\"\n";
			}
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="register")
			{
				$this->register($cmd);
			}
			else if($cmd[0]=="show")
			{
				$this->show($cmd);
			}
			else if($cmd[0]=="export")
			{
				$this->export($cmd);
			}
			else if($cmd[0]=="import")
			{
				$this->import($cmd);
			}
			
			if(isset($__run))
			{
				break;
			}
			echo "EM-Console: > ";
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}

	function register($cmd)
	{
		global $CS;
		$CS->showInfo("Enter Error Type ");
		$type = $CS->read();
		$CS->showInfo("Enter Error Serise ");
		$serise = $CS->read();
		$CS->showInfo("Enter Error Description");
		$description = $CS->read();
		$CS->showInfo("Enter Error Resion ");
		$reson = $CS->read();
		$CS->showInfo("Enter Error Solution ");
		$solution = $CS->read();
		$ems = new EMService();
		$errorCode = $ems->register($type,$serise,$description,$reson,$solution);
		$CS->showOk("Error registration successfuly done. Error code is : ".$errorCode);
	}
	function show($cmd)
	{
		$ems = new EMService();			
		if($cmd[1]=="all")
		{
		
			$r = $ems->getList();
			$r->showCLITable();
		}
		else if($cmd[1]!="")
		{
			$r = $ems->getList($cmd[1]);
			$r1 = new Recordset();
			$r1->addColumns("key","value");

			for($i=0;$i<count($r->columns);$i++)
			{
				$col = $r->columns[$i]->getName();
				$r1->add($col,$r->get($col));
			}
			$r1->showCLITable();
		}
	}
	function export($cmd)
	{
		global $CS;
		if(count($cmd)>4)
		{
			if($cmd[2]=="json")
			{
				$filename = $cmd[4];
				$ems = new EMService();	
				$ems->exportJSON($filename);
				$CS->showOk("Error defination export complite to $filename");
			}
		}
		else
		{
			$CS->showError("insufficiant parameter");	
			$CS->showWarnning("Syntext is \"export as json to <filename>\"");	
		}

	}
	function import($cmd)
	{
		global $CS;
		if(count($cmd)>3)
		{
			if($cmd[1]=="json")
			{
				$filename = $cmd[3];
				$ems = new EMService();	
				$ems->importJSON($filename);
				$CS->showOk("Error defination import complite from $filename");
			}
		}
		else
		{
			$CS->showError("insufficiant parameter");	
			$CS->showWarnning("Syntext is \"import json from <filename>\"");	
		}
	}
}
?>