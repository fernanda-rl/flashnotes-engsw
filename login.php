<?php
session_start();
require 'conexao.php';

$mensagem_erro = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email_digitado = $_POST['email_usuario'] ?? '';
    $senha_digitada = $_POST['senha_usuario'] ?? '';

    $email_digitado = filter_var($email_digitado, FILTER_SANITIZE_EMAIL);

    if (empty($email_digitado) || empty($senha_digitada)) {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    } else {

        // 🔎 Busca usuário no banco
        $sql = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email_digitado);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $usuario = $resultado->fetch_assoc();

            // 🔐 Verifica senha criptografada
            if (password_verify($senha_digitada, $usuario['senha_hash'])) {

                $_SESSION['usuario_logado'] = true;
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['email_usuario'] = $usuario['email'];

                header("Location: dashboard.php");
                exit();

            } else {
                $mensagem_erro = "Senha incorreta.";
            }

        } else {
            $mensagem_erro = "Usuário não encontrado.";
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
    <title>Flashnotes - Login</title>
    <link rel="stylesheet" href="css/login.css">
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

        <!-- Lado Direito: Formulário de Login -->
        <div class="secao-direita">
            <div class="caixa-login">
                <div class="logo">
                    <img src="img/logo_completa_azul.png" alt="Logo" class="imagem-ilustracao">
                </div>
                
                <h2 class="titulo-login">Login</h2>

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

                <form action="login.php" method="POST" class="formulario-login">
                    <div class="campo-entrada">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email_usuario" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="senha">SENHA</label>
                        <input type="password" id="senha" name="senha_usuario" required>
                    </div>

                    <button type="submit" class="botao-entrar">Entrar</button>
                </form>

                <div class="links-auxiliares">
                    <p>Você não possui uma conta? Cadastre-se <a href="cadastro.php">aqui</a></p>
                    <p>Você esqueceu sua senha? Clique <a href="esqueciasenha.php">aqui</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
