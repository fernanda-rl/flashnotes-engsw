<?php
session_start();

// 🔒 Proteção de acesso
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit();
}

// 🔌 CONEXÃO
$conn = new mysqli("localhost", "root", "", "flashnotes");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// inicializar como array

$tarefas_pendentes = [];
$proximos_eventos = [];
$horarios = [];

// ======================
// TAREFAS
// ======================

$sql = "SELECT * FROM tarefas WHERE usuario_id = ? ORDER BY vencimento ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $tarefas_pendentes[] = $row;
}

// ======================
// EVENTOS
// ======================

$sql = "SELECT * FROM eventos WHERE usuario_id = ? ORDER BY data ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $proximos_eventos[] = $row;
}

// ======================
// HORÁRIOS
// ======================

$sql = "SELECT * FROM horarios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $horarios[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <div class="container-dashboard">
        <!-- Sidebar e Barra Superior -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Conteúdo Principal -->
        <main class="conteudo-principal">
            <div class="colunas-dashboard">
                
                <!-- Coluna 1: Tarefas Pendentes -->
                <section class="coluna">
                    <div class="cabecalho-coluna">
                        <img src="icons/checklist.svg" width="24" height="24" alt="Checklist">
                        <h2>Tarefas Pendentes</h2>
                    </div>
                    
                    <div class="lista-itens">
                        <?php foreach ($tarefas_pendentes as $tarefa): ?>
                            <div class="item-tarefa">
                                <div class="titulo-tarefa">
                                    <strong><?php echo htmlspecialchars($tarefa['titulo']); ?></strong>
                                </div>
                                <div class="detalhes-tarefa">
                                    <span class="vencimento">Vence: <?php echo date('d/m/Y', strtotime($tarefa['vencimento'])); ?></span>
                                    <span class="prioridade prioridade-<?php echo strtolower(str_replace('é','e',$tarefa['prioridade'])); ?>">
                                        <?php echo htmlspecialchars($tarefa['prioridade']); ?>
                                    </span>
                                </div>
                                <div class="status-tarefa">
                                    <?php echo htmlspecialchars($tarefa['status']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <!-- Coluna 2: Próximos Eventos -->
                <section class="coluna">
                    <div class="cabecalho-coluna">
                        <img src="icons/calendario4dias.svg" width="24" height="24" alt="Calendario">
                        <h2>Próximos Eventos</h2>
                    </div>
                    
                    <div class="lista-itens">
                        <?php foreach ($proximos_eventos as $evento): ?>
                            <div class="item-evento">
                                <div class="marcador-evento"></div>
                                <div class="info-evento">
                                    <strong><?php echo htmlspecialchars($evento['titulo']); ?></strong>
                                    <span class="data-evento">Data: <?php echo date('d/m/Y', strtotime($evento['data'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
                <!-- Coluna 3: Horário -->
                <section class="coluna">
                    <div class="cabecalho-coluna">
                        <img src="icons/relogio.svg" width="24" height="24" alt="Relógio">
                        <h2>Horário</h2>
                    </div>
                    
                    <div class="lista-itens">
                        <?php foreach ($horarios as $horario): ?>
                            <div class="item-horario">
                                <div class="disciplina-horario">
                                    <strong><?php echo htmlspecialchars($horario['disciplina']); ?></strong>
                                </div>
                                <div class="tempo-horario">
                                    <?php echo htmlspecialchars($horario['horario']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
                
            </div>
        </main>
    </div>
</body>
</html>
