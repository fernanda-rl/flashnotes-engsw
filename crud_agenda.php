<?php

session_start();

header('Content-Type: application/json');

include 'conexao.php';

if (!isset($_SESSION['usuario_logado'])) {
    echo json_encode([
        'sucesso' => false
    ]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$acao = $_POST['acao'] ?? '';

/*
|--------------------------------------------------------------------------
| ADICIONAR
|--------------------------------------------------------------------------
*/

if ($acao == 'adicionar') {

    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];

    $sql = "INSERT INTO eventos
            (usuario_id, titulo, data, tipo)
            VALUES (?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "isss",
        $usuario_id,
        $titulo,
        $data,
        $tipo
    );

    if ($stmt->execute()) {

        echo json_encode([
            'sucesso' => true,
            'id' => $conn->insert_id
        ]);

    } else {

        echo json_encode([
            'sucesso' => false
        ]);

    }
}

/*
|--------------------------------------------------------------------------
| EDITAR
|--------------------------------------------------------------------------
*/

if ($acao == 'editar') {

    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $data = $_POST['data'];
    $tipo = $_POST['tipo'];

    $sql = "UPDATE eventos
            SET titulo = ?, data = ?, tipo = ?
            WHERE id = ? AND usuario_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sssii",
        $titulo,
        $data,
        $tipo,
        $id,
        $usuario_id
    );

    $sucesso = $stmt->execute();

    echo json_encode([
        'sucesso' => $sucesso
    ]);
}

/*
|--------------------------------------------------------------------------
| EXCLUIR
|--------------------------------------------------------------------------
*/

if ($acao == 'excluir') {

    $id = $_POST['id'];

    $sql = "DELETE FROM eventos
            WHERE id = ? AND usuario_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ii",
        $id,
        $usuario_id
    );

    $sucesso = $stmt->execute();

    echo json_encode([
        'sucesso' => $sucesso
    ]);
}