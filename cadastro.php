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