<?php
class JQTab
{
	var $id;
	var $page;
	function JQTab($id)
	{
		$this->id = $id;
		$this->page = array();
	}
	function addPage($title,$html='',$id='')
	{
		$temp = array();
		$temp['title']=$title;
		$temp['html']=$html;
		$temp['id']=$id;
		$this->page[count($this->page)]=$temp;
		
	}
	function rander()
	{
		$ul='<ul>';
		$div='';
		$js='';
		for($i=0;$i<count($this->page);$i++)
		{
			$div.='<div id="'.$this->id.'_'.$i.'"> '.$this->page[$i]['html'].' </div>';
			$ul.='<li><a href="#'.$this->id.'_'.$i.'">'.$this->page[$i]['title'].'</a></li>';
			$js.='var '.$this->id.'_'.$i.'=document.getElementById("'.$this->id.'_'.$i.'");';
			if($this->page[$i]['id']!='')
			{
				$js.= '
				var t = document.getElementById("'.$this->page[$i]['id'].'").outerHTML;
				document.getElementById("'.$this->page[$i]['id'].'").outerHTML = "";
				$("#'.$this->id.'_'.$i.'").html(t);
				';
				
			}
		}
		$ul.="</ul>";
		echo "<div id='".$this->id."'>$ul\n$div</div>";
		//$js=' <script>$(function() {var '.$this->id.' = $( "#'.$this->id.'" ).tabs();});</script>';
		$js.='$(function() {var '.$this->id.' = $( "#'.$this->id.'" ).tabs();});';
		//echo $js;
		echo '<script type="text/javascript">'.$js."</script>";
	}
}
?>