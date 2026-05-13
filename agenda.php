<?php
/**
 * Agenda - Flashnotes
 * Página para visualizar e gerenciar eventos
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

// Dados fictícios de eventos
$eventos = array(
    array(
        'id' => 1,
        'titulo' => 'Prova Física',
        'data' => '06/04/26',
        'tipo' => 'prova',
        'cor' => '#FF4444'
    ),
    array(
        'id' => 2,
        'titulo' => 'Apresentação',
        'data' => '06/04/26',
        'tipo' => 'apresentacao',
        'cor' => '#FF4444'
    ),
    array(
        'id' => 3,
        'titulo' => 'Prova Química',
        'data' => '07/04/26',
        'tipo' => 'prova',
        'cor' => '#FFD700'
    ),
    array(
        'id' => 4,
        'titulo' => 'Prova Português',
        'data' => '08/04/26',
        'tipo' => 'prova',
        'cor' => '#FFD700'
    ),
    array(
        'id' => 5,
        'titulo' => 'Prova Literatura',
        'data' => '09/04/26',
        'tipo' => 'prova',
        'cor' => '#22C55E'
    ),
    array(
        'id' => 6,
        'titulo' => 'Prova Álgebra',
        'data' => '10/04/26',
        'tipo' => 'prova',
        'cor' => '#22C55E'
    ),
);

// Função para obter o mês e ano atual
$mes_atual = date('m');
$ano_atual = date('Y');
$mes_nome = array('', 'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 
                  'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Agenda</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/agenda.css">
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
            <div class="cabecalho-agenda">
                <div class="titulo-agenda">
                    <img src="icons/calendario4dias.svg" width="24" height="24" alt="Calendario">
                    <h1>Agenda</h1>
                </div>
            </div>
            
            <!-- Container Principal -->
            <div class="container-agenda">
                <!-- Calendário -->
                <div class="secao-calendario">
                    <div class="calendario">
                        <div class="cabecalho-calendario">
                            <button class="botao-mes" id="mes-anterior"><img src="icons/seta_esquerda.svg" width="24" height="24" alt="Calendario" style="filter: brightness(0) invert(1);"></button>
                            <h2 id="mes-ano"><?php echo $mes_nome[$mes_atual] . ' ' . $ano_atual; ?></h2>
                            <button class="botao-mes" id="mes-proximo"><img src="icons/seta_direita.svg" width="24" height="24" alt="Calendario" style="filter: brightness(0) invert(1);"></button>
                        </div>
                        
                        <div class="dias-semana">
                            <div class="dia-semana">Dom</div>
                            <div class="dia-semana">Seg</div>
                            <div class="dia-semana">Ter</div>
                            <div class="dia-semana">Qua</div>
                            <div class="dia-semana">Qui</div>
                            <div class="dia-semana">Sex</div>
                            <div class="dia-semana">Sab</div>
                        </div>
                        
                        <div class="dias-calendario" id="dias-calendario">
                            <!-- Preenchido por JavaScript -->
                        </div>
                    </div>
                </div>
                
                <!-- Próximos Eventos -->
                <div class="secao-eventos">
                    <div class="cabecalho-eventos">
                        <div class="titulo-eventos">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <h2>Próximos Eventos</h2>
                        </div>
                        <button class="botao-adicionar" id="botao-adicionar-evento">
                            Adicionar evento +
                        </button>
                    </div>
                    
                    <div class="lista-eventos" id="lista-eventos">
                        <?php foreach ($eventos as $evento): ?>
                            <div class="card-evento" data-id="<?php echo $evento['id']; ?>">
                                <div class="indicador-evento" style="background-color: <?php echo $evento['cor']; ?>;"></div>
                                <div class="conteudo-evento">
                                    <h3><?php echo htmlspecialchars($evento['titulo']); ?></h3>
                                    <p class="data-evento">Data: <?php echo htmlspecialchars($evento['data']); ?></p>
                                </div>
                                <div class="acoes-evento">
                                    <button class="botao-editar-evento" onclick="abrirModalEditar(<?php echo $evento['id']; ?>, '<?php echo htmlspecialchars($evento['titulo']); ?>', '<?php echo $evento['data']; ?>')">
                                        Editar
                                    </button>
                                    <button class="botao-deletar-evento" onclick="abrirModalExcluir(<?php echo $evento['id']; ?>, '<?php echo htmlspecialchars($evento['titulo']); ?>')">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Modal Adicionar Evento -->
    <div class="modal" id="modal-adicionar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2>Adicionar Evento</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-adicionar')">&times;</button>
            </div>
            <form class="formulario-evento" onsubmit="salvarEvento(event)">
                <div class="grupo-formulario">
                    <label for="titulo-evento">Título do Evento</label>
                    <input type="text" id="titulo-evento" name="titulo" placeholder="Ex: Prova de Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="data-evento">Data do Evento</label>
                    <input type="date" id="data-evento" name="data" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="tipo-evento">Tipo de Evento</label>
                    <select id="tipo-evento" name="tipo" required>
                        <option value="prova">Prova</option>
                        <option value="apresentacao">Apresentação</option>
                        <option value="trabalho">Trabalho</option>
                        <option value="reuniao">Reunião</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Editar Evento -->
    <div class="modal" id="modal-editar">
        <div class="conteudo-modal">
            <div class="cabecalho-modal">
                <h2>Editar Evento</h2>
                <button class="botao-fechar" onclick="fecharModal('modal-editar')">&times;</button>
            </div>
            <form class="formulario-evento" onsubmit="salvarEdicao(event)">
                <input type="hidden" id="id-evento-editar" name="id">
                
                <div class="grupo-formulario">
                    <label for="titulo-evento-editar">Título do Evento</label>
                    <input type="text" id="titulo-evento-editar" name="titulo" placeholder="Ex: Prova de Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="data-evento-editar">Data do Evento</label>
                    <input type="date" id="data-evento-editar" name="data" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="tipo-evento-editar">Tipo de Evento</label>
                    <select id="tipo-evento-editar" name="tipo" required>
                        <option value="prova">Prova</option>
                        <option value="apresentacao">Apresentação</option>
                        <option value="trabalho">Trabalho</option>
                        <option value="reuniao">Reunião</option>
                        <option value="outro">Outro</option>
                    </select>
                </div>
                
                <button type="submit" class="botao-salvar">Salvar</button>
            </form>
        </div>
    </div>
    
    <!-- Modal Excluir Evento -->
    <div class="modal" id="modal-excluir">
        <div class="conteudo-modal conteudo-excluir">
            <div class="titulo-excluir">
                <h2 id="titulo-evento-excluir">Evento</h2>
                <h3>Excluir</h3>
            </div>
            <p class="mensagem-excluir">Deseja realmente excluir esse evento?</p>
            <div class="botoes-excluir">
                <button class="botao-nao" onclick="fecharModal('modal-excluir')">Não</button>
                <button class="botao-sim" onclick="confirmarExclusao()">Sim</button>
            </div>
        </div>
    </div>
    
    <!-- Overlay para modais -->
    <div class="overlay" id="overlay" onclick="fecharTodosModais()"></div>
    
    <script src="agenda.js"></script>
</body>
</html>
