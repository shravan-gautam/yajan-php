<?php
class JQAccordion
{
	var $id;
	var $page;
	function JQAccordion($id)
	{
		$this->id = $id;
		$this->page = array();
	}
	function addPage($title,$html='')
	{
		$temp = array();
		$temp['title']=$title;
		$temp['html']=$html;
		$this->page[count($this->page)]=$temp;
	}
	function rander()
	{
		$ul='<ul>';
		$div='';
		$js='';
		for($i=0;$i<count($this->page);$i++)
		{
			$div.=' <h3>'.$this->page[$i]['title'].'</h3><div id="'.$this->id.'_'.$i.'"> '.$this->page[$i]['html'].' </div>'."\n";
			//$ul.='<li><a href="#'.$this->id.'_'.$i.'">'.$this->page[$i]['title'].'</a></li>';
			$js.='var '.$this->id.'_'.$i.'=document.getElementById("'.$this->id.'_'.$i.'");'."\n";
		}
		$ul.="</ul>";
		echo "<div id='".$this->id."'>$div</div>";
		$js=' <script>$(function() {var '.$this->id.' = $( "#'.$this->id.'" ).accordion();});</script>';
		echo $js;
	}
}
?>