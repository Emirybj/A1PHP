<?php
$logado = isset($_SESSION['usuario']) && isset($_SESSION['senha']);
$nome_usuario_logado = $_SESSION['usuario'] ?? 'Usuário';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            Sua Playlist
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
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
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <?php if ($logado): ?>
                    <li class="nav-item">
                        <span class="navbar-text me-3">
                            Olá, <?php echo htmlspecialchars($nome_usuario_logado); ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger" href="logout.php">Sair</a>
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