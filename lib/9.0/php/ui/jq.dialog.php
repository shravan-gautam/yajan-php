<?php
class JQDialog
{
	var $id;
	var $width;
	var $height;
	var $x;
	var $y;
	var $title;
	var $module;
	var $resource;
	var $close;
	var $resize;
	var $move;
	function JQDialog($id,$title)
	{
		$this->id = $id;
		$this->title = $title;
		$this->height="";
		$this->width="";
		$this->x="";
		$this->y="";
		$this->modal=false;
		$this->resource="";
		$this->close=true;
		$this->resize=true;
		$this->move=false;
	}
	function setSize($w,$h)
	{
		$this->height=$h;
		$this->width=$w;
	}
	function setXY($x,$y)
	{
		$this->x=$x;
		$this->y=$y;
	}
	function modal($val)
	{
		$this->modal =$val;
	}
	function resource($r)
	{
		$this->resource = $r;
		$this->id=$r;
	}
	function close($v)
	{
		$this->close=$v;
	}
	function move($v)
	{
		$this->move=$v;
	}
	function resize($v)
	{
		$this->resize=$v;
	}
	function rander()
	{
		$w = "";
		if($this->width!="")
		{
			$w=",width:"+$this->width;
		}
		$h = "";
		$x = "";
		$y = "";
		$prop = "";
		if($this->x!="")
		{
			$prop .= 'x: '.$this->x.",";
		}
		if($this->y!="")
		{
			$prop .= 'y: '.$this->y.",";
		}
		if($this->height!="")
		{
			$prop .= 'height: '.$this->height.",";
		}
		if($this->width!="")
		{
			$prop .= 'width: '.$this->width.",";
		}
		if($this->x!="")
		{
			$prop .= 'x: '.$this->x.",";
		}
		if($this->modal==true)
		{
			$prop .= 'modal: '.$this->modal.",";
		}
		if($this->move==false)
		{
			$prop .= 'draggable: false,';
		}
		if($this->resize==false)
		{
			$prop .= 'resizable: false,';
		}
		$prop = rtrim($prop,",");
		if($this->resource=="")
		{
			echo '<div id="'.$this->id.'" ></div>';
		}
		
		
		echo '<script>
$(function() {
document.getElementById("'.$this->id.'").title="'.$this->title.'";
'.$this->id.' = $( "#'.$this->id.'" ).dialog({'.$prop.'});

});
</script>';
	}
}
?>