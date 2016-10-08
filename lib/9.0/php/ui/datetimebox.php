<?php
class DateTimeBox extends TextBox
{
	function DateTimeBox($id)
	{
		parent::TextBox($id);
		$this->type = "datetime";
	}
}
?>