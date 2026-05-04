<?php
/**
 * Horários - Flashnotes
 * Página para visualizar o horário semanal de aulas
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

// Dados fictícios de horários por dia da semana
$horarios = array(
    'segunda' => array(
        array('disciplina' => 'Física', 'horario' => '07:00 - 08:00'),
        array('disciplina' => 'Educação Física', 'horario' => '08:00 - 09:00'),
        array('disciplina' => 'Álgebra', 'horario' => '09:30 - 10:30'),
        array('disciplina' => 'Química', 'horario' => '10:30 - 11:30'),
    ),
    'terca' => array(
        array('disciplina' => 'Física', 'horario' => '07:00 - 08:00'),
        array('disciplina' => 'Educação Física', 'horario' => '08:00 - 09:00'),
        array('disciplina' => 'Álgebra', 'horario' => '09:30 - 10:30'),
        array('disciplina' => 'Química', 'horario' => '10:30 - 11:30'),
    ),
    'quarta' => array(
        array('disciplina' => 'Física', 'horario' => '07:00 - 08:00'),
        array('disciplina' => 'Educação Física', 'horario' => '08:00 - 09:00'),
        array('disciplina' => 'Álgebra', 'horario' => '09:30 - 10:30'),
        array('disciplina' => 'Química', 'horario' => '10:30 - 11:30'),
    ),
    'quinta' => array(
        array('disciplina' => 'Física', 'horario' => '07:00 - 08:00'),
        array('disciplina' => 'Educação Física', 'horario' => '08:00 - 09:00'),
        array('disciplina' => 'Álgebra', 'horario' => '09:30 - 10:30'),
        array('disciplina' => 'Química', 'horario' => '10:30 - 11:30'),
    ),
    'sexta' => array(
        array('disciplina' => 'Física', 'horario' => '07:00 - 08:00'),
        array('disciplina' => 'Educação Física', 'horario' => '08:00 - 09:00'),
        array('disciplina' => 'Álgebra', 'horario' => '09:30 - 10:30'),
        array('disciplina' => 'Química', 'horario' => '10:30 - 11:30'),
    )
);

$dias_semana = array(
    'segunda' => 'Segunda',
    'terca' => 'Terça',
    'quarta' => 'Quarta',
    'quinta' => 'Quinta',
    'sexta' => 'Sexta'
);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Horários</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/horarios.css">
</head>
<body>
    <div class="container-dashboard">
        <!-- Sidebar e Barra Superior -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Conteúdo Principal -->
        <main class="conteudo-principal">
            <div class="cabecalho-horarios">
                <div class="titulo-horarios">
                    <img src="icons/relogio.svg" width="24" height="24" alt="Relógio">
                    <h1>Horário</h1>
                </div>
            </div>
            
            <!-- Grade Semanal -->
            <div class="grade-semanal">
                <?php foreach ($dias_semana as $chave => $dia): ?>
                    <div class="coluna-dia">
                        <h2 class="titulo-dia"><?php echo $dia; ?></h2>
                        <div class="lista-horarios">
                            <?php foreach ($horarios[$chave] as $aula): ?>
                                <div class="card-horario">
                                    <div class="nome-disciplina">
                                        <?php echo htmlspecialchars($aula['disciplina']); ?>
                                    </div>
                                    <div class="horario-aula">
                                        <?php echo htmlspecialchars($aula['horario']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>
