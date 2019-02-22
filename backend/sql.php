<?php

$user = 'user'; 
$pass = 'passwd';
$port = 10060; 

if 		 ($_GET['domain']=='https://bd_0.domain.ru') { $server='192.168.000.000'; $database = 'BD_0'; }
elseif ($_GET['domain']=='https://bd_1.domain.ru') { $server='192.168.000.001'; $database = 'BD_1'; }
elseif ($_GET['domain']=='https://bd_2.domain.ru') { $server='192.168.000.002'; $database = 'BD_2'; }
else 		{ exit('Wrong domain'); }
try {
	$link = new PDO ("sqlsrv:Server=$server;Database=$database;ConnectionPooling=1","$user","$pass"); 
} catch (PDOException $e) { echo "Failed to get DB handle: ".$e->getMessage(); exit;}

function getSql ($sql) {
	public $link;
	return $link->prepare($sql)->execute(); //$row = $stmt->fetch();print_r($row);
}

?>