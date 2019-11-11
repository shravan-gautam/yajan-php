<?php
include "class.canvas.php";
include "class.datablock.php";
include "class.form.php";
include "class.item.php";
include "class.trigger.php";
include "class.view.php";
class FormBuilder
{
	var $formName;
	var $version;
	var $cmd;
	var $dbName;
	var $prompt;
	var $db;
	var $dbDriver;
	var $objectName;
	var $objectType;
	var $application;
	var $status;
	
	function __construct($cmd)
	{
		global $CS,$DEFAULT_DB_CONFIG,$FORM_BUILDER_VAR;
		$this->cmd = $cmd;
		
		$CS->setFGColor("yellow");
		$CS->cout("\n\nWelcome Form Builder $this->version\n\n");	
		
		if(!isset($FORM_BUILDER_VAR) || $FORM_BUILDER_VAR=="")
		{
			$CS->showError("FORM_BUILDER_VAR is not define. please set in config");
			$this->status=false;
			$CS->showError("Exit form builder");
		}
		else
		{
			$this->status=true;
		}
		$this->version = "1.1";
		
	}
	function string2cmd($str)
	{
		$str = rtrim($str,";");
		return explode(" ",$str);
	}
	function run()
	{
		global $CS,$DB_REG,$__run;
		if(count($this->cmd)<4)
		{
			$db =0;
		}
		else
		{
			$db =$this->cmd[3];
		}

		$sp = $DB_REG->getProperty($db);
		$this->db = new Connection($sp['name']);
		$cmd = "";
		$this->dbName = $sp['name'];
		$this->dbDriver = $sp['driver'];
		$this->prompt = "$this->dbName";
		while($cmd!="exit")
		{
			if(isset($__run))
			{
				$cmd=$__run;
			}
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="create")
			{
				if($cmd[1]=="form")
				{
					$this->createForm($cmd);
				}
				else if($cmd[1]=="ui")
				{
					$this->ui($cmd);
				}
				else if($cmd[1]=="datablock")
				{
					$this->createDatablock($cmd);
				}
				else if($cmd[1]=="canvas")
				{
					$this->createCanvas($cmd);
				}
				else if($cmd[1]=="view")
				{
					$this->createView($cmd);
				}
				else if($cmd[1]=="class")
				{
					$this->createFormClass($cmd);
				}
			}
			else if($cmd[0]=="open")
			{
				if($cmd[1]=="form")
				{
					$this->openForm($cmd);
				}
			}
			else if($cmd[0]=="compile")
			{
				$this->compile($cmd);
			}
			else if($cmd[0]=="install")
			{
				$this->install($cmd);
			}
			
			if(isset($__run))
			{
				break;
			}
			echo "FORM-BUILDER: [".$this->prompt."] > ";
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}
	function install($cmd)
	{
		global $MODULE_PATH;
		if($MODULE_PATH!="")
		{
			$form = new FormBuilderForm($this->formName);
			$form->install($MODULE_PATH);
		}
	}
	function ui($cmd)
	{
		
		$object = $cmd[2];
		$name = $cmd[3];
		$path = $cmd[5];
		include "class.ui.php";
		
		$uc = new UICreater($object,$name,$path);
		$uc->create();
		
	}
	function createForm($cmd)
	{
		global $CS,$FORM_URL_MODE;
		if(!isset($FORM_URL_MODE))
		{
			$FORM_URL_MODE = "module";
		}
		if(count($cmd)<5)
		{
			$CS->showError("Insufficient parameter\n");
			return;
		}
		$this->formName = $cmd[2];
		$this->application = $cmd[4];
		$tableName="";
		if(isset($cmd[6]))
		{
			$tableName=$cmd[6];
		}
		$this->formName = str_replace(" ","_",$this->formName);
		
		if(!is_dir("var/form"))
		{
			mkdir("var/form");
		}
		if(!is_dir("var/form/$this->formName"))
		{
			mkdir("var/form/$this->formName");
			mkdir("var/form/$this->formName/datablock");
			mkdir("var/form/$this->formName/view");
			mkdir("var/form/$this->formName/canvas");
			mkdir("var/form/$this->formName/recordset");
			mkdir("var/form/$this->formName/lov");
			mkdir("var/form/$this->formName/output");
		}
		$this->prompt = "$this->dbName@$this->formName";
		$php = '<?php
		$formProperty = array();
		$formProperty["name"]="'.$this->formName.'";		//Name of form
		$formProperty["urlMode"]="'.$FORM_URL_MODE.'";					//Request url mode [file/path]
		$formProperty["ajaxSubmission"]=true;				//Form submission mathod [true/false]
		$formProperty["application"]="'.$this->application.'";			//Application name
		
?>';
		$formPropertyFileName = "var/form/$this->formName/property.php";
		$file = new File($formPropertyFileName);
		$file->write($php);
		if($tableName!="")
		{
			
			$c = $this->string2cmd("create datablock b1 for $tableName");
			$this->createDatablock($c);
			
			$c = $this->string2cmd("create canvas c1 for b1");
			$this->createCanvas($c);

			$c = $this->string2cmd("create view v1 for b1");
			$this->createView($c);
			
			$c = $this->string2cmd("compile");
			$this->compile($c);

		}
	}
	function createCanvas($cmd)
	{
		global $CS;
		if(count($cmd)< 5)
		{
			$CS->showError("Insufficient parameter.");
			return;
		}		
		$canvasName = $cmd[2];
		$datablockName = $cmd[4];
		if(!is_dir("var/form/$this->formName/canvas/$canvasName"))
		{
			mkdir("var/form/$this->formName/canvas/$canvasName");
		}
		$filename = "var/form/$this->formName/canvas/$canvasName/property.php";
		$php = '<?php
	$canvasProperty = array();
	$canvasProperty["name"]="'.$canvasName.'";
	$canvasProperty["datablock"]="'.$datablockName.'";
	$canvasProperty["type"]="form";
?>';
		$file = new File($filename);
		$file->write($php);
		$CS->showInfo("\nCanvas created.\n");
		$this->objectName = $canvasName;
		$this->objectType = "canvas";
		$this->prompt = "$this->dbName@$this->formName/$this->objectType/$this->objectName";		
	}
	function getDatablockFieldList($datablock)
	{
		$dir = "var/form/$this->formName/datablock/$datablock/items";
		$p  = new Path($dir);
		$list = $p->getRecordset();
		return $list;
		
	}	
	function createDatablock($cmd)
	{
		global $CS;
		if(count($cmd)< 5)
		{
			$CS->showError("Insufficient parameter.");
			return;
		}
		$datablockName = $cmd[2];
		$tableName = $cmd[4];
		if(!is_dir("var/form/$this->formName/datablock/$datablockName"))
		{
			mkdir("var/form/$this->formName/datablock/$datablockName");
		}
		if(!is_dir("var/form/$this->formName/datablock/$datablockName/items"))
		{
			mkdir("var/form/$this->formName/datablock/$datablockName/items");
		}
			
		
		$datablockPropertyFilename = "var/form/$this->formName/datablock/$datablockName/property.php";
		$php = '<?php
		$datablockProperty = array();
		$datablockProperty["name"]="'.$datablockName.'";
		$datablockProperty["tableName"]="'.$tableName.'";
?>';
		$file = new File($datablockPropertyFilename);
		$file->write($php);
		if($this->dbDriver=="oracle")
		{
			$q="select * from $tableName where rownum <= 1";
		}
		else if($this->dbDriver == "mysql")
		{
			$q="select * from $tableName limit 1,1";
		}
		$r = $this->db->execute($q);
		
		for($i=0;$i<count($r->columns);$i++)
		{
			$colName = $r->columns[$i]->getName();
			$colType = $r->columns[$i]->getType();
			$colSize = $r->columns[$i]->getSize();
			if(!is_dir("var/form/$this->formName/datablock/$datablockName/items/$colName"))
			{
				mkdir("var/form/$this->formName/datablock/$datablockName/items/$colName");
				mkdir("var/form/$this->formName/datablock/$datablockName/items/$colName/trigger");
				$filename = "var/form/$this->formName/datablock/$datablockName/items/$colName/property.php";
				if($i==0)
				{
					$prCol="true";
				}
				else
				{
					$prCol="false";
				}
				$formatMask="";
				if($colType=="DATE")
				{
					$formatMask = "DD/MM/RRRR";
				}
				$php = '<?php
	$colProperty = array();
	$colProperty["name"]="'.$colName.'";		// Name
	$colProperty["primary"]='.$prCol.';				// Primary column 
	$colProperty["dbName"]="'.$colName.'";		// Database column name
	$colProperty["className"]="TextBox";		// Object Class Name
	$colProperty["dbType"]="'.$colType.'";		// Database column type
	$colProperty["dbSize"]="'.$colSize.'";		// Database column site
	$colProperty["formatMask"]="'.$formatMask.'";		// Value Format mask
	$colProperty["defaultValue"]="";		// Default Value
	$colProperty["visible"]=true;		// Visible in view
	$colProperty["editable"]=true;		// User can edit value
	$colProperty["enable"]=true;		// User can navigate by mouse or keyboard
	$colProperty["initValue"]="";		// Initial Value
	$colProperty["sourceType"]="";		// Suggestion population source type. It can be blank, QUERY or ARRAY
	$colProperty["sourceValue"]="";		// Suggestion population source string. It can be blank, SQL Query or ARRAY String
?>';
				$file = new File($filename);
				$file->write($php);
			}
		}
		$this->objectName = $datablockName;
		$this->objectType = "datablock";
		$this->prompt = "$this->dbName@$this->formName/$this->objectType/$this->objectName";
		
		
		
		if(!is_dir("var/form/$this->formName/datablock/$datablockName/trigger"))
		{
			$colList = $this->getDatablockFieldList($datablockName);
			$colList = $colList->getCsvColumn("name",'"');
			$colList = str_replace('","',',&$',$colList);
			$colList = substr($colList,1,strlen($colList));
			$colList = "&$".$colList;
			$colList = str_replace('"',"",$colList);
			
			mkdir("var/form/$this->formName/datablock/$datablockName/trigger");
			$txt = '<?php
	function trigger_'.$datablockName.'_priInsert()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priInsert.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_priUpdate()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priUpdate.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_priDelete()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priDelete.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postInsert()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postInsert.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postUpdate()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postUpdate.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postDelete()
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postDelete.php");
			$file->write($txt);
			
			
			
			$colList = str_replace("&","",$colList);
			$colList = "obj,e,".$colList;
			$txt = '
function trigger_'.$datablockName.'_priInsert(obj,e)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priInsert.js");
			$file->write($txt);


			$txt = '
function trigger_'.$datablockName.'_priUpdate(obj,e)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priUpdate.js");
			$file->write($txt);


			$txt = '
function trigger_'.$datablockName.'_priDelete(obj,e)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priDelete.js");
			$file->write($txt);


			$txt = '
function trigger_'.$datablockName.'_postInsert(resp)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postInsert.js");
			$file->write($txt);


			$txt = '
function trigger_'.$datablockName.'_postUpdate(resp)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postUpdate.js");
			$file->write($txt);


			$txt = '
function trigger_'.$datablockName.'_postDelete(resp)
{
	return true;
}
';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postDelete.js");
			$file->write($txt);
			
		}
	
		$CS->showInfo("\nDatablock created.\n");
	}
	function createView($cmd)
	{
		global $CS;
		if(count($cmd)< 5)
		{
			$CS->showError("Insufficient parameter.");
			return;
		}		
		$viewName = $cmd[2];
		$datablockName = $cmd[4];
		$viewType = $cmd[6];
		if(!is_dir("var/form/$this->formName/view/$viewName"))
		{
			mkdir("var/form/$this->formName/view/$viewName");
		}
		
		$filename = "var/form/$this->formName/view/$viewName/property.php";
		$php = '<?php
	$viewProperty = array();
	$viewProperty["name"]="'.$viewName.'";					// name of view
	$viewProperty["datablock"]="'.$datablockName.'";		// datablock name
	$viewProperty["type"]="'.$viewType.'";					// layout type.it can be form/table
	$viewProperty["maxRecordPerPage"]="10";					// if layout is table then number of recrod to display on screen. if is zero then display will all record;
	$viewProperty["showPager"]=true;						// show pager if layout is table
	$viewProperty["showSearchForm"]=true;					// show database search feature for table layout
	$viewProperty["showInlineSearch"]=true;					// show inline search form for table layout
	$viewProperty["ajaxEnable"]=false;						// ajax enable for search and pageing for table layout;
	$viewProperty["enableAddNewRecord"]=true;				// show add new record in table
	$viewProperty["enableRemoveRecord"]=true;				// show remove link in table
	$viewProperty["enableRemoveRecordAjaxImprassion"]=false;// eable ajax for remove data from datable when remove record from table
?>';
		$file = new File($filename);
		$file->write($php);
		if($viewType=="form")
		{
			$html = "<table>\n";
			$block = $this->loadDatablock($datablockName);
			for($i=0;$i<count($block["items"]);$i++)
			{
				$colFile = "var/form/$this->formName/datablock/".$datablockName."/items/".$block["items"][$i]["name"]."/property.php";
				include "$colFile";
				if($colProperty['visible']==true)
				{
				$html.="\t<tr>\n";
				$html.="\t\t<td>".$block["items"][$i]["name"]."</td>\n";
				$html.="\t\t<td>{".$block["items"][$i]["name"]."}</td>\n";
				$html.="\t</tr>\n";
				}
			}
			$html .= "</table>";
			$html .= "{FORM_SUBMIT} {FORM_RESET}";
			$template = "var/form/$this->formName/view/$viewName/template.php";
			$file = new File($template);
			$file->write($html);
		}
		else
		{
			$html = '{TABLE}
			<br>
			{FORM_SUBMIT} {FORM_RESET}';
			$template = "var/form/$this->formName/view/$viewName/template.php";
			$file = new File($template);
			$file->write($html);
		}
		$CS->showInfo("\nView created.\n");
		$this->objectName = $viewName;
		$this->objectType = "view";
		$this->prompt = "$this->dbName@$this->formName/$this->objectType/$this->objectName";		
		
	}
	function openForm($cmd)
	{
		global $CS;
		$name = $cmd[2];
		if(file_exists("var/form/$name/property.php"))
		{
			$this->formName = $cmd[2];
			$this->prompt = "$this->dbName@$this->formName";
		}
		else
		{
			$CS->showError("Form file not exist.");
			return false;
		}
	}	
	function _createClass($cmd)
	{
		global $CS;
		$form = new FormBuilderForm($this->formName);
		$addModule=$form->getAddModulePath();
		$editModule=$form->getEditModulePath();
		$removeModule=$form->getRemoveModulePath();
		$listModule=$form->getListModulePath();
		$initModule=$form->getInitModulePath();
		$dBlocks = $form->getDatablocks();
		$urlMode = $form->getProperty("urlMode");
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}		
		for($i=0;$i<count($dBlocks);$i++)
		{
			$dBlock = $dBlocks[$i];
			$className = "Table_".$dBlock->getProperty("tableName");
			$filename = "class.".$dBlock->getProperty("tableName").".php";
			$tableName=$dBlock->getProperty("tableName");
			$varList = $dBlock->getPhpClassVarsStr();
			$classMember=$dBlock->getPhpClassMemberVars();
			$varListInitVal = $dBlock->getPhpClassVarsInitValStr();
			$triggerPhp = $dBlock->getPhpTriggerString();
			$classFunctionParm = $dBlock->getClassFunctionParm();
			$classFunctionParmInit = $dBlock->getClassFunctionParmInit();
			$classFunctionParmInitPrimery = $dBlock->getClassFunctionParmInit(true);
			$primeryKeyColumns = $dBlock->getClassFunctionPrimeryParm();
			$primeryKeyColumnsList = explode(",",$primeryKeyColumns);
			$pouplatedValuSet = $dBlock->getPouplatedValuSet();
			$inserQuery = $dBlock->getInsertString();		
			$updateQuery = $dBlock->getUpdateString();
			$deleteQuery = $dBlock->getDeleteString();
			$selectQuery = $dBlock->getSelectString();
			$text = '<?php
class '.$className.' extends DBTable
{
	var $db;
	var $err;
	var $message;
	var $dataListTable;
	var $dataListQuery;	
	var $pagerRows;
	var $editBy;
	var $popupWidth;
	var $popupHeight;
	var $editModulePath;
	var $addModulePath;
'.$classMember.'
	function '.$className.'(Connection $db)
	{
		parent::DBTable("",$db);
		
		$this->setTable("'.$dBlock->getProperty("tableName").'");
		$this->dataListTable=null;
		$this->dataListQuery="";
		$this->pagerRows=0;
		$this->db = $db;
		$this->editBy="";
		$this->popupHeight="500px";
		$this->popupWidth="500px";
		$this->editModulePath="$RELETIVE_YAJAN_HOME/'.$form->getProperty('application').'/'.$this->formName.'/edit'.$urlMode.'";
		$this->addModulePath="$RELETIVE_YAJAN_HOME/'.$form->getProperty('application').'/'.$this->formName.'/add'.$urlMode.'";
		$this->db->autoCommit(false);
'.$varListInitVal.'
	}
'.$triggerPhp.'
	function insert('.$classFunctionParm.')
	{
'.$classFunctionParmInit.'
		if($this->trigger_'.$dBlock->name.'_priInsert())
		{
			$q="'.$inserQuery.'";
			if(!$this->db->execute($q))
			{
				$this->err = true;
				$this->message="on insert error.";
				$this->db->rollback();
				return false;
			}
			else
			{
				if(!$this->trigger_'.$dBlock->name.'_postInsert())
				{
					$this->err = true;
					$this->message="post insert trigger error.";
					$this->db->rollback();
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			$this->err = true;
			$this->message="pri insert trigger error.";
			$this->db->rollback();
			return false;
		}
	}
	function update('.$classFunctionParm.')
	{
'.$classFunctionParmInit.'
		if($this->trigger_'.$dBlock->name.'_priUpdate())
		{
			$q="'.$updateQuery.'";
			if(!$this->db->execute($q))
			{
				$this->err = true;
				$this->message="on update error.";
				$this->db->rollback();
				return false;
			}
			else
			{
				if(!$this->trigger_'.$dBlock->name.'_postUpdate())
				{
					$this->err = true;
					$this->message="post update trigger error.";
					$this->db->rollback();
					return false;
				}
				else
				{
					
					return true;
				}
			}
		}
		else
		{
			$this->err = true;
			$this->message="pri update trigger error.";
			$this->db->rollback();
			return false;
		}
	}
	function delete('.$primeryKeyColumns.')
	{
'.$classFunctionParmInitPrimery.'
		if($this->trigger_'.$dBlock->name.'_priDelete())
		{
			$q="'.$deleteQuery.'";
			if(!$this->db->execute($q))
			{
				$this->err = true;
				$this->message="on delete error.";
				$this->db->rollback();
				return false;
			}
			else
			{
				if(!$this->trigger_'.$dBlock->name.'_postDelte())
				{
					$this->err = true;
					$this->message="post delete trigger error.";
					$this->db->rollback();
					return false;
				}
				else
				{
					return true;
				}
			}
		}
		else
		{
			$this->err = true;
			$this->message="pri delete trigger error.";
			$this->db->rollback();
			return false;
		}
	}
	function load('.$primeryKeyColumns.')
	{
'.$classFunctionParmInitPrimery.'
		$q="'.$selectQuery.'";
		$r = $this->db->execute($q);
		if($r->count>0)
		{
'.$pouplatedValuSet.'
		}
	}
	function setListQuery($q)
	{
		$this->dataListQuery=$q;
		$this->query=$q;
	}
	function setPopupSize($w,$h)
	{
		$this->popupHeight=$h;
		$this->popupWidth=$w;
	}
	function buildList()
	{
		
		if($this->dataListQuery!="")
		{
			$q=$this->dataListQuery;
		}
		else
		{
			$q="select * from '.$dBlock->getProperty("tableName").' order by 1";
		}
			
		
		
		$this->dataListTable = new Table("'.str_replace("_","",$dBlock->getProperty("tableName")).'");
		$this->dataListTable->setQuery($q,$this->db);
		return $this->dataListTable;
	}
	function addLink($name,$onClick)
	{
		$l= new Link("");
		$e= $l->addJsEvent("onClick",$onClick);
		$l->setUrl("javascript:");
		
		if($this->dataListTable!=null)
		{
			$this->dataListTable->setColumnClass($name,$l);
			';
				//$pList = "";
				for($ixx=0;$ixx<count($primeryKeyColumnsList);$ixx++)
				{
					$idCol1=str_replace("$","",$primeryKeyColumnsList[$ixx]);
					$idCol=str_replace("ID","ID_ID",$idCol1);
					$text.='$this->dataListTable->bindWith("'.$idCol1.'",$name,"'.$idCol.'");
			';
				}
			
			$text.='
			
		}
		return $e;
	}
	function randerList()
	{
		if($this->dataListTable==null)
		{
			$this->buildList();
		}
		$this->randerAdd();
		$this->dataListTable->searchForm();
		$this->makePager();
		if($this->editBy!="")
		{
			echo \'
			<script type="text/javascript">
			function onClickEdit'.$tableName.'(obj,e)
			{
				';
				$pList = "";
				for($ixx=0;$ixx<count($primeryKeyColumnsList);$ixx++)
				{
					$colName1=str_replace("$","",$primeryKeyColumnsList[$ixx]);
					$colName=str_replace("ID","ID_ID",$colName1);
					$text.='var '.$colName.'= obj.get("'.$colName.'");
				';
					$pList.="$colName1=\"+$colName+\"&";
				}
				
				$text.='
				showLink("\'.$this->editModulePath.\'?'.$pList.'","\'.$this->popupWidth.\'","\'.$this->popupHeight.\'");
			}
			</script>\';
			$this->addLink($this->editBy,"onClickEdit'.$tableName.'");
		}
		$this->dataListTable->addClass("whightTable");
		$this->dataListTable->rander();	
	}
	function commit()
	{
		$this->db->commit();
	}
	function getValueAs($name,$obj)
	{
		$o = new $obj("$name");
		$o->setValue($this->$name);
		return $o;
	}
	function randerAdd()
	{
		echo '."'<script type=\"text/javascript\">
		function onClickAdd$tableName(obj,e)
		{
			showLink(\"'.".'$this->addModulePath'.".'\",\"'.".'$this->popupWidth'.".'\",\"'.".'$this->popupHeight'.".'\");
		}
		</script>';".'
		$b = new Button("addButton'.$tableName.'");
		$b->addJsEvent("onClick","onClickAdd'.$tableName.'");
		$b->setValue("Add");
		$b->rander();
	}
	function pager($row)
	{
		$this->pagerRows=$row;
	}
	function makePager()
	{
		if($this->pagerRows>0)
		{
			$this->dataListTable->maxRowPerPage($this->pagerRows);
			echo \'<div id="pager">\';
			$this->dataListTable->showPager("'.$initModule.'");
			echo \'</div>\';
		}
	}
	function editBy($name)
	{
		$this->editBy=$name;
	}
}
?>';
			$form->writeFile($filename,$text);
			$CS->showInfo("class module compile.");
		}
		return $className;
	}
	
	function _add($cmd,$className)
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		$MP = "";
		$RP = "../../";
		$dbName=$this->db->database->parm["name"];
		if($MODULE_PATH!="")
		{
			$MP = $MODULE_PATH."/";
			$RP = "../../../";
		}	
		$php="";
		$form = new FormBuilderForm($this->formName);
		$urlMode = $form->getProperty("urlMode");
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}
		if($urlMode=="file")
		{
$php .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>';
		}
		
		$viewHtml = $form->getViewHtml();
$dBlock=$form->getDatablocks();
$dBlockName=$dBlock[0]->getProperty("tableName");
$classFunctionParm = $dBlock[0]->getClassFunctionParm();
$php .= '<?php
include "class.'.$dBlockName.'.php";
$db=new Connection("'.$dbName.'");
$tb=new '.$className.'($db);
$form = new Form("'.$this->formName.'");
$form->ajax(true);
$form->ajaxCallback("callback_'.$this->formName.'");
$form->setUrl("$RELETIVE_YAJAN_HOME/'.$form->getProperty('application').'/'.$this->formName.'/add'.$urlMode.'");
if($form->submited())
{
	if(!$tb->insert('.$classFunctionParm.'))
	{
		echo "error in insert";
	}
	else
	{
		$db->commit();
	}
}
else
{
	?>
	<script type="text/javascript">
	function callback_'.$this->formName.'(resp)
	{
		if(resp=="")
		{
			ajax("<?=$RELETIVE_YAJAN_HOME?>/'.$form->getProperty('application').'/'.$this->formName.'/list'.$urlMode.'","",function(resp)
			{
				$("#listTable").html(resp);
				$.colorbox.close();
			});
		}
		else
		{
			alert(resp);
		}
	}
	</script>
	<?php
	$form->begin();
	?>
	'.$viewHtml.'
	<?php
	$form->submit();
	$form->reset();
	$form->end();
}
?>';	
		$form->writeFile("add.php",$php);
	}
	
	
	
	
	function _edit($cmd,$className)
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		$MP = "";
		$RP = "../../";
		if($MODULE_PATH!="")
		{
			$MP = $MODULE_PATH."/";
			$RP = "../../../";
		}	
		$php="";
		$dbName=$this->db->database->parm["name"];
		$form = new FormBuilderForm($this->formName);
		$urlMode = $form->getProperty("urlMode");
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}
		if($urlMode=="file")
		{
$php .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>';
		}
		
		$viewHtml = $form->getViewHtml();
		
$dBlock=$form->getDatablocks();
$dBlockName=$dBlock[0]->getProperty("tableName");
$primeryKeyColumns = $dBlock[0]->getClassFunctionPrimeryParm();
$classFunctionParm = $dBlock[0]->getClassFunctionParm();
$php .= '<?php
include "class.'.$dBlockName.'.php";
$db=new Connection("'.$dbName.'");
$tb=new '.$className.'($db);
$form = new Form("'.$this->formName.'");
$form->ajax(true);
$form->ajaxCallback("callback_'.$this->formName.'");
$form->setUrl("$RELETIVE_YAJAN_HOME/'.$form->getProperty('application').'/'.$this->formName.'/edit'.$urlMode.'");
if($form->submited())
{
	if(!$tb->update('.$classFunctionParm.'))
	{
		echo "error in updation";
	}
	else
	{
		$db->commit();
	}
}
else
{
	?>
	<script type="text/javascript">
	function callback_'.$this->formName.'(resp)
	{
		if(resp=="")
		{
			ajax("<?=$RELETIVE_YAJAN_HOME?>/'.$form->getProperty('application').'/'.$this->formName.'/list'.$urlMode.'","",function(resp)
			{
				$("#listTable").html(resp);
				$.colorbox.close();
			});
		}
		else
		{
			alert(resp);
		}
	}
	</script>
	<?php
	$form->begin();
	
	$tb->populate('.$primeryKeyColumns.');
	
	?>
	'.$viewHtml.'
	<?php
	$form->submit();
	$form->reset();
	$form->end();
}
?>';	
		$form->writeFile("edit.php",$php);
	}
	function _init($cmd)
	{
		$form = new FormBuilderForm($this->formName);
		$dbName=$this->db->database->parm["name"];
		$php ='<h3>'.$form->name.'</h3>
		<div id="listTable">
<?php
include "list.php";
?>
		</div>';
		
		$form->writeFile("init.php",$php);
	}
	function _list($cmd,$className)
	{
		$form = new FormBuilderForm($this->formName);
		$dBlock=$form->getDatablocks();
		$dBlockName=$dBlock[0]->getProperty("tableName");
		$dbName=$this->db->database->parm["name"];
		$php ='<?php
$db=new Connection("'.$dbName.'");
include "class.'.$dBlockName.'.php";
$bm= new '.$className.'($db);
$bm->buildList();
$bm->randerList();
?>';
		
		$form->writeFile("list.php",$php);
	}
	function createFormClass($cmd)
	{
		global $CS;
		$className=$this->_createClass($cmd);
		$CS->showInfo("Class $clssName is created");
	}
	function compile($cmd)
	{
		$className=$this->_createClass($cmd);
		$this->_add($cmd,$className);
		$this->_edit($cmd,$className);
		$this->_list($cmd,$className);
		$this->_init($cmd);
	}

}
?>