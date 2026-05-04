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

// Dados fictícios de disciplinas
$disciplinas = array(
    array(
        'id' => 1,
        'nome' => 'Educação Física',
        'horario_inicio' => '08:00',
        'horario_fim' => '09:00',
        'dia' => 'Segunda',
        'duracao' => '1 hora'
    ),
    array(
        'id' => 2,
        'nome' => 'Física',
        'horario_inicio' => '07:00',
        'horario_fim' => '08:00',
        'dia' => 'Segunda',
        'duracao' => '1 hora'
    ),
    array(
        'id' => 3,
        'nome' => 'Química',
        'horario_inicio' => '10:30',
        'horario_fim' => '11:30',
        'dia' => 'Quarta',
        'duracao' => '1 hora'
    ),
    array(
        'id' => 4,
        'nome' => 'Matemática',
        'horario_inicio' => '09:00',
        'horario_fim' => '10:00',
        'dia' => 'Terça',
        'duracao' => '1 hora'
    ),
    array(
        'id' => 5,
        'nome' => 'Português',
        'horario_inicio' => '14:00',
        'horario_fim' => '15:00',
        'dia' => 'Quinta',
        'duracao' => '1 hora'
    )
);
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
                <input type="text" id="campo-pesquisa" placeholder="PESQUISE AQUI A DISCIPLINA" class="campo-pesquisa">
            </div>
            
            <!-- Grade de Disciplinas -->
            <div class="grade-disciplinas" id="grade-disciplinas">
                <?php foreach ($disciplinas as $disciplina): ?>
                    <div class="card-disciplina" data-id="<?php echo $disciplina['id']; ?>">
                        <h3><?php echo htmlspecialchars($disciplina['nome']); ?></h3>
                        <div class="info-disciplina">
                            <p><strong>Horário:</strong> <?php echo htmlspecialchars($disciplina['horario_inicio'] . ' - ' . $disciplina['horario_fim']); ?></p>
                            <p><strong>Dia(s):</strong> <?php echo htmlspecialchars($disciplina['dia']); ?></p>
                            <p><strong>Duração:</strong> <?php echo htmlspecialchars($disciplina['duracao']); ?></p>
                        </div>
                        <div class="acoes-disciplina">
                            <button class="botao-editar" onclick="abrirModalEditar(<?php echo $disciplina['id']; ?>, '<?php echo htmlspecialchars($disciplina['nome']); ?>', '<?php echo $disciplina['horario_inicio']; ?>', '<?php echo $disciplina['horario_fim']; ?>', '<?php echo htmlspecialchars($disciplina['dia']); ?>')">
                                Editar
                            </button>
                            <button class="botao-deletar" onclick="abrirModalExcluir(<?php echo $disciplina['id']; ?>, '<?php echo htmlspecialchars($disciplina['nome']); ?>')">
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
            <form class="formulario-disciplina" onsubmit="salvarDisciplina(event)">
                <div class="grupo-formulario">
                    <label for="nome-disciplina">Nome da Disciplina</label>
                    <input type="text" id="nome-disciplina" name="nome" placeholder="Ex: Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-inicio">Horário de Início</label>
                    <input type="time" id="horario-inicio" name="horario_inicio" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-termino">Horário de Término</label>
                    <input type="time" id="horario-termino" name="horario_termino" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="dia-semana">Dia(s) da Semana</label>
                    <input type="text" id="dia-semana" name="dia" placeholder="Ex: Segunda, Quarta" required>
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
            <form class="formulario-disciplina" onsubmit="salvarEdicao(event)">
                <input type="hidden" id="id-disciplina-editar" name="id">
                
                <div class="grupo-formulario">
                    <label for="nome-disciplina-editar">Nome da Disciplina</label>
                    <input type="text" id="nome-disciplina-editar" name="nome" placeholder="Ex: Matemática" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-inicio-editar">Horário de Início</label>
                    <input type="time" id="horario-inicio-editar" name="horario_inicio" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="horario-termino-editar">Horário de Término</label>
                    <input type="time" id="horario-termino-editar" name="horario_termino" required>
                </div>
                
                <div class="grupo-formulario">
                    <label for="dia-semana-editar">Dia(s) da Semana</label>
                    <input type="text" id="dia-semana-editar" name="dia" placeholder="Ex: Segunda, Quarta" required>
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
                <button class="botao-sim" onclick="confirmarExclusao()">Sim</button>
            </div>
        </div>
    </div>
    
    <!-- Overlay para modais -->
    <div class="overlay" id="overlay" onclick="fecharTodosModais()"></div>
    
    <script src="disciplinas.js"></script>
</body>
</html>
