<?php
class SearchForm
{
	private $obj1;
	private $obj2;
	var $id;
	function __construct($id)
	{
		$this->id = $id;
		$this->obj1 = new TextBox($this->id);
		$this->obj2 = new Button($this->id."_search");
		$this->obj2->addJsEvent("onclick",$this->id."_onClick");
		$this->obj2->setValue("Search");
	}
	function rander()
	{
		echo '<script type="text/javascript">
			function '.$this->id.'_onClick(obj,e)
			{
				window.find('.$this->id.'.val());
			}
		</script>';
		$this->obj1->rander();
		$this->obj2->rander();
	}
}
?>