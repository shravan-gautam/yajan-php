<?php
class ContextMenu
{
	public $id;
	public $items;
	public $callback;
	function __construct($id)
	{
		$this->id = $id;
		$this->items = new ItemGroup();
	}
	function add($name,$val)
	{
		$this->items->add($name,$val);
	}
	function addGroup($name)
	{
		return $this->items->addGroup($name);
	}
	function setCallback($fun)
	{
		$this->callback = $fun;
	}
	function rander()
	{
		echo '<script type="text/javascript">
		var menulist_'.$this->id.' = 
		[
			';
			for($i=0;$i<$this->items->count();$i++)
			{
				$v = $this->items->get($i);

				echo "
				{'".$v['name']."':
					{ 
						onclick:function(menuItem,menu) 
						{ 
							".$this->callback."(menuItem,menu);
						}, 
						className:'menu3-custom-item', 
						hoverClassName:'menu3-custom-item-hover',
						rel:'".$v['value']."'
					} 
				}, 
				";
			}
			echo'
		];
		var '.$this->id.' = new ContextMenu("'.$this->id.'");
		'.$this->id.'.setItemObject(menulist_'.$this->id.');
		'.$this->id.'.refresh();
		</script>';
	}
}
?>