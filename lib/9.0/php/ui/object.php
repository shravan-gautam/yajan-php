<?php
define("NO_ID","noid",true);
class UIObject
{
	var $type;
	var $tag;
	var $id;
	var $class;
	var $name;
	var $css;
	var $value;
	var $size;
	var $maxlength;
	var $property;
	var $lable;
	var $innerHTML;
	var $tagList;
	var $echo;
	var $link;
	var $nameStatus;
	var $idMode;
	var $placeHolder;
	var $phpEvent;
	var $width;
	var $height;
	var $disable;
	var $readonly;
	var $hindi;
	var $validateWith;
	var $contextMenu;
	var $valueFormat;
	var $showLable;
	var $jsValueUpdation;
	var $valueType;
	var $bootstrap;
	var $objectData;
	var $keyboradNavigation;
	var $integrationParameters;
	var $integratedQuery;
	var $dbConnection;
	var $recordset;
	var $nextEl;
	var $priviusEl;
	var $innerHtmlElement;
	var $showIntegratedQuery;
	function UIObject()
	{
		global $UI;
		$this->css=array();
		$this->size=0;
		$this->showIntegratedQuery=false;
		$this->maxlength=0;
		$this->property='';
		$this->lable='';
		$this->dbConnection = null;
		$this->innerHTML='';
		$this->integratedQuery="";
		$this->nextEl=null;
		$this->priviusEl=null;
		$this->integrationParameters=array();
		$this->contextMenu = null;
		$this->echo=true;
		$this->idMode="";
		$this->nameStatus=true;
		$this->placeHolder="";
		$this->disable=false;
		$this->readonly=false;
		$this->tagList = array("textarea","select","span","a","div","button","meter");
		$this->innerHtmlElement = array("textarea","span","div");
		$this->hindi = false;
		$this->validateWith="";
		$this->valueFormat = new Format("string");
		$this->showLable = true;
		$this->jsValueUpdation = true;
		$this->valueType = "char";
		$this->bootstrap=null;
		$this->objectData = array();
		$this->keyboradNavigation = true;
		if($UI->module=="bootstrap")
		{
			$this->bootstrap = new BootstrapObject();
		}
	}
	function nextElementId($id)
	{
		$this->nextEl = $id;
	}
	function priviusElementId($id)
	{
		$this->priviusEl = $id;
	}
	function setRecordset(Recordset $rec)
	{
		$this->recordset = $rec;
	}
	function getRecordset()
	{
		return $this->recordset;
	}
	function setDbConnection(Connection $db)
	{
		$this->dbConnection = $db;
	}
	function setIntegratedQuery($q)
	{
		$this->integratedQuery = $q;
	}
	function setIntegrationParameter($key,$val)
	{
		$this->integrationParameters[$key]=$val;
	}
	function setData($key,$val)
	{
		$this->objectData[strtoupper($key)]=$val;
	}
	public function setValueFormat($format) // Format or extancs or format Class object
	{
		$this->valueFormat = $format;
		if($this->value!="" && is_numeric($this->value)==true)
		{
			$this->value = $this->valueFormat->getFormatedValue($this->value);
		}
	}
	public function setContextMenu($contextMenu)
	{
		$this->contextMenu = $contextMenu;
	}
	function validate($format)
	{
		$this->validateWith = strtoupper($format);
	}
	function hindi($val)
	{
		$this->hindi = $val;
	}
	function disable($val)
	{
		$this->disable=$val;
	}
	function readonly($val)
	{
		$this->readonly=$val;
	}
	function setPlaceHolder($placeHolder)
	{
		$this->placeHolder = $placeHolder;
	}
	function width($w)
	{
		$this->width=$w;
	}
	function height($h)
	{
		$this->height=$h;
	}
	function setUrl($link)
	{
		$this->link = $link;
	}
	function setEcho($val)
	{
		$this->echo = $val;
	}
	function getId()
	{
		return $this->id;
	}
	function setName($name)
	{
		$this->name = $name;
	}
	function setId($id)
	{
		$this->id = $id;
	}
	function setLable($lable)
	{
		$this->lable=$lable;
	}
	function showLable($val)    //true or false
	{
		
		$this->showLable = $val;
	}
	function setSize($size)
	{
		$this->size = $size;
	}
	function setMaxLength($size)
	{
		$this->maxlength=$size;
	}
	function addCss($name,$value)
	{
		$p = count($this->css);
		$this->css[$p] = new UICSS();
		$this->css[$p]->addCss($name,$value);
	}
	function setValue($value,$jsValue=true)
	{
		$this->jsValueUpdation = $jsValue;
		$this->value=$value;
		if($this->valueFormat!=null && $this->valueType =="char")
		{
			if($this->value!="" && is_numeric($this->value)==true)
			{
				$this->value = $this->valueFormat->getFormatedValue($this->value);
			}
		}
	}
	function addJsEvent($name,$callback,$overWrite=false)
	{
		if($overWrite)
		{
			$p=-1;
			for($i=0;$i<count($this->events);$i++)
			{
				if(strtoupper($this->events[$i]->name)==strtoupper($name) && $this->events[$i]->type=="js")
				{
					$p = $i;
					break;
				}
			}
			if($p==-1)
			{
				$p=count($this->events);
			}
		}
		else
		{
			$p=count($this->events);
		}
		$this->events[$p]= new UIEvents($name,$callback,"js");
		return $this->events[$p];
	}
	function addPhpEvent($name,$url,$callback,$overWrite=false)
	{
		if($overWrite)
		{
			$p=-1;
			for($i=0;$i<count($this->events);$i++)
			{
				if(strtoupper($this->events[$i]->name)==strtoupper($name) && $this->events[$i]->type=="php")
				{
					$p = $i;
					break;
				}
			}
			if($p==-1)
			{
				$p=count($this->events);
			}
		}
		else
		{
			$p=count($this->events);
		}
		$this->events[$p]= new UIEvents($name,$callback,"php");
		$this->events[$p]->setUrl($url);
		return $this->events[$p];
	}
	private function _isInlineClose()
	{	
		
		if(in_array($this->tag,$this->tagList))
		{
			return true;
		}
		else
		{
			return false;
		}
		
	}
	function createInnerHTML()
	{
		
	}
	function addClass($class)
	{
		$this->class .= $class. " ";
	}
	function required($val)
	{
		if($val==true)
		{
			$this->class .= "required ";
		}
		else
		{
			$this->class = str_replace("required","",$this->class);
		}
	}
	private function _createTagDescriptor()
	{
		
		global $UI;
		$class='';
		if($this->contextMenu!=null)
		{
			$this->class.=" contextMenu_".$this->contextMenu->id;
		}
		
		
		
		$evt = "";
		if(count($this->events)>0)
		{
			for($i=0;$i<count($this->events);$i++)
			{
				if($this->events[$i]->type=="js")
				{
					//$this->events[$i]->callback=$this->events[$i]->callback."";
					$parm='';
					for($j=0;$j<count($this->events[$i]->parm);$j++)
					{
						
						if($this->events[$i]->parm[$j]['varname']!='')
						{
							$parm.="'".$this->events[$i]->parm[$j]['varname']."'";
						}
						else
						{
							$parm.="".$this->events[$i]->parm[$j]['name'];
						}
						if($j<count($this->events[$i]->parm)-1)
						{
							$parm.=",";
						}
					}
					if($parm!='')
					{
						$parm=",$parm";
					}
					$evt .= ' '.$this->events[$i]->name.'="'.$this->events[$i]->callback.'(this,event'.$parm.')" ';
				}
				else if($this->events[$i]->type=="php")
				{
					$parm='';
					for($j=0;$j<count($this->events[$i]->parm);$j++)
					{
						$parm.="'".$this->events[$i]->parm[$j]['name']."','".$this->events[$i]->parm[$j]['varname']."','".$this->events[$i]->parm[$j]['value']."'";
						if($j<count($this->events[$i]->parm)-1)
						{
							$parm.=",";
						}
					}
					if($parm!='')
					{
						$parm=",$parm";
					}
					$evt .= ' '.$this->events[$i]->name.'="callPhpMathod('."'".$this->events[$i]->url."','".$this->events[$i]->callback."'$parm".')" ';
				}
			}
			
		}
		$css = '';
		if(count($this->css)>0)
		{
			for($i=0;$i<count($this->css);$i++)
			{
				$css .= $this->css[$i]->css['name'].":".$this->css[$i]->css['value'].";";
			}
		}
		if($css!="")
		{
			$css = ' style="'.$css.'" ';
		}
		$size='';
		if($this->size>0)
		{
			$size = ' size="'.$this->size.'" ';
		}
		
		$maxlength='';
		if($this->maxlength>0)
		{
			$maxlength = ' maxlength="'.$this->maxlength.'" ';
		}
		$str='';
		if($this->lable!='')
		{
			$str.='<label for="'.$this->id.'">'.$this->lable.'</label>';
		}
		$id=' id="'.$this->id.'" ';

		if($this->idMode=='noid')
		{
			$id='';
			//$value = ' value="'.$this->value.'" ';
		}
		
		$name="";
		if($this->name!="")
		{
			$name=' name="'.$this->name.'" ';
		}
		else if($this->nameStatus==true)
		{
			$name=' name="'.$this->id.'" ';
		}
		$value = "";

		if($this->value!="")
		{
			if(array_search($this->tag,$this->innerHtmlElement)!==false)
			{
				$this->innerHTML.=$this->value;
				$this->value="";
			}
			else
			{
				$value = ' value="'.$this->value.'" ';
			}
		}
		$type="";
		if($this->type!="")
		{
			$type=' type="'.$this->type.'" ';
		}
		$placeHolder= "";
		if($this->placeHolder!="")
		{
			$placeHolder = ' placeholder="'.$this->placeHolder.'" ';
		}
		$disable = "";
		if($this->disable==true)
		{
			$disable=' disabled="disabled" ';
		}
		$readonly="";
		if($this->readonly==true)
		{
			$readonly= ' readonly="readonly" ';
		}
		
		$language=' lang="english" ';
		if($this->hindi==true)
		{
			$language = ' lang="hindi" ';
		}
		
		if($this->validateWith!="")
		{
			$this->class.=" validate".$this->validateWith." ";
		}
		
		if($UI->module=="bootstrap")
		{
			if($this->bootstrap!=null)
			{
				$this->class .= $this->bootstrap->getCss($this->tag);
			}
		}
		if($this->class!="")
		{

			$class=' class="'.$this->class.'" ';
		}
		//print($this->property."\n");
		$str = '';

		$str .= '<'.$this->tag.$class.$size.$maxlength.$this->property.$id.$value.$name.$type.$css.$evt.$readonly.$disable.$placeHolder.$language.' >'."\n";

		return $str;
	}
	function createJsObject()
	{
		global $JS_ELEMENT_MODE;
		$str = '';
		if($this->idMode!='noid')
		{
			$val = $this->value;
			$val = str_replace("\"","\\\"",$val);
			if($JS_ELEMENT_MODE=="jquery")
			{
				if($this->jsValueUpdation)
				{
					$str.=$this->id.'=$("#'.$this->id.'");'.$this->id.'.val("'.$val.'");'."\n";
				}
			}
			else if($JS_ELEMENT_MODE=="dom")
			{
				$val = str_replace("\r\n","",$val);
				$val = str_replace("\n","",$val);

				$str.='
				try{
					'.$this->id.'=document.getElementById("'.$this->id.'");
					
					';
						$str.=$this->id.'.addInTabIndex();'."\n";
						if($this->nextEl!=null)
						{
							$str.=$this->id.'.nextElementId("'.$this->nextEl.'");'."\n";
						}
						if($this->priviusEl!=null)
						{
							$str.=$this->id.'.priviusElementId("'.$this->nextEl.'");'."\n";
						}
						foreach($this->objectData as $k =>$v)
						{
							$v1 = str_replace("\r","\\\r",$v);
							$v1 = str_replace("\n","\\\n",$v1);
							$str.=$this->id.'.set("'.$k.'","'.$v1.'");'."\n";
						}

					
					$str.='
				}
				catch(err)
				{
					console.log(err);
				}'."\n";

				if($this->jsValueUpdation && $val!="")
				{
					$str.='
					try
					{
						'.$this->id.'.val("'.$val.'");
					}
					catch(ex)
					{
					}'."\n";
				}
				if($this->validateWith!="")
				{
					if($this->validateWith=="EMAIL")
					{
						$str .='if(typeof '.$this->id.'.onblur == "object"){ '.$this->id.'.onblur=function(){
							if(!'.$this->id.'.isEmail())
							{
								'.$this->id.'.label("Invalid Email.");
							}
							else
							{
								'.$this->id.'.label("");
							}
						}}';
					}
				}
			}
		}
		if($this->hindi == true)
		{
			$str.= 'keyboardStatus=true;transliterate("'.$this->id.'");';
		}
		return $str;
	}
	function setProperty($property)
	{
		$this->property = $property;
	}
	function rander($echo=true)
	{
		
		global $JS_ELEMENT_MODE;
		$str = "";
		
		if($this->integratedQuery!="")
		{
			$str =  $this->integratedQuery;
		}
		if(count($this->integrationParameters)>0)
		{
			foreach($this->integrationParameters as $k => $v)
			{
				$str = str_replace('{'.$k.'}',"$v",$str);
			}
		}
		if($this->dbConnection!=null)
		{
			//echo $str;
			if($this->showIntegratedQuery)
			{
				echo $str;
			}
			$this->recordset = $this->dbConnection->execute($str);
		}
				
		$this->createInnerHTML();

		$str = "\n".$this->_createTagDescriptor();
		
		if($this->innerHTML!="")
		{
			$str .= $this->innerHTML;
			$str .= "</".$this->tag.">\n";
		}
		else
		{
			if(!$this->_isInlineClose())
			{
				$str = substr($str,0,strlen($str)-2)." />\n";
			}
			else
			{
				if($this->value!="" )
				{
					$this->innerHTML.=$this->value;
				}
				
				$str .= $this->innerHTML;
				$str .= "</".$this->tag.">\n";
			}
		}
		
		if($this->showLable)
		{
			$str .= '<label id="'.$this->id.'_label" for="'.$this->id.'">&nbsp;</label>';
		}
		$str .= '<script type="text/javascript" language="javascript">'.$this->createJsObject().'</script>';
		if($this->echo==true && $echo==true)
		{
			echo $str;
		}

		return $str;
	}
}
?>