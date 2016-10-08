<?php
class ElExplorer
{
	var $id;
	var $width;
	var $height;
	var $root;
	var $allowShortcuts;
	var $dragUploadAllow;
	var $uploadEnable;
	var $editEnable;
	var $removeEnable;
	var $downloadEnable;
	var $archiveEnable;
	var $moveEnable;
	var $searchEnable;
	var $aboutEnable;

	function ElExplorer($id,$root)
	{
		$this->id = $id;
		$this->root =$root;
		$this->allowShortcuts=true;
		$this->dragUploadAllow=true;
		$this->uploadEnable=true;
		$this->editEnable=true;
		$this->removeEnable=true;
		$this->downloadEnable=true;
		$this->archiveEnable=true;
		$this->moveEnable=true;
		$this->searchEnable=true;
		$this->aboutEnable=true;

	}
	function rander()
	{	
		
		global $_PWD,$FREAMWORK_PATH,$libVersion,$SCRIPT_PATH;
		$root = base64_encode($this->root);
		$allowShortcuts = false;
		if($this->allowShortcuts)
		{
			$allowShortcuts=true;
		}
		$dragUploadAllow = false;
		if($this->dragUploadAllow)
		{
			$dragUploadAllow=true;
		}
		$uploadEnable = '';
		if($this->uploadEnable)
		{
			$uploadEnable="['mkdir', 'mkfile', 'upload'],";
		}
		$editEnable = '';
		if($this->editEnable)
		{
			$editEnable="['duplicate', 'rename', 'edit', 'resize'],";
		}
		$removeEnable = "";
		if($this->removeEnable)
		{
			$removeEnable="['rm'],";
		}
		$downloadEnable = '';
		if($this->downloadEnable)
		{
			$downloadEnable = "['open', 'download', 'getfile'],['view'],['info'],";
		}
		$archiveEnable='';
		if($this->archiveEnable)
		{
			$archiveEnable="['extract', 'archive'],";
		}
		$moveEnable='';
		if($this->moveEnable)
		{
			$moveEnable="['copy', 'cut', 'paste'],";
		}
		$searchEnable='';
		if($this->searchEnable)
		{
			$searchEnable="['search'],";
		}
		$aboutEnable='';
		if($this->aboutEnable)
		{
			$aboutEnable="['help']";
		}
		$html ='
		


		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" media="screen" href="'."$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion".'/css/elfinder/elfinder.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="'."$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion".'/css/elfinder/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script type="text/javascript" src="'."$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion".'/js/elfinder/elfinder.js"></script>

		<!-- elFinder translation (OPTIONAL) -->
		<script type="text/javascript" src="'."$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion".'/js/elfinder/i18n/elfinder.ru.js"></script>
		<div id="'.$this->id.'"></div>
		<script type="text/javascript">
			';
			$html .="$().ready(function() {
				var elf = $('#".$this->id."').elfinder({
					url : '$SCRIPT_PATH/$FREAMWORK_PATH/lib/$libVersion/php/addons/elfinder/connector.php',  // connector URL (REQUIRED)
					allowShortcuts : true,
					dragUploadAllow : true,
					customData : {'root':'$root'},
					uiOptions : {
						toolbar : [
							['back', 'forward'],
							['reload'],
							['home', 'up'],
							$uploadEnable
							$downloadEnable
							['quicklook'],
							$moveEnable
							$removeEnable
							$editEnable
							$archiveEnable
							$searchEnable
							$aboutEnable
						],
						tree : {
							// expand current root on init
							openRootOnLoad : true,
							// auto load current dir parents
							syncTree : true
						},

						// navbar options
						navbar : {
							minWidth : 150,
							maxWidth : 500
						},

						// current working directory options
						cwd : {
							// display parent directory in listing as 
							oldSchool : false
						}
					},
					contextmenu : {
						// navbarfolder menu
						navbar : [
							'open', '|',";
							if($this->editEnable)
							{
							$html.="
							'copy', 'cut', 'paste', 'duplicate', '|', ";
							}
							if($this->removeEnable)
							{
							$html.="
							'rm', '|', ";
							}
							$html.="
							'info'],

						// current directory menu
						cwd    : [
							'reload', 'back', '|', ";
							if($this->uploadEnable)
							{
							$html.="
							'upload', 'mkdir', 'mkfile', 'paste', '|', ";
							}
							$html.="
							'info'],

						// current directory file menu
						files  : [";
							if($this->downloadEnable)
							{
							$html.="
							'getfile', '|',
							'open', 'quicklook', '|', 
							'download', '|', ";
							}
							if($this->moveEnable)
							{
							$html.="
							'copy', 'cut', 'paste', 'duplicate', '|',";
							}
							if($this->removeEnable)
							{
							$html.="
							'rm', '|', ";
							}
							if($this->editEnable)
							{
							$html.="
							'edit', 'rename', 'resize', '|', ";
							}
							
							if($this->archiveEnable)
							{
							$html.="
							'archive', 'extract', '|', ";
							}
							
							$html.="
							'info'
						]
					},
				}).elfinder('instance');
			});";
			$html.='
		</script>';
		
		echo $html;
	}
}
?>