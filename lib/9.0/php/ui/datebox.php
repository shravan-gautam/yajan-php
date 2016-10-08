<?php
class DateBox extends TextBox
{
	function DateBox($id)
	{
		parent::TextBox($id);
		$this->type = "date";
	}
}
?>