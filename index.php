<?php session_start();

require_once 'includes/functions.php';

$logado = isset($_SESSION['usuario']) && isset($_SESSION['senha']);
$nome_usuario_logado = $_SESSION['usuario'] ?? 'Usuário'; 

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Playlist de Músicas</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>  
</head>
<body>

    
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Plataforma de Música</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                </li>
                <?php if ($logado): ?>
                <li class="nav-item">
                <a class="nav-link" href="add_music.php">Adicionar Música</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="list_music.php">Minhas Músicas</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="logout.php">Sair</a>
                </li>
                <?php else: ?>
                <li class="nav-item">
                <a class="nav-link" href="login.php">Login</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="cadastro.php">Cadastre-se</a>
                </li>
                <?php endif; ?>
            </ul>
            </div>
        </div>
    </nav>

    <hr> 
    <?php
    // Exibe mensagens de feedback ao usuário (sucesso, erro, validação)
    // A função verificar_codigo() deve estar definida em functions.php e não deve usar estilos.
    verificar_codigo();
    ?>

    <?php if ($logado): ?>
        <!-- Conteúdo para usuários LOGADOS -->
        <h4>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario_logado); ?>!</h4>
        <p>Aqui você pode gerenciar suas músicas favoritas.</p>
        <p>
            <a href="add_music.php">Adicionar Nova Música</a> <br><br>
            <a href="list_music.php">Ver Minhas Músicas</a>
        </p>

    <?php else: ?>
        <!-- Conteúdo para usuários NÃO LOGADOS -->
        <h4>Bem-vindo(a)!</h4>
        <p>Por favor, faça login para acessar suas playlists ou cadastre-se se ainda não tiver uma conta.</p>
        <p>
            <a href="login.php">Fazer Login</a> <br><br>
            <a href="cadastro.php">Cadastre-se</a>
        </p>
    <?php endif; ?>

</body>
</html>
