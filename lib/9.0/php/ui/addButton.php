<?php
class AddButton extends Button
{
	var $callback;
	var $url;
	var $width;
	var $height;
	function AddButton()
	{
		
		global $application,$module;
		$this->callback="onClicAddButton".time();
		parent::Button("addButton".time());
		$this->addJsEvent("onClick",$this->callback);
		$this->url="/$application/$module/add";
		$this->width="70%";
		$this->height="70%";
		$this->setValue("Add");
	}
	function setUrl($url)
	{
		$this->url=$url;
	}
	function rander($echo=true)
	{
		echo '
		<script type="text/javascript">
		function '.$this->callback.'(obj,e)
		{
			showLink("'.$this->url.'","'.$this->width.'","'.$this->height.'");
		}
		</script>
		';
		parent::rander($echo);
	}
}
?>