<?php
class JQMenu
{
	var $id;
	var $items;
	var $html;
	function JQMenu($id)
	{
		$this->id = $id;
		$this->items = new ItemGroup();
		$this->html="";
	}
	function add($name)
	{
		$this->items->add($name);
	}
	function addGroup($name)
	{
		return $this->items->addGroup($name);
	}
	private function _getHtml($node)
	{
		if($this->html=="")
		{
			$this->html.='<ul id="'.$this->id.'">'."\n";
		}
		else
		{
			$this->html.="<ul>\n";
		}
		for($i=0;$i<count($node);$i++)
		{
			$this->html.="<li>\n";
			$name = ($node[$i]['name']);
			$this->html.='<a href="#">'.$name.'</a>'."\n";
			if(!empty($node[$i]['items']))
			{
				$item =  ($node[$i]['items']);
				
				if(gettype($item)=="object")
				{
					$this->_getHtml($item->items);
				}
			}
			$this->html.="</li>\n";
		}
		$this->html.="</ul>\n";
	}
	function rander()
	{
		$this->_getHtml($this->items->items);
		echo $this->html;
		echo ' <script>
$(function() {
$( "#'.$this->id.'" ).menu();
});
</script>';
	}
}
?>