<?php

session_start();

require_once 'includes/conexao.php';
require_once 'includes/functions.php'; 

$conn = conectar_banco();


$usuario_ou_email_preencher = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta e sanitiza os dados do formulário
    $usuario_ou_email = htmlspecialchars(trim($_POST['usuario'] ?? ''));
    $senha_digitada = $_POST['senha'] ?? '';

    // Armazena o valor digitado para preencher o campo se houver erro
    $usuario_ou_email_preencher = $usuario_ou_email;

    if (campos_login_em_branco($usuario_ou_email, $senha_digitada)) {
        header("Location: login.php?codigo=2"); 
        exit();
    } else {
        
        $stmt = mysqli_prepare($conn, "SELECT id, nome_usuario, senha_hash FROM usuarios WHERE nome_usuario = ? OR email = ?");

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $usuario_ou_email, $usuario_ou_email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $id, $nome_usuario_db, $senha_hash_db);
                mysqli_stmt_fetch($stmt);

                if (password_verify($senha_digitada, $senha_hash_db)) {
                    $_SESSION['logado'] = true;
                    $_SESSION['usuario_id'] = $id;
                    $_SESSION['usuario_nome'] = $nome_usuario_db;

                    $_SESSION['usuario'] = $nome_usuario_db;
                    $_SESSION['senha'] = $senha_digitada; 

                    header("Location: index.php");
                    exit();
                } else {
                    header("Location: login.php?codigo=1"); // Senha incorreta
                    exit();
                }
            } else {
                header("Location: login.php?codigo=1"); // Usuário/e-mail não encontrado
                exit();
            }
            mysqli_stmt_close($stmt);
        } else {
            header("Location: login.php?codigo=3"); // Erro na preparação da query
            exit();
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
    <title>Login - Playlist de Músicas</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">

    </head>
<body>
    <?php
    require_once 'includes/header.php';
    ?>

    <main class="container mt-5"> <div class="row justify-content-center"> <div class="col-md-6 col-lg-5"> <div class="card shadow-lg"> <div class="card-body p-4"> <h2 class="card-title text-center mb-4">Acesse a Área Restrita</h2> <?php
                        verificar_codigo();
                        ?>

                        <form action="login.php" method="post">
                            <div class="mb-3"> <label for="usuario" class="form-label">Usuário ou E-mail:</label> <input type="text" name="usuario" id="usuario" class="form-control" required autofocus 
                                       value="<?php echo htmlspecialchars($usuario_ou_email_preencher); ?>"> </div>

                            <div class="mb-4"> <label for="senha" class="form-label">Senha:</label>
                                <input type="password" name="senha" id="senha" class="form-control" required>
                            </div>

                            <div class="d-grid"> <button type="submit" class="btn btn-primary btn-lg">Entrar</button> </div>
                        </form>
                        <p class="text-center mt-3 mb-0"> Ainda não tem uma conta? <a href="cadastro.php" class="text-decoration-none">Cadastre-se aqui!</a> </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>