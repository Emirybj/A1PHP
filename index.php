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
</head>
<body>

    <h1>Plataforma de Música</h1>

    <nav>
        <a href="index.php">Home</a> |
        <?php if ($logado): ?>
            <a href="add_music.php">Adicionar Música</a> |
            <a href="list_music.php">Minhas Músicas</a> |
            <a href="logout.php">Sair</a>
        <?php else: ?>
            <a href="login.php">Login</a> |
            <a href="cadastro.php">Cadastre-se</a>
        <?php endif; ?>
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
