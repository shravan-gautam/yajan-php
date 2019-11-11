<?php
require_once("$LIB_PATH/php/cli/color.php");
class CLI
{
	var $colors;
	var $fcolor;
	var $bcolor;
	var $screenwidth;
	var $screenheight;
	var $x;
	var $y;
	function __construct()
	{
		$this->colors = new Colors();
		$this->fcolor="white";
		$this->bcolor="black";
		$this->screenwidth = exec('tput cols');
		$this->screenheight = exec('tput lines');
		
	}
	function clear() 
	{ 
		system("clear"); 
		$this->paint(0, 0, $this->screenwidth, $this->screenheight,$this->bcolor);
	}
	function gotoxy($x,$y)
	{
		$this->x =$x;
		$this->y = $y;
	}
	function setColor($color)
	{
		$this->setFColor($color);
	}
	function setFGColor($color)
	{
		$this->fcolor=$color;
	}
	function setBGColor($color)
	{
		$this->bcolor=$color;
	}
	function password($prompt = "Enter Password ") 
	{
		if (preg_match('/^win/i', PHP_OS)) 
		{
			$vbscript = sys_get_temp_dir() . 'prompt_password.vbs';
			file_put_contents($vbscript, 'wscript.echo(InputBox("'. addslashes($prompt). '", "", "password here"))');
			$command = "cscript //nologo " . escapeshellarg($vbscript);
			$password = rtrim(shell_exec($command));
			unlink($vbscript);
			return $password;
		} 
		else 
		{
			$command = "/usr/bin/env bash -c 'echo OK'";
			if (rtrim(shell_exec($command)) !== 'OK') {
			trigger_error("Can't invoke bash");
			return;
		}
		$command = "/usr/bin/env bash -c 'read -s -p \"". addslashes($prompt). "\" mypassword && echo \$mypassword'";
		$password = rtrim(shell_exec($command));
		echo "\n";
		return $password;
		}
	}	
	function read()
	{
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        return trim($line);
	}
	function anyKey($vals) 
	{
		$anyKey = "";
		while(!in_array($anyKey,$vals)) 
		{
			$anyKey = trim(`read valu;echo \$valu`);
		}
		return $anyKey;
	}
	function echoAT($Col,$Row,$prompt="") 
	{
		// Display prompt at specific screen coords
		$this->x=$Col;
		$this->y=$Row;
		echo "\033[".$Row.";".$Col."H".$prompt;
	} 
	function showError($str)
	{
		$this->setFGColor("red");
		$this->cout($str."\n");
	}
	function showOK($str)
	{
		$this->setFGColor("green");
		$this->cout($str."\n");
	}
	function showInfo($str)
	{
		$this->setFGColor("light_cyan");
		$this->cout($str."\n");
	}
	function showWarnning($str)
	{
		$this->setFGColor("light_purple");
		$this->cout($str."\n");
	}
	function cunfirm($str)
	{
		
		$y = $this->getInput($str." press Y/y for Yes : ");
		if($y=="Y"||$y=="y")
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getInput($str)
	{
		$this->setFGColor("light_green");
		$this->cout($str);
		return $this->read();
	}
	function cout($str)
	{
		echo $this->colors->getColoredString($str, $this->fcolor, $this->bcolor);
	}
	function write($str)
	{
		$this->echoAT($this->x,$this->y,$this->colors->getColoredString($str, $this->fcolor, $this->bcolor));
	}
	function writeXY($str,$x="", $y="")
	{
		if($x!="")
		{
			$this->x=$x;
		}
		if($y!="")
		{
			$this->y=$y;
		}
		$this->echoAT($this->x,$this->y,$this->colors->getColoredString($str, $this->fcolor, $this->bcolor));
	}
	function getch()
	{
		system("stty -icanon");
		return fread(STDIN, 1);
	}
	function erase($x, $y, $length, $height)
	{
		$str = '';
		for($i=0; $i<$length; $i++)
		{ 
			$str .= ' '; 
		}
		for($j=0; $j<$height; $j++) 
		{ 
			$this->writeXY($str,$x, $y+$j); 
		}
	}
	function paint($x, $y, $length, $height,$bg="")
	{
		$str = '';
		$temp = $this->bcolor;
		if($bg!="")
		{
			$this->bcolor=$bg;
		}
		for($i=0; $i<$length; $i++)
		{ 
			$str .= ' '; 
		}
		for($j=0; $j<$height; $j++) 
		{ 
			$this->writeXY($str,$x, $y+$j); 
		}
		$this->color=$temp;
	}
	
	function end()
	{
		$this->gotoxy(0,$this->screenheight);
		$this->write("");
	}
}
?>