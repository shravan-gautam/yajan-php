<?php
class RichEditor extends  TextArea 
{
	
	function __construct($id)
	{
		parent::__construct($id);
	}
	function rander($echo=true)
	{
		parent::rander($echo);
		echo '<script type="text/javascript" language="javascript">	
		function changeRichEditor'.$this->id.'()
		{
			'.$this->id.'_editor =$("#'.$this->id.'").treditor({"width":"'.$this->width.'","height":"'.$this->height.'"});
		}
		'.$this->id.'_editor = null;
		setTimeout("changeRichEditor'.$this->id.'()",400);</script>';
	}
}
?>