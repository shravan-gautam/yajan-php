<?php
class Form
{
	var $url;
	var $mathod;
	var $encod;
	var $id;
	var $ajax;
	var $ajaxCallback;
	var $postSubmitCallback;
	var $validateStatus;
	var $className;
	var $bootStrap;
	var $formGroup=false;
	var $externalIframe="";
	function __construct($id,$url="")
	{
	
		$this->id = $id;
		$this->url=$url;
		$this->mathod = "post";
		$this->encode = "";
		$this->ajax=false;
		$this->ajaxCallback="''";
		$this->postSubmitCallback="";
		$this->validateStatus=false;
		$this->className = "form";
		$this->bootStrap=null;
		
	}
	function mathod($mathod)
	{
		$this->mathod=$mathod;
	}
	function addCss($css)
	{
		$this->className = $css;
	}
	function validation($val)
	{
		$this->validateStatus=$val;
	}
	function setPostsubmit($callback)
	{
		$this->postSubmitCallback = $callback;
	}
	function ajaxCallback($callback)
	{
		$this->ajaxCallback=$callback;
	}
	function setExternalIframe($iframeId)
	{
		$this->externalIframe = $iframeId;
	}
	function begin()
	{
		global $UI;
		
		$encode = '';
		if($this->encode!="")
		{
			$encode = 'enctype="'.$this->encode.'"';
		}
		$targate = "";
		if($this->postSubmitCallback!="")
		{
			$this->postSubmitCallback = rtrim($this->postSubmitCallback,"()");
		}
		
		
		if($this->postSubmitCallback!="")
		{
			$postSubmit = "var resp = ".$this->postSubmitCallback.";
			if(resp==false)
			{
				if(navigator.appName == \"Microsoft Internet Explorer\") 
				{
					window.document.execCommand('Stop');
				}
				else {
					window.stop();
				}
				return false;
			}
			else
			{
				return true;
			}
			";
		}
		$targetIframe = "";
		if($this->ajax==true)
		{
			if($this->externalIframe=="")
			{
				//
				$targetIframe = $this->id.'_ifream';
				$targate= 'target = "'.$this->id.'_ifream"';
				echo '<iframe style="width:0;height:0;border:0px solid #fff;visibility:hidden;" name="'.$this->id.'_ifream" id="'.$this->id.'_ifream">   
				</iframe>
			
					';
			}
			else
			{
				$targetIframe = $this->externalIframe;
				$targate='target = "'.$this->externalIframe.'"';
			}
		}
		else
		{
			if($this->externalIframe!="")
			{
				$targetIframe = $this->externalIframe;
				$targate='target = "'.$this->externalIframe.'"';
			}
		}
		echo '<form id="'.$this->id.'_form" class="'.$this->className.'" action="'.$this->url.'"  method="'.$this->mathod.'" '.$encode.$targate.' role="search"  >';
		echo '<input type="hidden" name="formProcessor" value="'.md5($this->id).'" />';
		echo '<input type="hidden" id="'.$this->id.'_submitStart" value="" />';
		if($UI->module=="bootstrap")
		{
			echo '<div class="form-group">';
			$this->formGroup = true;
		}

		echo '<script type="text/javascript">
				'.$this->id.' = new Form("'.$this->id.'","'.$this->id.'_form");
				$("#'.$targetIframe.'").load(function(){
					'.$this->id.'.ajaxSubmitComplite('.$this->ajaxCallback.');
				});
				</script>';

	}
	function ajax($val)
	{
		$this->ajax=$val;
	}
	function setUploadEncode()
	{
		$this->encode = "multipart/form-data";
	}
	function submitted()
	{
		return $this->submited();
	}
	function submited()
	{
		global $formProcessor;
		if($formProcessor==md5($this->id))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function setUrl($url,$forceOverwrite=false)
	{
		global $SCRIPT_PATH;
		if($SCRIPT_PATH!="" && !$forceOverwrite)
		{
			$this->url = $SCRIPT_PATH."/".$url;	
		}
		else
		{
			$this->url = $url;	
		}
	}
	function submit($name = "Submit",$class="")
	{
		global $UI;
		if($UI->module=="bootstrap")
		{
			$this->formGroup = false;
			
		}
			if(gettype($name)=="string")
			{
				$b1 = new Button($this->id."_send");
				$b1->setValue($name);
				if($class!="")
				{
					$b1->addClass($class);
				}
				//$b1->type="submit";
			}
			else
			{
				$b1 = $name;
			}

			$b1->addJsEvent("onClick",$this->id.".submit");
			$b1->rander();
	}
	function reset($name="Reset")
	{
		$submit = new Button($this->id."_reset");
		$submit->type="reset";
		$submit->setValue($name);
		$submit->rander();
	}
	function cancle($cancleCallback,$lable="Cancel")
	{
		$cancle = new Button($this->id."_cancle");
		$cancle->type="button";
		$cancle->addJsEvent("onclick",$cancleCallback);
		$cancle->setValue($lable);
		$cancle->rander();
	}
	function end()
	{
		global $UI;
		if($UI->module=="bootstrap" && $this->formGroup==false)
		{
			$this->formGroup = false;
			echo '</div>';
		}
		echo '</form>';
		
		echo '<script type="text/javascript" language="javascript">
		
		';
		if($this->postSubmitCallback!="")
		{
			echo '
				'.$this->id.'.setPostSubmit('.$this->postSubmitCallback.');';
		}
		
		echo'
		function onClickSubmit_'.$this->id.'(obj,e)
		{
				'.$this->id.'.submitStatus = true;
				'.$this->id.'.submit();
		}
		$("#'.$this->id.'").on("submit", function() 
		{
				return false;
		});
		';
		echo '</script>';
	}
}
?>
