<?php

$host = "localhost";
$db = "planilhodb";
$user = "root";
$pass = "mlkzika";

$mysqli = new mysqli($host, $user, $pass, $db);
if($mysqli->connect_errno) {
    die("Falha na conexão com o banco de dados");
}
