<?php
/**
 * Sidebar Reutilizável - Flashnotes
 * Este arquivo contém a navegação lateral e o perfil do usuário
 * Deve ser incluído em todas as páginas do dashboard
 */

// Inicia a sessão se não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtém informações do usuário da sessão
$nome_usuario = $_SESSION['nome_usuario'] ?? 'Usuário';
$email_usuario = $_SESSION['email_usuario'] ?? 'usuario@exemplo.com';
?>

<!-- Barra Superior -->
<div class="barra-superior">
    <div class="barra-esquerda">
        <button class="botao-menu-mobile" id="botao-menu-mobile">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <img src="img/logo_completa_azul.png" alt="Ilustração 3D de Bloco de Notas" id="logo_c" style="width:184px;height: 46px;" >
    </div>
    
    <div class="barra-direita">
        <button class="botao-notificacao">
                <img src="icons/sino.svg" width="24" height="24" alt="Sino de notificação" style="filter: brightness(0) invert(1);">
        </button>
        <button class="botao-perfil" id="botao-perfil">
                <img src="icons/perfil.svg" width="24" height="24" alt="Sino de notificação" style="filter: brightness(0) invert(1);">            
        </button>
    </div>
</div>

<!-- Menu Lateral (Sidebar) -->
<aside class="sidebar" id="sidebar">
    <nav class="menu-navegacao">
        <a href="dashboard.php" class="item-menu">
            <img src="icons/quadro.svg" width="24" height="24" alt="Quadro branco" style="filter: brightness(0) invert(1);">
            <span>Dashboard</span>
        </a>
        
        <a href="disciplinas.php" class="item-menu">
                <img src="icons/caderno.svg" width="24" height="24" alt="Caderno" style="filter: brightness(0) invert(1);">
            <span>Disciplinas</span>
        </a>
        
        <a href="horarios.php" class="item-menu">
            <img src="icons/relogio.svg" width="24" height="24" alt="Relógio" style="filter: brightness(0) invert(1);">
            <span>Horários</span>
        </a>
        
        <a href="tarefas.php" class="item-menu">
            <img src="icons/checklist.svg" width="24" height="24" alt="Checklist" style="filter: brightness(0) invert(1);">
            <span>Tarefas</span>
        </a>
        
        <a href="agenda.php" class="item-menu">
            <img src="icons/calendario4dias.svg" width="24" height="24" alt="Calendario" style="filter: brightness(0) invert(1);">
            <span>Agenda</span>
        </a>
        
        <a href="configuracoes.php" class="item-menu">
            <img src="icons/engrenagem.svg" width="24" height="24" alt="Engrenagem" style="filter: brightness(0) invert(1);">
            <span>Configurações</span>
        </a>
    </nav>
</aside>

<!-- Menu Perfil (Dropdown) -->
<div class="menu-perfil" id="menu-perfil">
    <div class="perfil-info">
        <div class="avatar-perfil">
            <img src="icons/perfil.svg" width="24" height="24" alt="Sino de notificação">
        </div>
        <div class="info-usuario">
            <p class="nome-usuario"><?php echo htmlspecialchars($nome_usuario); ?></p>
            <p class="email-usuario"><?php echo htmlspecialchars($email_usuario); ?></p>
        </div>
    </div>
    
    <hr class="divisor-menu">
    
    <a href="perfil.php" class="opcao-menu">
        <img src="icons/perfil.svg" width="24" height="24" alt="Sino de notificação">
        Meu Perfil
    </a>
    
    <a href="configuracoes.php" class="opcao-menu">
        <img src="icons/engrenagem.svg" width="24" height="24" alt="Engrenagem">
        Configurações
    </a>
    
    <hr class="divisor-menu">
    
    <a href="logout.php" class="opcao-menu opcao-sair">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        Sair
    </a>
</div>

<!-- Script para controlar o menu -->
<script>
    // Menu mobile
    const botaoMenuMobile = document.getElementById('botao-menu-mobile');
    const sidebar = document.getElementById('sidebar');
    
    if (botaoMenuMobile) {
        botaoMenuMobile.addEventListener('click', function() {
            sidebar.classList.toggle('ativo');
        });
    }
    
    // Menu perfil
    const botaoPerfil = document.getElementById('botao-perfil');
    const menuPerfil = document.getElementById('menu-perfil');
    
    if (botaoPerfil) {
        botaoPerfil.addEventListener('click', function() {
            menuPerfil.classList.toggle('ativo');
        });
    }
    
    // Fechar menu perfil ao clicar fora
    document.addEventListener('click', function(event) {
        if (menuPerfil && !menuPerfil.contains(event.target) && !botaoPerfil.contains(event.target)) {
            menuPerfil.classList.remove('ativo');
        }
    });
</script>
