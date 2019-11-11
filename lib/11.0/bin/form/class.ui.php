<?php
class UICreater
{
	var $type;
	var $name;
	var $path;
	function __construct($type,$name,$path)
	{
		$this->type = $type;
		$this->name = $name;
		$this->path = $path;
	}
	function create()
	{
		global $MODULE_PATH,$CS;
		if($this->type=="form")
		{
			$php = '<?php
$form = new Form("'.$this->name.'");
$form->setUrl("");
$form->ajax(true);
$form->ajaxCallback("post_'.$this->name.'");
if($form->submited())
{
	
}
else
{
	?>
	<script type="text/javascript">
	function post_'.$this->name.'(resp)
	{
		
	}
	</script>
	<?php
	$form->begin();
	?>
	
	<?php
	$form->end();
}
?>';

			exec("mkdir -p $MODULE_PATH/$this->path");
			file_put_contents("$MODULE_PATH/$this->path/$this->name.php",$php);
			$CS->showInfo("UI Form $this->name created.");
		}
	}
}
?>