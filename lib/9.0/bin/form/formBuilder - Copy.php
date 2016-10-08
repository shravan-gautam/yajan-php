<?php
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
	function FormBuilder($cmd)
	{
		global $CS;
		$this->cmd = $cmd;
		$this->version = "1.1";
		$CS->setFGColor("yellow");
		$CS->cout("\n\nWelcome Form Builder $this->version\n\n");	
	}

	function run()
	{
		global $CS,$DB_REG;
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
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="create")
			{
				if($cmd[1]=="form")
				{
					$this->createForm($cmd);
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
			echo "FORM-BUILDER: [".$this->prompt."] > ";
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}
	function createForm($cmd)
	{
		global $CS,$FORM_URL_MODE;
		if(!isset($FORM_URL_MODE))
		{
			$FORM_URL_MODE = "file";
		}
		if(count($cmd)<5)
		{
			$CS->showError("Insufficient parameter\n");
			return;
		}
		$this->formName = $cmd[2];
		$this->application = $cmd[4];
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
	function trigger_'.$datablockName.'_priInsert('.$colList.')
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priInsert.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_priUpdate('.$colList.')
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priUpdate.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_priDelete('.$colList.')
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/priDelete.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postInsert('.$colList.')
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postInsert.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postUpdate('.$colList.')
	{
		return true;
	}
?>';
			$file = new File("var/form/$this->formName/datablock/$datablockName/trigger/postUpdate.php");
			$file->write($txt);
			
			$txt = '<?php
	function trigger_'.$datablockName.'_postDelete('.$colList.')
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
	function loadDatablock($blockName)
	{
		global $CS;
		$blockPath = "var/form/$this->formName/datablock/$blockName";
		$path = new Path("$blockPath/items");
		$path->setRecursive(true);
		$filelist = $path->getRecordset();
		$datablock = array();
		include "var/form/$this->formName/datablock/$blockName/property.php";
		$datablock['property'] = $datablockProperty;
		$datablock['items']=array();
		for($i=0;$i<$filelist->count;$i++)
		{
			$itemName = $filelist->data[$i]['NAME'];
			$filename = "var/form/$this->formName/datablock/$blockName/items/$itemName/property.php";
			include $filename;
			$datablock['items'][$i]=$colProperty;
		}
		
		return $datablock;
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
	function makeView($view,$initValue=true)
	{
		$viewFile = "var/form/$this->formName/view/$view/property.php";
		
		$templateFile = "var/form/$this->formName/view/$view/template.php";
		$file = new File($templateFile);
		$html = $file->read();
		include $viewFile;
		//print_r($viewProperty);
		if($viewProperty['type']=="form")
		{
			$blockDir = "var/form/$this->formName/datablock/".$viewProperty['datablock']."/items";
			$p = new Path($blockDir);
			$list = $p->getRecordset();
			for($i=0;$i<$list->count;$i++)
			{
				if($list->data[$i]['TYPE']=="FOLDER")
				{
					
					$colFile = "var/form/$this->formName/datablock/".$viewProperty['datablock']."/items/".$list->data[$i]['NAME'];
					include "$colFile/property.php";
					$className = $colProperty['className'];
					$itemName = $colProperty['name'];
					$itemId = 'Item'.$viewProperty['datablock'].$colProperty['name'];
					
					
					
					$str='<?php
		$item = new '.$className.'("'.$itemId.'");';
		if($initValue)
		{
			$str.='
		$item->setValue("'.$colProperty["defaultValue"].'");';
		}
		else
		{
			$str.='
		$item->setValue($info->data[0]['."'".$list->data[$i]['NAME']."'".']);
		';
		if($colProperty['primary'])
		{
			$str.='$item->readonly(true);
		';
		}
		
		}
		$str.='
		$item->rander();
		?>';
					$html = str_replace("{".$itemName."}","$str",$html);
				}
			}								
			$html = str_replace("{FORM_SUBMIT}",'<?php $form->submit();?>',$html);
			$html = str_replace("{FORM_RESET}",'<?php $form->reset();?>',$html);
			return $html;
		}
		else
		{
			$php = '<?php
			$rec = new Recordset();
			$table = new Table("Table_'.$view.'");
			';
			$blockDir = "var/form/$this->formName/datablock/".$viewProperty['datablock']."/items";
			$p = new Path($blockDir);
			$list = $p->getRecordset();
			$colList = "";
			$colValue = "";
			for($i=0;$i<$list->count;$i++)
			{
				if($list->data[$i]['TYPE']=="FOLDER")
				{
					$colFile = "var/form/$this->formName/datablock/".$viewProperty['datablock']."/items/".$list->data[$i]['NAME'];
					include "$colFile/property.php";
					$className = $colProperty['className'];
					$itemName = $colProperty['name'];
					$itemId = 'Item'.$viewProperty['datablock'].$colProperty['name'];
					$colList .= '"'.$itemName.'",';
					$colValue .= '"'.$colProperty['initValue'].'",';
				}
			} 
			$colList = rtrim($colList,",");
			$colValue = rtrim($colValue,",");
			
			$php .='
			$rec->addColumn('.$colList.');
			$rec->add('.$colValue.');
			$table->setRecordset($rec);
';

			for($i=0;$i<$list->count;$i++)
			{
				if($list->data[$i]['TYPE']=="FOLDER")
				{
					$colFile = "var/form/$this->formName/datablock/".$viewProperty['datablock']."/items/".$list->data[$i]['NAME'];
					include "$colFile/property.php";
					$php .= '
			$obj = new '.$colProperty['className'].'("");
			$table->setColumnClass("'.$colProperty['name'].'",$obj);';
				}
			}
			
			$php.='
			$table->rander();
?>';
			$html = str_replace("{TABLE}",$php,$html);
			$html = str_replace("{FORM_SUBMIT}",'<?php $form->submit();?>',$html);
			$html = str_replace("{FORM_RESET}",'<?php $form->reset();?>',$html);
			
			return $html;
		}
	}
	function _createClass($cmd)
	{
		
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		include "var/form/$this->formName/property.php";
		$datablockPath = "var/form/$this->formName/datablock";
		$p = new Path($datablockPath);
		
		$list = $p->getRecordset();
		$classList = "";
		
		$classInsert = "";
		$classUpdate = "";
		$classDelete = "";
		for($i=0;$i<$list->count;$i++)
		{
			$filename = $datablockPath."/".$list->data[$i]['NAME']."/property.php";
			include "$filename";
			$datablockName = $datablockProperty['name'];
			$tableName = $datablockProperty['tableName'];
			$colList = $this->getDatablockFieldList($datablockName);
			$varList = "";
			$varListInit = "";
			$varListInitVal = "";
			$fieldList = "";
			$condList1 = "";
			$parmList1 = "";
			$parmList2 = "";
			$insValueList = "";
			$insFealdList = "";
			$updateList = "";
			$varFldInit = "";
			$priCol = array();
			for($j=0;$j<$colList->count;$j++)
			{
				$cl  =$colList->data[$j]['NAME'];
				include "var/form/$this->formName/datablock/$datablockName/items/$cl/property.php";
				if($colProperty['dbType']=="DATE")
				{
					$msk = $colProperty['formatMask'];
					$fieldList .= "to_char($cl,'$msk') as $cl,";
					$insValueList .= "to_date('$"."this->$cl','$msk'),";
				}
				else
				{
					$fieldList .= $cl.",";
					$insValueList .= "'$"."this->$cl',";
				}
				$insFealdList .= $cl.',';
				if($colProperty['primary'])
				{
					$priCol[count($priCol)]=$cl;
					$parmList1 .= "$".$cl.",";
					if($colProperty['dbType']=="DATE")
					{
						$msk = $colProperty['formatMask'];
						$condList1 .= "$cl = to_date('$".$cl."','$msk') and ";
					}
					else
					{
						$condList1 .= "$cl = '$".$cl."' and ";
					}
					
				}
				else
				{
					
					if($colProperty['dbType']=="DATE")
					{
						$msk = $colProperty['formatMask'];
						$updateList .= "$cl = to_date('$"."this->$cl"."','$msk'), ";
					}
					else
					{
						$updateList .= "$cl = '$"."this->$cl"."', ";
					}
					
				}
				$parmList2 .= "$".$cl.",";
				
				
$varList.='
	var $'.$cl.';';
$varListInit.='
		$this->'.$cl.'="";';
$varListInitVal.='
		$this->'.$cl.'=$'.$cl.';';		
$varFldInit = "
		$"."this->$cl='$"."r->data[0][".'"'.$cl.'"'."]';";
		
			}
			
			if(isset($priCol[0]))
			{
				$priCol0 = $priCol[0];
			}
			else
			{
				$priCol0="";
			}
			
			
				$p = new Path("var/form/$this->formName/datablock/$datablockName/trigger");
				$p->setExt("php");
				$list = $p->getRecordset();
				$libString = "";
				for($i=0;$i<$list->count;$i++)
				{
					$filePath = $list->data[$i]['PATH'];
					//if(strpos(strtolower($list->data[$i]['NAME']),"insert")!==false)
					{
						$f = new File($filePath);
						$libString.= $f->read();
					}
				}
				$libString = str_replace("<?php","",$libString);
				$libString = str_replace("?>","",$libString);
				
			$fieldList = rtrim($fieldList,",");
			$parmList1  = rtrim($parmList1,",");
			$parmList2  = rtrim($parmList2,",");
			$condList1  = rtrim($condList1,"and ");
			$insFealdList = rtrim($insFealdList,",");
			$updateList = rtrim($updateList,",");
$classOuput = '';
$classOuput.='<?php
class Table_'.$tableName.'
{
	var $name;
	var $tableName;
	'.$varList.'
	var $db;
	function Table_'.$tableName.'($dbLink)
	{
		$this->db = $dbLink;
		$this->name="'.$datablockName.'";
		$this->tableName="'.$tableName.'";
		'.$varListInit.'
	}
'.$libString.'	
	function populateAll()
	{
		$q="select '.$fieldList.' from $this->tableName";
		return $this->db->execute($q);
	}
	function populate('.$parmList1.')
	{
		$q="select '.$fieldList.' from $this->tableName where 1 =1 and '.$condList1.'";
		$r= $this->db->execute($q);
		'.$varFldInit.'
		return $r;
	}
	function getNewId()
	{
		';
		if($priCol0!="")
		{
$classOuput.=
'	
		$q="select nvl(max(to_number('.$priCol0.')),0)+1 as newId from $this->tableName";
		$r = $this->db->execute($q);
		$this->'.$priCol0.'=$r->data[0]["NEWID"];
		if($this->'.$priCol0.'=="")
		{
			$this->'.$priCol0.'=1;
		}
		return $this->'.$priCol0.';
';			
		}
		else
		{
$classOuput.=
'
		return "0";
';	
		}
$classOuput.=
'		
	}
	function insert('.$parmList2.')
	{
		'.$varListInitVal.';
		$this->getNewId();
		$q="insert into $this->tableName('.$insFealdList.') values('.$insValueList.')";
		$this->db->execute($q);
	}
	function update('.$parmList2.')
	{
		'.$varListInitVal.';
		$q="update $this->tableName set '.$updateList.' where 1=1 and '.$condList1.'";
		$this->db->execute($q);
		
	}
	function delete()
	{
		$q="delete from $this->tableName where 1=1 and '.$condList1.'";
		$this->db->execute($q);
	}
}
?>';		
			$file = new File("var/form/$this->formName/output/class.table.$tableName.php");
			$file->write($classOuput);
		}

		
				
	}
	function _add($cmd)
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		$CS->showInfo("Make Views Adding");

		$MP = "";
		$RP = "../../";
		if($MODULE_PATH!="")
		{
			$MP = $MODULE_PATH."/";
			$RP = "../../../";
		}
		include "var/form/$this->formName/property.php";
		$urlMode = $formProperty['urlMode'];
		$datablockName = $formProperty['name'];
		$tableName = $formProperty['tableName'];		
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
		$addhtml .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>';
		}

		
		$viewFile = "var/form/$this->formName/view/";
		$p = new Path($viewFile);
		$list = $p->getRecordset();
		$addhtml = '';
		
		$viewHtml = "";
		if($formProperty['ajaxSubmission']==true)
		{
			for($i=0;$i<$list->count;$i++)
			{
				if($list->data[$i]['TYPE']=='FOLDER')
				{
					$viewName = $list->data[$i]['NAME'];
					$CS->showInfo("\t$viewName...");
					$viewHtml .= $this->makeView($viewName);
				}
			}
		}	
		$datablockPath = "var/form/$this->formName/datablock";
		$p = new Path($datablockPath);
		
		$classFileName="";
		$list = $p->getRecordset();
		for($i=0;$i<$list->count;$i++)
		{
			$filename = $datablockPath."/".$list->data[$i]['NAME']."/property.php";
			include "$filename";
			$datablockName = $datablockProperty['name'];
			$tableName = $datablockProperty['tableName'];
$classFileName .='
	include "class.table.'.$tableName.'.php";';
			
		}
		
$addhtml.='<?php
'.$classFileName.';
	$form = new Form("AddForm'.$this->formName.'");
	$form->ajax(true);
	$form->ajaxCallback("postComplite_'.$this->formName.'");
	$form->setUrl("<?=$RELETIVE_YAJAN_HOME?>'.$MP.$formProperty['application'].'/'.$this->formName.'/add'.$urlMode.'");
	if($form->submited())
	{
		';
		$datablockPath = "var/form/$this->formName/view";
		$p = new Path($datablockPath);
		$list = $p->getRecordset();
		
		for($i=0;$i<count($list);$i++)
		{	
			if($list->data[$i]['TYPE']=="FOLDER")
			{
				include "var/form/$this->formName/view/".$list->data[$i]['NAME']."/property.php";
				
				$name = $viewProperty['name'];
				$viewType = $viewProperty['type'];
				$datablockName = $viewProperty['datablock'];
				include "var/form/$this->formName/datablock/$datablockName/property.php";
				$tableName = $datablockProperty['tableName'];
				$className = "Table_$tableName";
				
				$colList2 = "";
				$colList3 = "";
				$colList1 = "";
				$priCol="";
				$colList = $this->getDatablockFieldList($datablockName);
				for($j=0;$j<$colList->count;$j++)
				{
					$cl  =$colList->data[$j]['NAME'];
					include "var/form/$this->formName/datablock/$datablockName/items/$cl/property.php";
					$colList2 .= $cl.",";
					if($colProperty['primary'])
					{
						$priCol.=$cl.",";
					}
					if($colProperty['dbType']=="DATE")
					{
						$mask = $colProperty['formatMask'];
						$colList3 .= "to_date('$"."Item".$datablockName.$cl."','$mask'),";
					}
					else
					{
						$colList3 .= "'$"."Item".$datablockName.$cl."',";
					}
					$colList1 .="$"."Item$datablockName$cl,";
				}
				$priCol= rtrim($priCol,",");
				$priCol= explode(",",$priCol);
				$colList1 = rtrim($colList1,",");
				
				/*
				$addhtml.='
				$q="select nvl(max(to_number('.$priCol[0].')),0)+1 as new_id from '.$tableName.'";
				$r = $db->execute($q);
				
				$'.$priCol[0].'=$r->data[0]["NEW_ID"];
				if($'.$priCol[0].'=="")
				{
					$'.$priCol[0].'="1";
				}
				';
				*/
				
				
				/*
				$p = new Path("var/form/$this->formName/datablock/$datablockName/trigger");
				$p->setExt("php");
				$list = $p->getRecordset();
				$libString = "";
				for($i=0;$i<$list->count;$i++)
				{
					$filePath = $list->data[$i]['PATH'];
					if(strpos(strtolower($list->data[$i]['NAME']),"insert")!==false)
					{
						$f = new File($filePath);
						$libString.= $f->read();
					}
				}
				*/
				$tableObjectName = "tableObj$className";
				$addhtml.='
		$'.$tableObjectName.' = new '.$className.'();
		$text="";
		$respType="";
		$db->autoCommit(false);
		$err=false;
		if(!$'.$tableObjectName.'->insert('.$colList1.'))
		{
			$err=true;
		}
		if(!$err)
		{
			$db->commit();
			$respType="OK";
		}
		else
		{
			$db->rollback();
			$respType="ERROR";
		}
		';
		$addhtml.="echo '{".'"type":"'."'.$"."respType.'".'","errorCode":"'."'.$"."errCode.'".'","errorMessage":"'."'.$"."errMessage.'".'","text":"'."'.$"."text.'".'"'."}';";
			}
		}
		$addhtml.='
	}
	else
	{
		$form->begin();
?>
<script type="text/javascript">
function postComplite_'.$this->formName.'(resp)
{
	try
	{
		var obj = $.parseJSON(resp);
		if(obj.type=="OK")
		{
			alert(obj.text);
			updateList();
			$.colorbox.close();
		}
		else
		{
			alert(obj.errorCode+":"+obj.errorMessage);
		}
	}
	catch(ex)
	{
		alert(resp);
	}
}
</script>
'.$viewHtml.'
<?php
		$form->end();
	}
			';
		
		$addhtml.='
?>';
		$file = new File("var/form/$this->formName/output/add.php");
		$output = $addhtml;
		$output = str_replace("?><?php","",$output);
		$file->write($output);

	}
	function _edit($cmd)
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		$CS->showInfo("Make Views for Editing");
		$MP = "";
		$RP = "../../";
		if($MODULE_PATH!="")
		{
			$MP = $MODULE_PATH."/";
			$RP = "../../../";
		}		
		include "var/form/$this->formName/property.php";
		$urlMode = $formProperty['urlMode'];
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}
		$addhtml = '';
		if($urlMode=="file")
		{
		$addhtml .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>';
		}
$addhtml.='<?php
	$form = new Form("EditForm'.$this->formName.'");
	$form->ajax(true);
	$form->ajaxCallback("postComplite_'.$this->formName.'");
	$form->setUrl("<?=$RELETIVE_YAJAN_HOME?>'.$MP.$formProperty['application'].'/'.$this->formName.'/edit'.$urlMode.'");
	if($form->submited())
	{
		';
		
		$datablockPath = "var/form/$this->formName/view";
		$p = new Path($datablockPath);
		$list = $p->getRecordset();
		
		for($i=0;$i<count($list);$i++)
		{	
			if($list->data[$i]['TYPE']=="FOLDER")
			{
				include "var/form/$this->formName/view/".$list->data[$i]['NAME']."/property.php";
				
				$name = $viewProperty['name'];
				$viewType = $viewProperty['type'];
				$datablockName = $viewProperty['datablock'];
				$colList = $this->getDatablockFieldList($datablockName);
				$colList3 = "";
				$where = ' and ';
				$priCol="";
				$colList1 = "";
				for($j=0;$j<$colList->count;$j++)
				{
					$cl  =$colList->data[$j]['NAME'];
					include "var/form/$this->formName/datablock/$datablockName/items/$cl/property.php";
					if($colProperty['primary'])
					{

						$priCol.=$cl.",";
						if($colProperty['dbType']=="DATE")
						{
							$mask = $colProperty['formatMask'];
							$where .= $cl."=to_date('$"."Item".$datablockName.$cl."','$mask') and ";
						}
						else
						{
							$where .= $cl."='$"."Item".$datablockName.$cl."' and ";
						}
					}
					else
					{
						if($colProperty['dbType']=="DATE")
						{
							$mask = $colProperty['formatMask'];
							$colList3 .= $cl."=to_date('$"."Item".$datablockName.$cl."','$mask'),";
						}
						else
						{
							$colList3 .= $cl."='$"."Item".$datablockName.$cl."',";
						}
					}
					$colList1 .="$"."Item$datablockName$cl,";
				}
				$priCol= rtrim($priCol,",");
				$priCol= explode(",",$priCol);
				$where  = rtrim($where,"and ");
				$colList3 = rtrim($colList3,",");
				$colList1 = rtrim($colList1,",");
				
				include "var/form/$this->formName/datablock/$datablockName/property.php";
				$tableName = $datablockProperty['tableName'];
				$p = new Path("var/form/$this->formName/datablock/$datablockName/trigger");
				$p->setExt("php");
				$list = $p->getRecordset();
				$libString = "";
				for($j=0;$j<$list->count;$j++)
				{
					$filePath = $list->data[$j]['PATH'];
					if(strpos(strtolower($list->data[$j]['NAME']),"update")!==false)
					{
						$f = new File($filePath);
						$libString.= $f->read();
					}
				}
				$addhtml.='?>'.'<?php
		$db->autoCommit(false);
		$err=false;
		$text="";
		$respType="";
		if(!trigger_'.$datablockName.'_priUpdate('.$colList1.'))
		{
			$err = true;
			$errCode="E001";
			$errMessage="Trigger pri update is return false.";
		}
		else
		{
			$q="update '.$tableName.' set '.$colList3.' where 1 = 1 '.$where.'";
			if(!$db->execute($q))
			{
				$err=true;
				$errCode="E002";
				$errMessage="database error.";
			}
			else
			{
				$text = "Update successfully";
			}
			if(!trigger_'.$datablockName.'_postUpdate('.$colList1.'))
			{
				$err = true;
				$errCode="E003";
				$errMessage="Trigger post insert is return false.";
			}
		}
		if(!$err)
		{
			$db->commit();
			$respType="OK";
		}
		else
		{
			$db->rollback();
			$respType="ERROR";
		}
		';
		
			}
		}
		$addhtml.="echo '{".'"type":"'."'.$"."respType.'".'","errorCode":"'."'.$"."errCode.'".'","errorMessage":"'."'.$"."errMessage.'".'","text":"'."'.$"."text.'".'"'."}';";
		$addhtml.='
	}
	else
	{
		$form->begin();
?>
<script type="text/javascript">
function postComplite_'.$this->formName.'(resp)
{
	try
	{
		var obj = $.parseJSON(resp);
		if(obj.type=="OK")
		{
			alert(obj.text);
			updateList();
			$.colorbox.close();
		}
		else
		{
			alert(obj.errorCode+":"+obj.errorMessage);
		}
	}
	catch(ex)
	{
		alert(resp);
	}
}
</script>
';
		$viewFile = "var/form/$this->formName/view/";
		$p = new Path($viewFile);
		$list = $p->getRecordset();
		$viewHtml = "";
		$viewHtml .='
		<?php
		$q="select * from '.$tableName.' where '.$priCol[0].'='."'$".$priCol[0]."'".'";
		$info = $db->execute($q);
		
		?>
		';
		if($formProperty['ajaxSubmission']==true)
		{
			for($i=0;$i<$list->count;$i++)
			{
				if($list->data[$i]['TYPE']=='FOLDER')
				{
					$viewName = $list->data[$i]['NAME'];
					$CS->showInfo("\t$viewName...");
					$viewHtml .= $this->makeView($viewName,false);
				}
			}
		}

$addhtml.=$viewHtml.'
<?php
		$form->end();
	}
			';
		
		$addhtml.='
?>';
		$output = $libString.$addhtml;
		$output = str_replace("?><?php","",$output);
		$file = new File("var/form/$this->formName/output/edit.php");
		$file->write($output);

	}
	function _list()
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME,$_PWD;
		$CS->showInfo("Make Views for listing");
		include "var/form/$this->formName/property.php";
		$urlMode = $formProperty['urlMode'];
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}
		$MP = "";
		$RP = "../../";
		if($MODULE_PATH!="")
		{
			if($urlMode=="file")
			{
				$MP = $MODULE_PATH."/";
			}
			else
			{
				$MP = "/";
			}
			$RP = "../../../";
		}
		
		$datablockPath = "var/form/$this->formName/datablock";
		$p = new Path($datablockPath);
		$list = $p->getRecordset();
		
				$script = '';
		if($urlMode=="file")
		{
		$script .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>
';
		}
$script.='<script type="text/javascript">
		';
		$output ='<?php
		';
		
		
		for($i=0;$i<count($list);$i++)
		{
			if($list->data[$i]['TYPE']=="FOLDER")
			{
				include "var/form/$this->formName/datablock/".$list->data[$i]['NAME']."/property.php";
				$datablockName = $datablockProperty['name'];
				$tableName = $datablockProperty['tableName'];
				$colList = $this->getDatablockFieldList($datablockName);
				$priCol='';
				for($j=0;$j<$colList->count;$j++)
				{
					$cl  =$colList->data[$j]['NAME'];
					include "var/form/$this->formName/datablock/$datablockName/items/$cl/property.php";
					if($colProperty['primary'])
					{
						$priCol.=$cl.",";
					}
				}
				$priCol= rtrim($priCol,",");
				$priCol= explode(",",$priCol);
				
				$output .= '
	$q="select * from '.$tableName.'";
	$table= new Table("'.$datablockName.'");
	$table->setQuery($q,$db);
	$table->searchForm();';
	if(count($priCol)>0)
	{
		$script.='
	function onClick_'.$datablockName.'(obj,e)
	{
		showLink("<?=$RELETIVE_YAJAN_HOME?>'.$MP.$formProperty['application'].'/'.$this->formName.'/edit'.$urlMode.'?'.$priCol[0].'="+obj.get("'.$priCol[0].'"),"700px","80%");
	}
	';		
		$output.='
	$link = new Link("");
	$link->addJsEvent("onClick","onClick_'.$datablockName.'");
	$link->setUrl("javascript:");
	$table->setColumnClass("'.$priCol[0].'",$link);
	$table->bindWith("'.$priCol[0].'","'.$priCol[0].'","'.$priCol[0].'");
	';
	}
	$output.='
	$table->rander();
';
			}
		}
		$output.='?>';
		$script.='
</script>
';
		$file = new File("var/form/$this->formName/output/list.php");
		$file->write($script.$output);

		
	}
	function _init($cmd)
	{
		global $CS,$MODULE_PATH,$RELETIVE_YAJAN_HOME;
		$CS->showInfo("Make init script");
		include "var/form/$this->formName/property.php";
		$urlMode = $formProperty['urlMode'];
		if($urlMode=="file")
		{
			$urlMode = ".php";
		}
		else
		{
			$urlMode="";
		}
		
		$MP = "";
		$RP = "../../";
		if($MODULE_PATH!="")
		{
			if($urlMode=="file")
			{
				$MP = $MODULE_PATH."/";
			}
			else
			{
				$MP = "/";
			}
			$RP = "../../../";
		}
		
		$_PWD = getcwd();
		$CS->showInfo("Make Views for listing");
		$output = '';
		if($urlMode=="file")
		{
		$output .= '
<?php
$YAJAN_HOME="'.$_PWD.'";
include "'.$RP.'yajan/include.php";
?>';
		
$output.'
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<?php
	$CSS->loadCommonFile();
	$JS->loadCommonFile();
?>
</head>

<body>
';
		}
$output.='
<h2>'.$this->formName.'</h2>
<script type="text/javascript">
function onClickAddRecord(obj,e)
{
	showLink("<?=$RELETIVE_YAJAN_HOME?>'.$MP.$formProperty['application'].'/'.$this->formName.'/add'.$urlMode.'","700px","80%");
}
function updateList()
{
	ajax("<?=$RELETIVE_YAJAN_HOME?>'.$MP.$formProperty['application'].'/'.$this->formName.'/list'.$urlMode.'","",function(resp)
	{
		$("#recordList").html(resp);
	});
}
</script>
<?php
$b = new Button("newRecord");
$b->setValue("Add New");
$b->addJsEvent("onClick","onClickAddRecord");
$b->rander();
?>
<div id="recordList">
<?php
include "list.php";
?>
</div>';
if($urlMode=="file")
{
$output.='
</body>
</html>

';
}
		$file = new File("var/form/$this->formName/output/init.php");
		$file->write($output);
	}
	function compile($cmd)
	{
		$this->_createClass($cmd);
		$this->_add($cmd);
		$this->_edit($cmd);
		$this->_list($cmd);
		$this->_init($cmd);
	}
	function install($cmd)
	{
		global $CS,$FORM_URL_MODE,$MODULE_PATH;
		if($MODULE_PATH=="" || $FORM_URL_MODE!="path")
		{
			$CS->showError("Invalid value of FORM_URL_MODE or MODULE_PATH parameter");
			return;
		}
		include "var/form/$this->formName/property.php";
		$appName = $formProperty['application'];
		exec("mkdir -p $MODULE_PATH/$appName/$this->formName");
		exec("cp var/form/$this->formName/output/*.php $MODULE_PATH/$appName/$this->formName/");
		$CS->showOk("Form $this->formName installation complete");
		$CS->showInfo("Access url for SSO Registration");
		$CS->showWarnning("/$appName/$this->formName/init");
		$CS->showWarnning("/$appName/$this->formName/list");
		$CS->showWarnning("/$appName/$this->formName/add");
		$CS->showWarnning("/$appName/$this->formName/edit");
	}
}

?>