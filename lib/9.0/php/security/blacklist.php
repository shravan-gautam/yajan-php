<?php
class BlackList
{
	private $list;
	function BlackList()
	{
		$this->list = array();
	}
	function add($text)
	{
		$this->list[count($this->list)]=$text;
	}
	function check($text)
	{
		if( !preg_match( '/(\b' . implode( '\b|\b', $this->list ) . '\b)/i', $text ))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
}
global $balckListWord;
$balckListWord = new BlackList();
$balckListWord->add('advertisements');
$balckListWord->add('affects');
$balckListWord->add('aian');
$balckListWord->add('alicia');
$balckListWord->add('amatuer');
$balckListWord->add('amature');
$balckListWord->add('anal');
$balckListWord->add('angelic');
$balckListWord->add('anna');
$balckListWord->add('anniston');
$balckListWord->add('apartment');
$balckListWord->add('arab');
$balckListWord->add('atlanta');
$balckListWord->add('augmentation');
$balckListWord->add('bear');
$balckListWord->add('bentleypc');
$balckListWord->add('black');
$balckListWord->add('breast');
$balckListWord->add('carrollton');
$balckListWord->add('cart');
$balckListWord->add('chicks');
$balckListWord->add('cock');
$balckListWord->add('counseling');
$balckListWord->add('cumming');
$balckListWord->add('design');
$balckListWord->add('dick');
$balckListWord->add('douglasville');
$balckListWord->add('erotic');
$balckListWord->add('escort');
$balckListWord->add('escorts');
$balckListWord->add('european');
$balckListWord->add('experts');
$balckListWord->add('facial');
$balckListWord->add('french');
$balckListWord->add('fresh');
$balckListWord->add('gay');
$balckListWord->add('georgia');
$balckListWord->add('graphic');
$balckListWord->add('guinea');
$balckListWord->add('horny');
$balckListWord->add('http');
$balckListWord->add('island');
$balckListWord->add('islands');
$balckListWord->add('love');
$balckListWord->add('nude');
$balckListWord->add('orgasm');
$balckListWord->add('oscommerce');
$balckListWord->add('photo');
$balckListWord->add('photos');
$balckListWord->add('pic');
$balckListWord->add('porn');
$balckListWord->add('product');
$balckListWord->add('pussy');
$balckListWord->add('repair');
$balckListWord->add('republic');
$balckListWord->add('reviews');
$balckListWord->add('rica');
$balckListWord->add('saint');
$balckListWord->add('sex');
$balckListWord->add('shoot');
$balckListWord->add('story');
$balckListWord->add('strip');
$balckListWord->add('teen');
$balckListWord->add('torrent');
$balckListWord->add('united');
$balckListWord->add('virgin');
$balckListWord->add('virus');
$balckListWord->add('wife');

?>