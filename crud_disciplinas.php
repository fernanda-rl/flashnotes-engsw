<?php
/**
 * CRUD Disciplinas - Flashnotes
 * Arquivo dedicado ao processamento de operações CRUD (Create, Read, Update, Delete)
 * para a tabela de disciplinas/horários
 */

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// =====================================================
// CONEXÃO COM O BANCO DE DADOS
// =====================================================
$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obtém o ID do usuário da sessão
$usuario_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 1;

// =====================================================
// PROCESSAMENTO DE REQUISIÇÕES (CRUD)
// =====================================================

// Verifica se há uma ação a ser processada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao = $_POST['acao'];

    // =====================================================
    // ADICIONAR DISCIPLINA
    // =====================================================
    if ($acao === 'adicionar') {
        $disciplina = $_POST['disciplina'] ?? '';
        $horario_inicio = $_POST['horario_inicio'] ?? '';
        $horario_fim = $_POST['horario_fim'] ?? '';
        $dia = $_POST['dia'] ?? '';
        $professor = $_POST['professor'] ?? '';

        if (!empty($disciplina) && !empty($horario_inicio) && !empty($horario_fim) && !empty($dia) && !empty($professor)) {
            $sql = "INSERT INTO horarios (usuario_id, disciplina, horario_inicio, horario_fim, dia, professor) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("isssss", $usuario_id, $disciplina, $horario_inicio, $horario_fim, $dia, $professor);
                
                if ($stmt->execute()) {
                    $_SESSION['mensagem'] = "Disciplina adicionada com sucesso!";
                    $_SESSION['tipo_mensagem'] = "sucesso";
                } else {
                    $_SESSION['mensagem'] = "Erro ao adicionar disciplina: " . $stmt->error;
                    $_SESSION['tipo_mensagem'] = "erro";
                }
                
                $stmt->close();
            }
        } else {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos.";
            $_SESSION['tipo_mensagem'] = "erro";
        }
    }

    // =====================================================
    // EDITAR DISCIPLINA
    // =====================================================
    elseif ($acao === 'editar') {
        $id = $_POST['id'] ?? '';
        $disciplina = $_POST['disciplina'] ?? '';
        $horario_inicio = $_POST['horario_inicio'] ?? '';
        $horario_fim = $_POST['horario_fim'] ?? '';
        $dia = $_POST['dia'] ?? '';
        $professor = $_POST['professor'] ?? '';

        if (!empty($id) && !empty($disciplina) && !empty($horario_inicio) && !empty($horario_fim) && !empty($dia) && !empty($professor)) {
            $sql = "UPDATE horarios SET disciplina = ?, horario_inicio = ?, horario_fim = ?, dia = ?, professor = ? 
                    WHERE id = ? AND usuario_id = ?";
            
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("sssssii", $disciplina, $horario_inicio, $horario_fim, $dia, $professor, $id, $usuario_id);
                
                if ($stmt->execute()) {
                    $_SESSION['mensagem'] = "Disciplina atualizada com sucesso!";
                    $_SESSION['tipo_mensagem'] = "sucesso";
                } else {
                    $_SESSION['mensagem'] = "Erro ao atualizar disciplina: " . $stmt->error;
                    $_SESSION['tipo_mensagem'] = "erro";
                }
                
                $stmt->close();
            }
        } else {
            $_SESSION['mensagem'] = "Por favor, preencha todos os campos.";
            $_SESSION['tipo_mensagem'] = "erro";
        }
    }

    // =====================================================
    // EXCLUIR DISCIPLINA
    // =====================================================
    elseif ($acao === 'excluir') {
        $id = $_POST['id'] ?? '';

        if (!empty($id)) {
            $sql = "DELETE FROM horarios WHERE id = ? AND usuario_id = ?";
            
            $stmt = $conn->prepare($sql);
            
            if ($stmt) {
                $stmt->bind_param("ii", $id, $usuario_id);
                
                if ($stmt->execute()) {
                    $_SESSION['mensagem'] = "Disciplina excluída com sucesso!";
                    $_SESSION['tipo_mensagem'] = "sucesso";
                } else {
                    $_SESSION['mensagem'] = "Erro ao excluir disciplina: " . $stmt->error;
                    $_SESSION['tipo_mensagem'] = "erro";
                }
                
                $stmt->close();
            }
        }
    }

    // Fecha a conexão
    $conn->close();

    // Redireciona de volta para a página de disciplinas
    header("Location: disciplinas.php");
    exit();
}

// Se não for POST, apenas fecha a conexão
$conn->close();
?>
