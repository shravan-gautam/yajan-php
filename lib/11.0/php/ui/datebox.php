<?php
class DateBox extends TextBox
{
	function __construct($id)
	{
		parent::__construct($id);
		$this->type = "date";
	}
}
?>