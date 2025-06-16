<?php

session_start();

require_once 'lock.php';
require_once 'includes/conexao.php'; 

$mysqli = conectar_banco();

$mensagem = ''; 
$musica_id = $_GET['id'] ?? null; 
$usuario_id = $_SESSION['usuario_id'] ?? null; 

$musica_encontrada = null; 

// Verifica se um ID de música foi fornecido na URL e se o usuário está logado
if (empty($musica_id) || empty($usuario_id)) {
    header("Location: list_music.php?codigo=erro_id"); // Redireciona se ID ou usuário ausente
    exit();
}

// Executa esta parte apenas se o formulário AINDA NÃO foi submetido via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $stmt = $mysqli->prepare("SELECT id, titulo, descricao FROM musicas WHERE id = ? AND usuario_id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $musica_id, $usuario_id); // 'ii' para dois inteiros
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows === 1) {
            $musica_encontrada = $resultado->fetch_assoc();
        } else {
            // Música não encontrada ou não pertence ao usuário logado
            header("Location: list_music.php?codigo=nao_encontrada");
            exit();
        }
        $stmt->close();
    } else {
        $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query para buscar música: ' . $mysqli->error . '</div>';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $usuario_id !== null) {
    $musica_id = htmlspecialchars(trim($_POST['musica_id'] ?? '')); // Pega o ID escondido do formulário
    $titulo_atualizado = htmlspecialchars(trim($_POST['titulo'] ?? ''));
    $descricao_atualizada = htmlspecialchars(trim($_POST['descricao'] ?? ''));

    if (empty($titulo_atualizado) || empty($descricao_atualizada)) {
        $mensagem = '<div class="alert alert-danger" role="alert">Título e Descrição são campos obrigatórios!</div>';
        // Para exibir os valores antigos no formulário, é preciso re-buscar ou passar via hidden inputs
        $musica_encontrada = ['id' => $musica_id, 'titulo' => $titulo_atualizado, 'descricao' => $descricao_atualizada];
    } else {
        // Prepara a query para atualizar a música
        $stmt = $mysqli->prepare("UPDATE musicas SET titulo = ?, descricao = ? WHERE id = ? AND usuario_id = ?");

        if ($stmt) {
            // 'ssii' indica os tipos: string, string, integer, integer
            $stmt->bind_param("ssii", $titulo_atualizado, $descricao_atualizada, $musica_id, $usuario_id);

            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $mensagem = '<div class="alert alert-success" role="alert">Música "'. htmlspecialchars($titulo_atualizado) .'" atualizada com sucesso!</div>';
                    // Redireciona para a lista após o sucesso
                    header("Location: list_music.php?codigo=editado_sucesso");
                    exit();
                } else {
                    $mensagem = '<div class="alert alert-info" role="alert">Nenhuma alteração foi feita na música ou ela não existe.</div>';
                }
            } else {
                $mensagem = '<div class="alert alert-danger" role="alert">Erro ao atualizar música: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        } else {
            $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query de atualização: ' . $mysqli->error . '</div>';
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
    <title>Editar Música - Meu Sistema</title>
    
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
                        <h2 class="card-title text-center mb-4">Editar Música</h2>

                        <?php
                        // Exibe a mensagem de feedback
                        if (!empty($mensagem)) {
                            echo $mensagem;
                        }
                        ?>

                        <?php if ($musica_encontrada): // Somente exibe o formulário se a música foi encontrada ?>
                            <form action="edit_music.php?id=<?php echo htmlspecialchars($musica_encontrada['id']); ?>" method="post">
                                <input type="hidden" name="musica_id" value="<?php echo htmlspecialchars($musica_encontrada['id']); ?>">

                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título da Música:</label>
                                    <input type="text" name="titulo" id="titulo" 
                                           class="form-control" required autofocus 
                                           value="<?php echo htmlspecialchars($musica_encontrada['titulo']); ?>">
                                </div>

                                <div class="mb-4">
                                    <label for="descricao" class="form-label">Descrição (Artista, Álbum, Gênero, etc.):</label>
                                    <textarea name="descricao" id="descricao" class="form-control" rows="4" required><?php echo htmlspecialchars($musica_encontrada['descricao']); ?></textarea>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">Atualizar Música</button>
                                    <a href="list_music.php" class="btn btn-outline-secondary btn-lg">Cancelar e Voltar</a>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning" role="alert">
                                Música não encontrada ou você não tem permissão para editá-la.
                                <a href="list_music.php" class="alert-link">Voltar para Minhas Músicas</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>


