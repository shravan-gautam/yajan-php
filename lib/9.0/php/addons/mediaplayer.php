<?php
class MediaPlayer
{
	var $id;
	var $source;
	var $type;
	var $poster;
	var $width;
	var $height;
	function MediaPlayer($id)
	{
		$this->id = $id;
		$this->poster = "";
		$this->width = "600";
		$this->height = "400";
	}
	function addSource($source,$type="video/webm")
	{
		$this->source = $source;
		$this->type = $type;
	}
	function rander()
	{
		global $libVersion,$YAJAN_PATH,$SCRIPT_PATH;
		$path = $SCRIPT_PATH.$YAJAN_PATH."";
		$poster = '';
		if($this->poster!="")
		{
			$poster = 'poster="'.$this->poster.'"';
		}
		echo '<link href="video-js.css" rel="stylesheet" type="text/css">
		<script src="video.js"></script>
		<script>
			videojs.options.flash.swf = "video-js.swf";
		</script>
		
		<video id="'.$this->id.'" class="video-js vjs-default-skin" controls preload="none" width="'.$this->width.'" height="'.$this->height.'"
      '.$poster.'
      data-setup="{}">
    <source src="'.$this->source.'" type="'.$this->type.'" />
	<!--
    <track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track>
	
    <track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track>
	-->
		</video>';
	}
}
?>