/**
 * Disciplinas - JavaScript
 * Gerencia os modais e interações da página de disciplinas
 */

let disciplinaEmExclusao = null;

// Abrir modal de adicionar disciplina
document.getElementById('botao-adicionar-disciplina').addEventListener('click', function() {
    abrirModal('modal-adicionar');
    document.querySelector('.formulario-disciplina').reset();
});

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
function abrirModalEditar(id, nome, horaInicio, horaFim, dia, professor) {
    document.getElementById('id-disciplina-editar').value = id;
    document.getElementById('nome-disciplina-editar').value = nome;
    document.getElementById('horario-inicio-editar').value = horaInicio;
    document.getElementById('horario-termino-editar').value = horaFim;
    document.getElementById('dia-semana-editar').value = dia;
    document.getElementById('professor-editar').value = professor;
    
    document.getElementById('titulo-editar').textContent = nome + ' - Editar';
    
    abrirModal('modal-editar');
}

// Função para abrir modal de excluir
function abrirModalExcluir(id, nome) {

    disciplinaEmExclusao = id;

    // Nome da disciplina
    document.getElementById('nome-disciplina-excluir').textContent = nome;

    // Define o ID no input hidden
    document.getElementById('id-disciplina-excluir').value = id;

    // Abre o modal
    abrirModal('modal-excluir');
}

// Função para salvar nova disciplina
function salvarDisciplina(event) {
    event.preventDefault();
    
    const nome = document.getElementById('nome-disciplina').value;
    const horaInicio = document.getElementById('horario-inicio').value;
    const horaFim = document.getElementById('horario-termino').value;
    const dia = document.getElementById('dia-semana').value;
    const professor = document.getElementById('professor').value;
    
    // Aqui você faria uma requisição AJAX para salvar a disciplina no banco de dados
    console.log('Salvando disciplina:', { nome, horaInicio, horaFim, dia, professor });
    
    // Por enquanto, apenas adicionamos ao DOM
    const novoCard = document.createElement('div');
    novoCard.className = 'card-disciplina';
    novoCard.innerHTML = `
        <h3>${nome}</h3>
        <div class="info-disciplina">
            <p><strong>Horário:</strong> ${horaInicio} - ${horaFim}</p>
            <p><strong>Dia(s):</strong> ${dia}</p>
            <p><strong>Professor:</strong> ${professor}</p>
        </div>
        <div class="acoes-disciplina">
            <button class="botao-editar" onclick="abrirModalEditar(0, '${nome}', '${horaInicio}', '${horaFim}', '${dia}', '${professor}')">
                Editar
            </button>
            <button class="botao-deletar" onclick="abrirModalExcluir(0, '${nome}')">
                Deletar
            </button>
        </div>
    `;
    
    document.getElementById('grade-disciplinas').appendChild(novoCard);
    fecharModal('modal-adicionar');
    document.querySelector('.formulario-disciplina').reset();
}

// Função para salvar edição
function salvarEdicao(event) {
    event.preventDefault();
    
    const id = document.getElementById('id-disciplina-editar').value;
    const nome = document.getElementById('nome-disciplina-editar').value;
    const horaInicio = document.getElementById('horario-inicio-editar').value;
    const horaFim = document.getElementById('horario-termino-editar').value;
    const dia = document.getElementById('dia-semana-editar').value;
    const professor = document.getElementById('professor-editar').value;
    
    // Aqui você faria uma requisição AJAX para atualizar a disciplina no banco de dados
    console.log('Editando disciplina:', { id, nome, horaInicio, horaFim, dia, professor });
    
    // Por enquanto, apenas atualizamos o DOM
    const card = document.querySelector(`[data-id="${id}"]`);
    if (card) {
        card.innerHTML = `
            <h3>${nome}</h3>
            <div class="info-disciplina">
                <p><strong>Horário:</strong> ${horaInicio} - ${horaFim}</p>
                <p><strong>Dia(s):</strong> ${dia}</p>
                <p><strong>Professor:</strong> ${professor}</p>
            </div>
            <div class="acoes-disciplina">
                <button class="botao-editar" onclick="abrirModalEditar(${id}, '${nome}', '${horaInicio}', '${horaFim}', '${dia}', '${professor}')">
                    Editar
                </button>
                <button class="botao-deletar" onclick="abrirModalExcluir(${id}, '${nome}')">
                    Deletar
                </button>
            </div>
        `;
    }
    
    fecharModal('modal-editar');
}

// Pesquisa de disciplinas
document.getElementById('campo-pesquisa').addEventListener('input', function(e) {
    const termo = e.target.value.toLowerCase();
    const cards = document.querySelectorAll('.card-disciplina');
    
    cards.forEach(card => {
        const nome = card.querySelector('h3').textContent.toLowerCase();
        if (nome.includes(termo)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
});

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
