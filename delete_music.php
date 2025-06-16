<?php

session_start();

require_once 'lock.php';
require_once 'includes/conexao.php'; 

$mysqli = conectar_banco();

$musica_id = $_GET['id'] ?? null; 
$usuario_id = $_SESSION['usuario_id'] ?? null; 

$mensagem_delete = '';

// Verifica se um ID de música foi fornecido e se o ID do usuário está na sessão
if (empty($musica_id) || empty($usuario_id)) {
    // Redireciona com uma mensagem de erro se o ID for inválido ou ausente
    header("Location: list_music.php?status=erro_id");
    exit();
}
// Prepara a declaração SQL para deletar a música, tem que incluir `usuario_id = ?` para que um usuário não possa deletar a música de outro.
$stmt = $mysqli->prepare("DELETE FROM musicas WHERE id = ? AND usuario_id = ?");

if ($stmt) {
    // Vincula os parâmetros: 'ii' indica que ambos são inteiros (ID da música, ID do usuário)
    $stmt->bind_param("ii", $musica_id, $usuario_id);

    // Tenta executar a query
    if ($stmt->execute()) {
        // Verifica se alguma linha foi afetada (se a música foi realmente excluída)
        if ($stmt->affected_rows > 0) {
            $mensagem_delete = "sucesso"; // Indica sucesso
        } else {
            // Nenhuma linha afetada: música não encontrada ou não pertence ao usuário
            $mensagem_delete = "nao_encontrada";
        }
    } else {
        // Erro na execução da query
        $mensagem_delete = "erro_execucao";
    }
    $stmt->close(); // Fecha o prepared statement
} else {
    // Erro na preparação da query
    $mensagem_delete = "erro_preparacao";
}

// Fecha a conexão com o banco de dados
if ($mysqli) {
    $mysqli->close();
}

// Redireciona de volta para a página de listagem de músicas com uma mensagem de status
header("Location: list_music.php?status=" . $mensagem_delete);
exit();

?>