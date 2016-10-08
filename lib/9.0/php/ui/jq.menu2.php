<?php
class JQMenu2
{
	var $id;
	var $items;
	var $html;
	function JQMenu2($id)
	{
		$this->id = $id;
		$this->items = new ItemGroup();
		$this->html="";
	}
	function add($name,$url='javascript:',$target='')
	{
		echo $target;
		$this->items->add($name,$url,$target);
	}
	function addGroup($name)
	{
		return $this->items->addGroup($name);
	}
	private function _getHtml($node)
	{
		if($this->html=="")
		{
			$this->html.='<ul>'."\n";
		}
		else
		{
			$this->html.="<ul>\n";
		}
		for($i=0;$i<count($node);$i++)
		{
			$this->html.="<li>\n";
			$name = ($node[$i]['name']);
			$url = ($node[$i]['value']);
			$target = ($node[$i]['target']);
			if($target!="")
			{
				$target = ' target="'.$target.'" ';
			}
			if(!empty($node[$i]['items']))
			{
				$this->html.='<span tabindex="1">'.$name.'</span>'."\n";
				$item =  ($node[$i]['items']);
				
				if(gettype($item)=="object")
				{
					$this->_getHtml($item->items);
				}
			}
			else
			{
				$this->html.='<a href="'.$url.'" '.$target.'>'.$name.'</a>'."\n";
			}
			$this->html.="</li>\n";
		}
		$this->html.="</ul>\n";
	}
	function rander()
	{
		$this->_getHtml($this->items->items);
		echo '<div id="'.$this->id.'" class="'.$this->id.'">'.$this->html.'</div>';
	}
}
?>