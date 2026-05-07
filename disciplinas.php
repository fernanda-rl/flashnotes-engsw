<?php
/**
 * Disciplinas - Flashnotes
 * Página para gerenciar disciplinas (matérias)
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

// ======================
// CONEXÃO COM O BANCO
// ======================

$conn = new mysqli("localhost", "flashuser", "1234", "flashnotes");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$usuario_id = $_SESSION['usuario_id'];

// ARRAY DAS DISCIPLINAS
$disciplinas = [];

// ======================
// BUSCAR DISCIPLINAS
// ======================

$sql = "SELECT * FROM horarios 
        WHERE usuario_id = ? 
        ORDER BY disciplina ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $disciplinas[] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Flashnotes - Disciplinas</title>

    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/disciplinas.css">
</head>

<body>
    <div class="container-dashboard">
        <!-- Sidebar e Barra Superior -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Conteúdo Principal -->
        <main class="conteudo-principal">
            <div class="cabecalho-pagina">
                <div class="titulo-pagina">
                    <img src="icons/caderno.svg" width="24" height="24" alt="Caderno">
                    <div>
                        <h1>Disciplinas</h1>
                        <p>Gerencie suas disciplinas e horários</p>
                    </div>
                </div>
                <button class="botao-adicionar" id="botao-adicionar-disciplina">
                    Adicionar disciplina +
                </button>
            </div>
            
            <!-- Barra de Pesquisa -->
            <div class="barra-pesquisa">
                <input type="text" id="campo-pesquisa" placeholder="PESQUISE POR UMA DISCIPLINA" class="campo-pesquisa">
            </div>
            
            <!-- Grade de Disciplinas -->
            <div class="grade-disciplinas" id="grade-disciplinas">

                <?php foreach ($disciplinas as $disciplina): ?>
                    <div class="card-disciplina" data-id="<?php echo $disciplina['id']; ?>">
                        <h3><?php echo htmlspecialchars($disciplina['disciplina']); ?></h3>
                        <div class="info-disciplina">
                            <p><strong>Horário:</strong>  <?php echo htmlspecialchars(substr($disciplina['horario_inicio'], 0, 5). ' - ' .substr($disciplina['horario_fim'], 0, 5));?>
                            <p><strong>Dia(s):</strong>  <?php echo htmlspecialchars($disciplina['dia']);?>
                            <p><strong>Duração:</strong> <?php echo htmlspecialchars($disciplina['duracao']); ?></p>
                        </div>
                        <div class="acoes-disciplina">
                             <!-- EDITAR -->
                            <button class="botao-editar"
                                onclick='abrirModalEditar(
                                    <?php echo json_encode($disciplina["id"]); ?>,
                                    <?php echo json_encode($disciplina["disciplina"]); ?>,
                                    <?php echo json_encode(substr($disciplina["horario_inicio"], 0, 5)); ?>,
                                    <?php echo json_encode(substr($disciplina["horario_fim"], 0, 5)); ?>,
                                    <?php echo json_encode($disciplina["dia"]); ?>,
                                    <?php echo json_encode($disciplina["duracao"]); ?>
                                    )'
                            >
                                Editar
                            </button>
                            <button class="botao-deletar" 
                                onclick="abrirModalExcluir(
                                    <?php echo $disciplina['id']; ?>,
                                    '<?php echo htmlspecialchars($disciplina['disciplina']); ?>'
                                )"
                            >
                                Deletar
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </main>
    </div>
    
    <!-- Modal Adicionar Disciplina -->
    <div class="modal" id="modal-adicionar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2>Adicionar Disciplina</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-adicionar')">&times;</button>
            </div>
            <form class="formulario-disciplina" action="salvar_disciplina.php" method="POST">
                <div class="grupo-formulario">
                    <label for="nome-disciplina">Nome da Disciplina</label>
                    <input type="text" name="disciplina" placeholder="Ex: Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-inicio">Horário de Início</label>
                    <input type="time" name="horario_inicio" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-termino">Horário de Término</label>
                    <input type="time" name="horario_fim" required>
                </div>
                
                <div class="grupo-formulario">

            <label for="dia-semana">Dia da Semana</label>

                <select id="dia-semana" name="dia" required>

                    <option value="Segunda-feira">
                        Segunda-feira
                    </option>

                    <option value="Terça-feira">
                        Terça-feira
                    </option>

                    <option value="Quarta-feira">
                        Quarta-feira
                    </option>

                    <option value="Quinta-feira">
                        Quinta-feira
                    </option>

                    <option value="Sexta-feira">
                        Sexta-feira
                    </option>

                    <option value="Sábado">
                        Sábado
                    </option>

                    <option value="Domingo">
                        Domingo
                    </option>

                </select>

        </div>
                <div class="grupo-formulario">
                    <label>Duração</label>
                    <input type="text" name="duracao" placeholder="Ex: 1 hora" required>
                </div>
                
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Editar Disciplina -->
    <div class="modal" id="modal-editar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2 id="titulo-editar">Editar Disciplina</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-editar')">&times;</button>
            </div>
            <form class="formulario-disciplina" action="editar_disciplina.php" method="POST">
                <input type="hidden" id="id-disciplina-editar" name="id">

                <div class="grupo-formulario">
                    <label>Nome da Disciplina</label>
                    <input type="text" id="nome-disciplina-editar" name="disciplina" required>
                </div>

                <div class="grupo-formulario">
                    <label>Horário de Início</label>
                    <input type="time" id="horario-inicio-editar" name="horario_inicio" required>
                </div>

                <div class="grupo-formulario">
                    <label>Horário de Término</label>
                    <input type="time" id="horario-fim-editar" name="horario_fim" required>
                </div>

            <div class="grupo-formulario">

                <label>Dia da Semana</label>

                <select id="dia-editar" name="dia" required>

                    <option value="Segunda-feira">
                        Segunda-feira
                    </option>

                    <option value="Terça-feira">
                        Terça-feira
                    </option>

                    <option value="Quarta-feira">
                        Quarta-feira
                    </option>

                    <option value="Quinta-feira">
                        Quinta-feira
                    </option>

                    <option value="Sexta-feira">
                        Sexta-feira
                    </option>

                    <option value="Sábado">
                        Sábado
                    </option>

                    <option value="Domingo">
                        Domingo
                    </option>

                </select>

            </div>
                <div class="grupo-formulario">
                    <label>Duração</label>
                    <input type="text" id="duracao-editar" name="duracao" required>
                </div>
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Excluir Disciplina -->
    <div class="modal" id="modal-excluir">
        <div class="conteudo-modal conteudo-excluir">
            <div class="titulo-excluir">
                <h2 id="nome-disciplina-excluir">Disciplina</h2>
                <h3>Excluir</h3>
            </div>
            <p class="mensagem-excluir">Deseja realmente excluir essa matéria?</p>
            <div class="botoes-excluir">
                <button class="botao-nao" onclick="fecharModal('modal-excluir')">Não</button>
                <form action="excluir_disciplina.php" method="POST">
                    <input type="hidden" id="id-disciplina-excluir" name="id">
                    <button type="submit" class="botao-sim"> Sim </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Overlay para modais -->
    <div class="overlay" id="overlay" onclick="fecharTodosModais()"></div>
    
    <script src="disciplinas.js"></script>
</body>
</html>
