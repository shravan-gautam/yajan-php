<?php
class LovButton extends Button
{
	var $lsitWidth;
	var $lsitHeight;
	var $target;
	var $eventParm;
	function LovButton($id)
	{
		parent::Button($id);
		$this->setValue("Select");
		//$this->eventParm= $this->addJsEvent("onClick","onClick$this->id"."LovButton");
		$this->eventParm= $this->addJsEvent("onClick","onClick$this->id"."LovButton");
		$this->lsitWidth=600;
		$this->lsitHeight = 500;		
	}
	function setWidth($w)
	{
		$this->lsitWidth = $w;
	}
	function setUrl($url)
	{
		$this->target = $url;
	}	
	function setHeight($h)
	{
		$this->lsitHeight = $h;
	}
	function getEventParameter()
	{
		return $this->eventParm;
	}
	function rander($val=true)
	{
		$eExist = false;
		//print_r($this->events);
		for($i=0;$i<count($this->events);$i++)
		{
			$e = $this->events[$i];
			//print_r($e->name);
			if(strtoupper($e->name)=="ONCLICK")
			{
				$eExist=true;
				break;
			}
		}
		if(!$eExist)
		{
			
		}
		
		$sp = "?";
		if(strpos($this->target,"?")!=false)
		{
			$sp="&";
		}
		$r = parent::rander($val);
		$sc = '<script type="text/javascript">
		function onClick'.$this->id.'LovButton()
		{
			var p="";
			for(var i=0;i<arguments.length;i++)
			{
				if(i>1)
				{
					p+="argv"+(i-2)+"="+arguments[i].val()+"&";
				}
			}
			window.open("'.$this->target.$sp.'mode=popup&"+p,"","width='.$this->lsitWidth.',height='.$this->lsitHeight.'");
		}
		</script>';
		if($val)
		{
			echo $sc;
		}
		return $r.$sc;

	}
}

?>