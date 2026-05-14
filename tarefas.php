<?php
/**
 * Tarefas - Flashnotes
 * Página para gerenciar tarefas
 */

session_start();

// Verifica login
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// =====================================================
// CONEXÃO COM BANCO
// =====================================================

$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Usuário logado
$usuario_id = $_SESSION['usuario_id'];

// =====================================================
// BUSCAR TAREFAS
// =====================================================

$tarefas = [];

$sql = "SELECT *
        FROM tarefas
        WHERE usuario_id = ?
        ORDER BY vencimento ASC";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("i", $usuario_id);

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        // Define cor da prioridade
        $cor = '#22C55E';

        if ($row['prioridade'] == 'Alta') {
            $cor = '#FF4444';
        }

        elseif ($row['prioridade'] == 'Média') {
            $cor = '#FFD700';
        }

        elseif ($row['prioridade'] == 'Baixa') {
            $cor = '#3B82F6';
        }

        $tarefas[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'vencimento' => date('Y-m-d', strtotime($row['vencimento'])),
            'vencimento_formatado' => date('d/m/y', strtotime($row['vencimento'])),
            'prioridade' => $row['prioridade'],
            'status' => $row['status'],
            'cor' => $cor
        ];
    }

    $stmt->close();
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Flashnotes - Tarefas</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/tarefas.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">

</head>

<body>

<div class="container-dashboard">

    <!-- Sidebar -->
    <?php include 'sidebar.php'; ?>

    <!-- Conteúdo principal -->
    <main class="conteudo-principal">

        <!-- Cabeçalho -->
        <div class="cabecalho-tarefas">

            <div class="titulo-tarefas">

                <img src="icons/checklist.svg"
                     width="24"
                     height="24"
                     alt="Checklist">

                <h1>Tarefas</h1>

            </div>

            <button class="botao-adicionar"
                    id="botao-adicionar-tarefa">

                Adicionar tarefa +

            </button>

        </div>

        <!-- Grade de tarefas -->
        <div class="grade-tarefas" id="grade-tarefas">

            <?php if (count($tarefas) > 0): ?>

                <?php foreach ($tarefas as $tarefa): ?>

                    <div class="card-tarefa"
                         data-id="<?php echo $tarefa['id']; ?>">

                        <!-- Indicador -->
                        <div class="indicador-prioridade"
                             style="background-color: <?php echo $tarefa['cor']; ?>;">
                        </div>

                        <!-- Conteúdo -->
                        <div class="conteudo-tarefa">

                            <h3>
                                <?php echo htmlspecialchars($tarefa['titulo']); ?>
                            </h3>

                            <div class="info-tarefa">

                                <p>
                                    <strong>Vence:</strong>

                                    <?php echo htmlspecialchars($tarefa['vencimento_formatado']); ?>
                                </p>

                                <p>
                                    <strong>Prioridade:</strong>

                                    <?php echo htmlspecialchars($tarefa['prioridade']); ?>
                                </p>

                                <p>
                                    <strong>Status:</strong>

                                    <?php echo htmlspecialchars($tarefa['status']); ?>
                                </p>

                            </div>

                        </div>

                        <!-- Ações -->
                        <div class="acoes-tarefa">

                            <!-- Editar -->
                            <button class="botao-editar"

                            onclick='abrirModalEditar(
                            <?php echo $tarefa["id"]; ?>,
                            <?php echo json_encode($tarefa["titulo"]); ?>,
                            <?php echo json_encode($tarefa["vencimento"]); ?>,
                            <?php echo json_encode($tarefa["prioridade"]); ?>
                            )'>

                                Editar

                            </button>

                            <!-- Excluir -->
                            <button class="botao-deletar"

                            onclick='abrirModalExcluir(
                            <?php echo $tarefa["id"]; ?>,
                            <?php echo json_encode($tarefa["titulo"]); ?>
                            )'>

                                Excluir

                            </button>

                        </div>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="mensagem-vazia">

                    <p>
                        Nenhuma tarefa cadastrada.
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </main>

</div>

<!-- ===================================================== -->
<!-- MODAL ADICIONAR -->
<!-- ===================================================== -->

<div class="modal" id="modal-adicionar">

    <div class="conteudo-modal">

        <div class="cabecalho-modal">

            <h2>Adicionar Tarefa</h2>

            <button class="botao-fechar"
                    onclick="fecharModal('modal-adicionar')">

                &times;

            </button>

        </div>

        <form class="formulario-tarefa"
              method="POST"
              action="crud_tarefas.php">

            <input type="hidden"
                   name="acao"
                   value="adicionar">

            <div class="grupo-formulario">

                <label for="titulo-tarefa">
                    Título da tarefa
                </label>

                <input type="text"
                       id="titulo-tarefa"
                       name="titulo"
                       required>

            </div>

            <div class="grupo-formulario">

                <label for="vencimento-tarefa">
                    Data de vencimento
                </label>

                <input type="date"
                       id="vencimento-tarefa"
                       name="vencimento"
                       required>

            </div>

            <div class="grupo-formulario">

                <label for="prioridade-tarefa">
                    Prioridade
                </label>

                <select id="prioridade-tarefa"
                        name="prioridade"
                        required>

                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>

                </select>

            </div>

            <div class="grupo-formulario">

                <label for="status-tarefa">
                    Status
                </label>

                <select id="status-tarefa"
                        name="status"
                        required>

                    <option value="Não iniciado">
                        Não iniciado
                    </option>

                    <option value="Em progresso">
                        Em progresso
                    </option>

                    <option value="Concluído">
                        Concluído
                    </option>

                </select>

            </div>

            <button type="submit"
                    class="botao-salvar">

                Salvar

            </button>

        </form>

    </div>

</div>

<!-- ===================================================== -->
<!-- MODAL EDITAR -->
<!-- ===================================================== -->

<div class="modal" id="modal-editar">

    <div class="conteudo-modal">

        <div class="cabecalho-modal">

            <h2>Editar tarefa</h2>

            <button class="botao-fechar"
                    onclick="fecharModal('modal-editar')">

                &times;

            </button>

        </div>

        <form class="formulario-tarefa"
              method="POST"
              action="crud_tarefas.php">

            <input type="hidden"
                   name="acao"
                   value="editar">

            <input type="hidden"
                   id="id-tarefa-editar"
                   name="id">

            <div class="grupo-formulario">

                <label for="titulo-tarefa-editar">
                    Título da tarefa
                </label>

                <input type="text"
                       id="titulo-tarefa-editar"
                       name="titulo"
                       required>

            </div>

            <div class="grupo-formulario">

                <label for="vencimento-tarefa-editar">
                    Data de vencimento
                </label>

                <input type="date"
                       id="vencimento-tarefa-editar"
                       name="vencimento"
                       required>

            </div>

            <div class="grupo-formulario">

                <label for="prioridade-tarefa-editar">
                    Prioridade
                </label>

                <select id="prioridade-tarefa-editar"
                        name="prioridade"
                        required>

                    <option value="Baixa">Baixa</option>
                    <option value="Média">Média</option>
                    <option value="Alta">Alta</option>

                </select>

            </div>

            <div class="grupo-formulario">

                <label for="status-tarefa-editar">
                    Status
                </label>

                <select id="status-tarefa-editar"
                        name="status"
                        required>

                    <option value="Não iniciado">
                        Não iniciado
                    </option>

                    <option value="Em progresso">
                        Em progresso
                    </option>

                    <option value="Concluído">
                        Concluído
                    </option>

                </select>

            </div>

            <button type="submit"
                    class="botao-salvar">

                Salvar

            </button>

        </form>

    </div>

</div>

<!-- ===================================================== -->
<!-- MODAL EXCLUIR -->
<!-- ===================================================== -->

<div class="modal" id="modal-excluir">

    <div class="conteudo-modal conteudo-excluir">

        <div class="titulo-excluir">

            <h2 id="titulo-tarefa-excluir">
                Tarefa
            </h2>

            <h3>Excluir</h3>

        </div>

        <p class="mensagem-excluir">

            Deseja realmente excluir essa tarefa?

        </p>

        <div class="botoes-excluir">

            <button class="botao-nao"
                    onclick="fecharModal('modal-excluir')">

                Não

            </button>

            <form method="POST"
                  action="crud_tarefas.php">

                <input type="hidden"
                       name="acao"
                       value="excluir">

                <input type="hidden"
                       id="id-tarefa-excluir"
                       name="id">

                <button type="submit"
                        class="botao-sim">

                    Sim

                </button>

            </form>

        </div>

    </div>

</div>

<!-- Overlay -->
<div class="overlay"
     id="overlay"
     onclick="fecharTodosModais()"></div>

<script src="tarefas.js"></script>

</body>
</html>