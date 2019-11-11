<?php
class MVCAppConsole
{
	var $instanceName;
	var $applicationName;
	var $dir;
	function __construct($cmd)
	{
		$this->instanceName ="";
		$this->applicationName = "";
		$this->dir;
		global $CS,$DEFAULT_DB_CONFIG,$FORM_BUILDER_VAR,$OUTPUT_CACHE_LOCATION;
		$this->cmd = $cmd;
		$this->version = "1.1";
		$CS->setFGColor("yellow");
		$CS->cout("\n\nMVC Application Console $this->version\n\n");	
		
		
	}
	function run()
	{
		global $CS,$DB_REG,$__run;
		while($cmd!="exit")
		{
			if(isset($__run))
			{
				$cmd=$__run;
				echo "executing \"$cmd\"\n";
			}
			$cmd = explode(" ",$cmd);
			if($cmd[0]=="create")
			{
				$this->create($cmd);
			}
			else if($cmd[0]=="open")
			{
				$this->open($cmd);
			}
			
			
			if(isset($__run))
			{
				break;
			}
			if($this->instanceName=="" && $this->applicationName=="")
			{
				echo "MVC-Console: > ";
			}
			else
			{
				echo "MVC-Console [$this->instanceName/$this->applicationName]: > ";
			}
			
			$cmd = $CS->read();
			$cmd = rtrim($cmd,";");
		}
	}
	function create($cmd)
	{
		global $CS,$db;
		$obj = $cmd[1];
		/*
			create instance <isntancename> on <homedir>
			create application <applicationname>
			create application <applicationname> with default <controllername>/<methodname>
			create module <modulename> for <tablename>
			create controller <controllername> 
			create view <viewname>
			create view <viewname> for <modulename> as <list/add/edit>
		*/
		if($obj == "instance")
		{
			$name = $cmd[2];
			$dir = $cmd[4];
			if(!isset($cmd[4]))
			{
				$CS->showError("insuffuciant parameter");
				$CS->showInfo("create instance <isntancename> on <homedir>");
				return;
			}
			if(!is_dir("$name"))
			{
				mkdir($name);
				$this->instanceName = $name;
				$dir = rtrim($dir,"/$name");
				$this->dir = $dir;
				$p = array();
				$p["name"]=$name;
				$p["home"]=$dir;
				$t = json_encode($p);
				file_put_contents("$this->instanceName/property.json.php",$t);


				$php = '<?php
import("mvc");

$instance = new MVCInstance("'.$this->instanceName.'");
$instance->setHome("'.$dir.'");
$instance->addUrlPattern("/{APP_HOME}");
$instance->addUrlPattern("/{APP_HOME}/{CONTROLLER}");
$instance->addUrlPattern("/{APP_HOME}/{CONTROLLER}/{METHOD}");
$instance->addUrlPattern("/{APP_HOME}/{CONTROLLER}/{METHOD}/{P1}");
$instance->addUrlPattern("/{APP_HOME}/{CONTROLLER}/{METHOD}/{P1}/{P2}");
';
				file_put_contents("$this->instanceName/run.php",$php);
				$CS->showInfo("Instance created");
			}
			else
			{
				$CS->showWarnning("Instance is exist");
			}
		}
		else if($obj=="application")
		{
			$name = $cmd[2];
			if($this->instanceName=="")
			{
				$CS->showError("No any instance open. Open instance first.");
				return;
			}
			if(!is_dir("$this->instanceName/$name"))
			{
				$appPath = "$this->instanceName/$name";
				$this->applicationName = $name;
				mkdir($appPath);
				mkdir("$appPath/controller");
				mkdir("$appPath/model");
				mkdir("$appPath/template");
				mkdir("$appPath/view");
				$output = array();
				$output["name"] = $name;
				$output["instance"] = $this->instanceName;
				$output["home"] = $this->dir;

				$php = '
$instance->setDetailApplication("'.$name.'");';
				$file = new File("$this->instanceName/run.php");
				$file->append($php);


				//file_put_contents("$this->instanceName/run.php",$php);';
				/*
				$php = '<?php
	import("mvc");
	
	$app = new MVCApplication("'.$this->instanceName.'","'.$this->applicationName.'");
	$app->setHome("'.$this->dir.'");
	
	// $app->enableUserSecurity($user,"<failure url>"); // $user is object of User classs
	// $app->enableSessionSecurity("<sessionid>"); // for session security
	// $app->enableUrlSecurity(); // for url hash security
	
	
	// $app->addPublicMethod("home","init"); // bypass url from url security
	// $app->addAnonymousMethod("home","init"); // bypass url from user security
            
                        
            ';
				file_put_contents("$this->instanceName/app.$this->applicationName.mvc.php",$php);   
				
				*/
				if(isset($cmd[5]))
				{
					$cn = $cmd[5];
					$cn = explode("/",$cn);
					$controllerName = $cn[0];
					$methodName = $cn[1];
					$output["defaultController"] = $controllerName;
					$output["defaultMethod"] = $methodName;
/*
					$php = '
	$app->setDefault("'.$controllerName.'","'.$methodName.'");';
					$file = new File("$this->instanceName/app.$this->applicationName.mvc.php");
					$file->append($php);
*/


$php = '
$instance->setDefaultController("'.$controllerName.'");
$instance->setDefaultMethod("'.$methodName.'");';
				$file = new File("$this->instanceName/run.php");
				$file->append($php);

				
					$php = '<?php
class '.$controllerName.' extends Controller
{
    function __construct()
    {
        parent::__construct();
    }	
    function '.$methodName.'()
    {
        $this->loadView("'.$controllerName.'/'.$methodName.'");
    }
}
?>';
					file_put_contents("$appPath/controller/$controllerName.php",$php);

					$html = '<html>
	<head>
		<title>Yajan MVC Application '.$appName.'</title>
	</head>
	<body>
		<center>Welcome MVC Application '.$appName.'</center>
	</body>
</html>';
					if(!is_dir("$appPath/view/$controllerName"))
					{
						mkdir("$appPath/view/$controllerName");
					}
					file_put_contents("$appPath/view/$controllerName/$methodName.php",$html);
				}

				$json = json_encode($output);
				file_put_contents("$appPath/property.json.php",$json);

				$CS->showInfo("Application created");

			}
			else
			{
				$CS->showWarnning("Application is exist");
			}
		}
		else if($obj=="model")
		{
			if($this->applicationName=="")
			{
				$CS->showError("Application is not open. open application first.");
				return;
			}
			$table = "";
			$name = $cmd[2];
			if(isset($cmd[4]))
			{
				$table = '$this->initTable("'.$cmd[4].'",$this->db);';
			}
			$php = '<?php
class '.$name.' extends Model
{
	function __construct()
	{
		parent::__construct();
		'.$table.'
	}
}			
?>';
			$modulePath = "$this->instanceName/$this->applicationName/model";
			file_put_contents("$modulePath/$name.php",$php);
			$CS->showInfo("Model created.");
		}
		else if($obj=="controller")
		{
			if($this->applicationName=="")
			{
				$CS->showError("Application is not open. open application first.");
				return;
			}
			$name = $cmd[2];
			$path = "$this->instanceName/$this->applicationName/controller";
			$php='<?php
class '.$name.' extends Controller
{
	function __construct()
	{
		parent::__construct();
	}	
///// put your methods here   //////


}
?>';
			file_put_contents("$path/$name.php",$php);
			$CS->showInfo("Controller created.");
		}
		else if($obj=="view")
		{
			if($this->applicationName=="")
			{
				$CS->showError("Application is not open. open application first.");
				return;
			}
			$name = $cmd[2];
			$path = "$this->instanceName/$this->applicationName/view";
			$php='<?php
class '.$name.' extends Controller
{
	function __construct()
	{
		parent::__construct();
	}	
///// put your methods here   //////


}
?>';
			file_put_contents("$path/$name.php",$php);
			$CS->showInfo("View created.");
		}
		else if($obj=="crud")
		{
			if($this->applicationName=="")
			{
				$CS->showError("Application is not open. open application first.");
				return;
			}
			
			$path = "$this->instanceName/$this->applicationName";
			$name = $cmd[2];
			$CS->showInfo("Enter Database connection name: ");
			$dbName = $CS->read();
			$CS->showInfo("Enter table name: ");
			$tableName = $CS->read();
			$table = str_replace("'","",$tableName);
			$table = str_replace("`","",$table);
			$table = str_replace(" ","",$table);

			$db1 = new Connection($dbName);
			$dbt = new DBTable($tableName,$db1);
			$dbt->initDb();
			//print_r($dbt->columns);
			$r = new Recordset();
			$r->addColumns("name");
			for($i=0;$i<count($dbt->columns);$i++)
			{
				$r->add($dbt->columns[$i]->getName());
			}
			$CS->showInfo("Column list of $table table");
			$r->showCLITable();
			$CS->showInfo("Enter primery key column");
			$pKey = $CS->read();
			$pKey = strtoupper($pKey);

			$controller = '<?php
class '.$name.' extends Controller
{
	function __construct()
	{
		parent::__construct();
	}	
	function init()
	{
		$this->loadView("'.$name.'/init");
	}
	function list()
	{
		$jsHome = $this->application->getJsHome();
		$'.$table.' = $this->loadModel("'.$table.'");
		$q=$'.$table.'->query("*","",false);

		$'.$table.'->addColumns("action");
		$'.$table.'->setColumnValue("action","Edit");
		$table = new Table("'.$table.'ListTable");
		$table->setQuery($q,$'.$table.'->db);
		$table->maxRowPerPage(30);
		$table->pageCallback("'.$name.'_dataUpdate");
		$table->showJsPager($jsHome."/'.$name.'/list");

		$l = new Link("");
		$l->setUrl("javascript:");
		$l->addJsEvent("onClick","onClickAction");
		$table->setColumnClass("'.$pKey.'",$l);
		$table->addClass("whiteTable");
		$table->bindWith("'.$pKey.'","'.$pKey.'","'.$table.'_'.$pKey.'");

		$table->rander();
	
	}
	function new()
	{
		$parm = array();
		$parm["'.$table.'"] = $this->loadModel("'.$table.'");
		$this->loadView("'.$name.'/new",$parm);
	}
	function edit()
	{
		$id = $_REQUEST["'.$table.'_'.$pKey.'"];
		$parm = array();
		$parm["'.$table.'"] = $this->loadModel("'.$table.'");
		$parm["'.$table.'"]->query("*",array("'.$pKey.'"=>"$id"),true);
		$this->loadView("'.$name.'/edit",$parm);

	}
	function remove()
	{
		$id = $_REQUEST["'.$table.'_id"];
		$'.$table.' = $this->loadModel("'.$table.'");
		
		
		if($'.$table.'->delete(array("'.$pKey.'"=>"$id"),true,true))
		{
			$output["type"]="ok";
		}
		else
		{
			$output["type"]="error";
			$output["text"]="error in deletion";
		}
		echo json_encode($output);
	}
}
?>';

			file_put_contents("$path/controller/$name.php",$controller);

			$model='<?php
class '.$table.' extends Model
{
	function __construct()
	{
		$db = new Connection("'.$dbName.'");
		parent::__construct($db);
		$this->initTable("'.$tableName.'",$this->db);
	}
	function preInsert(&$data,$column,$db)
	{
		$data["'.$pKey.'"]=$this->getMax("'.$pKey.'");
	}
}			
?>';

			file_put_contents("$path/model/$table.php",$model);

			if(!is_dir("$path/view/$name"))
			{
				mkdir("$path/view/$name");
			}


			$init = '<?php
global $CSS,$JS,$app;
$jsHome = $app->getJsHome("/index.php");
?>
<html>
	<head>
		<title>Yajan MVC Application </title>
		<?php
			$CSS->loadCommonFile();
			$JS->loadCommonFile();
		?>
		<script type="text/javascript">
		HOME="<?=$jsHome?>";
		function onClickAction(obj,e)
		{
			var obj = document.getElementById(obj.id);
			showLink(HOME+"/'.$name.'/edit?'.$table.'_'.$pKey.'="+obj.get("'.$table.'_'.$pKey.'"),"60%","70%");
		}
		function updateList()
		{
			ajax(HOME+"/'.$name.'/list","",function(resp)
			{
				$("#'.$table.'List").html(resp);
			},true);
		}
		function '.$table.'OnClickNew(obj,e)
		{
			showLink(HOME+"/'.$name.'/new","70%","70%");
		}
		function '.$name.'_dataUpdate(resp)
		{
			$("#'.$table.'List").html(resp);
		}
		</script>
		<style>
			body,table{
				font-size: 12px;
				font-family: "verdana";
			}
			a{
				text-decoration: none;
			}
			.whiteTable{
				font-size: 12px;
				margin-bottom:20px;
				border-top:1px dotted #aaaaaa;
				border-left:1px dotted #aaaaaa;
			}
			
			.whiteTable td,th{
				padding:7px;
				border-bottom:1px dotted #aaaaaa;
				border-right:1px dotted #aaaaaa;
				text-align: left;
			}
			.whiteTable tr:hover{
				background-color:#EEE;
			}
			.formTable td{
				padding:10px;
			}
			.tablePagger{
				margin:0 auto;
				list-style:none;
				padding:0px;
			}
			.tablePagger li{
				float:left;
				padding:5px;
				border:1px solid #DDD;
				margin:3px;
			}
			.tablePagger .selectedPage{
				border-bottom:3px solid #F00;
			}
		</style>
	</head>
	<body>
		<h3>'.strtoupper($name).' LIST</h3>
		<div id="'.$table.'List"></div>
		<script type="text/javascript">
		updateList();
		</script>
		<?php
		$b = new Button("'.$table.'New");
		$b->setValue("Add new");
		$b->addJsEvent("onClick","'.$table.'OnClickNew");
		$b->rander();
		?>
	</body>
</html>';


			file_put_contents("$path/view/$name/init.php",$init);
			$edit = '<?php
global $app,$formProcessor;
$jsHome = $app->getJsHome("/index.php");
$target=basename($jsHome);
$form = new Form("'.$table.'Edit");
$form->setUrl("$jsHome/'.$name.'/edit",true);
$form->ajax(true);
$form->ajaxCallback("'.$table.'EditCallback");

if($form->submited())
{
	$data = array();
	';
	for($i=0;$i<count($dbt->columns);$i++)
	{
		$cName = $dbt->columns[$i]->getName();
		$edit.='
		$data["'.$cName.'"]=$_REQUEST["'.$table.'_'.$cName.'"];';
	}
	$edit.='
	$'.$table.'->loadArray($data);
	$output = array();
	if($'.$table.'->update("'.$pKey.'",true,true))
	{
		$output["type"]="ok";
		$output["id"]=$'.$table.'->get("'.$pKey.'");

	}
	else
	{
		$output["type"]="error";
		$output["text"]="error in updation";
	}
	echo json_encode($output);
}
else
{
?>
<script type="text/javascript">
function '.$table.'EditCallback(resp)
{
	try
	{
		var r = JSON.parse(resp);
		if(r.type=="ok")
		{
			$.colorbox.close();
			updateList();
			alert("Entry update");
		}
		else
		{
			alert(r.text);
		}
	}
	catch(ex)
	{
		alert(ex.message+"\n"+resp);
	}
}
function '.$table.'OnClickDelete(obj,e)
{
	<?php
	$id = $'.$table.'->get("'.$pKey.'");
	?>
	if(window.confirm("Are you sure delete this entry?"))
	{
		ajax(HOME+"/'.$name.'/remove","'.$table.'_id=<?=$id?>",function(resp)
		{
			try
			{
				var r = JSON.parse(resp);
				if(r.type=="ok")
				{
					$.colorbox.close();
					updateList();
					alert("Entry deleted");
				}
				else
				{
					alert(r.text);
				}
			}
			catch(ex)
			{
				alert(ex.message+"\n"+resp);
			}
		});
	}
}
</script>
<?php
$form->begin();
$h = new HiddenItem("'.$table.'_'.$pKey.'");
$h->setValue($'.$table.'->get("'.$pKey.'"));
$h->rander();
?>
<h4>Edit '.strtoupper($name).'</h4>

<table class="formTable">';

	for($i=0;$i<count($dbt->columns);$i++)
	{
		$cName = $dbt->columns[$i]->getName();
		if(strtoupper($cName)==$pKey)
		{
			$edit.='
		<tr>
			<td>'.$cName.'</td>
			<td><?=$'.$table.'->get("'.$pKey.'")?></td>
		</tr>';
	
		}
		else
		{
			$edit.='
		<tr>
			<td>'.$cName.'</td>
			<td><?php
				$txt = new TextBox("'.$table.'_'.$cName.'");
				$txt->setValue($'.$table.'->get("'.$cName.'"));
				$txt->rander();
			?></td>
		</tr>';
		
		}
	}
	$edit.='
</table>
<?php
$form->submit("Update");
$b = new Button("'.$table.'Delete");
$b->addJsEvent("onClick","'.$table.'OnClickDelete");
$b->setValue("Delete");
$b->rander();
$form->end();
}
?>';


			file_put_contents("$path/view/$name/edit.php",$edit);

			$new = '<?php
global $app,$formProcessor;
$jsHome = $app->getJsHome("/index.php");
$target=basename($jsHome);
$form = new Form("'.$table.'New");
$form->setUrl("$jsHome/'.$name.'/new",true);
$form->ajax(true);
$form->ajaxCallback("'.$table.'NewCallback");
if($form->submited())
{
	
	$data = array();
	';
	for($i=0;$i<count($dbt->columns);$i++)
	{
		$cName = $dbt->columns[$i]->getName();
		$new.='
		$data["'.$cName.'"]=$_REQUEST["'.$table.'_'.$cName.'"];';
	}
	$new.='
	$'.$table.'->loadArray($data);
	$output = array();
	
	if($'.$table.'->insert(true,true))
	{
		$output["type"]="ok";
		$output["id"]=$'.$table.'->get("'.$pKey.'");

	}
	else
	{
		$output["type"]="error";
		$output["text"]="error in insertion";
	}
	echo json_encode($output);
}
else
{
?>
<script type="text/javascript">
function '.$table.'NewCallback(resp)
{
	console.info(resp);
	try
	{
		var r = JSON.parse(resp);
		if(r.type=="ok")
		{
			$.colorbox.close();
			updateList();
			alert("Entry created");
		}
		else
		{
			alert(r.text);
		}
	}
	catch(ex)
	{
		alert(ex.message+"\n"+resp);
	}
}
</script>
<?php
$form->begin();
?>
<h4>New '.strtoupper($name).'</h4>

<table class="formTable">
';

for($i=0;$i<count($dbt->columns);$i++)
{
	$cName = $dbt->columns[$i]->getName();
	if(strtoupper($cName)==$pKey)
	{
		$new.='
	<tr>
		<td>'.$cName.'</td>
		<td>AUTO</td>
	</tr>';

	}
	else
	{
		$new.='
	<tr>
		<td>'.$cName.'</td>
		<td><?php
			$txt = new TextBox("'.$table.'_'.$cName.'");
			$txt->rander();
		?></td>
	</tr>';
	
	}
}
$new.='
</table>
<?php
$form->submit("Update");
$form->end();
}
?>';

			file_put_contents("$path/view/$name/new.php",$new);

			$CS->showInfo("CRUD $name created.");
		}
	}
	private function _openInstance($name)
	{
		$this->instanceName = $name;
		$filename = "$this->instanceName/property.json.php";
		$t = file_get_contents($filename);
		$p = json_decode($t,true);
		$this->dir =$p["home"];
	}
	function open($cmd)
	{
		global $CS;
		$obj = $cmd[1];
		/*
			open application <applicationname> from <instancename>
		*/
		
		if($obj=="instance")
		{
			$name = $cmd[2];
			if(is_dir($name))
			{
				$this->_openInstance($name);
			}
			else
			{
				$CS->showError("Instance not exist");
			}
		}
		else if($obj=="application")
		{
			
			if(isset($cmd[4]))
			{
				$this->_openInstance($name);
			}
			$name = $cmd[2];
			$n = explode("/",$name);
			if(count($n)>1)
			{
				$this->_openInstance($n[0]);
				$name = $n[1];
			}
			if($this->instanceName=="")
			{
				$CS->showError("Instance is not open. open instance first.");
				return;
			}
			
			if(is_dir("$this->instanceName/$name"))
			{
				$this->applicationName = $name;
				$appPath = "$this->instanceName/$name";
				$filename = "$appPath/property.json.php";
				$t = file_get_contents($filename);
				$output = json_decode($t,true);
				
			}
			else
			{
				$CS->showError("Application not exist");
			}
		}
	}

}
?>
