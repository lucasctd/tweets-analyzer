<?php
$srcfile = fopen("insert_cidades.sql", "r") or die("Unable to open file!");
$wfile = fopen("new_insert_cidades.sql", "w+");

$find = array("/\'[a-z]{2}\',\s\'[a-záàâãéèêíïóôõöúçñ]+\s?[a-z]*\s?[a-z]*\s?[a-z]*\',\s/i");

while(!feof($srcfile)) {
	$value = preg_replace($find, "", fgets($srcfile), 1);//remove city code, uf code, uf
	fwrite($wfile, $value);
}
fclose($srcfile);
fclose($wfile);