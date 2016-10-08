<?php
class FormBuilderView
{
	var $name;
	var $form;
	var $property;
	var $path;
	function FormBuilderView($form,$name)
	{
		$this->form = $form;
		$this->name = $name;
		$this->path = $this->form->path."/view/$name";
		include "$this->path/property.php";
		$this->property = $viewProperty;

	}
	function getHtml()
	{
		$template = "$this->path/template.php";
		
		$block = new FormBuilderDatablock($this->form,$this->getProperty("datablock"));
		$items = $block->getItems();
		
		$html  = '<table>';
		for($i=0;$i<count($items);$i++)
		{
			$item=$items[$i];
			$name=$item->name;
			
			$html .= '
	<tr>
		<td>'.$item->name.'</td>
		<td><?php
			$obj=$tb->getValueAs("'.$name.'","TextBox");
			$obj->rander();
		?></td>
	</tr>';
			
		}
		$html .= '</table>';
		return $html;
	}
	function getProperty($name)
	{
		return $this->property[$name];
	}
	
}
?>