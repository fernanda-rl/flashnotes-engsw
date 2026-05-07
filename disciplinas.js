/**
 * Disciplinas - JavaScript
 * Compatível com o HTML enviado
 */

let disciplinaEmExclusao = null;

// =============================
// BOTÃO ADICIONAR
// =============================

const botaoAdicionar = document.getElementById('botao-adicionar-disciplina');

if (botaoAdicionar) {

    botaoAdicionar.addEventListener('click', function () {

        abrirModal('modal-adicionar');

        const formAdicionar = document.querySelector(
            '#modal-adicionar .formulario-disciplina'
        );

        if (formAdicionar) {
            formAdicionar.reset();
        }

    });

}

// =============================
// ABRIR MODAL
// =============================

function abrirModal(modalId) {

    const modal = document.getElementById(modalId);

    const overlay = document.getElementById('overlay');

    if (modal) {
        modal.classList.add('ativo');
    }

    if (overlay) {
        overlay.classList.add('ativo');
    }

}

// =============================
// FECHAR MODAL
// =============================

function fecharModal(modalId) {

    const modal = document.getElementById(modalId);

    const overlay = document.getElementById('overlay');

    if (modal) {
        modal.classList.remove('ativo');
    }

    if (overlay) {
        overlay.classList.remove('ativo');
    }

}

// =============================
// FECHAR TODOS OS MODAIS
// =============================

function fecharTodosModais() {

    document.querySelectorAll('.modal').forEach(function (modal) {

        modal.classList.remove('ativo');

    });

    const overlay = document.getElementById('overlay');

    if (overlay) {
        overlay.classList.remove('ativo');
    }

}

// =============================
// MODAL EDITAR
// =============================

function abrirModalEditar(
    id,
    nome,
    horaInicio,
    horaFim,
    dia,
    duracao
) {

    document.getElementById('id-disciplina-editar').value = id;

    document.getElementById('nome-disciplina-editar').value = nome;

    document.getElementById('horario-inicio-editar').value = horaInicio;

    document.getElementById('horario-fim-editar').value = horaFim;

    document.getElementById('dia-editar').value = dia;

    document.getElementById('duracao-editar').value = duracao;

    document.getElementById('titulo-editar').textContent =
        nome + ' - Editar';

    abrirModal('modal-editar');

}

// =============================
// MODAL EXCLUIR
// =============================

function abrirModalExcluir(id, nome) {

    disciplinaEmExclusao = id;

    document.getElementById('nome-disciplina-excluir').textContent = nome;

    document.getElementById('id-disciplina-excluir').value = id;

    abrirModal('modal-excluir');

}

// =============================
// PESQUISA
// =============================

const campoPesquisa = document.getElementById('campo-pesquisa');

if (campoPesquisa) {

    campoPesquisa.addEventListener('input', function (e) {

        const termo = e.target.value.toLowerCase();

        const cards = document.querySelectorAll('.card-disciplina');

        cards.forEach(function (card) {

            const titulo = card.querySelector('h3');

            if (!titulo) {
                return;
            }

            const nome = titulo.textContent.toLowerCase();

            if (nome.includes(termo)) {

                card.style.display = 'block';

            } else {

                card.style.display = 'none';

            }

        });

    });

}

// =============================
// ESC FECHA MODAL
// =============================

document.addEventListener('keydown', function (event) {

    if (event.key === 'Escape') {

        fecharTodosModais();

    }

});

// =============================
// ANIMAÇÃO
// =============================

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