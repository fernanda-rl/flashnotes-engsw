<?php
/**
 * Dashboard - Flashnotes
 * Página principal do usuário logado
 * Verifica se o usuário está autenticado antes de exibir o conteúdo
 */

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: login.php");
    exit();
}

// Dados fictícios para as tarefas pendentes
$tarefas_pendentes = array(
    array(
        'titulo' => 'Trabalho Ciências',
        'vencimento' => '08/04/26',
        'prioridade' => 'Alta',
        'status' => 'Não iniciado'
    ),
    array(
        'titulo' => 'Trabalho Matemática',
        'vencimento' => '07/04/26',
        'prioridade' => 'Média',
        'status' => 'Em progresso'
    ),
    array(
        'titulo' => 'Trabalho Ciências',
        'vencimento' => '08/04/26',
        'prioridade' => 'Baixa',
        'status' => 'Concluído'
    ),
    array(
        'titulo' => 'Trabalho Português',
        'vencimento' => '09/04/26',
        'prioridade' => 'Alta',
        'status' => 'Não iniciado'
    )
);

// Dados fictícios para os próximos eventos
$proximos_eventos = array(
    array(
        'titulo' => 'Prova Física',
        'data' => '08/04/26',
        'tipo' => 'Prova'
    ),
    array(
        'titulo' => 'Apresentação',
        'data' => '08/04/26',
        'tipo' => 'Apresentação'
    ),
    array(
        'titulo' => 'Prova Química',
        'data' => '07/04/26',
        'tipo' => 'Prova'
    ),
    array(
        'titulo' => 'Prova Português',
        'data' => '08/04/26',
        'tipo' => 'Prova'
    ),
    array(
        'titulo' => 'Prova Literatura',
        'data' => '09/04/26',
        'tipo' => 'Prova'
    ),
    array(
        'titulo' => 'Prova Álgebra',
        'data' => '10/04/26',
        'tipo' => 'Prova'
    )
);

// Dados fictícios para o horário
$horarios = array(
    array(
        'disciplina' => 'Física',
        'horario' => '07:00 - 08:00'
    ),
    array(
        'disciplina' => 'Educação Física',
        'horario' => '08:00 - 09:00'
    ),
    array(
        'disciplina' => 'Álgebra',
        'horario' => '09:30 - 10:30'
    ),
    array(
        'disciplina' => 'Química',
        'horario' => '10:30 - 11:30'
    )
);
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
                                    <span class="vencimento">Vence: <?php echo htmlspecialchars($tarefa['vencimento']); ?></span>
                                    <span class="prioridade prioridade-<?php echo strtolower($tarefa['prioridade']); ?>">
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
                                    <span class="data-evento">Data: <?php echo htmlspecialchars($evento['data']); ?></span>
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
