<?php
session_start();

require_once 'includes/functions.php'; 
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Playlist de Músicas - Página Inicial</title> <link rel="stylesheet" href="css/bootstrap.min.css">

    </head>
<body>
    <?php
    
    require_once 'includes/header.php';
    ?>

    <main class="container mt-4"> <h1 class="text-center text-primary mb-4">Minha Playlist de Músicas</h1>

        <?php verificar_codigo(); ?>

        <?php if ($logado): ?>
            <div class="alert alert-success" role="alert">
                <h4>Bem-vindo(a), <?php echo htmlspecialchars($nome_usuario_logado); ?>!</h4>
                <p>Aqui você pode gerenciar suas músicas favoritas.</p>
            </div>
            <div class="d-grid gap-2"> <a href="add_music.php" class="btn btn-primary btn-lg mb-2">Adicionar Nova Música</a>
                <a href="list_music.php" class="btn btn-secondary btn-lg">Ver Minhas Músicas</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                <h4>Bem-vindo(a)!</h4>
                <p>Por favor, faça login para acessar suas playlists ou cadastre-se se ainda não tiver uma conta.</p>
            </div>
            <div class="d-grid gap-2">
                <a href="login.php" class="btn btn-success btn-lg mb-2">Fazer Login</a>
                <a href="cadastro.php" class="btn btn-outline-success btn-lg">Cadastre-se</a>
            </div>
        <?php endif; ?>

    </main> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>