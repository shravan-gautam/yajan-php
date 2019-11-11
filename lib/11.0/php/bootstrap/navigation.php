<?php
class Navigation
{
	public $mode;
	private $items;
	private $html;
	private $position;
	private $placement;
	private $class;
	public function __construct($id)
	{
		$this->showlable=false;
		$this->mode = "";
		$this->html="";
		$this->items = new ItemGroup();
		$this->class="navbar-default";
		$this->position=null;
		$this->placement=null;
		
	}
	function add($name,$value='')
	{
		$this->items->add($name,$value);
	}
	function addGroup($name)
	{
		return $this->items->addGroup($name);
	}
	public function setPosition($p)
	{
		$this->position=$p;
	}
	public function setPlacement($p)
	{
		$this->placement=$p;
	}
	private function _getHtml($node,$mode=false)
	{

		if($this->html=="")
		{
			if($mode==false)
			{
				$this->html.='<ul class="nav navbar-nav">'."\n";
			}
			else
			{
				$this->html.='<ul class="dropdown-menu">'."\n";
			}
		}
		else
		{
			$this->html.="<ul class='dropdown-menu'>\n";
		}
		for($i=0;$i<count($node);$i++)
		{
			$this->html.="<li class='dropdown'>\n";
			$name = ($node[$i]['name']);
			$url = ($node[$i]['value']);
			if(!empty($node[$i]['items']))
			{
				$this->html.='<a  href="#" class="dropdown-toggle" data-toggle="dropdown">'.$name.'<b class="caret"></b></a>'."\n";
				$item =  ($node[$i]['items']);
				if(gettype($item)=="object")
				{
					$this->_getHtml($item->items,true);
				}
			}
			else
			{
				$this->html.='<a href="'.$url.'">'.$name.'</a>'."\n";
			}
			$this->html.="</li>\n";
		}
		$this->html.="</ul>\n";
	}
	public function rander()
	{
		if($this->placement != null && $this->position!=null)
		{
			$this->class="navbar-$this->position-$this->placement";
		}
		
		echo '<div class="navbar '.$this->class.'">
		<div class="navbar-inner">
        <div class="navbar-header">
		
        <a class="navbar-brand" href="#">Project name</a>
        </div>
        <div class="navbar-collapse collapse">';
		$this->_getHtml($this->items->items);
		echo $this->html;
		echo '
		</div>
		</div><!--/.nav-collapse -->
		</div>';
	}
}
?>