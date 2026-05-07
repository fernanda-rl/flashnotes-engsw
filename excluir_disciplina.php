<?php
session_start();

// CONEXÃO
$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

// VERIFICA LOGIN
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// ID
$id = $_POST['id'];

// DELETE
$sql = "DELETE FROM horarios WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $id);

$stmt->execute();

// VOLTA
header("Location: disciplinas.php");
exit();
?>