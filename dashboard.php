<?php
session_start();

// Proteção de acesso
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php");
    exit();
}

// CONEXÃO
$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

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

$sql = "SELECT *
        FROM tarefas
        WHERE usuario_id = ?
        AND status != 'Concluído'
        ORDER BY
            CASE
                WHEN status = 'Não iniciado' THEN 1
                WHEN status = 'Em progresso' THEN 2
                ELSE 3
            END,
            vencimento ASC";
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Pacifico&display=swap" rel="stylesheet">
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
                    <?php
                    $corPrioridade = '#22C55E';
                    if ($tarefa['prioridade'] == 'Alta') {
                        $corPrioridade = '#FF4444';
                    }
                    elseif ($tarefa['prioridade'] == 'Média') {
                        $corPrioridade = '#FFD700';
                    }
                    elseif ($tarefa['prioridade'] == 'Baixa') {
                        $corPrioridade = '#3B82F6';
                    }
                    ?>
                    <div class="item-tarefa">
                        <div class="titulo-tarefa">
                            <strong>
                                <?php echo htmlspecialchars($tarefa['titulo']); ?>
                            </strong>
                        </div>

                        <div class="detalhes-tarefa">
                            <span class="vencimento">
                                Vence:
                                <?php echo date('d/m/Y', strtotime($tarefa['vencimento'])); ?>
                            </span>

                            <span class="prioridade"
                                style="background-color: <?php echo $corPrioridade; ?>;">
                                <?php echo htmlspecialchars($tarefa['prioridade']); ?>
                            </span>

                        </div>

                        <div class="status-tarefa
                            status-<?php echo strtolower(str_replace(' ', '-', $tarefa['status'])); ?>">
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
                <?php
                date_default_timezone_set('America/Sao_Paulo');

                $diasSemana = [
                    'Sunday' => 'Domingo',
                    'Monday' => 'Segunda-feira',
                    'Tuesday' => 'Terça-feira',
                    'Wednesday' => 'Quarta-feira',
                    'Thursday' => 'Quinta-feira',
                    'Friday' => 'Sexta-feira',
                    'Saturday' => 'Sábado'
                ];

                $diaHoje = $diasSemana[date('l')];

                $sql = "SELECT * FROM horarios
                        WHERE usuario_id = ?
                        AND dia = ?
                        ORDER BY horario_inicio ASC";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("is", $usuario_id, $diaHoje);
                $stmt->execute();

                $resultado = $stmt->get_result();

                $horarios = [];

                while ($row = $resultado->fetch_assoc()) {
                    $horarios[] = $row;
                }

                ?>
                <section class="coluna">
                    <div class="cabecalho-coluna">
                        <img src="icons/relogio.svg" width="24" height="24" alt="Relógio">
                        <h2>Horário</h2>
                    </div>
                    
                    <div class="lista-itens">
                        <?php if (count($horarios) > 0): ?>

                                <?php foreach ($horarios as $horario): ?>
                                    
                                    <div class="item-horario">

                                        <div class="disciplina-horario">
                                            <strong>
                                                <?php echo htmlspecialchars($horario['disciplina']); ?>
                                            </strong>
                                        </div>

                                        <div class="tempo-horario">
                                            <?php echo date('H:i', strtotime($horario['horario_inicio'])); ?>
                                            -
                                            <?php echo date('H:i', strtotime($horario['horario_fim'])); ?>
                                        </div>

                                        <div class="dia-horario">
                                            <?php echo htmlspecialchars($horario['dia']); ?>
                                        </div>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <div class="item-horario">
                                    <div class="disciplina-horario">
                                        <strong>Nenhuma aula hoje</strong>
                                    </div>

                                    <div class="tempo-horario">
                                        Aproveite seu dia ✨
                                    </div>
                                </div>

                            <?php endif; ?>
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
