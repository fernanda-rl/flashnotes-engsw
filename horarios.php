<?php
/**
 * Horários - Flashnotes
 * Página para visualizar o horário semanal de aulas
 */

// Inicia a sessão
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
    header("Location: login.php");
    exit();
}

// =====================================================
// CONEXÃO COM O BANCO
// =====================================================

$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Obtém o ID do usuário logado
$usuario_id = $_SESSION['usuario_id'];

// =====================================================
// ARRAY DOS DIAS DA SEMANA
// =====================================================

$horarios = [
    'Segunda-feira' => [],
    'Terça-feira' => [],
    'Quarta-feira' => [],
    'Quinta-feira' => [],
    'Sexta-feira' => [],
    'Sábado' => [],
    'Domingo' => []
];

// =====================================================
// BUSCAR HORÁRIOS DO BANCO
// =====================================================

$sql = "SELECT disciplina, horario_inicio, horario_fim, dia
        FROM horarios
        WHERE usuario_id = ?
        ORDER BY horario_inicio ASC";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("i", $usuario_id);

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {

        $dia = $row['dia'];

        // Evita erro caso o dia não exista
        if (!isset($horarios[$dia])) {
            $horarios[$dia] = [];
        }

        $horarios[$dia][] = [
            'disciplina' => $row['disciplina'],
            'horario' =>
                substr($row['horario_inicio'], 0, 5)
                . ' - ' .
                substr($row['horario_fim'], 0, 5)
        ];
    }

    $stmt->close();
}

$conn->close();

// =====================================================
// DIAS DA SEMANA
// =====================================================

$dias_semana = [
    'Segunda-feira',
    'Terça-feira',
    'Quarta-feira',
    'Quinta-feira',
    'Sexta-feira',
    'Sábado',
    'Domingo'
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Horários</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/horarios.css">

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

        <div class="cabecalho-horarios">

            <div class="titulo-horarios">

                <img src="icons/relogio.svg" width="24" height="24" alt="Relógio">

                <h1>Horário</h1>

            </div>

        </div>

        <!-- Grade semanal -->
        <div class="grade-semanal">

            <?php foreach ($dias_semana as $dia): ?>

                <div class="coluna-dia">

                    <h2 class="titulo-dia">
                        <?php echo htmlspecialchars($dia); ?>
                    </h2>

                    <div class="lista-horarios">

                        <?php if (count($horarios[$dia]) > 0): ?>

                            <?php foreach ($horarios[$dia] as $aula): ?>

                                <div class="card-horario">

                                    <div class="nome-disciplina">
                                        <?php echo htmlspecialchars($aula['disciplina']); ?>
                                    </div>

                                    <div class="horario-aula">
                                        <?php echo htmlspecialchars($aula['horario']); ?>
                                    </div>

                                </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <div class="card-horario vazio">

                                <div class="nome-disciplina">
                                    Nenhuma aula
                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </main>

</div>

</body>
</html>