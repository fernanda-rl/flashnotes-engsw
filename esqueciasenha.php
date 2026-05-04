<?php
/**
 * Página de Esqueci a Senha - Flashnotes
 * HTML e PHP unificados em um único arquivo
 * Fluxo com 3 etapas: Email -> Código -> Nova Senha
 */

// Inicia a sessão para armazenar dados do processo
session_start();
// CONEXÃO COM O BANCO
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "flashnotes";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Variáveis para armazenar mensagens e controlar o fluxo
$mensagem_erro = '';
$mensagem_sucesso = '';
$etapa_atual = $_SESSION['etapa_recuperacao'] ?? 1;
$email_recuperacao = $_SESSION['email_recuperacao'] ?? '';

// ==========================
// PROCESSAMENTO
// ==========================
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // ==========================
    // ETAPA 1 - EMAIL
    // ==========================
    if (isset($_POST['etapa1_email'])) {

        $email = filter_var($_POST['email_usuario'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $mensagem_erro = "E-mail inválido.";
        } else {

            $sql = "SELECT id FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $resultado = $stmt->get_result();

            if ($resultado->num_rows == 0) {
                $mensagem_erro = "E-mail não encontrado.";
            } else {

                $_SESSION['email_recuperacao'] = $email;
                $_SESSION['codigo_recuperacao'] = rand(100000, 999999);
                $_SESSION['etapa_recuperacao'] = 2;

                $mensagem_sucesso = "Código gerado! (teste: " . $_SESSION['codigo_recuperacao'] . ")";
                $etapa_atual = 2;
            }

            $stmt->close();
        }
    }

    // ==========================
    // ETAPA 2 - CÓDIGO
    // ==========================
    elseif (isset($_POST['etapa2_codigo'])) {

        $codigo = $_POST['codigo_recuperacao'];

        if ($codigo != $_SESSION['codigo_recuperacao']) {
            $mensagem_erro = "Código inválido.";
        } else {
            $_SESSION['etapa_recuperacao'] = 3;
            $etapa_atual = 3;
            $mensagem_sucesso = "Código validado!";
        }
    }

    // ==========================
    // ETAPA 3 - NOVA SENHA
    // ==========================
    elseif (isset($_POST['etapa3_senha'])) {

        $nova = $_POST['nova_senha_usuario'];
        $confirmar = $_POST['confirmar_nova_senha_usuario'];

        if ($nova !== $confirmar) {
            $mensagem_erro = "As senhas não coincidem.";
        } elseif (strlen($nova) < 6) {
            $mensagem_erro = "Senha muito curta.";
        } else {

            $hash = password_hash($nova, PASSWORD_DEFAULT);
            $email = $_SESSION['email_recuperacao'];

            $sql = "UPDATE usuarios SET senha_hash = ? WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $hash, $email);

            if ($stmt->execute()) {

                $mensagem_sucesso = "Senha redefinida com sucesso! Redirecionando...";

                session_unset();
                session_destroy();

                header("refresh:2;url=login.php");

                $etapa_atual = 4;
            } else {
                $mensagem_erro = "Erro ao atualizar senha.";
            }

            $stmt->close();
        }
    }
}

// Garante etapa inicial
if (!isset($_SESSION['etapa_recuperacao'])) {
    $_SESSION['etapa_recuperacao'] = 1;
    $etapa_atual = 1;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Esqueci a Senha</title>
    <link rel="stylesheet" href="css/esqueciasenha.css">
</head>
<body>
    <div class="container-principal">
        <!-- Lado Esquerdo: Ilustração e Menu -->
        <div class="secao-esquerda">
            <nav class="menu-superior">
                <ul>
                    <li><a href="index.html">Início</a></li>
                    <li><a href="login.php" class="ativo">Login / Cadastre-se</a></li>
                    <li><a href="faleconosco.php">Fale conosco</a></li>
                </ul>
            </nav>
            
            <div class="conteudo-ilustracao">
                <img src="img/moca_pc.png" alt="Ilustração de uma pessoa trabalhando no computador" class="imagem-ilustracao">
            </div>

            <footer class="rodape-esquerdo">
                <span>Siga-nos!</span>
                <a href="#">@flashnotes</a>
                <a href="mailto:flahsnotes@email">flahsnotes@email</a>
            </footer>
        </div>

        <!-- Lado Direito: Formulário de Recuperação -->
        <div class="secao-direita">
            <div class="caixa-recuperacao">
                <div class="logo">
                    <img src="img/logo_completa_azul.png" alt="Logo" class="imagem-ilustracao">
                </div>
                
                <h2 class="titulo-recuperacao">Esqueci a senha</h2>

                <!-- Indicador de Etapas -->
                <div class="indicador-etapas">
                    <div class="etapa <?php echo ($etapa_atual >= 1) ? 'ativa' : ''; ?>">1</div>
                    <div class="linha-etapas <?php echo ($etapa_atual >= 2) ? 'ativa' : ''; ?>"></div>
                    <div class="etapa <?php echo ($etapa_atual >= 2) ? 'ativa' : ''; ?>">2</div>
                    <div class="linha-etapas <?php echo ($etapa_atual >= 3) ? 'ativa' : ''; ?>"></div>
                    <div class="etapa <?php echo ($etapa_atual >= 3) ? 'ativa' : ''; ?>">3</div>
                </div>

                <!-- Exibe mensagens de erro ou sucesso -->
                <?php if (!empty($mensagem_erro)): ?>
                    <div class="mensagem mensagem-erro">
                        <?php echo htmlspecialchars($mensagem_erro); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensagem_sucesso)): ?>
                    <div class="mensagem mensagem-sucesso">
                        <?php echo htmlspecialchars($mensagem_sucesso); ?>
                    </div>
                <?php endif; ?>

                <!-- ETAPA 1: Email -->
                <?php if ($etapa_atual == 1): ?>
                    <form action="esqueciasenha.php" method="POST" class="formulario-recuperacao">
                        <div class="campo-entrada">
                            <label for="email">EMAIL</label>
                            <input type="email" id="email" name="email_usuario" required>
                        </div>

                        <button type="submit" name="etapa1_email" class="botao-acao">Enviar Código</button>
                    </form>
                <?php endif; ?>

                <!-- ETAPA 2: Código de Verificação -->
                <?php if ($etapa_atual == 2): ?>
                    <form action="esqueciasenha.php" method="POST" class="formulario-recuperacao">
                        <p class="texto-etapa">Insira o código de verificação enviado para:</p>
                        <p class="email-confirmacao"><?php echo htmlspecialchars($email_recuperacao); ?></p>
                        
                        <div class="campo-entrada">
                            <label for="codigo">CÓDIGO</label>
                            <input type="text" id="codigo" name="codigo_recuperacao" placeholder="Insira o código de 6 dígitos" required>
                        </div>

                        <button type="submit" name="etapa2_codigo" class="botao-acao">Validar</button>
                    </form>
                    
                    <div class="links-auxiliares">
                        <p><a href="esqueciasenha.php" class="link-voltar">Voltar</a></p>
                    </div>
                <?php endif; ?>

                <!-- ETAPA 3: Nova Senha -->
                <?php if ($etapa_atual == 3): ?>
                    <form action="esqueciasenha.php" method="POST" class="formulario-recuperacao">
                        <div class="campo-entrada">
                            <label for="nova-senha">NOVA SENHA</label>
                            <input type="password" id="nova-senha" name="nova_senha_usuario" required>
                        </div>

                        <div class="campo-entrada">
                            <label for="confirmar-nova-senha">CONFIRMAR SENHA</label>
                            <input type="password" id="confirmar-nova-senha" name="confirmar_nova_senha_usuario" required>
                        </div>

                        <button type="submit" name="etapa3_senha" class="botao-acao">Salvar</button>
                    </form>
                <?php endif; ?>

                <!-- ETAPA 4: Conclusão -->
                <?php if ($etapa_atual == 4): ?>
                    <div class="conclusao-recuperacao">
                        <p class="texto-conclusao">✓ Sua senha foi redefinida com sucesso!</p>
                        <p class="texto-secundario">Você será redirecionado para o login em alguns segundos...</p>
                        <a href="login.php" class="botao-acao">Ir para Login</a>
                    </div>
                    <script>
                        setTimeout(function() {
                            window.location.href = 'login.php';
                        }, 3000);
                    </script>
                <?php endif; ?>

                <?php if ($etapa_atual < 4): ?>
                    <div class="links-auxiliares">
                        <p>Lembrou sua senha? <a href="login.php">Faça login aqui</a></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
