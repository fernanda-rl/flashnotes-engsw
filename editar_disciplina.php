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

// DADOS
$id = $_POST['id'];

$disciplina = $_POST['disciplina'];
$horario_inicio = $_POST['horario_inicio'];
$horario_fim = $_POST['horario_fim'];
$dia = $_POST['dia'];
$duracao = $_POST['duracao'];

// UPDATE
$sql = "UPDATE horarios SET
        disciplina = ?,
        horario_inicio = ?,
        horario_fim = ?,
        dia = ?,
        duracao = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssssi",
    $disciplina,
    $horario_inicio,
    $horario_fim,
    $dia,
    $duracao,
    $id
);

$stmt->execute();

// VOLTA
header("Location: disciplinas.php");
exit();
?>