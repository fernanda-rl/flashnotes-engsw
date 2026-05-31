<?php
$host = "localhost";
$usuario = "flashuser";
$senha = "1234";
$banco = "flashnotes";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>