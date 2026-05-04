<?php
/**
 * Página de Cadastro - Flashnotes
 * HTML e PHP unificados em um único arquivo
 */

// Inicia a sessão para armazenar dados do usuário
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

// Variáveis para armazenar mensagens de erro/sucesso
$mensagem_erro = '';
$mensagem_sucesso = '';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados do formulário
    $email_novo = $_POST['email_usuario'] ?? '';
    $senha_nova = $_POST['senha_usuario'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha_usuario'] ?? '';

    // Limpeza básica de dados (Sanitização)
    $email_novo = filter_var($email_novo, FILTER_SANITIZE_EMAIL);

    // Validação básica
    if (empty($email_novo) || empty($senha_nova) || empty($confirmar_senha)) {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    } elseif (!filter_var($email_novo, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "Por favor, insira um e-mail válido.";
    } elseif (strlen($senha_nova) < 6) {
        $mensagem_erro = "A senha deve ter no mínimo 6 caracteres.";
    } elseif ($senha_nova !== $confirmar_senha) {
        $mensagem_erro = "As senhas não coincidem. Tente novamente.";
    } else {
    // Verifica se o e-mail já existe
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email_novo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $mensagem_erro = "Este e-mail já está cadastrado.";
    } else {
        // Criptografa a senha
            $senha_hash = password_hash($senha_nova, PASSWORD_DEFAULT);

            // Insere no banco
            $sql = "INSERT INTO usuarios (nome, email, senha_hash, tipo_perfil) VALUES (?, ?, ?, 'estudante')";
            $stmt = $conn->prepare($sql);

            $nome = "Usuário"; // depois você pode pegar isso do formulário

            $stmt->bind_param("sss", $nome, $email_novo, $senha_hash);

            if ($stmt->execute()) {
                $mensagem_sucesso = "Cadastro realizado com sucesso! Agora faça login.";
            } else {
                $mensagem_erro = "Erro ao cadastrar: " . $conn->error;
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
</head>
<body>
    <div class="container-principal">
        <!-- Lado Esquerdo: Ilustração e Menu -->
        <div class="secao-esquerda">
            <nav class="menu-superior">
                <ul>
                    <li><a href="index.html">Início</a></li>
                    <li><a href="#" class="ativo">Login / Cadastre-se</a></li>
                    <li><a href="#">Fale conosco</a></li>
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

        <!-- Lado Direito: Formulário de Cadastro -->
        <div class="secao-direita">
            <div class="caixa-cadastro">
                <div class="logo">
                    <img src="img/logo_completa_azul.png" alt="Logo" class="imagem-ilustracao">
                </div>
                
                <h2 class="titulo-cadastro">Cadastro</h2>

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

                <form action="cadastro.php" method="POST" class="formulario-cadastro">
                    <div class="campo-entrada">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email_usuario" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="senha">SENHA</label>
                        <input type="password" id="senha" name="senha_usuario" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="confirmar-senha">CONFIRMAR SENHA</label>
                        <input type="password" id="confirmar-senha" name="confirmar_senha_usuario" required>
                    </div>

                    <button type="submit" class="botao-entrar">Entrar</button>
                </form>

                <div class="links-auxiliares">
                    <p>Você já possui uma conta? Entre <a href="login.php">aqui</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>