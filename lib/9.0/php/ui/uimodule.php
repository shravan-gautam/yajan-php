<?php
class UIModule
{
	public $module;
	var $cssObject;
	public function UIModule()
	{
		$this->module="jquery";
		$this->cssObject = null;
	}
	public function begin()
	{
		echo '<div class="container">';
	}
	public function end()
	{
		echo '</div>';
	}
	public function createJsObject()
	{
		echo '<script type="text/javascript">
			UI = new UIModule();
		</script>';
	}
	function getCss($tag)
	{
		if($this->cssObject!=null)
		{
			return $this->cssObject->getCss($tag);
		}
		else
		{
			return "";
		}
	}
}

?>