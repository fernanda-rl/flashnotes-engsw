<?php
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "flashnotes";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>