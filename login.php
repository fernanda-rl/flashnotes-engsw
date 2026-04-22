<?php
// Lógica básica de backend para processar o login
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';

    // Exemplo simples de validação (em um sistema real, você consultaria um banco de dados)
    if ($email === "usuario@email.com" && $senha === "123456") {
        // Redirecionar para a home ou dashboard
        header("Location: index.php");
        exit();
    } else {
        $mensagem_erro = "E-mail ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Login </title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <header class="cabecalho">
        <nav class="navbar">
                <a href="index.html" id="ativo">Início</a>
                <a href="login.php"> Login / Cadastre-se</a>
                <a href="#">Fale conosco</a>
        </nav>
    </header>

    <main class="principal">
            <div id="espaco-imagem">
                <img src="img/moca_pc.png" alt="Ilustração de pessoa trabalhando" id="imagem">
                <footer class="footer">
                    <span> Siga-nos! </span>
                    <a href="#">@flashnotes</a>
                    <a href="mailto:flahsnotes@email"> flahsnotes@email </a>
                </footer> 
            </div>

           

            <div class="container-login">
                    <img src="img/logo_completa_azul.png" alt="Ilustração da logo do site" id="logo_c">
                
                <h1 class="titulo-login">Login</h1>

                <?php if ($mensagem_erro): ?>
                    <p class="mensagem-erro"><?php echo $mensagem_erro; ?></p>
                <?php endif; ?>

                <form action="login.php" method="POST" class="formulario-login">
                    <div class="campo-entrada">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="senha">SENHA</label>
                        <input type="password" id="senha" name="senha" re   quired>
                    </div>

                    <button type="submit" class="botao-entrar">Entrar</button>
                </form>

                <div class="links-auxiliares">
                    <p>Você não possui uma conta? Cadastre-se <a href="cadastro.php"> aqui</a></p>
                    <p>Você esqueceu sua senha? Clique <a href="esquecisenha.php"> aqui</a></p>
                </div>
            </div>
                    

    </main>
</body>
</html>
