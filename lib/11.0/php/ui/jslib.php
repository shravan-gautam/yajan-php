<?php
class UIJSLIB
{
	var $filelist;
	function __construct()
	{

	}
	function scripMode()
	{
		global $JAVASCRIPT_SYNC_MODE;
		if($JAVASCRIPT_SYNC_MODE=="defer")
		{
			$val = $JAVASCRIPT_SYNC_MODE;
		}
		else if($JAVASCRIPT_SYNC_MODE=="async")
		{
			$val = " async=true ";
		}
		return $val;
	}
	function loadCommonFile($mode="")
	{
		global $SCRIPT_PATH,$FREAMWORK_PATH,$AJAX_LISTNER,$libVersion ,$UI,$bootstrapVersion,$PACKEGES,$ONLINE_LIB_PATH,$YAJAN_PATH,$JAVASCRIPT_SYNC_MODE,$CSS_SYNC_MODE;
		echo '<script type="text/javascript">
		var jsLibPath="'."$SCRIPT_PATH/$YAJAN_PATH/js/default".'";
		</script>';
		
		$dir = new Path("$YAJAN_PATH/js/default");
		$dir->setExt("js");
		$this->filelist = $dir->getFilelist();
		$this->filelist = sortArray($this->filelist,'path');
		for($i=0;$i<count($this->filelist);$i++)
		{
			$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
			if($mode=="")
			{
				echo '<script type="text/javascript" language="javascript" src="'.$filepath.'" '.$JAVASCRIPT_SYNC_MODE.'>';
				echo'</script>'."\n";
			}
			else if($mode=="embed")
			{
				echo '<script type="text/javascript" language="javascript" '.$JAVASCRIPT_SYNC_MODE.'>';
				$file = new File($filepath);
				echo $file->read();
				echo'</script>'."\n";
			}
		}
		echo '<script type="text/javascript" '.$JAVASCRIPT_SYNC_MODE.'>';
		global $AJAX_LISTNER_STATUS;
		if($AJAX_LISTNER_STATUS==true)
		{
			echo 'var AJAX_LISTNER_STATUS=true;';
		}
		else
		{
			echo 'var AJAX_LISTNER_STATUS=false;';
		}
		echo '
		var applicationPath="'.$SCRIPT_PATH."/".$AJAX_LISTNER.'";
		</script>';
		if($PACKEGES->isImported("socialnetwork"))
		{
			$filepath = "$ONLINE_LIB_PATH/js/facebook";
			echo '<script type="text/javascript" language="javascript" src="'.$filepath.'/facebook.js" '.$JAVASCRIPT_SYNC_MODE.'></script>'."\n";
			echo '<script type="text/javascript" language="javascript" src="'.$filepath.'/facebookclass.js" '.$JAVASCRIPT_SYNC_MODE.'></script>'."\n";
		}
		global $BOOTSTRAP_JS;
		if($UI->module=="bootstrap" && $BOOTSTRAP_JS==true)
		{
			//echo "$YAJAN_PATH/bootstrap.$bootstrapVersion/1dist";
			$dir = new Path("$YAJAN_PATH/bootstrap.$bootstrapVersion/1dist");
			$dir->setExt("js");
			$this->filelist = $dir->getFilelist();
			$this->filelist = sortArray($this->filelist,'path');
			for($i=0;$i<count($this->filelist);$i++)
			{
				$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
				if($mode=="")
				{
					echo '<script type="text/javascript" language="javascript" src="'.$filepath.'" '.$JAVASCRIPT_SYNC_MODE.'>';
					echo'</script>'."\n";
				}
				else if($mode=="embed")
				{
					echo '<script type="text/javascript" language="javascript" '.$JAVASCRIPT_SYNC_MODE.'>';
					$file = new File($filepath);
					echo $file->read();
					echo'</script>'."\n";
				}
			}
		}
		$UI->createJsObject();
	}
	function loadModuleFile($mode="")
	{
		global $SCRIPT_PATH,$MODULE_PATH,$application,$module,$JAVASCRIPT_SYNC_MODE;
		$dir = new Path("$MODULE_PATH/$application/$module");

		$dir->setExt("js");
		$this->filelist = $dir->getFilelist();
		for($i=0;$i<count($this->filelist);$i++)
		{
			$filepath = $SCRIPT_PATH."/".$this->filelist[$i]['path'];
			if($mode=="")
			{
				echo '<script type="text/javascript" language="javascript" src="'.$filepath.'" '.$JAVASCRIPT_SYNC_MODE.'>';
				echo'</script>'."\n";				
			}
			else if($mode=="embed")
			{
				echo '<script type="text/javascript" language="javascript" '.$JAVASCRIPT_SYNC_MODE.'>';
				$file = new File($filepath);
				echo $file->read();
				echo'</script>'."\n";
			}
		}
	}
}
?>