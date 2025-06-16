<?php require_once 'includes/conexao.php'; 

require_once 'includes/functions.php';
$conn = conectar_banco();

$nome_usuario_preencher = '';
$email_preencher = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário para evitar injeção de código (XSS)
    $nome_usuario = htmlspecialchars(trim($_POST['nome_usuario'] ?? ''));
    $senha_digitada = $_POST['senha'] ?? '';
    $confirmar_senha = $_POST['confirmar_senha'] ?? '';
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));

    if (empty($nome_usuario) || empty($senha_digitada) || empty($confirmar_senha) || empty($email)) {
        // Redireciona com um código de erro para campos obrigatórios
        header("Location: cadastro.php?codigo=2"); // preenche todos os campos
        exit();
    } elseif ($senha_digitada !== $confirmar_senha) {
        // Redireciona com um código de erro se as senhas não coincidirem
        header("Location: cadastro.php?codigo=senhas_nao_coincidem");
        exit();
    } elseif (strlen($senha_digitada) < 6) { // Exemplo: senha mínima de 6 caracteres
        // Redireciona com um código de erro para senha muito curta
        header("Location: cadastro.php?codigo=senha_curta"); 
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Redireciona com um código de erro para formato de e-mail inválido
        header("Location: cadastro.php?codigo=email_invalido"); 
        exit();
    } else {
        // password_hash() cria um hash seguro da senha.
        $senha_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);

        // Prepara a declaração SQL para inserir um novo usuário.
        $stmt = mysqli_prepare($conn, "INSERT INTO usuarios (nome_usuario, senha_hash, email) VALUES (?, ?, ?)");

        // Verifica se a preparação da query foi bem-sucedida
        if ($stmt) {
            // Vincula os parâmetros à query. 'sss' indica que todos são strings.
            mysqli_stmt_bind_param($stmt, "sss", $nome_usuario, $senha_hash, $email);

            // Tenta executar a query
            if (mysqli_stmt_execute($stmt)) {
                // Redireciona para a página de login com um código de sucesso
                header("Location: login.php?codigo=10"); // Código 10: Operação realizada com sucesso
                exit();
            } else {
                // Erro ao executar a inserção (ex: usuário ou e-mail já existem, pois são UNIQUE)
                if (mysqli_errno($conn) === 1062) { // Código de erro para chave duplicada
                    header("Location: cadastro.php?codigo=usuario_email_existente"); 
                    exit();
                } else {
                    header("Location: cadastro.php?codigo=3"); 
                    exit();
                }
            }
            mysqli_stmt_close($stmt); // Fecha o prepared statement
        } else {
            header("Location: cadastro.php?codigo=3");
        }
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Playlist de Músicas</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">

    </head>
<body>
    <?php
    require_once 'includes/header.php';
    ?>

    <main class="container mt-5"> <div class="row justify-content-center"> <div class="col-md-7 col-lg-5"> <div class="card shadow-lg"> <div class="card-body p-4"> <h2 class="card-title text-center mb-4">Cadastre-se para Acessar</h2> <?php
                        // exibe mensagens (erros/sucesso)
                        verificar_codigo();
                        ?>

                        <form action="cadastro.php" method="post">
                            <div class="mb-3"> <label for="nome_usuario" class="form-label">Nome de Usuário:</label> <input type="text" name="nome_usuario" id="nome_usuario" 
                                       class="form-control" required autofocus 
                                       value="<?php echo htmlspecialchars($nome_usuario_preencher); ?>"> </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" name="email" id="email" 
                                       class="form-control" required 
                                       value="<?php echo htmlspecialchars($email_preencher); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>

                            <div class="mb-4"> <label for="confirmar_senha" class="form-label">Confirmar Senha:</label>
                                <input type="password" name="confirmar_senha" id="confirmar_senha" class="form-control" required>
                            </div>

                            <div class="d-grid"> <button type="submit" class="btn btn-success btn-lg">Cadastrar</button> </div>
                        </form>
                        <p class="text-center mt-3 mb-0"> Já tem uma conta? <a href="login.php" class="text-decoration-none">Faça login aqui!</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>