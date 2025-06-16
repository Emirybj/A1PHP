<?php

session_start(); 

require_once 'lock.php'; 
require_once 'includes/conexao.php'; 

$mysqli = conectar_banco(); 

$mensagem = ''; // Armazena mensagens de feedback do usuário.
$titulo_preencher = '';
$descricao_preencher = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = htmlspecialchars(trim($_POST['titulo'] ?? ''));
    $descricao = htmlspecialchars(trim($_POST['descricao'] ?? ''));

    $usuario_id = $_SESSION['usuario_id'] ?? null;

    // Para preencher os campos se houver erro (melhora a UX)
    $titulo_preencher = $titulo;
    $descricao_preencher = $descricao;

    if (empty($titulo) || empty($descricao)) {
        // Mensagem mais adequada para campos vazios
        $mensagem = '<div class="alert alert-warning" role="alert">Erro: Por favor, preencha todos os campos obrigatórios (Título e Descrição).</div>';
    } elseif (empty($usuario_id)) {
        // Esta condição não deveria ser atingida se lock.php funcionar, mas é uma segurança.
        $mensagem = '<div class="alert alert-danger" role="alert">Erro: ID do usuário não encontrado na sessão. Por favor, faça login novamente.</div>';
    } else {
        // Prepara para inserir nova música
        $stmt = $mysqli->prepare("INSERT INTO musicas (titulo, descricao, usuario_id) VALUES (?, ?, ?)");

        if ($stmt) {
            $stmt->bind_param("ssi", $titulo, $descricao, $usuario_id);

            if ($stmt->execute()) {
                // Corrigido 'sucess' para 'success' e ajustada a mensagem
                $mensagem = '<div class="alert alert-success" role="alert">Música "'. htmlspecialchars($titulo) . '" adicionada com sucesso!</div>'; 
                // Limpa os campos do formulário se der certo.
                $titulo_preencher = '';
                $descricao_preencher = '';
            } else {
                $mensagem = '<div class="alert alert-danger" role="alert">Erro ao adicionar música: ' . htmlspecialchars($stmt->error) . '</div>';
            }
            $stmt->close();
        } else {
            $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query: ' . htmlspecialchars($mysqli->error) . '</div>';
        }
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
    <title>Adicionar Música - Meu Sistema</title>
    
    <link rel="stylesheet" href="css/bootstrap.min.css">

</head>
<body>
    <?php
    require_once 'includes/header.php';
    ?>

    <main class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <h2 class="card-title text-center mb-4">Adicionar Nova Música</h2>
                        <?php
                        // Exibe a mensagem de feedback (sucesso ou erro)
                        if (!empty($mensagem)) {
                            echo $mensagem;
                        }
                        ?>

                        <form action="add_music.php" method="post">
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título da Música:</label>
                                <input type="text" name="titulo" id="titulo" 
                                       class="form-control" required autofocus 
                                       value="<?php echo htmlspecialchars($titulo_preencher); ?>">
                            </div>

                            <div class="mb-4">
                                <label for="descricao" class="form-label">Descrição (Artista, Álbum, Gênero, etc.):</label>
                                <textarea name="descricao" id="descricao" class="form-control" rows="4" required><?php echo htmlspecialchars($descricao_preencher); ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">Salvar Música</button>
                                <a href="list_music.php" class="btn btn-outline-secondary btn-lg">Ver Minhas Músicas</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>