<?php
/**
 * Tarefas - Flashnotes
 * Página para gerenciar tarefas e trabalhos
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

// Dados fictícios de tarefas
$tarefas = array(
    array(
        'id' => 1,
        'titulo' => 'Trabalho Ciências',
        'vencimento' => '06/04/26',
        'prioridade' => 'alta',
        'cor' => '#FF4444',
        'concluida' => false
    ),
    array(
        'id' => 2,
        'titulo' => 'Trabalho Matemática',
        'vencimento' => '07/04/26',
        'prioridade' => 'alta',
        'cor' => '#FFD700',
        'concluida' => false
    ),
    array(
        'id' => 3,
        'titulo' => 'Trabalho Ciências',
        'vencimento' => '08/04/26',
        'prioridade' => 'alta',
        'cor' => '#22C55E',
        'concluida' => false
    ),
    array(
        'id' => 4,
        'titulo' => 'Trabalho Português',
        'vencimento' => '09/04/26',
        'prioridade' => 'alta',
        'cor' => '#22C55E',
        'concluida' => false
    ),
    array(
        'id' => 5,
        'titulo' => 'Trabalho História',
        'vencimento' => '10/04/26',
        'prioridade' => 'média',
        'cor' => '#3B82F6',
        'concluida' => false
    ),
    array(
        'id' => 6,
        'titulo' => 'Trabalho Geografia',
        'vencimento' => '11/04/26',
        'prioridade' => 'baixa',
        'cor' => '#8B5CF6',
        'concluida' => false
    ),
);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Tarefas</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/tarefas.css">
</head>
<body>
    <div class="container-dashboard">
        <!-- Sidebar e Barra Superior -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Conteúdo Principal -->
        <main class="conteudo-principal">
            <div class="cabecalho-tarefas">
                <div class="titulo-tarefas">
                    <img src="icons/checklist.svg" width="24" height="24" alt="Checklist">
                    <h1>Tarefas</h1>
                </div>
                <button class="botao-adicionar" id="botao-adicionar-tarefa">
                    Adicionar tarefa +
                </button>
            </div>
            
            <!-- Grade de Tarefas -->
            <div class="grade-tarefas" id="grade-tarefas">
                <?php foreach ($tarefas as $tarefa): ?>
                    <div class="card-tarefa" data-id="<?php echo $tarefa['id']; ?>" data-prioridade="<?php echo $tarefa['prioridade']; ?>">
                        <div class="indicador-prioridade" style="background-color: <?php echo $tarefa['cor']; ?>;"></div>
                        <div class="conteudo-tarefa">
                            <h3><?php echo htmlspecialchars($tarefa['titulo']); ?></h3>
                            <div class="info-tarefa">
                                <p><strong>Vence:</strong> <?php echo htmlspecialchars($tarefa['vencimento']); ?></p>
                                <p><strong>Prioridade:</strong> <?php echo ucfirst(htmlspecialchars($tarefa['prioridade'])); ?></p>
                            </div>
                        </div>
                        <div class="acoes-tarefa">
                            <button class="botao-concluir" onclick="marcarConcluida(<?php echo $tarefa['id']; ?>)" title="Marcar como concluído">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </button>
                            <button class="botao-editar" onclick="abrirModalEditar(<?php echo $tarefa['id']; ?>, '<?php echo htmlspecialchars($tarefa['titulo']); ?>', '<?php echo $tarefa['vencimento']; ?>', '<?php echo $tarefa['prioridade']; ?>')">
                                Editar
                            </button>
                            <button class="botao-deletar" onclick="abrirModalExcluir(<?php echo $tarefa['id']; ?>, '<?php echo htmlspecialchars($tarefa['titulo']); ?>')">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                    <line x1="10" y1="11" x2="10" y2="17"></line>
                                    <line x1="14" y1="11" x2="14" y2="17"></line>
                                </svg>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
    
    <!-- Modal Adicionar Tarefa -->
    <div class="modal" id="modal-adicionar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2>Adicionar Tarefa</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-adicionar')">&times;</button>
            </div>
            <form class="formulario-tarefa" onsubmit="salvarTarefa(event)">
                <div class="grupo-formulario">
                    <label for="titulo-tarefa">Título da Tarefa</label>
                    <input type="text" id="titulo-tarefa" name="titulo" placeholder="Ex: Trabalho de Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="vencimento-tarefa">Data de Vencimento</label>
                    <input type="date" id="vencimento-tarefa" name="vencimento" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="prioridade-tarefa">Prioridade</label>
                    <select id="prioridade-tarefa" name="prioridade" required>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Editar Tarefa -->
    <div class="modal" id="modal-editar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2>Editar Tarefa</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-editar')">&times;</button>
            </div>
            <form class="formulario-tarefa" onsubmit="salvarEdicao(event)">
                <input type="hidden" id="id-tarefa-editar" name="id">
                
                <div class="grupo-formulario">
                    <label for="titulo-tarefa-editar">Título da Tarefa</label>
                    <input type="text" id="titulo-tarefa-editar" name="titulo" placeholder="Ex: Trabalho de Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="vencimento-tarefa-editar">Data de Vencimento</label>
                    <input type="date" id="vencimento-tarefa-editar" name="vencimento" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="prioridade-tarefa-editar">Prioridade</label>
                    <select id="prioridade-tarefa-editar" name="prioridade" required>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                    </select>
                </div>
                
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Excluir Tarefa -->
    <div class="modal" id="modal-excluir">
        <div class="conteudo-modal conteudo-excluir">
            <div class="titulo-excluir">
                <h2 id="titulo-tarefa-excluir">Tarefa</h2>
                <h3>Excluir</h3>
            </div>
            <p class="mensagem-excluir">Deseja realmente excluir essa tarefa?</p>
            <div class="botoes-excluir">
                <button class="botao-nao" onclick="fecharModal('modal-excluir')">Não</button>
                <button class="botao-sim" onclick="confirmarExclusao()">Sim</button>
            </div>
        </div>
    </div>
    
    <!-- Overlay para modais -->
    <div class="overlay" id="overlay" onclick="fecharTodosModais()"></div>
    
    <script src="tarefas.js"></script>
</body>
</html>
