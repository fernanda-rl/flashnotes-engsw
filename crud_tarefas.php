<?php

session_start();

// =====================================================
// VERIFICA LOGIN
// =====================================================

if (
    !isset($_SESSION['usuario_logado']) ||
    $_SESSION['usuario_logado'] !== true
) {
    header("Location: login.php");
    exit();
}

// =====================================================
// CONEXÃO COM BANCO
// =====================================================

$conn = new mysqli(
    "localhost",
    "flashuser",
    "1234",
    "flashnotes"
);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// =====================================================
// USUÁRIO LOGADO
// =====================================================

$usuario_id = $_SESSION['usuario_id'];

// =====================================================
// PROCESSAR AÇÕES
// =====================================================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['acao'])
) {

    $acao = $_POST['acao'];

    // =================================================
    // ADICIONAR TAREFA
    // =================================================

    if ($acao === 'adicionar') {

        $titulo = trim($_POST['titulo'] ?? '');

        $vencimento = trim($_POST['vencimento'] ?? '');

        $prioridade = trim($_POST['prioridade'] ?? '');

        $status = trim($_POST['status'] ?? 'Não iniciado');

        if (
            !empty($titulo) &&
            !empty($vencimento) &&
            !empty($prioridade) &&
            !empty($status)
        ) {

            $sql = "
                INSERT INTO tarefas
                (
                    usuario_id,
                    titulo,
                    vencimento,
                    prioridade,
                    status
                )
                VALUES (?, ?, ?, ?, ?)
            ";

            $stmt = $conn->prepare($sql);

            if ($stmt) {

                $stmt->bind_param(
                    "issss",
                    $usuario_id,
                    $titulo,
                    $vencimento,
                    $prioridade,
                    $status
                );

                $stmt->execute();

                $stmt->close();
            }
        }
    }

    // =================================================
    // EDITAR TAREFA
    // =================================================

    elseif ($acao === 'editar') {

        $id = intval($_POST['id'] ?? 0);

        $titulo = trim($_POST['titulo'] ?? '');

        $vencimento = trim($_POST['vencimento'] ?? '');

        $prioridade = trim($_POST['prioridade'] ?? '');

        $status = trim($_POST['status'] ?? '');

        if (
            $id > 0 &&
            !empty($titulo) &&
            !empty($vencimento) &&
            !empty($prioridade) &&
            !empty($status)
        ) {

            $sql = "
                UPDATE tarefas
                SET
                    titulo = ?,
                    vencimento = ?,
                    prioridade = ?,
                    status = ?
                WHERE id = ?
                AND usuario_id = ?
            ";

            $stmt = $conn->prepare($sql);

            if ($stmt) {

                $stmt->bind_param(
                    "ssssii",
                    $titulo,
                    $vencimento,
                    $prioridade,
                    $status,
                    $id,
                    $usuario_id
                );

                $stmt->execute();

                $stmt->close();
            }
        }
    }

    // =================================================
    // EXCLUIR TAREFA
    // =================================================

    elseif ($acao === 'excluir') {

        $id = intval($_POST['id'] ?? 0);

        if ($id > 0) {

            $sql = "
                DELETE FROM tarefas
                WHERE id = ?
                AND usuario_id = ?
            ";

            $stmt = $conn->prepare($sql);

            if ($stmt) {

                $stmt->bind_param(
                    "ii",
                    $id,
                    $usuario_id
                );

                $stmt->execute();

                $stmt->close();
            }
        }
    }
}

// =====================================================
// FECHA CONEXÃO
// =====================================================

$conn->close();

// =====================================================
// REDIRECIONA
// =====================================================

header("Location: tarefas.php");
exit();

?>