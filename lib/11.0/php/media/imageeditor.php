<?php

require_once("$LIB_PATH/php/media/".'gd/Exception/ImageWorkshopBaseException.php');
require_once("$LIB_PATH/php/media/".'gd/Exception/ImageWorkshopException.php');
require_once("$LIB_PATH/php/media/".'gd/Core/Exception/ImageWorkshopLayerException.php');
require_once("$LIB_PATH/php/media/".'gd/Core/Exception/ImageWorkshopLibException.php');
require_once("$LIB_PATH/php/media/".'gd/Core/ImageWorkshopLib.php');
require_once("$LIB_PATH/php/media/".'gd/Core/ImageWorkshopLayer.php');
require_once("$LIB_PATH/php/media/".'gd/Core/ImageWorkshop.php');



use PHPImageWorkshop\ImageWorkshop;
class ImageEditor
{
	var $filename;
	var $image;
	var $width;
	var $height;
	var $imageQuality;
	var $font;
	var $fontPath;
	var $opacity;
	var $color;
	var $bgcolor;
	function __construct($name="")
	{
		
		
		$this->imageQuality = 100;
		$this->fontPath="yajan/lib/fonts";
		$this->font = $this->fontPath."/"."arial.ttf";
		$this->color = "ffffff";

		if($name!="")
		{
			$this->open($name);
		}
	}
	function open($filename)
	{
		
		try
		{
		$this->filename = $filename;
		$this->image = ImageWorkshop::initFromPath($this->filename);

		$this->width = $this->image->getWidth();
		$this->height = $this->image->getHeight();
		}
		catch(Exception $ex)
		{
			echo $ex->getMessage();
			$this->image=null;
		}
	}
	function setFont($font)
	{
		$this->font = $this->fontPath."/"."$font.ttf";
	}
	function loadFile($name)
	{
		$this->open($name);
	}
	function addString($str,$fontSize,$color="")
	{
		if($color!="")
		{
			$this->color = $color;
		}
		$this->image = ImageWorkshop::initTextLayer($str, $this->font, $fontSize, $this->color, 0,$this->bgcolor);
		$this->width = $this->image->getWidth();
		$this->height = $this->image->getHeight();
	}
	function addLayer($layer,$offset1,$offset2,$direction)
	{
		$this->image->addLayerOnTop($layer->image, $offset1, $offset2, $direction);
	}
	function loadResource($resource)
	{
		$this->image = ImageWorkshop::initFromResourceVar($resource);
	}
	
	
	function getResource()
	{
		return $this->image->getResult();
	}
	function gifCreater($freams)
	{
		$gc = new GifCreator();
		$gc->create($freams, $gfe->getFrameDurations(), 0);
		return $gc->getGif();
	}
	function setOpacity($opacity)
	{
		$this->opacity = $opacity;
		$this->image->opacity($this->opacity);
	}
	function setBGColor($color)
	{
		$this->bgcolor=$color;
	}
	function setColor($color)
	{
		$this->color= $color;
	}
	function contrast($amt)
	{
		$this->image->applyFilter(IMG_FILTER_CONTRAST, $amt, null, null, null, true);
	}
	function bright($amt)
	{
		$this->image->applyFilter(IMG_FILTER_BRIGHTNESS, $amt, null, null, null, true);
	}
	function grayscale($amt)
	{
		$this->image->applyFilter(IMG_FILTER_GRAYSCALE, $amt, null, null, null, true);
	}
	function negative($amt)
	{
		$this->image->applyFilter(IMG_FILTER_NEGATE, $amt, null, null, null, true);
	}
	function emboss($amt)
	{
		$this->image->applyFilter(IMG_FILTER_EMBOSS, $amt, null, null, null, true);
	}
	function blur($amt)
	{
		$this->image->applyFilter(IMG_FILTER_GAUSSIAN_BLUR, $amt, null, null, null, true);
	}
	function smooth($amt)
	{
		$this->image->applyFilter(IMG_FILTER_SMOOTH, $amt, null, null, null, true);
	}
	function isAnimated()
	{
		return GifFrameExtractor::isAnimatedGif($this->filename);
	}
	function extractFreams()
	{
		$gfe = new GifFrameExtractor();
		return $gfe->extract($this->filename);
	}
	function rotate($dig)
	{
		$this->image->rotate($dig);
	}
	function resize($w,$h=null)
	{
		$v=true;
		if(($w!="" && $h!="") || ($w!=null && $h!=null))
		{
			$v=false;
		}
		$this->image->resizeInPixel($w,$h,$v,0,0,"MM");
	}
	function save($path="",$name="")
	{

		if($path=="")
		{
			$path = dirname ($this->filename);
		}
		if($name=="")
		{
			$name=basename($this->filename);
		}

		$this->image->save($path, $name, true, null, $this->imageQuality);
	}
	function show()
	{
		global $SCRIPT_PATH;
		$id = uniqid();
		$this->save(".","var/$id.jpg");
		echo '<img src="'.$SCRIPT_PATH.'/var/'.$id.'.jpg" />';
	}
}

?>