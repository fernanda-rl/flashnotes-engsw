/**
 * Agenda - JavaScript
 * Gerencia o calendário, modais e interações da página de agenda
 */

let eventoEmExclusao = null;
let mesAtual = new Date().getMonth();
let anoAtual = new Date().getFullYear();

const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
               'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

// Inicializar calendário ao carregar a página
document.addEventListener('DOMContentLoaded', function() {
    renderizarCalendario();
    
    // Botões de navegação do calendário
    document.getElementById('mes-anterior').addEventListener('click', function() {
        mesAtual--;
        if (mesAtual < 0) {
            mesAtual = 11;
            anoAtual--;
        }
        renderizarCalendario();
    });
    
    document.getElementById('mes-proximo').addEventListener('click', function() {
        mesAtual++;
        if (mesAtual > 11) {
            mesAtual = 0;
            anoAtual++;
        }
        renderizarCalendario();
    });
    
    // Botão de adicionar evento
    document.getElementById('botao-adicionar-evento').addEventListener('click', function() {
        abrirModal('modal-adicionar');
        document.querySelector('.formulario-evento').reset();
    });
});

// Função para renderizar o calendário
function renderizarCalendario() {
    const diasCalendario = document.getElementById('dias-calendario');
    const mesAnoElement = document.getElementById('mes-ano');
    
    // Atualizar título
    mesAnoElement.textContent = meses[mesAtual] + ' ' + anoAtual;
    
    // Limpar dias anteriores
    diasCalendario.innerHTML = '';
    
    // Obter primeiro dia do mês e número de dias
    const primeiroDia = new Date(anoAtual, mesAtual, 1).getDay();
    const diasNoMes = new Date(anoAtual, mesAtual + 1, 0).getDate();
    const diasMesAnterior = new Date(anoAtual, mesAtual, 0).getDate();
    
    // Adicionar dias do mês anterior
    for (let i = primeiroDia - 1; i >= 0; i--) {
        const dia = document.createElement('div');
        dia.className = 'dia-calendario outro-mes';
        dia.textContent = diasMesAnterior - i;
        diasCalendario.appendChild(dia);
    }
    
    // Adicionar dias do mês atual
    const hoje = new Date();
    for (let i = 1; i <= diasNoMes; i++) {
        const dia = document.createElement('div');
        dia.className = 'dia-calendario';
        dia.textContent = i;
        
        // Destacar hoje
        if (i === hoje.getDate() && mesAtual === hoje.getMonth() && anoAtual === hoje.getFullYear()) {
            dia.classList.add('hoje');
        }
        
        // Adicionar evento de clique
        dia.addEventListener('click', function() {
            selecionarDia(this);
        });
        
        diasCalendario.appendChild(dia);
    }
    
    // Adicionar dias do próximo mês
    const totalCelulas = diasCalendario.children.length;
    const diasRestantes = 42 - totalCelulas; // 6 linhas x 7 colunas
    for (let i = 1; i <= diasRestantes; i++) {
        const dia = document.createElement('div');
        dia.className = 'dia-calendario outro-mes';
        dia.textContent = i;
        diasCalendario.appendChild(dia);
    }
}

// Função para selecionar um dia
function selecionarDia(elemento) {
    // Remover seleção anterior
    document.querySelectorAll('.dia-calendario.selecionado').forEach(el => {
        el.classList.remove('selecionado');
    });
    
    // Adicionar seleção ao dia clicado
    if (!elemento.classList.contains('outro-mes')) {
        elemento.classList.add('selecionado');
    }
}

// Função para abrir modal
function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('overlay');
    
    modal.classList.add('ativo');
    overlay.classList.add('ativo');
}

// Função para fechar modal
function fecharModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('overlay');
    
    modal.classList.remove('ativo');
    overlay.classList.remove('ativo');
}

// Função para fechar todos os modais
function fecharTodosModais() {
    document.querySelectorAll('.modal').forEach(modal => {
        modal.classList.remove('ativo');
    });
    document.getElementById('overlay').classList.remove('ativo');
}

// Função para abrir modal de editar
function abrirModalEditar(id, titulo, data) {
    document.getElementById('id-evento-editar').value = id;
    document.getElementById('titulo-evento-editar').value = titulo;
    document.getElementById('data-evento-editar').value = data;
    
    abrirModal('modal-editar');
}

// Função para abrir modal de excluir
function abrirModalExcluir(id, titulo) {
    eventoEmExclusao = id;
    document.getElementById('titulo-evento-excluir').textContent = titulo;
    
    abrirModal('modal-excluir');
}

// Função para confirmar exclusão
function confirmarExclusao() {
    if (eventoEmExclusao !== null) {
        console.log('Deletando evento com ID:', eventoEmExclusao);
        
        // Remover do DOM
        const card = document.querySelector(`[data-id="${eventoEmExclusao}"]`);
        if (card) {
            card.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                card.remove();
                fecharModal('modal-excluir');
                eventoEmExclusao = null;
            }, 300);
        }
    }
}

// Função para salvar novo evento
function salvarEvento(event) {
    event.preventDefault();
    
    const titulo = document.getElementById('titulo-evento').value;
    const data = document.getElementById('data-evento').value;
    const tipo = document.getElementById('tipo-evento').value;
    
    // Converter data para formato DD/MM/YY
    const dataObj = new Date(data);
    const dataFormatada = String(dataObj.getDate()).padStart(2, '0') + '/' + 
                         String(dataObj.getMonth() + 1).padStart(2, '0') + '/' + 
                         String(dataObj.getFullYear()).slice(-2);
    
    // Mapa de cores por tipo
    const coresTipo = {
        'prova': '#FF4444',
        'apresentacao': '#FF6B6B',
        'trabalho': '#FFD700',
        'reuniao': '#3B82F6',
        'outro': '#8B5CF6'
    };
    
    console.log('Salvando evento:', { titulo, data, tipo });
    
    // Adicionar ao DOM
    const novoCard = document.createElement('div');
    novoCard.className = 'card-evento';
    novoCard.setAttribute('data-id', '0');
    novoCard.innerHTML = `
        <div class="indicador-evento" style="background-color: ${coresTipo[tipo]};"></div>
        <div class="conteudo-evento">
            <h3>${titulo}</h3>
            <p class="data-evento">Data: ${dataFormatada}</p>
        </div>
        <div class="acoes-evento">
            <button class="botao-editar-evento" onclick="abrirModalEditar(0, '${titulo}', '${data}')">
                Editar
            </button>
            <button class="botao-deletar-evento" onclick="abrirModalExcluir(0, '${titulo}')">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.getElementById('lista-eventos').insertBefore(novoCard, document.getElementById('lista-eventos').firstChild);
    fecharModal('modal-adicionar');
    document.querySelector('.formulario-evento').reset();
}

// Função para salvar edição
function salvarEdicao(event) {
    event.preventDefault();
    
    const id = document.getElementById('id-evento-editar').value;
    const titulo = document.getElementById('titulo-evento-editar').value;
    const data = document.getElementById('data-evento-editar').value;
    const tipo = document.getElementById('tipo-evento-editar').value;
    
    // Converter data para formato DD/MM/YY
    const dataObj = new Date(data);
    const dataFormatada = String(dataObj.getDate()).padStart(2, '0') + '/' + 
                         String(dataObj.getMonth() + 1).padStart(2, '0') + '/' + 
                         String(dataObj.getFullYear()).slice(-2);
    
    // Mapa de cores por tipo
    const coresTipo = {
        'prova': '#FF4444',
        'apresentacao': '#FF6B6B',
        'trabalho': '#FFD700',
        'reuniao': '#3B82F6',
        'outro': '#8B5CF6'
    };
    
    console.log('Editando evento:', { id, titulo, data, tipo });
    
    // Atualizar no DOM
    const card = document.querySelector(`[data-id="${id}"]`);
    if (card) {
        card.innerHTML = `
            <div class="indicador-evento" style="background-color: ${coresTipo[tipo]};"></div>
            <div class="conteudo-evento">
                <h3>${titulo}</h3>
                <p class="data-evento">Data: ${dataFormatada}</p>
            </div>
            <div class="acoes-evento">
                <button class="botao-editar-evento" onclick="abrirModalEditar(${id}, '${titulo}', '${data}')">
                    Editar
                </button>
                <button class="botao-deletar-evento" onclick="abrirModalExcluir(${id}, '${titulo}')">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                </button>
            </div>
        `;
    }
    
    fecharModal('modal-editar');
}

// Fechar modais ao pressionar ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        fecharTodosModais();
    }
});

// Animação de fade out
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);
