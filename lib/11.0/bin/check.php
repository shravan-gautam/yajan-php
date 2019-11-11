<?php
class check
{
	var $repository;
	var $yajanServerUrl;
	function __construct($cmd)
	{
		global $YAJAN_PATH,$YAJAN_SERVER,$CS,$AUTH;
		
		if(!$AUTH->isMemberOff("admin"))
		{
			$CS->showError("insufficient privileges.");
			return;
		}
	
		$this->repository=$YAJAN_PATH."/repository";
		if(!isset($YAJAN_SERVER))
		{
			$CS->showError("YAJAN_SERVER config not avilable");
			return;
		}
		$this->yajanServerUrl = $YAJAN_SERVER;
		if(!is_dir($this->repository))
		{
			mkdir($this->repository);
		}
		if($cmd[1]=="update")
		{
			$this->update($cmd);
		}
	}
	function update($cmd)
	{
		global $libVersion,$CS,$FREAMWORK_PATH;
		import("net");
		$info = new YajanInfo();
		$CS->showInfo("Connecting to server...");
		$CS->showInfo("Current Update Review Version is ".$info->getInfo("update_review_version"));
		$avReview = file_get_contents($this->yajanServerUrl."/release.php?version=$libVersion&get=updateVersion");
		$avReview = str_replace("\n","",$avReview);
		$CS->showInfo("Available Update Review Version is ".$avReview);
		
		$data = file_get_contents($this->yajanServerUrl."/release.php?get=dirlist&version=$libVersion");
		$count = 0;
		for($i=0;$i<count($data);$i++)
		{
				$dirname = $data[$i];
				$dirname = str_replace("./","$FREAMWORK_PATH/",$dirname);
				if($dirname!="")
				{
						if(!is_dir($dirname))
						{
								//mkdir($dirname);
								$count++;
						}
				}
		}

		
		
		
		
		$data = file_get_contents($this->yajanServerUrl."/release.php?version=$libVersion");
		$data = explode("\n",$data);
		
		for($i=0;$i<count($data);$i++)
		{
			$mdHash = substr($data[$i],0,32);
			$filename = trim(substr($data[$i],33,strlen($data[$i])));
			if($filename!="")
			{
				$filename = str_replace("./","$FREAMWORK_PATH/",$filename);
				if(file_exists("$filename"))
				{
					$md = md5_file("$filename");
					if($md!=$mdHash)
					{
						/*
						$filedata = file_get_contents($this->yajanServerUrl."/release.php?hash=$mdHash");
						if($mdHash!="cd5568dd73758946b8fb74fdd3c7cda9")
						{
							file_put_contents("$filename",$filedata);
							$CS->showInfo("Update $filename");
						}
						*/
						$count++;
					}
				}
				else
				{
					/*
					$filedata = file_get_contents($this->yajanServerUrl."/release.php?hash=$mdHash");
					file_put_contents("$filename",$filedata);
					$CS->showInfo("Create $filename");
					*/
					$count++;
				}
			}
		}
		if($count==0)
		{
			$CS->showInfo("No update found.");
		}
		else
		{
			$CS->showInfo("$count update found.");
		}
		$CS->showOk("Checking new update complete.");
	}
}
?>