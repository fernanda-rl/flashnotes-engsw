<?php
/**
 * Disciplinas - Flashnotes
 * Página para exibir e gerenciar disciplinas (matérias)
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
// BUSCAR DISCIPLINAS DO BANCO DE DADOS
// =====================================================
$disciplinas = array();

$sql = "SELECT id, disciplina, horario_inicio, horario_fim, dia, professor FROM horarios 
        WHERE usuario_id = ? 
        ORDER BY dia ASC, horario_inicio ASC";

$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $disciplinas[] = $row;
    }

    $stmt->close();
}

// Fecha a conexão
$conn->close();

// =====================================================
// RECUPERAR MENSAGENS DE FEEDBACK
// =====================================================
$mensagem = '';
$tipo_mensagem = '';

if (isset($_SESSION['mensagem'])) {
    $mensagem = $_SESSION['mensagem'];
    $tipo_mensagem = $_SESSION['tipo_mensagem'];
    
    // Limpa as mensagens da sessão após exibir
    unset($_SESSION['mensagem']);
    unset($_SESSION['tipo_mensagem']);
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
            <!-- Mensagem de Feedback -->
            <?php if (!empty($mensagem)): ?>
                <div class="mensagem-feedback mensagem-<?php echo $tipo_mensagem; ?>">
                    <p><?php echo htmlspecialchars($mensagem); ?></p>
                    <button onclick="this.parentElement.style.display='none';" class="botao-fechar-mensagem">&times;</button>
                </div>
            <?php endif; ?>

            <div class="cabecalho-pagina">
                <div class="titulo-pagina">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
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
                <input type="text" id="campo-pesquisa" placeholder="PESQUISE AQUI A DISCIPLINA" class="campo-pesquisa">
            </div>
            
            <!-- Grade de Disciplinas -->
            <div class="grade-disciplinas" id="grade-disciplinas">
                <?php if (count($disciplinas) > 0): ?>
                    <?php foreach ($disciplinas as $disciplina): ?>
                        <div class="card-disciplina" data-id="<?php echo $disciplina['id']; ?>">
                            <h3><?php echo htmlspecialchars($disciplina['disciplina']); ?></h3>
                            <div class="info-disciplina">
                                <p><strong>Horário:</strong> <?php echo htmlspecialchars($disciplina['horario_inicio'] . ' - ' . $disciplina['horario_fim']); ?></p>
                                <p><strong>Dia(s):</strong> <?php echo htmlspecialchars($disciplina['dia']); ?></p>
                                <p><strong>Professor:</strong> <?php echo htmlspecialchars($disciplina['professor']); ?></p>
                            </div>
                            <div class="acoes-disciplina">
                                <button class="botao-editar" onclick="abrirModalEditar(<?php echo $disciplina['id']; ?>, '<?php echo htmlspecialchars($disciplina['disciplina']); ?>', '<?php echo $disciplina['horario_inicio']; ?>', '<?php echo $disciplina['horario_fim']; ?>', '<?php echo htmlspecialchars($disciplina['dia']); ?>', '<?php echo htmlspecialchars($disciplina['professor']); ?>')">
                                    Editar
                                </button>
                                <button class="botao-deletar" onclick="abrirModalExcluir(<?php echo $disciplina['id']; ?>, '<?php echo htmlspecialchars($disciplina['disciplina']); ?>')">
                                    Deletar
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="mensagem-vazia">
                        <p>Nenhuma disciplina cadastrada. Comece adicionando uma!</p>
                    </div>
                <?php endif; ?>
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
            <form class="formulario-disciplina" method="POST" action="crud_disciplinas.php">
                <input type="hidden" name="acao" value="adicionar">
                
                <div class="grupo-formulario">
                    <label for="nome-disciplina">Nome da Disciplina</label>
                    <input type="text" id="nome-disciplina" name="disciplina" placeholder="Ex: Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-inicio">Horário de Início</label>
                    <input type="time" id="horario-inicio" name="horario_inicio" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-termino">Horário de Término</label>
                    <input type="time" id="horario-termino" name="horario_fim" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="dia-semana">Dia da Semana</label>
                    <select id="dia-semana" name="dia" required>
                        <option value="">Selecione um dia</option>
                        <option value="Segunda">Segunda-feira</option>
                        <option value="Terça">Terça-feira</option>
                        <option value="Quarta">Quarta-feira</option>
                        <option value="Quinta">Quinta-feira</option>
                        <option value="Sexta">Sexta-feira</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>
                
                <div class="grupo-formulario">
                    <label for="professor">Nome do Professor</label>
                    <input type="text" id="professor" name="professor" placeholder="Ex: Prof. João Silva" required>
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
            <form class="formulario-disciplina" method="POST" action="crud_disciplinas.php">
                <input type="hidden" name="acao" value="editar">
                <input type="hidden" id="id-disciplina-editar" name="id">
                
                <div class="grupo-formulario">
                    <label for="nome-disciplina-editar">Nome da Disciplina</label>
                    <input type="text" id="nome-disciplina-editar" name="disciplina" placeholder="Ex: Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-inicio-editar">Horário de Início</label>
                    <input type="time" id="horario-inicio-editar" name="horario_inicio" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-termino-editar">Horário de Término</label>
                    <input type="time" id="horario-termino-editar" name="horario_fim" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="dia-semana-editar">Dia da Semana</label>
                    <select id="dia-semana-editar" name="dia" required>
                        <option value="">Selecione um dia</option>
                        <option value="Segunda">Segunda-feira</option>
                        <option value="Terça">Terça-feira</option>
                        <option value="Quarta">Quarta-feira</option>
                        <option value="Quinta">Quinta-feira</option>
                        <option value="Sexta">Sexta-feira</option>
                        <option value="Sábado">Sábado</option>
                        <option value="Domingo">Domingo</option>
                    </select>
                </div>
                
                <div class="grupo-formulario">
                    <label for="professor-editar">Nome do Professor</label>
                    <input type="text" id="professor-editar" name="professor" placeholder="Ex: Prof. João Silva" required>
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
                <form id="form-excluir" method="POST" action="crud_disciplinas.php" style="display: inline;">
                    <input type="hidden" name="acao" value="excluir">
                    <input type="hidden" id="id-disciplina-excluir" name="id">
                    <button type="submit" class="botao-sim">Sim</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Overlay para modais -->
    <div class="overlay" id="overlay" onclick="fecharTodosModais()"></div>
    
    <script src="disciplinas.js"></script>
</body>
</html>
