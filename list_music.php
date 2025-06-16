<?php
session_start(); 
require_once 'lock.php'; 

require_once 'includes/conexao.php'; 
$mysqli = conectar_banco(); 

$usuario_id = $_SESSION['usuario_id'] ?? null;
$musicas = [];
$mensagem = '';

if (empty($usuario_id)) {
    $mensagem = '<div class="alert alert-danger" role="alert">Erro: ID do usuário não encontrado na sessão. Por favor, faça login novamente.</div>';
} else {
    $stmt = $mysqli->prepare("SELECT id, titulo, descricao, data_cadastro FROM musicas WHERE usuario_id = ? ORDER BY data_cadastro DESC");

    if ($stmt) {
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows > 0) {
            while ($musica = $resultado->fetch_assoc()) {
                $musicas[] = $musica;
            }
        } else {
            $mensagem = '<div class="alert alert-info" role="alert">Nenhuma música cadastrada ainda. <a href="add_music.php" class="alert-link">Adicionar agora!</a></div>';
        }
        $stmt->close();
    } else {
        $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query para listar músicas: ' . htmlspecialchars($mysqli->error) . '</div>';
    }
}

if ($mysqli) { 
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Músicas - Meu Sistema</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">
</head>
<body>
    <?php
    require_once 'includes/header.php';
    ?>

    <main class="container mt-5">
        <h1 class="text-center mb-4">Minhas Músicas</h1>

        <?php
        if (!empty($mensagem)) {
            echo $mensagem;
        }
        ?>

        <?php if (!empty($musicas)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Título</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Data de Cadastro</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($musicas as $index => $musica): ?>
                            <tr>
                                <th scope="row"><?php echo $index + 1; ?></th>
                                <td><?php echo htmlspecialchars($musica['titulo']); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($musica['descricao'])); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($musica['data_cadastro'])); ?></td>
                                <td>
                                    <a href="edit_music.php?id=<?php echo $musica['id']; ?>" class="btn btn-sm btn-warning me-2">Editar</a>
                                    <a href="delete_music.php?id=<?php echo $musica['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta música?');">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="text-center mt-4">
                <a href="add_music.php" class="btn btn-success btn-lg">Adicionar Nova Música</a>
            </div>

        <?php endif; ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>