<?php session_start();
require_once 'includes/conexao.php';
require_once 'includes/functions.php';

$conn = conectar_banco();

$mensagem_erro = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário
    $usuario_ou_email = htmlspecialchars(trim($_POST['usuario'] ?? ''));
    $senha_digitada = $_POST['senha'] ?? '';

    if (campos_login_em_branco($usuario_ou_email, $senha_digitada)) {
        header("Location: login.php?codigo=2"); // Código 2 para campos em branco
        exit();
    } else {
        // --- Preparação da Query para Validação (Requisito 2 e Observações Gerais - Prepared Statements) ---
        // Agora usando $conn (o objeto de conexão) para preparar a declaração.
        $stmt = mysqli_prepare($conn, "SELECT id, nome_usuario, senha_hash FROM usuarios WHERE nome_usuario = ? OR email = ?");

        if ($stmt) {
            // 'ss' indica que ambos os parâmetros são strings
            mysqli_stmt_bind_param($stmt, "ss", $usuario_ou_email, $usuario_ou_email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt); // Armazena o resultado da query

            // Se um usuário foi encontrado (número de linhas > 0)
            if (mysqli_stmt_num_rows($stmt) === 1) {
                // Vincula as colunas do resultado a variáveis PHP
                mysqli_stmt_bind_result($stmt, $id, $nome_usuario_db, $senha_hash_db);
                mysqli_stmt_fetch($stmt); // Obtém os valores do resultado

                // --- Verificação da Senha (Requisito 2) ---
                if (password_verify($senha_digitada, $senha_hash_db)) {
                    // Login bem-sucedido!

                    // --- Início da Sessão (Requisito: Obrigatório o uso de sessões) ---
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario_nome'] = $nome_usuario_db;

                    // Ajustes para o lock.php funcionar como seu exemplo
                    $_SESSION['usuario'] = $nome_usuario_db;
                    $_SESSION['senha'] = $senha_digitada;

                    // Redireciona para a página principal após o login (agora para index.php)
                    header("Location: index.php");
                    exit();
                } else {
                    // Senha incorreta (Requisito 2 - Mensagem clara)
                    header("Location: login.php?codigo=1"); // Código 1 para usuário/senha inválidos
                    exit();
                }
            } else {
                // Usuário/e-mail não encontrado (Requisito 2 - Mensagem clara)
                header("Location: login.php?codigo=1"); // Código 1 para usuário/senha inválidos
                exit();
            }
            mysqli_stmt_close($stmt); // Fecha o prepared statement
        } else {
            // Erro na preparação da query
            header("Location: login.php?codigo=3"); // Código 3 para erro SQL interno
            exit();
        }
    }
}

// Fechando a conexão com o banco de dados.
// CORRIGIDO: Usando $conn->close() ou mysqli_close($conn).
mysqli_close($conn); // Ou $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Playlist de Músicas</title>
    <!-- REMOVIDO: Quaisquer links para CSS ou Bootstrap, conforme solicitado. -->
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <h2>Informe seus dados para acessar a Área Restrita</h2>

        <?php
        // Exibe mensagens de erro ou sucesso
        // A função verificar_codigo() deve estar definida em includes/functions.php.
        verificar_codigo();
        ?>

        <form action="login.php" method="post">
            <p>
                <label for="usuario">Usuário:</label><br>
                <input type="text" name="usuario" id="usuario" required>
            </p>

            <p>
                <label for="senha">Senha:</label><br>
                <input type="password" name="senha" id="senha" required>
            </p>

            <button type="submit">Logar</button> <!-- Botão simples, sem classes Bootstrap -->
        </form>
        <p style="margin-top: 15px; text-align: center;">
            Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se aqui!</a>
        </p>
    </div>
</body>
</html>
