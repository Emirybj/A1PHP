<?php
require_once 'lock.php';
require_once 'conexao.php'; 

$usuario_id = $_SESSION['usuario_id'] ?? null; // Obtém o ID do usuário logado da sessão
$musicas = []; // Array para armazenar as músicas do usuário
$mensagem = ''; // Variável para mensagens de feedback (ex: nenhuma música encontrada)

// Verifica se o ID do usuário está disponível na sessão
if (empty($usuario_id)) {
    $mensagem = '<div class="alert alert-danger" role="alert">Erro: ID do usuário não encontrado na sessão. Por favor, faça login novamente.</div>';
} else {
    // Seleciona todas as músicas associadas ao ID do usuário logado e 'ORDER BY' para exibir as músicas mais recentes
    $stmt = $mysqli->prepare("SELECT id, titulo, descricao, data_cadastro FROM musicas WHERE usuario_id = ? ORDER BY data_cadastro DESC");

    if ($stmt) {
        // Vincula o parâmetro: 'i' indica que é um integer (ID do usuário)
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result(); // Obtém o resultado da query

        // Verifica se há músicas cadastradas
        if ($resultado->num_rows > 0) {
            // Itera sobre os resultados e armazena cada música no array $musicas
            while ($musica = $resultado->fetch_assoc()) {
                $musicas[] = $musica;
            }
        } else {
            $mensagem = '<div class="alert alert-info" role="alert">Nenhuma música cadastrada ainda. <a href="add_music.php">Adicionar agora!</a></div>';
        }
        $stmt->close(); // Fecha o prepared statement
    } else {
        // Erro na preparação da query
        $mensagem = '<div class="alert alert-danger" role="alert">Erro na preparação da query para listar músicas: ' . $mysqli->error . '</div>';
    }
}

$mysqli->close();
?>