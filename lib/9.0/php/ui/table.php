<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
require_once("$LIB_PATH/php/ui/tablecolumn.php");
class Table extends UIObject
{
	var $events;
	var $recordset;
	var $recordsetOverwrite;
	var $columnMap;
	var $columns;
	var $showHeader;
	var $alternetClass;
	var $headerClass;
	var $width;
	var $orderControl;
	var $db;
	var $itemPerPage;
	var $page;
	var $query;
	var $pageDisplay;
	var $searchStr;
	var $searchOn;
	var $rowQuery;
	var $tableUrl;
	var $eachFunction;
	var $displayModifier;
	var $pageCallback;
	var $summeryColumns;
	var $showSummery;
	var $showRownum;
	var $showLable;
	var $conditionalCss;
	var $source;
	var $recordsetFilterStatus;
	var $priviusColumnReset;
	var $summeryRows;
	var $editLink;
	var $removeLink;
	var $customColum;
	function Table($id)
	{
		parent::UIObject();
		$this->source="recordset";
		$this->recordsetFilterStatus=false;
		$this->id=$id;
		$this->type="";
		$this->tag="table";
		$this->events = array();
		$this->columnMap=array();
		$this->columns=array();
		$this->priviusColumnReset=true;
		$this->showHeader = true;
		$this->alternetClass = array();
		$this->headerClass = "";
		$this->width="100%";
		$this->orderControl=true;
		$this->recordset = new Recordset();
		$this->recordsetOverwrite=false;
		$this->db = null;
		$this->itemPerPage=0;
		$this->page=1;
		$this->eachFunction="";
		$this->displayModifier=array();
		$this->pageCallback="";
		$this->summeryColumns = array();
		$this->showDistinctGroup = false;
		$this->showLable = false;
		$this->conditionalCss = array();
		
		if(isset($_GET[$this->id."_page"]))
		{
			$this->page=$_GET[$this->id."_page"];
		}
		else if(isset($_POST[$this->id."_page"]))
		{
			$this->page=$_POST[$this->id."_page"];
		}
		$this->showSummery = true;
		$this->query="";
		$this->pageDisplay=10;
		$this->searchStr = "";
		$this->searchOn=array();
		$this->rowQuery="";
		$this->tableUrl="";
		$this->showRownum=true;
		$this->editLink="";
		$this->removeLink="";
	}
	function addCustomColumn($name,$value,$columnClass,$key,$keyValue)
	{
		$temp = array();
		$temp["name"]=$name;
		$temp["columnClass"]=$columnClass;
		$temp["value"]=$value;
		$temp["key"]=$key;
		$temp["keyValue"]=$keyValue;
		$this->customColum[count($this->customColum)]=$temp;
	}
	
	function setPriviusColumnReset($v)
	{
		$this->priviusColumnReset=$v;
	}

	function showLable($v)
	{
		$this->showLable = $v;
	}
	function showDistinctGroup($v) // parameter : true/false
	{
		$this->showDistinctGroup = $v;
	}
	function showRownum($v)
	{
		$this->showRownum = $v;
	}
	function addSummeryColumn($column,$logic,$format=null)
	{
		$column = strtoupper($column);
		$logic = strtoupper($logic);
		$t= array();
		$t['column']=$column;
		$t['logic']=$logic;
		$t['value']="";
		$t['format']=$format;
		$this->summeryColumns[count($this->summeryColumns)] = $t;
	}
	function makeSummery()
	{
		for($i=0;$i<count($this->summeryColumns);$i++)
		{
			$col = $this->summeryColumns[$i]['column'];
			$logic = $this->summeryColumns[$i]['logic'];
			
			if($logic=="MAX")
			{
				$val = -99999999999999999999;
			}
			else if($logic=="MIN")
			{
				$val = 99999999999999999999;
			}
			else
			{
				$val = 0;
			}
			$count = 0;
			
			for($j=0;$j<$this->recordset->count;$j++)
			{
				if($logic=="SUM" || $logic == "AVRAGE")
				{
					
					if($this->recordset->data[$j][$col]!="")
					{
						$val = $val + $this->recordset->data[$j][$col];
						$count++;
						
					}
				}
				else if($logic == "COUNT")
				{
					if($this->recordset->data[$j][$col]!="")
					{
						$val = $val + $this->recordset->data[$j][$col];
						$count++;
					}
				}
				else if($logic == "MIN")
				{
					if($val > $this->recordset->data[$j][$col])
					{
						$val = $this->recordset->data[$j][$col];
					}
				}
				else if($logic == "MAX")
				{
					if($val < $this->recordset->data[$j][$col])
					{
						$val = $this->recordset->data[$j][$col];
					}					
				}
			}
			if($logic=="MAX")
			{
				$this->summeryColumns[$i]['value'] = $val;
			}
			else if($logic=="MIN")
			{
				$this->summeryColumns[$i]['value'] = $val;
			}
			else if($logic=="AVRAGE")
			{
				$this->summeryColumns[$i]['value'] = $val/$count;
			}
			else if($logic=="SUM")
			{
				$this->summeryColumns[$i]['value'] = $val;
			}
			else if($logic=="COUNT")
			{
				$this->summeryColumns[$i]['value'] = $count;
			}
			if($this->summeryColumns[$i]['format']!=null)
			{
				$f = $this->summeryColumns[$i]['format'];
				$this->summeryColumns[$i]['value'] = $f->getFormatedValue($this->summeryColumns[$i]['value']);
			}
		}
		$this->showSummery = true;
	}
	function showSummery($v)
	{
		$this->showSummery = $v;
	}
	function getSum($col)
	{
		return $this->recordset->getSum($col);
	}
	function getAverage($col)
	{
		return $this->recordset->getAverage($col);
	}
	function tableUrl($u)
	{
		$this->tableUrl=$u;
	}
	function setPage($p)
	{
		$this->page = $p;
	}
	function maxRowPerPage($p)
	{
		$this->itemPerPage=$p;
	}
	function setQuery($q,$db)
	{
		$this->source="query";
		$this->db = $db;
		$this->query = $q;
		$this->rowQuery = $q;
		$this->setSearchString();
		$this->_initStructure();
	}
	function addSearchField($on,$type)
	{

		$this->searchOn[count($this->searchOn)] = array("field"=>$on,"type"=>$type);
		$this->setSearchString();
	}
	function dbJsSearchForm($u)
	{
		global $UI;
		if(isset($_POST[$this->id.'_str']))
		{
			$v=$_POST[$this->id.'_str'];
		}
		else if(isset($_GET[$this->id."_str"]))
		{
			$v=$_GET[$this->id."_str"];
		}
		else
		{
			$v="";
		}

		$className = $UI->getCss('input');
		echo '<input class="'.$className.'" name="'.$this->id.'_str" type="text" id="'.$this->id.'_str" value="'.$v.'" /><input type="button" onclick="'.$this->id.'_onClickSearchButton()" value="Go" />
		<script type="text/javascript">
			function '.$this->id.'_onClickSearchButton()
			{
				ajax("'.$u.'","'.$this->id.'_page=1&'.$this->id.'_str="+document.getElementById("'.$this->id.'_str").val(),
				function(resp)
				{

					';
					if($this->pageCallback!="")
					{
					echo $this->pageCallback.'(resp);';
					}
					echo'
				});
			}
		</script>';
	}
	function dbSearchForm($u)
	{
		global $UI;
		if(isset($_POST[$this->id.'_str']))
		{
			$v=$_POST[$this->id.'_str'];
		}
		else if(isset($_GET[$this->id."_str"]))
		{
			$v=$_GET[$this->id."_str"];
		}
		else
		{
			$v="";
		}

		$className = $UI->getCss("input");
		$className1 = $UI->getCss("button");
		
		echo '<div id="searchFormDiv'.$this->id.'" ><form id="'.$this->id.'_searchForm_0X1" name="'.$this->id.'_searchForm_0X1" method="get" action="'.$u.'"><input type="hidden" id="page" name="page" value="1" /><input class="'.$className.'"  name="'.$this->id.'_str" type="text" id="'.$this->id.'_str" value="'.$v.'" /><input class="'.$className1.'" type="submit" value="Go" />
</form></div>';
	}
	function setSearchString($msg="")
	{

		$q=$this->rowQuery;
		if(isset($_POST[$this->id.'_str']))
		{
			$v1=$_POST[$this->id.'_str'];
		}
		else if(isset($_GET[$this->id."_str"]))
		{
			$v1=$_GET[$this->id."_str"];
		}
		else
		{
			$v1="";
		}	
		
		$v = $v1;
		$inStr = $v;
		
		if(count($this->searchOn)>0)
		{
			$str = '';

			for($i=0;$i<count($this->searchOn);$i++)
			{
				$v=$inStr;
				if($v1!="")
				{
					if($this->searchOn[$i]['type']=="equle")
					{
					
						$v=" = trim(lower('$v')) ";
					}
					else if($this->searchOn[$i]['type']=="equal")
					{
						$v=" = trim(lower('$v')) ";
					}
					else if($this->searchOn[$i]['type']=="startwith")
					{
						$v=" like trim(lower('$v%')) ";
					}
					else if($this->searchOn[$i]['type']=="endwith")
					{
						$v=" like trim(lower('%$v')) ";
					}
					else
					{
						$v=" like trim(lower('%$v%')) ";
					}
					$tf = $this->searchOn[$i]['field'];
					$str .= " trim(lower($tf)) $v or";
				}
				
			}
			$str = rtrim($str,"or");
			if($str!="")
			{
				$str = "where $str";
			}
			$q="select * from ($q) a $str";
		}	
		
		$this->query=$q;

	}
	
	function pageCallback($c)
	{
		$this->pageCallback = $c;
	}
	function showJsPager($u)
	{
		if($this->itemPerPage>0)
		{
			$this->setSearchString("As");
			if($this->source=="query")
			{
				$q=$this->query;
				$q="select count(*) as total from ($q) a";
				$r = $this->db->execute($q);
				$total = $r->data[0]['TOTAL'];
			}
			else if($this->source=="recordset")
			{
				$this->recordsetFilter();
				$total = $this->recordset->count;
			}
			$tP = ((int)($total/$this->itemPerPage))+1;
			
			$f=1;
			$px = (int)($this->pageDisplay/2);
			if($this->page>$px)
			{
				$f = $this->page-$px;
			}
			$t = $f+$this->pageDisplay;
			if($t>$tP)
			{
				$t=$tP;
			}
			if(isset($_POST[$this->id.'_str']))
			{
				$v=$_POST[$this->id.'_str'];
			}
			else if(isset($_GET[$this->id."_str"]))
			{
				$v=$_GET[$this->id."_str"];
			}
			else
			{
				$v="";
			}
			echo "<ul>";
			echo "<li><a href='javascript:' onclick='".$this->id."_onClickTablePager(".'"1","'.$v.'","'.$this->id.'"'.")'>First</a></li>";
			for($i=$f;$i<=$t;$i++)
			{
				$cl='';
				if($i==$this->page)
				{
					$cl = 'selectedPage';
				}
				echo "<li class='$cl'><a href='javascript:' onclick='".$this->id."_onClickTablePager(".'"'.$i.'","'.$v.'","'.$this->id.'"'.")'>$i</a></li>";
			}
			echo "<li><a href='javascript:' onclick='".$this->id."_onClickTablePager(".'"'.$tP.'","'.$v.'","'.$this->id.'"'.")'>Last</a></li>";
			echo "</ul>";
			
			echo '<script type="text/javascript">
			function '.$this->id.'_onClickTablePager(i,v,id)
			{
				
				ajax("'.$u.'","'.$this->id.'_page="+i+"&'.$this->id.'_str="+v,
				function(resp)
				{
					';
					if($this->pageCallback!="")
					{
						echo $this->pageCallback.'(resp);';
					}
					echo'
				});
			}
			</script>';
		}
	}
	function showPager($u)
	{
		
		if($this->itemPerPage>0)
		{
			if($this->source=="query")
			{
				$q=$this->query;
				$q="select count(*) as total from ($q) a";
				
				$r = $this->db->execute($q);
				$total = $r->data[0]['TOTAL'];
			}
			else if($this->source=="recordset")
			{
				$this->recordsetFilter();
				$total = $this->recordset->count;
			}
			
			/*
			$q=$this->query;
			$q="select count(*) as total from ($q) a";
			
			$r = $this->db->execute($q);
			$total = $r->data[0]['TOTAL'];
			*/
			
			$tP = ((int)($total/$this->itemPerPage))+1;
			
			$f=1;
			$px = (int)($this->pageDisplay/2);
			if($this->page>$px)
			{
				$f = $this->page-$px;
			}
			$t = $f+$this->pageDisplay;
			if($t>$tP)
			{
				$t=$tP;
			}
			if(isset($_POST[$this->id.'_str']))
			{
				$v=$_POST[$this->id.'_str'];
			}
			else if(isset($_GET[$this->id."_str"]))
			{
				$v=$_GET[$this->id."_str"];
			}
			else
			{
				$v="";
			}
			
			if(strpos($u,"?")===false)
			{
				$u=$u."?";
				
			}
			else
			{
				$u=$u."&";
			}
		
			$nu = $u.$this->id."_page=1&".$this->id."_str=".$v;
			echo "<ul>";
			echo "<li><a href='$nu'>First</a></li>";
			for($i=$f;$i<=$t;$i++)
			{
				$cl='';
				if($i==$this->page)
				{
					$cl = 'selectedPage';
				}
				echo "<li class='$cl'><a href='$u$this->id"."_page=$i&$this->id"."_str=$v'>$i</a></li>";
			}
			echo "<li><a href='$u$this->id"."_page=$tP&$this->id"."_str=$v'>Last</a></li>";
			echo "</ul>";
		}
	}
	private function _initStructure()
	{	
		$q=$this->rowQuery;

		if($this->db->getDriver()!="cloudDB")
		{
			if($this->db->getDriver()=="oracle")
			{
				$q="select * from ($q) a where rownum =1";
			}
			else
			{
				$q="select * from ($q) a limit 1,1";
			}
		}
		else
		{
			if($this->db->database->parm['database']=="oracle")
			{
				$q="select * from ($q) a where rownum =1";
			}
			else
			{
				$q="select * from ($q) a limit 1,1";
			}
		}
		$this->recordset = $this->db->execute($q);
		
		$this->recordsetOverwrite=true;
		$this->initRecordset();
	}
	function initRecordset()
	{
		for($i=0;$i<$this->recordset->countColumns;$i++)
		{
			$this->addColumns($this->recordset->columns[$i]->getName());
			 
		}
		for($i=0;$i<$this->recordset->countColumns;$i++)
		{
			$this->bindWith($this->recordset->columns[$i]->getName(),$this->recordset->columns[$i]->getName());
		}
		
	}
	private function _execute()
	{
		
		if(isset($_POST[$this->id.'_str']))
		{
			$v=$_POST[$this->id.'_str'];
		}
		else if(isset($_GET[$this->id."_str"]))
		{
			$v=$_GET[$this->id."_str"];
		}
		else
		{
			$v="";
		}
		$this->setSearchString();
		
		$q=$this->query;

		if($this->itemPerPage>0)
		{
			$to  = ($this->page * $this->itemPerPage);
			$from = $to-$this->itemPerPage;
			if($this->db->getDriver()=="oracle")
			{
				$q="select * from (select rownum as sr,a.* from ($q) a) b where b.sr between $from and $to";
			}
			else
			{
				$nrows = $to-$from;
				$q="select * from (select * from ($q) a) b limit $from,$nrows";
			}
		}
		//echo $q;
		$tempCol = $this->recordset->columns;
		$this->recordset = $this->db->execute($q);
		$this->recordset->columns = $tempCol;
	}
	function manupulateRecordset()
	{				
		$this->recordset->distinctFilterStatus=false;
		$this->recordset->decrypt();
		$this->recordset->setPriviusColumnReset($this->priviusColumnReset);
		$this->recordset->distinctSelection();

		$this->recordsetOverwrite=true;
		if(count($this->summeryColumns)>0)
		{
			$this->makeSummery();
		}
	}
	function searchForm()
	{
		global $UI;
		$className = $UI->getCss('input');
		echo '<input type="text" value="" class="'.$className.'" onkeyup="'.$this->id.'.search(this.value)" />';
	}
	function orderControl($val)
	{
		$this->orderControl = $val;
	}
	function width($w)
	{
		$this->width=$w;
	}
	function headerClass($val)
	{
		$this->headerClass = $val;
	}
	function showHeader($val)
	{
		$this->showHeader=$val;
	}
	function alternetClass()
	{
		$this->alternetClass = func_get_args();
	}
	function bindWith($rname,$tname,$tproperty='val',$mode="")
	{
		if($mode=="" && strtoupper($tproperty)=="ID")
		{
			echo "ID attribute is not allowd to bind.";
			return false;
		}
		$tname = strtoupper($tname);
		$temp=array();
		$tname= str_replace(" ","_",$tname);
		$temp['name']=strtoupper($rname);
		$temp['property']=$tproperty;
		$this->columnMap[$tname]=$temp;
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$tname)
			{
				$this->columns[$i]->setPropertyMap($tproperty,$rname);
			}
		}
		return true;
	}
	function removeBinding()
	{
		for($i=0;$i<count($this->columns);$i++)
		{
			$this->columns[$i]->removePropertyMap();
		}
	}
	function setColumnEncription($name,$value)
	{
		$this->recordset->setColumnEncription($name,$value);
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->setEncryption($value);
			}
		}
		
	}
	function setRecordset(Recordset $rec)
	{
		
		$this->removeBinding();
		$this->recordset = $rec;
		$this->recordsetOverwrite=true;
		if(count($this->columns)==0)
		{
			for($i=0;$i<count($this->recordset->columns);$i++)
			{
				$name = $this->recordset->columns[$i]->getName();
				$this->addColumns($name);
				$this->bindWith($name,$name);
			}
		}
		$this->recordset->distinctFilterStatus=false;
		$this->initRecordset();
	}
	function fromFile($filename)
	{
		$r = new Recordset();
		if($r->fromFile($filename))
		{
			$this->setRecordset($r);
		}
	}
	function addColumns()
	{
		$numargs = func_num_args();
		$arg_list = func_get_args();
		
		for($i=0;$i<$numargs;$i++)
		{
			$col_name  = str_replace(" ","_",$arg_list[$i]);
			$st = false;
			for($j=0;$j<count($this->columns);$j++)
			{
				if($this->columns[$j]->getName()==strtoupper($col_name))
				{
					$st=true;
				}
			}
			
			if(!$st)
			{
				$this->columns[count($this->columns)]=new TableColumn($col_name,new Span($col_name)); 
				if($this->recordsetOverwrite==false)
				{
					
					$this->recordset->addColumns($col_name);
					$this->bindWith($col_name,$col_name);
				}
			}
		}
		
	}
	function addConditionalCss($column,$condition,$value,$css,$applicable="row",$target="",$source="VALUE")
	{
		$source = strtoupper($source);
		$name = strtoupper($column);
		$this->conditionalCss[count($this->conditionalCss)] = array("column"=>$name,"cond"=>$condition,"value"=>$value,"css"=>$css,"cell"=>$applicable,"target"=>$target,"type"=>"css","source"=>$source);
	}
	function addConditionalJSEvent($column,$condition,$value,$evnet,$applicable="row",$target="",$source="VALUE")
	{
		$source = strtoupper($source);
		$name = strtoupper($column);
		$this->conditionalCss[count($this->conditionalCss)] = array("column"=>$name,"cond"=>$condition,"value"=>$value,"css"=>$evnet,"cell"=>$applicable,"target"=>$target,"type"=>"event","source"=>$source);
	}	
	function addConditionalAttrib($column,$condition,$value,$evnet,$applicable="row",$target="",$source="VALUE")
	{
		$source = strtoupper($source);
		$name = strtoupper($column);
		$this->conditionalCss[count($this->conditionalCss)] = array("column"=>$name,"cond"=>$condition,"value"=>$value,"css"=>$evnet,"cell"=>$applicable,"target"=>$target,"type"=>"attrib","source"=>$source);
	}
	function addColumnCssClass($name,$className)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->addCssClass($className);
			}
		}
	}
	function addColumnCss($name,$key,$val)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->addCss($key,$val);
			}
		}
	}
	function setColumnValueFormat($name,$format)
	{
		$name = strtoupper($name);
		
		for($i=0;$i<count($this->columns);$i++)
		{	
			if($this->columns[$i]->getName()==$name)
			{
				
				$this->columns[$i]->setValueFormat($format);
			}
		}
	}
	function setColumnDistinctSelection($name,$val)
	{
		$this->recordset->setColumnDistinctSelection($name,$val);	
		$this->orderControl=false;
	}	
	function distinctSelection()
	{
		
		$this->recordset->distinctSelection();
		
	}
	function setColumnClass($name,$class)
	{
		$name = strtoupper($name);
		
		for($i=0;$i<count($this->columns);$i++)
		{	
			if($this->columns[$i]->getName()==$name)
			{
				
				$this->columns[$i]->setClass($class);
			}
		}
	}
	function setColumnTitle($name,$title)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->setHeaderTitle($title);
			}
		}
	}
	function setColumnVisibility($name,$v)
	{
		$name = strtoupper($name);
		for($i=0;$i<count($this->columns);$i++)
		{
			if($this->columns[$i]->getName()==$name)
			{
				$this->columns[$i]->setVisibility($v);
			}
		}
	}
	function createJsObject()
	{
		$js='';
		$obj = $this->id;
		$js.="var $obj = new Table('".$this->id."');
		$obj".".setRowCount(".$this->recordset->count().");";
		if($this->showRownum)
		{
			$js.="$obj".".addColumn('SR');\n";
		}
		for($i=0;$i<count($this->columns);$i++)
		{
			$js.="$obj".".addColumn('".$this->columns[$i]->getName()."');\n";
		}
		if($this->showHeader==true)
		{
			$js.="$obj.showHeader=true;";
		}
		if($this->showSummery == true)
		{
			$js.="$obj.showSummery=true;";
			$js.="$obj.summeryStatus=true;";
		}
		if($this->showRownum==true)
		{
			$js.="$obj.showRownum=true;";
		}
		if(count($this->alternetClass)>0)
		{
			$str = "";
			for($i=0;$i<count($this->alternetClass);$i++)
			{
				$str.=$this->alternetClass[$i].",";
			}
			$str = rtrim($str,",");
			$js.="$obj.setAlternetClass('$str');";
		}
		$js.="$obj.makeIndex();";
		return $js;
	}
	function rander($echo=true)
	{
		
		if($this->query!="" && $this->source=="query")
		{
			$this->_execute();
		}
		
		if($this->customColum!=null)
		{
			for($i=0;$i<count($this->customColum);$i++)
			{
				$customColum = $this->customColum[$i];
				
				$columnClass = $customColum["columnClass"];
				$name = $customColum["name"];
				$value = $customColum["value"];
				$key=$customColum["key"];
				$keyValue=$customColum["keyValue"];
				$this->columns[count($this->columns)]=new TableColumn($name,$columnClass);
				$this->recordset->addColumns($name);
				$this->recordset->setColumnValue($name,"Remove");
				$this->bindWith($name,$name);
				$this->bindWith($key,$name,$keyValue);
			}
		}
		/*
		if($this->editLink!="")
		{
			$l = new Link("edit_link");
			$l->setUrl("javascript:");
			$l->addJsEvent("onClick",$this->editLink);
			$this->columns[count($this->columns)]=new TableColumn("edit_link",$l); 
			$this->recordset->addColumns("edit_link");
			$this->recordset->setColumnValue("edit_link","Edit");
			$this->bindWith("edit_link","edit_link");
		}
		if($this->removeLink!=null)
		{
			$callback = $this->removeLink["callback"];
			$newColumn = $this->removeLink["column"];
			$l = new Link("remove_link");
			$l->setUrl("javascript:");
			$l->addJsEvent("onClick",$callback);
			$this->columns[count($this->columns)]=$newColumn;//new 
			
			$this->recordset->addColumns($newColumn->getName());
			$this->recordset->setColumnValue($newColumn->getName(),"Remove");
			$this->setColumnClass($newColumn->getName(),$l);
			$this->bindWith("remove_link","remove_link");
		}
		*/
		$this->manupulateRecordset();
		
		if($this->source=="recordset")
		{
			$this->recordsetFilter();
		}
		
		parent::rander($echo);
		echo '
		<script type="text/javascript">
		';
		if(count($this->conditionalCss)>0)
		{
			for($i=0;$i<count($this->conditionalCss);$i++)
			{
				if($this->conditionalCss[$i]['type']=="css")
				{
					echo $this->id.".addRowCssCondition('".$this->conditionalCss[$i]['column']."','".$this->conditionalCss[$i]['cond']."','".$this->conditionalCss[$i]['value']."','".$this->conditionalCss[$i]['css']."','".$this->conditionalCss[$i]['cell']."','".$this->conditionalCss[$i]['target']."','".$this->conditionalCss[$i]['source']."');";
				}
				else if($this->conditionalCss[$i]['type']=="event")
				{
					echo $this->id.".addRowEventCondition('".$this->conditionalCss[$i]['column']."','".$this->conditionalCss[$i]['cond']."','".$this->conditionalCss[$i]['value']."','".$this->conditionalCss[$i]['css']."','".$this->conditionalCss[$i]['cell']."','".$this->conditionalCss[$i]['target']."','".$this->conditionalCss[$i]['source']."');";
				}
				else if($this->conditionalCss[$i]['type']=="attrib")
				{
					echo $this->id.".addRowAttribCondition('".$this->conditionalCss[$i]['column']."','".$this->conditionalCss[$i]['cond']."','".$this->conditionalCss[$i]['value']."','".$this->conditionalCss[$i]['css']."','".$this->conditionalCss[$i]['cell']."','".$this->conditionalCss[$i]['target']."','".$this->conditionalCss[$i]['source']."');";
				}
			}
			echo '
			'.$this->id.'.resetAlternetClass();
			';
			
			
		}
		
		for($i=0;$i<count($this->columns);$i++)
			{
				
				if($this->columns[$i]->getEncryption()!="ASCI")
				{
					echo $this->id.'.setColumnEncryption("'.$this->columns[$i]->getName().'","'.$this->columns[$i]->getEncryption().'");'."\n";
				}
			}
		echo'
		</script>
		';
	}
	function each($fun)
	{
		$this->eachFunction = $fun;
	}
	function setColumnDisplayModifier($column,$modifier)
	{
		$temp = array();
		$temp['column'] = strtoupper($column);
		$temp['modifier'] = $modifier;
		$this->displayModifier[count($this->displayModifier)] = $temp;
	}
	private function findColumnModifierIndex($column)
	{
		for($i=0;$i<count($this->displayModifier);$i++)
		{
			if($this->displayModifier[$i]['column']==$column)
			{
				return $i;
			}
		}
		return null;
	}
	private function findColumnModifier($column)
	{
		for($i=0;$i<count($this->displayModifier);$i++)
		{
			if(strtoupper($this->displayModifier[$i]['column'])==strtoupper($column))
			{
				return ($this->displayModifier[$i]);
			}
		}
		return null;
	}	
	function recordsetFilter()
	{
		
		if(!$this->recordsetFilterStatus && count($this->searchOn)>0)
		{
			if(isset($_POST[$this->id.'_str']))
			{
				$str=$_POST[$this->id.'_str'];
			}
			else if(isset($_GET[$this->id."_str"]))
			{
				$str=$_GET[$this->id."_str"];
			}
			else
			{
				$str="";
			}
			if($str=="")
			{
				$this->recordsetFilterStatus=true;
				return ;
			}
			$r = $this->recordset->createDuplicate();
			for($i=0;$i<count($this->searchOn);$i++)
			{
				if($this->searchOn[$i]['type']=="equle")
				{
					$temp = $this->recordset->extract($this->searchOn[$i]['field'],$str,"==");
					
				}
				else if($this->searchOn[$i]['type']=="equal")
				{
					$temp = $this->recordset->extract($this->searchOn[$i]['field'],$str,"==");
					
				}
				else if($this->searchOn[$i]['type']=="startwith")
				{
					$temp = $this->recordset->extract($this->searchOn[$i]['field'],$str,"start with");
					
				}
				else if($this->searchOn[$i]['type']=="endwith")
				{
					$temp = $this->recordset->extract($this->searchOn[$i]['field'],$str,"end with");
					
				}
				else
				{
					$temp = $this->recordset->extract($this->searchOn[$i]['field'],$str,"content");
					
				}
				
				$r->append($temp);
				
			}
			$this->recordset = $r;
			
		}
		else
		{
			
		}
		
		$this->recordsetFilterStatus=true;
	}
	
	function createInnerHTML()
	{
		$this->innerHTML='';
		$this->property .= ' width="'.$this->width.'" ';
		if($this->eachFunction!="")
		{
			$funStr = $this->eachFunction;
			eval('global $'.$funStr.';');
		}

		if($this->showHeader==true)
		{
			$headerClass ="";
			if($this->headerClass!="")
			{
				$headerClass = ' class="'.$this->headerClass.'" ';
			}
			$this->innerHTML .= '<tr '.$headerClass.'>';
			if($this->showRownum)
			{
				if($this->orderControl==true)
				{
					$this->innerHTML .= '<th  ><a href="javascript:" onClick="'.$this->id.'.sortToggle('."'SR'".')" >SR</a></th>';
				}
				else
				{
					$this->innerHTML .= '<th ><a href="javascript:" >SR</a></th>';
				}
			}
			
			for($i=0;$i<count($this->columns);$i++)
			{
					$col = $this->columns[$i];
					$orderControl = "";
					
					
					$cv = "";
					if(!$col->isVisible())
					{
						$cv="display:none;";
					}
					if($this->orderControl==true)
					{
						$orderControl = ' onClick="'.$this->id.'.sortToggle('."'".$col->getName()."'".')" ';
						$this->innerHTML .= '<th class="'.$this->columns[$i]->getCssClass().'" style="'.$this->columns[$i]->getStyleString().';'.$cv.'"><a href="javascript:" '.$orderControl.'>'.strtoupper($col->getHeaderTitle()).'</a></th>';
					}
					else
					{
						$this->innerHTML .= '<th class="'.$this->columns[$i]->getCssClass().'" style="'.$this->columns[$i]->getStyleString().';'.$cv.'">'.strtoupper($col->getHeaderTitle()).'</th>';
					}
					
			}
			$this->innerHTML .= '</tr>';
		}
		$to  = ($this->page * $this->itemPerPage);
		$from = $to-$this->itemPerPage;
		
		if(!$this->recordset->isPopulated())
		{
			$c=0;
			$rowCount = 0;
			$alc = count($this->alternetClass);
			
			while($row = $this->recordset->getDBRow())
			{
				if($alc>0)
				{
					$this->innerHTML .= '<tr class="'.$this->alternetClass[($rowCount+1)%$alc].'">';
				}
				else
				{
					$this->innerHTML .= '<tr>';
				}
				if($this->showRownum)
				{
					$stObj = new Span($this->id.'_SR_'.($c+1));
					$stObj->setValue($c+1+$from);
					$stObj->setEcho(false);
					$stObj->showLable(false);
					$this->innerHTML .= '<td >'.$stObj->rander().'</td>';
					//$this->innerHTML .= '<td ><span id="'.$this->id.'_SR_'.($c+1).'">'.($c+1+$from).'</span></td>';
				}
				if($this->eachFunction!="")
				{
					$funStr = $this->eachFunction;
					$$funStr($row);
				}
				
				for($i=0;$i<count($this->columns);$i++)
				{
					
					
					//$obj =  $this->columns[$i]->getClass();
					
					$obj = clone $this->columns[$i]->getClass();
					$obj->setId($this->id."_".$this->columns[$i]->getName()."_".($c));
					$obj->setName($this->id."_".$this->columns[$i]->getName());					
					$obj->setEcho(false);	
					$obj->showLable($this->showLable);		
					
					for($x=0;$x<count($this->columns[$i]->propertyMap);$x++)
					{
						
						$colVal = $row[$this->columns[$i]->propertyMap[$x]['column']];
						
						$obj->setData($this->columns[$i]->propertyMap[$x]['property'],$colVal);
						if($this->columns[$i]->propertyMap[$x]['property']=="val")
						{
							$obj->setValue($colVal);	
						}
						else if($this->columns[$i]->propertyMap[$x]['property']=="href")
						{
							$obj->setUrl($colVal);	
						}
						else if($this->columns[$j]->propertyMap[$x]['property']=="name")
						{
							$obj->setName($colVal);	
						}
						else
						{
							$obj->property .= ' '.$this->columns[$i]->propertyMap[$x]['property'].'="'.$colVal.'" ';
							
						}
						
					}
					
					
					/*
					$inteParm = clone $obj->integrationParameters;
					$obj->integrationParameters = array();
					foreach($inteParm as $k => $v)
					{
						//$obj->setIntegrationParameter($k,$row[strtoupper($v)]);
					}
					*/
					$cv = "";
					if(!$this->columns[$i]->isVisible())
					{
						$cv="display:none;";
					}
					$this->innerHTML .= "<td style=\"".$this->columns[$i]->getStyleString().";$cv\" class=\"".$this->columns[$i]->getCssClass()."\" >". $obj->rander(false) ."</td>\n";
					
					
				}
				$c++;
				$rowCount++;
				$this->innerHTML .= '</tr>'."\n";
			}
		}
		else
		{
			$alc = count($this->alternetClass);
			if($this->recordset->count()>0)
			{
				$from  = 0;
				$fromOffset=0;
				$to = $this->recordset->count;
				if($this->itemPerPage>0)
				{
					$toOffset = $this->page*$this->itemPerPage;
					$fromOffset = $toOffset -$this->itemPerPage;
					
				}
				if($this->source=="recordset" && $this->itemPerPage>0)
				{
					
					$to = $this->page*$this->itemPerPage;
					$from = $to -$this->itemPerPage;
					if($to>$this->recordset->count)
					{
						$to = $this->recordset->count;
					}
					
				}
				
				
				for($i=$from;$i<$to;$i++)
				{	
					if($this->eachFunction!="")
					{
						$funStr = $this->eachFunction;
						//echo $funStr;
						eval($funStr.'($this->recordset->data[$i]);');
						//$$funStr($this->recordset->data[$i]);
					}
					if($alc>0)
					{
						$this->innerHTML .= '<tr class="'.$this->alternetClass[($i+1)%$alc].'">';
					}
					else
					{
						$this->innerHTML .= '<tr>';
					}					
					if($this->showRownum)
					{
						$stObj = new Span($this->id.'_SR_'.($i));
						$stObj->setValue($i+1+$fromOffset);
						$stObj->showLable(false);
						$stObj->setEcho(false);
						$this->innerHTML .= '<td >'.$stObj->rander().'</td>';
						//$this->innerHTML .= '<td ><span id="'.$this->id.'_SR_'.($i+1).'">'.($i+1+$from).'</span></td>';
					}
					$this->recordset->moveRecord($i);
					//print_r($this->recordset);
					$row = $this->recordset->getCurrentRow();
					
					for($j=0;$j<count($this->columns);$j++)
					{
						
						$obj = clone $this->columns[$j]->getClass();
						//echo $this->id."_".$this->columns[$j]->getName()."_".($i+1)."<br>";
						$obj->setId($this->id."_".$this->columns[$j]->getName()."_".($i));
						$obj->setName($this->id."_".$this->columns[$j]->getName());
						$obj->setEcho(false);
						$obj->showLable($this->showLable);	
						$pr = $obj->property;
						
						//print_r(($this->columns[$j]->propertyMap));
						for($x=0;$x<count($this->columns[$j]->propertyMap);$x++)
						{
							//print_r( $this->columns[$j]->propertyMap[$x]['property']);
							$colVal = $row[$this->columns[$j]->propertyMap[$x]['column']];
							
							if(gettype($colVal)!="object")
							{
								$obj->setData($this->columns[$j]->propertyMap[$x]['property'],$colVal);
							}
							else
							{
								$colVal = $colVal->load();
							}
							//echo $this->columns[$j]->propertyMap[$x]['column'];
							
							//print_r($this->columns[$j]->propertyMap[$x]);
							
							if(count($this->displayModifier)>0)
							{
								$modStr = $this->findColumnModifier($this->columns[$j]->getName());
								//print_r($modStr['modifier']);
								$modStr = $modStr['modifier'];
								eval($modStr.'($colVal);');
							}
							if($this->columns[$j]->propertyMap[$x]['property']=="val")
							{
								$obj->setValue($colVal);	
							}
							else if($this->columns[$j]->propertyMap[$x]['property']=="href")
							{
								$obj->setUrl($colVal);	
							}
							else if($this->columns[$j]->propertyMap[$x]['property']=="name")
							{
								$obj->setName($colVal);	
							}
							else if($this->columns[$j]->propertyMap[$x]['property']=="data")
							{
								$obj->embedStream($colVal);	
							}
							else
							{	
								if(!gettype($colVal)=="object")
								{
								$obj->property .= ' '.$this->columns[$j]->propertyMap[$x]['property'].'="'.$colVal.'" ';
								}
							}
						}
						//print($obj->link."\n");
						//print($$obj->property."\n");
						
						$inteParm = $obj->integrationParameters;
						$obj->integrationParameters = array();
						foreach($inteParm as $k => $v)
						{
							$obj->setIntegrationParameter($k,$row[strtoupper($v)]);
						}
						
						
						$cv = "";
						if(!$this->columns[$j]->isVisible())
						{
							$cv="display:none;";
						}
						$rowSpan = "";

						if($this->showDistinctGroup)
						{
							if(count($this->recordset->distinctColumnIndex)>0)
							{
								
								if(isset($this->recordset->distinctColumnIndex[$this->columns[$j]->getName()]))
								{
									if(isset($this->recordset->distinctColumnIndex[$this->columns[$j]->getName()][$i."::".$colVal]))
									{
										if($this->recordset->distinctColumnIndex[$this->columns[$j]->getName()][$i."::".$colVal] > 1)
										{
											$rowSpan = 'rowspan="'.$this->recordset->distinctColumnIndex[$this->columns[$j]->getName()][$i."::".$colVal].'"';
										}
										if($colVal!='------"-----')
										{
											$this->innerHTML .= "<td  class=\"".$this->columns[$j]->getCssClass()."\"  $rowSpan style=\"".$this->columns[$j]->getStyleString().";$cv\">". $obj->rander(false) ."</td>\n";
										}
									}
									else
									{
										if($colVal!='------"-----')
										{
											$this->innerHTML .= "<td  class=\"".$this->columns[$j]->getCssClass()."\"  $rowSpan style=\"".$this->columns[$j]->getStyleString().";$cv\">". $obj->rander(false) ."</td>\n";
										}
									}
								}
								else
								{
									$this->innerHTML .= "<td  class=\"".$this->columns[$j]->getCssClass()."\"  style=\"".$this->columns[$j]->getStyleString().";$cv\">". $obj->rander(false) ."</td>\n";
								}
								
							}
							else
							{
								$this->innerHTML .= "<td  class=\"".$this->columns[$j]->getCssClass()."\"  style=\"".$this->columns[$j]->getStyleString().";$cv\">". $obj->rander(false) ."</td>\n";
							}
						}
						else
						{
							$this->innerHTML .= "<td  class=\"".$this->columns[$j]->getCssClass()."\"  style=\"".$this->columns[$j]->getStyleString().";$cv\">". $obj->rander(false) ."</td>\n";
						}
						
					}
					$this->innerHTML .= '</tr>'."\n";
					
				}
			}
		}
		if($this->showSummery==true)
		{
			if(count($this->summeryColumns)>0)
			{
				$this->makeSummery();
			}
			$headerClass ="";
			if($this->headerClass!="")
			{
				$headerClass = ' class="'.$this->headerClass.'" ';
			}
			$this->innerHTML .= '<tr '.$headerClass.'>';
			if($this->showRownum)
			{
				$this->innerHTML .= '<th ><span></span></th>';
			}
			for($i=0;$i<count($this->columns);$i++)
			{
					$col = $this->columns[$i];
					$orderControl = "";
					
					
					$cv = "";
					if(!$col->isVisible())
					{
						$cv="display:none;";
					}
					

					$l ='';
					if($this->showLable)
					{
						$l = '<label id="SUMERY_'.$this->id.'_'.$col->getName().'_label" for="SUMERY_'.$this->id.'_'.$col->getName().'">&nbsp;</label>';;
					}
					$c = $this->findSummeryColumn($col->getName());
					$this->innerHTML .= '<th class="'.$this->columns[$i]->getCssClass().'" style="'.$this->columns[$i]->getStyleString().';'.$cv.'"><span id="SUMERY_'.$this->id.'_'.$col->getName().'">'.$c['value'].'</span>'.$l.'</th>';
					
			}
			$this->innerHTML .= '</tr>';
		}
	}
	private function findSummeryColumn($col)
	{
		$col = strtoupper($col);
		for($i=0;$i<count($this->summeryColumns);$i++)
		{
			if($this->summeryColumns[$i]['column']==$col)
			{
				return $this->summeryColumns[$i];
			}
		}
	}

}
?>