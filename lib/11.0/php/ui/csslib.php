<?php
class UICSSLIB
{
	var $filelist;
	function __construct()
	{

	}
	function loadCommonFile($mode="")
	{
		global $SCRIPT_PATH,$FREAMWORK_PATH,$libVersion,$UI,$bootstrapVersion,$ONLINE_LIB_PATH,$LIB_PATH,$YAJAN_PATH ,$JAVASCRIPT_SYNC_MODE,$CSS_SYNC_MODE;
		//print_r($_SERVER);
		//echo "$ONLINE_LIB_PATH/css/default";
		
		$tempPath = $_SERVER['DOCUMENT_ROOT'].$_SERVER['PHP_SELF'];
		$bName = basename($tempPath);
		$tempPath = str_replace("/$bName","",$tempPath);
		$tempPath = str_replace($_SERVER['DOCUMENT_ROOT']."/","",$tempPath);
		$lbPath = str_replace($tempPath."/","",$ONLINE_LIB_PATH);
		
		//echo $tempPath;
		$cssPath = "$lbPath/css/default";
		$cssPath = "$YAJAN_PATH/css/default";

		$dir = new Path($cssPath);
		$dir->setExt("css");
		
		$this->filelist = $dir->getFilelist();
		
		for($i=0;$i<count($this->filelist);$i++)
		{
			$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
			if($mode=="")
			{
				echo '<link href="'.$filepath.'" rel="stylesheet" type="text/css" />',"\n";
			}
			else if($mode=="embed")
			{
				echo '<style>';
				$file = new File($filepath);
				echo $file->read();
				echo'</style>'."\n";
			}
		}
		global $BOOTSTRAP_CSS;
		if($UI->module=="bootstrap" && $BOOTSTRAP_CSS)
		{
			
			$dir = new Path("$YAJAN_PATH/bootstrap.$bootstrapVersion/1dist");
			$dir->setExt("css");
			$this->filelist = $dir->getFilelist();
			for($i=0;$i<count($this->filelist);$i++)
			{
				$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
				if($mode=="")
				{
					echo '<link href="'.$filepath.'" rel="stylesheet" type="text/css" />',"\n";
				}
				else if($mode=="embed")
				{
					echo '<style>';
					$file = new File($filepath);
					echo $file->read();
					echo'</style>'."\n";
				}
			}
			echo '<style>
			
				label
				{
					display:none;
				}
			
			</style>';
		}
		
	}
	function loadModuleFile($mode="")
	{
		global $SCRIPT_PATH,$MODULE_PATH,$application,$module;
		
		$dir = new Path("$MODULE_PATH/$application/$module");
		
		$dir->setExt("css");
		$this->filelist = $dir->getFilelist();
		
		for($i=0;$i<count($this->filelist);$i++)
		{
			$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
			
			if($mode=="")
			{
				echo '<link href="'.$filepath.'" rel="stylesheet" type="text/css" />'."\n";				
			}
			else if($mode=="embed")
			{
				echo '<style>';
				$file = new File($filepath);
				echo $file->read();
				echo'</style>'."\n";
			}
		}
	}
}
?>