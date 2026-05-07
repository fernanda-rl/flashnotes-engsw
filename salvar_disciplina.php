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

$usuario_id = $_SESSION['usuario_id'];

// DADOS DO FORMULÁRIO
$disciplina = $_POST['disciplina'];
$horario_inicio = $_POST['horario_inicio'];
$horario_fim = $_POST['horario_fim'];
$dia = $_POST['dia'];
$duracao = $_POST['duracao'];

// INSERT
$sql = "INSERT INTO horarios
(
    usuario_id,
    disciplina,
    horario_inicio,
    horario_fim,
    dia,
    duracao
)
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isssss",
    $usuario_id,
    $disciplina,
    $horario_inicio,
    $horario_fim,
    $dia,
    $duracao
);

$stmt->execute();

// VOLTAR
header("Location: disciplinas.php");
exit();
?>