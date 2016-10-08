<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class ListOfValue
{
	var $events;
	var $id;
	var $query;
	var $db;
	var $url;
	var $bindedColumns;
	var $maxRecord;
	var $returnColumns;
	var $height;
	var $hiddenColumns;
	var $regulerKeys;
	var $encriptedColumns;
	var $hindiSupport;
	var $exitFocus;
	var $onCloseCallback;
	function ListOfValue($id)
	{
		$this->id = $id;
		$this->bindedColumns = array();
		$this->returnColumns = array();
		$this->maxRecord = 20;
		$this->height=430;
		$this->hiddenColumns = array();
		$this->regulerKeys=true;
		$this->encriptedColumns=array();
		$this->hindiSupport=false;
		$this->onCloseCallback = null;
	}
	function setOnCloseCallback($fun)
	{
		$this->onCloseCallback = $fun;
	}
	function exitFocusElement($id)
	{
		$this->exitFocus = $id;
	}
	function setColumnEncription($name,$enc)
	{
		$name = strtoupper($name);
		$this->encriptedColumns[$name]=$enc;
	}
	function setRagulerKey($val)
	{
		if($val)
		{
		$this->regulerKeys = "true";
		}
		else
		{
			$this->regulerKeys = "false";
		}
	}
	function setColumnVisibility($columns,$val=false)
	{
		if($val==false)
		{
			$this->hiddenColumns[count($this->hiddenColumns)]=strtoupper($columns);
		}
	}
	function setQuery($query,$db)
	{
		$this->query = $query;
		$this->db = $db;
	}
	function setHeight($height)
	{
		$this->height = $height;
	}
	function setUrl($url)
	{
		$this->url = $url;
	}
	function columnToSearch($column,$searchType='start')
	{
		$this->bindedColumns[count($this->bindedColumns)]=array($column,-1,$searchType);
	}
	function returnTo($tableField,$uiObject)
	{

		$this->returnColumns[count($this->returnColumns)]=array($tableField,-1,$uiObject);
	}
	function hindi($v)
	{
		$this->hindiSupport=$v;
	}
	function rander()
	{
		global $JS,$CSS;
		if(!isset($_REQUEST['str']))
		{
			if(isset($_REQUEST['mode']))
			{
				if($_REQUEST['mode']=="popup")
				{
					echo '
					<html>
					<head>';
					
					$JS->loadCommonFile();
					$CSS->loadCommonFile();
					echo '
					</head>
					<body>
					';
				}
			}
			echo '<script type="text/javascript">
			
			
			function '.$this->id.'_onKeyUp(obj,evt)
			{
				'.$this->id.'.onKeyUp(obj,evt);
			}
			function '.$this->id.'onMouseClickRow(obj,id)
			{
				
				'.$this->id.'.onMouseClickRow(obj,id);
				
			}
			function '.$this->id.'onDblClickRow(obj)
			{
				'.$this->id.'.updateReturnValue();
			}
			function '.$this->id.'closeWindow()
			{
				'.$this->id.'.closeWindow();
			}
			</script>';
			$txt = new TextBox($this->id."_input");
			$txt->showLable(false);
			if($this->hindiSupport)
			{
				$txt->hindi($this->hindiSupport);
			}
			$txt->addJsEvent("onKeyUp",$this->id."_onKeyUp");
			$txt->rander();
			
			echo '<div class="listofvalueDiv" style="height:'.$this->height.'px" id="'.$this->id.'_content" class="listofvaluedetail"></div>';
			$b = new Button($this->id."_SEARCH");
			$b->setValue("Search");
			$b->addJsEvent("onClick",$this->id."_onKeyUp");
			$b->rander();
			$b = new Button($this->id."_OK");
			$b->setValue("OK");
			$b->addJsEvent("onClick",$this->id."onDblClickRow");
			$b->rander();
			$b = new Button($this->id."_Close");
			$b->setValue("Close");
			$b->addJsEvent("onClick",$this->id."closeWindow");
			$b->rander();
			echo '<script type="text/javascript">
			
			'.$this->id.' = new ListOfValue("'.$this->id.'");
			
			'.$this->id.'.setUrl("'.$this->url.'");
			'.$this->id.'.setMaxRow("'.$this->maxRecord.'");
			'.$this->id.'.setRagulerKey('.$this->regulerKeys.');
			'.$this->id.'.exitFocusElement("'.$this->exitFocus.'");
			';
				for($i=0;$i<count($this->returnColumns);$i++)
				{
					echo $this->id.'.returnTo("'.$this->returnColumns[$i][0].'","'.$this->returnColumns[$i][2].'");';
				}
				
				if($this->onCloseCallback!=null)
				{
					echo '
						'.$this->id.'.onCloseCallback="'.$this->onCloseCallback.'";
					';
				}
				
				echo '
			
			
			
			setTimeout("'.$this->id.'_input.focus();'.$this->id.'.updateRowColor(\'new\');",500);
			'.$this->id.'.updateData();
			</script>';
			if(isset($_REQUEST['mode']))
			{
				if($_REQUEST['mode']=="popup")
				{
					echo '<script type="text/javascript">
					'.$this->id.'.setCallingMode("'.$_REQUEST['mode'].'");
					</script>
					<body></html>';
				}
			}
		}
		else
		{

			$str = $_REQUEST['str'];
			$col = "";
			for($i=0;$i<count($this->bindedColumns);$i++)
			{
				$temp = "";
				foreach($this->encriptedColumns as $k => $v)
				{
					if($k==strtoupper($this->bindedColumns[$i][0]))
					{
						$enc = new Encryption();
						$enc->setFormat($v);
						$temp = $enc->crypt($str);
					}
				}
				if($temp=="")
				{
					$temp = $str;
				}
				
				if($this->bindedColumns[$i][2]=="start")
				{
					$col .= " upper(".$this->bindedColumns[$i][0].") like upper('".$temp."%') ";
				}
				else if($this->bindedColumns[$i][2]=="like")
				{
					$col .= " upper(".$this->bindedColumns[$i][0].") like upper('%".$temp."%') ";
				}
				else if($this->bindedColumns[$i][2]=="end")
				{
					$col .= " upper(".$this->bindedColumns[$i][0].") like upper('%".$temp."') ";
				}
				if($i<count($this->bindedColumns)-1)
				{
					$col .= " or ";
				}
			}
			if($this->db->getDriver()!="cloudDB")
			{
				if($this->db->getDriver()=="oracle")
				{
					$q = "select * from ($this->query) ab where rownum <= $this->maxRecord and ( $col )";
				}
				else
				{
					$q = "select * from ($this->query) ab where $col limit 0,$this->maxRecord";
				}
			}
			else
			{
				if($this->db->database->parm['database']=="oracle")
				{
					$q = "select * from ($this->query) ab where rownum <= $this->maxRecord and ( $col )";
				}
				else
				{
					$q = "select * from ($this->query) ab where $col limit 0,$this->maxRecord";
				}
			}
			
			/*
			$table = new Table($this->id."_contentTable");
			
			$table->setQuery($q,$this->db);
			$table->maxRowPerPage($this->maxRecord);
			$table->rander();
			*/
			
			$r = $this->db->execute($q);
			foreach($this->encriptedColumns as $k => $v)
			{
				$r->setColumnEncription($k,$v);
			}
			$r->decrypt();
			$r->createJsObject($this->id."_data");
			echo '<table id="'.$this->id.'_contentTable" class ="listofvalueTable">';
			
			for($i=0;$i<$r->count;$i++)
			{
				echo '<tr class="defaultRow" onClick="'.$this->id.'onMouseClickRow(this,'.$i.')" onDblClick="'.$this->id.'onDblClickRow(this)">';
				foreach($r->data[$i] as $k => $v)
				{
					if(array_search($k,$this->hiddenColumns)===false)
					{
						echo '<td>'.$r->data[$i][$k].'</td>';
					}
				}
				echo '</tr>';
			}
			echo '</table>
			<script type="text/javascript">
			'.$this->id.'.setData('.$this->id.'_data);
			</script>';
		}
	}
}
?>
