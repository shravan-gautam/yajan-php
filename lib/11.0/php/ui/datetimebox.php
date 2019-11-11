<?php
class DateTimeBox extends TextBox
{
	function __construct($id)
	{
		parent::__construct($id);
		$this->type = "datetime";
	}
}
?>