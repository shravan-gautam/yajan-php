<?php
import("security");
require_once("$LIB_PATH/php/data/columnCollection.php");
class Text
{
	var $str;
	private $type;
	private $compression;
	function Text($str="",$type="asci")
	{
		$this->str = $str;
		$this->type = $type;
		$this->compression = 1;
	}
	function trimUnclose()
	{
		$unClose=true;
		for($i=strlen($this->str)-1;$i>0;$i--)
		{
			if($this->str[$i]==">")
			{
				$unClose==false;
				break;
			}
			if($unClose==true && $this->str[$i]=="<")
			{
				$this->str = substr($this->str,0,$i-1);
				break;
			}
		}
	}
	function wordCount()
	{
		return str_word_count($this->str);
	}
	function clearUTF8()
	{

		$str = preg_replace('/[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]'.
 '|[\x00-\x7F][\x80-\xBF]+'.
 '|([\xC0\xC1]|[\xF0-\xFF])[\x80-\xBF]*'.
 '|[\xC2-\xDF]((?![\x80-\xBF])|[\x80-\xBF]{2,})'.
 '|[\xE0-\xEF](([\x80-\xBF](?![\x80-\xBF]))|(?![\x80-\xBF]{2})|[\x80-\xBF]{3,})/S',
 '?',$this->str );
		return new Text($str);
	}
	function substr($p1,$p2=0,$wrap=false)
	{
		$f = $p1;
		$l = $p2;
		$r = false;
		if(gettype($p2)=="boolean")
		{
			$wrap = $p2;
			$f=0;
			$l=$p1;
		}
		if($p2==0)
		{
			$f=0;
			$l=$p1;
		}
		if($l<0)
		{
			$l = 0-$l;
			$f = strlen($this->str)-$f-$l;
			$r=true;
		}
		$str = substr($this->str,$f,$l);
		
		
		if($wrap==true)
		{
		/*
			if($f>0)
			{
				$p = strpos($str,' ');
				$f = $p+1;
			}
		*/
			if(!$r)
			{
				/*
				if($f==0)
				{
					$p = strpos($str,' ');
					$f = $p+1;
					$p = strrpos($str,' ');
					$l = $p;
				}
				else
				{
					$p=strpos($str,' ');
					$f = $p;
					$p = strrpos($str,' ');
					$l = $p-$f;
					
				}
				*/
				$p = strpos($str,' ');
				$f = $p+1;
				$p = strrpos($str,' ');
				$l = $p - $f;
				
			}
			else
			{
				
				//echo $str;
					if(gettype($p2)=="boolean")
					{
						$p2=abs($p1);
						$p1=0;
					}

					if($p1==0 && $p2>0)
					{
						$p = strpos($str,' ');
						$l = strlen($str)-$p;
						$f=$p;
						
					}
					else
					{
						$p = strpos($str,' ');
						$p1 = strrpos($str,' ');
						$f = $p;
						$l = $p1-$f;
					}
				
				
			}
			
			$str = substr($str,$f,$l);
			
		}
		
		return new Text(trim($str));
	}
	function subword($p1,$p2=0,$wrap=false)
	{
		$f = $p1;
		$l = $p2;
		$r = false;
		if(gettype($p2)=="boolean")
		{
			$wrap = $p2;
			$f=0;
			$l=$p1;
		}
		if($p2==0)
		{
			$f=0;
			$l=$p1;
		}
		if($l<0)
		{
			$l = 0-$l;
			$f = strlen($this->str)-$f-$l;
			$r=true;
		}
		
		$arr = explode(" ",$this->str);
		$str = implode(' ', array_slice($arr, $f, $l));

		return new Text(trim($str));
	}	
	function __toString()
	{
		
		return $this->str;
	}
	function write()
	{
		echo $this->str;
	}
	function isUTF8()
	{
		if(strlen($this->str) != strlen(utf8_decode($this->str)))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function length()
	{
		return mb_strlen($this->str);
	}
	function toString()
	{
		return $this->str;
	}
	function toText()
	{
		return new Text($this->str);
	}
	function toColumnCollection($terminator=' ')
	{
		$r = explode($terminator,$this->str);
		$o = array();
		
		for($i=0;$i<count($r);$i++)
		{
			$r[$i]=trim($r[$i]);
			if($r[$i]!="")
			{
				$o[count($o)]=$r[$i];
			}
		}
		$oc = new ColumnCollection();
		for($i=0;$i<count($o);$i++)
		{
			$w = $o[$i];
			
			$s = 0;
			$sz= 0;
			if($i<count($o)-1)
			{
				$s = strpos($this->str,$o[$i+1]);
				$sz = $s - strpos($this->str,$r[$i]);
			}
			else
			{
				$s = 4000;//-strpos($this->str,$r[$i]);
				$sz = $s - strpos($this->str,$r[$i]);
			}
			$c = new RecordsetColumns($o[$i],"varchar2",$s-strpos($this->str,$o[$i]));
			$c->addAttribute("start",strpos($this->str,$o[$i]));
			$c->addAttribute("end",$s);
			$oc->add($c);
		}
		return $oc;
	}
	function toRecordsetRow(ColumnCollection $colConf)
	{
			$out = array();
			for($i=0;$i<$colConf->count();$i++)
			{
				$c = $colConf->getColumn($i);
				
				$str = substr($this->str,$c->getAttribute("start"),$c->getSize());
				
				$str = trim($str);
				$out[$c->getName()]=$str;
			}
			return $out;
	}
	
	function encrypt($salt='')
	{
		import("security");
		$c = new Compression();
		$c->level = $this->compression;
		$e = new Encryption($salt);
		$e->saltIntigration = true;
		//$this->str = base64_encode($e->encrypt($c->compress($this->str)));
		$this->str =  base64_encode($e->encrypt($c->compress($this->str)));
	}
	function decrypt($salt='')
	{
		import("security");
		$c = new Compression();
		$c->level = $this->compression;
		$e = new Encryption($salt);
		$e->saltIntigration = true;
		$this->str = $c->uncompress($e->decrypt(base64_decode($this->str)));
	}
	function isBlackListed()
	{
		global $balckListWord;
		return $balckListWord->check($this->str);
	}
	function changeLanguage($lang="hindi")
	{
		$str = explode(" ",$this->str);
		for($i=0;$i<count($str);$i++)
		{
			
			$t = file_get_contents("http://xlit.quillpad.in/quillpad_backend2/processWordJSON?lang=".$lang."&inString=".$str[$i]."&callback=&scid=0");

			$t = json_decode($t,true);
//			print_r($t);
			$str[$i] =  $t['itrans'];
		}
		$this->str = join(" ",$str);

	}
	function replace($from,$to)
	{
		$this->str = str_replace($from,$to,$this->str);
	}
}
?>
