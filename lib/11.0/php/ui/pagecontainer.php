<?php
require_once("$LIB_PATH/php/ui/event.php");
require_once("$LIB_PATH/php/ui/object.php");
class PageContainer
{
	private $pages;
	private $content;
	private $id;
	private $pageSize;
	private $header;
	private $showFooter;
	
	function __construct($id)
	{
		$this->id = $id;
		$this->pages = array();
		$this->pageSize = "A4";
		$this->showFooter=false;
	}
	function footer($v)
	{
		$this->showFooter= $v;
	}
	function header($h)
	{
		$this->header = $h;
	}
	function setContent($content)
	{
		$this->content=$content;
	}
	function pageSize($p)
	{
		$this->pageSize = $p;
	}
	function rander()
	{
		
		$this->content = explode(" ",$this->content);
		$this->content = join(" </span><span>",$this->content);
		$this->content = "<span>$this->content</span>";
		$this->content = '<div id="'.$this->id.'_header">'.$this->header.'</div>'.$this->content;
		echo '<div id="'.$this->id.'">'.$this->content.'</div>';
		echo '<script type="text/javascript">
			'.$this->id.' = new PageContainer("'.$this->id.'","'.$this->id.'");
			'.$this->id.'.pageSize("'.$this->pageSize.'");';
			if($this->showFooter)
			{
				echo $this->id.'.showFooter();';
			}
			echo '
			'.$this->id.'.pageBreak();
			'.$this->id.'.rander();
		</script>';
	}
}
?>