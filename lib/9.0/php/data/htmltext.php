<?php
class HtmlText extends Text
{
	function HtmlText($text="")
	{
		parent::Text($text);
	}
	function highlights($text,$className="HighlightedText")
	{
		$this->str = preg_replace("/($text)/i", '<span class="'.$className.'">$1</span>', $this->str);
	}
	function strip($text="")
	{
		if($text!="")
		{
			$this->str=$text;
		}
		$txt = str_replace("\r\n"," ",$this->str);
		$txt = str_replace("<br />","+++++",$txt);
		$txt = str_replace("<br>","+++++",$txt);
		$txt = strip_tags($txt);
		$txt = str_replace("+++++","<br>",$txt);
		return $txt;
	}

	function toHtmlDom()
	{
		import("web");
		$h =  new Html();
		$h->parse($this->str);
		return $h;
	}
}
?>