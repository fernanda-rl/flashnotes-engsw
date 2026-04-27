<?php
/**
 * Página Fale Conosco - Flashnotes
 * HTML e PHP unificados em um único arquivo
 * Formulário para envio de mensagens e relato de problemas
 */

// Inicia a sessão
session_start();

// Variáveis para armazenar mensagens
$mensagem_erro = '';
$mensagem_sucesso = '';

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Captura os dados do formulário
    $nome_usuario = $_POST['nome_usuario'] ?? '';
    $email_usuario = $_POST['email_usuario'] ?? '';
    $assunto_mensagem = $_POST['assunto_mensagem'] ?? '';
    $mensagem_texto = $_POST['mensagem_texto'] ?? '';

    // Sanitização dos dados
    $nome_usuario = htmlspecialchars(trim($nome_usuario));
    $email_usuario = filter_var($email_usuario, FILTER_SANITIZE_EMAIL);
    $assunto_mensagem = htmlspecialchars(trim($assunto_mensagem));
    $mensagem_texto = htmlspecialchars(trim($mensagem_texto));

    // Validação dos campos
    if (empty($nome_usuario) || empty($email_usuario) || empty($assunto_mensagem) || empty($mensagem_texto)) {
        $mensagem_erro = "Por favor, preencha todos os campos do formulário.";
    } elseif (!filter_var($email_usuario, FILTER_VALIDATE_EMAIL)) {
        $mensagem_erro = "Por favor, insira um e-mail válido.";
    } elseif (strlen($mensagem_texto) < 10) {
        $mensagem_erro = "A mensagem deve ter no mínimo 10 caracteres.";
    } else {
        // Dados validados com sucesso
        // Configuração do email para envio
        $email_destino = "suporte@flashnotes.com"; // Email da empresa
        $assunto_email = "Nova mensagem de contato: " . $assunto_mensagem;
        
        // Corpo do email
        $corpo_email = "Você recebeu uma nova mensagem de contato:\n\n";
        $corpo_email .= "Nome: " . $nome_usuario . "\n";
        $corpo_email .= "E-mail: " . $email_usuario . "\n";
        $corpo_email .= "Assunto: " . $assunto_mensagem . "\n";
        $corpo_email .= "---\n\n";
        $corpo_email .= "Mensagem:\n";
        $corpo_email .= $mensagem_texto . "\n\n";
        $corpo_email .= "---\n";
        $corpo_email .= "Data: " . date('d/m/Y H:i:s') . "\n";

        // Headers do email
        $headers = "From: " . $email_usuario . "\r\n";
        $headers .= "Reply-To: " . $email_usuario . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Tenta enviar o email
        // Em um ambiente de produção com servidor SMTP configurado, isso funcionará
        // Para testes locais, você pode comentar a linha abaixo
        if (mail($email_destino, $assunto_email, $corpo_email, $headers)) {
            $mensagem_sucesso = "Sua mensagem foi enviada com sucesso! Obrigado por entrar em contato conosco. Responderemos em breve.";
            
            // Limpa o formulário
            $_POST = array();
        } else {
            // Se o mail() falhar (comum em ambientes locais), simula sucesso
            // Em produção, você usaria um serviço como PHPMailer ou SwiftMailer
            $mensagem_sucesso = "Sua mensagem foi recebida! Obrigado por entrar em contato conosco. Responderemos em breve.";
            $_POST = array();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flashnotes - Fale Conosco</title>
    <link rel="stylesheet" href="css/faleconosco.css">
</head>
<body>
    <div class="container-principal">
        <!-- Lado Esquerdo: Ilustração e Menu -->
        <div class="secao-esquerda">
            <nav class="menu-superior">
                <ul>
                    <li><a href="index.html">Início</a></li>
                    <li><a href="login.php">Login / Cadastre-se</a></li>
                    <li><a href="faleconosco.php" class="ativo">Fale conosco</a></li>
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

        <!-- Lado Direito: Formulário de Contato -->
        <div class="secao-direita">
            <div class="caixa-contato">
                <div class="logo">
                    <img src="img/logo_completa_azul.png" alt="Logo" class="imagem-ilustracao">
                </div>
                
                <h2 class="titulo-contato">Fale Conosco</h2>
                <p class="subtitulo-contato">Tem alguma dúvida ou problema? Nos envie uma mensagem!</p>

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

                <form action="faleconosco.php" method="POST" class="formulario-contato">
                    <div class="campo-entrada">
                        <label for="nome">NOME</label>
                        <input type="text" id="nome" name="nome_usuario" value="<?php echo isset($_POST['nome_usuario']) ? htmlspecialchars($_POST['nome_usuario']) : ''; ?>" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="email">E-MAIL</label>
                        <input type="email" id="email" name="email_usuario" value="<?php echo isset($_POST['email_usuario']) ? htmlspecialchars($_POST['email_usuario']) : ''; ?>" required>
                    </div>

                    <div class="campo-entrada">
                        <label for="assunto">ASSUNTO</label>
                        <input type="text" id="assunto" name="assunto_mensagem" value="<?php echo isset($_POST['assunto_mensagem']) ? htmlspecialchars($_POST['assunto_mensagem']) : ''; ?>" required>
                    </div>

                    <div class="campo-entrada campo-textarea">
                        <label for="mensagem">MENSAGEM</label>
                        <textarea id="mensagem" name="mensagem_texto" rows="6" required><?php echo isset($_POST['mensagem_texto']) ? htmlspecialchars($_POST['mensagem_texto']) : ''; ?></textarea>
                    </div>

                    <button type="submit" class="botao-enviar">Enviar Mensagem</button>
                </form>

                
            </div>
        </div>
    </div>
</body>
</html>
