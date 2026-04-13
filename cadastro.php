<?php
// Lógica básica de backend para processar o cadastro
$mensagem_sucesso = "";
$mensagem_erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $senha = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';

    // Validação simples
    if (empty($email) || empty($senha) || empty($confirmar_senha)) {
        $mensagem_erro = "Por favor, preencha todos os campos.";
    } elseif ($senha !== $confirmar_senha) {
        $mensagem_erro = "As senhas não coincidem.";
    } else {
        // Em um sistema real, aqui você salvaria no banco de dados
        $mensagem_sucesso = "Cadastro realizado com sucesso! Você já pode fazer login.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Cadastro</title>
    <link rel="stylesheet" href="css/cadastro.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&family=Pacifico&display=swap" rel="stylesheet">
</head>
<body>
    <header class="cabecalho">
        <nav class="navegacao">
            <ul class="lista-menu">
                <li><a href="index.html">Início</a></li>
                <li><a href="login.php" class="ativo">Login / Cadastre-se</a></li>
                <li><a href="#">Fale conosco</a></li>
            </ul>
        </nav>
    </header>

    <main class="conteudo-principal">
        <section class="secao-ilustracao">
            <div class="container-ilustracao">
                <img src="img/moca_pc.png" alt="Ilustração de pessoa trabalhando" class="imagem-ilustrativa">
            </div>
            <footer class="rodape-links">
                <span>Siga-nos!</span>
                <a href="#">@flashnotes</a>
                <a href="mailto:flahsnotes@email">flahsnotes@email</a>
            </footer>
        </section>

        <section class="secao-formulario">
            <div class="container-cadastro">
                <div class="logo-container">
                    <img src="img/logo_completa_azul.png" alt="Ilustração 3D de Bloco de Notas" id="logo_c">
                </div>
                
                <h1 class="titulo-cadastro">Cadastro</h1>

                <?php if ($mensagem_erro): ?>
                    <p class="mensagem-alerta erro"><?php echo $mensagem_erro; ?></p>
                <?php endif; ?>

                <?php if ($mensagem_sucesso): ?>
                    <p class="mensagem-alerta sucesso"><?php echo $mensagem_sucesso; ?></p>
                <?php endif; ?>

                <form action="cadastro.php" method="POST" class="formulario-cadastro">
                    <div class="campo-entrada">
                        <label for="email">EMAIL</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="senha">SENHA</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="confirmar_senha">CONFIRMAR SENHA</label>
                        <input type="password" id="confirmar_senha" name="confirmar_senha" required>
                    </div>

                    <button type="submit" class="botao-entrar">Entrar</button>
                </form>

                <div class="links-auxiliares">
                    <p>Você já possui uma conta? <a href="login.php">Entre aqui</a></p>
                </div>
            </div>
        </section>
    </main>
</body>
</html>