<?php
$srcfile = fopen("update_br_state_latitude_longitude_source.txt", "r") or die("Unable to open file!");
$wfile = fopen("update_br_state_latitude_longitude.sql", "w+");

$find = array("/\'[a-z]{2}\',\s\'[a-záàâãéèêíïóôõöúçñ]+\s?[a-z]*\s?[a-z]*\s?[a-z]*\',\s/i");
fgets($srcfile);//ignore first line
while(!feof($srcfile)) {
	list($latitude, $longitude) = explode(',', explode('@', fgets($srcfile))[1]);
	fwrite($wfile, "UPDATE br_state SET latitude = $latitude, longitude = $longitude WHERE uf = \n");
}
fclose($srcfile);
fclose($wfile);