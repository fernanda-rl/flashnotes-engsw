<?php
/**
 * Configurações - Flashnotes
 * Página para gerenciar as configurações da conta do usuário
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

// Define a seção ativa (padrão: perfil)
$secao_ativa = isset($_GET['secao']) ? $_GET['secao'] : 'perfil';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Configurações</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="css/configuracoes.css">
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
                    <a href="?secao=perfil" class="opcao-menu <?php echo ($secao_ativa === 'perfil') ? 'ativa' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Perfil</span>
                    </a>
                    
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
                    
                    <a href="?secao=tema" class="opcao-menu <?php echo ($secao_ativa === 'tema') ? 'ativa' : ''; ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"></circle>
                            <line x1="12" y1="1" x2="12" y2="3"></line>
                            <line x1="12" y1="21" x2="12" y2="23"></line>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                            <line x1="1" y1="12" x2="3" y2="12"></line>
                            <line x1="21" y1="12" x2="23" y2="12"></line>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                        </svg>
                        <span>Tema</span>
                    </a>
                    
                    <a href="?secao=deletar" class="opcao-menu <?php echo ($secao_ativa === 'deletar') ? 'ativa' : ''; ?> opcao-deletar">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        <span>Deletar Conta</span>
                    </a>
                </aside>
                
                <!-- Conteúdo das Seções -->
                <section class="conteudo-configuracoes">
                    <!-- SEÇÃO: PERFIL -->
                    <?php if ($secao_ativa === 'perfil'): ?>
                        <div class="secao-ativa">
                            <h2>Meu Perfil</h2>
                            <div class="formulario-secao">
                                <div class="info-perfil">
                                    <div class="avatar-perfil">
                                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="12" cy="7" r="4"></circle>
                                        </svg>
                                    </div>
                                    <div class="dados-perfil">
                                        <p><strong>Nome:</strong> João Silva</p>
                                        <p><strong>Email:</strong> joao.silva@email.com</p>
                                        <p><strong>Data de Cadastro:</strong> 15 de Janeiro de 2026</p>
                                        <p><strong>Plano:</strong> Gratuito</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: ALTERAR EMAIL -->
                    <?php if ($secao_ativa === 'email'): ?>
                        <div class="secao-ativa">
                            <h2>Alterar Email</h2>
                            <form class="formulario-secao" onsubmit="return false;">
                                <div class="grupo-input">
                                    <label for="email-atual">Email Atual</label>
                                    <input type="email" id="email-atual" placeholder="seu.email@exemplo.com" disabled>
                                </div>
                                
                                <div class="grupo-input">
                                    <label for="novo-email">Novo Email</label>
                                    <input type="email" id="novo-email" placeholder="novo.email@exemplo.com" required>
                                </div>
                                
                                <div class="grupo-input">
                                    <label for="confirmar-novo-email">Confirmar Novo Email</label>
                                    <input type="email" id="confirmar-novo-email" placeholder="novo.email@exemplo.com" required>
                                </div>
                                
                                <button type="submit" class="botao-salvar">Salvar</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: ALTERAR SENHA -->
                    <?php if ($secao_ativa === 'senha'): ?>
                        <div class="secao-ativa">
                            <h2>Alterar Senha</h2>
                            <form class="formulario-secao" onsubmit="return false;">
                                <div class="grupo-input">
                                    <label for="senha-atual">Senha Atual</label>
                                    <input type="password" id="senha-atual" placeholder="••••••••" required>
                                </div>
                                
                                <div class="grupo-input">
                                    <label for="nova-senha">Nova Senha</label>
                                    <input type="password" id="nova-senha" placeholder="••••••••" required>
                                </div>
                                
                                <div class="grupo-input">
                                    <label for="confirmar-nova-senha">Confirmar Nova Senha</label>
                                    <input type="password" id="confirmar-nova-senha" placeholder="••••••••" required>
                                </div>
                                
                                <button type="submit" class="botao-salvar">Salvar</button>
                            </form>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: NOTIFICAÇÕES -->
                    <?php if ($secao_ativa === 'notificacoes'): ?>
                        <div class="secao-ativa">
                            <h2>Preferências de Notificação</h2>
                            <div class="formulario-secao">
                                <div class="opcao-notificacao">
                                    <div class="info-notificacao">
                                        <h3>Notificações de Email</h3>
                                        <p>Receba alertas sobre tarefas e eventos importantes por email.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                
                                <div class="opcao-notificacao">
                                    <div class="info-notificacao">
                                        <h3>Notificações de Navegador</h3>
                                        <p>Receba notificações em tempo real enquanto estiver usando o Flashnotes.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                
                                <div class="opcao-notificacao">
                                    <div class="info-notificacao">
                                        <h3>Resumo Semanal</h3>
                                        <p>Receba um resumo semanal de suas tarefas e eventos.</p>
                                    </div>
                                    <label class="toggle-switch">
                                        <input type="checkbox">
                                        <span class="slider"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- SEÇÃO: TEMA -->
                    <?php if ($secao_ativa === 'tema'): ?>
                        <div class="secao-ativa">
                            <h2>Preferências de Tema</h2>
                            <div class="formulario-secao">
                                <div class="opcoes-tema">
                                    <div class="opcao-tema ativa">
                                        <div class="preview-tema claro"></div>
                                        <h3>Tema Claro</h3>
                                        <p>Interface clara e limpa</p>
                                    </div>
                                    
                                    <div class="opcao-tema">
                                        <div class="preview-tema escuro"></div>
                                        <h3>Tema Escuro</h3>
                                        <p>Interface escura e confortável</p>
                                    </div>
                                    
                                    <div class="opcao-tema">
                                        <div class="preview-tema automatico"></div>
                                        <h3>Automático</h3>
                                        <p>Segue as preferências do sistema</p>
                                    </div>
                                </div>
                            </div>
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
                            
                            <form class="formulario-secao formulario-deletar" onsubmit="return false;">
                                <div class="grupo-input">
                                    <label for="email-deletar">Confirme seu Email</label>
                                    <input type="email" id="email-deletar" placeholder="seu.email@exemplo.com" required>
                                </div>
                                
                                <div class="grupo-input">
                                    <label for="senha-deletar">Confirme sua Senha</label>
                                    <input type="password" id="senha-deletar" placeholder="••••••••" required>
                                </div>
                                
                                <div class="confirmacao-deletar">
                                    <label class="checkbox-confirmacao">
                                        <input type="checkbox" id="confirmar-deletar" required>
                                        <span>Entendo que esta ação é irreversível e desejo deletar minha conta</span>
                                    </label>
                                </div>
                                
                                <button type="submit" class="botao-deletar-conta">Deletar Conta Permanentemente</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </section>
            </div>
        </main>
    </div>
    
    <script src="configuracoes.js"></script>
</body>
</html>
