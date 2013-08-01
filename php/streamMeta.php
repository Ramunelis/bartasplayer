<?php

//print(time());

$url = "stream.franclr.fr";
$mountPoint = "/radiobartas";

$title = "Sans Titre";

//header("Content-type: audio/mpeg");

$fp = fsockopen($url, 8000, $errno, $errstr, 30);
if ($fp == false) {
	//echo "$errstr ($errno)";
} else {

	fputs($fp, "GET / HTTP/1.1\r\n");
	fputs($fp, "User-Agnet: WinampMPEG/2.9\r\n");
	fputs($fp, "Accept: */*\r\n");
	fputs($fp, "Icy-MetaData:1\r\n");
	fputs($fp, "Connection: Close\r\n\r\n");

	$infos = "";

	while (!feof($fp)) {
		$infos .= fread($fp, 1024 / 16);
		
	}
	fclose($fp);

	$mountPoints = preg_split("#Mount Point /#", $infos);

	//Recherche les infos de Radio Bartas
	foreach ($mountPoints as $value) {
		if (strncmp($value, "radiobartas", 10) == 0)
			$mountPoint = $value;
	}

	//preg_match("#.*Mount Point /radiobartas(.*)MountPoint.*#", $infos, $matches, PREG_OFFSET_CAPTURE);

	//Supprime les tags html
	$mountPoint = strip_tags($mountPoint);

	//Supprime les espaces superflus
	$mountPoint = preg_replace('/\:\n/', " : ", $mountPoint); 
	$mountPoint = preg_replace('/\s\s+/', "\n", $mountPoint); 

	$array = explode("\n", $mountPoint);

	$result = array();

	foreach ($array as $key => $value) {
		$temp = explode(" : ", $value);
		if(count($temp) == 2)
			$result[$temp[0]] = $temp[1];
	}

	/*echo "<pre>";
	var_dump($result);
	echo "</pre>";*/

	print($result["Current Song"]);



}

?>