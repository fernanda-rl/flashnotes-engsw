<?php

session_start();

if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {

    header("Location: login.php");
    exit();
}

require_once 'crud_configuracoes.php';

$secao_ativa = $_GET['secao'] ?? 'email';

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Configurações</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/configuracoes.css">
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

            <div class="cabecalho-configuracoes">
                <div class="titulo-configuracoes">
                    <img src="icons/engrenagem.svg" width="24" height="24" alt="Engrenagem">
                    <h1>Configurações</h1>
                </div>
            </div>
            
            <!-- Container Principal -->
            <div class="container-configuracoes">
                <!-- Menu Lateral -->
                <aside class="menu-configuracoes">
                    
                    <a href="?secao=email" class="opcao-menu <?php echo ($secao_ativa === 'email') ? 'ativa' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                        </svg>
                        <span>Alterar Email</span>
                    </a>
                    
                    <a href="?secao=senha" class="opcao-menu <?php echo ($secao_ativa === 'senha') ? 'ativa' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>Alterar Senha</span>
                    </a>
                    
                    <a href="?secao=notificacoes" class="opcao-menu <?php echo ($secao_ativa === 'notificacoes') ? 'ativa' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                        </svg>
                        <span>Notificações</span>
                    </a>
                    
                    <a href="?secao=deletar" class="opcao-menu <?php echo ($secao_ativa === 'deletar') ? 'ativa' : ''; ?> opcao-deletar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>Deletar Conta</span>
                    </a>
                </aside>
                
                <!-- Conteudo das Seções -->
                <section class="conteudo-configuracoes">
                    <!-- SEÇÃO: ALTERAR EMAIL -->
                    <?php if ($secao_ativa === 'email'): ?>
                        <div class="secao-ativa">
                            <h2>Alterar Email</h2>
                                <form class="formulario-secao" method="POST">
                                    <div class="grupo-input">
                                        <label>Email Atual</label>
                                        <input
                                            type="email"
                                            value="<?php echo htmlspecialchars($usuario['email']); ?>"
                                            disabled
                                        >
                                    </div>

                                    <div class="grupo-input">
                                        <label for="novo-email">Novo Email</label>
                                        <input
                                            type="email"
                                            id="novo-email"
                                            name="novo_email"
                                            placeholder="novo.email@exemplo.com"
                                            required
                                        >
                                    </div>

                                    <div class="grupo-input">
                                        <label for="confirmar-novo-email">Confirmar Novo Email</label>
                                        <input
                                            type="email"
                                            id="confirmar-novo-email"
                                            name="confirmar_novo_email"
                                            placeholder="novo.email@exemplo.com"
                                            required
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        name="alterar_email"
                                        class="botao-salvar"
                                    >
                                        Salvar
                                    </button>
                                </form>
                                <?php if (!empty($mensagem)): ?>
                                    <div class="mensagem-sucesso">
                                        <?php echo $mensagem; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($erro)): ?>
                                    <div class="mensagem-erro">
                                        <?php echo $erro; ?>
                                    </div>
                                <?php endif; ?>  
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: ALTERAR SENHA -->
                    <?php if ($secao_ativa === 'senha'): ?>
                        <div class="secao-ativa">
                            <h2>Alterar Senha</h2>
                                <form class="formulario-secao" method="POST">
                                    <div class="grupo-input">
                                        <label for="senha-atual">Senha Atual</label>
                                        <input
                                            type="password"
                                            id="senha-atual"
                                            name="senha_atual"
                                            placeholder="••••••••"
                                            required
                                        >
                                    </div>

                                    <div class="grupo-input">
                                        <label for="nova-senha">Nova Senha</label>
                                        <input
                                            type="password"
                                            id="nova-senha"
                                            name="nova_senha"
                                            placeholder="••••••••"
                                            required
                                        >
                                    </div>

                                    <div class="grupo-input">
                                        <label for="confirmar-nova-senha">Confirmar Nova Senha</label>
                                        <input
                                            type="password"
                                            id="confirmar-nova-senha"
                                            name="confirmar_nova_senha"
                                            placeholder="••••••••"
                                            required
                                        >
                                    </div>

                                    <button
                                        type="submit"
                                        name="alterar_senha"
                                        class="botao-salvar"
                                    >
                                        Salvar
                                    </button>
                                </form>
                                <?php if (!empty($mensagem)): ?>
                                    <div class="mensagem-sucesso">
                                        <?php echo $mensagem; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($erro)): ?>
                                    <div class="mensagem-erro">
                                        <?php echo $erro; ?>
                                    </div>
                                <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: NOTIFICAÇÕES -->
                    <?php if ($secao_ativa === 'notificacoes'): ?>
                    <div class="secao-ativa">
                        <h2>Preferências de Notificação</h2>
                        <form class="formulario-secao" method="POST">
                            <div class="opcao-notificacao">
                                <div class="info-notificacao">
                                    <h3>Notificações de Email</h3>
                                    <p> Receba alertas sobre tarefas e eventos importantes por email.</p>
                                </div>

                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        name="notificacao_email"
                                        <?= $usuario['notificacao_email'] ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="opcao-notificacao">
                                <div class="info-notificacao">
                                    <h3>Notificações de Navegador</h3>
                                    <p>Receba notificações em tempo real no navegador.</p>
                                </div>

                                <label class="toggle-switch">
                                    <input
                                        type="checkbox"
                                        name="notificacao_navegador"
                                        <?= $usuario['notificacao_navegador'] ? 'checked' : '' ?>
                                    >
                                    <span class="slider"></span>
                                </label>
                            </div>

                            <div class="opcao-notificacao">
                                <div class="info-notificacao">
                                    <h3>Resumo Semanal</h3>
                                    <p>Receba um resumo semanal toda segunda-feira das suas tarefas.</p>
                                </div>

                                <label class="toggle-switch">
                                        <input
                                            type="checkbox"
                                            name="resumo_semanal"
                                            <?= $usuario['resumo_semanal'] ? 'checked' : '' ?>
                                        >
                                    <span class="slider"></span>

                                </label>
                            </div>

                            <button
                                type="submit"
                                name="salvar_notificacoes"
                                class="botao-salvar"
                            >
                                Salvar Preferências
                            </button>

                        </form>

                        <?php if (!empty($mensagem)): ?>
                            <div class="mensagem-sucesso">
                                <?php echo $mensagem; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($erro)): ?>
                            <div class="mensagem-erro">
                                <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: DELETAR CONTA -->
                    <?php if ($secao_ativa === 'deletar'): ?>
                        <div class="secao-ativa">
                            <h2>Deletar Conta</h2>
                            <div class="aviso-deletar">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <div>
                                    <h3>Atenção!</h3>
                                    <p>Deletar sua conta é uma ação permanente e irreversível. Todos os seus dados, tarefas, disciplinas e eventos serão removidos permanentemente.</p>
                                </div>
                            </div>
                                <form class="formulario-secao formulario-deletar" method="POST">
                                    <div class="grupo-input">
                                        <label for="email-deletar">Confirme seu Email</label>
                                        <input
                                            type="email"
                                            id="email-deletar"
                                            name="email_deletar"
                                            placeholder="seu.email@exemplo.com"
                                            required
                                        >
                                    </div>

                                    <div class="grupo-input">
                                        <label for="senha-deletar">Confirme sua Senha</label>
                                        <input
                                            type="password"
                                            id="senha-deletar"
                                            name="senha_deletar"
                                            placeholder="••••••••"
                                            required
                                        >
                                    </div>

                                    <div class="confirmacao-deletar">
                                        <label class="checkbox-confirmacao">
                                            <input
                                                type="checkbox"
                                                id="confirmar-deletar"
                                                required
                                            >
                                            <span>
                                                Entendo que esta ação é irreversível e desejo deletar minha conta
                                            </span>
                                        </label>
                                    </div>

                                    <button
                                        type="submit"
                                        name="deletar_conta"
                                        class="botao-deletar-conta"
                                    >
                                        Deletar Conta Permanentemente
                                    </button>
                                </form>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
    
    <script src="configuracoes.js"></script>
    <script src="sidebar.js"></script>
</body>
</html>